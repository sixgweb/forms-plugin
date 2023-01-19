<?php

namespace Sixgweb\Forms\Components;

use Auth;
use Queue;
use Event;
use Session;
use Redirect;
use Cms\Classes\ComponentBase;
use Sixgweb\Forms\Models\Form;
use Sixgweb\Forms\Models\Entry as EntryModel;

/**
 * Form Component
 */
class Entry extends ComponentBase
{
    protected $form;
    protected $entry;

    public function componentDetails()
    {
        return [
            'name' => 'Entry Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'form' => [
                'title' => 'Form',
                'type' => 'dropdown',
                'options' => Form::lists('name', 'slug'),
            ],
        ];
    }

    public function init()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->page['user'] = Auth::getUser();
        $this->page['form'] = $this->getForm();
        $this->page['entry'] = $this->getEntry();
        $this->page['timeout'] = $this->getTimeout();
        $this->page->title = $this->page['form']['name'] ?? 'Form Not Found';
    }

    public function onRefreshForm()
    {
        return $this->renderPartial('::form');
    }

    public function onEntry()
    {
        if ($this->getTimeout()) {
            return;
        }

        $data = post();
        $data['form_id'] = $this->form->id;
        $entry = $this->getEntry();
        $entry->fill($data);

        if (!$entry->validate()) {
            return false;
        }

        Event::fire('sixgweb.forms.beforeEntry', [$entry]);

        $entry->save();

        $key = 'sixgweb.forms.' . $this->form->id;
        $count = Session::get($key . '.entries', 0);
        Session::put($key . '.entries', $count + 1);
        Session::put($key . '.time', time());

        Event::fire('sixgweb.forms.afterEntry', [$entry]);

        /**
         * Notify requires an existing model to process.
         * Instead of conditionally saving, we'll conditionally delete.
         */
        if (!$this->form->settings['save_entries']) {
            $entry->delete();
        }

        if ($this->form->settings['redirect'] ?? null) {
            return Redirect::to($this->form->settings['redirect']);
        }

        return ['#' . $this->getFormContainerId() => $this->form->confirmation];
    }

    public function forms()
    {
        return Form::enabled()->orderBy('name', 'asc')->get();
    }

    public function getForm()
    {
        $slug = $this->property('form');

        //If no Forms found, fall back to false.  Otherwise, memoization if pointless on null.
        return $this->form ?? $this->form = Form::where('slug', $slug)->enabled()->first() ?? false;
    }

    public function getEntry()
    {
        $form = $this->getForm();
        return $this->entry ?? $this->entry = new EntryModel([
            'form_id' => $form->id ?? null,
        ]);
    }

    public function getTimeout()
    {
        if (!$form = $this->getForm()) {
            return;
        }

        $seconds = $form->settings['throttle_timeout'];
        $threshold = $form->settings['throttle_threshold'];

        if (!$form->settings['throttle_entries'] || !$seconds || !$threshold) {
            Session::forget('sixgweb.forms.' . $form->id);
            return false;
        }

        if (!$session = Session::get('sixgweb.forms.' . $form->id)) {
            return false;
        }

        if ($session['entries'] >= $threshold) {
            if ($seconds <= (time() - $session['time'])) {
                Session::forget('sixgweb.forms.' . $form->id);
            } else {
                return $seconds - (time() - $session['time']);
            }
        }

        return false;
    }

    public function getEntryContainerId()
    {
        return $this->alias . 'EntryContainer';
    }

    public function getFormContainerId()
    {
        return $this->alias . 'FormContainer';
    }
}
