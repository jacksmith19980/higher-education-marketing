<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\School\Invoice;
use Illuminate\Http\Request;

class PaymentResponseController extends Controller
{
    public function response(School $school, Request $request)
    {
        if (! $request->has('gateway')) {
            return false;
        }
        $gateway = $request->gateway;

        return app('App\\Payment\\'.ucwords($gateway).'Payment')->response($request);
    }
}
