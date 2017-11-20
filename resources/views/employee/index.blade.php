@extends('layouts.app')
@section('title', 'Employees List')
@section('content')
<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class="col-xs-12 wrapper-box">
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Employees</h1><!-- PAGE HEADING TAG - END -->                            
                </div>
                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>                            
                            <a  href="{{url('/dashboard')}}">
                                <i class="fa fa-home"></i>Home</a>
                        </li>
                        {{-- <li>
                            <a  href="{{url('/employee')}}">
                                Employees</a>
                        </li> --}}
                        <li class="active">
                            <strong>Employees Listing</strong>
                        </li>
                        <li style="display:none;">
                        </li>
                    </ol>
                </div>

            </div>
               <div class="col-md-4 col-sm-12 search-box">
                        <form name="search" action="{{URL::to('/employee')}}" method="get" class="form-inline" >
                        <input type="text" name="q" placeholder="search..." class="form-control search-bar" value="{{Request::input('q')}}">
                            <input type="submit" value="Search" class="btn btn-primary search-btn">
                        </form>
                    </div>
            <div class="add-btn pull-right" >
                <a  href="{{url('employee/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add Employee </a>
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
            <section class="box clearfix">
                <header class="panel_header">
 
                    <div class="actions panel_actions pull-right">
                     {{--    <a class="box_toggle fa fa-chevron-down"></a>
                        <a class="box_close fa fa-times"></a> --}}
                    </div>
                </header>
                <div class="content-body col-md-12 table-responsive">    
                    <!-- ********************************************** -->
                    <table id="example" class="display table table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th width="30%">Address</th>
                                        <th class="th-act">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = (Request::input('page')) ? (Request::input('page') - 1) * $employees->perPage() + 1 : 1;
                                    ?>
                                    @if(count($employees))
                                    @foreach($employees as $key => $value)

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ucfirst($value->name)}}</td>
                                        <td>{{($value->username)}}</td>
                                        <td>{{ucfirst($value->phone)}}</td>
                                        <td>
                                        @if(!empty($value->address))
                                        {{ substr($value->address,0,40)}}

                                        @endif
                                        </td>


                                        <td>
                                            <form method="POST" action="{{URL::to('employee')}}/{{$value->id}}" id="{{ $value->id }}" accept-charset="UTF-8">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">

                                                <a class="btn btn-primary" href="{{URL::to('/employee')}}/{{$value->id}}/edit" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>

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
                                        <td colspan="6">No employee yet</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $employees->links() }}
                            </div>
                            <!-- ********************************************** -->
                </div>

            </section>

        </div>
         
        <!-- General section box modal start -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Delete Employee</h4>
                    </div>
                    <div class="modal-body"> Are you sure want to delete? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <a href="#" class="btn btn-danger" id="danger">Delete</a> </div>
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT AREA ENDS -->

        @endsection 
