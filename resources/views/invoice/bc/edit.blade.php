@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Edit Invoice</h1><!-- PAGE HEADING TAG - END -->
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
                            <strong>Edit Invoice</strong>
                        </li>
                    </ol>
                </div>                 
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- MAIN CONTENT AREA STARTS -->
        <div class="col-xs-12">
            <section class="box clearfix">
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

                    <form class="form-inline" method="post" action="{{URL('invoices')}}/{{ $invoice->id }}">

                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="_method" value="PUT">

                        <table class="table table-hover table-condensed my-table">
                            <tr>
                                <td><label class="col-2 col-form-label imp" for="date">Date:</label></td>
                                <td>  
                                    <input type="text" class="form-control"  placeholder="Date" name="date" id="datepicker1" value="{{ $invoice->date }}" readonly="readonly">
                                </td>

                                <td><label class="col-2 col-form-label imp" for="Invoice">Invoice:</label></td>
                                <td>  
                                    <input type="text" class="form-control"  placeholder="Invoice Number" name="invoice_number"  value="{{ $invoice->invoice_number }}" readonly="readonly">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="email" class="col-2 col-form-label imp">Stocks:</label></td>                              
                                <td> 
                                    <div class="form-group" >
                                        <input class="form-control" placeholder="Search Stocks"   type="text" id="search-bar">
                                        <a href="#" class="" id="openStockmodal"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    </div>

                                </td>

                                <td><label class="col-2 col-form-label imp" for="email">Bill To:</label></td>
                                <td>  
                                    <input class="form-control" placeholder="Search & Add Customers" type="text" id="search-customers" value="{{$invoice->customer->name}}" name="customer_name">
                                    <input type="hidden" name="customer_id" value="" id="search-customers_hidden">
                                    <input type="text" class="form-control"  placeholder="Search & Add Company" name="bill_to" id="search-company" value="{{ !empty($invoice->customer->company) ?  $invoice->customer->company :'' }}">
                                </td>

                            </tr>
                            <tr>
                                <td><label class="col-2 col-form-label imp" for="email">Due Date:</label></td>
                                <td>  
                                    <input type="text" class="form-control"  placeholder="Due Date" name="due_date" id="datepicker" value="{{ $invoice->due_date }}" readonly="readonly">
                                </td>

                                <td><label class="col-2 col-form-label imp" for="email">Currency Type:</td>
                                <td>
                                    <div class="radio radio-inline currency_type">
                                        <label><input type="radio" name="currency_type" value="HKD" @if($invoice->currency_type ==  'HKD') checked="checked" @endif> HKD</label>
                                    </div>
                                    <div class="radio radio-inline currency_type">
                                        <label><input type="radio" name="currency_type" value="RMB" @if($invoice->currency_type ==  'RMB') checked="checked" @endif> RMB</label>
                                    </div>
                                </td>

                            </tr>
                            <tr>

                            </tr>
                        </table>  

                        <div class="table-responsive col-md-12 table-box">
                            <table class="table manage_grid">
                                <thead>
                                    <tr>
                                        <th width="">Product Detail</th>
                                        <th width="40%">Unit Price</th>
                                        <th width="100px">Quantity</th>
                                        <th width="">Total</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $grandTotal = 0; ?>
                                    @if(count($invoice))
                                    @foreach($invoice->invoiceItem as $value )
                                    <tr>
                                        <td>{{ $value->stock->name }}
                                            <input type="hidden" value="{{$value->product_id}}" name="product_id[{{$value->product_id}}]" class="stockid"></td>
                                        <td class="radio-td">
                                            <label class="radio-inline">Selling</label><input type="radio" class="inline_content" data='Selling' name="HKD_price[{{$value->product_id}}]" value="{{$value->stock->selling_hkd}}" @if( $value->price_type == 'Selling') checked @endif>

                                            <label>{{number_format($value->stock->selling_hkd,2, '.', '')}}</label>&nbsp;&nbsp;&nbsp;&nbsp;

                                            <label class="radio-inline">Vip</label><input type="radio" data='Vip' class="inline_content" name="HKD_price[{{$value->product_id}}]" value="{{$value->stock->vip_hkd }}"  @if( $value->price_type == 'Vip') checked @endif>

                                          <label>{{number_format($value->stock->vip_hkd,2, '.', '')}}</label>

                                            <input type="text" class='form-control hkd_price rates' name="Unit_price[{{$value->product_id}}]" value="{{number_format($value->HKD_price,2, '.', '')}}" onkeypress="return isNumberKey(event)">
                                        </td>

                                        <td>
                                            <input type="text" class='form-control qty' size='1' value="{{($value->quantity)}}" name="qty[{{$value->product_id}}]" remaining_qty="{{$value->stock->qty}}" onkeypress='return isNumberKey(event)' max="99999"></td>

                                        <td><span class="total">
                                                {{number_format($value->total,2, '.', '')}}
                                            </span></td>

                                        <td><button type='button' data-row="{{$value->product_id}}" class="btnRemove btn btn-danger" ><i class='fa fa-times' aria-hidden=true></i></button>

                                            <input type='hidden' class='price_type' name="price_type[{{$value->product_id}}]" value="{{$value->price_type}}">

                                            <input type='hidden' class='cost_hkd' name="cost_hkd[{{$value->product_id}}]" value="{{$value->stock->cost_hkd}}">

                                            <input type='hidden' class='total_price' name="total_price[{{$value->product_id}}]" value="{{$value->unit_price}}">


                                        </td>

                                    </tr>
                                    @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>

                        <div class="sub-total col-md-12">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">


                                <tr class="">
                                    <td style="text-align: right"><h2>Grand Total</h2></td>
                                    <td class="border_none" align="right">
                                        <h3><span id="showcurrency">{{$invoice->currency_type}}</span>&nbsp;$<span id="grandTotal">{{ $invoice->total}}</span></h3></td>

                                </tr>

                                <input type="hidden" value="{{$invoice->total}}" id="grandTotalvalue" name="total">

                                <tr class="bg_color">
                                    <td style="text-align: right" colspan="2">
                                        <div class="form-group" style="margin-top: 15px;">
                                            <label for="comment">Comment:</label>
                                            <textarea class="form-control" cols="40" rows="3" name="comment" id="comment">{{$invoice->comment}}</textarea>
                                        </div>
                                    </td>
                                </tr>


                            </table>
                            <div class="form-group text-right " style="margin: 15px 0; width:100%;">
                                <button type="submit" class="btn btn-success">Update Invoice</button>
                                <a href="{{url('invoices')}}" class="btn btn-default">Cancel</a>

                            </div>

                    </form>
                </div>
        </div>

        </div>
        </div>
        </div>

        <div id="modal-content" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" style="overflow-y: initial !important">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3>Add Stocks</h3>
                    </div>
                    <div class="modal-body" style="overflow-y: auto ;max-height: 250px;">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Product Detail</th>
                                    <th>Quantity</th>
                                    <th>Selling Price</th>
                                    <th>Vip Price</th>                            
                                </tr>
                            </thead>
                            <tbody id="stocks"></tbody> 
                        </table>                        

                    </div>
                    <div class="modal-footer"> 
                        <a href="#" class="btn" data-dismiss="modal">Close</a>
                        <a href="#" class="btn btn-primary" id="addModalstock">Add</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
                $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
            });
        </script>

        <script type="text/javascript">
            var addedProductCodes = [];
            $(document).ready(function () {
                $('.stockid').each(function () {
                    var allstockid = $(this).val().trim();
                    addedProductCodes.push(allstockid);
                });

            });

            $(function () {

                $("#search-bar").autocomplete({
                    source: siteUrl + '/invoices/search',
                    select: function (event, ui) {


                        var pid = ui.item.id.toString();
                        var index = addedProductCodes.indexOf(pid);

                        if (index > -1) {
                            alert("You already added this Product");
                            return false;
                        }

                        addedProductCodes.push(pid);

                        if (ui.item.qty > 0) {
                            $('.manage_grid').append("<tbody<tr><td>" + ui.item.name + "</td><td class='radio-td'><label class='radio-inline'>Selling</label><input type='radio' class='inline_content'  name='HKD_price[" + ui.item.id + "]'  value=" + ui.item.selling_hkd + " checked><label>" + parseFloat(ui.item.selling_hkd).toFixed(2) + "</label>&nbsp;&nbsp;&nbsp;&nbsp;<label class='radio-inline'>Vip</label><input type='radio'  class='inline_content' name='HKD_price[" + ui.item.id + "]' class='' value='" + ui.item.vip_hkd + "'><label>" + parseFloat(ui.item.vip_hkd).toFixed(2) + "</label><input type='text' class='form-control rates hkd_price' name='Unit_price[" + ui.item.id + "]' id='rate' value='" + parseFloat(ui.item.selling_hkd).toFixed(2) + "' onkeypress='return isNumberKey(event)'></td><td><input type=text value='1'  name='qty[" + ui.item.id + "]' class='form-control qty' size='1' data=" + ui.item.selling_hkd + " remaining_qty='" + ui.item.qty + "' onkeypress='return isNumberKey(event)' ></td><td><span class='total' id=total_id_" + ui.item.id + " name='product_price[]'>" + ui.item.selling_hkd + "</span><td><button type='button' class='btnRemove btn btn-danger' ><i class='fa fa-times' aria-hidden=true></i></button><input type='hidden' class='total_price' name='total_price[" + ui.item.id + "]'></td> <input type='hidden' class='price_type' name='price_type[" + ui.item.id + "]' value='Selling'></tr></tbody><input type='hidden' name='product_id[" + ui.item.id + "]' value='" + ui.item.id + "'> <input type='hidden' name='cost_hkd[" + ui.item.id + "]' value='" + ui.item.cost_hkd + "'>");
                            calculateAmount();


                        } else {
                            alert("Item is out of stock");
                            return false;
                        }

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

                    var row = parseInt($(this).attr('data-row'));
                    var index = addedProductCodes.indexOf($(this).attr('data-row'));

                    if (index > -1) {
                        addedProductCodes.splice(index, 1);
                    }

                    $(this).closest('tr').remove();
                    $(this).closest('tbody').remove();

                    calculateAmount();
                });


                $('body').on('keyup', '.hkd_price', function () {

                    var selectedsellPrice = $(this).closest("tbody").find(".inline_content:checked").val();
                    var sellPrice =  $(this).val();
                    var qty = $(this).closest("tr").find(".qty").val();
                    var total = parseFloat(qty * selectedsellPrice).toFixed(2);

                    if(sellPrice == "") {
                        alert('Unit price field is required.')
                        var sellPrice = $(this).val(selectedsellPrice);
                        total =  parseFloat(qty * sellPrice).toFixed(2);
                       
                    } 

                    $(this).parents('tbody').find('.total').text(total);
                    calculateAmount();
                     

                });

                $('body').on('keyup', '.qty', function () {

                    var qty = $(this).val();
                    var remaining_qty = $(this).attr('remaining_qty');

                    var sellPrice = $(this).closest("tbody").find(".inline_content:checked").val();
                    var total = parseFloat(qty * sellPrice).toFixed(2);

                    if(qty == "") {
                        alert('Quantity field is required.')
                        var qty = $(this).val(parseInt(remaining_qty));
                        total =  parseFloat(qty * sellPrice).toFixed(2);
                        
                    }

                    if(parseInt(this.value) > remaining_qty) {
                        alert('Quantity is out of stock.')
                        var qty = $(this).val(remaining_qty);
                        total =  parseFloat(qty * sellPrice).toFixed(2);
                        
                    }

                    if(qty == 0) {
                        alert('Quantity must be greater than zero.')
                        var qty = $(this).val(remaining_qty);
                        total =  parseFloat(qty * sellPrice).toFixed(2);
                        
                    }

                    $(this).parents('tbody').find('.total').text(total);
                    calculateAmount();

                });


                $('body').on('change', '.inline_content', function () {

                    var qty = $(this).closest("tr").find(".qty").val();
                    var sellPrice = parseFloat($(this).val()).toFixed(2);
                    var total = parseFloat(qty * sellPrice).toFixed(2);
                    var price_type = $(this).attr('data');

                    $(this).closest("tr").find(".rates").val(sellPrice);
                    $(this).closest("tr").find(".price_type").val(price_type);

                    calculateAmount();

                });

                $(".currency_type").change(function () {

                    calculateAmount();

                });

                $('.qty').prop('maxLength', 5);
            });



            ///only enter Number 
            function isNumberKey(evt) {

                 var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                        alert('please enter numeric value')
                        return false;
                    }
            }

            



            $(function () {
                $("#search-customers").autocomplete({
                    source: siteUrl + '/invoices/customer-search',
                    select: function (event, ui) {
                        $("#search-customers").val(ui.item.name);
                        $("#search-customers_hidden").val(ui.item.id);
                        $("#search-company").val(ui.item.company);
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



            $(function () {

                $("#search-company").autocomplete({
                    source: siteUrl + '/invoices/search-company',
                    select: function (event, ui) {
                        $("#search-company").val(ui.item.company);
                        $("#search-company_hidden").val(ui.item.id);
                        //$("#search-customers").val(ui.item.name);
                        return false;
                    },
                })

                        .data("ui-autocomplete")._renderItem = function (ul, item) {

                    var inner_html;
                    inner_html = "<span>" + item.company + "</span>";

                    return $("<li />")
                            .data("ui-autocomplete-item", item)
                            .append("<a>" + item.company + "</a>")
                            .appendTo(ul);
                };

            });

            // open modal

            $('#openStockmodal').click(function () {

                $.ajax({
                    type: "get",
                    async: false, //here you are synchrone siteUrl + '/invoices/search',
                    url: "{{URL::to('/invoices/search')}}",
                    dataType: 'json',
                    success: function (data) {

                        var stocks = [];
                        $.each(data, function (key, value) {

                            var html = "<tr><td><input type='checkbox' class='stocks' name='stock_id' value='" + value.id + "' stock_name='" + value.name + "' selling_price='" + value.selling_hkd + "' cost_hkd = '" + value.cost_hkd + "' vip_price='" + value.vip_hkd + "' qty='" + value.qty + "' ></td><td>" + value.name + "</td><td>" + value.qty + "</td><td>" + value.selling_hkd + "</td><td>" + value.vip_hkd + "</td></tr>";
                            stocks.push(html);

                        });

                        $('#stocks').html(stocks)
                    }

                });

                $('#modal-content').modal({
                    show: true

                });


            });



            // Append html after open modal
            $('#addModalstock').click(function () {

                $('#modal-content').modal('hide');

                $('input[name="stock_id"]:checked').each(function () {

                    var id = (this.value);
                    var stock_name = $(this).attr('stock_name');
                    var selling_price = $(this).attr('selling_price');
                    var vip_hkd = $(this).attr('vip_price');
                    var qty = $(this).attr('qty');
                    var cost_hkd = $(this).attr('cost_hkd');

                     var pid = id.toString();
                        var index = addedProductCodes.indexOf(pid);

                        if (index > -1) {
                            alert("You already added this Product");
                            return false;
                        }

                       addedProductCodes.push(pid);

                    if (qty > 0) {
                        $('.manage_grid').append("<tbody<tr><td>" + stock_name + "</td><td class='radio-td'><label class='radio-inline'>Selling</label><input type='radio' class='inline_content'  name='HKD_price[" + id + "]'  value=" + selling_price + " checked><label>" + parseFloat(selling_price).toFixed(2) + "</label>&nbsp;&nbsp;&nbsp;&nbsp;<label class='radio-inline'>Vip</label><input type='radio'  class='inline_content' name='HKD_price[" + id + "]' class='' value='" + vip_hkd + "'><label>" + parseFloat(vip_hkd).toFixed(2) + "</label><input type='text' class='form-control rates hkd_price' onkeypress='return isNumberKey(event)' name='Unit_price[" + id + "]'   value='" + parseFloat(selling_price).toFixed(2) + "' ></td><td><input type=text value='1'  name='qty[" + id + "]' class='form-control qty' size='1' data=" + selling_price + " remaining_qty='" + qty + "' onkeypress='return isNumberKey(event)' ></td><td><span class='total' id=total_id_" + id + " name='product_price[]'>" + selling_price + "</span><td><button type='button' class='btnRemove btn btn-danger' ><i class='fa fa-times' aria-hidden=true></i></button><input type='hidden' class='total_price' name='total_price[" + id + "]'></td> <input type='hidden' class='price_type' name='price_type[" + id + "]' value='Selling'></tr></tbody><input type='hidden' name='product_id[" + id + "]' value='" + id + "'> <input type='hidden' name='cost_hkd[" + id + "]' value='" + cost_hkd + "'>");



                    } else {

                        alert("Item is out of stock");
                        return false;
                    }

                });

                calculateAmount();
            });

            $('body').on('click', '.btnRemove', function () {

                var row = parseInt($(this).attr('data-row'));
                var index = addedProductCodes.indexOf($(this).attr('data-row'));
                if (index > -1) {
                    addedProductCodes.splice(index, 1);
                }

                $(this).closest('tr').remove();
                $(this).closest('tbody').remove();

                calculateAmount();
            });


            function calculateAmount() {

                var currency_type = $('input[name=currency_type]:checked').val();
                var amount = parseFloat('{{$settings->currency_rate}}').toFixed(2);

                var grandTotal = 0;


                $('.hkd_price').each(function () {

                    var amt = parseFloat($(this).val());
                    var qty = $(this).closest("tr").find(".qty").val();
                    if (currency_type == 'RMB') {
                        var total = parseFloat(amt * amount).toFixed(2);
                    } else {
                        var total = amt;
                    }

                    $(this).parents('tr').find('.total_price').val(total);

                    total = total * qty;


                    grandTotal += total;

                    $(this).parents('tr').find('.total').text(parseFloat(total).toFixed(2));

                });


                grandTotal = parseFloat(grandTotal).toFixed(2);


                $('#grandTotal').text(grandTotal);
                $('#grandTotalvalue').val(grandTotal);
                $('#showcurrency').text(currency_type);


            }


            $("form").submit(function(){
                var customer_name = $('#search-customers').val(); 
                
                 alert(sellPrice);
                if(customer_name == "") {
                    alert('Bill to field is required.')
                    return false;
                }


                return true;
            });

        </script>

        <style> 
            .table tr td{ vertical-align: middle; }  
            /*.table tr td input{width:100%; }*/
            .table tr select{width:100%; }
            .table tr .form-group{width:100% !important; }
            .ui-autocomplete{overflow:scroll;height: 90px; width: 280px; }
            .radio-td input{ margin-right: 4px; display: inline-block; width: auto !important; margin-left: 10px; margin-top: 0px; vertical-align: middle; }
            .radio-td .rates{     margin-left: 0px;
                                  margin-top: 0px;
                                  width: 82px !important;
                                  padding: 10px 5px;
                                  margin-left: 9px; float: right;
            }   
            .radio-td .rate{ margin-left: 0px; margin-top: 5px; }   
            .radio-td label{vertical-align: middle; margin-bottom: 0px; padding-left: 0px;}
        </style>



        @endsection
