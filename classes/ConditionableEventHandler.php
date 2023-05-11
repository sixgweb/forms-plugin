<?php

namespace Sixgweb\Forms\Classes;

use Sixgweb\Conditions\Classes\AbstractConditionableEventHandler;

class ConditionableEventHandler extends AbstractConditionableEventHandler
{
    protected function getModelClass(): string
    {
        return \Sixgweb\Forms\Models\Form::class;
    }

    protected function getTab(): ?string
    {
        return 'Conditions';
    }

    protected function getLabel(): ?string
    {
        return 'Form Conditions';
    }

    protected function getComment(): ?string
    {
        return 'Conditions required for this form to be visible';
    }
}
