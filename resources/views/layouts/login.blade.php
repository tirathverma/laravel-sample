<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hao Fung International | Admin Login</title>
<meta name="description" content="This is test Description">
<meta name="keywords" content="Demo, Login">

<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

<!-- Fonts -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

<!-- Scripts -->
<script src="{{ asset('/js/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

<script>
 var siteUrl = '{{ url("/") }}';
 var token   = '{{csrf_token()}}';
</script>

</head>
<body class="admin-login-body">	
	@if ( Session::has('flash_message') )
		<div class="alert {{ Session::get('flash_type') }}">
			<div>{{ Session::get('flash_message') }}</div>
		</div>
	@endif	
	@yield('content') 
</div>

</body>
</html>
