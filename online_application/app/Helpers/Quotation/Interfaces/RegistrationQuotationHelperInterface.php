<?php

namespace App\Helpers\Quotation\Interfaces;

interface RegistrationQuotationHelperInterface
{
    public static function registerUser($data);

    public static function user($request);

    public static function createBooking($cart, $user_id = null, $user_role = null);

    public static function afterRegistrationByRoleHandler($user, $request, $school);

    public static function redirectIfUserExist();

    public static function validation();
}
