<?php

namespace App\Http\Controllers\Tenant;

use Sign;
use Response;
use App\School;
use Illuminate\Http\Request;
use App\Tenant\Traits\Slugable;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use App\Integrations\Mautic\Mautic;
use App\Tenant\Traits\Integratable;
use App\Http\Controllers\Controller;
use App\Integrations\Eversign\Eversign;
use App\Tenant\Models\ApplicationAction;
use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\Application\ApplicationHelpers;
use App\Events\Tenant\Application\ApplicationCreated;
use App\Tenant\Models\Field;

class ApplicationController extends Controller
{
    use Slugable;
    use Integratable;
    use  HasCampuses;
    const  PERMISSION_BASE = "application";

    public function __construct()
    {
        $this->middleware('plan.features:application');
    }

    public function index(Request $request)
    {
       // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);


        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        if (isset($request->hash)) {
            $hash = 'appication';
        }

        $hash = isset($request->hash) ? $request->hash : null;

        $applications = Application::with('submissions' , 'campuses')->byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->get();

        $params = [

            'modelName' => 'applications',

        ];

        $school = School::ByUuid(session('tenant'))->first();

        // Get Application Status Count
        return view('back.applications.index', compact('applications', 'params', 'school', 'hash' , 'permissions'));
    }

    public function create(Request $request)
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

