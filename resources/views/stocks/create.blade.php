@extends('layouts.app')
@section('content')

<!-- START CONTENT -->
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Add Stock</h1><!-- PAGE HEADING TAG - END -->
                </div>

                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/dashboard')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                        <li>
                            <a href="{{url('/stocks')}}">Stock</a>
                        </li>
                        <li class="active">
                            <strong>Add Stock</strong>
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

                <form method="post" action="{{URL('stocks')}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                      <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-8">


                      <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-8">

                    
                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Name</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="name" placeholder="" value="{{old('name')}}">
                        </div>
                    </div>

                    <div class="form-group required">
                        <label for="name" class="col-2 col-form-label">Image</label>
                        <div class="col-6">
                            <input class="form-control" type="file" name="image" value="" id="image">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Vintage</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="vintage" placeholder="" value="{{old('vintage')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Size</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="size" placeholder="" value="{{old('size')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Quantity</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="qty" placeholder="" value="{{old('qty')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Owc</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="owc" placeholder="" value="{{old('owc')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Pound Cost</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="pound_cost" placeholder="" value="{{old('pound_cost')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Cost (HKD)</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="cost_hkd" placeholder="" value="{{old('cost_hkd')}}">
                        </div>
                    </div>


                     <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Selling (HKD)</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="selling_hkd" placeholder="" value="{{old('selling_hkd')}}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">VIP (HKD)</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="vip_hkd" placeholder="" value="{{old('vip_hkd')}}">
                        </div>
                    </div>
  
 
                    <div class="form-group">
                        <div class="col-6">
                            <button type="submit" class="btn btn-success">Add Stock</button>
                              <a href="{{url('stocks')}}" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                    </div>
                    </div>
                </form>
            </div>
            </section>
        </div>
        

@endsection
