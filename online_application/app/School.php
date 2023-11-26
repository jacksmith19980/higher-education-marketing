<?php

namespace App;

use Storage;
use App\Plan;
use Carbon\Carbon;
//use App\Tenant\Traits\ForSystem;
use App\TenantConnection;
use App\Tenant\Traits\Scope;
use App\Tenant\Models\ApiKey;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\IsTenant;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class School extends Model implements Tenant
{
    //  use IsTenant, ForSystem;
    use IsTenant;
    use Scope;

    protected $fillable = [
        'name',
        'uuid',
        'slug',
        'logo',
        'description',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static $modelName = 'schools';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function connection()
    {
        return $this->hasOne(TenantConnection::class);
    }

    public function settings()
    {
        return Setting::byGroup();
    }

    public function getLogoAttribute()
    {
        $settings = $this->settings();

        return isset($settings['branding']['logo']) ?
        Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'] , Carbon::now()->addMinutes(5)) : '';
    }

    public function scopeByUuid($builder, $uuid)
    {
        return $builder->where('uuid', $uuid);
    }


    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function setSlugAttribute($value)
    {
        if (static::where('slug', $value)->exists())
            return $this->setSlugAttribute($this->incrementSlug($value));

        $this->attributes['slug'] = $value;
    }

    private function incrementSlug(string $value): string
    {
        $words = explode("-", $value);
        $lastWord = $words[count($words) - 1];

        if (!is_numeric($lastWord))
            return $value . '-1';

        $words[count($words) - 1] = ((int) $lastWord + 1);
        return implode('-', $words);
    }


}
