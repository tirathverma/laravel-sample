@extends('layouts.app')
@section('content')
 

<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''> 
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">View Invoice </h1><!-- PAGE HEADING TAG - END -->                                         
                </div>

                     
            </div>
        </div>
        <div class="clearfix"></div> 
        <div class="col-xs-12">
            <section class="box ">
                <header class="panel_header">
                    <h2 class="title pull-left">Basic Info</h2>
                </header>

                <div class="content-body form-body"> 
                      
                    <div class="row">
                        <div class="col-xs-12">
                 
                        <table class="table table-bordered">
                        <tr>
                            <td> <b> Date </b> </td>
                            <td> {{$invoice->date}} </td>
                        </tr>

                         <tr>
                            <td> <b> Invoice </b> </td>
                            <td> {{$invoice->invoice_number}} </td>
                        </tr>

                        <tr>
                            <td> <b> Bill To </b> </td>
                            <td> {{ucfirst($invoice->customer->name)}}@if(!empty($invoice->customer->company))-{{ucfirst($invoice->customer->company)}}@endif </td>
                        </tr>

                        <tr>
                            <td> <b> Due Date </b> </td>
                            <td> {{$invoice->due_date}} </td>
                        </tr>
 

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Grand Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php
                            $grandtotal = 0;
                            ?>
                            @if(count($invoice->invoiceItem))
                                @foreach($invoice->invoiceItem as $val )
                                    
                                  <tr>  
                                    <td>  {{ ucfirst($val->stock->name)  }}</td>
                                    <td>{{ $val->unit_price }}</td>
                                    <td>{{ $val->quantity }}</td>
                                    <td>${{ number_format($val->unit_price  *  $val->quantity,2, '.', '') }}</td>
                                    <td>${{ number_format($val->unit_price  *  $val->quantity,2, '.', '') }}</td>
                                  </tr>

                                  <?php

                                   $grandtotal = $grandtotal + ($val->unit_price  *  $val->quantity); 
                                    

                                  ?>
                                @endforeach
                                 
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Total</b></td>
                                    <td colspan=""><b>{{$invoice->currency_type}}&nbsp;${{number_format($grandtotal,2, '.', '') }}</b></td>
                                </tr>
                            </tfoot>
                        </table>

                        @if($invoice->comment)
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
 

                    </table>
                    
                    <a href="{{url('invoices')}}" class="btn btn-default">Back</a>
                    </div>  
                    </div>
            </section>
        </div>
               
    </div>
</div>

<style type="text/css">
/*.table td{padding: 0 5px !important; font-size: 12px !important; border-collapse: collapse;}
.table th{padding: 0 5px !important; font-size: 12px !important; border-collapse: collapse;}
#main-content{ margin-left: 0px;}
.content-body{ border-top:1px solid #e8e8e8 !important;}
.logo-td{ border-top:0; }    */
</style>        
 
        @endsection
