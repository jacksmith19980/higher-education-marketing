<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class AssistantBuilder extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'assistantsBuilder';
    protected $table = 'assistants_builder';
    protected $fillable = ['title', 'slug', 'properties', 'description', 'help_logo', 'help_title', 'help_content'];
    protected $casts = ['properties' => 'array', 'help_logo' => 'array'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function assistant()
    {
        return $this->hasMany(Assistant::class);
    }
}
