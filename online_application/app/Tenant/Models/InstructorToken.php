<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class InstructorToken extends Model
{
    use ForTenants;

    protected $fillable = ['token', 'expired'];
    protected $table = 'instructor_token';

    /**
     * Find Valid Token
     */
    public function scopeValid($builder, $email)
    {
        return $builder->where('expired', false);
    }

    public function agent()
    {
        return $this->belongsTo(Instructor::class);
    }
}
