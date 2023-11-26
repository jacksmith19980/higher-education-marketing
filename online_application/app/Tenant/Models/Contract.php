<?php

namespace App\Tenant\Models;

use App\User;
use App\Tenant\Traits\Scope;
use App\Tenant\Models\Student;
use App\Tenant\Models\Envelope;
use App\Tenant\Models\Submission;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contract extends Model
{
    use ForTenants;
    use Scope;

    protected $fillable = ['uid', 'envelope_id', 'status', 'properties', 'student_id', 'submission_id', 'user_id', 'url', 'service', 'title'];
    protected $casts = ['properties' => 'array'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class );
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function envelope()
    {
        return $this->belongsTo(Envelope::class);
    }
}
