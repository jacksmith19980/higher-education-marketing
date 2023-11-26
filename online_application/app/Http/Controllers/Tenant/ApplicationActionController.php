<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Application;
use App\Tenant\Models\ApplicationAction;
use App\Tenant\Traits\Integratable;
use Illuminate\Http\Request;
use Response;
use Sign;

class ApplicationActionController extends Controller
{
    use Integratable;

    public function create(Application $application, Request $request)
    {
        $actionName = $request->action;
        $route = route('application.actions.store', [
            'application'   => $application,
        ]);

        return view('back.applications.actions.'.$actionName.'.form', compact('route', 'actionName', 'application'));
    }

    public function store(Request $request)
    {
        $application = Application::find($request->application);

        $action = new ApplicationAction();
        $action->title = isset($request->title) ? $request->title : $request->type;
        $action->action = $request->type;
        $action->properties = $request->properties;
        $action->application()->associate($request->application);

        if ($action->save()) {
            $html = view('back.applications._partials.action', compact('action', 'application'))->render();

            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
                ]);
        } else {
            return Response::json([
                'status'    =>  400,
                'response'  => 'fail',
                'extra'     => [],
            ]);
        }
    }

    /**
     * Edit Application Action
     *
     * @param Application $application
     * @param ApplicationAction $action
     * @param Request $request
     * @return Response
     */
    public function edit(Application $application, ApplicationAction $action, Request $request)
    {
        $route = route('application.actions.update', [
            'action'        => $action,
            'application'   => $application,
        ]);
        $actionName = $action->action;

        $fieldsSelect = [];

        $sections = $application->sections()->orderBy('id', 'asc')->with('onlyFields')->get();

        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                // get Fields amd Files
                if ($field->field_type == 'field' || $field->field_type == 'file') {
                    $fieldsSelect[$section->title][$field->name] = $field->label;
                }
            }
        }

        $fieldsSelectEdit = ApplicationController::cartMapping($fieldsSelect);

        $eversign_statics = [
            'addons' => 'Addons',
            'courses_addons' => 'Course Addons',
            'programs_addons' => 'Program Addons',
            'course' => 'Course',
        ];

        if (
            array_key_exists('documentHash', $action->properties) &&
            $eversignFieldsSelectEdit = Sign::getFields($action->properties['documentHash'])
        ){
            $eversignFieldsSelectEdit = array_merge($eversignFieldsSelectEdit, $eversign_statics);
        } else {
            $eversignFieldsSelectEdit = '';
        }
        $method = 'PUT';
        return view(
            'back.applications.actions.'.$actionName.'.form',
            compact(
                'route',
                'action',
                'application',
                'actionName',
                'method',
                'fieldsSelectEdit',
                'eversignFieldsSelectEdit'
            )
        );
    }

    /**
     * Delete ApplicationAction
     * @param  [ApplicationAction] $ApplicationAction [description]
     * @return [JSON]                   [return rmeoved Application Action Id to hide]
     */
    public function destroy($application, ApplicationAction $action)
    {
        if ($response = optional($action)->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $action->id],
             ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
              ]);
        }
    }

    public function update($application, ApplicationAction $action, Request $request)
    {
        $action->title = isset($request->title) ? $request->title : $request->type;
        $action->action = $request->type;
        $action->properties = $request->properties;

        if ($action->save()) {
            $html = view('back.applications._partials.action', compact('action', 'application'))->render();

            return Response::json([
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]);
        } else {
            return Response::json([
                'status'   => 400,
                'response' => 'fail',
                'extra'    => [],
            ]);
        }
    }

    public function loadMauticEmails($payload)
    {
        if ($integration = $this->inetgration()) {
            $emailsList = $integration->getEmailsList();

            $args = [
                'name'      => 'properties[mautic_email]',
                'label'     => 'Mautic Email',
                'class'     => 'ajax-form-field select2',
                'required'  => true,
                'attr'      => '',
                'data'      => $emailsList,
                'value'     =>  '',
            ];
            $html = view('back.layouts.core.forms.select', $args)->render();
        } else {
            $html = "<p class='alert alert-danger'>Please, enable Mautic integration</p>";
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => $html],
        ]);
    }

    /**
     * Deprecated
     *
     * @return \Illuminate\Http\Response
     */
    public function getApplicationActionDetails($payload)
    {
        $html = view('back.applications.actions.'.$payload['application_action'])->render();

        return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
        ]);
    }
}
