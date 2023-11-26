<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Tenant\Models\Campus;

class CampusRepository implements CampusRepositoryInterface
{
    private $campus;

    public function all()
    {
        return Campus::all();
    }

    public function findOrFail($id)
    {
        return Campus::findOrFail($id);
    }

    public function create($input)
    {
        return Campus::create($input);
    }

    public function fill($campus, $input)
    {
        return $campus->fill($input)->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function get()
    {
        return $this->campus;
    }
}
