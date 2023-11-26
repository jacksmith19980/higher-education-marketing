<?php

namespace App\Repositories\Interfaces;

interface SettingRepositoryInterface extends RepositoryInterface
{
    public function firstOrNew(array $data, $group);
}
