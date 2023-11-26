<?php

namespace App\Listeners\Tenant\Education;

use Carbon\Carbon;
use App\Tenant\Models\Date;
use App\Events\Tenant\Education\SemesterCreated;

class UpdateDateSemester
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SemesterCreated  $event
     * @return void
     */
    public function handle(SemesterCreated $event)
    {
        $semester = $event->semester;
        $dates = Date::whereDate('start' , '>=' , $semester->start_date )
        ->whereDate('end' , '<=' , $semester->end_date )
        ->where('semester_id', '!=' , $semester->id)
        ->orWhereNull('semester_id')
        ->get();
        dd($dates);


    }


}
