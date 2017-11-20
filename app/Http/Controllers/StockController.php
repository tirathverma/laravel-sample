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
use App\Sample;
use App\InvoiceItem;
use App\Invoice;
use App\StockHistory;

class StockController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {

        $stocks = Stock::orderBy('id', 'DECS');

        if ($request->has('q')) {
            $q = trim($request->q);
            $stocks->orWhere('name', 'LIKE', "%$q%")
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

        return view('stocks.index')->with('stocks', $stocks);
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

        $image = $request->file('image');

        if (!empty($image)) {

            $input['image'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'images/stocks';
            $image->move($destinationPath, $input['image']);
            $image = $input['image'];
        }

        $stock = new Stock;
        $stock::create($input);

        $stock_id = DB::getPdo()->lastInsertId();

        $this->createLog('Add', $stock_id, 0, $request->qty, $request->qty);

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
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $stock = Stock::find($id);
        if (is_null($stock)) {

            return redirect()->back();
        }

        return view('stocks.edit')->with('stock', $stock);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $input = array_map('trim', $request->all());
        $rules = Stock::$rules;
        $rules['name'].= ",name,$id";

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput($input)->withErrors($validator->errors());
        }

        $image = $request->file('image');

        if (!empty($image)) {

            $input['image'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'images/stocks';
            $image->move($destinationPath, $input['image']);
            $image = $input['image'];
        }

        $stock = Stock::find($id);

        if ($stock->qty != $request->qty) {

            if ($stock->qty < $request->qty) {
                $change_qty = $request->qty - $stock->qty;
            } else {
                $change_qty = '-';
                $change_qty .= $stock->qty - $request->qty;
            }

            $this->createLog('Update', $id, $stock->qty, $change_qty, $request->qty);
        }

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
        $stock = Stock::find($id);

        if (is_null($stock)) {

            return redirect()->back();
        }

        $invoice = InvoiceItem::where('product_id', $id)->get();


        foreach ($invoice as $ids) {

            InvoiceItem::where('invoice_id', $ids->invoice_id)->delete();
            Invoice::where('id', $ids->invoice_id)->delete();
        }

        StockHistory::where('stock_id', $id)->delete();

        $stock->delete();

        Session::flash('flash_message', 'Stock deleted successfully.');
        Session::flash('flash_type', 'alert-success');
        return redirect('stocks');
    }

    public function export() {


        Excel::create('Stocks', function($excel) {

            $excel->sheet('mySheet', function($sheet) {


                $reports = Stock::select('name', 'vintage', 'size', 'qty', 'owc', 'pound_cost', 'cost_hkd', 'selling_hkd', 'vip_hkd')->orderBy('id', 'DECS')->get();

                foreach ($reports as $report) {

                    $data[] = array(
                        $report->name,
                        $report->vintage,
                        $report->size,
                        $report->qty,
                        $report->owc,
                        $report->pound_cost,
                        $report->cost_hkd,
                        $report->selling_hkd,
                        $report->vip_hkd,
                    );
                }

                $sheet->fromArray($data);
                $sheet->row(1, array(
                    'Name', 'Vintage', 'Size', 'Qty', 'OWC', 'Pound Cost', 'Cost (HKD)', 'Selling (HKD)', 'VIP (HKD)'
                ));
            });
        })->download('xls');
    }

    public function importForm() {
        return view('stocks.import');
    }

    public function saveImport(Request $request) {


        $update = [];
        $names = [];
        $duplicate_records = [];

        if (Input::hasFile('import')) {

            $input = $request->all();
            $path = Input::file('import')->getRealPath();
            $data = Excel::load($path, function($reader) {
                        
                    })->get();

            $allowed = array('xls', 'xlsx', 'xlt', 'xlsm');
            $filename = $request->file('import')->getClientOriginalName();
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {

                return redirect()->back()->withErrors(['import' => 'Please upload files having extensions: .xls, .xlsx, .xlt, .xlsm only']);
            }


            if (!empty($data) && $data->count()) {


                foreach ($data as $key => $value) {


                    if (!empty($value->name) && !empty($value->qty) && !empty($value->cost_hkd) && !empty($value->selling_hkd) && !empty($value->vip_hkd)) {

                        $stock = Stock::where('name', '=', $value->name)->first();

                        if ($stock) {

                            $update[] = ['name' => $value->name, 'vintage' => $value->vintage, 'size' => $value->size, 'qty' => $value->qty, 'owc' => $value->owc, 'pound_cost' => $value->pound_cost, 'cost_hkd' => $value->cost_hkd, 'selling_hkd' => $value->selling_hkd, 'vip_hkd' => $value->vip_hkd, 'old_details' => $stock];
                        } else {

                            $insert[] = ['name' => $value->name, 'vintage' => $value->vintage, 'size' => $value->size, 'qty' => $value->qty, 'owc' => $value->owc, 'pound_cost' => $value->pound_cost, 'cost_hkd' => $value->cost_hkd, 'selling_hkd' => $value->selling_hkd, 'vip_hkd' => $value->vip_hkd];

                            if (in_array($value->name, $names)) {

                                $duplicate_records[] = $value->name;
                            }

                            $names[] = $value->name;
                        }
                    } else {

                        Session::flash('flash_message', 'Required filed are empty in import file');
                        Session::flash('flash_type', 'alert-danger');
                        return redirect()->back();
                    }
                }

                if ($duplicate_records) {

                    Session::flash('flash_message', 'Found duplicate records');
                    Session::flash('flash_type', 'alert-danger');
                    return redirect()->back();
                }

                $is_imported = false;

                if (!empty($insert)) {
                    $is_imported = true;
                    foreach ($insert as $values) {

                        $stock = new Stock;
                        $stock::create($values);

                        $this->createLog('Import', DB::getPdo()->lastInsertId(), 0, $values['qty'], $values['qty']);
                    }
                }

                if (!empty($update)) {

                    $is_imported = true;
                    foreach ($update as $values) {

                        $oldData = $values['old_details'];
                        unset($values['old_details']);
                        $new_qty = $values['qty'] + $oldData->qty;

                        $change_qty = $values['qty'];

                        $values['qty'] = $new_qty;


                        Stock::where('id', $oldData->id)->update($values);

                        $this->createLog('Import', $oldData->id, $oldData->qty, $change_qty, $values['qty']);
                    }
                }


                if ($is_imported) {
                    Session::flash('flash_message', 'Stock imported successfully.');
                    Session::flash('flash_type', 'alert-success');

                    return redirect()->back();
                } else {

                    Session::flash('flash_message', 'Stock not added');
                    Session::flash('flash_type', 'alert-danger');
                    return redirect()->back();
                }
            } else {

                return redirect()->back()->withErrors(['import' => 'import field is required']);
            }
        } else {

            return redirect()->back()->withErrors(['import' => 'import field is required']);
        }

        return redirect('stocks');
    }

    public function sampleExport() {

        $data = Sample::select('name', 'vintage', 'size', 'qty', 'owc', 'pound_cost', 'cost_hkd', 'selling_hkd', 'vip_hkd')->orderBy('id', 'DECS')->get();

        Excel::create('Sample', function($excel) use ($data) {

            $excel->sheet('mySheet', function($sheet) use ($data) {

                $sheet->fromArray($data);

                $sheet->row(1, array(
                    'Name', 'Vintage', 'Size', 'Qty', 'OWC', 'Pound Cost', 'Cost (HKD)', 'Selling (HKD)', 'VIP (HKD)'
                ));
            });
        })->download('xlsx');
    }

    function history(Request $request, $id = 0) {

        $stock = Stock::find($id);
        $data_range = \Helper::get_date_range();

        if (!$stock) {
            return redirect()->back();
        }

        $historyQuery = StockHistory::with('user')->where('stock_id', $id)->orderBy('id', 'DESC');

        if ($request->has('date_range')) {

            if ($request->sd && $request->ed) {

                $historyQuery->whereBetween('created_at', [($request->sd).' 00:00:00', ($request->ed).' 23:59:59']);
            }
        } elseif ($request->has('time_period')) {
            $data_range = \Helper::get_date_range();
            $value = explode(':', $data_range[$request->time_period]);
            $historyQuery->whereBetween('created_at', [($value[0] . ' 00:00:00'), ($value[1] . ' 23:59:59')]);
        }

        if (!$request->has('search')) {
            $historyQuery->where(DB::raw('DATE(created_at)'), DB::raw('DATE(NOW())'));
        }

        $histories = $historyQuery->paginate();

        return view('stocks.history')->with(compact('stock', 'histories'));
    }

    function createLog($action, $stock_id, $old_qty, $change_qty, $new_qty) {

        if ($action == 'sale') {
            $change_qty = '-' . $change_qty;
        }

        $stockHistory = new StockHistory;
        $stockHistory->stock_id = $stock_id;
        $stockHistory->user_id = \Auth::user()->id;
        $stockHistory->action = $action;
        $stockHistory->old_qty = $old_qty;
        $stockHistory->qty = $change_qty;
        $stockHistory->new_qty = $new_qty;
        $stockHistory->save();
    }

}
