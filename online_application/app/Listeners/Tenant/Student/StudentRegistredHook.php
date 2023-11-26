<?php

namespace App\Listeners\Tenant\Student;

use App\Tenant\Models\Campus;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Tenant\Student\StudentRegistred;

class StudentRegistredHook
{
    use Integratable;

    protected $settings;
    protected $plugin;
    protected $integration;

    /**
     * Handle the event.
     *
     * @param  StudentRegistred  $event
     * @return void
     */
    public function __construct()
    {
        $this->settings = Setting::byGroup();
        $this->plugin = Plugin::where([
            'type'      => 'crm',
            'published' => true
        ])->first();
        if($this->plugin){

            $this->integration = $this->getIntegration($this->plugin->name , $this->plugin->properties);
        }else{
            $this->integration = null;
        }

    }


    public function handle(StudentRegistred $event)
    {
        /** Abort if now pushing to Mautic */
        if (!$this->pushToCRM()) {
            return;
        }

        if ($integration = $this->inetgration()) {
            $contact = $event->student;

            $lang = request()->has('lang') ? request('lang') : app()->getLocale();
            $campus  = request()->filled('campus') ? request('campus') : null;
            $country = request()->filled('country') ? request('country') : null;
            $phone   = request()->filled('phone') ? request('phone') : null;
            $requestType = request()->filled('request_type') ? request('request_type') : null;

            $data = [
                'firstname'     => $contact->first_name,
                'lastname'      => $contact->last_name,
                'email'         => $contact->email,
                'language'      => $lang,
                'campus'        => $campus,
                'country'       => $country,
                'phone'         => $phone,
                'requestType'   => $requestType,
            ];

            $stage = null;
            // check if stages are exist
            if (!empty($this->settings['stages'])) {
                $stage = $this->getStage($contact->role);
            }
            $contactType = request()->filled('contact_type') ? request('contact_type') : $this->getContactType($event);

            $data += request()->except("first_name","last_name","email", "role","password","password_confirmation","_token");
            if($this->integration){
                $data = array_filter($this->integration->getRegisterationDefaults($this->settings, $data));
            }
            $integration->createNewContact($data, $contactType, $stage);
            return true;
        }
    }

    protected function pushToCRM()
    {
        if (isset($this->plugin) && $this->plugin->name == 'hubspot') {
        return (isset($this->settings['auth']['hubspot']['push']) && $this->settings['auth']['hubspot']['push'] == 'Yes') ?
        true : false;
        }

        if (isset($this->plugin) && $this->plugin->name == 'mautic') {
        return (isset($this->settings['auth']['mautic_push']) && $this->settings['auth']['mautic_push'] == 'Yes') ? true :
        false;
        }

        if (isset($this->plugin) && $this->plugin->name == 'campuslogin') {
        return (isset($this->settings['auth']['campuslogin']['push']) && $this->settings['auth']['campuslogin']['push'] ==
        'Yes') ? true : false;
        }

    return false;
    }



    protected function getContactType($event)
    {

        // return the same Data array for HS and CampusLogin
        if ( in_array($this->plugin->name , ['hubspot' , 'campuslogin'] ) ) {
        return '';
        } elseif (isset($this->settings['auth']['mautic_contact_type'])) {
            return $this->settings['auth']['mautic_contact_type'];
        } else {
            return $event->contactType;
        }
    }

    protected function getStage($role = 'student')
    {
        switch ($role) {
            case 'parent':
            return $this->settings['stages']['new_parent_stage'];
            break;

            case 'student':
            return $this->settings['stages']['new_account_stage'];
            break;

            default:
            return $this->settings['stages']['new_account_stage'];
            break;
        }
    }
}
