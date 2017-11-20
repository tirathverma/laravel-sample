<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use App\InvoiceItem;
use App\Invoice;
use DB;
use Validator;
use Auth;

class CustomerController extends Controller {

     
    /*
     *  
     */
    public function index() {

        $customers = Customer::where('is_deleted', 0)->orderBy('id', 'DESC')->get();

        if ($customers) {
            return response([
                'status' => 'success',
                'total_record' => count($customers),
                'customer' => $customers
            ]);
        } else {
            return response([
                'status' => 'No Customer found',
            ]);
        }
    }
    
   
    /*
     * Store Customer
     */
    public function store(Request $request) {

        $input = $request->json()->all();

        $rules = Customer::$rules;

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response(
                    $validator->messages()
                    , 422);
        }

        $customer = new Customer;
        $customer::create($input);

        if ($customer) {
            return response([
                'status' => 'success',
                'customer_id' => DB::getPdo()->lastInsertId(),
                'msg' => "Customer created successfully"
                    ], 200);
        } else {

            return response([
                'error' => ['Error while creating customer']
                    ], 400);
        }
    }
    
    /*
     * Update Customer
     */
    public function update(Request $request, $id) {

        $input = $request->json()->all();
        $rules = Customer::$rules;

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response(
                    $validator->messages()
                    , 422);
        }

        $customer = Customer::find($id);
        $customer->update($input);

        if ($customer) {
            return response([
                'status' => 'success',
                'customer' => $customer,
                'msg' => "Customer updated successfully"
                    ], 200);
        } else {

            return response([
                'error' => ['Failed to update record']
                    ], 400);
        }
    }

     /*
      *  Del Customer 
      */
    public function destroy($id) {

        $customer = Customer::find($id);

        if (is_null($customer)) {

            return redirect()->back();
        }

        $invoice = Invoice::where('customer_id', $id)->get();

        // foreach($invoice as $ids) {
        //     InvoiceItem::where('invoice_id',$ids->id)->delete();
        //     Invoice::where('id',$ids->id)->delete();
        // }

        $customer->update(array('is_deleted' => 1));

        if ($customer) {
            return response([
                'status' => 'success',
                'msg' => "Customer deleted successfully"
                    ], 200);
        } else {

            return response([
                'error' => ['Failed to delete record']
                    ], 400);
        }
    }

}
