<?php

namespace App\Helpers\School;

use App\Tenant\Models\Plugin;
use URL;

/**
 * Application Helper
 */
class PluginsHelper
{
    public $plugin;
    public $config;
    public $model;

    public function plugin($plugin)
    {
        $this->plugin = $plugin;
        $this->model = Plugin::where('name', $this->plugin)->first();

        return $this;
    }

    public function config($prop = false)
    {
        $this->config = include resource_path('views/back/plugins/'.$this->plugin.'/config.php');

        if ($prop) {
            return $this->config[$prop];
        } else {
            return $this->config;
        }
    }

    public function isActive()
    {
        if (! $this->model) {
            return false;
        }

        return $this->model->published;
    }

    public static function getPlugins($type)
    {
        return Plugin::where('type', $type)->get();
    }

    public static function getPluginsList($type)
    {
        return Plugin::where([
            'type'      =>  $type,
            'published' => true
        ])->pluck('name' , 'name')->toArray();
    }


}
