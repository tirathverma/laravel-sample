@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
<!-- START CONTENT --> 
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
    <div class='col-xs-12'>
        <div class="page-title">
            <div class="pull-left">
          <!-- PAGE HEADING TAG - START --><h1 class="title">Change Password</h1><!-- PAGE HEADING TAG - END -->                    </div>
                   <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/invoices')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                         <li class="active">
                            <strong>Change Password</strong>
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
			<h2 class="title pull-left">Change Password</h2>
		</header>
		
        <div class="content-body form-body ">				    
        @if (isset($errors) && count($errors) > 0)

    	<div class="alert alert-danger margin-top-2 clearfix">
		<strong>Whoops!</strong> There were some problem with your input.<br><br>
        	<ul>
                @foreach ($errors->all() as $error)
              	<li>{{ $error }}</li>
                @endforeach
            </ul>
  
		</div>
      	@endif
      	@if(Session::has('message'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }} margin-top-2">{{ Session::get('message') }}</p>
	    @endif	
    <div class="row">
        <div class="col-md-8 col-sm-9 col-xs-10">
			 <form  role="form" method="POST" action="{{ url('store-change-password') }}">
			 {{ csrf_field() }}
			<div class="form-group">
                <label class="form-label" for="field-1">Current Password</label>
                <div class="controls">
                    <input type="password" class="form-control" id="field-1" name="current_password" placeholder="Current Password">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="field-1">New Password</label>
                <div class="controls">
                    <input type="password" class="form-control" id="field-1" name="password" placeholder="New Password">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="field-2">Confirm Password</label>
                <div class="controls">
                    <input type="password"  class="form-control" id="field-2" name="confirm_password" placeholder="Confirm Password">
                </div>
            </div>

            <div class="form-group">
                <div class="controls">
					<button class="btn btn-primary">Submit</button>
                </div>
            </div>
			</form>
        </div>
    </div>


    </div>
        </section>
@endsection

