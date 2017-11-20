@extends('layouts.app')
@section('title', 'Customers List')
@section('content')

<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class="col-xs-12 wrapper-box">
            <div class="page-title">

                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START --><h1 class="title">Customers</h1><!-- PAGE HEADING TAG - END -->                            </div>
                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>                            
                            <a  href="{{url('/dashboard')}}">
                                <i class="fa fa-home"></i>Home</a>
                        </li>
                        {{--  <li>
                            <a  href="{{url('/customers')}}">
                        Customers</a>
                        </li> --}}
                        <li class="active">
                            <strong>Customers Listing</strong>
                        </li>
                        <li style="display:none;">
                        </li>
                    </ol>
                </div>

            </div>
            <div class="col-md-4 col-sm-12 search-box">
                <form name="search" action="{{URL::to('/customers')}}" method="get" class="form-inline" style=" ">
                    <input type="text" name="q" placeholder="search..." class="form-control search-bar" value="{{Request::input('q')}}">
                    <input type="submit" value="Search" class="btn btn-primary search-btn">
                </form>
            </div>
            <div class="add-btn pull-right" >

                <a  href="{{url('customers/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add Customer </a>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12" style="margin-top: 11px">

            @if(Session::has('flash_message'))
            <div class="alert {{ Session::get('flash_type') }}">
                {{Session::get('flash_message')}}
            </div>
            @endif 
            <section class="box clearfix">
                <header class="panel_header">

                    {{-- <div class=" pull-left">
                        <form name="search" action="{{URL::to('/customers')}}" method="get" class="form-inline pull-right" style="margin-top:20px; padding-left:20px;">

                    <input type="text" name="q" placeholder="search..." class="form-control" value="{{Request::input('q')}}">
                    <input type="submit" value="Search" class="btn btn-primary">

                    </form>
                    </div> --}}

                    <div class="actions panel_actions pull-right">
                        {{--    <a class="box_toggle fa fa-chevron-down"></a>
                        <a class="box_close fa fa-times"></a> --}}
                    </div>
                </header>
                <div class="content-body col-md-12 table-responsive">    
                    <table id="example" class="display table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th width="30%">Address</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = (Request::input('page')) ? (Request::input('page') - 1) * $customers->perPage() + 1 : 1;
                            ?>
                            @if(count($customers))
                            @foreach($customers as $value )
                            <tr>
                                <td>{{ $i}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td>{{($value->company)}}</td>

                                <td>
                                    @if(!empty($value->address))
                                    {{  substr($value->address,0,40)}}</td>
                                @endif
                                <td>{{ ($value->remark)}}</td>

                                <td>
                                    <form method="POST" action="{{URL::to('customers')}}/{{$value->id}}" id="{{ $value->id }}" accept-charset="UTF-8">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">

                                        <a class="btn btn-primary" href="{{URL::to('/customers')}}/{{$value->id}}/edit" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>

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
                                <td colspan="4">No customers found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $customers->links() }}
                    </div>
                </div>
        </div>
        </div>

        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Delete Customer</h4>
                    </div>
                    <div class="modal-body"> Are you sure want to delete? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <a href="#" class="btn btn-danger" id="danger">Delete</a> </div>
                </div>
            </div>
        </div>

        @endsection