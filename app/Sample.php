<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model {

    public $timestamps = false;
    protected $table = 'sample';
   
    protected $fillable = [
        'name', 'vintage', 'size', 'qty', 'owc', 'pound_cost', 'cost_hkd', 'selling_hkd', 'vip_hkd'
    ];
    
    public static $rules = array(
    );

}
