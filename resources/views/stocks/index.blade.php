@extends('layouts.app')
@section('title', 'Stocks List')
@section('content')
 
<section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
        <div class="col-xs-12 wrapper-box">
            <div class="page-title">
                <div class="pull-left">
                    <!-- PAGE HEADING TAG - START -->
                    <h1 class="title">Stocks</h1><!-- PAGE HEADING TAG - END -->                            
                </div>
                <div class="pull-right hidden-xs">
                    <ol class="breadcrumb">
                        <li>                            
                            <a  href="{{url('/dashboard')}}">
                                <i class="fa fa-home"></i>Home</a>
                        </li>
                        {{-- <li>
                            <a  href="{{url('/stocks')}}">
                                Stocks</a>
                        </li> --}}
                        <li class="active">
                            <strong>Stocks Listing</strong>
                        </li>
                        <li style="display:none;"> 
                        </li>
                    </ol>
                </div>

            </div>
             <div class="col-md-4 col-sm-12 search-box">
                        <form name="search" action="{{URL::to('/stocks')}}" method="get" class="form-inline" style="">
                    <input type="text" name="q" placeholder="search..." class="form-control search-bar" value="{{Request::input('q')}}">
                            <input type="submit" value="Search" class="btn btn-primary search-btn">
                        </form>
                    </div>
            <div class="add-btn pull-right" >

                <a  href="{{url('stocks/create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add Stock </a>
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
                 
                    <div class=" pull-left" style="margin-top:20px; padding-left:20px;">
                        @if(count($stocks))
                            <a href="{{URL('stocks/export')}}"><button type="button" class="btn btn-primary">Export </button></a>
 
                        @endif
                            <a href="{{URL('stocks/sample-export')}}"><button type="button" class="btn btn-success">Sample Excel</button></a>
                            
                             <a href="{{URL('stocks/import-form')}}"><button type="button" class="btn btn-info">Import </button></a>
                        
                    </div>

                    

                    <div class="actions panel_actions pull-right">
                     {{--    <a class="box_toggle fa fa-chevron-down"></a>
                        <a class="box_close fa fa-times"></a> --}}
                    </div>
                </header>
                <div class="content-body col-md-12 table-responsive">    
                        <table id="example" class="display table table-hover table-condensed">   <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Vintage</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Owc</th>
                                <th>Pound Cost</th>
                                <th>Cost (HKD)</th>
                                <th>Selling (HKD)</th>
                                <th>VIP (HKD)</th>
                                 
                                <th class="th-act">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = (Request::input('page')) ? (Request::input('page') - 1) * $stocks->perPage() + 1 : 1;
                            ?>
                            @if(count($stocks))
                            @foreach($stocks as $value )
                            <tr>
                                <td>{{ $i}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td>  {{ number_format($value->vintage,2, '.', '')}}</td>
                                <td>{{ucfirst($value->size)}}</td>
                                <td>{{ ($value->qty)}}</td>
                                <td>{{ ($value->owc)}}</td>
                                <td>{{ number_format($value->pound_cost,2, '.', '')}}</td>
                                <td>{{ number_format($value->cost_hkd,2, '.', '')}}</td>
                                <td>{{ number_format($value->selling_hkd,2, '.', '')}}</td>
                                <td>{{ number_format($value->vip_hkd,2, '.', '')}}</td>
                                <td>
                                    <form method="POST" action="{{URL::to('stocks')}}/{{$value->id}}" id="{{ $value->id }}" accept-charset="UTF-8">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
 
                                        <a class="btn btn-primary" href="{{URL::to('/stocks')}}/{{$value->id}}/edit" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        
                                        <a class="btn btn-info" href="{{URL::to('/stocks/history')}}/{{$value->id}}" role="button"><i class="fa fa-history" aria-hidden="true"></i></a>

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
                                <td colspan="4">No stocks found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                                {{ $stocks->links() }}
                    </div>
             
              

            </section>

        </div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete stock</h4>
            </div>
            <div class="modal-body"> Are you sure want to delete? </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="danger">Delete</a> </div>
        </div>
    </div>
</div>

@endsection
