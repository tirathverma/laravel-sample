<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    public $timestamps = true;
    protected $table = 'invoices';
    protected $fillable = [
        'invoice_number', 'date', 'customer_id', 'due_date', 'prices', 'quantity', 'comment', 'total', 'is_paid', 'bill_to', 'currency_type', 'currency_rate'
    ];
    public static $rules = array(
        'invoice_number' => 'required|unique:invoices',
        'date' => 'required',
        'due_date' => 'required',
        'currency_type' => 'required',
        'prices' => 'numeric|min:0',
        'quantity' => 'numeric',
    );

    public function invoiceItem() {
        return $this->hasMany('App\InvoiceItem');
    }

    public function customer() {
        return $this->belongsTo('App\Customer');
    }

}
