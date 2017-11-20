<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Customer;
use App\Stock;
use App\Setting;
use App\Invoice;
use App\InvoiceItem;
use DB;
use Validator;
use Session;

class InvoiceController extends Controller {

     
    public function __construct() {
        $this->middleware('auth');
    }

    /*
     *  invoice lising and search
     */

    public function index(Request $request) {

        $invoices = Invoice::with('customer')->orderBy('id', 'DESC');
        $customers = Customer::orderBy('id', 'DESC')->get();


        if ($request->has('q')) {
            $q = trim($request->q);
            $invoices->whereHas('customer', function($invoices) use($q) {
                $invoices->orWhere('name', 'like', "%$q%")
                        ->orWhere('invoice_number', 'like', "%$q%")
                        ->orWhere('comment', 'like', "%$q%")
                        ->orWhere('due_date', 'like', "%$q%");
            });
        }
        $invoices = $invoices->paginate();

        return view('invoice.index')->with(compact('invoices', 'customers'));
    }

    /*
     *  create invoice
     */

    public function create() {

        $customers = Customer::orderBy('id', 'DESC')->get();
        $stocks = Stock::orderBy('id', 'DESC')->get();
        $invoices = DB::select("SHOW TABLE STATUS LIKE 'invoices'");
        $settings = Setting::orderBy('id', 'DESC')->first();

        return view('invoice.create')->with(compact('customers', 'stocks', 'invoices', 'settings'));
    }

    
    /*
     *  Store invoice
     */

    public function store(Request $request) {

        $input = $request->all();
        //echo "<pre>"; print_r($input); die;
        $rules = Invoice::$rules;
        $validator = Validator::make($input, $rules);

        $validator->after(function ($validator) use ($request) {

            if (empty($request->product_id)) {

                $validator->errors()->add('field', "The Stock field is required");
            }

            if (empty($request->qty)) {

                $validator->errors()->add('field', "The quantity field is required");
            }
 

            // if (empty($request->customer_name) && empty($request->customer_id)) {

            //     $validator->errors()->add('field', "The bill to field is required");
            // }


            if (strtotime($request->due_date) < strtotime($request->date)) {

                $validator->errors()->add('field', "The due date cannot be less than invoice date.");
            }
        });


        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }


        $cusQuery = DB::table('customers');

        $name =  !empty($request->customer_name) ? trim($request->customer_name) : '' ;
        $com =   !empty($request->bill_to) ? trim($request->bill_to) : '' ;

        if ($name && $com) {
            $cusQuery->where('name', $name)->where('company', $com);
        } elseif ($name) {
            $cusQuery->where('name', $name);
        } elseif ($com) {
            $cusQuery->where('company', $com);
        }  

        $customers = $cusQuery->first();

        if ($customers) {
            $get_customer_id = $customers->id;
        } else {
            $customers = DB::table('customers')->insert(['name' => $name, 'company' => $com]);
            $get_customer_id = DB::getPdo()->lastInsertId();
        }

        $input['customer_id'] = $get_customer_id;

        $input['currency_rate'] = $request->currencyrate;

        $input['due_date'] = date("Y-m-d", strtotime($request->due_date));
        $input['date'] = date("Y-m-d", strtotime($request->date));
        $invoice = new Invoice;
        $invoice::create($input);
        $getid = DB::getPdo()->lastInsertId();

        /*
         * invoice item start from here also history is updated here 
         */

        $input_data = array();
        foreach ($request->Unit_price as $key => $value) {

            $data = array(
                'invoice_id' => $getid,
                'quantity' => $request->qty[$key],
                'cost_hkd' => $request->cost_hkd[$key],
                'unit_price' => $request->total_price[$key],
                'HKD_price' => $request->Unit_price[$key],
                'product_id' => $request->product_id[$key],
                'total' => $request->qty[$key] * $request->total_price[$key],
                'price_type' => $request->price_type[$key],
            );

            $input_data[] = $data;

            $stock = Stock::find($request->product_id[$key]);

            $new_qty = $stock->qty - $request->qty[$key];
            $change = '-' . $request->qty[$key];

            $action = 'Sale';
            app('App\Http\Controllers\StockController')->createLog($action, $request->product_id[$key], $stock->qty, $change, $new_qty);

            $stock->update(array('qty' => $new_qty));
        }


        DB::table('invoice_item')->insert($input_data);

