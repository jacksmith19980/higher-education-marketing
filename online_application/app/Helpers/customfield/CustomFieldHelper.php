<?php

namespace App\Helpers\customfield;

use App\Tenant\Models\CustomField;
use App\Tenant\Models\Customfieldable;

class CustomFieldHelper
{
    public static function getProgramsCustomFields($label = 'name', $value = 'id')
    {
        return CustomField::Where('properties', 'programs')->get()->pluck($label, $value)->toArray();
    }

    public static function getCoursesCustomFields($label = 'name', $value = 'id')
    {
        return CustomField::Where('properties', 'courses')->get()->pluck($label, $value)->toArray();
    }


    public static function getContactsCustomFields($label = 'name', $value = 'id')
    {
        return CustomField::Where('properties', 'students')->get()->pluck($label, $value)->toArray();
    }

    public static function saveCustomfield($request, $object)
    {
        if (! isset($request->customfields) || ! $request->customfields || count($request->customfields) < 1) {
            return $object;
        }

        $customfields = ['customfields' => []];

        foreach ($request->customfields as $slug => $customfield_value) {
            $customfield = CustomField::where('slug', $slug)->first();


            switch ($customfield->field_type) {
                case 'list':
                    foreach ($request->customfields[$slug] as $value) {
                        $position = array_search($value, $customfield->data['values']);
                        $label = $customfield->data['labels'][$position];
                        $customfields['customfields'][$slug][] = [$value => $label];
                    }
                    break;
                case 'text':
                    $customfields['customfields'][$slug] = $customfield_value;
                    break;
                default:
                    # code...
                    break;
            }
        }

        $properties_old_object = $object->properties;
        $object->properties = array_merge($customfields, $properties_old_object);

        return $object;
    }
}
