<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Student;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use ForTenants;

    protected $fillable = ['token', 'expired'];
    protected $table = 'student_token';

    /**
     * Find Valid Token
     */
    public function scopeValid($builder, $email)
    {
        return $builder->where('expired', false);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
