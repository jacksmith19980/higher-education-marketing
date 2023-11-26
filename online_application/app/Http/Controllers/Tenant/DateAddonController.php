<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Course;
use App\Tenant\Models\Date;
use App\Tenant\Models\Field;
use Illuminate\Http\Request;
use Response;

class DateAddonController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Date $date, Request $request)
    {
        $addon = $date->properties['addons'][$request->addon_key];
        $subkey = $request->addon_key;
        $edit = true;

        return view('back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.addons', compact('course', 'addon', 'date', 'subkey', 'edit'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Course $course, Date $date, Request $request)
    {
        $props = $date->properties;
        $addonKey = $request->addon_key;
        $addon = $request->properties['addons'][$addonKey];
        $props['addons'][$addonKey] = $addon;

        $date->update([
            'properties' => $props,
        ]);
        $html = view('back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.addon', compact('course', 'addon', 'date', 'addonKey'))->render();

        if ($response = $date->save()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['key' => $addonKey, 'html' => $html],
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Date $date, Request $request)
    {
        $props = $date->properties;
        $addons = $props['addons'];
        unset($addons[$request->addon_key]);

        $props['addons'] = $addons;

        $date->update(['properties' => $props]);

        if ($response = $date->save()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['key' => $request->addon_key],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function getDateAddons(Request $request)
    {
        $field = Field::find($request->element);

        $course_slug = $request->course;
        $hash = $request->hash;
        $course = Course::with('dates', 'addons')->where('slug', $course_slug)->first();

        $date_id = $request->date;

        $date = Date::findOrFail($date_id);
        $datesAddonsHtml = '';

        $addons = array_key_exists(
            'addons',
            $date->properties
        ) && ! empty($date->properties['addons']) ? $date->properties['addons'] : [];

        if (count($addons) > 0) {
            $datesAddonsHtml = view(
                'front.applications.application-layouts.oiart.courses.partials.dates-addons',
                compact('addons', 'course', 'field', 'date_id', 'hash')
            )->render();
        }

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'datesAddonsHtml' => $datesAddonsHtml,
                ],
            ]
        );
    }
}
