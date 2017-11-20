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


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    


    public function index(Request $request) {

        $invoices = Invoice::with('customer')->orderBy('id','DESC');

        if($request->has('q')) {
            $q = trim($request->q);
            $invoices->whereHas('customer',function($invoices) use($q){
            $invoices->orWhere('name','like',"%$q%")
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
       
       $input = $request->json()->all();

       $invoices = DB::select("SHOW TABLE STATUS LIKE 'invoices'");
       $input['invoice_number'] = $invoices[0]->Auto_increment;
       
       $rules = Invoice::$rules;
       
         
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
             return response(
                 $validator->messages()
            ,422);
        }
       
        
        $invoice = new Invoice;
        $invoice::create($input);
        $getid = DB::getPdo()->lastInsertId();
 
        $items = $input['items'];
       
         foreach ($items as $key => $value) {
             
            $data[] = array(
                'invoice_id' => $getid, 
                'quantity' => $value['itemQty'], 
                'unit_price' => $value['price'],
                'product_id' => $value['id'],
                'total' => $value['itemTotal'] 
            );

           
         }
          
       $invoiceItem = DB::table('invoice_item')->insert($data);
       
        if(!empty($invoiceItem)) {

            return response([
                'status' => 'success',
                 'msg'=>'Invoice created successfully'
            ],200);
      
        } else {

             return response([
            'error' => 'Failed while creating record'
            ],400);
        }
        

 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

       $invoice = Invoice::with('customer','invoiceItem.stock')->find($id);        

        if(is_null($invoice)) {

            return redirect()->back();
        }
        
        if(!empty($invoice)) {

            return response([
                'status' => 'success',
                 'invoice'=>$invoice,
            ],200);
      
        }  
 
       

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            ,422);
        }
 
        $invoice = Invoice::find($id);
        $invoice->update($input);
         
        $items = $input['items'];
       
         foreach ($items as $key => $value) {
             
            $data[] = array(
                'invoice_id' => $id, 
                'quantity' => $value['itemQty'], 
                'unit_price' => $value['price'],
                'product_id' => $value['id'],
                'total' => $value['itemTotal'] 
            );

           
         }
     
        
        DB::table('invoice_item')->where('invoice_id', $id)->delete();
        $invoiceItem = DB::table('invoice_item')->insert($data);

        if(!empty($invoiceItem)) {

            return response([
                'status' => 'success',
                 'msg'=>'Invoice updated successfully'
            ],200);
      
        } else {

             return response([
            'error' => 'Failed to delete record'
            ],400);
        }



        // Session::flash('flash_message', 'invoice updated successfully.');
        // Session::flash('flash_type', 'alert-success');
        // return redirect('invoices');
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        
        $invoice = invoice::find($id) ;
        if(is_null($invoice)) {

            return redirect()->back();
        }
        
        $invoice->invoiceItem()->delete();
        $invoice->delete();

        if($invoice) {
        return response([
                'status' => 'success',
                'msg' => "Invoice deleted successfully"
            ],200);
        
        } else {

            return response([
            'error' => ['Failed to delete record']
            ],400);
        }

    
      
    }


    public function search (Request $request) {
              
        if($request->has('q')) {
            
            $stocks = Stock::Where('name', 'like', "%$request->q%")->get();
             
        }
       
             
         if(!empty($stocks)){ 
                 return response([
                    'status' => 'success',
                    'stocks' => ($stocks)
               ],200);
       
         } else {
                  return response([
            'status' => 'No record found',
            'stocks'=> [],
            ],400);
         }
       

    }

    public function settings() {
      
        $setting = Setting::orderBy('id','DESC')->first();
         
        if(!empty($setting)) {
        return response([
                'status' => 'success',
                'logo_url' => !empty($setting->logo) ?   \URL::to('/').'/images/logo/'.($setting->logo) : '' ,
                'setting' => $setting,

           ],200);
        
        } else {
              return response([
            'status' => 'No settings found',
            'setting'=> [],
            ],400);
         }


    }


}
