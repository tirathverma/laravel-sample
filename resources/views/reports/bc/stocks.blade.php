@extends('layouts.app')
@section('title', 'Invoices List')
@section('content')
<!-- START CONTENT -->

<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
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
            <section class="box ">
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
                <div class="content-body">    <div class="row">
                        <div class="col-xs-12">
                            <!--
                             ********************************************** -->

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
                                 <?php $i++; ?>
                                @endforeach 
                                <tr>
                                <td colspan="2"><b>Summary<b></td>
                                <td><b>{{$totalQty}}</b></td>
                                <td><b>${{number_format($totalCost,2, '.', '')}}</b></td>
                                <td><b>${{number_format($totalSellingPrice,2, '.', '')}}</b></td>
                                <td><b>${{number_format($totalVipPrice,2, '.', '')}}</b></td>
                                <td><b>${{number_format(($totalSellingPrice - $totalCost)* ($totalQty),2, '.', '') }}</b></td>
                                <td><b>${{number_format(($totalVipPrice - $totalCost)* ($totalQty),2, '.', '')}}</b></td>
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
                
        $("#search").autocomplete({
              source: siteUrl + '/reports/search',
              
            select: function(event, ui){
                 $("#search").val(ui.item.name);
                 var test  = (ui.item.name);
                    // $.ajax({
                    //     url: siteUrl + '/reports/stocks',
                    //     dataType:"html",
                    //     type: "GET",
                    //     data: "term="+test,
                    //     success: function(html){
                    //     //$("#results").show();
                    //     $('#results').html('');
                    //     $("#results").append(html);
                    //     }
                    // });
                 
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
    .ui-autocomplete{
           overflow:scroll;
            height: 90px;
            width: 280px;
        }
</style>
@endsection