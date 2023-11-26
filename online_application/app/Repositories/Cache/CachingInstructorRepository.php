<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\InstructorRepositoryInterface;
use App\Tenant\Manager;
use Illuminate\Support\Facades\Cache;

class CachingInstructorRepository implements InstructorRepositoryInterface
{
    protected $instructorRepository;

    public function __construct(InstructorRepositoryInterface $instructor)
    {
        $this->instructorRepository = $instructor;
    }

    public function all()
    {
        return Cache::rememberForever('instructor.'.$this->getTenantTag().'.all', function () {
            return $this->instructorRepository->all();
        });
    }

    public function findOrFail($id)
    {
        return Cache::rememberForever('instructor.'.$this->getTenantTag().'.'.$id, function () use ($id) {
            return $this->instructorRepository->findOrFail($id);
        });
    }

    public function create($input)
    {
        Cache::forget('instructor.'.$this->getTenantTag().'.all');

        return $this->instructorRepository->create($input);
    }

    public function fill($instructor, $input)
    {
        Cache::forget('instructor.'.$this->getTenantTag().'.all');
        Cache::forget('instructor.'.$this->getTenantTag().'.'.$instructor->id);

        return $this->instructorRepository->fill($instructor, $input);
    }

    public function delete($instructor)
    {
        Cache::forget('instructor.'.$this->getTenantTag().'.all');
        Cache::forget('instructor.'.$this->getTenantTag().'.'.$instructor->id);

        return $this->instructorRepository->delete($instructor);
    }

    public function campuses($instructor, $campuses)
    {
        return $instructor->campuses($campuses);
    }

    protected function getTenantTag()
    {
        return app()[Manager::class]->getTenant()->uuid;
    }
}
