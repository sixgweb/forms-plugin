<?php

namespace Sixgweb\Forms\Updates;

use Seeder;
use Sixgweb\Forms\Models\Form;

class MigrateToSettingsColumn extends Seeder
{
    public function run()
    {
        foreach (Form::get() as $form) {
            $form->settings['save_entries'] = $form->save_entries;
            $form->settings['throttle_entries'] = $form->throttle_entries;
            $form->settings['throttle_count'] = $form->throttle_threshold;
            $form->settings['throttle_time_period'] = $form->throttle_timeout;
            $form->settings['throttle_time_period_unit'] = 'minutes';
            $form->settings['purge_entries'] = $form->purge_entries;
            $form->settings['purge_days'] = $form->purge_days;
            $form->save();
        }
    }
}
