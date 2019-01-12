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
								<th>Email</th>
								<th>Source</th>
								<th width="100">Notice Period</th>
								<th>Job Position</th>
								<th>Location</th>
								<th>Match %</th>
								<th width="120">Actions</th>
							</tr>
							
							@if (isset($candidates) && count($candidates))
							
								@foreach ($candidates as $candidate)
									
									<tr>
        								<td>{{ $candidate->name }}</td>
        								<td>{{ $candidate->email }}</td>
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
        									
        									
        										<a href="{{ route('candidates.qset', ['id' => $candidate->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
            										title="Question Set">
            										<i class="fa fa-question"></i>
            									</a>
            									
            									<a href="{{ route('candidates.recalculate', ['id' => $candidate->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
            										title="Recalculate match %">
            										<i class="fa fa-calculator"></i>
            									</a>
            									
            									<a href="{{ route('candidate.edit', ['id' => $candidate->id]) }}" 
            										role="button" 
            										class="btn btn-warning btn-xs"
            										title="Edit">
            										<i class="fa fa-edit"></i>
            									</a>
        									
        										<input type="hidden" name="_method" value="DELETE"> 
												<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
												<button type="submit" 
            										class="btn btn-danger btn-xs"
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

@endsection
