@extends('layouts.app-front') 

@section('content')

<!-- Content Wrapper. Contains page content -->

	<!-- Main content -->
	<section class="content">
	
		<div class="row">
        	<div class="col-md-2">
        		<center>
            		<div id="sellPerCirc" class="perCirc">
                        <div class="perCircInner">
                            <div class="perCircStat">0%</div><div>Match</div>
                        </div>
                    </div>
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
        	<div class="col-md-10">
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
        </div>
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-default">
				
					<form role="form" 
            			action="{{ route('candidates.answer') }}"
                    	method="POST">
                    	
                    	{{ csrf_field() }}
                    	
                    	<input type="hidden" name="session" value="{{ $session }}" />
    				
    					<div class="box-header with-border">
    						<h2>Questions</h2>
    					</div>
    					<!-- /.box-header -->
    					
    					<div class="box-body">
    					
        					@if (isset($questions) && count($questions))
            					@foreach ($questions as $question)
            						<div class="row">
            							<div class="col-md-12">
            								<div class="form-group">
                								<label for="qid-{{ $question->id }}">{{ $question->question }}</label>
                        						@if ($question->type == 'Text')
                        							<textarea id="qid-{{ $question->id }}" 
                        								name="qid[{{ $question->id }}]" 
                        								class="form-control"
                        								required></textarea>
                        						@elseif ($question->type == 'Select')
                        							<select id="qid-{{ $question->id }}" 
                        								name="qid[{{ $question->id }}]" 
                        								class="form-control"
                        								required>
                            							@if (!empty($question->options))
                            								@foreach ($question->options as $option)
                            								<option value="{{ $option->id }}">{{ $option->label }}</option>
                            								@endforeach
                            							@endif
                        							</select>
                        						@else
                        							@if (!empty($question->options))
                        								@foreach ($question->options as $option)
                        									<div class="checkbox">
                                                                <label>
                                                                  	<input type="checkbox"
                                        								name="qid[{{ $question->id }}][]" 
                                        								value="{{ $option->id }}"> {{ $option->label }}
                                                                </label>
                                                            </div>
                        								@endforeach
                        							@endif
                        						@endif
                    						</div>
                						</div>
            						</div>
            					@endforeach
        					@endif
    					</div>
    					<!-- /.box-body -->
    					
    					<div class="box-footer" style="text-align: center;">
                            <button type="submit" class="btn btn-primary">Submit Answers</button>
                        </div>
                    
                    </form>
				</div>
				<!-- /.box -->
			</div>
		</div>
	
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
