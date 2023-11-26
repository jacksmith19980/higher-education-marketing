<?php

namespace App\Tenant\Models;

use App\User;
use App\Tenant\Traits\Scope;
use App\Tenant\Models\Message;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\Publishable;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;
    use HasCampuses;

    protected $guarded = [];
    protected $casts = [
        'is_read'   => 'datetime'
    ];
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function recipient()
    {
        return $this->morphTo();
    }


    public function getRecipentDetailsAttribute()
    {
        if($this->recipient_type == 'App\User'){
            return User::find($this->recipient_id);
        }
        return $this->recipient;
    }

}
