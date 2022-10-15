<?php

namespace Sixgweb\Forms\Models;

use Model;
use Schema;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'sixgweb_forms_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
