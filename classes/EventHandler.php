<?php

namespace Sixgweb\Forms\Classes;

use October\Rain\Database\Model;
use Sixgweb\Attributize\Classes\AbstractEventHandler;

class EventHandler extends AbstractEventHandler
{
    /**
     * Title used in backend navigation and field editor
     *
     * @return string
     */
    protected function getTitle(): string
    {
        return 'Entry Field';
    }

    /**
     * Model class that stores the field data
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return \Sixgweb\Forms\Models\Entry::class;
    }

    /**
     * Frontend component to inject Attributize Fields component
     * into. Return null if no frontend editing required.
     *
     * @return string|null
     */
    protected function getComponentClass(): ?string
    {
        return \Sixgweb\Forms\Components\Entry::class;
    }

    /**
     * Controller class responsible for editing model class
     *
     * @return string
     */
    protected function getControllerClass(): string
    {
        return \Sixgweb\Forms\Controllers\Entries::class;
    }

    /**
     * Logic used to retrieve model via the frontend component.
     * 
     * @param [type] $component
     * @return Model
     */
    protected function getComponentModel($component): Model
    {
        return $component->getEntry() ?? new ($this->getModelClass())();
    }

    /**
     * Existing backend plugin menu parameters.  Used to insert new
     * link to edit fields. 
     *
     * @return array
     */
    protected function getBackendMenuParameters(): array
    {
        return [
            'owner' => 'Sixgweb.Forms',
            'code' => 'forms',
            'url' => \Backend::url('sixgweb/forms/entries/fields')
        ];
    }

    protected function getAllowCreateFileUpload(): bool
    {
        return true;
    }
}
