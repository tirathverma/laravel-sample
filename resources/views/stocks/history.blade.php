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
                    <h1 class="title">Stock History</h1><!-- PAGE HEADING TAG - END -->                      
                </div>
            </div>


            <div class=" pull-left">
                <form name="search" action="{{URL::to('/stocks/history')}}/{{$stock->id}}" method="get" class=""  >
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
                    <input type="text" class="form-control" placeholder="To" name="ed" id="datepicker1" value="{{Request::input('ed')}}">

                     <input type="submit" value="Search" name="search" class="btn btn-primary"> 

                    </td>
                </tr>
                <!--
                <tr>
                    <td colspan="3">
                        <input type="text" class="form-control"  placeholder="Search Items" name="item" id="search" value="{{Request::input('item')}}">
                    </td>
                    <td colspan="2" > <select name="paidstatus" id=""  class="form-control">
                                <option value="">All</option>
                                <option value="0" @if(Request::input('paidstatus') == '0')  selected="selected" @endif)>Unpaid</option>
                                <option value="1" @if(Request::input('paidstatus')  == '1')  selected="selected" @endif>Paid</option>                                    
                        </select>
                         
                        </td>
                         
                <td>
                    <input type="submit" value="Search" name="search" class="btn btn-primary"> 
                    <button type="submit" name="export" value="1" class="btn btn-primary">Export </button>
                </td>
                        
                </tr> -->
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
 
                    <div class="actions panel_actions pull-left" style="left:0">
                       
                    </div>
                </header>
                <div class="content-body">    <div class="row">
                    <div class="col-xs-12">
                     
                    <div style="margin-left:10px;">
                        <b>Stock : {{$stock->name}}</b><br/><br/>
                    </div>

                     <table id="example" class="display table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>Transaction Date</th>
                                <th>Old Quantity</th>
                                <th>Quantity</th>
                                <th>New Quantity</th>
                                <th>Action Type</th>
                                <th>Employee </th>
                                                   
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = (Request::input('page')) ? (Request::input('page') - 1) * $histories->perPage() + 1 : 1;
                            
                            $totalSubTotal = 0;
                            $totalCost  = 0;
                            $totalProfit = 0;
                            ?>
                            @if(count($histories))
                            @foreach($histories as $value )
 
                            <tr id="results">
                                <td>{{$value->created_at}}</td>
                                <td>{{$value->old_qty}}</td>
                                <td>{{$value->qty}}</td>
                                <td>{{$value->new_qty}}</td>
                                <td>{{ ucfirst($value->action) }}</td>    
                                <td>{{$value->user->name}}</td>

                            </tr>
                             
                             
                            @endforeach
                           
                            @else 
                            <tr>
                                <td colspan="4">No history found.</td>
                            </tr>
                            @endif
                        </tbody>
                       
                    </table>
                    <div class="text-center">
<!--
                        {{ $histories->links() }}
-->
	<?php
					 echo str_replace('/?', '?', $histories->appends(Input::except('page'))->render()); 
	?>
                    </div>	
                     

                     
                </div>
               
            </div>
        </div>
    </div>
</div>

 


<script type="text/javascript">
    $(function () {
    $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#datepicker1").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $(function () {
        
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
