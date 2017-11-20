@extends('layouts.app')
@section('content')
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class='col-xs-12'>
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Edit Stocks</h1><!-- PAGE HEADING TAG - END -->                                         
                </div>

                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/invoices')}}"><i class="fa fa-home"></i>Home</a>
                        </li>
                        <li>
                            <a href="{{url('stocks')}}">Stocks</a>
                        </li>
                        <li class="active">
                            <strong>Edit Stocks</strong>
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
                <form method="post" action="{{URL('stocks')}}/{{ $stock->id }}" enctype="multipart/form-data">


                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                    <input type="hidden" name="_method" value="PUT">

                     <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-8">

                     <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Name</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="name" placeholder="" value="{{$stock->name}}">
                        </div>
                    </div>

                    <div class="form-group required">
                        <label for="name" class="col-2 col-form-label imp">Image</label>
                        <div class="col-6">
                            <input class="form-control" type="file" name="image" value="{{$stock->image}}" id="image">
                        </div>

                    </div> 

                    @if(!empty($stock->image))
                    <div class="form-group" id="years_image_div">
                        <label class="control-label" for="name"></label>
                        <img src="{{ asset('images/stocks/' . $stock->image)}}" width="100px" height="100px">
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Vintage</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="vintage" placeholder="" value="{{$stock->vintage}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Size</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="size" placeholder="" value="{{$stock->size}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Quantity</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="qty" placeholder="" value="{{$stock->qty}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Owc</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="owc" placeholder="" value="{{$stock->owc}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label">Pound Cost</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="pound_cost" placeholder="" value="{{$stock->pound_cost}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Cost (HKD)</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="cost_hkd" placeholder="" value="{{$stock->cost_hkd}}">
                        </div>
                    </div>


                     <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">Selling (HKD)</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="selling_hkd" placeholder="" value="{{$stock->selling_hkd }}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="name" class="col-2 col-form-label imp">VIP (HKD)</label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="vip_hkd" placeholder="" value="{{$stock->vip_hkd}}">
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <div class="col-6">
                            <button type="submit" class="btn btn-success">Update</button>
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
