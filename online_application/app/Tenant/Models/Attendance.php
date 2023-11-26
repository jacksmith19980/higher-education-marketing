<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'attendances';
    protected $guarded = [];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
