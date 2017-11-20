@extends('layouts.apps')
@section('content')
<?php
$file_name = $invoice->customer->name;
if ($invoice->customer->company) {
    $file_name .= ' - ' . $invoice->customer->company;
}

$file_name .= ' ' . $invoice->invoice_number;
?>
<script type="text/javascript">
    document.title = '<?php echo $file_name; ?>';
    window.print();
</script>

<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row">
        <div class='col-xs-12'>

        </div>

        <!-- MAIN CONTENT AREA STARTS -->
        <div class="col-xs-12">

            <div class="content-body form-body">  

                <form method="" action="">
                    <table class="table" style="border-collapse: collapse;">
                        <tr>
                            <td style="border-top:none;"><img src="{{asset('/images/logo/'. $settings->logo)}}" width="160px"></td>

                        </tr>

                        <tr>
                            <td> {!! $settings->header  !!}</td>
                            <td>
                                <table class="table table-bordered">
                                    <tr><th>Date</th><td>{{$invoice->date}}</td></tr>
                                    <tr><th>Invoice</th><td>{{$invoice->invoice_number}}</td></tr>
                                    <tr><th>Customer Id</th><td>{{$invoice->customer_id}}</td></tr>
                                    <tr><th>Due Date</th><td>{{$invoice->due_date}}</td></tr>

                                </table>
                            </td> 
                        </tr>

                        <tr>
                            <th colspan="2" >Bill To<br />

                                <b>{{ucfirst($invoice->customer->name)}}</b>

                                &nbsp;-&nbsp;
                                @if(!empty($invoice->bill_to))
                                <b> {{ ucfirst($invoice->bill_to)}} </b>
                                @endif
                            </th></tr>
                        <tr> 

                        </tr>


                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Size</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                  
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $grandtotal = 0;
                                ?>
                                @if(count($invoice->invoiceItem))
                                @foreach($invoice->invoiceItem as $val )

                                <tr>  
                                    <td> {{ ucfirst($val->stock->name)  }}</td>
                                    <td> {{ ucfirst($val->stock->size)  }}</td>
                                    <td>{{ number_format($val->unit_price,2, '.', '') }}</td>
                                    <td>{{ $val->quantity }}</td>
                                    <td>{{ number_format($val->unit_price  *  $val->quantity,2, '.', '') }}</td>
                                 
                                </tr>

                                <?php
                                $grandtotal = $grandtotal + ($val->unit_price * $val->quantity);
                                ?>
                                @endforeach

                                @endif
                            </tbody>
                            <tfoot>
                            
                                    <td colspan="4" class="text-right"><b>Total</b></td>
                                    <td colspan=""><b>{{$invoice->currency_type}}&nbsp;${{number_format($grandtotal,2, '.', '') }}</b></td>
                                 
                            </tfoot>
                        </table>

                        @if(!empty($invoice->comment))
                        <table class="table table-bordered" style="width:65%">
                            <thead>
                                <tr>
                                    <th>Other Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$invoice->comment}}</td>
                                </tr>
                            </tbody>

                        </table>
                        @endif
                        
                        <table border="1">
                            <tr>
                                {!! $settings->footer !!}
                            </tr>
                        </table>
                   
                    </table>
                   
                </form>
            </div>

        </div>
        <style type="text/css">
            .table td{padding: 0 5px !important; font-size: 12px !important; border-collapse: collapse;}
            .table th{padding: 0 5px !important; font-size: 12px !important; border-collapse: collapse;}
            #main-content{ margin-left: 0px;}
            .content-body{ border-top:1px solid #e8e8e8 !important;}
            .logo-td{ border-top:0; }    
        </style>        

        @endsection
