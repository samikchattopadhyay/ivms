@extends($layout == false ? 'layouts.app' : 'layouts.app-popup')

@section('content')

@if ($layout == false)
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Candidate <small>View candidates detail</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('candidate.index') }}">Candidates</a></li>
		<li class="active">Detail</li>
	</ol>
</section>
@endif

<!-- Main content -->
<section class="content">
	<div class="{{ $layout == false ? 'box' : '' }} box-default">
		<div class="box-body">
            <div class="row">
            	<div class="col-md-2">
            		<img src="{{ Avatar::create($candidate->name)->toBase64() }}" style="height: 130px;">
            	</div>
            	<div class="col-md-7">
            		<h2>{{ $candidate->name }}</h2>
            		<p>
            			<a href="mailto: {{ $candidate->email }}">{{ $candidate->email }}</a>
            			<br>Suitable for <b>{{ $candidate->position }}</b>
            			<br>{!! !empty($candidate->location) ? 'From <b>' . $candidate->location . '</b>' : '' !!}
            		</p>
            		<p>
            			@php
            				$keywords = array_unique($keywords);
            			@endphp
            			
            			@foreach ($keywords as $keyword)
                        	<span class="label label-default">{{ $keyword }}</span>
                        @endforeach
                    </p>
                    
                    @if (!$layout)
                        <hr>
                        <div class="row">
                        	<div class="col-md-4">
                        		<label>Status</label>
                        		<div class="input-group">
            						<div id="status-group" class="btn-group">
            							<button type="button" class="btn btn-default" id="candistat">{{ $statusList[$candidate->status] }}</button>
            							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            								<span class="caret"></span> 
            								<span class="sr-only">Select Status</span>
            							</button>
            							<ul class="dropdown-menu" role="menu">
            								<li><a href="REJ">Rejected</a></li>
            								<li><a href="SLT">Shortlisted</a></li>
            								<li class="divider"></li>
            								<li><a href="WTG">Waiting</a></li>
            								<li><a href="SEL">Selected</a></li>
            								<li><a href="NEG">Negotiate</a></li>
            								<li class="divider"></li>
            								<li><a href="CNF">Confirmed</a></li>
            								<li><a href="JND">Joined</a></li>
            							</ul>
            						</div>
            					</div>
                        	</div>
                        	<div class="col-md-4">
                        		@if ($candidate->status == 'INV')
                        			<label>Interview Scheduled at</label>
                        			@if (empty($candidate->interview))
                            		<div class="input-group" style="width: 250px;">
                						<div class="input-group-addon">
                							<i class="fa fa-clock-o"></i>
                						</div>
                						<input type="text"
                							class="form-control pull-right"
                							id="reservationtime">
                					</div>
                					@else
                					<div class="input-group" style="width: 250px;">
                						<div class="input-group-addon">
                							<i class="fa fa-clock-o"></i>
                						</div>
                						<input type="text"
                							readonly
                							value="{{ date('dS M, Y - h:i a', strtotime($candidate->interview)) }}"
                							class="form-control pull-right">
                					</div>
                					@endif
            					@endif
                        	</div>
                        </div>
                    @endif
				</div>
            	<div class="col-md-3">
            		<center>
                		<div id="sellPerCirc" class="perCirc">
                            <div class="perCircInner">
                                <div class="perCircStat">0%</div><div>Match</div>
                            </div>
                        </div>
                        <br>
                        
                    	<a href="{{ route('candidates.resume', ['cid' => $candidate->id]) }}" 
                    		role="button" 
                    		class="btn btn-xs btn-success" 
                            target="_blank"
                            title="Download Resume/CV">Download</a>
                            
                        @if (empty($candidate->qsent))
                        	<a href="{{ route('candidates.email', ['cid' => $candidate->id]) }}" 
                    		role="button" 
                    		class="btn btn-xs btn-success"
                    		title="Email question set to candidate">Email</a>
                		@elseif (in_array($candidate->status, ['NEW','SLT','QNA']))
                			<a href="{{ route('candidates.email', ['cid' => $candidate->id]) }}" 
                    		role="button" style="color: black;"
                    		class="btn btn-xs btn-warning"
                    		title="Resend question set to candidate">Resend</a>
                		@else
                			<a href="#" 
                    		role="button"
                    		class="btn btn-xs btn-default" disabled>Sent</a>
                		@endif
                		
                    </center>
                    
                    <style>
                        .perCirc {
                            position: relative;
                            text-align: center;
                            width: 110px;
                            height: 110px;
                            border-radius: 100%;
                            background-color: #00cc00;
                            background-image: linear-gradient(91deg, transparent 50%, #ccc 50%), linear-gradient(90deg, #ccc 50%, transparent 50%);
                        }
                        .perCirc .perCircInner {
                            position: relative;
                            top: 10px;
                            left: 10px;
                            text-align: center;
                            width: 90px;
                            height: 90px;
                            border-radius: 100%;
                            background-color: #eee;
                        }
                        .perCirc .perCircInner div {
                            position: relative;
                            top: 22px;
                            color:#777;
                        }
                        .perCirc .perCircStat {
                            font-size: 30px;
                            line-height:1em;
                        }
                    </style>
                    
                    <script>
                    
                    function perCirc($el, end, i) {
                        if (end < 0)
                            end = 0;
                        else if (end > 100)
                            end = 100;
                        if (typeof i === 'undefined')
                            i = 0;
                        var curr = (100 * i) / 360;
                        $el.find(".perCircStat").html(Math.round(curr) + "%");
                        if (i <= 180) {
                            $el.css('background-image', 'linear-gradient(' + (90 + i) + 'deg, transparent 50%, #ccc 50%),linear-gradient(90deg, #ccc 50%, transparent 50%)');
                        } else {
                            $el.css('background-image', 'linear-gradient(' + (i - 90) + 'deg, transparent 50%, #00cc00 50%),linear-gradient(90deg, #ccc 50%, transparent 50%)');
                        }
                        if (curr < end) {
                            setTimeout(function () {
                                perCirc($el, end, ++i);
                            }, 1);
                        }
                    }
            
                    $(document).ready(function() {
            
                    	// change the value below from 80 to whichever 
                     	// percentage you want it to stop at.
                        perCirc($('#sellPerCirc'), {{ $candidate->cv_match_percent}});
            
                    });
                    
                    </script>
            	</div>
            </div>
        
            @if (count($comments))
            <hr>
            <div class="row">
            	<div class="col-md-12">
            		<h3>Comments</h3>
            		<div class="row">
            			<div class="col-md-12">
            				<ul class="list-group" id="listGroup">
                            	@foreach ($comments as $comment)
                            		<li class="list-group-item">
                                		{{ $comment->comment }} 
                                		<br>
                                		<em style="color: #888;">- Commented by {{ empty($comment->username) ? 'System' : $comment->username }}, {{ $comment->created_at->diffForHumans() }}</em>
                            		</li>
                            	@endforeach
                            </ul>
            			</div>
            		</div>
            	</div>
            </div>
            @endif
            
            @if ($layout == false)
                <!-- Give a comment -->
                <br>
                <form id="comment-form" action="/candidates/comment" method="post">
                	{{ csrf_field() }} 
                	<input type="hidden" name="candidate" value="{{ $candidate->id }}" />
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
    				<div class="row">
    					<div class="col-md-12">
    						<button type="submit" class="btn btn-primary">Comment</button>
    					</div>
    				</div>
                </form>
                
                <script>
    			$(document).ready(function() {
    
    				$('#comment-form').on('submit', function(e) {
    					e.preventDefault();
    					formSubmit ('comment-form', function(args) {
    						if (args.success) {
    							$('#comment-text').val('');
    							var commentLi = '<li class="list-group-item">' +  args.comment + 
    							' <br><em style="color: #888;">- Commented by ' + args.username + 
    							', ' + args.created + '</em></li>';
    							$('#listGroup').prepend(commentLi);
    							alert('Comment has been posted');
    						}
    					});
    				});
    
    			});
                </script>
            @endif
        
            @if (count($answers))
            <div class="row">
            	<div class="col-md-12">
            		<h3>Question &amp; Answers</h3>
            		<hr>
            		@if (isset($questions) && count($questions))
                		@foreach ($questions as $question)
                			<div class="row">
                				<div class="form-group">
            						<div class="col-md-12">
            							<label for="qid-{{ $question->id }}">{{ $question->question }}</label>
            						</div>
            						
            						@if ($question->type == 'Text')
            							<div class="col-md-12">
            								<p style="border: 1px #AAA solid; background-color: #EFEFEF; padding: 10px;">{{ $answers[$question->id] }}</p>
            							</div>
            						@elseif ($question->type == 'Select')
            							<div class="col-md-12">
            								<select class="form-control" disabled>
                    							@if (!empty($question->options))
                    								@foreach ($question->options as $option)
                    								<option value="{{ $option->id }}" {{ $answers[$question->id] == $option->id ? 'selected' : '' }}>{{ $option->label }}</option>
                    								@endforeach
                    							@endif
                							</select>
            							</div>
            						@else
            							@if (!empty($question->options))
            								@foreach ($question->options as $option)
            									<div class="checkbox col-md-4">
                                                    <label>
                                                      	<input type="checkbox" value="{{ $option->id }}" {{ in_array($option->id, $answers[$question->id]) ? 'checked' : '' }} disabled> {{ $option->label }}
                                                    </label>
                                                </div>
            								@endforeach
            							@endif
            						@endif
            					</div>
                			</div>
                		@endforeach
                	@endif
            	</div>
            </div>
            @endif
    	</div>
    </div>
</section>
<!-- /.content -->

<script>
$(document).ready(function() {

	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	$('#status-group li a').on('click', function(e) {
		
		e.preventDefault();
		var stat = $(this).attr('href');
		var statName = $(this).text();
		
		$.post('/candidates/status', {
			_token: CSRF_TOKEN,
			cid: '{{ $candidate->id }}',
			status: stat
		}, function(response) {
			if (response.success) {
				$('#candistat').text(statName);
			}
		}, 'json');
	});


	@if ($candidate->status == 'INV' && empty($candidate->interview) && !$layout)
    	$('#reservationtime').daterangepicker({
        	"dateFormat": 'yy-mm-dd h:mm A',
            "singleDatePicker": true,
            "showDropdowns": true,
            "timePicker": true,
            "timePickerIncrement": 15,
            "autoApply": true,
        }, function(start, end, label) {
    
        	$.post('/candidates/interview', {
    			_token: CSRF_TOKEN,
    			cid: '{{ $candidate->id }}',
    			interview: start.format('YYYY-MM-DD h:mm A')
    		}, function(response) {
    			if (response.success) {
    				alert('Done');
    			} else {
    				alert('Failed');
    			}
    		}, 'json');
    
        });
    @endif

});
</script>
@endsection
