<?php

namespace App\Listeners\Tenant\Student;

use App\Events\Tenant\Student\StudentRegistred;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateStudentBooking
{
    use Integratable;

    /**
     * Handle the event.
     *
     * @param  ParentRegistred  $event
     * @return void
     */
    public function handle(StudentRegistred $event)
    {
        $request = $event->request;

        if (! $request->has(['booking', 'user'])) {
            return true;
        }

        $booking = Booking::where([
            'id'        => $request->booking,
            'user_id'   => $request->user,
        ])->first();

        if ($booking) {
            $booking->user_id = $event->student->id;
            $booking->save();
        }

        return true;
    }
}
