<?php

return [
    'form' => [
        'tabs' => [
            'details' => 'Details',
            'settings' => 'Settings',
            'submitting' => 'Submitting',
            'saving' => 'Saving',
            'throttling' => 'Throttling',
        ],
        'options' => [
            'seconds' => 'Seconds',
            'minutes' => 'Minutes',
            'hours' => 'Hours',
            'days' => 'Days',
        ],
        'is_enabled' => 'Is Enabled',
        'name' => 'Name',
        'description' => 'Description',
        'confirmation_message' => 'Confirmation Message',
        'confirmation_message_description' => 'The message to display after the form has been submitted.  Not shown if redirected in Settings',
        'confirmation_message_hidden' => 'Confirmation Message not available when Settings->Redirect is set',
        'redirect_url' => 'Submission Redirect URL',
        'redirect_url_description' => 'URL to redirect to after submission.  Leave blank to show form confirmation message',
        'save_entries' => 'Save Entries',
        'save_entries_description' => 'Save form entries to database',
        'purge_entries' => 'Purge Entries',
        'purge_entries_description' => 'Purge form entries from database, after a specified number of days',
        'purge_days' => 'Purge Days',
        'purge_days_description' => 'Number of days to keep form entries in database',
        'throttle_entries' => 'Throttle Entries',
        'throttle_entries_description' => 'Limit the number of Entries Allowed in a given Time Period/Unit',
        'throttle_count' => 'Entries Allowed',
        'throttle_count_description' => 'Number of entries allowed in the given time period',
        'throttle_time_period' => 'Entry Time Period',
        'throttle_time_period_description' => 'Time period to limit entries',
        'throttle_time_period_unit' => 'Entry Time Period Unit',
        'throttle_time_period_unit_description' => 'Unit of time used for the time period value',
        'throttle_ip' => 'Throttle by IP Address',
        'throttle_ip_description' => 'Throttle entries by IP address, instead of session ID.  If user is logged in, throttling is done by user ID regardless of this setting',
    ]
];
