<?php

namespace App\Http\Controllers\Tenant\Plugins;

use App\Helpers\School\PluginsHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\PluginsController;
use App\Tenant\Models\Plugin;
use Illuminate\Http\Request;
use Response;

class DocusignController extends PluginsController
{
    public function auth(Request $request, $plugin)
    {
    }
}
