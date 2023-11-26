<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use ForTenants;
    use Scope;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeByName(Builder $builder, $fileName)
    {
        return $builder->where('name', $fileName);
    }
}
