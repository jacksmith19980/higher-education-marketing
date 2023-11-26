<?php

namespace App\Repositories;

use App\Repositories\Interfaces\InstructorRepositoryInterface;
use App\Tenant\Models\Instructor;

class InstructorRepository implements InstructorRepositoryInterface
{
    private $instructor;

    public function all()
    {
        return Instructor::active()->OrderLastName()->get();
    }

    public function findOrFail($id)
    {
        return Instructor::findOrFail($id);
    }

    public function create($input)
    {
        return Instructor::create($input);
    }

    public function fill($instructor, $input)
    {
        return $instructor->fill($input)->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function campuses($instructor, $campuses)
    {
        return $instructor->campuses()->sync($campuses);
    }

    public function save()
    {
        return $this->instructor->save();
    }

    public function get()
    {
        return $this->instructor;
    }
}
