<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Permission\PermissionHelpers;
use App\School;
use Illuminate\Http\Request;
use App\Tenant\Models\ApiKey;
use App\Http\Controllers\Controller;
use Response;
use Webpatser\Uuid\Uuid;

class ApiKeyController extends Controller
{

    const PERMISSION_BASE = "settings";

    public function show()
    {
        $permissions = PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|'.self::PERMISSION_BASE]) {
            return PermissionHelpers::accessDenied();
        }

        $key = $this->getActiveKey();
        return view('back.api-key.index' , compact('key' , 'permissions'));
    }

    public function generate()
    {
        $permissions = PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|'.self::PERMISSION_BASE]) {
            return PermissionHelpers::accessDenied();
        }

        $school = School::where('uuid', session('tenant'))->first();
        // Deactivate All Keys
        if($activeKey = $this->getActiveKey()){
            $activeKey->is_active = false;
            $activeKey->save();
        }

        // generate New Key
        $key = new ApiKey;
        $key->api_key = Uuid::generate(4)->string;
        $key->school()->associate($school);
        if($key->save()){
            // Render HTML
            $html = view('back.api-key.key' , compact('key' ,'permissions' ))->render();
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'html' => $html,
                        'message'   => __("API Key Generated Successfully!")
                    ],
                ]);

        }
    }

    public function deactivate()
    {
        $permissions = PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|'.self::PERMISSION_BASE]) {
            return PermissionHelpers::accessDenied();
        }


        $key = $this->getActiveKey();
        $key->is_active = false;
        if($key->save()){
            // Render HTML
            $key = null;
            $html = view('back.api-key.key' , compact('key','permissions'))->render();
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'html'      => $html,
                        'message'   => __("API Key Deactivated Successfully!")
                    ],
                ]);

        }
    }

    protected function getActiveKey()
    {
        $permissions = PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|'.self::PERMISSION_BASE]) {
            return PermissionHelpers::accessDenied();
        }

        $school = School::where('uuid', session('tenant'))->first();
        return $school->apiKeys()->isActive()->first();
    }
}
