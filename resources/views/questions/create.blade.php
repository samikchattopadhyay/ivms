@extends('layouts.app') 

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Questions ( {{ $group->group_name }} ) <small>Add new questions in this groups</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="{{ route('question.index', [ 'gid' => $group->id ]) }}">Questions</a></li>
			<li class="active">{{ $action }}</li>
		</ol>
	</section>
	
	<!-- Main content -->
	<section class="content">
	
		<div class="box box-default">
			
			<form role="form" 
			
			@if($action== 'Add New')
            	action="{{ route('question.store') }}" 
            @else
        		action="{{ route('question.update', ['id' => $question->id]) }}" 
    		@endif
			
			method="POST">
        	
        		@if($action == 'Add New') 
        			{{ csrf_field() }} 
    			@else 
    				<input type="hidden" name="_method" value="PATCH"> 
    				<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
            	@endif
        		
        		<input type="hidden" name="gid" value="{{ $group->id }}" />
			
    			<div class="box-header with-border">
    				<h3 class="box-title">{{ $action }}</h3>
    			</div>
    			<!-- /.box-header -->
    			
    			<div class="box-body">
    				<div class="row">
    					<div class="col-md-12">
    						
    						<div class="form-group{{ $errors->has('question') ? ' has-error' : '' }}">
                                <label for="question">Question</label>
                                <textarea id="question" 
                                	class="form-control" 
                                	name="question"
                                    required 
                                    autofocus>{{ empty(old('question', '')) ? (isset($question->question) ? $question->question : '') : old('question') }}</textarea>
                                @if ($errors->has('name')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('question') }}</strong>
                            		</span> 
                        		@endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
                                <label for="keywords">Keywords <em style="color: #AAA;">( Comma separated values )</em></label>
                                <input type="text" 
                                	id="keywords" 
                                	class="form-control"
                                    name="keywords"
                                    value="{{ empty(old('keywords', '')) ? (isset($question->keywords) ? $question->keywords : '') : old('keywords') }}"
                                    required>
                                @if ($errors->has('name')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('keywords') }}</strong>
                            		</span> 
                        		@endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label for="type">Type</label>
                                
                                @php
                                	$types = ['Text', 'Select', 'Check'];
                                	$type = empty(old('type', '')) ? (isset($question->type) ? $question->type : '') : old('type')
                                @endphp
                                <select id="type" name="type" class="form-control" required>
                                	<option value="select"></option>
                                	@foreach ($types as $t)
                                	<option value="{{ $t }}" {{ ($type == $t) ? 'selected' : '' }}>{{ $t }}</option>
                                	@endforeach
                                </select>
                                
                                @if ($errors->has('name')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('type') }}</strong>
                            		</span> 
                        		@endif
                            </div>
    						<!-- /.form-group -->
    						
    						<div class="form-group">
                                <label for="option">Options  <em style="color: #AAA;">( Only for select and check )</em></label>
                                
                                <div id="newoption" 
                            		class="input-group">
                                    <input type="text" 
                                		class="form-control" 
                                		name="newoptions[]" 
                                		placeholder="Type new option/label">
                            		<div class="input-group-btn">
                                    	<button type="button" class="btn btn-primary" id="addopt">&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;</button>
                                    </div>
                                    <!-- /btn-group -->
                                </div>
                                
                                <div id="newoptions">
                                
                                    @if (isset($question->options) && count($question->options))
                                    	@foreach ($question->options as $opt)
                                            <div class="input-group" style="margin-top: 5px;">
                                                <input type="text" 
                                        			class="form-control"
                                            		name="options[{{ $opt->id }}]" 
                                            		value="{{ $opt->label }}" 
                                            		required>
                                        		<div class="input-group-btn">
                                                	<button type="button" 
                                                		data-index="{{ $opt->id }}"
                                                		class="btn btn-danger delopt">Delete</button>
                                                </div>
                                                <!-- /btn-group -->
                                            </div>
                                    	@endforeach
                                    @endif
                                    
                                </div>
                                 
                            </div>
    						<!-- /.form-group -->
    						
    						<script type="text/javascript">
							$(document).ready(function() {

								$('#addopt').on('click', function() {
									var newObj = $('#newoption').clone();
									$('input[type="text"]', newObj).val('')
										.attr('placeholder', '');
									$('button', newObj).text('Delete')
										.removeClass('btn-primary')
										.addClass('btn-danger deltmp');
									newObj.removeAttr('id')
										.val('')
										.css({'margin-top': '5px'})
										.prependTo('#newoptions');
								});

								$('.delopt').on('click', function() {
									var ans = confirm('Are you sure?');
									if (ans) {

										var optId = $(this).data('index');
										$.post("/options/destroy", {id: optId}, function(result) {
											console.log(result);
										});

									}
								});

								$('body').on('click', '.deltmp', function() {
									var value = $(this).closest('div.input-group').find('input[type="text"]').val();
									if ($.trim(value) == '') {
										$(this).closest('div.input-group').remove();
									} else {
										var ans = confirm('Are you sure?');
										if (ans) {
											$(this).closest('div.input-group').remove();
										}
									}
								});

							});

    						</script>
    						
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
</div>
<!-- /.content-wrapper -->

@endsection
