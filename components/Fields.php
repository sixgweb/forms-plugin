<?php

namespace Sixgweb\Forms\Components;

use Sixgweb\Attributize\Components\Fields as FieldsBase;

/**
 * Fields Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class Fields extends FieldsBase
{
    /**
     * componentDetails
     */
    public function componentDetails()
    {
        return [
            'name' => 'Entry Fields',
            'description' => 'Display Attributize Fields for Sixgweb.Forms'
        ];
    }
}
