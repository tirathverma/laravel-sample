<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = false;
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logo', 'header', 'footer', 'invoice_prefix', 'currency_rate', 'access_cost'
    ];
    public static $rules = array(
        'logo' => 'mimes:jpeg,jpg,png,|max:2048|',
        'header' => 'required',
        'footer' => 'required',
    );

}
