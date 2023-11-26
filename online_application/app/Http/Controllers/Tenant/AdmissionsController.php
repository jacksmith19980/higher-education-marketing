<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Admission;
use App\Tenant\Traits\School\Toggleable;
use Illuminate\Http\Request;
use Response;

class AdmissionsController extends Controller
{
    use Toggleable;

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
    public function create()
    {
        return view('back.settings.admissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email',
            'phone'         => 'required',
            'timezone'      => 'required',
            'availability'  => 'required',
        ]);

        $availability = [];
        foreach ($request->availability as $slot) {
            if ($slot['day']) {
                $availability[$slot['day']] = [
                    'start' => $slot['start'],
                    'end' => $slot['end'],
                ];
            }
        }

        $admission = Admission::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'timezone'      => $request->timezone,
            'available'     => true,
            'availability'  => $availability,
            'properties'    => [],
        ]);

        $html = view('back.settings.admissions.admission', compact('admission'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => $html],
        ]);
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
