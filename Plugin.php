<?php

namespace Sixgweb\Forms;

use Event;
use Backend;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Sixgweb\Forms\Models\Form;
use Sixgweb\Forms\Models\Entry;
use Sixgweb\Forms\Classes\Helper;
use Sixgweb\Forms\Controllers\Forms;
use Sixgweb\Forms\Classes\EventHandler;
use Sixgweb\Conditions\Classes\ConditionersManager;
use Sixgweb\Forms\Classes\ConditionableEventHandler;
use Sixgweb\Forms\Classes\ConditionerEventHandler;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        'Sixgweb.Attributize',
        'Sixgweb.Conditions',
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
            'description' => 'Create forms and collect entries',
            'author'      => 'Sixgweb',
            'icon'        => 'icon-file-text'
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
        if (class_exists(EventHandler::class) && class_exists(ConditionerEventHandler::class)) {
            $this->addEventSubscribers();
        }
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents(): array
    {
        return [
            'Sixgweb\Forms\Components\Entry' => 'formsEntry',
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
            'sixgweb.forms.manage_forms.create' => [
                'tab' => 'Forms',
                'label' => 'Create Forms'
            ],
            'sixgweb.forms.manage_forms.update' => [
                'tab' => 'Forms',
                'label' => 'Update Forms'
            ],
            'sixgweb.forms.manage_forms.delete' => [
                'tab' => 'Forms',
                'label' => 'Delete Forms'
            ],
            'sixgweb.forms.manage_entries' => [
                'tab' => 'Forms',
                'label' => 'Manage Entries'
            ],
            'sixgweb.forms.manage_entries.create' => [
                'tab' => 'Forms',
                'label' => 'Create Entries'
            ],
            'sixgweb.forms.manage_entries.update' => [
                'tab' => 'Forms',
                'label' => 'Update Entries'
            ],
            'sixgweb.forms.manage_entries.delete' => [
                'tab' => 'Forms',
                'label' => 'Delete Entries'
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
                'icon'        => 'bi-file-earmark-code',
                'permissions' => ['sixgweb.forms.*'],
                'order'       => 500,
                'sideMenu' => [
                    'forms' => [
                        'label'       => 'Forms',
                        'url'         => Backend::url('sixgweb/forms/forms'),
                        'icon'        => 'bi-file-earmark-code',
                        'permissions' => ['sixgweb.forms.manage_forms'],
                        'order'       => 100,
                    ],
                    'entries' => [
                        'label'       => 'Entries',
                        'url'         => Backend::url('sixgweb/forms/entries'),
                        'icon'        => 'bi-file-earmark-text',
                        'permissions' => ['sixgweb.forms.manage_entries'],
                        'order'       => 200,
                    ],
                ]
            ],
        ];
    }

    /**
     * Register entryFieldValuesToHTML for use in twig
     *
     * @return void
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'entryFieldValuesToHTML' => [Helper::class, 'entryFieldValuesToHTML'],
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
            foreach (Form::where('settings->purge_entries', 1)->get() as $form) {
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

    protected function addEventSubscribers()
    {
        Event::subscribe(EventHandler::class);
        Event::subscribe(ConditionableEventHandler::class);
        Event::subscribe(ConditionerEventHandler::class);
    }
}
