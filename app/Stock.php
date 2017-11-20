<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

    public $timestamps = false;
    protected $table = 'stocks';
    protected $fillable = [
        'name', 'image', 'vintage', 'size', 'qty', 'owc', 'pound_cost', 'cost_hkd', 'selling_hkd', 'vip_hkd'
    ];
    public static $rules = array(
        'name' => 'required|unique:stocks',
        'qty' => 'required|numeric|min:0',
        'pound_cost' => 'numeric|min:0',
        'cost_hkd' => 'required|numeric|min:0',
        'selling_hkd' => 'required|numeric|min:0',
        'vip_hkd' => 'required|numeric|min:0',
    );

    public function invoiceStocks() {
        return $this->hasMany('App\InvoiceItem', 'product_id');
    }

}
