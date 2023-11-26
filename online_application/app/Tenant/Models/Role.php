<?php
namespace App\Tenant\Models;

use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role as BaseRole;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class Role extends BaseRole
{


    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = [
            'name'          => $attributes['name'],
            'guard_name'    => $attributes['guard_name'],
            'school_id'     => $attributes['school_id']
        ];
        if (PermissionRegistrar::$teams) {
            if (array_key_exists(PermissionRegistrar::$teamsKey, $attributes)) {
                $params[PermissionRegistrar::$teamsKey] = $attributes[PermissionRegistrar::$teamsKey];
            } else {
                $attributes[PermissionRegistrar::$teamsKey] = app(PermissionRegistrar::class)->getPermissionsTeamId();
            }
        }

        if (static::findByParam($params)) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

}
