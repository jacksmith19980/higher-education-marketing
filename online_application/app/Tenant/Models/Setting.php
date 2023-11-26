<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use ForTenants;

    public static $modelName = 'settings';

    protected $fillable = ['title', 'data', 'slug', 'group'];

    protected $casts = ['data' => 'array'];

    public static function byGroup($group = null)
    {
        if ($group) {
            $schoolSettings = self::where('group', $group)->get();
        //return $settings;
        } else {
            $schoolSettings = self::all();
        }

        $settings = [];

        foreach ($schoolSettings as $setting) {
            $settings[$setting->group][$setting->slug] = $setting->data;
        }

        return $settings;
    }
}
