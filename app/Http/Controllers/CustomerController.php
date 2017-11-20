<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use App\Customer;
use App\Invoice;
use App\InvoiceItem;
use Validator;
use Session;

class CustomerController extends Controller {

    
    public function __construct() {
        $this->middleware('auth');
    }
    
    /*
     *  Index page of Customer and searching
     */
    public function index(Request $request) {
        $customers = Customer::where('is_deleted', 0)->orderBy('id', 'DESC');

        if ($request->has('q')) {
            $q = trim($request->q);
            $customers->where(function($customers) use($q) {
                $customers->orWhere('name', 'like', "%$q%")
                        ->orWhere('company', 'like', "%$q%")
                        ->orWhere('remark', 'like', "%$q%")
                        ->orWhere('address', 'like', "%$q%")
                        ->Where('is_deleted', '=', "0");
            });
        }

        $customers = $customers->paginate();

        return view('customers.index')->with('customers', $customers);
    }
    
    /*
     * create Customer 
     */
    public function create(Request $request) {

        return view('customers.create');
    }
 
    
    /*
     * Store Customer
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules = Customer::$rules;

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $customer = new Customer;
        $customer::create($input);

        Session::flash('flash_message', 'Customer created successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('customers');
    }
    
    /*
     * Edit Customer
     */
    public function edit($id) {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return redirect()->back();
        }

        return view('customers.edit')->with('customer', $customer);
    }
    
   
    /*
     * Update Customer
     */
    public function update(Request $request, $id) {
        $input = $request->all();
        $rules = Customer::$rules;

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $customer = Customer::find($id);
        $customer->update($input);

        Session::flash('flash_message', 'Customer updated successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('customers');
    }

    
    
    /*
     *  soft delete Customer
     */
    public function destroy($id) {
        $customer = Customer::find($id);

        if (is_null($customer)) {

            return redirect()->back();
        }

        //$invoice = Invoice::where('customer_id',$id)->get(); 
        // foreach($invoice as $ids) {
        //     InvoiceItem::where('invoice_id',$ids->id)->delete();
        //     Invoice::where('id',$ids->id)->delete();
        // }

        $customer->update(array('is_deleted' => 1));


        Session::flash('flash_message', 'Customer deleted successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('customers');
    }

}
