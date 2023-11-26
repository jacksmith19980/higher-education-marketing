<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use ForTenants;

    protected $fillable = ['object_id', 'properties', 'key', 'date_type'];
    protected $casts = ['properties' => 'array'];

    public static $modelName = 'dates';

    public function course()
    {
        return $this->belongsTo(Course::class, 'id', 'object_id');
    }

    public function getCompletedAttribute()
    {
        return (isset($this->properties['completed']) && $this->properties['completed'] == true);
    }
}
