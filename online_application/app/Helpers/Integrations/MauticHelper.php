<?php

namespace App\Helpers\Integrations;

use App\Tenant\Traits\Integratable;

class MauticHelper
{
    use Integratable;

    /**
     * Get List of Request Type from Mautic
     *
     * @return array
     */
    public static function getRequestType()
    {
        $data = [];
        if ($mautic = (new static)->inetgration()) {
            $data = $mautic->getFieldList('request_type');
        }

        return $data;
    }

    /**
     * Get List of Contact Types from Mautic
     *
     * @return array
     */
    public static function getContactTypes()
    {
        return self::getMauticListField('contact_type');
    }

    public static function getMauticListField($alias = null)
    {
        if (! $alias) {
            return [];
        }
        $list = [];
        if ($mautic = (new static)->inetgration()) {
            $mauticField = $mautic->getField($alias);
            if (isset($mauticField['properties']['list'])) {
                foreach ($mauticField['properties']['list'] as $item) {
                    $list[$item['value']] = $item['label'];
                }
            }
        }
        $list['agencies'] = 'Agency';
        return $list;
    }
}
