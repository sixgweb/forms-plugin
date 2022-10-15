<?php

namespace Sixgweb\Forms\Components;

use Auth;
use Event;
use Session;
use Validator;
use ValidationException;
use Cms\Classes\ComponentBase;
use Sixgweb\Forms\Models\Form;
use Sixgweb\Forms\Models\Settings;
use October\Rain\Database\ModelException;
use Sixgweb\Forms\Models\Entry as EntryModel;

/**
 * Form Component
 */
class Entry extends ComponentBase
{
    const CONTAINERID = 'entryContainer';

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
        return [];
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
        $this->page['containerID'] = self::CONTAINERID;
        $this->page->title = $this->page['form']['name'] ?? 'Form Not Found';
    }

    public function onEntry()
    {
        if ($this->getTimeout()) {
            return;
        }

        $data = post();
        $data['form_id'] = $this->form->id;
        $data['user_id'] = Auth::id();
        $entry = $this->getEntry();
        $entry->fill($data);

        if (!$entry->validate()) {
            return false;
        }

        Event::fire('sixgweb.forms.beforeSave', [$entry]);
        if ($this->form->save_entries) {
            $entry->save();
        }

        $key = 'sixgweb.forms.' . $this->form->id;
        $count = Session::get($key . '.entries', 0);
        Session::put($key . '.entries', $count + 1);
        Session::put($key . '.time', time());

        Event::fire('sixgweb.forms.afterSave', [$entry]);

        return ['#' . self::CONTAINERID => $this->form->confirmation];
    }

    public function forms()
    {
        return Form::enabled()->orderBy('name', 'asc')->get();
    }

    public function getForm()
    {
        $slug = $this->param('slug');

        //If no Forms found, fall back to false.  Otherwise, memoization if pointless on null.
        return $this->form ?? $this->form = Form::where('slug', $slug)->first() ?? false;
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
        $seconds = Settings::get('timeout');
        $threshold = Settings::get('timeout_threshold');
        $form = $this->getForm();

        if (!$seconds || !$threshold || !$form) {
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
}
