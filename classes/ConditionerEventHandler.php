<?php

namespace Sixgweb\Forms\Classes;

use Sixgweb\Conditions\Classes\AbstractConditionerEventHandler;

class ConditionerEventHandler extends AbstractConditionerEventHandler
{
    protected function getModelClass(): string
    {
        return \Sixgweb\Forms\Models\Form::class;
    }

    protected function getFieldConfig(): array
    {
        return [
            'label' => 'Form',
            'type' => 'checkboxlist',
            'options' => \Sixgweb\Forms\Models\Form::lists('name', 'id'),
        ];
    }

    protected function getConditionerCallback(): ?callable
    {
        return null;
    }

    protected function getModelOptions(): array
    {
        return $this->getModelClass()::get()->pluck('name', 'id')->toArray();
    }

    protected function getControllerClass(): ?string
    {
        return \Sixgweb\Forms\Controllers\Entries::class;
    }

    protected function getGroupName(): string
    {
        return 'Form';
    }

    protected function getGroupIcon(): string
    {
        return 'bi-file-code-fill';
    }
}
