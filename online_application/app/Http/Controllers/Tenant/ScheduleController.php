<?php

namespace App\Http\Controllers\Tenant;

use App\Events\Tenant\UsersWereInvited;
use App\Helpers\School\PluginsHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\SettingRepository;
use App\School;
use App\Tenant\Models\Admission;
use App\Tenant\Models\Schedule;
use App\Tenant\Traits\Integratable;
use Illuminate\Http\Request;
use Response;
use Storage;

class ScheduleController extends Controller
{
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
        return view('back.settings.calendar.calendar-schedule');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->schedule_label);
        /*$request->validate([
            'label'          => 'required',
            'start_time'     => 'required',
            'end_time'       => 'required',
        ]);*/

        foreach ($request->schedule_label as $k => $v) {
            $schedule = Schedule::updateOrCreate([
                'label'         => $request->$v,
                'start_time'    => $request->start_time[$k],
                'end_time'         => $request->end_time[$k],
            ]);
        }

        $res = $schedule->save();

        session()->put('settings-'.session('tenant'), Setting::byGroup());
        dd($res);

        /*$html = view('back.settings.admissions.admission', compact('admission'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => $html ]
        ]);*/
    }
}
