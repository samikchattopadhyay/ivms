@extends('layouts.app') 

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Candidates <small>View and manage Candidates </small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Candidates </li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">List All {{ isset($job) && !empty($job) ? ' - ' . $job->position : '' }} ({{ $candidates->total() }})</h3>
						<div class="box-tools">
							
							<form action="">
    							<div class="input-group input-group-sm" style="width: 250px;">
    								{{ csrf_field() }}
    								<input type="text" 
    									name="s" 
    									class="form-control pull-right" 
    									placeholder="Search">
    								<div class="input-group-btn">
    									<button type="submit" class="btn btn-default">
    										<i class="fa fa-search"></i>
    									</button>
    									<a role="button" href="/candidate/create" class="btn btn-warning">
    										<i class="fa fa-plus"></i>
    										Add New
    									</a>
    								</div>
    							</div>
							</form>
							
						</div>
					</div>
					<!-- /.box-header -->
					
					<div class="box-body table-responsive no-padding">
					
						<table class="table table-hover">
							<tr>
								<th>Name</th>
								<th>Source</th>
								<th width="100">Notice Period</th>
								<th>Job Position</th>
								<th>Location</th>
								<th>Match %</th>
								<th width="170">Actions</th>
							</tr>
							
							@if (isset($candidates) && count($candidates))
							
								@foreach ($candidates as $candidate)
									
									<tr>
        								<td>{{ $candidate->name }}</td>
        								<td>{{ $candidate->source }}</td>
        								<td>{{ $candidate->notice_period }} days</td>
        								<td>{{ $candidate->job_position }}</td>
        								<td>{{ $candidate->location }}</td>
        								<td>
        									@php
        										if ($candidate->cv_match_percent < 30)
        											$color = 'danger';
        										elseif ($candidate->cv_match_percent >= 30 && $candidate->cv_match_percent < 60)
        											$color = 'warning';
        										elseif ($candidate->cv_match_percent >= 60 && $candidate->cv_match_percent < 85)
        											$color = 'success';
        										else
        											$color = 'default';
        									@endphp
            								<div class="progress" title="{{ $candidate->cv_match_percent }}%">
                                                <div class="progress-bar progress-bar-{{ $color }}" 
                                                	role="progressbar" 
                                                	aria-valuenow="{{ $candidate->cv_match_percent }}" 
                                                	aria-valuemin="0" 
                                                	aria-valuemax="100" 
                                                	style="width: {{ $candidate->cv_match_percent }}%">
                                                    <span class="sr-only">Resume/CV matches {{ $candidate->cv_match_percent }}% of the job description</span>
                                                </div>
                                            </div>
        								
        								</td>
        								<td>
        									<form method="POST"
        										action="{{ route('candidate.destroy', ['id' => $candidate->id]) }}" 
        										onsubmit="return confirm('Are you sure?')">
        									
    											<a href="javascript: void(0)" 
            										role="button" 
            										class="btn btn-default btn-xs preview"
            										data-index="{{ $candidate->id }}"
            										data-toggle="modal" 
            										data-target="#modal-preview"
            										title="Preview">
            										<i class="fa fa-eye"></i>
            									</a>
            									
        										<a href="javascript: void(0)" 
            										role="button" 
            										class="btn btn-default btn-xs comment"
            										data-index="{{ $candidate->id }}"
            										data-toggle="modal" 
            										data-target="#modal-comment"
            										title="Comment / Note">
            										<i class="fa fa-comment"></i>
            									</a>
            									
        										<a href="{{ route('candidates.qset', ['id' => $candidate->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
            										title="Question Set">
            										<i class="fa fa-question"></i>
            									</a>
            									
            									<a href="{{ route('candidates.recalculate', ['id' => $candidate->id]) }}" 
            										role="button" 
            										style="color: #000;"
            										class="btn btn-default btn-xs"
            										title="Recalculate match %">
            										<i class="fa fa-calculator"></i>
            									</a>
            									
            									<a href="{{ route('candidate.edit', ['id' => $candidate->id]) }}" 
            										role="button" 
            										style="color: #000;"
            										class="btn btn-default btn-xs"
            										title="Edit">
            										<i class="fa fa-edit"></i>
            									</a>
        									
        										<input type="hidden" name="_method" value="DELETE"> 
												<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
												<button type="submit" 
            										class="btn btn-danger btn-xs"
            										style="color: #000;"
            										title="Delete">
            										<i class="fa fa-trash"></i>
            									</button>
        									</form>
        								</td>
        							</tr>
        							
								@endforeach
							
							@else 
							
								<tr>
    								<td colspan="20" style="text-align: center;">No record(s) found</td>
    							</tr>
							
							@endif
							
						</table>
					</div>
					<!-- /.box-body -->
					
					<div class="box-footer clearfix">
						{{ $candidates->links('vendor.pagination.bootstrap-4') }}
                    </div>
                    
                    
				</div>
				<!-- /.box -->
			</div>
		</div>
	
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modal-comment">
	<form id="comment-form" action="/candidates/comment">
		{{ csrf_field() }} 
		<input type="hidden" name="candidate" id="candidate-hid" value="" />
    	<div class="modal-dialog">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal"
    					aria-label="Close">
    					<span aria-hidden="true">&times;</span>
    				</button>
    				<h4 class="modal-title">Comments</h4>
    			</div>
    			<div class="modal-body">
    				<div class="row">
    					<div class="col-md-12" id="comments-list">
    						
    					</div>
    				</div>
    				<div class="row">
    					<div class="col-md-12">
    						<div class="form-group">
                              	<textarea class="form-control" 
                              	id="comment-text" 
                              	name="comment"
                              	placeholder="Enter your comment here"></textarea>
                            </div>
    					</div>
    				</div>
    			</div>
    			<div class="modal-footer">
    				<button type="submit" class="btn btn-primary">Comment</button>
    			</div>
    		</div>
    		<!-- /.modal-content -->
    	</div>
    	<!-- /.modal-dialog -->
	</form>
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-preview">
	<div class="modal-dialog" style="width: 70%; min-width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="col-md-12" id="preview-area"></div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
$(document).ready(function() {

	$('.comment').on('click', function(e) {
		e.preventDefault();
		var cId = $(this).data('index');
		$('#candidate-hid').val(cId);
		$.get('/candidates/comments/' + cId, function(comments) {
			$('#comments-list').html(comments);
		});
	});

	$('#comment-form').on('submit', function(e) {
		e.preventDefault();
		formSubmit ('comment-form', function(args) {
			if (args.success) {
				$('#comment-text').val('');
				$('#modal-comment').modal('toggle');
			}
		});
	});

	$('.preview').on('click', function(e) {
		e.preventDefault();
		var cId = $(this).data('index');
		$.get('/candidates/preview/' + cId, function(comments) {
			$('#preview-area').html(comments);
		});
	});
	
});
</script>

@endsection
