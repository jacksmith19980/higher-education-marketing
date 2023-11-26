<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Course;
use App\Tenant\Models\Date;
use App\Tenant\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;

class DateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Course $course
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function create(Course $course, Request $request)
    {
        $key = Str::random(10);
        $schedules = Schedule::get();

        return view('back.courses._partials.dates.course-dates', compact('course', 'schedules'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Course $course
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Course $course, Request $request)
    {
        $request->validate([
            'key'           => 'required',
            'date_type'     => 'required',
            'properties'    => 'required',
        ]);

        ///*dd($request->properties);
        $date = new Date();
        $date->key = $request->key;
        $date->date_type = $request->date_type;
        $date->properties = $request->properties;
        $date->object_id = $course->id;

        if ($response = $date->save()) {
            $html = view(
                'back.courses._partials.dates.dates-template.'.$request->date_type.'.block',
                [
                    'date'      => $date,
                    'key'       => $date->key,
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
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @param Date $date
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function edit(Course $course, Date $date, Request $request)
    {
        $schedules = Schedule::get();
        $key = $date->key;

        return view(
            'back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.index',
            compact(
                'course',
                'date',
                'key',
                'schedules'
            )
        )->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Course $course
     * @param Date $date
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Course $course, Date $date, Request $request)
    {
        $schedules = Schedule::get();
        //return $request->properties;
        $date->update($request->all());
        if ($response = $date->save()) {
            $html = view(
                'back.courses._partials.dates.dates-template.'.$course->properties['dates_type'].'.block',
                [
                    'date'      => $date,
                    'key'       => $date->key,
                    'course'    => $course,
                    'schedules' => $schedules,
                ]
            )->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['html' => $html, 'key' => $date->key],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function toggleStatus($payload)
    {
        if(!$date = Date::find($payload['date'])){
           return Response::json([
                'status'    => 404,
                'response' => 'failed',
                'extra' => [
                    'message' => 'Something went wrong! Please try again',
                ]
            ]);
        }
        $course = Course::find($payload['course']);

        $properties = $date->properties;

        switch ($payload['status']) {
            case 'complete':
                $properties['completed'] = true;
                $message = _("Marked as completed successfully!");
                break;

                case 'available':
                    $properties['completed'] = false;
                    $message = _("Marked as available successfully!");
                break;
        }
        $date->properties = $properties;

        if($date->save())
        {
            $html = view('back.courses._partials.course.course-date-row' , [
                'date' => $date,
                'course' => $course,
            ])->render();
            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'message'   => $message,
                    'html'      => $html,
                    'key'       => $date->id,
                ]
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @param Date $date
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Course $course, Date $date, Request $request)
    {
        $key = $date->key;

        if ($response = $date->delete()) {
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