        Session::flash('flash_message', 'invoice created successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('invoices');
    }
    
    /*
     *  Edit Invoice
     */
    public function edit($id) {

        $invoice = Invoice::with('invoiceItem.stock', 'customer')->find($id);

        if (is_null($invoice)) {

            return redirect()->back();
        }

        $settings = Setting::orderBy('id', 'DESC')->first();

        return view('invoice.edit')->with(compact('invoice', 'settings'));
    }

    
    /*
     *  update invoice
     */
    public function update(Request $request, $id) {

        $input = $request->all();
        $rules = Invoice::$rules;
        $rules['invoice_number'].= ",invoice_number,$id";

        $validator = Validator::make($input, $rules);

        $validator->after(function ($validator) use ($request) {

            if (empty($request->product_id)) {

                $validator->errors()->add('field', "The Stock field is required");
            }

            if (empty($request->qty)) {

                $validator->errors()->add('field', "The qty field is required");
            }

            if (empty($request->Unit_price)) {

                $validator->errors()->add('field', "The price field is required");
            }

            // if (empty($request->customer_name) && empty($request->customer_id)) {

            //     $validator->errors()->add('field', "The bill to field is required");
            // }

            if (strtotime($request->due_date) < strtotime($request->date)) {

                $validator->errors()->add('field', "The due date cannot be less than invoice date.");
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }


        $cusQuery = DB::table('customers');

        $name = trim($request->customer_name);
        $com = trim($request->bill_to);

        if ($name && $com) {
            $cusQuery->where('name', $name)->where('company', $com);
        } elseif ($name) {
            $cusQuery->where('name', $name);
        } elseif ($com) {
            $cusQuery->where('company', $com);
        }  

        $customers = $cusQuery->first();

        if ($customers) {
            $get_customer_id = $customers->id;
        } else {
            $customers = DB::table('customers')->insert(['name' => $name, 'company' => $com]);
            $get_customer_id = DB::getPdo()->lastInsertId();
        }

        $input['customer_id'] = $get_customer_id;

        $input['due_date'] = date("Y-m-d", strtotime($request->due_date));
        $input['date'] = date("Y-m-d", strtotime($request->date));

        $invoice = Invoice::find($id);
        $invoice->update($input);

        /*
         * invoice item start from here also history is updated here 
         */

        $input_data = array();

        $items = DB::table('invoice_item')->select('product_id', 'quantity')->where('invoice_id', $id)->get();

        $newStocks = array_keys($request->qty);

        $oldStocks = [];

        foreach ($items as $it) {

            if (!in_array($it->product_id, $newStocks)) {

                DB::table('invoice_item')->where('product_id', $it->product_id)->where('invoice_id', $id)->delete();

                $stock = Stock::find($it->product_id);

                $new_qty = $stock->qty + $it->quantity;
                $action = 'Invoice - Removed';

                app('App\Http\Controllers\StockController')->createLog($action, $it->product_id, $stock->qty, $it->quantity, $new_qty);
                $stock->update(array('qty' => $new_qty));
            } else {
                $oldStocks[$it->product_id] = $it->quantity;
            }
        }



        foreach ($request->Unit_price as $key => $value) {

            $pid = $request->product_id[$key];

            $data = array(
                'invoice_id' => $id,
                'quantity' => $request->qty[$key],
                'cost_hkd' => $request->cost_hkd[$key],
                'unit_price' => $request->total_price[$key],
                'HKD_price' => $request->Unit_price[$key],
                'product_id' => $pid,
                'total' => $request->qty[$key] * $request->total_price[$key],
                'price_type' => $request->price_type[$key],
            );

            $stock = Stock::find($pid);

            if (array_key_exists($pid, $oldStocks)) {

                DB::table('invoice_item')->where('product_id', $pid)
                        ->where('invoice_id', $id)->update($data);

                $action = '';
                if ($oldStocks[$pid] < $request->qty[$key]) {
                    $action = 'Invoice - Edited';
                    $qty = $request->qty[$key] - $oldStocks[$pid];
                    $new_qty = $stock->qty - $qty;
                    $qty = '-' . $qty;
                } elseif ($oldStocks[$pid] > $request->qty[$key]) {
                    $action = 'Invoice - Edited';
                    $qty = $oldStocks[$pid] - $request->qty[$key];
                    $new_qty = $stock->qty + $qty;
                }
            } else {
                $qty = '-';
                $qty .= $request->qty[$key];
                $input_data[] = $data;
                $action = 'Invoice - Edited';
                $new_qty = $stock->qty - $request->qty[$key];
            }

            if ($action) {

                app('App\Http\Controllers\StockController')->createLog($action, $pid, $stock->qty, $qty, $new_qty);
                $stock->update(array('qty' => $new_qty));
            }
        }


        DB::table('invoice_item')->insert($input_data);

        Session::flash('flash_message', 'Invoice updated successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('invoices');
    }

    /*
     *  delete invoice
     */

    public function destroy(Request $request, $id) {

        $invoice = Invoice::with('invoiceItem')->find($id);
        if (is_null($invoice)) {

            return redirect()->back();
        }

        foreach ($invoice->invoiceItem as $item) {

            $stock = Stock::find($item->product_id);

            $new_qty = $stock->qty + $item->quantity;

            $action = 'Invoice Deleted';

            app('App\Http\Controllers\StockController')->createLog($action, $item->product_id, $stock->qty, $item->quantity, $new_qty);

            $stock->update(array('qty' => $new_qty));
        }

        $invoice->invoiceItem()->delete();
        $invoice->delete();


        Session::flash('flash_message', 'Invoice deleted successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect()->back();
    }

    /*
     *  Stock search
     */

    public function search(Request $request) {


        if ($request->has('term')) {

            $stocks = Stock::Where('name', 'like', "%$request->term%")->get();
        } else {

            $stocks = Stock::Where('name', 'like', "%$request->term%")->orderBy('id','DESC')->get();
        }


        return json_encode($stocks);
    }

    /*
     *  customerSearch search
     */

    public function customerSearch(Request $request) {

        if ($request->has('term')) {

            $customer = Customer::Where('name', 'like', "%$request->term%")->Where('is_deleted', '=', 0)->get();
        }

        return (json_encode($customer));
    }

    /*
     *  Create Invoice
     */

    public function invoicePrint($id) {
        $invoice = Invoice::with('invoiceItem.stock', 'customer')->find($id);

        if (is_null($invoice)) {

            return redirect()->back();
        }

        $settings = Setting::orderBy('id', 'DESC')->first();


        return view('invoice.printinvoice')->with(compact('settings', 'invoice'));
    }

    /*
     *  Change status, ajax request
     */

    public function paidStatus(Request $request) {

        $invoice = Invoice::with('invoiceItem.stock')->find($request->id);

        if (is_null($invoice)) {

            return redirect()->back();
        }

        if ($invoice->is_paid == 2) {

            foreach ($invoice->invoiceItem as $value) {
                $stocks = Stock::find($value->product_id);
                $newQty = $stocks->qty - $value->quantity;
                $change_qty = '-';
                $change_qty .= $value->quantity;

                app('App\Http\Controllers\StockController')->createLog('Invoice - Edited', $stocks->id, $stocks->qty, $change_qty, $newQty);
                $stocks->update(['qty' => $newQty]);
            }
        }


        $update = Invoice::where('id', $request->id)->update(array('is_paid' => $request->value));


        if ($request->value == 2) {

            foreach ($invoice->invoiceItem as $value) {
                $stocks = Stock::find($value->product_id);
                $newQty = $stocks->qty + $value->quantity;
                $change_qty = $value->quantity;

                app('App\Http\Controllers\StockController')->createLog('Invoice - Edited', $stocks->id, $stocks->qty, $change_qty, $newQty);
                $stocks->update(array('qty' => $newQty));
            }
        }

        if (!empty($update)) {

            echo json_encode(
                    array(
                        'type' => 'success',
                        'msg' => 'Invoice status change successfully.',
                        'data' => $request->id
                    )
            );
        }

        exit;
    }

    /*
     *  customerSearch search
     */

    public function companySearch(Request $request) {


        if ($request->has('term')) {
            $company = Customer::Where('company', 'like', "%$request->term%")
                            ->Where('is_deleted', '=', 0)->get();
        }

        return (json_encode($company));
    }

    /*
     *  invoice view
     */

    public function show($id) {

        $invoice = Invoice::with('invoiceItem.stock', 'customer')->find($id);

        if (is_null($invoice)) {

            return redirect()->back();
        }

        $settings = Setting::orderBy('id', 'DESC')->first();

        return view('invoice.show')->with(compact('invoice', 'settings'));
    }

}
