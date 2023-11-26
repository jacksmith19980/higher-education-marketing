<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Application\FieldsHelper;
use App\Helpers\customfield\CustomFieldHelper;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Application;
use App\Tenant\Models\Field;
use App\Tenant\Models\Section;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Http\Request;
use Response;

class FieldController extends Controller
{
    use Integratable;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $field = new Field();
        $type = $request->type;
        $field_type = $request->field_type;

        $application = Application::where('slug', $request->application)->with(['sections'])->first();

        $sections = $application->sections;

        $settings = Setting::byGroup();
        $contactTypes = [];
        if (isset($settings['applications']['contact_type'])) {
            $contactTypes = $settings['applications']['contact_type'];
        }

        $integrable = $this->inetgration();

        $route = 'fields.store';

        return view(
            'back.applications.fields.'.$type.'.form',
            compact('field', 'field_type', 'type', 'sections', 'route', 'application', 'contactTypes', 'integrable')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->fieldValidationRules($request));
        // Get the Section
        $section = Section::find($request->section);

        $application = $section->applications()->first();

        $field = new Field();

        $field->label = $request->title;
        $field->object = $request->object;
        $field->field_type = $request->field_type;

        $field->name = $this->getUniqueFieldName($request->name);

        if ($field = $this->saveFieldData($request['properties'], $field, $section, $request)) {
            $html = view('back.applications._partials.field', compact('field', 'section', 'application'))->render();

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html' => $html, 'section_id' => $section->id],
            ]);
        }
    }

    protected function fieldValidationRules(Request $request)
    {
        $rules = [
            'title'     => 'required',
           /* 'name'      => 'required', */
            'section'   => 'required',
        ];

        return $rules;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Field $field, Request $request)
    {
        $type = $request->type;
        $field_type = $request->field_type;

        $application = Application::where('slug', $request->application)->with('sections')->first();

        $sections = $application->sections;

        $settings = Setting::byGroup();
        $contactTypes = [];
        if (isset($settings['applications']['contact_type'])) {
            $contactTypes = $settings['applications']['contact_type'];
        }

        $integrable = $this->inetgration();

        $route = 'fields.update';

        return view(
            'back.applications.fields.edit',
            compact('field', 'type', 'field_type', 'application', 'sections', 'route', 'contactTypes', 'integrable')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get the Field
        $field = Field::find($id);

        //Section
        $section = Section::find($request->section);
        $application = $section->applications()->first();

        // Check if Section is updated ....
        if ($field->section->id != $request->section) {
            // Remove the field from Old Section
            $oldSection = $this->updateFieldsOrder($field->section, $field->id, false);
            $oldSection->save();
        }

        $field->label = $request->title;
        $field->object = $request->object;
        $field->field_type = $request->field_type;

        //Get Unique Field Name
        if (! $field_name = $request->name) {
            //Get Unique Field Name
            $field_name = FieldsHelper::getFieldName($request->title);
        }
        $field->name = $field_name;

        if ($field = $this->saveFieldData($request['properties'], $field, $section, $request)) {
            // Update Repeater
            if ($field->properties['type'] == 'loop') {
                $this->updateRepeatedFields($field->properties['fields'], $section, $field->id, true);
            }

            $html = view('back.applications._partials.field', compact('field', 'section', 'application'))->render();

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html' => $html, 'section_id' => $section->id],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $field = Field::where('id', $id)->where('section_id', $request->section)->first();

        $section = Section::find($request->section);
        $section = $this->updateFieldsOrder($section, $field->id, false);
        $section->save();

        // reset Repeated fields
        if ($field->properties['type'] == 'loop') {
            $this->resetRepeatedFields($field->id);
        }

        if ($response = optional($field)->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    /**
     * Return Custom List view to the ajax request
     * @return [type] [description]
     */
    public function list($payload)
    {
        if ($html = view('back.applications.fields.list.empty_list_items', ['order' => $payload['order']])->render()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html' => $html],
            ]);
        }
    }

    /**
     * Return Checkbox view to the ajax request
     * @return [type] [description]
     */
    public function checkbox($payload)
    {
        if ($html = view('back.applications.fields.checkbox.checkbox-options', ['order' => $payload['order']])->render()) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html' => $html],
            ]);
        }
    }

    /**
     * Return Radio view to the ajax request
     * @return [type] [description]
     */
    public function radio($payload)
    {
        if ($html = view('back.applications.fields.radio.radio-options', ['order' => $payload['order']])->render()) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html' => $html],
            ]);
        }
    }

    /**
     * Return Addon view to the ajax request
     * @return [type] [description]
     */
    public function addon($payload)
    {
        if ($html = view('back.applications.fields.addon.checkbox-options', ['order' => $payload['order']])->render()) {
            return Response::json([
                                      'status'    => 200,
                                      'response'  => 'success',
                                      'extra'     => ['html' => $html],
                                  ]);
        }
    }

    public function saveFieldData($properties, $field, $section, $request)
    {
        // To be updated
        if (isset($request->data)) {
            if ($request->data != 'custom_list') {
                if (($request->properties['isCustomized'] == 'Yes') || $request->data == 'course' || (strpos($request->data, 'course') !== false) || (strpos($request->data, 'courses') !== false)) {
                    $custom_data = $request->custom_data;
                    $data = FieldsHelper::getCustomListData($request->custom_data);
                } else {
                    $data = FieldsHelper::getListData($request->data);
                }
            } else {
                $data = FieldsHelper::getCustomListData($request->custom_data);
            }

            //$data = FieldsHelper::getCustomListData($request->custom_data);
        } else {
            $data = [];
        }

        $field->data = $data;

        // attache To Section
        $field->section()->associate($section);

        if ($properties) {
            // Get the Values in array
            $field->properties = $this->getPropertiesValues($properties);
        }

        if ($field->save()) {
            // Update Section Order Add the new field at the end.
            $this->updateFieldsOrder($section, $field->id, true)->save();

            // Update Repeated Fields
            if ($field->properties['type'] == 'loop') {
                $this->updateRepeatedFields($field->properties['fields'], $section, $field->id, false);
            }

            return $field;
        }
    }

    /**
     * Update Repeated Fields to avoid showing them in the application
     *
     * @param [type] $fields
     * @return void
     */
    protected function updateRepeatedFields($fields, $section, $fieldId, $update = true)
    {
        if ($update) {
            $this->resetRepeatedFields($fieldId);
        }
        // Update Fields
        $section->fields()->whereIn('name', $fields)->update([
            'repeater' =>  $fieldId,
        ]);

        return true;
    }

    /**
     * Reset Repeated Fields
     *
     * @param [type] $repeater
     * @return void
     */
    protected function resetRepeatedFields($repeater)
    {
        // reset fields
        Field::where('repeater', $repeater)->update([
            'repeater' =>  null,
        ]);
    }

    protected function updateFieldsOrder($section, $fieldId, $add = true)
    {
        $sectionOrder = $section->fields_order;

        if (! is_array($sectionOrder)) {
            $sectionOrder = [];
        }

        // Add Filed
        if ($add) {
            if (! in_array($fieldId, $sectionOrder)) {
                array_push($sectionOrder, $fieldId);
            }
        } else {
            // @Todo Remove Field
            $sectionOrder = array_diff($sectionOrder, [$fieldId]);
        }

        $section->fields_order = $sectionOrder;

        return $section;
    }

    /**
     * Return the field properties array
     * @param  [Request] $request [Form Request]
     * @return [array] Field Properties
     */
    protected function getPropertiesValues($properties)
    {
        $fieldProperties = [];

        foreach ($properties as $key => $value) {
            $key = explode('_', $key);

            if (count($key) == 1) {
                $fieldProperties[$key[0]] = $value;
            } else {
                $fieldProperties[$key[0]][$key[1]] = $value;
            }
        }

        // show Fields By default
        $fieldProperties['show'] = true;

        return $fieldProperties;
    }

    public function getIntelligenceRule($payload)
    {
        $application = Application::find($payload['applicationId']);

        $fields = $this->getApplicationFileds($application);

        if (
            $html = view('back.applications._partials.intelligence.rule', [
            'fields'        => $fields,
            'application'   => $application,
            ])->render()
        ) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html' => $html],
            ]);
        }
    }

    public function getApplicationFileds($application)
    {
        $allFields[0] = 'Select Field';
        // get fields
        foreach ($application->sections()->get() as $section) {
            $fields = $section->fields()->get();
            foreach ($fields as $field) {
                $allFields[$field->name] = $field->label;
            }
        }

        return $allFields;
    }

    public function fieldData($payload)
    {
        $sections = Application::find($payload['applicationId'])->sections()->get();

        $field = Field::byName(
            $payload['fieldName']
        )->whereIn('section_id', $sections->pluck('id')->toArray())->first();

        // Get File Type
        $file = $this->getInputFileByFieldType($field->properties['type'], $payload['condition']);

        if (
            $html = view('back.applications._partials.intelligence.field_value', [
            'type'      => $field->properties['type'],
            'file'      => $file,
            'data'      => $field->data,
            'condition' => $payload['condition'],
            ])->render()
        ) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html' => $html],
            ]);
        }
    }

    public function getOptionsList($payload)
    {
        if (! $payload['list']) {
            return Response::json([
                'status'    => 400,
                'response'  => 'failed',
                'extra'     => 'list is mandatory',
            ]);
        }

        //Field options
        if (! $items = FieldsHelper::getListData($payload['list'], $this->inetgration())) {
            abort(404);
        }

        if ($payload['list'] == 'mautic_custom_field') {
            $html = view('back.applications.fields.list.mautic_custom_field', compact('items'))->render();
        }
        if ($payload['list'] == 'custom_field') {
            $application = Application::find($payload['application']);

            $fields = app(self::class)->getApplicationFileds($application);

            $html = view('back.applications._partials.customfields-sync-settings', [
                'application' => $application,
                'fields'      => $fields,
            ])->render();
        } else {
            $html = view('back.applications.fields.list.custom_list', compact('items'))->render();
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => $html],
        ]);
    }

    public function getMauticCustomFieldData($payload)
    {
        if (! $items = $this->inetgration()->getFieldList($payload['list'])) {
            abort(404);
        }

        $html = view('back.applications.fields.list.mautic.custom_list', compact('items'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    protected function getInputFileByFieldType($type, $condition = null)
    {
        switch ($type) {
            case 'date':
                return 'date-input';
            break;

            case 'checkbox':
            case 'list':
                if ($condition == 'contain') {
                    return 'multi-select';
                }

                return 'select';
            break;

            case 'textarea':
                return 'text-area';
            break;

            default:
                return 'text-input';
            break;
        }
    }

    /**
     * Get the value of mautic
     */
    public function getMautic()
    {
        return $this->mautic;
    }

    /**
     * Set the value of mautic
     *
     * @return  self
     */
    public function setMautic($mautic)
    {
        $this->mautic = $mautic;

        return $this;
    }

    public function clone(Field $old_field, Section $section, Application $application)
    {
        $field = $old_field->replicate();
        $name = $field->name;
        $field->name = $this->getUniqueFieldName($name);
        $field->save();

        $order = $section->fields_order;
        $order[] = $field->id;
        $section->fields_order = $order;
        $section->save();

        $html = view('back.applications._partials.field', compact('field', 'section', 'application'))->render();

        return Response::json([
              'status'    => 200,
              'response'  => 'success',
              'extra'     => ['html' => $html, 'section_id' => $section->id, 'field_id' => $field->id],
          ]);
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

    public function addCustomField($payload)
    {
        $object = isset($payload['payload']['object']) ? $payload['payload']['object'] : 'course';
        if ($html = view('back.applications.fields.'.$object.'.custom-field', ['order' => $payload['order'] , 'object' => $object])->render()) {
            return Response::json([
                        'status'    => 200,
                        'response'  => 'success',
                        'extra'     => ['html' => $html],
                    ]);
        }
    }
}
