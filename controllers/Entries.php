<?php

namespace Sixgweb\Forms\Controllers;

use Backend\Behaviors\ImportExportController;
use BackendMenu;
use Backend\Classes\Controller;

/**
 * Entries Backend Controller
 */
class Entries extends Controller
{
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
        BackendMenu::setContext('Sixgweb.Forms', 'forms', 'entries');
    }

    public function export()
    {
        $behavior = $this->asExtension('ImportExportController');
        if (get('uselist', false)) {
            $behavior->setConfig([
                'export' => [
                    'useList' => [
                        'raw' => true,
                    ]
                ]
            ]);
        }
        return $behavior->export();
    }
}
