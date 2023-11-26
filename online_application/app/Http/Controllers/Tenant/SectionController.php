<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Application\FieldsHelper;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Application;
use App\Tenant\Models\Field;
use App\Tenant\Models\Section;
use Illuminate\Http\Request;
use Response;
use Storage;

class SectionController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $application = Application::bySlug($request->application)->first();

        return view('back.applications.sections.create', compact('application'));
    }

    public function store(Request $request)
    {
        $icon = ($request->hasFile('icon')) ? Storage::putFile('/'.session('tenant'), $request->file('icon')) : '';

        $section = new Section();
        $section->title = $request->title;
        $section->properties = [
        'label'             => $request->properties_label,
        'color'             => $request->properties_color,
        'text_color'        => $request->properties_text_color,
        'icon'              => $icon,
        'description'       => $request->properties_description,
        ];

        $application = Application::find($request->application_id);

        if ($section->save()) {
            // Add new Section to Application Section Order
            $this->updateSectionsOrder($application, $section->id, true)->save();

            $html = view('back.applications._partials.section', compact('section', 'application'))->render();

            $application->sections()->attach($section);

            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
                ]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Section $section, Request $request)
    {
        $route = 'sections.update';

        return view('back.applications.sections.edit', compact('section', 'route'));
    }

    public function update(Request $request, Section $section)
    {
        $icon = (
            $request->hasFile('icon')
        ) ? Storage::putFile('/'.session('tenant'), $request->file('icon')) : false;

        $properties = [];
        $properties['label'] = $request->properties_label;

        if ($icon && ! empty(trim($icon))) {
            $properties['icon'] = $icon;
        } else {
            $properties['icon'] = (isset($section->properties['icon'])) ? $section->properties['icon'] : null;
        }

        $section->title = $request->title;
        $section->properties = $properties;

        if ($section->save()) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['title'  => $section->title, 'sectionId' => $section->id],
                ]);
        } else {
        }
    }

    public function destroy(Section $section, Request $request)
    {
        $application = Application::find($request->application);

        //Update Sections Order
        $application = $this->updateSectionsOrder($application, $section->id, false);
        $application->save();

        if ($response = optional($section)->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $section->id],
            ]);
        } else {
            return Response::json([
               'status'    => 404,
               'response'  => $response,
            ]);
        }
    }

    public function fieldsOrder($payload)
    {
        $section = Section::find($payload['section']);
        $section->fields_order = $payload['order'];

        if ($section->save()) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['fields_order'  => $section->fields_order],
            ]);
        }
    }

    protected function updateSectionsOrder($application, $sectionId, $add = true)
    {
        $sectionsOrder = $application->sections_order;

        if (! is_array($sectionsOrder)) {
            $sectionOrder = [];
        }
        // Add Filed
        if ($add) {
            if (is_array($sectionsOrder) && !in_array($sectionId, $sectionsOrder)) {
                array_push($sectionsOrder, $sectionId);
            }
        } else {
            // @Todo Remove Field
            $sectionsOrder = array_diff($sectionsOrder, [$sectionId]);
        }

        $application->sections_order = $sectionsOrder;

        return $application;
    }

    public function clone(Section $old_section, Application $application)
    {
        $sections_changes = [];
        $fields_changes = [];
        $sections_order = $application->sections_order;

        $application->load('sections.fields', 'PaymentGateways', 'integrations', 'actions');
        $old_section->load('fields');

        $new_section = $old_section->replicate();
        $new_section->save();

        $sections_changes[$old_section->id] = $new_section->id;
        $application->sections()->save($new_section);
        $sections_order[] = $new_section->id;

        $replicates_fields = $this->replicate($new_section, 'fields');

        foreach ($replicates_fields as $field_id) {
            $field = Field::findOrFail($field_id);
            $field->section()->associate($new_section);
            $field->name = $this->getUniqueFieldName($field->name);
            $field->save();
        }

        $fields_order = $this->updateFieldsOrder($replicates_fields, $old_section->fields_order);

        $new_section->fields_order = $fields_order;

        $new_section->save();

        //Re select the object because i cant change the relation after clone and keep the old one
        // and solved take the object again from database
        $section = Section::find($new_section->id);

        $application->sections_order = $sections_order;

        if ($application->save()) {
            $html = view('back.applications._partials.section', compact('section', 'application'))->render();

            return Response::json([
                  'status'    => 200,
                  'response'  => 'success',
                  'extra'     => ['html'  => $html],
              ]);
        }
    }

    protected function replicate(
        $model,
        $relation,
        $sections_changes = null,
        $fields_changes = null
    ): array {
        $changes = [];
        foreach ($model->$relation as $item) {
            $newRelation = $item->replicate();

            if ($relation == 'PaymentGateways') {
                $this->fixCloneOrder($item, $newRelation, $sections_changes, 'section_id');
                $this->fixCloneOrder($item, $newRelation, $fields_changes, 'field_id');
            }

            $e = strtolower((new \ReflectionClass($model))->getShortName()).'_id';
            $newRelation->$e = $model->id;
            $newRelation->save();
            $changes[$item->id] = $newRelation->id;
        }

        return $changes;
    }

    /**
     * Update section_id and field_id in payment_gateways table
     * @param $original_model
     * @param $clone_model
     * @param $elements
     * @param $element_field
     */
    protected function fixCloneOrder($original_model, $clone_model, $elements, $element_field)
    {
        if ($original_model->$element_field) {
            $clone_model->$element_field = $elements[$original_model->$element_field];
        }
    }

    /**
     * @param array $fields_changes
     * @param $fields_order
     * @return mixed
     */
    protected function updateFieldsOrder(array $fields_changes, $fields_order)
    {
        foreach ($fields_changes as $key => $value) {
            $fields_order[array_search($key, $fields_order)] = $value;
        }

        return $fields_order;
    }

    protected function getUniqueFieldName($name)
    {
        if ($field_name = $name) {
            $field_name = FieldsHelper::getFieldName($name);
        } else {
            $field_name = FieldsHelper::getFieldName($name);
        }
        //dd($field_name);
        return $field_name;
    }
}
