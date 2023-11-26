<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Addon;
use App\Tenant\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course, Request $request)
    {
        $key = Str::random(10);

        return view('back.courses._partials.addons.course-addons', compact('course', 'key'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Course $course, Request $request)
    {
        $request->validate([
           'title'      => 'required',
           'category'   => 'required',
           'price'      => 'required',
           'price_type' => 'required',
       ]);
        $addon = new Addon;
        $addon->title = $request->title;
        $addon->key = $request->key;
        $addon->category = $request->category;
        $addon->price = $request->price;
        $addon->price_type = $request->price_type;
        $addon->object_id = $course->id;

        if ($response = $addon->save()) {
            $html = view(
                'back.courses._partials.addons.course-addons-block',
                [
                    'addon'      => $addon,
                    'key'       => $addon->key,
                    'course'    => $course,
                ]
            )->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['html' => $html],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tenant\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function show(Addon $addon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tenant\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Addon $addon, Request $request)
    {
        $key = $addon->key;

        return view('back.courses._partials.addons.course-addons', compact('course', 'addon', 'key'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tenant\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function update(Course $course, Addon $addon, Request $request)
    {
        $addon->update($request->all());

        if ($response = $addon->save()) {
            $html = view(
            'back.courses._partials.addons.course-addons-block',
                [
                    'addon'      => $addon,
                    'key'       => $addon->key,
                    'course'    => $course,
                ]
            )->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['html' => $html, 'key' => $addon->key],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tenant\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Addon $addon, Request $request)
    {
        $key = $addon->key;

        if ($response = $addon->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['key' => $key],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }
}
