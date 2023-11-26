<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use ForTenants;
    use Scope;

    protected $fillable = ['title', 'label'];
    public static $modelName = 'applicationStatus';

}