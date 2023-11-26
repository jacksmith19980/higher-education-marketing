<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Response;

class ModuleController extends Controller
{
    public function index()
    {
        $params = [
            'modelName' => Module::getModelName(),
        ];

        $modules = Module::get();

        return view('back.modules.index', compact('modules', 'params'));
    }

    public function create()
    {
        $courses = Course::all()->toArray();
        $courses = Arr::pluck($courses, 'title', 'id');

        return view('back.modules.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'course' => 'required',
        ]);

        $module = Module::create([
            'title' => $request->title,
            'properties' => $request->properties,
        ]);

        $module->course()->associate($request->course)->save();

        return redirect(route('modules.index'))
            ->with('success', "Module {$module->title} created successfully!");
    }

    public function show(Module $module)
    {
        $courses = Course::all()->toArray();
        $courses = Arr::pluck($courses, 'title', 'id');

        return view('back.modules.show', compact('courses'));
    }

    public function edit(Module $module)
    {
        $courses = Course::all()->toArray();
        $courses = Arr::pluck($courses, 'title', 'id');

        return view('back.modules.edit', compact('module', 'courses'));
    }

    public function update(Request $request, Module $module)
    {
        $request->validate(
            [
                'title'  => 'required',
                'course' => 'required',
            ]
        );

        $module->update(
            [
                'title'      => $request->title,
                'properties' => $request->properties,
            ]
        );

        $module->course()->associate($request->course)->save();

        return redirect(route('modules.index'))
            ->with(['success' => "Module {$module->title } updated successfully!"]);
    }

    public function destroy(Module $module)
    {
        if ($response = $module->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $module->id],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }
}
