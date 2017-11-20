<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model {

    public $timestamps = false;
    protected $table = 'invoice_item';
    protected $fillable = [
        'invoice_id', 'product_id', 'unit_price', 'quantity', 'total', 'price_type', 'HKD_price'
    ];
    public static $rules = array(
        'invoice_id' => 'required',
        'product_id' => 'required',
        'unit_price' => 'required',
        'quantity' => 'numeric|min:0',
        'total' => 'numeric',
    );

    public function stock() {
        return $this->belongsTo('App\Stock', 'product_id');
    }

}
