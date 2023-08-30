<?php

namespace Sixgweb\Forms\Models;

use Model;

/**
 * Form Model
 */
class Form extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Purgeable;
    use \October\Rain\Database\Traits\Nullable;

    /**
     * @var string table associated with the model
     */
    public $table = 'sixgweb_forms_forms';

    /**
     * @var array guarded attributes aren't mass assignable
     */
    protected $guarded = ['*'];

    /**
     * @var array fillable attributes are mass assignable
     */
    protected $fillable = [];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $purgeable = [];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $nullable = [];

    /**
     * @var array rules for validation
     */
    public $rules = [
        'name' => 'required',
        'throttle_timeout' => 'required_if:throttle_entries,1',
        'throttle_threshold' => 'required_if:throttle_entries,1',
        'purge_days' => 'required_if:purge_entries,1',
    ];

    public $slugs = [
        'slug' => 'name',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = [
        'settings',
    ];

    /**
     * @var array appends attributes to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array hidden attributes removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array hasOne and other relations
     */
    public $hasOne = [];
    public $hasMany = [
        'entries' => [
            Entry::class,
        ],
        'entries_count' => [
            Entry::class,
            'count' => true,
        ]
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function scopeEnabled($query)
    {
        $query->where('is_enabled', 1);
    }

    public function filterFields($fields, $context = null)
    {
        if (isset($fields->save_entries)) {
            $saveEntries = post($fields->save_entries->getName(), $fields->save_entries->value);
            if ($saveEntries == 0) {
                $fields->purge_entries->value = 0;
                $fields->purge_entries->disabled = 1;
                $fields->purge_days->disabled = 1;
            } else {
                $fields->purge_entries->disabled = 0;
            }
        }

        if (isset($fields->purge_entries)) {
            $purgeEntries = post($fields->purge_entries->getName(), $fields->purge_entries->value);
            $fields->purge_days->disabled = $purgeEntries == 0;
        }
    }
}
