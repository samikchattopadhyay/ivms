@extends('layouts.app') 

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Question Groups <small>View and manage question groups</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Question Groups</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
	
		<div class="box box-default">
			
			<form role="form" 
			
				@if($action== 'Add New')
                	action="{{ route('qgroup.store') }}" 
                @else
            		action="{{ route('qgroup.update', ['id' => $group->id]) }}" 
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
    					<div class="col-md-12">
    						
    						<div class="form-group{{ $errors->has('group_name') ? ' has-error' : '' }}">
                                <label for="group_name">Question Group Name</label>
                                <input id="group_name" type="text" class="form-control"
                                    name="group_name"
                                    value="{{ empty(old('group_name', '')) ? (isset($group->group_name) ? $group->group_name : '') : old('group_name') }}"
                                    required autofocus> 
                                @if ($errors->has('name')) 
                                	<span class="help-block"> 
                                		<strong>{{ $errors->first('group_name') }}</strong>
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
								<th>Name</th>
								<th width="100"></th>
							</tr>
							
							@if (isset($groups) && count($groups))
							
								@foreach ($groups as $group)
									
									<tr>
        								<td>{{ $group->id }}</td>
        								<td>{{ $group->group_name }}</td>
        								<td>
        									<form method="POST"
        									action="{{ route('qgroup.destroy', ['id' => $group->id]) }}" 
        									onsubmit="return confirm('Are you sure?')">
        									
        										<a href="{{ route('question.index', ['gid' => $group->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
            										title="Questions">
            										<i class="fa fa-question-circle"></i>
            									</a>
            									
            									<a href="{{ route('qgroup', ['id' => $group->id]) }}" 
            										role="button" 
            										class="btn btn-default btn-xs"
            										title="Edit Group">
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
						{{ $groups->links('vendor.pagination.bootstrap-4') }}
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
