@extends('layouts.app') 

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Candidates <small>View and manage Candidates </small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('candidate.index') }}">Candidates</a></li>
		<li class="active">{{ $action }}</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">

	<!-- SELECT2 EXAMPLE -->
	<div class="box box-default">
	
		<form role="form" 
		@if($action== 'Add New')
        	action="{{ route('candidate.store') }}" 
        @else
    		action="{{ route('candidate.update', ['id' => $candidate->id]) }}" 
		@endif
    	method="POST"
    	enctype="multipart/form-data">
    	
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
			
				@if (session('status'))
					@php
						$status = session('status')
					@endphp
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-{{ $status['type'] }}">
                                {{ $status['msg'] }}
                            </div>
						</div>
                    </div>
                @endif
			
				<div class="row">
					<div class="col-md-6">
						
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control"
                                name="name"
                                value="{{ empty(old('name', '')) ? (isset($candidate->name) ? $candidate->name : '') : old('name') }}"
                                required autofocus> 
                            @if ($errors->has('name')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('name') }}</strong>
                        		</span> 
                    		@endif
                        </div>
                        
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control"
                                name="email"
                                value="{{ empty(old('email', '')) ? (isset($candidate->email) ? $candidate->email : '') : old('email') }}"
                                {{ $action == 'Edit' ? 'disabled' : ''}}
                                required> 
                            @if ($errors->has('email')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('email') }}</strong>
                        		</span> 
                    		@endif
                        </div>
						
						<!-- /.form-group -->
						<div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location">Location</label>
                            <input id="location" 
                            	type="text" 
                            	class="form-control" 
                            	name="location"
                                value="{{ empty(old('location', '')) ? (isset($candidate->location) ? $candidate->location : '') : old('location') }}"> 
                            @if ($errors->has('location')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('location') }}</strong>
                                </span> 
                            @endif
                        </div>
						<!-- /.form-group -->
						
						<div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
                            <label for="source">Source <em style="color: #AAA;">(Who gave this CV)</em></label>
                            <input id="source" 
                            	type="text" 
                            	class="form-control" 
                            	name="source"
                                value="{{ empty(old('source', '')) ? (isset($candidate->source) ? $candidate->source : '') : old('source') }}"> 
                            @if ($errors->has('source')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('source') }}</strong>
                                </span> 
                            @endif
                        </div>
						<!-- /.form-group -->
						
					</div>
					<!-- /.col -->
					<div class="col-md-6">
						
						<div class="form-group{{ $errors->has('job_id') ? ' has-error' : '' }}">
                            <label for="password">Job ID</label>
                            @php
                            	$selectedJob = empty(old('job_id', '')) ? (isset($candidate->job_id) ? $candidate->job_id : '') : old('job_id');
                            @endphp
                            <select id="job_id" class="form-control" name="job_id" required> 
                            	<option value=""></option>
                            	@foreach ($jobs as $job)
                            		<option value="{{ $job->id }}" {{ $job->id == $selectedJob ? 'selected' : '' }}>{{ $job->position }}</option>
                            	@endforeach
                            </select>
                            @if ($errors->has('job_id')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('job_id') }}</strong>
                            	</span> 
                        	@endif
                        </div>
						<!-- /.form-group -->
						
						<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <label for="mobile">Mobile <em style="color: #AAA;">(Comma separate for multiple)</em></label>
                            <input id="mobile" 
                            	type="text" class="form-control" 
                            	name="mobile"
                            	required
                                value="{{ empty(old('mobile', '')) ? (isset($candidate->mobile) ? $candidate->mobile : '') : old('mobile') }}"> 
                            @if ($errors->has('mobile')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('mobile') }}</strong>
                                </span> 
                            @endif
                        </div>
						<!-- /.form-group -->
						
						<div class="form-group{{ $errors->has('notice_period') ? ' has-error' : '' }}">
                            <label for="notice_period">Notice Period <em style="color: #AAA;">(in days)</em></label>
                            <input id="notice_period" 
                            	type="text" 
                            	class="form-control" 
                            	name="notice_period"
                                value="{{ empty(old('notice_period', '')) ? (isset($candidate->notice_period) ? $candidate->notice_period : '') : old('notice_period') }}"> 
                            @if ($errors->has('notice_period')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('notice_period') }}</strong>
                                </span> 
                            @endif
                        </div>
						<!-- /.form-group -->
						
						<div class="form-group{{ $errors->has('cv_file') ? ' has-error' : '' }}">
                            <label for="cv_file">Resume / CV <em style="color: #AAA;">(docx, pdf file only)</em></label>
                            <input id="cv_file" 
                            	type="file" 
                            	name="cv_file" 
                            	{{ $action == 'Edit' ? '' : 'required'}}> 
                            @if ($errors->has('cv_file')) 
                            	<span class="help-block"> 
                            		<strong>{{ $errors->first('cv_file') }}</strong>
                                </span> 
                            @endif
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