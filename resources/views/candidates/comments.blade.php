<ul class="list-group">
	@foreach ($comments as $comment)
		<li class="list-group-item">
    		{{ $comment->comment }} 
    		<br>
    		<em style="color: #888;">- Commented by {{ $comment->username }}, {{ $comment->created_at->diffForHumans() }}</em>
		</li>
	@endforeach
</ul>





