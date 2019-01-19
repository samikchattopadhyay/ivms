@extends('layouts.app') 

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Administrators <small>View and manage Admins</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/user"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="/users">Administrator</a></li>
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
									<a role="button" href="/user/create" class="btn btn-warning">
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
							<th width="30">ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Mobile</th>
							<th width="70"></th>
						</tr>
						
						@if (isset($users) && count($users))
						
							@foreach ($users as $user)
								
								<tr>
    								<td>{{ $user->id }}</td>
    								<td>{{ $user->name }}</td>
    								<td>{{ $user->email }}</td>
    								<td>{{ $user->mobile_no }}</td>
    								<td>
    									<form method="POST"
    									action="{{ route('user.destroy', ['id' => $user->id]) }}" 
    									onsubmit="return confirm('Are you sure?')">
    									
    										<a href="{{ route('user.edit', ['id' => $user->id]) }}" 
        										role="button" 
        										class="btn btn-default btn-xs"
        										title="Edit">
        										<i class="fa fa-edit"></i>
        									</a>
    									
    										<input type="hidden" name="_method" value="DELETE"> 
											<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
											@if ($user->email != Auth::user()->email)
            									<button type="submit" 
            										class="btn btn-danger btn-xs"
            										title="Delete">
            										<i class="fa fa-trash"></i>
            									</button>
    										@endif
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
					{{ $users->links('vendor.pagination.bootstrap-4') }}
                </div>
                
                
			</div>
			<!-- /.box -->
		</div>
	</div>

</section>
<!-- /.content -->

@endsection
