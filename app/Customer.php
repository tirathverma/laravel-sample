<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = true;
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'customer_id', 'company', 'address', 'remark', 'is_deleted'
    ];
    public static $rules = array(
        'name' => 'required',
        'company' => 'required',
        'address' => 'required',
    );

    public function customers() {
        return $this->hasMany('App\Invoice');
    }

}
