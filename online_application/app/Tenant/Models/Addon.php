<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Course;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;
    use ForTenants;

    protected $fillable = ['category', 'title', 'price', 'price_type', 'object_id', 'properties', 'key'];
    protected $casts = ['properties' => 'array'];

    public static $modelName = 'addons';

    public function course()
    {
        return $this->belongsTo(Course::class, 'id', 'object_id');
    }

    public function invoiceable()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable')->withTimestamps();
    }
}
