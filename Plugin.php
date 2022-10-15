<?php

namespace Sixgweb\Forms;

use Backend;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Forms',
            'description' => 'No description provided yet...',
            'author'      => 'Sixgweb',
            'icon'        => 'icon-form'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents(): array
    {
        return [
            'Sixgweb\Forms\Components\Entry' => 'Entry',
        ];
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function registerMailTemplates(): array
    {
        return [
            'sixgweb.forms::mail.entry',
        ];
    }

    /**
     * Registers any backend permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions(): array
    {
        return [
            'sixgweb.forms.manage_forms' => [
                'tab' => 'Forms',
                'label' => 'Manage Forms'
            ],
            'sixgweb.forms.manage_entries' => [
                'tab' => 'Forms',
                'label' => 'Manage Entries'
            ],
            'sixgweb.forms.manage_settings' => [
                'tab' => 'Forms',
                'label' => 'Manage Settings',
            ],
        ];
    }

    /**
     * Registers backend navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'forms' => [
                'label'       => 'Forms',
                'url'         => Backend::url('sixgweb/forms/forms'),
                'icon'        => 'icon-file',
                'permissions' => ['sixgweb.forms.*'],
                'order'       => 500,
                'sideMenu' => [
                    'forms' => [
                        'label'       => 'Forms',
                        'url'         => Backend::url('sixgweb/forms/forms'),
                        'icon'        => 'icon-file',
                        'permissions' => ['sixgweb.forms.manage_forms'],
                        'order'       => 100,
                    ],
                    'entries' => [
                        'label'       => 'Entries',
                        'url'         => Backend::url('sixgweb/forms/entries'),
                        'icon'        => 'icon-file-text',
                        'permissions' => ['sixgweb.forms.manage_entries'],
                        'order'       => 200,
                    ],
                    'settings' => [
                        'label' => 'Settings',
                        'icon' => 'icon-gear',
                        'url' => Backend::url('sixgweb/forms/settings/update/sixgweb/forms/settings'),
                        'permissions' => ['sixgweb.forms.manage_settings'],
                        'order'       => 300,
                    ]
                ]
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Forms Settings',
                'description' => 'Manage forms settings.',
                'category'    => 'Forms',
                'icon'        => 'icon-address-card',
                'class'       => 'Sixgweb\Forms\Models\Settings',
                'order'       => 500,
                'keywords'    => 'forms',
                'permissions' => ['sixgweb.forms.manage_settings']
            ]
        ];
    }
}
