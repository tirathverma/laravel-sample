@extends('layouts.app')
@section('content')


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

                    <form class="form-inline" method="post" action="{{URL('invoices')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <table class="table table-hover table-condensed my-table">
                            <tr>    
                                <td>
                                    <label for="email" class="col-2 col-form-label imp">Date:</label></td>              
                                <td>  
                                    <input type="text" class="form-control"  placeholder="Date" name="date" id="datepicker1" value="{{old('date')}}" readonly="readonly">
                                </td>


                                <td><label class="col-2 col-form-label imp" for="Invoice">Invoice:</label></td>
                                <td>  
                                    <input type="text" class="form-control"  placeholder="Invoice Number" name="invoice_number"  value="{{$settings->invoice_prefix}}{{$invoices[0]->Auto_increment}}" readonly="readonly">
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

                                    <input class="form-control" placeholder="Search & Add Customers" type="text" id="search-customers" value="{{old('customer_name')}}" name="customer_name">

                                    <input type="hidden" name="customer_id" value="" id="search-customers_hidden">

                                    <input type="text" class="form-control"  placeholder="Search & Add Company" name="bill_to" id="search-company" value="{{old('bill_to')}}">

                                    <input type="hidden" name="comanpy_id" value="" id="search-company_hidden">
                                </td>
                            </tr>
                            <tr>   
                                <td><label class="col-2 col-form-label imp" for="email">Due Date:</label></td>

                                <td>  
                                    <input type="text" class="form-control"  placeholder="Due Date" name="due_date" id="datepicker" value="{{old('due_date')}}" readonly="readonly">
                                </td>

                                <td>

                                    <label class="col-2 col-form-label imp" for="">Currency Type:</td>
                                <td>

                                    <div class="radio-inline currency_type">
                                        <label><input type="radio" name="currency_type" value="HKD" checked="checked">HKD</label>
                                    </div>&nbsp;&nbsp;&nbsp;
                                    <div class="radio-inline currency_type">
                                        <label><input type="radio" name="currency_type" value="RMB" >RMB</label>
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
                                        <th width="">Unit Price</th>
                                        <th width="100px">Quantity</th>
                                        <th width="">Total</th>
                                        <th> </th>
                                    </tr>
                                </thead> 
                            </table>
                        </div>
                        <div class="sub-total col-md-12">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr class="gray_bg">
                                    <td style="text-align: right"><h2>Grand Total</h2></td>
                                    <td class="border_none" align="right">
                                        <h3><span id="showcurrency">HKD</span>&nbsp;$<span id="grandTotal">0.00</span></h3></td>
                                </tr>

                                <input type="hidden" value="" id="grandTotalvalue" name="total">

                                <input type="hidden" value="{{$settings->currency_rate}}" name="currencyrate">
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

<!-- Modal -->
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
 

