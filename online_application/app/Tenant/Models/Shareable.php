<?php

namespace App\Tenant\Models;

use App\Tenant\DocumentBuilder;
use App\Tenant\Models\Instructor;
use App\Tenant\Models\Student;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shareable extends Model
{
    use HasFactory;
    use ForTenants;

    protected $fillable = ['shareable_id', 'shareable_type', 'documentable_id', 'documentable_type', 'is_active'];

    public function students()
    {
        return $this->morphedByMany(Student::class, 'shareable');
    }

    public function instructors()
    {
        return $this->morphedByMany(Instructor::class, 'shareable');
    }

    public function documentBuilders()
    {
        return $this->morphedByMany(DocumentBuilder::class, 'documentable');
    }

    public function shareable()
    {
        return $this->morphTo();
    }
    
    public function document()
    {
        return $this->morphTo('documentable');
    }
    
}
