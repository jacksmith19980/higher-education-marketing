<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\User;

class UserRepository implements RepositoryInterface
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
        return User::create($input);
    }

    public function fill($school, $input)
    {
        // TODO: Implement fill() method.
    }

    public function delete($model)
    {
        // TODO: Implement delete() method.
    }
}
