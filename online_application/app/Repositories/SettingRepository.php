<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Tenant\Models\Setting;

class SettingRepository implements SettingRepositoryInterface
{
    public function all()
    {
        return Setting::all();
    }

    public function findOrFail($id)
    {
        // TODO: Implement findOrFail() method.
    }

    public function create($input)
    {
        return Setting::create($input);
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
        foreach ($data as $slug => $value) {
            $setting = Setting::firstOrNew(['slug' => $slug]);
            $setting->group = $group;

            if ($value) {
                $setting->data = (is_array($value)) ? array_filter($value) : $value;
            }
            $setting->save();
        }
    }
}
