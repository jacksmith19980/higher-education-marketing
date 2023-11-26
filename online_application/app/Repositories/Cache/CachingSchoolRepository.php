<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\SchoolRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CachingSchoolRepository implements SchoolRepositoryInterface
{
    protected $schoolRepository;

    public function __construct(SchoolRepositoryInterface $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

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
        Cache::forget('schools.all');

        return $this->schoolRepository->create($input);
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
        //return Cache::rememberForever("school.{$uuid}", function () use ($uuid) {
        return $this->schoolRepository->byUuid($uuid);
        //});
    }

    public function bySlug($slug)
    {
        //return Cache::rememberForever('school' . $slug, function () use ($slug) {
        return $this->schoolRepository->bySlug($slug);
        //});
    }
}
