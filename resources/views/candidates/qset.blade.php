@extends('layouts.app') 

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Candidates <small>View and manage Candidates </small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="{{ route('candidate.index') }}">Candidates</a></li>
			<li class="active">Question set</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-default">
				
					<form role="form" 
            			action="{{ route('candidates.answer') }}"
                    	method="POST">
    				
    					<div class="box-header with-border">
    						<h3 class="box-title">Questions</h3>
    					</div>
    					<!-- /.box-header -->
    					
    					<div class="box-body">
            	
                    		{{ csrf_field() }}
                	
        					@if (isset($questions) && count($questions))
            					@foreach ($questions as $question)
            						<div class="row">
            							<div class="col-md-12">
            								<div class="form-group">
                								<label for="qid-{{ $question->id }}">{{ $question->question }}</label>
                        						@if ($question->type == 'Text')
                        							<textarea id="qid-{{ $question->id }}" 
                        								name="qid-{{ $question->id }}" 
                        								class="form-control"
                        								required></textarea>
                        						@elseif ($question->type == 'Select')
                        							<select id="qid-{{ $question->id }}" 
                        								name="qid-{{ $question->id }}" 
                        								class="form-control"
                        								required>
                            							@if (!empty($question->options))
                            								@foreach ($question->options as $option)
                            								<option value="{{ $option->id }}">{{ $option->label }}</option>
                            								@endforeach
                            							@endif
                        							</select>
                        						@else
                        							@if (!empty($question->options))
                        								@foreach ($question->options as $option)
                        									<div class="checkbox">
                                                                <label>
                                                                  	<input type="checkbox"
                                        								name="qid-{{ $question->id }}[]" 
                                        								value="{{ $option->id }}"> {{ $option->label }}
                                                                </label>
                                                            </div>
                        								@endforeach
                        							@endif
                        						@endif
                    						</div>
                						</div>
            						</div>
            					@endforeach
        					@endif
    					</div>
    					<!-- /.box-body -->
    					
    					<div class="box-footer clearfix">
    						
                        </div>
                    
                    </form>
				</div>
				<!-- /.box -->
			</div>
		</div>
	
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
