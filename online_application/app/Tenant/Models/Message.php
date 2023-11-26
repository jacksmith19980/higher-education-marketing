<?php

namespace App\Tenant\Models;

use App\User;
use App\Tenant\Traits\Scope;
use App\Tenant\Models\Recipient;
use App\Tenant\Models\Attachment;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;
    use  HasCampuses;

    public static $modelName = 'message';

    protected $guarded = [];

     public function scopeMain(Builder $builder , $isMain = true)
    {
        if($isMain)
        {
            $builder->whereNull('parent_id');
        }
        $builder;
    }
    public function sender()
    {
        return $this->morphTo();
    }

     public function attachments()
    {
        return $this->morphMany(Attachment::class, 'object');
    }

    public function getOwnerAttribute()
    {
        if($this->sender_type == 'App\User'){
            return User::find($this->sender_id);
        }
        return $this->sender;
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class);
    }

    public function replies()
    {
        return $this->hasMany(Message::class , 'parent_id');
    }

    public function recipient($rec = null)
    {
        if($rec){
            $recipient = $this->recipients->map(function ($recipient) use ($rec) {
                $recId = is_object($rec) ? $rec->id : $rec;
                if(isset($recipient->recipient) && $recipient->recipient->id == $recId) {
                    return $recipient;
                }
            })->first();

            if(!$recipient)
            {
                return $this->recipients->first();
            }
            return $recipient;
        }
        return $this->recipients->first();
    }

    public function parent()
    {
        return $this->belongsTo(Message::class , 'parent_id');
    }

}
