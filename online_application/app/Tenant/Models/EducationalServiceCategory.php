<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalServiceCategory extends Model
{
    use HasFactory;
    use ForTenants;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['object', 'name', 'is_published', 'color'];

}
