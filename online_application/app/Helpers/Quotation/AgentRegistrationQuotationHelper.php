<?php

namespace App\Helpers\Quotation;

use App\Events\Tenant\Agency\AgencyIsCreated;
use App\Events\Tenant\Agent\AgentRegistered;
use App\Helpers\Quotation\Interfaces\RegistrationQuotationHelperInterface;
use App\Tenant\Models\Agency;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Booking;
use Auth;

class AgentRegistrationQuotationHelper extends RegistrationQuotationHelper implements
    RegistrationQuotationHelperInterface
{
    public static function validation()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'account'       => 'required',
            'email'         => 'required|email',
            'password'      => 'required|confirmed',
            'agency_name'   => 'required',
            'agency_email'  => 'required',
        ];
    }

    public static function registerUser($request)
    {
        $data = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'role'                  => $request->role,
            'account'               => $request->account,
            'agency_name'           => $request->agency_name,
            'agency_email'          => $request->agency_email,
            'consent'               => $request->consent,
            'is_admin'              => true,
            'password'              => $request->password,
            'password_confirmation' => $request->password,
        ];

        $data['agency_id'] = self::creatAgency($data['agency_name'], $data['agency_email']);

        return app(\App\Http\Controllers\Tenant\Auth\Agents\RegisterController::class)->create($data);
    }

    public static function user($request)
    {
        return Agent::where('email', $request->email)->first();
    }

    public static function createBooking($cart, $user_id = null, $user_role = null): Booking
    {
        if (! is_array($cart)) {
            $cart = json_decode($cart, true);
        }

        $bookingDetails = [
            'courses' => $cart['price']['courses'],
            'addons' => $cart['price']['addons'],
            'accommodation' => $cart['price']['accommodation'],
            'transfer' => $cart['price']['transfer'],
            'totalPrice' => $cart['price']['total'],
            'details' => $cart,
        ];

        Booking::create(
            [
                'quotation_id' => request()->quotation->id,
                'user_id' => $user_id,
                'invoice' => $bookingDetails,
                'object' => 'agent',
            ]
        );
    }

    /**
     * @param $agency_name
     * @param $agency_email
     * @return mixed agency_id
     */
    private static function creatAgency($agency_name, $agency_email)
    {
        $agency = Agency::create([
            'name' => $agency_name,
            'email' => $agency_email,
        ]);

        return $agency->id;
    }

    public static function afterRegistrationByRoleHandler($user, $request, $school)
    {
        $agency = Agency::find($user->agency_id);
        event(new AgentRegistered($user, $school, $agency));
        event(new AgencyIsCreated($agency));

        if (Auth::guard('agent')->loginUsingId($user->id)) {
            return 'school.agent.home';
        } else {
            return 'school.agent.login';
        }
    }

    public static function redirectIfUserExist()
    {
        return 'school.agent.home';
    }
}
