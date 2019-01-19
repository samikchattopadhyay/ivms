@extends('layouts.app') 

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Questions ( {{ $group->group_name }} ) <small>View and manage questions in this groups</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('qgroup') }}">Question Groups</a></li>
		<li class="active">List All</li>
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
								<input type="text" name="s" class="form-control pull-right" placeholder="Search">
								<div class="input-group-btn">
									<button type="submit" class="btn btn-default">
										<i class="fa fa-search"></i>
									</button>
									<a role="button" href="{{ route('question.create', ['gid' => $group->id]) }}" class="btn btn-warning">
										<i class="fa fa-plus"></i>
										Add New Question
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
							<th>Question</th>
							<th width="100">Type</th>
							<th width="70"></th>
						</tr>
						
						@if (isset($questions) && count($questions))
						
							@foreach ($questions as $question)
								
								<tr>
    								<td>{{ $question->question }}</td>
    								<td>{{ $question->type }}</td>
    								<td>
    									<form method="POST"
    									action="{{ route('question.destroy', ['id' => $question->id]) }}" 
    									onsubmit="return confirm('Are you sure?')">
    									
    										<a href="{{ route('question.edit', ['id' => $question->id ]) }}" 
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
					{{ $questions->links('vendor.pagination.bootstrap-4') }}
                </div>
                
                
			</div>
			<!-- /.box -->
		</div>
	</div>

</section>
<!-- /.content -->

@endsection
