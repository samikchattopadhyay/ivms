@extends('layouts.app-auth')

@section('content')

<div class="login-box-body">

	<p class="login-box-msg">{{ __('Reset Password') }}</p>
	
	@if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

	<form action="{{ route('password.email') }}" method="post">
	
		@csrf
		
		<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
		
			<input type="email" 
				id="email" 
				name="email"
				class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
				value="{{ old('email') }}"
				placeholder="{{ __('E-Mail Address') }}"
				required autofocus> 
				
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				
			@if ($errors->has('email'))
                <span class="help-block" role="alert">
                	<i class="fa fa-times-circle-o"></i> 
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
			
		</div>
		
		<div class="row">
			<div class="col-xs-2"></div>
			<!-- /.col -->
			<div class="col-xs-8">
				<button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Send Password Reset Link') }}</button>
			</div>
			<div class="col-xs-2"></div>
			<!-- /.col -->
		</div>
	</form>
	<!-- /.social-auth-links -->
    
</div>
<!-- /.login-box-body -->

@endsection
