<?php

namespace Database\Seeders\Tenant;

use App\Tenant\Models\Setting;
use Database\Seeders\Tenant\SettingSeeder;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    protected $settings = [

        'school'    => [
            'main_color'        => '#0080c0',
            'secondary_color'   => '#000000',
            'links_color'       => '#0080c0',
        ],

        'auth'      =>[
            'background_color'  => '#ffffff',
            'text_color'        => '#0080c0',
        ],

        'quotation' => [
            'default_currency' => 'CAD',
        ],
        'applications' => [
            'contact_type' => ['Lead', 'Agent', 'Applicant', 'Student'],
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = Setting::byGroup('school');

        // If we have settings saved don't overwrite it
        if (! empty($settings)) {
            return false;
        }

        foreach ($this->settings as $group=>$setting) {
            foreach ($setting as $slug=>$datat) {
                $this->addSetting($group, $slug, $datat);
            }
        }
    }

    protected function addSetting($group, $slug, $datat)
    {
        Setting::create([
                'group'     => $group,
                'slug'      => $slug,
                'data'      => $datat,
            ]);
    }
}
