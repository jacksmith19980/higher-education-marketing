<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Booking;
use Auth;
use Response;

class BookingController extends Controller
{
    public function show($school, Booking $booking)
    {
        if ($booking->user_id != Auth::guard('student')->user()->id) {
            return "<p class='alert alert-danger'>Something Wrong</p>";
        }

        return view('front.booking.show', compact('booking'));
    }

    public function destroy($school, Booking $booking)
    {
        if (Auth::guard('student')->user()->id != $booking->user_id) {
            return Response::json([
                'status' => 400,
                'response' => 'not Found',
                'extra' => [],
            ]);
        }

        $booking->submissions()->delete();

        if ($booking->delete()) {
            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['booking_id' => $booking->id],
            ]);
        }
    }
}
