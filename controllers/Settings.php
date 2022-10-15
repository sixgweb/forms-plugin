<?php

namespace Sixgweb\Forms\Controllers;

use BackendMenu;
use System\Controllers\Settings as SettingsController;


/**
 * Memberships Settings Controller
 */
class Settings extends SettingsController
{

    public $requiredPermissions = ['sixgweb.forms.manage_settings'];

    public function __construct()
    {
        parent::__construct();
        $this->addViewPath(base_path() .  '/modules/system/controllers/settings');
        BackendMenu::setContext('Sixgweb.Forms', 'forms', 'settings');
    }
}
