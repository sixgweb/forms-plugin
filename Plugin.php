<?php

namespace Sixgweb\Forms;

use Backend;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Sixgweb\Forms\Models\Form;
use Sixgweb\Forms\Models\Entry;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        'Sixgweb.Attributize',
        'Sixgweb.Conditions',
        'Sixgweb.AttributizeForms',
        'Sixgweb.ConditionsForms',
    ];

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
                    /*'settings' => [
                        'label' => 'Settings',
                        'icon' => 'icon-gear',
                        'url' => Backend::url('sixgweb/forms/settings/update/sixgweb/forms/settings'),
                        'permissions' => ['sixgweb.forms.manage_settings'],
                        'order'       => 300,
                    ]*/
                ]
            ],
        ];
    }

    /**
     * Automatically purge entries
     *
     * @param object $schedule
     * @return void
     */
    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            foreach (Form::where('purge_entries', 1)->get() as $form) {
                $olderThan = Carbon::now()->subdays($form->purge_days);
                $entries = Entry::where('form_id', $form->id)
                    ->where('created_at', '<=', $olderThan)
                    ->get();
                foreach ($entries as $entry) {
                    $entry->delete();
                }
            }
        })->daily();
    }
}
