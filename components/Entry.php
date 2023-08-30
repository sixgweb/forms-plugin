<?php

namespace Sixgweb\Forms\Components;

use Auth;
use Event;
use Session;
use Request;
use Redirect;
use Cms\Classes\ComponentBase;
use Sixgweb\Forms\Models\Form;
use Illuminate\Support\Facades\RateLimiter;
use Sixgweb\Forms\Models\Entry as EntryModel;


/**
 * Form Component
 */
class Entry extends ComponentBase
{
    const LIMITER_KEY_PREFIX = 'sixgweb.forms.entry';

    protected $form;
    protected $entry;
    protected $rateLimiterKey;
    protected $rateLimiterSeconds;
    protected $entryFields;


    /**
     * Component Details
     *
     * @return array
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'Entry Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * Prepend properties to Attributize Fields component properties
     *
     * @return array
     */
    public function defineProperties(): array
    {
        $fields = new \Sixgweb\Attributize\Components\Fields;
        return [
            'form' => [
                'title' => 'Form',
                'type' => 'dropdown',
                'options' => Form::lists('name', 'slug'),
            ],
        ] + $fields->defineProperties();
    }

    /**
     * Initialize component
     *
     * @return void
     */
    public function init(): void
    {
        $this->prepareVars();
        $this->entryFields = $this->addComponent(
            'Sixgweb\Attributize\Components\Fields',
            'entryFields',
            $this->getProperties(),
        );
        $this->entryFields->bindModel($this->getEntry());
        $this->entryFields->createFormWidget();
    }

    /**
     * Prepare partial variables
     *
     * @return void
     */
    public function prepareVars(): void
    {
        $this->page['user'] = Auth::getUser();
        $this->form = $this->page['form'] = $this->getForm();
        $this->rateLimiterKey = $this->getRateLimiterKey();
        $this->page['entry'] = $this->getEntry();
        $this->page['timeout'] = $this->getTimeout();
        $this->page->title = $this->page['form']['name'] ?? 'Form Not Found';
    }

    /**
     * Workaround since OCMS doesn't use methodExists in onInspectableGetOptions
     *
     * @return array
     */
    public function getCodesOptions(): array
    {
        $entry = new EntryModel;
        $fields = $entry->getFieldableFields();
        return $fields->pluck('name', 'code')->toArray();
    }

    /**
     * Workaround since OCMS doesn't use methodExists in onInspectableGetOptions
     *
     * @return array
     */
    public function getTabsOptions(): array
    {
        $entry = new EntryModel;
        $fields = $entry->getFieldableFields();
        return $fields->pluck('tab', 'tab')->toArray();
    }

    /**
     * Ajax handler for entry creation
     *
     * @return mixed
     */
    public function onEntry(): ?array
    {
        if ($this->getTimeout()) {
            return null;
        }

        $data = post();
        $data['form_id'] = $this->form->id;
        $data['ip_address'] = Request::ip();
        $entry = $this->getEntry();
        $entry->fill($data);

        if (!$entry->validate()) {
            return null;
        }

        Event::fire('sixgweb.forms.entry.beforeSave', [$entry]);

        $entry->save();

        RateLimiter::hit($this->rateLimiterKey, $this->getThrottleTimeoutSeconds());

        Event::fire('sixgweb.forms.entry.afterSave', [$entry]);

        /**
         * Notify requires an existing model to process.
         * Instead of conditionally saving, we'll conditionally delete and decrement the ID.
         */
        if (!$this->form->settings['save_entries']) {
            $entry->delete();
            $entry->decrement('id');
        }

        if ($this->form->settings['redirect'] ?? null) {
            return Redirect::to($this->form->settings['redirect']);
        }

        return ['#' . $this->getFormContainerId() => $this->form->confirmation];
    }

    /**
     * Get Form Model
     *
     * @return Form|null
     */
    public function getForm(): ?Form
    {
        $slug = $this->property('form');

        //If no Forms found, fall back to false.  Otherwise, memoization is pointless on null.
        return $this->form ?? $this->form = Form::where('slug', $slug)->enabled()->first() ?? false;
    }

    public function getRateLimiterKey()
    {
        $fallback = $this->form->settings['throttle_ip']
            ? Request::ip()
            : Session::getId();

        $limiterKey  = [
            self::LIMITER_KEY_PREFIX,
            $this->form->id ?? null,
            Auth::id() ?? $fallback
        ];

        return implode('.', $limiterKey);
    }

    /**
     * Get the Entry Model
     *
     * @return EntryModel|null
     */
    public function getEntry(): ?EntryModel
    {
        return $this->entry ?? $this->entry = new EntryModel([
            'form_id' => $this->form->id ?? null,
        ]);
    }

    /**
     * Get form throttle timeout in seconds
     *
     * @return integer|null
     */
    public function getTimeout(): ?int
    {
        if (!$this->form) {
            return null;
        }

        //Throttling disabled.  Clear RateLimiter, if previous value.
        if (!$this->form->settings['throttle_entries']) {
            RateLimiter::clear($this->rateLimiterKey);
            return null;
        }

        $allowed = $this->form->settings['throttle_count'] ?? null;
        $seconds = $this->getThrottleTimeoutSeconds();

        if (RateLimiter::remaining($this->rateLimiterKey, $allowed, $seconds)) {
            return null;
        }

        return RateLimiter::availableIn($this->rateLimiterKey);
    }

    public function getEntryContainerId(): string
    {
        return $this->alias . 'EntryContainer';
    }

    public function getFormContainerId(): string
    {
        return $this->alias . 'FormContainer';
    }

    private function getThrottleTimeoutSeconds(): int
    {
        if ($this->rateLimiterSeconds) {
            return $this->rateLimiterSeconds;
        }

        $number = $this->form->settings['throttle_time_period'];
        $type = $this->form->settings['throttle_time_period_unit'];
        $seconds = 0;

        switch ($type) {
            case 'seconds':
                $seconds = $number;
                break;
            case 'minutes':
                $seconds = $number * 60;
                break;
            case 'hours':
                $seconds = ($number * 60) * 60;
                break;
            case 'days':
                $seconds =  (($number * 60) * 60) * 24;
                break;
        }

        $this->rateLimiterSeconds = $seconds;

        return $seconds;
    }
}
