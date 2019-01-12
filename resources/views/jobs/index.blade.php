@extends('layouts.app') 

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Jobs <small>View and manage Jobs</small></h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Jobs</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">List All</h3>
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
    									<a role="button" href="/job/create" class="btn btn-warning">
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
								<th width="50">ID</th>
								<th>Position</th>
								<th>Location</th>
								<th>Compensation</th>
								<th>Vacancies</th>
								<th>Expiry</th>
								<th>CV#</th>
								<th width="100">Actions</th>
							</tr>
							
							@if (isset($jobs) && count($jobs))
							
								@foreach ($jobs as $job)
									
									<tr>
        								<td>{{ $job->id }}</td>
        								<td>{{ $job->position }}</td>
        								<td>{{ $job->location }}</td>
        								<td>{{ $job->compensation }}</td>
        								<td>{{ $job->vacancies }}</td>
        								<td>{{ $job->expiry_date }}</td>
        								<td>{{ $job->cv_count }}</td>
        								<td>
        									<form method="POST"
        										action="{{ route('job.destroy', ['id' => $job->id]) }}" 
        										onsubmit="return confirm('Are you sure?')">
        									
        										<a href="{{ route('candidate.index', ['jid' => $job->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
            										title="Candidates">
            										<i class="fa fa-user"></i>
            									</a>
            									
        										<a href="{{ route('job.edit', ['id' => $job->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
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
						{{ $jobs->links('vendor.pagination.bootstrap-4') }}
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
