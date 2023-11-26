<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Tenant\Traits\Slugable;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\Integratable;
use App\Http\Controllers\Controller;
use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\Application\ApplicationHelpers;

class ApplicationCloneController extends Controller
{
    use Integratable;
    use Slugable;
    use  HasCampuses;
    const  PERMISSION_BASE = "application";


    public function create(Application $application)
    {
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        // if user is not permitted to create courses cross campus
        if (!$permissions['campusesCreate|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }

        $customization = $this->getApplicationThemeCustomization(['theme' => $application->layout], false);

        $application->title = '';

        return view('back.applications.clone.create', compact('application', 'customization' , 'campuses'));
    }

    public function store(Request $request, Application $application)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $application->load('sections.fields', 'PaymentGateways', 'integrations', 'actions');
        $newApplication = $application->replicate();

        $newApplication->title = $request->title;
        $newApplication->slug = $this->slugify($request->title, Application::class);
        $newApplication->save();

        $sections_changes = [];
        $fields_changes = [];
        $sections_order = $newApplication->sections_order;
        foreach ($newApplication->sections as $section) {
            $newSection = $section->replicate();
            $newSection->save();
            $sections_changes[$section->id] = $newSection->id;
            $newApplication->sections()->save($newSection);
            $sections_order[array_search($section->id, $sections_order)] = $newSection->id;

            $replicates_fields = $this->replicate($newSection, 'fields');

            $fields_order = $this->updateFieldsOrder($replicates_fields, $section->fields_order);

            $fields_changes = $fields_changes + $replicates_fields;

            $newSection->fields_order = $fields_order;
            $newSection->save();
        }

        $newApplication->sections_order = $sections_order;
        $newApplication->save();

        $this->replicate($newApplication, 'PaymentGateways', $sections_changes, $fields_changes);
        $this->replicate($newApplication, 'integrations');
        $this->replicate($newApplication, 'actions');

        return redirect(route('applications.build', $newApplication));
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

    public function getApplicationThemeCustomization($payload, $html = true)
    {
        // Get Customization

        $customization = ApplicationHelpers::$application_themes[$payload['theme']]['customization'];

        if (! $html) {
            return $customization;
        }

        $html = view('back.applications._partials.application-customization', [
            'customization' => $customization,
        ])->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html'  => $html],
        ]);
    }
}
