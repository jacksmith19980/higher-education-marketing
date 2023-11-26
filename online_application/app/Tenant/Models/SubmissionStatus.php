<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Submission;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class SubmissionStatus extends Model
{
    use ForTenants;

    protected $fillable = ['status', 'properties', 'submission_id', 'student_id', 'step'];

    protected $casts = ['properties' => 'array'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