<script type="text/javascript">
    var addedProductCodes = new Array();

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

                    $('.manage_grid').append("<tbody<tr id='" + ui.item.id + "'><td>" + ui.item.name + "</td><td class='radio-td'><label class='radio-inline'>Selling</label><input type='radio' class='inline_content '  name='HKD_price[" + ui.item.id + "]'  value=" + ui.item.selling_hkd + " data='Selling'  checked><label>" + parseFloat(ui.item.selling_hkd).toFixed(2) + " </label>&nbsp;&nbsp;&nbsp;&nbsp;<label class='radio-inline'>Vip</label><input type='radio' data='Vip'  class='inline_content' name='HKD_price[" + ui.item.id + "]' class='' value='" + ui.item.vip_hkd + "' ><label>" + parseFloat(ui.item.vip_hkd).toFixed(2) + "</label><input type='text' class='form-control rate hkd_price' name='Unit_price[" + ui.item.id + "]' id='rate' value='" + parseFloat(ui.item.selling_hkd).toFixed(2) + "' onkeypress='return isNumberKey(event)'></td><td><input type=text value='1'  name='qty[" + ui.item.id + "]' remaining_qty='" + ui.item.qty + "' class='form-control qty' size='1' maxlength='5' data=" + ui.item.selling_hkd + " onkeypress='return isNumberKey(event)'  oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'></td><td><span class='total' id=total_id_" + ui.item.id + " name='product_price[]'>" + ui.item.selling_hkd + "</span><td><button type='button' data-row='" + ui.item.id + "' class='btnRemove btn btn-danger' ><i class='fa fa-times' aria-hidden=true></i></button><input type='hidden' class='total_price' name='total_price[" + ui.item.id + "]'><input type='hidden' class='price_type' name='price_type[" + ui.item.id + "]' value='Selling'></td></tr></tbody><input type='hidden' name='product_id[" + ui.item.id + "]' value='" + ui.item.id + "'><input type='hidden' name='cost_hkd[" + ui.item.id + "]' value='" + ui.item.cost_hkd + "'>");

                    var currency_type = $('input[name=currency_type]:checked').val();
                    if (currency_type == 'RMB') {

                        // var test =   $('.total').text(rmb_amount);
                        $('.total').each(function () {
                            var rmb_amount = (ui.item.selling_hkd * '{{$settings->currency_rate}}').toFixed(2);
                            var amt = parseFloat(rmb_amount);

                            $(this).find('.total').text(amt);
                        });


                    }
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

            $(this).closest('tbody').remove();
            calculateAmount();
        });



        $('body').on('keyup', '.qty', function () {

            var qty = $(this).val();
            var remaining_qty = $(this).attr('remaining_qty');
            var sellPrice = $(this).closest("tbody").find(".inline_content:checked").val();
            var total = parseFloat(qty * sellPrice).toFixed(2);

            if(qty == "") {
                alert('Quantity field is required.')
                var qty = $(this).val(remaining_qty);
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


        $('body').on('keyup', '.rate', function () {

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

        $('body').on('change', '.inline_content', function () {
            var sellPrice = parseFloat($(this).val()).toFixed(2);
            var price_type = $(this).attr('data');
            $(this).closest("tbody").find(".rate").val(sellPrice);
            //$(this).parents('tbody').find('.total').text(total);
            $(this).closest("tr").find(".price_type").val(price_type);
            calculateAmount();

        });



        $(".currency_type").change(function () {
            var currency_type = $('input[name=currency_type]:checked').val();
            var amount = '{{$settings->currency_rate}}';
            var total = 0;
            calculateAmount();

        });

    });



    ///only enter Number 
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            alert('please enter numeric value')
            return false;
        }

        return true;
    }

    $('body').on('keyup', '.rates', function () {
 
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




    $(function () {
        $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'}).datepicker("setDate", "0");
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'}).datepicker("setDate", "0");
    });



    $(function () {

        $("#search-customers").autocomplete({
            source: siteUrl + '/invoices/customer-search',
            select: function (event, ui) {
                $("#search-customers").val(ui.item.name);
                $("#search-company").val(ui.item.company);
                $("#search-customers_hidden").val(ui.item.id);
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
                // $("#search-customers").val(ui.item.name);
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
                $('.manage_grid').append("<tbody<tr id='" + id + "'><td>" + stock_name + "</td><td class='radio-td'><label class='radio-inline'>Selling</label><input type='radio' class='inline_content '  name='HKD_price[" + id + "]'  value=" + selling_price + " data='Selling'  checked><label>" + parseFloat(selling_price).toFixed(2) + " </label>&nbsp;&nbsp;&nbsp;&nbsp;<label class='radio-inline'>Vip</label><input type='radio' data='Vip'  class='inline_content' name='HKD_price[" + id + "]' class='' value='" + vip_hkd + "' ><label>" + parseFloat(vip_hkd).toFixed(2) + "</label><input type='text' class='form-control rate hkd_price' name='Unit_price[" + id + "]' id='rate' value='" + parseFloat(selling_price).toFixed(2) + "' onkeypress='return isNumberKey(event)'></td><td><input type=text value='1'  name='qty[" + id + "]' remaining_qty='" + qty + "' class='form-control qty' size='1' maxlength='5' data=" + selling_price + " onkeypress='return isNumberKey(event)'  oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'></td><td><span class='total' id=total_id_" + id + " name='product_price[]'>" + selling_price + "</span><td><button type='button' data-row='" + id + "' class='btnRemove btn btn-danger' ><i class='fa fa-times' aria-hidden=true></i></button><input type='hidden' class='total_price' name='total_price[" + id + "]'><input type='hidden' class='price_type' name='price_type[" + id + "]' value='Selling'></td></tr></tbody><input type='hidden' name='product_id[" + id + "]' value='" + id + "'><input type='hidden' name='cost_hkd[" + id + "]' value='" + cost_hkd + "'>");

            } else {

                alert("Item is out of stock");
                return false;
            }

        });
        calculateAmount();


    });

    function calculateAmount() {

        var currency_type = $('input[name=currency_type]:checked').val();
        var amount = parseFloat('{{$settings->currency_rate}}').toFixed(2);
        var grandTotal = 0;

        $('.hkd_price').each(function () {
            var amt = parseFloat($(this).val());
            var qty = $(this).closest("tbody").find(".qty").val();

            if (currency_type == 'RMB') {
                var total = parseFloat(amt * amount).toFixed(2);
            } else {
                var total = amt;
            }

            $(this).parents('tr').find('.total_price').val(total);

            total = total * qty;
            total = Math.round(total * 100) / 100;
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
        var company_name = $('#search-company').val(); 
        if(!customer_name &&  !company_name) {
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
                          margin-left: 9px;
    }   
    .radio-td .rate{    margin-left: 0px;
                        margin-top: 0px;
                        width: 82px !important;
                        padding: 10px 5px;
                        margin-left: 9px; float: right;}   
    .radio-td label{vertical-align: middle; margin-bottom: 0px; padding-left: 0px;}
</style>



@endsection
