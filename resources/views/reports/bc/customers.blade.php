@extends('layouts.app')
@section('title', 'Invoices List')
@section('content')
<!-- START CONTENT -->

<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Customer past transactions</h1><!-- PAGE HEADING TAG - END -->               
                </div>
            </div>


            <div class=" pull-left">
                <form name="search" action="{{URL::to('/reports/customers')}}" method="get" class=""  >
                <table class="table">
                <tr> 
                    <td> Date Range </td>  
                    <td>
                    <select name="time_period"  class="form-control">
                    <option @if(Request::input('time_period') == 'Today') selected @endif >Today</option>
                        <option @if(Request::input('time_period') == 'Yesterday') selected @endif>Yesterday</option>
                        <option @if(Request::input('time_period') == 'Last 7 Days') selected @endif>Last 7 Days
                        </option>
                        <option @if(Request::input('time_period') == 'This Month') selected @endif>This Month
                        </option>
                        <option @if(Request::input('time_period') == 'Last Month') selected @endif>Last Month
                        </option>
                        <option @if(Request::input('time_period') == 'This Year') selected @endif>This Year</option>
                        <option @if(Request::input('time_period') == 'Last Year') selected @endif>Last Year</option>
                        <option @if(Request::input('time_period') == 'All Time') selected @endif>All Time</option>
                    </select>
                    </td>
                    <td style="font-size: 18px; font-weight: 500;">OR</td>
                     <td class="date-range-1">     
                        <label>
                        <input type="radio" name="date_range" value="2" @if(Request::input('date_range') ==  2) checked @endif>From
                        </label>
                        
                    </td>
                    <td>
                        <input type="text" class="form-control"  placeholder="From" name="sd" id="datepicker" value="{{Request::input('sd')}}">
                    </td>
                    <!-- <td class="">  </td> -->
                    <td class="date-range-2"> 
                    <label>To</label>
                    <input type="text" class="form-control" placeholder="To" name="ed" id="datepicker1" value="{{Request::input('ed')}}"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label>Status</label>
                         <select name="paidstatus" id=""  class="form-control">
                                <option value="">All</option>
                                <option value="0" @if(Request::input('paidstatus') == '0')  selected="selected" @endif)>Unpaid</option>
                                <option value="1" @if(Request::input('paidstatus')  == '1')  selected="selected" @endif>Paid</option>                                    
                        </select>
                    </td>
                    <td colspan="2">
                        <label>Customer</label> 
                         <select name="customer"  class="form-control" required>
                                <option value="">- select - </option>
                                @if($customers)
                                    @foreach($customers as $cus)
                                    <option value="{{$cus->id}}" @if(Request::input('customer') == $cus->id) selected @endif>{{$cus->name}} - {{$cus->company}}</option>
                                    @endforeach
                                @endif                              
                        </select>

                        
                        </td>
                         
                  <td>  
                        <br/>
                        <input type="submit" value="Search" name="search" class="btn btn-primary"> 
                        <button type="submit" name="export" value="1" class="btn btn-primary">Export </button>
                 </td>
                        
                </tr>
                 </table>
                </form>

            </div>
          
            <div class="pull-right" >

                 
            </div> 
        </div>
        <div class="clearfix"></div>

        <!-- MAIN CONTENT AREA STARTS -->

        <div class="col-lg-12">
            @if(Session::has('flash_message'))
            <div class="alert {{ Session::get('alert-class') }}">
                {{Session::get('flash_message')}}
            </div>
            @endif 
            <section class="box ">
                <header class="panel_header">
 
                    <div class="pull-left" style="margin-top: 11px; margin-left: 25px">
                     @if($customers)
                        @foreach($customers as $cus)
                            @if(Request::input('customer') == $cus->id)
                           <b> {{ucfirst($cus->name)}} - {{ucfirst($cus->company)}} </b>
                             @endif     
                        @endforeach
                    @endif     
                    </div>

                </header>
                <div class="content-body">  

                <div class="row">

                        <div class="col-xs-12">
                            <!-- ********************************************** -->
                    

                     <table id="example" class="display table table-hover table-condensed">
                    
                    
                        <thead>
                            <tr>
                                <th> </th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Cost (HKD) </th>
                                <th>Total (HKD) </th>
                                <th>Profit (HKD) </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = (Request::input('page')) ? (Request::input('page') - 1) * $reports->perPage() + 1 : 1;
                            
                            $totalSubTotal = 0;
                            $totalCost  = 0;
                            $totalProfit = 0;
                            $transactions = 0; 
                            $items = 0; 

                            ?>
                            @if(count($reports))
                            @foreach($reports as $value )
 
                            <tr id="results">
                                <td>
                                <a href="javascript:void(0)" type="show" row="{{$value->id}}" class="show_details">
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </a>

                                </td>
                                <td>
                                @if(!empty($value->is_paid))
                                    Paid
                                @else
                                    Unpaid
                                @endif
                                </td>
                                <td>{{($value->date)}}</td>
                                <td>{{ucfirst($value->invoice_number)}}</td>
                                
                                <td>
                                    <?php
                                    $transactions ++;
                                    $cost = 0;
                                    $sale_price = 0;
                                    $child_data = '';
                                    $no = 0;
                                    foreach ($value->invoiceItem as $item) {
                                        $items++; 
                                        $no++;
                                        $cost += ($item->cost_hkd * $item->quantity);
                                        $sale_price += $item->total;

                                        $child_data .= '<tr><td>'.$no.'</td><td>'.$item->stock['name'].'</td><td>'.$item->stock['vintage'].'</td><td>'.$item->stock['size'].'</td><td>'.$item->stock['owc'].'</td><td>'.$item->quantity.'</td><td>'.'$'.number_format($item->cost_hkd,2, '.', '').'</td><td>'.'$'.number_format($item->unit_price,2, '.', '').'</td><td>'.'$'.number_format($item->total,2, '.', '').'</td></tr>';
                                        
                                    }
                                  
                                    $profit =  $sale_price -  $cost;
                                    ?>
                                      ${{ number_format($cost,2, '.', '') }} 
                                </td>
                                <td>
                                ${{ number_format($sale_price,2, '.', '') }}
                                    
                                </td>
                                 <td>${{ number_format($profit,2, '.', '') }}</td>
                                 
                            </tr>
                            <tr id="{{$value->id}}" style="display:none">
                                <td colspan="8" style="background-color:#ccc">
                                    <table class="display table table-hover table-condensed">
                                          <tr>
                                            <th>No.</th> 
                                            <th>Item</th>
                                            <th>Vintage</th>
                                            <th>Size</th>
                                            <th>OWC</th>
                                            <th>Quantity</th> 
                                            <th>Cost (HKD)</th> 
                                            <th>Price (HKD) </th> 
                                            <th>Total (HKD) </th>      
                                          </tr> 
                                        <?php echo  $child_data ?>
                                    </table>
                                </td>
                            </tr>    

                            <?php 
                                $totalSubTotal += $sale_price;
                                $totalCost  += $cost;
                                $totalProfit += $profit;
                                
                                $i++;
                            ?>
                            @endforeach
                           
                            @else 
                            <tr>
                                <td colspan="4">No reports found.</td>
                            </tr>
                            @endif
                        </tbody>
                       
                    </table>

                    @if(count($reports))
                        <table class="display table table-hover table-condensed" style="margin-top:70px">
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;background: #000; color: #fff;text-align: center;"><b>Summary</b></th>
                            <th><b>Transactions</b></th>
                            <th><b>Items</b></th>
                            <th><b>SubTotal (HKD) </b></th>
                            <th><b>Cost (HKD) </b></th>
                            <th><b>Profit (HKD) </b></th>

                        </tr>
                            
                        <tr>
                            <td><b>{{($transactions)}}</b></td>
                            <td><b>{{$items}}</b></td>
                            <td><b>${{number_format($totalSubTotal,2, '.', '')}}</b></td>
                            <td><b>${{number_format($totalCost,2, '.', '')}}</b></td>
                            <td><b>${{number_format($totalProfit,2, '.', '')}}</b></td>
                        </tr>
                        </table>
                    @endif

                     
                </div>
               
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Invoice</h4>
            </div>
            <div class="modal-body"> Are you sure want to delete? </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="danger">Delete</a> </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
    $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#datepicker1").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $(function () {
        
        $('.show_details').click(function(){
            var type = $(this).attr('type');
            var rowId = $(this).attr('row');
            if(type == 'show'){
                $(this).html('<i class="fa fa-minus-square" aria-hidden="true"></i>').attr('type','hide');
            }
            else{
                $(this).html('<i class="fa fa-plus-square" aria-hidden="true"></i>').attr('type','show');
            }
            $('#'+rowId).toggle();

        });

        $("#search").autocomplete({
              source: siteUrl + '/reports/search',
              
            select: function(event, ui){
                 $("#search").val(ui.item.name);
                 var test  = (ui.item.name);
                                      
             return false;
            },
            
        }) 

        .data("ui-autocomplete")._renderItem = function (ul, item) {
                                
            var inner_html;
            inner_html = "<span>" + item.name + "</span>";                  
            
            return $("<li />")
                .data("ui-autocomplete-item", item)
                .append("<a>" + item.name + "</a>")
                .appendTo(ul);
        };    

      });  

    
</script>
<style type="text/css">
    .ui-autocomplete{overflow:scroll;height: 90px; width: 280px; }
    .table td{ vertical-align: middle !important; }
    .date-range-1{ text-align: right; width: 80px; padding: 0px !important;}
    .date-range-1 label input{margin-right: 5px; vertical-align: middle; margin-top: 0px;}
    .date-range-1 label{ margin-bottom: 0px; }
    .date-range-2 label{ margin-bottom:0px; display: inline-block; margin-right: 7px;}
    .date-range-2 input{ display: inline-block; width:auto; }
</style>
@endsection