<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'modules';
    protected $fillable = ['title', 'properties'];
    protected $casts = ['properties' => 'array'];
    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
