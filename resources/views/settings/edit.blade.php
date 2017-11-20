@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Edit Settings</h1><!-- PAGE HEADING TAG - END -->                                         
                </div>

                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a  href="{{url('/dashboard')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                        {{-- <li>
                            <a href="{{url('settings/1/edit')}}">Settings</a>
                        </li> --}}
                        <li class="active">
                            <strong>Edit Settings</strong>
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
                    <div class="alert {{ Session::get('flash_type')}}"> {{ Session::get('flash_message') }} </div>
                    @endif
                    <form method="post" action="{{URL('settings')}}/{{ $setting->id }}" enctype="multipart/form-data">


                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-8">

                                <div class="form-group required">
                                    <label for="name" class="col-2 col-form-label imp">Logo</label>
                                    <div class="col-6">
                                        <input class="form-control" type="file" name="logo" value="{{$setting->logo}}" id="logo">
                                    </div>

                                </div>
 
                                @if(!empty($setting->logo))
                                <div class="form-group" id="years_image_div">
                                    <label class="control-label" for="name"></label>
                                    <img src="{{ asset('images/logo/' . $setting->logo)}}" width="100px" height="100px">

                                </div>
                                @endif

                                <div class="form-group required">
                                    <label for="name" class="col-2 col-form-label">Invoice Prefix</label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="invoice_prefix" value="{{$setting->invoice_prefix}}" id="logo">
                                    </div>

                                </div>


                                {{-- <div class="form-group required">
                                    <label for="name" class="col-2 col-form-label imp">Access Cost</label>
                                    <div class="col-6">
                                      <div class="radio">
                                          <label><input type="radio" name="access_cost" value="0" @if($setting->access_cost == 0) checked @endif>Show</label>
                                        </div>
                                        <div class="radio">
                                          <label><input type="radio" name="access_cost" value="1" @if($setting->access_cost == 1) checked @endif>Hide</label>
                                        </div>
                                    </div>

                                </div> --}}

                                <div class="form-group required">
                                    <label for="name" class="col-2 col-form-label imp">Currency Rate</label>
                                    <div class="col-6">
                                        1 HKD TO RMB<input class="form-control" type="text" name="currency_rate" value="{{$setting->currency_rate}}">
                                    </div>

                                </div>
 
                                <div class="form-group">
                                    <label for="control-label" class="col-2 col-form-label imp">Header</label>
                                    <textarea class="ckeditor" id="editor" rows="10" cols="60" id="comment" name="header">{{ $setting->header }}</textarea>

                                </div>



                                <div class="form-group">
                                    <label for="control-label" class="col-2 col-form-label imp">Footer</label>
                                    <textarea class="ckeditor" id="editor" rows="10" cols="60" id="comment" name="footer">{{ $setting->footer }}</textarea>

                                </div>

 

                                <div class="form-group">

                                    <button type="submit" class="btn btn-lg btn-primary">
                                        Update
                                    </button>
                                </div>
                            </div>

                    </form> 
                </div>
            </section>
        </div>

        <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
        @endsection

