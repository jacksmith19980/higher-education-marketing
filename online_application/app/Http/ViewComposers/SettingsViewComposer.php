<?php

namespace App\Http\ViewComposers;

use App\Plan;
use App\School;
use Illuminate\View\View;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Setting;
use App\Helpers\School\SettingsHelper;

class SettingsViewComposer
{
    public function compose(View $view)
    {
        if (request()->school) {
            if ($school = School::where('slug', request()->school)->first()) {
                session()->put('tenant', $school->uuid);
            }
        }
        if (session('tenant') && !session('settings-'.session('tenant'))) {
            if (!$settings = Setting::byGroup()) {
                $settings = [];
            }
            // Update School Settings session
            $settings = SettingsHelper::updateSchoolSettings($settings);
        }

        $view->with('settings', session('settings-'.session('tenant')));
    }
}
