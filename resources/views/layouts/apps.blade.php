<!DOCTYPE html>
<html class=" ">
    <head>
        <!-- 
         * @Package: Complete Admin - Responsive Theme
         * @Subpackage: Bootstrap
         * @Version: 2.2
         * This file is part of Complete Admin Theme.
        -->
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Hao Fung International : @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        
        <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" type="image/x-icon" />    <!-- Favicon -->
        <link rel="apple-touch-icon-precomposed" href="{{asset('/images/apple-touch-icon-57-precomposed.png')}}">   <!-- For iPhone -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('/images/apple-touch-icon-114-precomposed.png')}}">    <!-- For iPhone 4 Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('/images/apple-touch-icon-72-precomposed.png')}}">    <!-- For iPad -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('/images/apple-touch-icon-144-precomposed.png')}}">    <!-- For iPad Retina display -->




        <!-- CORE CSS FRAMEWORK - START -->
        <link href="{{asset('/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/css/all.css')}}" rel="stylesheet" type="text/css"/>   
        <link href="{{asset('/css/style.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/css/responsive.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/css/animate.min.css')}}" rel="stylesheet" type="text/css"/>   
        
        
        <!-- CORE CSS TEMPLATE - END -->
        <script src="{{ asset('/js/jquery-1.11.2.min.js') }}"></script>
        <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/js/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('/js/icheck.min.js') }}"></script>
        
        <script src="{{ asset('/js/scripts.js') }}"></script>   
        <script> var siteUrl = '{{ url("/") }}';</script>

        <style type="text/css">
             .form-group .col-form-label.imp:after {
                content:"  *";color:red;
        </style>
            
    </head>
    <!-- END HEAD -->
 
    
<!-- BEGIN BODY -->
<body class=" ">

<!-- Include admin header-->
{{-- @include('partials.header') --}}

<!-- Include left navigation-->
{{-- @include('partials.left_navigation') --}}

<!-- START CONTAINER -->
  
    @yield('content')
    
 

</div>

</body>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>

<script>
        $(document).ready(function () {

            $('#confirm-delete').on('show.bs.modal', function (e) {
                var form = $(e.relatedTarget).data('href');
                $('#danger').click(function () {
                    $('#' + form).submit();
                });
            })

        });
    </script>
</html>



