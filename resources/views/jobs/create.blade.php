@extends('layouts.app') 

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Jobs <small>Post / Manage Job Requirements</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="{{ route('job.index') }}">Jobs</a></li>
			<li class="active">{{ $action }}</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- SELECT2 EXAMPLE -->
		<div class="box box-default">
			
			<form role="form" 
    			@if($action== 'Add New')
                	action="{{ route('job.store') }}" 
                @else
            		action="{{ route('job.update', ['id' => $job->id]) }}" 
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
    					<div class="col col-md-6">
    						
    						<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                                <label for="position">Position Name</label>
                                <input id="position" type="text" class="form-control"
                                    name="position"
                                    value="{{ empty(old('position', '')) ? (isset($job->position) ? $job->position : '') : old('position') }}"
                                    required autofocus> 
                                @if ($errors->has('position')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('position') }}</strong>
                            		</span> 
                        		@endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                                <label for="location">Location</label>
                                <input id="location" type="text" class="form-control" name="location"
                                    value="{{ empty(old('location', '')) ? (isset($job->location) ? $job->location : '') : old('location') }}" > 
                                @if ($errors->has('location')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('location') }}</strong>
                                    </span> 
                                @endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group{{ $errors->has('expiry_date') ? ' has-error' : '' }}">
                                <label for="expiry_date">Expiry Date</label>
                                <input id="expiry_date" 
                                	type="text" 
                                	class="form-control" 
                                	name="expiry_date"
                                    value="{{ empty(old('expiry_date', '')) ? (isset($job->expiry_date) ? $job->expiry_date : '') : old('expiry_date') }}" > 
                                @if ($errors->has('expiry_date')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('expiry_date') }}</strong>
                                    </span> 
                                @endif
                            </div>
    						<!-- /.form-group -->
    						
						</div>
						<!-- /.col -->
						
						<div class="col col-md-6">
    						
    						<div class="form-group{{ $errors->has('vacancies') ? ' has-error' : '' }}">
                                <label for="vacancies">Vacancies</label>
                                <input id="vacancies" 
                                	type="text" 
                                	class="form-control" 
                                	name="vacancies"
                                    value="{{ empty(old('vacancies', '')) ? (isset($job->vacancies) ? $job->vacancies : '') : old('vacancies') }}"
                                    required> 
                                @if ($errors->has('vacancies')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('vacancies') }}</strong>
                                    </span> 
                                @endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group{{ $errors->has('compensation') ? ' has-error' : '' }}">
                                <label for="compensation">Compensation <em style="color: #AAA;">(Yearly)</em></label>
                                <input id="compensation" type="text" 
                                	class="form-control" 
                                	name="compensation"
                                    value="{{ empty(old('compensation', '')) ? (isset($job->compensation) ? $job->compensation : '') : old('compensation') }}" 
                                    placeholder="Lacks per year"
                                    required> 
                                @if ($errors->has('compensation')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('compensation') }}</strong>
                                    </span> 
                                @endif
                            </div>
    						<!-- /.form-group -->
    						
    					</div>
    					<!-- /.col -->
    					
    				</div>
    				<!-- /.row -->
    				
    				<div class="row">
    					<div class="col col-md-12">
    					
    						<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description">Description</label>
                                <textarea class="textarea" 
                                	id="description"
                                	name="description"
                                	placeholder="Place some text here" 
                                	style="width: 100%; 
                                    height: 200px; 
                                    font-size: 14px; 
                                    line-height: 18px; 
                                    border: 1px solid #dddddd; 
                                    padding: 10px;"
                                    required>{{ empty(old('description', '')) ? (isset($job->description) ? $job->description : '') : old('description') }}</textarea>
                                @if ($errors->has('description')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('description') }}</strong>
                                    </span> 
                                @endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group{{ $errors->has('responsibilities') ? ' has-error' : '' }}">
                                <label for="responsibilities">Responsibilities</label>
                                <textarea class="textarea" 
                                	id="responsibilities"
                                	name="responsibilities"
                                	placeholder="Place some text here" 
                                	style="width: 100%; 
                                    height: 200px; 
                                    font-size: 14px; 
                                    line-height: 18px; 
                                    border: 1px solid #dddddd; 
                                    padding: 10px;"
                                    required>{{ empty(old('responsibilities', '')) ? (isset($job->responsibilities) ? $job->responsibilities : '') : old('responsibilities') }}</textarea>
                                @if ($errors->has('responsibilities')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('responsibilities') }}</strong>
                                    </span> 
                                @endif
                            </div>
    						<!-- /.form-group -->
    						
    						<!-- checkbox -->
							<div class="form-group">
								<label style="clear: both;">Question Groups</label>
								<div class="row">
								
									@php
										$jobQGroups = array();
									@endphp
									
									@if (isset($job->qgroups))
										@php
											$jobQGroups = explode(',', $job->qgroups);
										@endphp
									@endif
										
									@if (isset($qgroups))
										@foreach ($qgroups as $qg)
											<div class="checkbox col col-md-4">
            									<label><input type="checkbox" name="qgroups[]" value="{{ $qg->id }}" {{ in_array($qg->id, $jobQGroups) ? 'checked' : ''}}> {{ $qg->group_name }}</label>
            								</div>
										@endforeach
									@endif
									
								</div>
							</div>

						</div>
    				</div>
    				
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
</div>
<!-- /.content-wrapper -->

@endsection