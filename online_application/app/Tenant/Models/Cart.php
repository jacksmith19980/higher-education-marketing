<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use ForTenants;

    protected $casts = ['data' => 'array'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function submission()
    {
        return $this->belongsTo(Submission ::class);
    }

    public function scopeByStudent(Builder $builder, Student $student)
    {
        return $builder->where('student_id', $student->id);
    }

    public function scopeByStudentAndApplication(Builder $builder, Student $student, Application $application)
    {
        return $builder->where('student_id', $student->id)->where('application_id', $application->id);
    }
}
