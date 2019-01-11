@extends('layouts.app-auth')

@section('content')

<div class="login-box-body">

	<p class="login-box-msg">{{ __('Sign in to start your session') }}</p>

	<form action="{{ route('login') }}" method="post">
	
		@csrf
		
		<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
		
			<input type="email" 
				id="email" 
				name="email"
				class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
				value="{{ old('email') }}"
				placeholder="{{ __('Email') }}"
				required autofocus> 
				
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				
			@if ($errors->has('email'))
                <span class="help-block" role="alert">
                	<i class="fa fa-times-circle-o"></i> 
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
			
		</div>
		
		<div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
		
			<input type="password" 
				id="password"
				name="password"
				class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
				placeholder="{{ __('Password') }}"
				required>
			
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			
			@if ($errors->has('password'))
                <span class="help-block" role="alert">
                	<i class="fa fa-times-circle-o"></i> 
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            
		</div>
		
		<div class="row">
			<div class="col-xs-8">
				<div class="checkbox icheck">
					<label> <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }} </label>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-xs-4">
				<button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Sign In') }}</button>
			</div>
			<!-- /.col -->
		</div>
	</form>
	<!-- /.social-auth-links -->

	@if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
    @endif
    
</div>
<!-- /.login-box-body -->
		
@endsection