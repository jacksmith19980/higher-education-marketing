<?php

namespace App\Repositories\Interfaces;

use App\Tenant\Manager;

interface RepositoryInterface
{
    public function all();

    public function findOrFail($id);

    public function create($input);

    public function fill($model, $input);

    public function delete($model);
}
