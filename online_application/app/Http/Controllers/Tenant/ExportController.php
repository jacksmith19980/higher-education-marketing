<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Application;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export(Application $application, Request $request)
    {
        $submissions = $application->submissions()->pluck('data')->toArray();

        $fields = [];
        $application->sections()->with('fields')->each(function ($section) use (&$fields) {
            $fields = $fields + $section->fields->pluck('label', 'name')->toArray();
        });
        $file = $this->generateFile($submissions, $fields);
    }

    /**
     * Generate the file
     *
     * @param [type] $data
     * @param [type] $fields
     * @return void
     */
    protected function generateFile($data, $fields)
    {
        dd($data);
    }
}
