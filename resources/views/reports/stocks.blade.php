@extends('layouts.app')
@section('title', 'Stock Holding Report')
@section('content')
<!-- START CONTENT -->

<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class="col-xs-12 wrapper-box">
            <div class="page-title">
                <div class="pull-left">
                    <h1 class="title">Stock Holding Report</h1>                             
                </div>

            </div>
            <div class=" pull-left">

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
            <section class="box clearfix">
                <header class="panel_header">

                    <div class="pull-left" style="margin-top:20px; padding-left:20px;">
                        <form name="search" action="{{URL::to('reports/stocks')}}" method="get" class="">
                            <button type="submit" name="export" value="1" class="btn btn-primary">Export </button>
                        </form>
                    </div>

                    <div class="actions panel_actions pull-right">
                        {{--    <a class="box_toggle fa fa-chevron-down"></a>
                        <a class="box_close fa fa-times"></a> --}}
                    </div>
                </header>
             
                <div class="content-body table-responsive">    

                    <!-- ********************************************** -->

                    <table id="example" class="display table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Cost (HKD) </th>
                                <th>Selling (HKD) </th>
                                <th>VIP (HKD) </th>
                                <th>Maximum Profit (HKD) </th>
                                <th>Minimum Profit (HKD) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = (Request::input('page')) ? (Request::input('page') - 1) * $reports->perPage() + 1 : 1;

                            $totalQuantity = 0;
                            $totalCost = 0;
                            $totalSellingTotal = 0;
                            $totalVip = 0;
                            $totalMinimumProfit = 0;
                            $totalMaximumProfit = 0;
                            ?>

                            @if(count($reports))
                            @foreach($reports as $value )

                            <tr id="results">
                                <td>{{ $i}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->qty}}</td>
                                <td>${{number_format($value->cost_hkd,2, '.', '')}}</td>
                                <td>${{number_format($value->selling_hkd,2, '.', '')}}</td>
                                <td>${{number_format($value->vip_hkd,2, '.', '')}}</td>
                                <td>${{number_format(($value->selling_hkd - $value->cost_hkd) *  ($value->qty),2, '.', '')}}</td>
                                <td>${{number_format(($value->vip_hkd - $value->cost_hkd) *  ($value->qty),2, '.', '') }} </td>
                            </tr>
                            <?php
                            $totalQuantity = $totalQuantity + $value->qty;
                            $totalCost = $totalCost + $value->cost_hkd;
                            $totalSellingTotal = $totalSellingTotal + $value->selling_hkd;
                            $totalVip = $totalVip + $value->vip_hkd;

                            $totalMinimumProfit = $totalMinimumProfit + ($value->selling_hkd - $value->cost_hkd) * ($value->qty);

                            $totalMaximumProfit = $totalMaximumProfit + ($value->vip_hkd - $value->cost_hkd) * ($value->qty);
                            $i++;
                            ?>
                            @endforeach 



                            <tr>
                                <td colspan="2"><b>Summary<b></td>

                                            <td><b>{{$totalQuantity}}</b></td>
                                            <td><b>${{number_format($totalCost,2, '.', '')}}</b></td>
                                            <td><b>${{number_format($totalSellingTotal,2, '.', '')}}</b></td>
                                            <td><b>${{number_format($totalVip,2, '.', '')}}</b></td>

                                            <td><b>${{number_format($totalMinimumProfit,2, '.', '')}}</b></td>
                                            <td><b>${{number_format($totalMaximumProfit,2, '.', '')}}</b></td>
                                            </tr>  


                                            @else
                                            <tr>
                                                <td colspan="4">No Stocks found.</td>
                                            </tr>

                                            @endif
                                            </tbody>
                                            </table>
                                            <div class="text-center">
                                                @if(count($reports))
                                                {{ $reports->links() }}
                                                @endif
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
                                                    $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
                                                    $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
                                                });




                                            </script>
                                            <style type="text/css">
                                                .ui-autocomplete{
                                                    overflow:scroll;
                                                    height: 90px;
                                                    width: 280px;
                                                }
                                            </style>

                                            </section>
    </section>
                                            @endsection