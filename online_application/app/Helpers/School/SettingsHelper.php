<?php

namespace App\Helpers\School;

use App\Plan;
use App\School;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;

/**
 * Settings Helper
 */
class SettingsHelper
{
    use Integratable;

    public static function settings()
    {
        if(!$settings = session('settings-'.session('tenant'))){
            $settings = Setting::byGroup();
        }
        return $settings;
    }

    public static function updateSchoolSettings($settings = [])
    {
        if(empty($settings)){
            $settings = Setting::byGroup();
        }

        $campuses = Campus::orderBy('title')->get(['id' , 'title' , 'slug' , 'properties'])->keyBy('id')->toArray();
        $settings['campuses'] = $campuses;
        $school = School::whereUuid(session('tenant'))->first();
        $settings['plan'] = isset($school->plan) ? $school->plan->toArray() : Plan::find(5);
        session()->put('settings-'.session('tenant'), $settings);
        return $settings;
    }


    public static function sendFrom(){

        $settings = self::settings();
        $fromName = isset($settings['mail']['from_name']) ? $settings['mail']['from_name'] : (isset($settings['school']['from_name'])? $settings['school']['from_name'] : 'HEM SP');

        $fromeEmail = isset($settings['mail']['from_email']) ? $settings['mail']['from_email'] : (isset($settings['school']['from_email'])? $settings['school']['from_email'] : 'info@higher-education-marketing.com');

        return [$fromName , $fromeEmail];

    }

    public static function signature()
    {
        $settings = self::settings();
        $signature = isset($settings['mail']['email_signature']) ? $settings['mail']['email_signature'] : ( isset($settings['scool']['email_signature']) ? $settings['school']['email_signature'] : 'Best Regards' );
        return $signature;
    }

    public static function toEmails()
    {
        $settings = self::settings();
        $toEmails = isset($settings['mail']['to_emails']) ? $settings['mail']['to_emails'] : ( isset($settings['scool']['to_emails']) ? $settings['school']['to_emails'] : null );
        return $toEmails;
    }

    public static function currency()
    {
        $settings = self::settings();
        return isset($settings['school']['default_currency'])? $settings['school']['default_currency'] : 'CAD';
    }




}
