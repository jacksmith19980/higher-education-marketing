<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function excuate(Request $request)
    {
        $action = explode('.', $request->action);
        $controllerName = '';
        if(strpos($action[0] ,"|")){

            $tmp = explode('|', $action[0]);
            $folderName = ucwords($tmp[0]);

            $tmp = explode('_', $tmp[1]);
            foreach ($tmp as $tmpname) {
                $controllerName .= ucfirst($tmpname);
            }
            $controller = 'App\\Http\\Controllers\\Tenant\\School\\'.$folderName.'\\'.$controllerName.'Controller';


        }else{
            $tmp = explode('_', $action[0]);
            foreach ($tmp as $tmpname) {
                $controllerName .= ucfirst($tmpname);
            }
            $controller = 'App\\Http\\Controllers\\Tenant\\School\\'.$controllerName.'Controller';
        }

        // Check if Controller Exist
        if (class_exists($controller)) {
            return app($controller)->{$action[1]}($request);
        }
    }
}
