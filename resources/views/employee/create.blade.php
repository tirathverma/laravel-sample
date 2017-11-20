@extends('layouts.app')
@section('content')

<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Add Employee</h1><!-- PAGE HEADING TAG - END -->
                </div>

                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/invoices')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                        <li>
                            <a href="{{url('/employee')}}">Employees</a>
                        </li>
                        <li class="active">
                            <strong>Add Employee</strong>
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

                    <form method="post" action="{{URL('employee')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-8">

                                <div class="form-group">
                                    <label for="name" class="col-2 col-form-label imp">Name</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="name" placeholder="Eg: John Mayer" value="{{old('name')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-2 col-form-label">Phone</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="phone" placeholder="+91-987654321" value="{{old('phone')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="col-2 col-form-label">Address</label>
                                    <div class="col-6">
                                        <textarea class="form-control" rows="2" name="address" id="address">{{old('address')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-2 col-form-label imp">Username</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="username" placeholder="Eg: JohnM" value="{{old('username')}}">
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label for="name" class="col-2 col-form-label imp">Hide cost price and cost per pound</label>
                                    <div class="col-6">

                                        <div class="radio">
                                            <label><input type="radio" name="employee_access" value="0" @if(old('employee_access') == 0) checked @endif>Hide</label>
                                        </div>

                                        <div class="radio">
                                            <label><input type="radio" name="employee_access" value="1" @if(old('employee_access') == 1)) checked @endif>Show</label>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <label for="example-password-input" class="col-2 col-form-label imp">Password</label>
                                    <div class="col-6">
                                        <input class="form-control" type="password" placeholder="Password" name="password" id="example-password-input" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="c-pwd" class="col-2 col-form-label imp">Confirm Password</label>
                                    <div class="col-6">
                                        <input type="Password" id="c-pwd" name="password_confirmation" class="form-control" value="" placeholder="Password Confirmation">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-success">Add Employee</button>
                                        <a href="{{url('employee')}}" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>


        @endsection
