@extends('layouts.app')
@section('title', 'Invoices List')
@section('content')
<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class="col-xs-12 wrapper-box">
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Invoices</h1><!-- PAGE HEADING TAG - END -->                                      
                </div>
                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>                            
                            <a  href="{{url('/dashboard')}}">
                                <i class="fa fa-home"></i>Home</a>
                        </li>
                         
                        <li class="active">
                            <strong>Invoices Listing</strong>
                        </li>
                        <li style="display:none;">
                        </li>
                    </ol>
                </div>

            </div>
            <div class="col-md-4 col-sm-12 search-box">
                <form name="search" action="{{URL::to('/invoices')}}" method="get" class="form-inline">
                    <input type="text" name="q" placeholder="search..." class="form-control search-bar" value="{{Request::input('q')}}">
                    <input type="submit" value="Search" class="btn btn-primary search-btn">

                </form>
            </div>
            <div class="add-btn pull-right" >

                <a  href="{{url('invoices/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add Invoice </a>
            </div>
        </div>
        <div class="clearfix"></div>

        <!-- MAIN CONTENT AREA STARTS -->

        <div class="col-lg-12" style="margin-top: 11px">
            @if(Session::has('flash_message'))
            <div class="alert {{ Session::get('flash_type') }}">
                {{Session::get('flash_message')}}
            </div>
            @endif 
            <div id="msg2"></div>
            <section class="box clearfix">
               
                <div class="content-body col-md-12 table-responsive">    
                    <!-- ********************************************** -->
                    <table id="example" class="display table table-hover table-condensed t-staus">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th >Invoice</th>
                                <th>Customer</th>
                                <th>Due Date</th>
                                <th width="10%">Comment</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="th-act">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = (Request::input('page')) ? (Request::input('page') - 1) * $invoices->perPage() + 1 : 1;
                            ?>
                            @if(count($invoices))
                            @foreach($invoices as $value )
                            <tr>
                                <td>{{ $i}}</td>
                                <td><a href="{{URL::to('invoices')}}/{{$value->id}}">{{ucfirst($value->invoice_number)}}</a></td>
                                <td>
                                    @foreach($customers as $customer)
                                    @if($customer->id == $value->customer_id)

                                    {{ucfirst($customer->name)}} 

                                    @endif
                                    @endforeach
                                </td>
                                <td>{{($value->due_date)}}</td>

                                <td>
                                    @if(!empty($value->comment))
                                    {{substr($value->comment,0,10)}}
                                    @endif
                                </td>
                                <td>{{$value->currency_type}} ${{ ($value->total)}}</td>

                                <td>
                                    @if($value->is_paid != 2)
                                    <div class="form-group">

                                        <select class="form-control status" id="sel1" onchange="changestatus('{{$value->id}}' , this.value)">
                                            <option value="0" @if($value->is_paid == 0) selected="selected"   @endif >Unpaid</option>
                                            <option value="1" @if($value->is_paid == 1) selected="selected" @endif >Paid</option>
                                            <option value="2" @if($value->is_paid == 2) selected="selected" @endif>Void</option>
                                        </select>
                                    </div>
                                    @else
                                    <select class="form-control status" disabled>
                                          <option value="Void">Void</option>
                                    </select>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{URL::to('invoices')}}/{{$value->id}}" id="{{ $value->id }}" accept-charset="UTF-8">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">

                                        <a class="btn btn-success" href="{{URL::to('/invoices/invoice-print')}}/{{$value->id}}"   role="button"> <i class="fa fa-print" aria-hidden="true"></i></a>

                                        <a class="btn btn-primary" href="{{URL::to('/invoices')}}/{{$value->id}}/edit" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                        <a class="btn my-btn btn-delete btn-danger" data-href="{{$value->id}}" data-toggle="modal" data-target="#confirm-delete" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>   

                                    </form> 

                                </td>
                            </tr>
                            <?php
                            $i++;
                            ?>
                            @endforeach

                            @else 
                            <tr>
                                <td colspan="4">No invoices found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $invoices->links() }}
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
            function changestatus(id, value) {

                $var = $(this).data('lastSelectedIndex', this.selectedIndex);
                var value = value;
                var id = id;

                if (value == 0 || value == 1 || value == 2) {

                    var status = confirm("Are you sure to change status");
                    
                    if (value == 2) {

                        $('#sel1').prop('disabled', true);
                        location.reload();

                    }

                    if (status == true) {
                        $.ajax({
                        type: "POST",
                                url: "{{URL::to('invoices/paid-status/')}}",
                                data: {'id': id, '_token': '{{ csrf_token() }}', 'value':value},
                                dataType: 'json',
                                success: function (data) {

                                    if (data.type == 'success') {

                                         $('#msg2').html('<div class="alert alert-success">' + data.msg + '</div>');
                                    }

                                }

                        });
                    } else {
                         location.reload();
                    }

                }


            }


        </script>

        @endsection