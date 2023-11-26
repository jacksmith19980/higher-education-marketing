<?php

namespace App\Tenant\Traits\Console;

use App\School;

trait FetchesTenants
{
    public function tenants($ids = null)
    {
        $tenants = School::query();

        if ($ids) {
            $tenants = $tenants->whereIn('id', $ids);
        }

        return $tenants;
    }
}
