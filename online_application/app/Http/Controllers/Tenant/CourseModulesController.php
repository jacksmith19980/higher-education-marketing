<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\School\ModuleHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use Illuminate\Http\Request;

class CourseModulesController extends Controller
{
    public function index($payload)
    {
        $html = '';

        if ($modules = ModuleHelpers::getModuleInArrayOnlyTitleId(Program::findOrFail($payload['program'])->modules)) {
            $html = view('back.shared._partials.field_value', [
                'data' => $modules,
                'name'  => 'module',
                'label' => 'Module',
            ])->render();
        }

        return response()->json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }
}
