@extends('layouts.login')

@section('content')

<div class="container-fluid">
	<div class="row margin-top-bottom-4">
		<div class="admin-forms">
			<div class="panel-heading text-center">	
				<img src="{{asset('images/logo-sm.png')}}" width="170px" class=" margin-bottom-3" alt="logo" />
				<span class="splash-description">Please enter your user information.</span>
			</div>
			
				<div class="panel-body login-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger margin-top-0">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<form class="" role="form" method="POST" action="{{ url('/dologin') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="col-md-12 col-sm-12 col-xs-12 padding-0 login-form-box">
							<div class="form-group">
								<input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
							</div>

							<div class="form-group">
								<input type="password" placeholder ="Password" class="form-control" name="password" id="password">
							</div>
							
							<div class="form-group col-md-12 pull-left padding-0 login-tools">
						      	<div class="checkbox checkbox-inline fancy-input">
	                        		<input type="checkbox" id="inlineCheckbox1" value="option1" name="remember">
	                        		<label for="inlineCheckbox1"> Remember Me </label>
	                    		</div>
                    								
							</div>
						</div>
							<div class="form-group login-submit">
								<button type="submit" class="btn btn-block btn-lightpurple">Sign me in</button>
							</div>
						</div>
					</form>
            </div>
		</div>
	</div>
</div>

<!--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/dologin') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

-->
@endsection
