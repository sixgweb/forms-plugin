<?php

namespace Sixgweb\Forms\Models;

use Sixgweb\Forms\Models\Entry;
use Sixgweb\Forms\Models\Form as FormModel;

class EntryExport extends \Backend\Models\ExportModel
{
    public $fillable = [
        'ExportOptions[form]',
    ];

    public $belongsTo = [
        'form' => [
            FormModel::class,
        ]
    ];

    public function exportData($columns, $sessionKey = null)
    {
        $entries = Entry::all();

        $entries->each(function ($entry) use ($columns) {
            //$entry->addVisible($columns);
        });

        return $entries->toArray();
    }
}
