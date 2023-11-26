<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Publishable;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class ApplicationAction extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;

    protected $fillable = ['title', 'action', 'properties'];

    protected $casts = [
            'properties'     => 'array',
        ];

    protected $table = 'application_action';

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
