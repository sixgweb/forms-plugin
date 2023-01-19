<?php

namespace Sixgweb\Forms\Models;

use Sixgweb\Forms\Models\Entry;
use Sixgweb\Forms\Models\Form as FormModel;

class EntryExport extends \Backend\Models\ExportModel
{
    use \Sixgweb\Attributize\Traits\ExportsFieldValues;

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
        $formId = post('ExportOptions.form');
        $entries = $formId ? Entry::where('form_id', $formId)->get() : Entry::all();
        $results = [];

        foreach ($entries as &$entry) {
            //Default data to entry array values, ignoring columns
            $data = $entry->toArray();
            foreach ($columns as $column) {
                $relation = strpos($column, 'field_values.') !== false
                    ? str_replace('.', '_', $column)
                    : null;

                if ($relation && $entry->hasRelation($relation) && $entry->{$relation}) {
                    $type = $entry->getRelationType($relation);
                    switch ($type) {
                        case 'attachOne':
                            $data[$column] = $entry->{$relation}->getPath();
                            break;
                        case 'attachMany':
                            $attachments = $entry->{$relation}->toArray();
                            $data[$column] = implode("\r\n", array_pluck($attachments, 'path'));
                            break;
                        default:
                            $data[$column] = $entry->{$relation};
                            break;
                    }
                }
            }
            $results[] = $data;
        }

        return $this->processExportDataFieldValues($results, $columns);
    }
}
