<?php

namespace App\Http\Controllers\Tenant;

use Crypt;
use Response;
use Illuminate\Http\Request;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Setting;
use App\Http\Controllers\Controller;
use App\Helpers\School\PluginsHelper;

class PluginsController extends Controller
{
    public function index(Request $request)
    {
        $pluginsHelper = new PluginsHelper;
        return view('back.plugins.index', compact('pluginsHelper'));
    }


    /**
     * Show PLugin setup form
     */
    public function setup($pluginName)
    {
        $plugin = Plugin::where('name', $pluginName)->first();
        $pluginsHelper = new PluginsHelper;
        $config = $pluginsHelper->plugin($pluginName)->config();
        return view('back.plugins.'.$pluginName.'/form', compact('plugin', 'pluginName' , 'config'));
    }


    // Get Plugin Properties and Decrypt secrect keys
    protected function properties(Request $request, $pluginName, $config)
    {
        $properties = [];
        foreach ($config['fields'] as $field => $rule) {
            $value = (isset($config['secret_keys']) && in_array($field, $config['secret_keys'])) ? Crypt::encrypt($request->{$field}) : $request->{$field};

            if ($request->filled($field)) {
                $properties[$field] = $value;
            }
        }
        if ($plugin = Plugin::where('name', $pluginName)->first()) {
            $properties = array_merge($plugin->properties, $properties);
        }
        return $properties;
    }

    /**
     * Store Plugin in Schools DB
     */
    public function store(Request $request, $pluginName)
    {
        $helper = new PluginsHelper;
        $config = $helper->plugin($pluginName)->config();

        $properties = $this->properties($request, $pluginName, $config);
        $request->validate($config['fields']);
        $published = isset($request->published) ? true : false;

        $plugin = Plugin::updateOrCreate(
            [
                'name'          => $pluginName,
            ],
            [
                'published'     => $published,
                'type'          => $request->type,
                'properties'    => $properties,
            ]
        );

        // Toggle off other CRM connections
        if ($request->type == 'crm' && isset($properties['is_default']) && $properties['is_default']) {
            $plugins = Plugin::where('id', '!=', $plugin->id)
            ->where('type', 'crm')->get();
            if ($plugins) {
                foreach ($plugins as $plugin) {
                    $props = $plugin->properties;
                    $props['is_default'] = false;
                    $plugin->properties = $props;
                    $plugin->published = false;
                    $plugin->update();
                }
            }
        }
        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'status' => ($published) ? 'ACTIVE' : 'INACTIVE',
            ],
        ]);
    }

    /**
     * Authenticate a plugin
     *
     * @param Request $request
     * @param [type] $plugin
     * @return void
     */
    public function auth(Request $request, $plugin)
    {
        // Get Plugin's Helper
        $helper = 'App\\Helpers\\Plugins\\'.ucwords($plugin).'Helper';
        $helper = new $helper;
        $helper->authenticatePlugin($request->code);
    }
}
