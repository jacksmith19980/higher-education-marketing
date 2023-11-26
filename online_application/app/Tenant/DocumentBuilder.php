<?php

namespace App\Tenant;

use App\Tenant\Models\Tenant;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentBuilder extends Model
{
    use HasFactory;
    use ForTenants;

    protected $fillable = ['name', 'selector', 'document'];

    public function shareable()
    {
        return $this->morphMany(Shareable::class, 'documentable');
    }
}
