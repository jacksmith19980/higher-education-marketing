<?php

namespace App\Services\Tenant;

use App\Tenant\Models\EducationalService;

class ServicesService
{
    public function __construct()
    {

    }

    /**
     * Get List of Services
     */
    public function getServices()
    {
        return EducationalService::get()->pluck('name', 'id')->toArray();
    }
}
