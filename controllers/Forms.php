<?php

namespace Sixgweb\Forms\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Sixgweb\Conditions\Classes\ConditionersManager;

/**
 * Forms Backend Controller
 */
class Forms extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['sixgweb.forms.manage_forms'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Sixgweb.Forms', 'forms', 'forms');
        ConditionersManager::instance()->addConditionerGroup('Form', 'Sixgweb\Forms\Models\Form');
    }
}
