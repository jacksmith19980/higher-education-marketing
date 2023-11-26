<?php

namespace App\Tenant\Traits;

use App\Tenant\Models\Tenant;
use App\TenantConnection;
use Webpatser\Uuid\Uuid;

trait IsTenant
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->uuid = Uuid::generate(4);
        });

        static::created(function ($tenant) {
            $tenant->tenantConnection()->save(static::newDatabaseConnection($tenant));
        });
    }

    protected static function newDatabaseConnection(Tenant $tenant)
    {
        return new TenantConnection([
            'database' => env('DATABASE_PREFIX').$tenant->id,
            'version'  => static::getLatestVersion(),
        ]);
    }

    public function tenantConnection()
    {
        return $this->hasOne(TenantConnection::class, 'school_id', 'id');
    }

    protected static function getLatestVersion()
    {
        return (int) TenantConnection::max('version');
    }

    /**
     * Return Model Name to Use in Routes
     * @return string
     */
    public static function getModelName()
    {
        return self::$modelName;
    }
}
