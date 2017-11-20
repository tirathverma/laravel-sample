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
use Excel;
use Helper;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /*
     * get sales report
     */
    public function sales(Request $request) {

        $reports = array();
        $byStock = '';

        if ($request->has('search') || $request->has('export')) {

            $reports = Invoice::with('invoiceItem.stock', 'customer')->orderBy('id', 'DESC');

            if ($request->has('date_range')) {

                if ($request->sd && $request->ed) {
                    $reports->whereBetween('date', [($request->sd), ($request->ed)]);
                }
            } else {
                $data_range = Helper::get_date_range();
                $value = explode(':', $data_range[$request->time_period]);
                $start_date = $value[0];
                $end_date = $value[1];
                $reports->whereBetween('date', [($start_date), ($end_date)]);
            }

            if (!empty($request->item)) {
                $byStock = $request->item;
                $reports->whereHas('invoiceItem.stock', function($query) use($request) {
                    $query->where('stocks.name', $request->item);
                });
            }


            if ($request->has('paidstatus')) {

                $reports->Where('is_paid', '=', $request->paidstatus);
            }

            $reports = $reports->get();
        }

        if ($request->has('export')) {
            $this->exportSales($reports, $byStock);
        }

        return view('reports.sales')->with(compact('reports', 'customers', 'data_range'));
    }

    
    /*
     * customer report
     */
    public function customers(Request $request) {

        $customers = Customer::orderBy('name', 'ASC')->get();
        $reports = array();
        $byStock = '';

        if ($request->has('search') || $request->has('export')) {

            $reports = Invoice::with('invoiceItem.stock', 'customer')->orderBy('id', 'DESC');
            if ($request->has('date_range')) {

                if ($request->sd && $request->ed) {
                    $reports->whereBetween('date', [($request->sd), ($request->ed)]);
                }
            } else {
                $data_range = Helper::get_date_range();
                $value = explode(':', $data_range[$request->time_period]);

                $start_date = $value[0];
                $end_date = $value[1];
                $reports->whereBetween('date', [($start_date), ($end_date)]);
            }

            if ($request->customer) {
                $reports->where('customer_id', $request->customer);
            }

            if ($request->has('paidstatus')) {
                $reports->Where('is_paid', '=', $request->paidstatus);
            }

            $reports = $reports->get();
        }

        if ($request->has('export')) {
            $this->exportCustomers($reports);
        }

        return view('reports.customers')->with(compact('reports', 'customers', 'data_range'));
    }
    
    /*
     *  get Stock report
     */
    public function stocks(Request $request) {

        $data_range = array();

        $query = Stock::orderBy('id', 'DESC');
        // $totalQty = $query->sum( 'qty');
        // $totalCost = $query->sum( 'cost_hkd');
        // $totalSellingPrice = $query->sum( 'selling_hkd');
        // $totalVipPrice = $query->sum( 'vip_hkd');

        if ($request->has('export')) {

            $reports = $query->paginate('100');
            $this->exportStocks($reports);
        } else {
            $reports = $query->paginate('100');
        }

        return view('reports.stocks')->with(compact(
                                'reports', 'data_range'
        ));
    }
    
    /*
     * Search stock
     */
    public function search(Request $request) {

        if ($request->has('term')) {

            $stocks = Stock::Where('name', 'like', "%$request->term%")->get();
        }

        return (json_encode($stocks));
    }
    
    /*
     *  search customer
     */
    public function customerSearch(Request $request) {

        if ($request->has('term')) {

            $customer = Customer::Where('name', 'like', "%$request->term%")->get();
        }
        return (json_encode($customer));
    }

    
    /*
     *  download export sales record
     */
    public function exportSales($data, $byStock = null) {

        Excel::create('Sales', function($excel) use($data, $byStock) {

            $excel->sheet('mySheet', function($sheet) use($data, $byStock) {

                $totalCost = 0;
                $totalSubtotal = 0;
                $totalProfit = 0;
                $i = 2;

                foreach ($data as $report) {

                    $mergeStart = 0;
                    $mergeEnd = 0;
                    $mergeTotal = 0;
                    $inoiceCost = 0;
                     
                    $itemCount = count($report->invoiceItem);
                    $inoiceCost = $report->invoiceItem->sum('cost_hkd');

                    foreach ($report->invoiceItem as $item) {

                        $cost = 0;
                        $sale_price = 0;
                        $profit = 0;

                        if ($byStock) {
                            if ($item->stock['name'] != $byStock) {
                                continue;
                            }
                        }

                        $cost = ($item->cost_hkd * $item->quantity);
                        $sale_price = $item->total;

                        $profit = $sale_price - $cost;
                        $totalCost += $cost;
                        $totalSubtotal += $sale_price;
                        $totalProfit += $profit;
                        $status = '' ;
                        
                        if($report->is_paid == 1) {
                        
                            $status = 'Paid' ;
                      
                        } else if($report->is_paid == 0) {
                            
                            $status = 'Unpaid' ; 
                        
                        } else {
                           
                            $status = 'Void' ; 
                        } 

                        $reportData[$i] = array(
                            $report->date,
                            $status,
                            $report->invoice_number,
                            $report->customer->name,
                            $item->stock['name'],
                            $item->stock['vintage'],
                            $item->stock['size'],
                            $item->stock['owc'],
                            $item->quantity,
                            '$' . $cost,
                            '$' . $sale_price,
                            '$' . $profit,
                            '$' . $inoiceCost
                        );

                        if ($itemCount > 1) {
                            if ($mergeTotal == 0) {
                                $mergeStart = $i;
                            }
                            $mergeTotal++;
                        }

                        $i++;
                    }

                    if ($mergeTotal) {
                        $mergeEnd = ($mergeStart + $mergeTotal) - 1;

                        $sheet->setMergeColumn(array(
                            'columns' => array('A', 'B', 'C', 'D', 'M'),
                            'rows' => array(
                                array($mergeStart, $mergeEnd)
                            ),
                        ));
                    }
                }

                $reportData[] = array();
                $reportData[] = array();

                $reportData[] = array(
                    'Summary',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '$' . $totalCost,
                    '$' . $totalSubtotal,
                    '$' . $totalProfit
                );

                $sheet->fromArray($reportData);
                $sheet->row(1, array(
                    'Date', 'Status', 'Invoice Number', 'Customer', 'Item', 'Vintage', 'Size', 'OWC', 'Quantity', 'Cost (HKD)', 'Price (HKD)', 'Profit (HKD)', 'Total (HKD)'
                ));
            });
        })->download('xls');
    }
    
   
    
    /*
     *  Download Customer report in excel
     */
    
    public function exportCustomers($data) {

        Excel::create('Customers', function($excel) use($data) {

            $excel->sheet('mySheet', function($sheet) use($data) {

                $totalCost = 0;
                $totalSubtotal = 0;
                $totalProfit = 0;
                $transactions = 0;
                $items = 0;

                foreach ($data as $report) {

                    $transactions++;

                    foreach ($report->invoiceItem as $item) {

                        $cost = 0;
                        $sale_price = 0;
                        $profit = 0;
                        $items++;

                        $cost = ($item->cost_hkd * $item->quantity);
                        $sale_price = $item->total;

                        $profit = $sale_price - $cost;
                        $totalCost += $cost;
                        $totalSubtotal += $sale_price;
                        $totalProfit += $profit;
                        $status = '' ;
                        
                        if($report->is_paid == 1) {
                        
                            $status = 'Paid' ;
                      
                        } else if($report->is_paid == 0) {
                            
                            $status = 'Unpaid' ; 
                        
                        } else {
                           
                            $status = 'Void' ; 
                        } 


                        $reportData[] = array(
                            $report->date,
                            $status,
                            $report->invoice_number,
                            $report->customer->name,
                            $item->stock['name'],
                            ''  .$item->stock['vintage'],
                            $item->stock['size'],
                            $item->stock['owc'],
                            ''  . $item->quantity,
                            '$' . $cost,
                            '$' . $sale_price,
                            '$' . $profit
                        );
                    }
                }

                $reportData[] = array();
                $reportData[] = array();
                //$reportData[] = array('');
                $reportData[] = array(
                    'Summary',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Transactions',
                    'Items',
                    'SubTotal (HKD)',
                    'Cost (HKD)',
                    'Profit (HKD)'
                );

                $reportData[] = array(
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''  .$transactions,
                    ''  .$items,
                    '$' . $totalCost,
                    '$' . $totalSubtotal,
                    '$' . $totalProfit
                );

                $sheet->fromArray($reportData);
                $sheet->row(1, array(
                    'Date', 'Status', 'Invoice Number', 'Customer', 'Item', 'Vintage', 'Size', 'OWC', 'Quantity', 'Total (HKD)', 'Cost (HKD)', 'Profit (HKD)'
                ));
            });
        })->download('xls');
    }
    
    
     
    /*
     *  Download export report in excel
     */
    public function exportStocks($reports) {

        Excel::create('Stocks', function($excel) use($reports) {

            $excel->sheet('mySheet', function($sheet) use($reports) {

                $i = 0;
                $totalQuantity = 0;
                $totalCost = 0;
                $totalSellingTotal = 0;
                $totalVip = 0;
                $totalMinimumProfit = 0;
                $totalMaximumProfit = 0;

                foreach ($reports as $value) {

                    $cost = 0;
                    $sale_price = 0;
                    $profit = 0;

                    $i++;

                    $totalQuantity += $value->qty;
                    $totalCost += $value->cost_hkd;
                    $totalSellingTotal += $value->selling_hkd;
                    $totalVip += $value->vip_hkd;

                    $maxProfit = ($value->selling_hkd - $value->cost_hkd) * ($value->qty);
                    $minProfit = ($value->vip_hkd - $value->cost_hkd) * ($value->qty);

                    $totalMinimumProfit += $minProfit;
                    $totalMaximumProfit += $maxProfit;


                    $data[] = array(
                        $value->name,
                        '' . $value->qty,
                        '$' . $value->cost_hkd,
                        '$' . $value->selling_hkd,
                        '$' . $value->vip_hkd,
                        '$' . $maxProfit,
                        '$' . $minProfit
                    );
                }

                $data[] = array();
                $data[] = array(
                    'Summary',
                    '' . $totalQuantity,
                    '$' . $totalCost,
                    '$' . $totalSellingTotal,
                    '$' . $totalVip,
                    '$' . $totalMinimumProfit,
                    '$' . $totalMaximumProfit,
                );

                $sheet->fromArray($data);
                $sheet->row(1, array(
                    'Item Name', 'Quantity', 'Cost (HKD)', 'Selling (HKD)', 'VIP (HKD)', 'Maximum Profit', 'Minimum Profit'
                ));
            });
        })->download('xls');
    }

}
