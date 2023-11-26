<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Section;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use ForTenants;

    public static $modelName = 'notifications';

    protected $fillable = ['object_id', 'object', 'route', 'text', 'status'];
}
