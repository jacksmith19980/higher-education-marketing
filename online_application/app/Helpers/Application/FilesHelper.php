<?php

namespace App\Helpers\Application;

use App\Tenant\Models\Campus;
use App\Tenant\Models\File;

class FilesHelper
{
    public static function getFile($fileName)
    {
        if ($file = auth()->guard('student')->user()->files()->byName($fileName)->first()) {
            return $file;
        }

        return false;
    }

    public static function getOriginalName($fileName)
    {
        $file = auth()->guard('student')->user()->files()->byName($fileName)->first();

        return $file->name;
    }
}
