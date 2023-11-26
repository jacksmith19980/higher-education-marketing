<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use ForTenants;

    public static $modelName = 'customfields';
    protected $table = 'customfields';

    protected $casts = [
        'data' => 'array',
    ];
    public static function byObject($object = null)
    {
        $list = [];

        if ($object) {
            $list = self::where('properties', $object)->get()->keyBy('slug')->toArray();
        } else {
            $customFields = self::all();
            foreach ($customFields as $customField) {
                $list[$customField->properties][$customField->slug] = $customField->toArray();
            }
        }

        return $list;
    }
}
