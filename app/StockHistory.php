<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model {

    protected $table = 'stock_histories';
    protected $fillable = [
        'stock_id', 'user_id', 'action', 'old_qty', 'qty', 'new_qty'
    ];
    public static $rules = array(
    );

    public function stock() {
        return $this->belongsTo('App\Stock');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
