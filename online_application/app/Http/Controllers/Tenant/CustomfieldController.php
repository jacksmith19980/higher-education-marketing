<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\customfield\CustomFieldHelper;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\CustomField;
use function GuzzleHttp\Promise\all;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Response;

class CustomfieldController extends Controller
{
    const PARENTS = [
            'students' => 'Contacts',
            'programs' => 'Programs',
            'courses' => 'Courses'
        ];
    const TYPES = [
        'text' => 'Text Input',
        'list' => 'Multi Select'
    ];
    const PERMISSION_BASE = "field";

    public function index()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $params = [
            'modelName'   => CustomField::getModelName(),
        ];

        $customFields = CustomField::all();

        return view('back.customFields.index', compact('customFields', 'params' , 'permissions'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        return view('back.customFields.create')->with(['parents' => self::PARENTS, 'types' => self::TYPES, 'permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
            'name'       => 'required',
            'slug'       => 'required',
            'field_type' => 'required',
            'parent'     => 'required',
        ]);

        $customfield = new CustomField();
        $customfield->name = $request->name;
        $customfield->slug = $request->slug;
        $customfield->field_type = $request->field_type;
        $customfield->properties = $request->parent;
        $customfield->for_forms = isset($request->for_forms) ? $request->for_forms : 0;

        switch ($customfield->field_type) {
            case 'list':
                $request->validate([
                'keys'       => 'required',
                'values'     => 'required',
                ]);
                $old_data = $customfield->data;
                if (is_array($old_data)) {
                    $newData = array_merge(['labels' => $request->keys, 'values' => $request->values], $old_data);
                    $customfield->data = $newData;
                } else {
                    $newData = ['labels' => $request->keys, 'values' => $request->values];
                    $customfield->data = $newData;
                }
            break;


            case 'text':
                $customfield->data = [];
            break;

            default:
                # code...
                break;
        }

        if (isset($request->mandatory)) {
            $newData['mandatory'] =  $request->mandatory;
            $customfield->data = $newData;
        }

        $message = ['success' => "Custom Field {$customfield->name } created successfully!"];

        try {
            $customfield->save();
        } catch (\Exception $e) {
            $message = ['error' => __($e->getMessage())];
        }

        return redirect(route('customfields.index'))
            ->with($message);
    }

    public function show($id)
    {
        //
    }

    public function edit(CustomField $customfield)
    {

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $customfield)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        return view('back.customFields.edit')->with(
            ['parents' => self::PARENTS, 'types' => self::TYPES, 'customfield' => $customfield]
        );
    }

    public function update(Request $request,CustomField $customfield)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $customfield)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        $request->validate([
            'name'       => 'required',
            'field_type' => 'required',
            'parent'     => 'required',
        ]);


        $customfield->name = $request->name;
        $customfield->field_type = $request->field_type;
        $customfield->properties = $request->parent;
        $customfield->for_forms = $request->for_forms;

        if (isset($request->mandatory)) {
            $data = $customfield->data;
            $data['mandatory'] = $request->mandatory;
            $customfield->data = $data;
        }

        $message = ['success' => "Custom Field {$customfield->name } updated successfully!"];

        try {
            $customfield->save();
        } catch (\Exception $e) {
            $message = ['error' => __('Custom Field not updated successfully!')];
        }

        return redirect(route('customfields.index'))
            ->with($message);
    }

    public function destroy(CustomField $customfield)
    {


        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $customfield)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'delete|' . self::PERMISSION_BASE,
        ]);

        // Delete Application
        if ($response = $customfield->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $customfield->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function getCustomfieldForm($payload)
    {
        if (!$payload['customfield_id']) {
            return Response::json([
                'status' => 400,
                'response' => 'failed',
                'extra' => 'customfield_id is mandatory',
            ]);
        }

        if ($payload['customfield_id'] != 'list' && $payload['customfield_id'] != 'text') {
            $customfield = CustomField::findOrFail($payload['customfield_id']);
            $html = view('back.customFields._partials.'.$customfield->field_type)->render();
        } else {
            $html = view('back.customFields._partials.'.$payload['customfield_id'])->render();
        }

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function addCustomfieldListValue($payload)
    {
        $order = $payload['order'];
        $html = view('back.customFields._partials.list-data', compact('order'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function getCustomfields($payload)
    {
        if (! $payload['entity']) {
            return Response::json([
                'status' => 400,
                'response' => 'failed',
                'extra' => 'entity is mandatory',
            ]);
        }

        if ($payload['entity'] == 'Program') {
            $customfields = CustomFieldHelper::getProgramsCustomFields('name', 'slug');
        } elseif ($payload['entity'] == 'Courses') {
            $customfields = CustomFieldHelper::getCoursesCustomFields('name', 'slug');
        }

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['customfields' => $customfields],
        ]);
    }
}
