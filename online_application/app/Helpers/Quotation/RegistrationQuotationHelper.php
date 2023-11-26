<?php

namespace App\Helpers\Quotation;

use App\Tenant\Models\Booking;
use Auth;
use Illuminate\Support\Str;

abstract class RegistrationQuotationHelper
{
    public static function createBooking($cart, $user_id = null, $user_role = null): Booking
    {
        if (! is_array($cart)) {
            $cart = json_decode($cart, true);
        }
        if (! $user_id) {
            $user_id = self::getUserId();
            $user_role = 'student';
        }

        $bookingDetails = [
            'courses' => $cart['price']['courses'],
            'addons' => $cart['price']['addons'],
            'accommodation' => $cart['price']['accomodations'],
            'transfer' => $cart['price']['transfer'],
            'totalPrice' => $cart['price']['total'],
            'details' => $cart,
        ];

        return Booking::create([
                'quotation_id' => request()->quotation->id,
                'user_id' => $user_id,
                'invoice' => $bookingDetails,
                'object' => $user_role,
        ]);
    }

    protected static function getUserId()
    {
        if ($user = Auth::guard('student')->user()) {
            $user_id = $user->id;
        } else {
            $user_id = Str::random(10);
        }

        return $user_id;
    }
}
