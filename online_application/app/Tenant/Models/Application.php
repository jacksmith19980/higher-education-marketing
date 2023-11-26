<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\Scope;
use App\Tenant\Models\Campus;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\Publishable;
use App\Tenant\Models\AgencySubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Application extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;
    use  HasCampuses;

    public static $modelName = 'applications';

    protected $fillable = ['title', 'description', 'slug', 'layout', 'properties', 'object'];

    protected $casts = [
        'sections_order' => 'array',
        'properties' => 'array',
    ];


    protected $app_actions = [

        'Redirect-To-Url' => [
            'title' => 'Redirect To URL',
            'icon'  => 'fas fa-external-link-alt',
        ],

        'Send-Notification-Email' => [
            'title' => 'Send Notification Email',
            'icon'  => 'fas fa-bell',
        ],

        'Send-Thank-You-Email' => [
            'title' => 'Send Thank You Email',
            'icon'  => 'fas fa-envelope',
        ],

        'e-Signature' => [
            'title' => 'e-Signature',
            'icon'  => 'fab fa-sass',
        ],
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    /* public function fields(){
        return $this->manyThroughMany( Field::class,  Section::class ,  'section_id' , 'id' , 'section_id');
    } */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Quotation::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function agencySubmissions()
    {
        return $this->hasMany(AgencySubmission::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function PaymentGateways()
    {
        return $this->hasMany(PaymentGateway::class);
    }

    public function paymentGateway()
    {
        return $this->PaymentGateways()->orderBy('id', 'desc')->first();
    }

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    public function actions()
    {
        return $this->hasMany(ApplicationAction::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function invoiceable()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable')->withTimestamps();
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class , 'campus_application');
    }

    /**
     * Get Application's submissions status
     * @param  [Application] $applications [School Applicatins]
     * @return [array] $count  [Count Of Submissions Status]
     */
    public function getStatusCountAttribute()
    {
        $count = [
            'Started'   => 0,
            'Submitted' => 0,
            'Updated'   => 0,
            'Approved'  => 0,
            'Paid'      => 0,
        ];

        if (isset($this->submissions)) {
            foreach ($this->submissions as $submission) {


                if ($submission['steps_progress_status'] == 100) {
                    $count['Submitted'] += 1;
                } else {
                    $count['Started'] += 1;
                }

                if ($submission->status == 'Paid') {
                    $count['Paid'] += 1;
                }

            }
        }

        return $count;
    }

    public function getStatusAttribute()
    {
        if (! $submission = $this->submissions->first()) {
            return false;
        }
        $status = $submission->status;

        return isset($status) ? $status : 'New';
    }

    public function getOnTimeSubmissionAttribute()
    {
        $properties = $this->properties;

        return (isset($properties['disable_resubmission']) && $properties['disable_resubmission'] == 1) ? true : false;
    }

    public function scopeBySlug(Builder $builder, $slug)
    {
        return $builder->where('slug', $slug);
    }

    public function getFieldsType()
    {
        return $this->fieldsType;
    }

    public function getPaymentGateways()
    {
        return $this->paymentGateways;
    }

    public function getIntegrations()
    {
        return $this->integrations;
    }

    public function getActions()
    {
        return $this->app_actions;
    }

    public function scopeStudent(Builder $builder)
    {
        return $builder->where('object', 'student');
    }

    public function scopeAgency(Builder $builder)
    {
        return $builder->where('object', 'agency');
    }

    public function lockable()
    {
        return isset($this->properties['lock_submission']) && $this->properties['lock_submission'] == 1;
    }

    /**
     * Check if the application require Login or no
     */
    /* public function noLogin(){
        return (isset($this->properties['no_login']))? true : false;
    }
 */
    public function getLoginAttribute()
    {
        return (! isset($this->properties['no_login'])) ? true : false;
    }

    public function getRouteAttribute()
    {
        return ($this->login) ? 'application.show' : 'application.show.no-login';
    }

    public function getProgramFieldName($byMapping = false , $all = false)
    {
        $field = self::sections()->Join('fields', 'fields.section_id', '=', 'sections.id')
        ->select([
            'fields.*',
            'fields.properties',
            'fields.name',
        ])->whereJsonContains('fields.properties->type', 'program');

        if($byMapping){
            $field = $field->orWhereJsonContains('fields.properties->map' , 'params|program');
        }
        if(!$all){
            $field = $field->get()->first();

            return isset($field->name) ? $field->name : '';
        }
        $fields = $field->pluck('name')->toArray();

        return $fields;
    }

    public function getCourseFieldName(): string
    {
        $field = self::sections()->Join('fields', 'fields.section_id', '=', 'sections.id')
        ->select([
            'fields.*',
            'fields.properties',
            'fields.name',
        ])->whereJsonContains('fields.properties->type', 'course')
       ->get()->first();

        return isset($field->name) ? $field->name : '';
    }

    /**
    * Find Application by Object
    * @param Builder $builder [description]
    * @param [type] $object [description]
    * @return [type] [description]
    */
    public function scopeByObject(Builder $builder, $object)
    {
        $builder->where('object', $object);
    }
}
