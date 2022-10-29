<?php

namespace Sixgweb\Forms\Models;

use Model;
use Sixgweb\Forms\Models\Form as FormModel;

/**
 * Entry Model
 */
class Entry extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Purgeable;

    /**
     * @var string table associated with the model
     */
    public $table = 'sixgweb_forms_entries';

    /**
     * @var array guarded attributes aren't mass assignable
     */
    protected $guarded = ['*'];

    /**
     * @var array fillable attributes are mass assignable
     */
    protected $fillable = [
        'form_id',
    ];

    protected $purgeable = [];

    /**
     * @var array rules for validation
     */
    public $rules = [
        'form_id' => 'required',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = [];

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
    public $hasMany = [];
    public $belongsTo = [
        'form' => [
            FormModel::class,
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * This method is called in fieldable and allows me to get the fields based on the
     * fieldablerelationid
     *
     * @return void
     */
    public function fieldableGetRelationModel()
    {
        return Form::find($this->form_id);
    }
}
