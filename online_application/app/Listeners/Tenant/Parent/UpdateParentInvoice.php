<?php

namespace App\Listeners\Tenant\Parent;

use App\Events\Tenant\Parent\ParentRegistred;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateParentInvoice
{
    use Integratable;

    /**
     * Handle the event.
     *
     * @param  ParentRegistred  $event
     * @return void
     */
    public function handle(ParentRegistred $event)
    {
        $settings = Setting::byGroup('stages');
        $request = $event->request;

        if (! $request->has(['booking', 'user'])) {
            return true;
        }
        $booking = Booking::where([
                'id'        => $request->booking,
                'user_id'   => $request->user,
                ])->first();
        if ($booking) {
            $booking->user_id = $event->parent->id;
            $booking->save();
        }

        return true;
    }
}
