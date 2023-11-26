<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function excuate(Request $request)
    {
        $action = explode('.', $request->action);

        $controllerName = '';
        $tmp = explode('_', $action[0]);
        foreach ($tmp as $tmpname) {
            $controllerName .= ucfirst($tmpname);
        }
        $controller = 'App\\Http\\Controllers\\Tenant\\School\\Agent\\'.$controllerName.'Controller';
        // Check if Controller Exist
        if (class_exists($controller)) {
            return app($controller)->{$action[1]}($request);
        }
    }
}
