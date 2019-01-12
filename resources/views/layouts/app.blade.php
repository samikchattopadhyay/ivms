<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Interview') }}</title>
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta
    	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
    	name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
    	href="/bower_components/bootstrap//dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
    	href="/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet"
    	href="/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet"
    	href="/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet"
    	href="/bower_components/bootstrap-datepicker//dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet"
    	href="/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
    	href="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    
    <!-- Google Font -->
    <link rel="stylesheet"
    	href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

	<!-- jQuery 3 -->
	<script src="/bower_components/jquery//dist/jquery.min.js"></script>
	
	<script>
	//File upload formSubmitSuccess
	function formSubmit (formId, callback) {
		var targetUrl = $('#' + formId).attr('action');
	    var formData = new FormData($('#' + formId)[0]);
	    $.ajax({
	        url: targetUrl,
	        type: 'POST',
	        data: formData,
	        cache: false,
	        contentType: false,
	        processData: false,
	        complete : function(resp) {
	            if (callback != undefined) {
	            	callback(resp.responseJSON);
	            } else {
	            	alert(resp.message);
	            }
	        }
	    });
	}
	</script>
	
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="fixed">

		<header class="main-header">
			<!-- Logo -->
			<a href="index2.html" class="logo"> <!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini">IMS</span> <!-- logo for regular state and mobile devices -->
				<span class="logo-lg">{{ config('app.name', 'Interview') }}</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="push-menu"
					role="button"> <span class="sr-only">{{ __('Toggle navigation') }}</span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Messages: style can be found in dropdown.less-->
						<li class="dropdown messages-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
    							<i class="fa fa-envelope-o"></i> 
    							<span class="label label-success">{{ Auth::user()->message_count }}</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have {{ Auth::user()->message_count ?? 'no' }} messages</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
										@if (Auth::user()->message_count)
    										@foreach ($userMessages as $message)
    										<li>
    											<!-- start message --> 
    											<a href="#">
    												<div class="pull-left">
    													<img src="/dist/img/{{ $message->user_image }}" class="img-circle" alt="{{ $message->user_name }}">
    												</div>
    												<h4> {{ $message->role }} <small><i class="fa fa-clock-o"></i> {{ $message->timediff }}</small></h4>
    												<p>{{ $message->message }}</p>
    											</a>
    										</li>
    										<!-- end message -->
    										@endforeach
										@endif
									</ul>
								</li>
								<li class="footer"><a href="/user/messages">See All Messages</a></li>
							</ul>
						</li>
						<!-- Notifications: style can be found in dropdown.less -->
						
						<li class="dropdown notifications-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
    							<i class="fa fa-bell-o"></i> 
    							<span class="label label-warning">{{ Auth::user()->notification_count }}</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have {{ Auth::user()->notification_count ?? 'no' }} notifications</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
										@if (Auth::user()->notification_count)
											@foreach ($userNotifications as $noti)
    										<li>
    											<a href="#"><i class="fa fa-{{ $noti->faIcon }} text-{{ $noti->iconColor }}"></i> {{ $noti->notification }}</a>
    										</li>
    										@endforeach
										@endif
										
									</ul>
								</li>
								<li class="footer"><a href="/user/notifications">View all</a></li>
							</ul>
						</li>
						<!-- Tasks: style can be found in dropdown.less -->
						
						
						<li class="dropdown tasks-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
								<i class="fa fa-flag-o"></i> 
								<span class="label label-danger">{{ Auth::user()->task_count }}</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have {{ Auth::user()->task_count ?? 'no' }} tasks</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
									
										@if (Auth::user()->task_count)
									 		@foreach ($userTasks as $task)
        										<li>
        											<!-- Task item --> 
        											<a href="#">
        												<h3>{{ $task->subject }} <small class="pull-right">{{ $task->completion }}%</small></h3>
        												<div class="progress xs">
        													<div class="progress-bar progress-bar-aqua"
        														style="width: {{ $task->completion }}%" 
        														role="progressbar" 
        														aria-valuenow="{{ $task->completion }}"
        														aria-valuemin="0" 
        														aria-valuemax="100">
        														<span class="sr-only">{{ $task->completion }}% Complete</span>
        													</div>
        												</div>
        											</a>
        										</li>
        										<!-- end task item -->
											@endforeach
										@endif
										
									</ul>
								</li>
								<li class="footer"><a href="/user/tasks">View all tasks</a></li>
							</ul>
						</li>
						<!-- User Account: style can be found in dropdown.less -->
						
						
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
    							<img src="/dist/img/avatars/{{ Auth::user()->picture ?? 'avatar.png' }}" class="user-image" alt="User Image"> 
    							<span class="hidden-xs">{{ Auth::user()->name }}</span>
							</a>
							<ul class="dropdown-menu">
							
								<!-- User image -->
								<li class="user-header">
									<img src="/dist/img/avatars/{{ Auth::user()->picture ?? 'avatar.png' }}" class="img-circle" alt="User Image">
									<p>{{ Auth::user()->name }} - {{ Auth::user()->role }} <small>Member since {{ date('M, Y', strtotime(Auth::user()->created_at)) }}</small></p>
								</li>
								
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="/user/profile" class="btn btn-default btn-flat">Profile</a>
									</div>
									<div class="pull-right">
										<a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Sign out') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
									</div>
								</li>
							</ul>
						</li>
						<!-- Control Sidebar Toggle Button -->
						
						<li>
							<a href="#" data-toggle="control-sidebar">
								<i class="fa fa-gears"></i>
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		
		<!-- Left side column. contains the logo and sidebar -->
		@include('layouts.aside')

		@yield('content')
		
		<footer class="main-footer">
			<div class="pull-right hidden-xs">
				<b>Version</b> 2.4.0
			</div>
			<strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed
					Studio</a>.
			</strong> All rights reserved.
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Create the tabs -->
			<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
				<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i
						class="fa fa-home"></i></a></li>
				<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i
						class="fa fa-gears"></i></a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<!-- Home tab content -->
				<div class="tab-pane" id="control-sidebar-home-tab">
					<h3 class="control-sidebar-heading">Recent Activity</h3>
					<ul class="control-sidebar-menu">
						<li><a href="javascript:void(0)"> <i
								class="menu-icon fa fa-birthday-cake bg-red"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

									<p>Will be 23 on April 24th</p>
								</div>
						</a></li>
						<li><a href="javascript:void(0)"> <i
								class="menu-icon fa fa-user bg-yellow"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Frodo Updated His
										Profile</h4>

									<p>New phone +1(800)555-1234</p>
								</div>
						</a></li>
						<li><a href="javascript:void(0)"> <i
								class="menu-icon fa fa-envelope-o bg-light-blue"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

									<p>nora@example.com</p>
								</div>
						</a></li>
						<li><a href="javascript:void(0)"> <i
								class="menu-icon fa fa-file-code-o bg-green"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

									<p>Execution time 5 seconds</p>
								</div>
						</a></li>
					</ul>
					<!-- /.control-sidebar-menu -->

					<h3 class="control-sidebar-heading">Tasks Progress</h3>
					<ul class="control-sidebar-menu">
						<li><a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Custom Template Design <span
										class="label label-danger pull-right">70%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-danger"
										style="width: 70%"></div>
								</div>
						</a></li>
						<li><a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Update Resume <span class="label label-success pull-right">95%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-success"
										style="width: 95%"></div>
								</div>
						</a></li>
						<li><a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Interview Integration <span
										class="label label-warning pull-right">50%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-warning"
										style="width: 50%"></div>
								</div>
						</a></li>
						<li><a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Back End Framework <span class="label label-primary pull-right">68%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-primary"
										style="width: 68%"></div>
								</div>
						</a></li>
					</ul>
					<!-- /.control-sidebar-menu -->

				</div>
				<!-- /.tab-pane -->
				<!-- Stats tab content -->
				<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab
					Content</div>
				<!-- /.tab-pane -->
				<!-- Settings tab content -->
				<div class="tab-pane" id="control-sidebar-settings-tab">
					<form method="post">
						<h3 class="control-sidebar-heading">General Settings</h3>

						<div class="form-group">
							<label class="control-sidebar-subheading"> Report panel usage <input
								type="checkbox" class="pull-right" checked>
							</label>

							<p>Some information about this general settings option</p>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading"> Allow mail redirect <input
								type="checkbox" class="pull-right" checked>
							</label>

							<p>Other sets of options are available</p>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading"> Expose author name in
								posts <input type="checkbox" class="pull-right" checked>
							</label>

							<p>Allow the user to show his name in blog posts</p>
						</div>
						<!-- /.form-group -->

						<h3 class="control-sidebar-heading">Chat Settings</h3>

						<div class="form-group">
							<label class="control-sidebar-subheading"> Show me as online <input
								type="checkbox" class="pull-right" checked>
							</label>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading"> Turn off notifications
								<input type="checkbox" class="pull-right">
							</label>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading"> Delete chat history <a
								href="javascript:void(0)" class="text-red pull-right"><i
									class="fa fa-trash-o"></i></a>
							</label>
						</div>
						<!-- /.form-group -->
					</form>
				</div>
				<!-- /.tab-pane -->
			</div>
		</aside>
		<!-- /.control-sidebar -->
		<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>
	</div>
	<!-- ./wrapper -->

	<!-- jQuery UI 1.11.4 -->
	<script src="/bower_components/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
	<!-- Bootstrap 3.3.7 -->
	<script src="/bower_components/bootstrap//dist/js/bootstrap.min.js"></script>
	<!-- Morris.js charts -->
	<script src="/bower_components/raphael/raphael.min.js"></script>
	<script src="/bower_components/morris.js/morris.min.js"></script>
	<!-- Sparkline -->
	<script
		src="/bower_components/jquery-sparkline//dist/jquery.sparkline.min.js"></script>
	<!-- jvectormap -->
	<script src="/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="/bower_components/jquery-knob//dist/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="/bower_components/moment/min/moment.min.js"></script>
	<script
		src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
	<!-- datepicker -->
	<script
		src="/bower_components/bootstrap-datepicker//dist/js/bootstrap-datepicker.min.js"></script>
	<!-- Bootstrap WYSIHTML5 -->
	<script
		src="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<!-- Slimscroll -->
	<script
		src="/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="/bower_components/fastclick/lib/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="/dist/js/adminlte.min.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="/dist/js/pages/dashboard.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="/dist/js/demo.js"></script>
</body>
</html>
