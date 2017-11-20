<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Stock;
use Validator;
use Session;
use Excel;
use Input;
use DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }
    

    public function index(Request $request) { 

        $stocks = Stock::orderBy('id','DECS');

        if($request->has('q')) {
        $q = trim($request->q);
        $stocks->orWhere('name','LIKE',"%$q%")
                 ->orWhere('vintage', 'like', "%$q%")       
                 ->orWhere('size', 'like', "%$q%")       
                 ->orWhere('qty', 'like', "%$q%")       
                 ->orWhere('owc', 'like', "%$q%")       
                 ->orWhere('pound_cost', 'like', "%$q%")       
                 ->orWhere('vip_hkd', 'like', "%$q%")       
                 ->orWhere('selling_hkd', 'like', "%$q%")       
                 ->orWhere('cost_hkd', 'like', "%$q%");       
        }
         $stocks = $stocks->paginate();

        return view('stocks.index')->with('stocks',$stocks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $input = $input = array_map('trim', $request->all());
        $rules = Stock::$rules;

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $stock = new Stock;
        $stock::create($input);

        Session::flash('flash_message', 'Stock created successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('stocks');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $stock = Stock::find($id) ;
        if(is_null($stock)) {

            return redirect()->back();
        }

         return view('stocks.edit')->with('stock',$stock);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input =  array_map('trim', $request->all());
        $rules = Stock::$rules;
        $rules['name'].= ",name,$id";

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $stock = Stock::find($id);
        $stock->update($input);

        Session::flash('flash_message', 'Stock updated successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('stocks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $stock = Stock::find($id) ;
        if(is_null($stock)) {

            return redirect()->back();
        }

        $stock->delete();

        Session::flash('flash_message', 'Stock deleted successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('stocks');
    }

    public function export() {
 
       $data = Stock::select('name', 'vintage', 'size','qty','owc','pound_cost','cost_hkd','selling_hkd','vip_hkd')->orderBy('id','DECS')->get();

        ///$data = Stock::get();


        return Excel::create('Stocks', function($excel) use ($data) {

            $excel->sheet('mySheet', function($sheet) use ($data) {

            $sheet->fromArray($data);
            
            $sheet->row(1, array(

               'Name','Vintage','Size','Qty','OWC','Pound Cost','Cost (HKD)','Selling (HKD)','VIP (HKD)'
            ));

            });

        })->download('xls');

    }

    public function importForm() {
         return view('stocks.import');
    }

    public function saveImport(Request $request) {
            
        
        if (Input::hasFile('import')) {
            
            $input = $request->all(); 
            $path = Input::file('import')->getRealPath();  
            $data = Excel::load($path, function($reader) {
            })->get();

            $allowed =  array('xls','xlsx','xlt','xlsm');
            $filename =$request->file('import')->getClientOriginalName();
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!in_array($ext,$allowed) ) {
            
             return redirect()->back()->withErrors(['import' => 'Please upload files having extensions: .xls, .xlsx, .xlt, .xlsm only']);
            }

        
            if(!empty($data) && $data->count()){
                
                foreach ($data as $key => $value) {
                   
                    if(!empty($value->name) && $value->vintage && $value->size ) {
                        $insert[] = ['name' => $value->name, 'vintage' => $value->vintage,'size'=>$value->size,'qty'=>$value->qty, 'owc'=>$value->owc,'pound_cost'=>$value->pound_cost,'cost_hkd'=>$value->cost_hkd,'selling_hkd'=>$value->selling_hkd,'vip_hkd'=>$value->vip_hkd];
                        
                           
                        } else {

                        Session::flash('flash_message', 'Required filed are empty in import file');
                        Session::flash('flash_type', 'alert-danger');
                        return redirect()->back();
                        }
          
                 }

                if(!empty($insert)){
                  
                    Stock::insert($insert); 
                    Session::flash('flash_message', 'Stock added successfully.');
                    Session::flash('flash_type', 'alert-success');
                    
                } else {

                    Session::flash('flash_message', 'Stock not added.');
                    Session::flash('flash_type', 'alert-success');
                }
            } else {
                return redirect()->back()->withErrors(['import' => 'import field is required']);
            }

        }   else {
            return redirect()->back()->withErrors(['import' => 'import field is required']);
        }
        
        return redirect('stocks');
    }


     
}

 
