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
            	<div class="col-md-8">
            		<h2>{{ $candidate->name }}</h2>
            		<p>
            			<a href="mailto: {{ $candidate->email }}">{{ $candidate->email }}</a>
            			<br>Suitable for {{ $candidate->position }}
            			<br>{{ !empty($candidate->location) ? 'From ' . $candidate->location : '' }}
            		</p>
            		<p>
            			@php
            				$keywords = array_unique($keywords);
            			@endphp
            			
            			@foreach ($keywords as $keyword)
                        	<span class="label label-default">{{ $keyword }}</span>
                        @endforeach
                    </p>
            	</div>
            	<div class="col-md-2">
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
                    		title="Email question set">Email</a>
                		@else
                		<a href="#" 
                    		role="button" 
                    		class="btn btn-xs btn-default disabled">Sent</a>
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
            		<h3>Question & Answers</h3>
            		<hr>
            		@if (isset($questions) && count($questions))
                		@foreach ($questions as $question)
                			<?php //dump($answers[$question->id]) ?>
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
@endsection
