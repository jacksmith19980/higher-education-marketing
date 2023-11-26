<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalService extends Model
{
    use HasFactory;
    use ForTenants;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'description', 'amount', 'educational_service_categories_id'];

    /**
     * Get Assigned Agent
     */
    public function educationalServiceCategory()
    {
        return $this->belongsTo(EducationalServiceCategory::class, 'educational_service_categories_id');
    }

    public function invoiceable()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable')->withTimestamps();
    }

}
