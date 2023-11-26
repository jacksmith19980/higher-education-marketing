<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Tenant\Manager;
use Illuminate\Support\Facades\Cache;

class CachingSettingRepository implements SettingRepositoryInterface
{
    protected $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function all()
    {
        return Cache::rememberForever('setting'.$this->getTenantTag().'.all', function () {
            return $this->settingRepository->all();
        });
    }

    public function findOrFail($id)
    {
        // TODO: Implement findOrFail() method.
    }

    public function create($input)
    {
        // TODO: Implement create() method.
    }

    public function fill($settings, $input)
    {
        // TODO: Implement fill() method.
    }

    public function delete($model)
    {
        // TODO: Implement delete() method.
    }

    public function firstOrNew(array $data, $group)
    {
        Cache::forget('setting'.$this->getTenantTag().'.all');

        return $this->settingRepository->firstOrNew($data, $group);
    }

    protected function getTenantTag()
    {
        return app()[Manager::class]->getTenant()->uuid;
    }
}
