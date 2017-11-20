<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use App\Stock;
use App\Setting;
use App\Invoice;
use App\InvoiceItem;
use DB;
use Validator;
use App\StockHistory;
use App\User;

class InvoiceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $invoices = Invoice::with('customer')->orderBy('id', 'DESC');

        if ($request->has('q')) {
            $q = trim($request->q);
            $invoices->whereHas('customer', function($invoices) use($q) {
                $invoices->orWhere('name', 'like', "%$q%")
                        ->orWhere('invoice_number', 'like', "%$q%");
            });
        }

        $invoices = $invoices->paginate(100);

        return response([
            'status' => 'success',
            'invoices' => $invoices
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
         
        $input = $request->json()->all();

        $invoices = DB::select("SHOW TABLE STATUS LIKE 'invoices'");
        $settings = Setting::select('invoice_prefix')->first();

        $invoice_name = '';
        if ($settings->invoice_prefix) {

            $invoice_name .= $settings->invoice_prefix;
        }

        $invoice_name .= $invoices[0]->Auto_increment;

        $input['invoice_number'] = $invoice_name;

        $rules = Invoice::$rules;


        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response(
                    $validator->messages()
                    , 422);
        }


        $cusQuery = DB::table('customers');

        $name =  '';
        $com = '';

        if (isset($input['customer']) && !empty($input['customer'])) {
            $name = trim($input['customer']);
        }

        if (isset($input['company']) && !empty($input['company'])) {
            $com = trim($input['company']);
        }

        

        if (!$name && !$com) {
            return response(
            [
                'error' => ['Please select customer']
            ], 400);
        }

        if ($name && $com) {
            $cusQuery->where('name', $name)->where('company', $com);
        } elseif ($name) {
            $cusQuery->where('name', $name);
        } elseif ($com) {
            $cusQuery->where('company', $com);
        } else {

            return response(
                    [
                'error' => ['Please select customer']
                    ], 400);
        }


        $customers = $cusQuery->first();


        if ($customers) {
            $get_customer_id = $customers->id;
        } else {
            $customers = DB::table('customers')->insert(['name' => $name, 'company' => $com]);
            $get_customer_id = DB::getPdo()->lastInsertId();
        }

        $input['customer_id'] = $get_customer_id;


        $invoice = new Invoice;
        $invoice::create($input);
        $getid = DB::getPdo()->lastInsertId();

        $items = $input['items'];

        $user = User::where('api_token', $input['api_token'])->first();

        if (empty($user)) {
            return response([
                'error' => ['Not valid request']
                    ], 400);
        }

        $user_id = $user->id;

        foreach ($items as $key => $value) {

            $data[] = array(
                'invoice_id' => $getid,
                'quantity' => $value['itemQty'],
                'cost_hkd' => $value['cost_hkd'],
                'unit_price' => $value['price'],
                'product_id' => $value['id'],
                'total' => $value['itemTotal'],
                'HKD_price' => $value['HKD_price'],
                'price_type' => $value['price_type'],
            );

            $stock = Stock::find($value['id']);

            $new_qty = $stock->qty - $value['itemQty'];
            $change = '-' . $value['itemQty'];

            $action = 'Sale';

            $this->createLog($action, $value['id'], $stock->qty, $change, $new_qty, $user_id);

            $stock->update(array('qty' => $new_qty));
        }

        $invoiceItem = DB::table('invoice_item')->insert($data);

        if (!empty($invoiceItem)) {

            return response([
                'status' => 'success',
                'msg' => 'Invoice created successfully'
                    ], 200);
        } else {

            return response([
                'error' => 'Failed while creating record'
                    ], 400);
        }
    }

    public function edit($id) {

        $invoice = Invoice::with('customer', 'invoiceItem.stock')->find($id);

        if (is_null($invoice)) {

            return redirect()->back();
        }

        if (!empty($invoice)) {

            return response([
                'status' => 'success',
                'invoice' => $invoice,
                    ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {

        $input = $request->json()->all();

        $invoice = Invoice::find($id);

        $input['invoice_number'] = $invoice->invoice_number;
        $rules = Invoice::$rules;
        $rules['invoice_number'].= ",invoice_number,$id";

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response(
                    $validator->messages()
                    , 422);
        }


        $cusQuery = DB::table('customers');

        $name =  '';
        $com = '';

        if (isset($input['customer']) && !empty($input['customer'])) {
            $name = trim($input['customer']);
        }

        if (isset($input['company']) && !empty($input['company'])) {
            $com = trim($input['company']);
        }

         

        if (!$name && !$com) {
            return response(
            [
                'error' => ['Please select customer']
            ], 400);
        }

        if ($name && $com) {
            $cusQuery->where('name', $name)->where('company', $com);
        } elseif ($name) {
            $cusQuery->where('name', $name);
        } elseif ($com) {
            $cusQuery->where('company', $com);
        } else {

            return response(
                [
                    'error' => ['Please select customer']
                ], 400);
        }



        $customers = $cusQuery->first();

        if ($customers) {
            $get_customer_id = $customers->id;
        } else {
            $customers = DB::table('customers')->insert(['name' => $name, 'company' => $com]);
            $get_customer_id = DB::getPdo()->lastInsertId();
        }

        $input['customer_id'] = $get_customer_id;



        $invoice = Invoice::find($id);
        $invoice->update($input);


        $user = User::where('api_token', $input['api_token'])->first();

        if (empty($user)) {
            return response([
                'error' => ['Not valid request']
                    ], 400);
        }

        $user_id = $user->id;

        $old_items = DB::table('invoice_item')
                        ->select('product_id', 'quantity')->where('invoice_id', $id)->get();

        $oldStocks = [];
        $input_data = [];
        $newStocks = [];

        $items = $input['items'];

        foreach ($items as $key => $value) {
            $newStocks[$value['id']] = $value['itemQty'];
        }


        foreach ($old_items as $it) {

            if (!in_array($it->product_id, array_keys($newStocks))) {

                DB::table('invoice_item')->where('product_id', $it->product_id)
                        ->where('invoice_id', $id)->delete();

                $stock = Stock::find($it->product_id);

                $new_qty = $stock->qty + $it->quantity;
                $action = 'Invoice - Removed';

                $this->createLog($action, $it->product_id, $stock->qty, $it->quantity, $new_qty, $user_id);

                $stock->update(array('qty' => $new_qty));
            } else {
                $oldStocks[$it->product_id] = $it->quantity;
            }
        }


        foreach ($items as $key => $value) {
            $pid = $value['id'];
            $data = array(
                'invoice_id' => $id,
                'quantity' => $value['itemQty'],
                'cost_hkd' => $value['cost_hkd'],
                'unit_price' => $value['price'],
                'product_id' => $pid,
                'total' => $value['itemTotal'],
                'HKD_price' => $value['HKD_price'],
                'price_type' => $value['price_type'],
            );

            $stock = Stock::find($pid);
            if (array_key_exists($pid, $oldStocks)) {

                $data1 = DB::table('invoice_item')->where('product_id', $pid)
                                ->where('invoice_id', $id)->update($data);


                $action = '';
                if ($oldStocks[$pid] < $value['itemQty']) {
                    $action = 'Invoice - Edited';
                    $qty = $value['itemQty'] - $oldStocks[$pid];
                    $new_qty = $stock->qty - $qty;
                    $qty = '-' . $qty;
                } elseif ($oldStocks[$pid] > $value['itemQty']) {
                    $action = 'Invoice - Edited';
                    $qty = $oldStocks[$pid] - $value['itemQty'];
                    $new_qty = $stock->qty + $qty;
                }
            } else {
                $qty = '-';
                $qty .= $value['itemQty'];
                $input_data[] = $data;
                $action = 'Invoice - Edited';
                $new_qty = $stock->qty - $value['itemQty'];
            }

            if ($action) {

                $this->createLog($action, $pid, $stock->qty, $qty, $new_qty, $user_id);
                $stock->update(array('qty' => $new_qty));
            }
        }


        if ($input_data) {

            $invoiceItem = DB::table('invoice_item')->insert($input_data);

            if (!empty($invoiceItem)) {

                return response([
                    'status' => 'success',
                    'msg' => 'Invoice updated successfully'
                        ], 200);
            } else {

                return response([
                    'error' => 'Failed to update record'
                        ], 400);
            }
        }



        return response([
            'status' => 'success',
            'msg' => 'Invoice updated successfully'
                ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id) {

        $input = $request->json()->all();
        //echo "<pre>"; print_r($input); die;

        $invoice = Invoice::with('invoiceItem')->find($id);

        if (is_null($invoice)) {
            return response([
                'error' => 'Failed to delete record'
                    ], 400);
        }


        $user = User::where('api_token', $input['api_token'])->first();

        if (empty($user)) {
            return response([
                'error' => ['Failed to perform status']
                    ], 400);
        }
        $user_id = $user->id;


        foreach ($invoice->invoiceItem as $item) {

            $stock = Stock::find($item->product_id);

            $new_qty = $stock->qty + $item->quantity;

            $action = 'Invoice Deleted';

            $this->createLog($action, $item->product_id, $stock->qty, $item->quantity, $new_qty, $user_id);

            $stock->update(array('qty' => $new_qty));
        }


        $invoice->invoiceItem()->delete();
        $invoice->delete();

        if ($invoice) {
            return response([
                'status' => 'success',
                'msg' => "Invoice deleted successfully"
                    ], 200);
        } else {

            return response([
                'error' => ['Failed to delete record']
                    ], 400);
        }
    }

    public function search(Request $request) {

        if ($request->has('q')) {

            $stocks = Stock::Where('name', 'like', "%$request->q%")->get();
        }


        if (!empty($stocks)) {
            return response([
                'status' => 'success',
                'stocks' => ($stocks)
                    ], 200);
        } else {
            return response([
                'status' => 'No record found',
                'stocks' => [],
                    ], 400);
        }
    }

    public function settings() {

        $setting = Setting::orderBy('id', 'DESC')->first();

        if (!empty($setting)) {
            return response([
                'status' => 'success',
                'logo_url' => !empty($setting->logo) ? \URL::to('/') . '/images/logo/' . ($setting->logo) : '',
                'setting' => $setting,
                    ], 200);
        } else {
            return response([
                'status' => 'No settings found',
                'setting' => [],
                    ], 400);
        }
    }

    public function changeStatus(Request $request) {

        $input = $request->json()->all();


        $invoice = Invoice::with('invoiceItem.stock')->find($input['id']);

        if (is_null($invoice)) {

            return response([
                'error' => ['Failed to find record']
                    ], 400);
        }


        $user = User::where('api_token', $input['api_token'])->first();

        if (empty($user)) {
            return response([
                'error' => ['Failed to perform status']
                    ], 400);
        }
        $user_id = $user->id;

        if ($invoice->is_paid == 2) {

            foreach ($invoice->invoiceItem as $value) {

                $stocks = Stock::find($value->product_id);
                $newQty = $stocks->qty - $value->quantity;

                $change_qty = '-';
                $change_qty .= $value->quantity;

                $this->createLog('Invoice - Edited', $stocks->id, $stocks->qty, $change_qty, $newQty, $user_id);

                $stocks->update(['qty' => $newQty]);
            }
        }


        $invoices = Invoice::where('id', $input['id'])->update(array('is_paid' => $input['status']));


        if ($input['status'] == 2) {


            foreach ($invoice->invoiceItem as $value) {

                $stocks = Stock::find($value->product_id);
                $newQty = $stocks->qty + $value->quantity;

                $this->createLog('Invoice - Edited', $stocks->id, $stocks->qty, $value->quantity, $newQty, $user_id);
                $stocks->update(['qty' => $newQty]);
            }
        }


        if (!empty($invoices)) {
            return response([
                'status' => 'success',
                'msg' => 'Status changed successfully',
                    ], 200);
        } else {
            return response([
                'status' => 'No settings found',
                'msg' => 'Status not changed',
                    ], 400);
        }
    }

    function createLog($action, $stock_id, $old_qty, $change_qty, $new_qty, $user_id) {

        if ($action == 'sale') {
            $change_qty = '-' . $change_qty;
        }

        $stockHistory = new StockHistory;
        $stockHistory->stock_id = $stock_id;
        $stockHistory->user_id = $user_id;
        $stockHistory->action = $action;
        $stockHistory->old_qty = $old_qty;
        $stockHistory->qty = $change_qty;
        $stockHistory->new_qty = $new_qty;
        $stockHistory->save();
    }

}
