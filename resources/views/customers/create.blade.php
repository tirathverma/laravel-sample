@extends('layouts.app')
@section('content')

<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Add Customer</h1><!-- PAGE HEADING TAG - END -->
                </div>

                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/invoices')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                        <li>
                            <a href="{{url('/customers')}}">Customers</a>
                        </li>
                        <li class="active">
                            <strong>Add Customer</strong>
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

                <div class="content-body form-body">
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

                    <form method="post" action="{{URL('customers')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-8">

                                <div class="form-group">
                                    <label for="name" class="col-2 col-form-label imp">Name</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="name" placeholder="" value="{{old('name')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-2 col-form-label imp">Company</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="company" placeholder="" value="{{old('company')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="col-2 col-form-label imp">Address</label>
                                    <div class="col-6">
                                        <textarea class="form-control" rows="2" name="address" id="address">{{old('address')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-2 col-form-label">Remark</label>
                                    <div class="col-6">
                                        <textarea class="form-control" rows="2" name="remark" id="remark">{{old('remark')}}</textarea>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-success">Add Customer</button>
                                        <a href="{{url('customers')}}" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @endsection
