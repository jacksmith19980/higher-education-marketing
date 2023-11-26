<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Booking;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\Student;
use App\Tenant\Models\Contract;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Models\SubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Application\ApplicationStatusHelpers;
use Auth;
class Submission extends Model
{
    use ForTenants;
    use SoftDeletes;
    use HasCampuses;

    protected $fillable = ['data', 'status'];

    protected $casts = ['data' => 'array', 'properties' => 'array'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get Submission's  Contracts
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function statuses()
    {
        return $this->hasMany(SubmissionStatus::class)->orderBy('id');
    }

    public function statusLast()
    {
        $status = (!empty($this->statuses->last()) && $this->statuses->last() != null) ? $this->statuses->last()->status : '';
        return ApplicationStatusHelpers::statusLabel($status);
    }

    public function getCompleteAttribute()
    {
        if (! $this->application || ! $sections = $this->application->sections) {
            return false;
        }

        return (isset($this->properties['step'])) && ($this->properties['step'] == $sections->count());
    }

    public function isLocked()
    {
        return isset($this->data['review']) && $this->data['review'] == 1;
    }

    public function invoiceable()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable')->withTimestamps();
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function scopeByCampus($query , $isPermitted = true)
    {
        if(!$isPermitted){
            $applications = Application::byCampus($isPermitted)->pluck('id')->toArray();
            $query->whereIn('application_id', $applications);
        }
        return $query;
    }

    public function getCampusesAttribute()
    {
        $application = $this->application()->first();
        return $application ? $application->campuses : [];
    }
    /**
     * Find Application Submission By Student and Application
     * @param  [Builder] $builder     [description]
     * @param  [Student] $student     [description]
     * @param  [Application] $application [description]
     * @return [Builder] Query Builder
     */
    public function scopeByStudentAndApplication(Builder $builder, Student $student, Application $application)
    {
        return $builder->where('student_id', $student->id)->where('application_id', $application->id);
    }

    public function scopeByStudent(Builder $builder, Student $student)
    {
        return $builder->where('student_id', $student->id);
    }
}
