<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Interview') }}</title>
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/bower_components/bootstrap//dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/bower_components/bootstrap-datepicker//dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

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
						
						@php
						$userNotificationCount = IvmsNotifier::countNew(Auth::user()->id);
						$userNotifications = IvmsNotifier::getNew(Auth::user()->id, 10);
						@endphp
						
						<li class="dropdown notifications-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
    							<i class="fa fa-bell-o"></i> 
    							@if ($userNotificationCount)
    							<span class="label label-danger" id="notival">{{ $userNotificationCount }}</span>
								@endif
							</a>
							<ul class="dropdown-menu">
								@if ($userNotificationCount == 0)
								<li class="header"><center>You have no new notifications</center></li>
								@else
								<li>
									<!-- inner menu: contains the actual data -->
									<ul id="noties" class="menu">
										@if (count($userNotifications))
											@foreach ($userNotifications as $noti)
    										<li>
    											<a data-unid="{{ $noti->unid }}"
    												target="{{ empty($noti->target) ? '_parent' : '_blank' }}" 
        											href="{{ $noti->target ?? '#' }}"
        											title="{{ $noti->message }}">
        											<i class="fa fa-{{ @$noti->faIcon }} text-{{ @$noti->iconColor }}"></i> 
        											{{ $noti->message }}
    											</a>
    										</li>
    										@endforeach
										@endif
										
									</ul>
								</li>
								@endif
							</ul>
						</li>
						
						<script>
                        $(document).ready(function() {
                        	$('body').on('click', '#noties li a', function(resp) {

                                var that = $(this);
                        		var unId = $(this).data('unid');
                        
                        		$.post('/users/notified', {
                        			_token: $('meta[name="csrf-token"]').attr('content'),
                        			id: unId,
                        		}, function(response) {
                        			var notival = parseInt($('#notival').text());'
                        			notival = notival > 0 ? notival - 1 : 0;
                        			console.log(notival);
                        			$('#notival').text(notival);
                        			that.parent().remove();
                        		}, 'json');
                        		
                        	});
                        });
						</script>
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
									<p>{{ Auth::user()->name }} <small>Member since {{ date('M, Y', strtotime(Auth::user()->created_at)) }}</small></p>
								</li>
								
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="/user/{{ Auth::user()->id }}/edit" class="btn btn-default btn-flat">Profile</a>
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
					</ul>
				</div>
			</nav>
		</header>
		
		<!-- Left side column. contains the logo and sidebar -->
		@include('layouts.aside')

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			@yield('content')
		</div>
		<!-- /.content-wrapper -->
		
		<footer class="main-footer">
			<strong>Copyright &copy; 2018-{{ date('Y') }} <a href="{{ env('COMPANY_URL', '#') }}">{{ env('COMPANY_NAME', 'Yourcompany Inc') }}</a>.
			</strong> All rights reserved.
		</footer>
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
	<script src="/bower_components/jquery-sparkline//dist/jquery.sparkline.min.js"></script>
	<!-- jvectormap -->
	<script src="/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="/bower_components/jquery-knob//dist/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<!-- datepicker -->
	<script src="/bower_components/bootstrap-datepicker//dist/js/bootstrap-datepicker.min.js"></script>
	<!-- Bootstrap WYSIHTML5 -->
	<script src="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<!-- Slimscroll -->
	<script src="/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
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
