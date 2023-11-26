<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = School::byUuid(session('tenant'))->firstOrFail();

        $programs = Program::get()->pluck('title', 'id')->toArray();
        $courses = Course::get()->pluck('title', 'id')->toArray();
        $campuses = Campus::get()->pluck('title', 'id')->toArray();

        return view('back.quotation.create', compact('settings', 'programs', 'courses', 'campuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Push to SettingsController
        app(\App\Http\Controllers\Tenant\SettingController::class)->store($request);

        return redirect(route('quotation.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