        $object = ($request->has('object')) ? $request->object : 'student';
        return view('back.applications.create-'.$object.'-application', compact('object' , 'permissions' , 'campuses'));
    }

    public function store(Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        $validatedData = $request->validate([
            'title'         => 'required|max:255',
            'description'   => 'required',
            'object'        => 'required',
            'layout'        => 'required',
        ]);

        // Create Application
        $application = Application::create([
            'title'         => $request->title,
            'slug'          => $this->slugify($request->title, Application::class),
            'object'        => $request->object,
            'description'   => $request->description,
            'layout'        => $request->layout,
            'properties'    => $request->properties,
            'published'     => true,
        ]);

        // Save Application Actions
        if (isset($request->actions) && count($request->actions)) {
            $this->saveApplicationActions($application, $request);
        }

        // Save Campus Relation
        if(!is_null($request->campus) && count( array_filter($request->campus) )){
            $application->campuses()->attach($request->campuses);
        }

        //School
        $school = School::where('uuid', session('tenant'))->first();

        //Dispatch Event ApplicationCreated
        event(new ApplicationCreated($application, $school));

        $hash = '';
        if (isset($request->hash) && $request->hash == 'application') {
            $hash = '?hash=newapplication#buildapplication';
        }

        //return redirect()->route('applications.edit' , $application);
        return redirect(route('applications.build', $application).$hash);
    }

    public function show(Application $application)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|' . self::PERMISSION_BASE]) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }

        $sections = $application->sections()->with('fields')->get();
        return view('back.applications.show', compact('application', 'sections'));
    }

    public function edit(Application $application)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $application)) {
            return PermissionHelpers::accessDenied();
        }
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['campusesEdit|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }


        $customization = $this->getApplicationThemeCustomization(['theme' => $application->layout], false);

        $stages = [];

        if ($mautic = $this->inetgration()) {
            $mauticStages = $mautic->getStages();

            if (isset($mauticStages['stages'])) {
                foreach ($mauticStages['stages'] as $stage) {
                    $stages[$stage['id']] = $stage['name'];
                }
            }
        }

        return view('back.applications.edit', compact('application', 'customization', 'stages' , 'permissions' , 'campuses'));
    }

    public function update(Request $request, Application $application)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $application)) {
            return PermissionHelpers::accessDenied();
        }


        $application->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'layout'        => $request->layout,
            'properties'   => $request->properties,
        ]);

        if($request->filled('campuses')){
            $application->campuses()->sync($request->campuses);
        }
        return redirect(route('applications.index'));
    }

    public function build(Application $application)
    {

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $application)) {
            return PermissionHelpers::accessDenied();
        }

        $school = School::where('uuid', session('tenant'))->first();

        $application->load(['integrations', 'PaymentGateways', 'actions']);

        $sections = $application->sections()->with(['fields'])->get();

        return view('back.applications.build', compact('application', 'sections', 'school'));
    }

    public function destroy(Application $application)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $application)) {
            return PermissionHelpers::accessDenied();
        }

        // Delete Application
        if ($response = $application->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $application->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    protected function saveApplicationActions(Application $application, Request $request)
    {
        foreach ($request->actions as $action) {
            $applicationAction = new ApplicationAction();

            $applicationAction->title = $action;

            $applicationAction->action = $action;

            $applicationAction->properties = $request->action_properties[$action];

            $application->actions()->save($applicationAction);

            $applicationAction->save();
        }

        return $application;
    }

    public function sectionsOrder($payload)
    {
        // Workaround to remove the reapted section ID from Drgulla

        array_pop($payload['order']);

        $application = Application::bySlug($payload['application'])->first();

        $application->sections_order = $payload['order'];

        if ($application->save()) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['sections_order'  => $application->sections_order],
            ]);
        }
    }

    public function getFieldsList($payload)
    {

        $fieldsSelect = [];

        $application = Application::find($payload['applicationid']);

        $sections = $application->sections()->orderBy('id', 'asc')->with('onlyFields')->get();

        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                // get Fields amd Files
                if ($field->field_type == 'field' || $field->field_type == 'file') {

                    $fieldsSelect[$section->title][$field->name] = $field->label;
                    // Adding Program default mapping fields
                    if($field->properties['type'] == "program"){
                        $fieldsSelect = $this->addEductionsDefaultMapping($section->title, $field , $fieldsSelect ,'Program');
                    }

                    if($field->properties['type'] == "course"){
                        $fieldsSelect = $this->addEductionsDefaultMapping($section->title, $field , $fieldsSelect
                        ,'Course');
                    }
                }
            }
        }
        $integrationName = isset($payload['formData']['type']) ? $payload['formData']['type'] : null;

        $customFields = [];
        if (isset($integrationName)) {
            $integration = $this->getIntegration($integrationName, $payload['formData']);
            $object = (in_array($application->object , ['form' , 'student'])) ? 'student' : $application->object;
            $customFields = $integration->getCustomFields($object);
        }
        $html = view('back.applications.integrations.'.$integrationName.'.customize-field-name', [
                'fieldsSelect'          => $fieldsSelect,
                'mauticFieldsSelect'    => $customFields,
                'field_value'           => $fieldsSelect,
                'custom_field_value'    => $customFields,
                ])->render();

        return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
        ]);
    }


    protected function addEductionsDefaultMapping($sectionTitle, Field $field , $list = [] , $object = 'Program')
    {
            // Add rogram's Default field
            $list[$sectionTitle][$field->name.'|programs']= "$object Code";
            $list[$sectionTitle][$field->name.'|campus'] = "$object's Campus";
            $list[$sectionTitle][$field->name.'|date'] = "$object's Date";
            $list[$sectionTitle][$field->name.'|start_date'] = "$object's Start Date";
            $list[$sectionTitle][$field->name.'|end_date'] = "$object's End Date";
            $list[$sectionTitle][$field->name.'|schedule'] = "$object's Schedule";
            if(isset($field->properties['customFields'])){
                foreach ($field->properties['customFields'] as $fieldCustomFields) {
                    $list[$sectionTitle][$field->name.'|'.$fieldCustomFields['name']] = "$object|" .
                    $fieldCustomFields['name'];
                }
            }
        return $list;
    }

    /**
     * Show only fields without integrations fields
     * @param $payload
     * @return mixed
     * @throws \Throwable
     */
    public function getSignatureFieldsList($payload)
    {
        $fieldsSelect = [];

        $application = Application::find($payload['applicationid']);

        $sections = $application->sections()->orderBy('id', 'asc')->with('onlyFields')->get();

        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                // get Fields amd Files
                if ($field->field_type == 'field' || $field->field_type == 'file') {
                    $fieldsSelect[$section->title][$field->name] = $field->label;
                }
            }
        }

        $fieldsSelect = self::cartMapping($fieldsSelect);

        $esignatureFieldsSelect = Sign::getFields($payload['hash']);

        $esignature_statics = [
            'addons'            => 'Addons',
            'courses_addons'    => 'Course Addons',
            'programs_addons'   => 'Program Addons',
            'course'            => 'Course',
        ];

        if ($esignatureFieldsSelect = Sign::getFields($payload['hash'])) {
            $esignatureFieldsSelect = array_merge($esignatureFieldsSelect, $esignature_statics);
            $html = view('back.applications.fields.esignature.customize-field-name', [
                'fieldsSelect' => $fieldsSelect,
                'esignatureFieldsSelect' => $esignatureFieldsSelect,
            ])->render();

            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
                ]);
        } else {
            abort('Missing Required Field', 404);
        }
    }

    public function getCustomizedFieldName($payload)
    {
        $html = view('back.applications.integrations.mautic.customize-field-name', [
                    'field_name'            => $payload['field_name'],
                    'field_value'           => isset($payload['field_value']) ? $payload['field_value'] : '',
                    'custom_field_name'     => $payload['custom_field_name'],
                    'custom_field_value'    => isset($payload['custom_field_value']) ? $payload['custom_field_value'] : '',
                    'mautic_contact_type'   => $payload['mautic_contact_type'],
                ])->render();

        return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
        ]);
    }

    public function getSignatureCustomizedFieldName($payload)
    {
        $html = view('back.applications.fields.esignature.customize-field-name', [
            'field_name'            => $payload['field_name'],
            'custom_field_name'     => $payload['custom_field_name'],
        ])->render();

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html'  => $html],
        ]);
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

    public function showSynSettings($payload)
    {
        $application = Application::find($payload['application']);

        $fields = app(FieldController::class)->getApplicationFileds($application);

        $html = view('back.applications._partials.fields-sync-settings', [
            'application' => $application,
            'fields'      => $fields,
        ])->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html'  => $html],
        ]);
    }

    public static function cartMapping(array $fieldsSelect)
    {
        $fieldsSelect['Cart'] = [
            'courses|title' => 'Course title',
            'courses|date' => 'Course date',
            'courses|price' => 'Course price',
            'courses|date_addons_title' => 'Course date addon title',
            'courses|date_addons_price' => 'Course date addon price',
            'courses|addons_title' => 'Course Addon title',
            'courses|addons_price' => 'Course Addon price',
            'programs|title' => 'Program title',
            'programs|regular_price' => 'Program price',
            'programs|registration_fees' => 'Program\'s Registration Fees',
            'programs|start_date' => 'Program start date',
            'programs|end_date' => 'Program end date',
            'programs|date_schudel' => 'Program schudel',
            'programs|addons_category' => 'Program Addon category',
            'programs|addons_title' => 'Program Addon title',
            'programs|addons_price' => 'Program Addon price',
            'addons|title' => 'Addon title',
            'addons|price'  => 'Addon price',
            'cart|total'    => 'Cart total price',
            'cart|programs' => 'Cart programs total price',
            'cart|courses' => 'Cart courses total price',
            'cart|addons' => 'Cart addons total price',
        ];

        return $fieldsSelect;
    }

    public function fullAmount($payload)
    {
        $html = view(
            'back.applications._partials.application-creation.shared.full-amount',
            [
                'cssClass' => $payload['cssClass'],
            ]
        )->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html'  => $html],
        ]);
    }

    public function fixedAmount($payload)
    {
        $html = view(
            'back.applications._partials.application-creation.shared.installment-fix-amount',
            [
                'cssClass' => $payload['cssClass'],
            ]
        )->render();

        return Response::json([
              'status'    => 200,
              'response'  => 'success',
              'extra'     => ['html'  => $html],
          ]);
    }

    public function variableAmount($payload)
    {
        $html = view(
            'back.applications._partials.application-creation.shared.installment-variable-amount',
            [
                'cssClass' => $payload['cssClass'],
            ]
        )->render();

        return Response::json([
              'status'    => 200,
              'response'  => 'success',
              'extra'     => ['html'  => $html],
          ]);
    }

    public function installment($payload)
    {
        $html = view(
            'back.applications._partials.application-creation.shared.installment-row',
            [
                'cssClass' => $payload['cssClass'],
            ]
        )->render();

        return Response::json([
                                  'status'    => 200,
                                  'response'  => 'success',
                                  'extra'     => ['html'  => $html],
                              ]);
    }

    public function togglePublishStatus($payload)
    {
        if (! isset($payload['application_id'])) {
            return abort(404);
        }
        if (! $application = Application::find($payload['application_id'])) {
            return abort(404);
        }

        $application->published = ! $application->published;
        if ($application->save()) {
            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'html' => 'done',
                    'application_id' => $application->id,
                    'status' => $application->published,
                ],
            ]);
        }
    }
}
