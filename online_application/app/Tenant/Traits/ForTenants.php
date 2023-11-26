<?php

namespace App\Tenant\Traits;

trait ForTenants
{
    public function getConnectionName()
    {
        if (request('school')) {
            return 'tenant';
        }

        if (session()->has('tenant')) {
            return 'tenant';
        } else {
            return false;
        }
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
