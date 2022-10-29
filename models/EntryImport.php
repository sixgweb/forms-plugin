<?php

namespace Sixgweb\Forms\Models;

use Sixgweb\Forms\Models\Entry;
use Sixgweb\Forms\Models\Form as FormModel;

class EntryImport extends \Backend\Models\ImportModel
{
    use \Sixgweb\Attributize\Traits\ImportsFieldValues;

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public $fillable = [
        'ImportOptions[form]',
    ];

    public $belongsTo = [
        'form' => [
            FormModel::class,
        ]
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            //Additional step to convert field_values to array
            $data = $this->processImportDataFieldValues($data);

            try {
                $entry = new Entry;
                $entry->fill($data);
                if (!$entry->form_id) {
                    $entry->form_id = post('ImportOptions.form');
                }
                $entry->save();
                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }
}
