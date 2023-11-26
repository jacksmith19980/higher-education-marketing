<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SchoolRepositoryInterface;
use App\School;

class SchoolRepository implements SchoolRepositoryInterface
{
    public function all()
    {
        // TODO: Implement all() method.
    }

    public function findOrFail($id)
    {
        // TODO: Implement findOrFail() method.
    }

    public function create($input)
    {
        return School::create($input);
    }

    public function fill($school, $input)
    {
        // TODO: Implement fill() method.
    }

    public function delete($model)
    {
        // TODO: Implement delete() method.
    }

    public function byUuid($uuid)
    {
        return School::where('uuid', $uuid);
    }

    public function bySlug($slug)
    {
        return School::where('slug', $slug);
    }
}
