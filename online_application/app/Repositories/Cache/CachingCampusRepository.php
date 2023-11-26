<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Tenant\Manager;
use Illuminate\Support\Facades\Cache;

class CachingCampusRepository implements CampusRepositoryInterface
{
    protected $instructor_repository;

    public function __construct(CampusRepositoryInterface $campus)
    {
        $this->instructor_repository = $campus;
    }

    public function all()
    {
        return Cache::rememberForever('campus.'.$this->getTenantTag().'.all', function () {
            return $this->instructor_repository->all();
        });
    }

    public function findOrFail($id)
    {
        return Cache::rememberForever('campus.'.$this->getTenantTag().'.'.$id, function () use ($id) {
            return $this->instructor_repository->findOrFail($id);
        });
    }

    public function create($input)
    {
        Cache::forget('campus.'.$this->getTenantTag().'.all');

        return $this->instructor_repository->create($input);
    }

    public function fill($campus, $input)
    {
        Cache::forget('campus.'.$this->getTenantTag().'.all');
        Cache::forget('campus.'.$this->getTenantTag().'.'.$campus->id);

        return $this->instructor_repository->fill($campus, $input);
    }

    public function delete($model)
    {
        Cache::forget('campus.'.$this->getTenantTag().'.all');
        Cache::forget('campus.'.$this->getTenantTag().'.'.$model->id);

        return $this->instructor_repository->delete($model);
    }

    protected function getTenantTag()
    {
        return app()[Manager::class]->getTenant()->uuid;
    }
}
