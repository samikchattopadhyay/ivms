@extends('layouts.app') 

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Administrator</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('user.index') }}">Administrator</a></li>
		<li class="active">{{ $action }}</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">

	<!-- SELECT2 EXAMPLE -->
	<div class="box box-default">
		
		<form role="form" 
		@if($action== 'Add New')
        	action="{{ route('user.store') }}" 
        @else
    		action="{{ route('user.update', ['id' => $user->id]) }}" 
		@endif
    	method="POST">
    	
    		@if($action == 'Add New') 
    			{{ csrf_field() }} 
			@else 
				<input type="hidden" name="_method" value="PATCH"> 
				<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
        	@endif
		
			<div class="box-header with-border">
				<h3 class="box-title">{{ $action }}</h3>
			</div>
			<!-- /.box-header -->
			
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control"
                                name="name"
                                value="{{ empty(old('name', '')) ? (isset($user->name) ? $user->name : '') : old('name') }}"
                                required autofocus> 
                            @if ($errors->has('name')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('name') }}</strong>
                        		</span> 
                    		@endif
                        </div>
						
						<!-- /.form-group -->
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">E-Mail</label>
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ empty(old('email', '')) ? (isset($user->email) ? $user->email : '') : old('email') }}"
                                {{ $action == 'Edit' ? 'disabled' : ''}}
                                required> 
                            @if ($errors->has('email')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('email') }}</strong>
                                </span> 
                            @endif
                        </div>
						<!-- /.form-group -->
						
						<!-- /.form-group -->
						<div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                            <label for="mobile_no">Mobile</label>
                            <input id="mobile_no" type="text" class="form-control" name="mobile_no"
                                value="{{ empty(old('mobile_no', '')) ? (isset($user->mobile_no) ? $user->mobile_no : '') : old('mobile_no') }}"
                                required> 
                            @if ($errors->has('mobile_no')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('mobile_no') }}</strong>
                                </span> 
                            @endif
                        </div>
						<!-- /.form-group -->
						
					</div>
					<!-- /.col -->
					<div class="col-md-6">
						
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control"
                                name="password" required> 
                            @if ($errors->has('password')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('password') }}</strong>
                            	</span> 
                        	@endif
                        </div>
						<!-- /.form-group -->
						
						<div class="form-group">
                            <label for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" 
                            	type="password" class="form-control"
                                name="password_confirmation" required>
                        </div>
						<!-- /.form-group -->
						
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>
			<!-- /.box-body -->
			
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">{{ $action == 'Add New' ? 'Create' : 'Update' }}</button>
                <!-- /.form-group -->
            </div>
            <!-- /.box-footer -->
        
        </form>
	</div>
	<!-- /.box -->

</section>
<!-- /.content -->

@endsection