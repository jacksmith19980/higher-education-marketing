<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Lessoneable extends Model
{
    use HasFactory;
    use ForTenants;
    use Scope;

    public static $modelName = 'lessoneables';
    protected $fillable = ['id', 'lesson_id', 'lessonable_type', 'lessoneable_id', 'created_at','updated_at'];
}
