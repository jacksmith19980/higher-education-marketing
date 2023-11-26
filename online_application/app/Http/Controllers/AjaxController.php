<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware(['tenant']);

    }

    public function excuate(Request $request)
    {
        $action = explode('.', $request->action);
        $controllerName = '';
        $tmp = explode('_', $action[0]);
        foreach ($tmp as $tmpname) {
            $controllerName .= ucfirst($tmpname);
        }
        if (! $payload = $request->payload) {
            $payload = [];
        }
        if (isset($payload['backEnd']) && $payload['backEnd'] != "false") {
            $path = 'App\\Http\\Controllers\\';
        } else {
            $path = 'App\\Http\\Controllers\\Tenant\\';
        }

        $controller = $path .$controllerName.'Controller';

        // Check if Controller Exist
        if ($classExists = class_exists($controller)) {
            return app($controller)->{$action[1]}($payload);
        }
    }
}
