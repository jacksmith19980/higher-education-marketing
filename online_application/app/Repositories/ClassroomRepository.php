<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ClassroomRepositoryInterface;
use App\Tenant\Models\Classroom;

class ClassroomRepository implements ClassroomRepositoryInterface
{
    private $classroom;

    public function all()
    {
        return Classroom::with('campus')->active()->orderCampus()->OrderTitle()->get();
    }

    public function findOrFail($id)
    {
        return Classroom::findOrFail($id);
    }

    public function create($input)
    {
        return Classroom::create($input);
    }

    public function fill($classroom, $input)
    {
        return $classroom->fill($input)->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function campus($campus)
    {
        return $this->classroom->campus()->associate($campus)->save();
    }

    public function get()
    {
        return $this->classroom;
    }

    public function save()
    {
        return $this->classroom->save();
    }
}
