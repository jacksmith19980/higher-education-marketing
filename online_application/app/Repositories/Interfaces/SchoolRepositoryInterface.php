<?php

namespace App\Repositories\Interfaces;

interface SchoolRepositoryInterface extends RepositoryInterface
{
    public function byUuid($uuid);

    public function bySlug($slug);
}
