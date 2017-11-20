@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Add Invoice</h1><!-- PAGE HEADING TAG - END -->
                </div>

                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/dashboard')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                        <li>
                            <a href="{{url('/invoices')}}">Invoices</a>
                        </li>
                        <li class="active">
                            <strong>Add Invoice</strong>
                        </li>
                    </ol>
                </div>                 
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- MAIN CONTENT AREA STARTS -->
        <div class="col-xs-12">
            <section class="box ">
                <header class="panel_header">
                    <h2 class="title pull-left">Basic Info</h2>
                </header>

                <div class="content-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(Session::has('flash_message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}"> {{ Session::get('flash_message') }} </div>
                    @endif

                    <form class="form-inline" method="post" action="{{URL('invoices')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                         
                       <table class="table">
                            <tr>    
                                <td>
                                    <label for="email" class="col-2 col-form-label imp">Date:</label></td>              
                                <td>  
                                      <input type="text" class="form-control"  placeholder="Date" name="date" id="datepicker1" value="{{old('date')}}">
                                </td>
                               

                                <td><label class="col-2 col-form-label imp" for="Invoice">Invoice:</label></td>
                                <td>  
                                   <input type="text" class="form-control"  placeholder="Invoice Number" name="invoice_number"  value="{{$settings->invoice_prefix}}{{$invoices[0]->Auto_increment}}" >
                                </td>
                            </tr> 
                            <tr>
                                <td>
                                <label for="email" class="col-2 col-form-label imp">Stocks:</label></td>                              
                                <td> 
                                    <div class="form-group" >
                                        <input class="form-control" placeholder="Search Stocks"   type="text" id="search-bar">
                                    </div>
                                </td>
                               
                                <td><label for="email" class="col-2 col-form-label imp">Customers: </label></td>
                                <td>  
                                    <select class="form-control" name="customer_id" id="customer_id">
                                        <option value="">Select Customer:</option>
                                        @foreach($customers as $customer)
                                        <option value="{{$customer->id}}"  @if (old('customer_id') == $customer->id) selected="selected" @endif>{{ucfirst($customer->name)}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                             <tr>   
                                 <td><label class="col-2 col-form-label imp" for="email">Due Date:</label></td>
                                <td>  
                                      <input type="text" class="form-control"  placeholder="Due Date" name="due_date" id="datepicker" value="{{old('due_date')}}">
                                </td>


                            </tr>
                        </table>  
                                <table class="table manage_grid">
                                    <thead>
                                        <tr>
                                            <th width="30%">Product Detail</th>
                                            <th width="20%">Unit Price</th>
                                            <th width="20%">Quantity</th>
                                            <th width="20%">Total</th>
                                             
                                        </tr>
                                    </thead> 
                                     

                                </table>

                                <div class="sub-total">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
 
                                        <tr class="gray_bg">
                                            <td style="text-align: right"><h2>Grand Total</h2></td>
                                            <td class="border_none" align="right"><h3>$<span id="grandTotal">0.00</span></h3></td>
                                        </tr>

                                        <input type="hidden" value="" id="grandTotalvalue" name="total">

                                        <tr class="bg_color">
                                            <td style="text-align: right" colspan="2">
                                                <div class="form-group" style="margin-top: 15px;">
                                                    <label for="comment">Comment:</label>
                                                    <textarea class="form-control" cols="40" rows="3" name="comment" id="comment"></textarea>
                                                </div>
                                            </td>
                                        </tr>


                                    </table>

                                    <div class="form-group text-right " style="margin: 15px 0; width:100%;">
                                        <button type="submit" class="btn btn-success">Add Invoice</button>
                                            <a href="{{url('invoices')}}" class="btn btn-default">Cancel</a>
                                        
                                    </div>
                                </div>
                           </div>
                        </div>  
                    </form>
                </div>
            </section>
        </div>
 <!--
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content 
                <div class="modal-content">
                    <div class="modal-header">
                        {{--  <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                        <h4 class="modal-title">Select Price</h4>
                    </div>
                    <div class="modal-body">
                        <div class="radio-inline">
                            <div><label>Pound Cost</label></div>
                            <label><input type="radio" name="price" id="price" value="" checked><span id="price">0.00</span></label>
                        </div>
                        <div class="radio-inline">
                            <div><label>Cost HKD</label></div>
                            <label><input type="radio" name="price" id="price1" value=""><span id="price1">0.00</span></label>
                        </div>
                        <input type="hidden" value="" id="data" name="data">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary savePrice" data-dismiss="modal" >ok</button>
                    </div>
                </div>

            </div>
        </div>

        <button type='button' class='myPrice' data-toggle='modal' data-price='" + ui.item.pound_cost + "'data-price2='" + ui.item.cost_hkd + "'data-target='#myModal' href='#myModal' data-qt  y='" + ui.item.qty + "' data-id='" + ui.item.id + "'><span class='cost' id='" + ui.item.id + "'>" + ui.item.pound_cost + "</span></button>
-->
 
        <script type="text/javascript">
         var addedProductCodes = new Array();

            $(function () {
            $("#search-bar").autocomplete({
            source: siteUrl + '/invoices/search',
                select: function (event, ui) {

                    var index = $.inArray(ui.item.id, addedProductCodes);
                   
                    if (index >= 0) {
                        alert("You already added this Product");
                        return false ;
                     }

                    addedProductCodes.push(ui.item.id);
                    
                   
                    $('.manage_grid').append("<tbody<tr><td>" + ui.item.name + "</td><td><label class='radio-inline'><input type='radio' class='inline_content'  name='Unit_price["+ui.item.id+"]'  value="+ ui.item.pound_cost +" checked style='margin-left:-32px'>"+ ui.item.pound_cost +" </label><label class='radio-inline'><input type='radio'  class='inline_content' name='Unit_price["+ui.item.id+"]' class='' value='"+ ui.item.cost_hkd +"' style='margin-left:-32px'>"+ ui.item.cost_hkd +"</td><td><input type=text value='1'  name='qty["+ui.item.id+"]' class='form-control qty' size='1' maxlength='5' data=" + ui.item.pound_cost + " onkeypress='return isNumberKey(event)'  oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'></td><td><span class='total' id=total_id_" + ui.item.id + " name='product_price[]'>" + ui.item.pound_cost + "</span><td><button type='button' class='btnRemove' ><i class='fa fa-times' aria-hidden=true></i></button></td></td></tr></tbody><input type='hidden' name='product_id["+ui.item.id+"]' value='" + ui.item.id + "'>");
                        calculateAmount();

            }  
               

            }).data("ui-autocomplete")._renderItem = function (ul, item) {
            var inner_html;
            inner_html = "<span>" + item.name + "</span>";
            return $("<li />")
                    .append($("<a>").text(item.name))
                    .data("ui-autocomplete-item", item)
                    .appendTo(ul);
            };
            $('body').on('click', '.btnRemove', function () {
            $(this).closest('tbody').remove();
            calculateAmount();
            });
           
            //Select Price
            $('body').on('click', '.myPrice', function () {

                var qty = $(this).val();
                var sellPrice = $(this).attr('data-price');
                var sellPrice2 = $(this).attr('data-price2');
                var data = $(this).attr('data-id');
                $(".modal-body #price").val(sellPrice);
                $(".modal-body #price").text(sellPrice);
                $(".modal-body #price1").val(sellPrice2);
                $(".modal-body #price1").text(sellPrice2);
                $(".modal-body #data").val(data);

            });
            //update price 

            $('body').on('click', '.savePrice', function () {

                qty = $("input[name=qty]").val();
                var id = ($('input[name="data"]').val());
                var price = ($('input[name="price"]:checked').val());
                var total = parseFloat(1 * price).toFixed(2);
                $('#' + id).text(price);
                $('#unit_price_' + id).val(price);
                $('#total_id_' + id).text(total);
                calculateAmount();
                $('#product_price_' + id).val(total);

            });

            $('body').on('keyup', '.qty', function () {

                var qty = $(this).val();
                var sellPrice = $(this).closest("tbody").find(".inline_content:checked").val();    
                var total = parseFloat(qty * sellPrice).toFixed(2);
                $(this).parents('tbody').find('.total').text(total);
                calculateAmount();

            });

            $('body').on('change', '.inline_content', function () {
                    
                var qty = $(this).closest("tbody").find(".qty").val();               
                var sellPrice = $(this).val();
                var total = parseFloat(qty * sellPrice).toFixed(2);
                
               $(this).parents('tbody').find('.total').text(total);
               calculateAmount();
             
            });
           
            function calculateAmount() {
                var total = 0;
                    $('.total').each(function () {
                        var amt = parseFloat($(this).text());
                        total += (amt);
                    });
                    $('#grandTotal').text(total);
                    $('#grandTotalvalue').val(total);
                }

            });

 
             

            ///only enter Number 
            function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                alert('please enter numeric value')
                        return false;
                }

                return true;
            }



            $(function () {
            $("#datepicker").datepicker();
            $("#datepicker1").datepicker();
            });

            // $(function () {
                
            //    $('.qty').prop('maxLength', 5);
            //    return true;
            // });

        </script>

        <style> 
        .table tr td{ vertical-align: middle; }  
        .table tr td input{width:100% !important; }
        .table tr select{width:100% !important; }
        .table tr .form-group{width:100% !important; }
 
        </style>



        @endsection
