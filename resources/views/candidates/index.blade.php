@extends('layouts.app') 

@section('content')

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
						<form id="srch-frm" action="">
							<div class="input-group input-group-sm" style="width: 450px;">
								{{ csrf_field() }}
								
								<input type="text" 
									name="s" 
									class="form-control" 
									placeholder="Search">
									
								<input type="hidden"
									name="t" 
									id="ht" />
									
								<div class="input-group-btn">
								
									<button type="button" class="btn btn-default" id="f-candistat">Filter Status</button>
        							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        								<span class="caret"></span> 
        								<span class="sr-only"> Status</span>
        							</button>
        							<ul id="f-status-group" class="dropdown-menu" role="menu">
        								<li><a href="REJ">Rejected</a></li>
        								<li><a href="SLT">Shortlisted</a></li>
        								<li><a href="QNA">Q & A Pending</a></li>
        								<li><a href="INV">Interview</a></li>
        								<li class="divider"></li>
        								<li><a href="WTG">Waiting</a></li>
        								<li><a href="SEL">Selected</a></li>
        								<li><a href="NEG">Negotiate</a></li>
        								<li class="divider"></li>
        								<li><a href="CNF">Confirmed</a></li>
        								<li><a href="JND">Joined</a></li>
        							</ul>
        							
        							<button type="submit" class="btn btn-default">
										<i class="fa fa-search"></i>
									</button>
        							
        							<a role="button" href="/candidate/create" class="btn btn-warning">
										<i class="fa fa-plus"></i> Add New
									</a>
								</div>
							</div>
						</form>
						
					</div>
				</div>
				<!-- /.box-header -->
				
				<div class="box-body table-responsive no-padding">
				
					@if(session()->has('message'))
                        <div style="margin: 5px;" class="alert alert-{{ session()->get('message')['type'] }}">
                            {{ session()->get('message')['text'] }}
                        </div>
                    @endif
				
					<table class="table table-hover">
						<tr>
							<th width="100">Status</th>
							<th>Name</th>
							<th>Job Position</th>
							<th>Location</th>
							<th>Match %</th>
							<th width="150">Actions</th>
						</tr>
						
						@if (isset($candidates) && count($candidates))
						
							@foreach ($candidates as $candidate)
								
								<tr>
									<td>
        								@if ($candidate->status == 'NEW')
        								<span class="label label-default" style="display:block; width: 100px; text-align: center;">{{ $statusList[$candidate->status] }}</span>
        								@elseif (in_array($candidate->status, ['REJ']))
        								<span class="label label-danger" style="display:block; width: 100px; text-align: center;">{{ $statusList[$candidate->status] }}</span>
        								@elseif (in_array($candidate->status, ['QNA']))
        								<span class="label label-warning" style="display:block; width: 100px; text-align: center;">{{ $statusList[$candidate->status] }}</span>
        								@elseif (in_array($candidate->status, ['SLT','INV','WTG','SEL']))
        								<span class="label label-success" style="display:block; width: 100px; text-align: center;">{{ $statusList[$candidate->status] }}</span>
        								@elseif (in_array($candidate->status, ['NEG','CNF','JND']))
        								<span class="label label-primary" style="display:block; width: 100px; text-align: center;">{{ $statusList[$candidate->status] }}</span>
        								@else
        								<span class="label label-" style="display:block; width: 100px; text-align: center;">{{ $statusList[$candidate->status] }}</span>
        								@endif
    								</td>
    								<td>{{ $candidate->name }}</td>
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
    									<style>
    									a.faded {
    									   color: #BBB !important;
    									}
    									</style>
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
        										class="btn btn-default btn-xs comment {{ ($candidate->comments) > 0 ? "" : "faded" }}"
        										data-index="{{ $candidate->id }}"
        										data-toggle="modal" 
        										data-target="#modal-comment"
        										title="Comment / Note">
        										<i class="fa fa-comment"></i>
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

<div class="modal fade" id="modal-comment">
	<form id="comment-form" action="/candidates/comment">
		{{ csrf_field() }} 
		<input type="hidden" name="candidate" id="candidate-hid" value="" />
    	<div class="modal-dialog">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                              	placeholder="Enter your comment here"
                              	required></textarea>
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

<style>
.lds-ring {
  display: inline-block;
  position: relative;
  width: 100px;
  height: 100px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 100px;
  height: 100px;
  margin: 6px;
  border: 6px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #AAA transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>

<div class="modal fade" id="modal-preview">
	<div class="modal-dialog" style="width: 70%; min-width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<a id="ex-preview"
					href="javascript: void(0)" 
					target="_blank" 
					role="button" 
					title="Open independent page" 
					style="float: left;">
					<i class="fa fa-external-link">&nbsp;</i>
				</a>
				<h4 class="modal-title col-md-4">Candidate Preview</h4>
			</div>
			<div class="modal-body">
				<div class="row">
    				<div class="col-md-12" id="preview-area" style="min-height: 200px;">
    					<div class="row">
    						<div class="col-md-12" style="text-align: center;">
    							<div class="lds-ring">
                        			<div></div>
                        			<div></div>
                        			<div></div>
                        			<div></div>
                        		</div>
                        		<h2>Loading...</h2>
    						</div>
    					</div>
    				</div>
				</div>
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
		$('a#ex-preview').attr('href', '/candidates/preview/' + cId + '/ex');
		$.get('/candidates/preview/' + cId, function(comments) {
			$('#preview-area').html(comments);
		});
	});

	$('#modal-preview').on('hidden.bs.modal', function () {
		$('a#ex-preview').attr('href', '#');
		$('#preview-area').html('<div class="row"><div class="col-md-12" style="text-align: center;"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><h2>Loading...</h2></div></div>');
	});

	$('#f-status-group li a').on('click', function(e) {
		e.preventDefault();
		var stat = $(this).attr('href');
		var statName = $(this).text();
		$('#ht').val(stat);
		$('#f-candistat').text(statName);
	});
	
});
</script>
@endsection
