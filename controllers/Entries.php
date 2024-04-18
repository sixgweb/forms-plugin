<?php

namespace Sixgweb\Forms\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Facades\Backend;

/**
 * Entries Backend Controller
 */
class Entries extends Controller
{
    protected $scopesInUrl = false;

    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\ImportExportController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var string importExportConfig file
     */
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();
        $this->checkForScopeInUlr();
        $behavior = $this->asExtension('ImportExportController');
        if (get('uselist', false)) {
            $config = $behavior->getConfig('export');
            $behavior->setConfig([
                'export' => [
                    'useList' => [
                        'raw' => true,
                    ],
                    'fileName' => $config['fileName'] ?? 'export',
                ]
            ]);
        }
        BackendMenu::setContext('Sixgweb.Forms', 'forms', 'entries');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
        if ($this->scopesInUrl) {
            return Backend::redirect('sixgweb/forms/entries');
        }
    }


    /**
     * Check if scope is in the url.  If set, update the filter scope and
     * set the flag to redirect without the scope get parameters.
     *
     * @return void
     */
    public function checkForScopeInUlr()
    {
        \Event::listen('backend.filter.extendScopes', function ($widget) {
            if (!($widget->getController() instanceof Entries)) {
                return;
            }

            if ($scopes = get('scope')) {
                foreach ($widget->getScopes() as $scope) {
                    $widget->putScopeValue($scope->scopeName, null);
                }
                foreach ($scopes as $scope => $value) {
                    if ($widget->getScope($scope)) {
                        $widget->putScopeValue($scope, ['value' => $value]);
                        $this->scopesInUrl = true;
                    }
                }
            }
        });
    }

    public function export()
    {
        $behavior = $this->asExtension('ImportExportController');
        if (get('uselist', false)) {
            $config = $behavior->getConfig('export');
            $behavior->setConfig([
                'export' => [
                    'useList' => [
                        'raw' => true,
                    ],
                    'fileName' => $config['fileName'] ?? 'export',
                ]
            ]);
        }
        return $behavior->export();
    }
}
