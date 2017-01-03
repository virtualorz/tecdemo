@if(isset($pagination) && $pagination['last'] > 1)
@expr( $qs = Request::getQueryString() )
<nav aria-label="Page navigation" class="text-center">
	<ul class="pagination">
	    <li>
		    <a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), [])) }}?page={{$pagination['prev']}}" aria-label="Previous">
			    <i class="fa fa-caret-left"></i>
		    </a>
		</li>
        @for($i = $pagination['start']; $i <= $pagination['end']; $i++)
        @if($i == $pagination['curr'])
        <li class="active"><a>{{ $i }}</a></li>
        @else
        <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), [])) }}?page={{$i}}">{{ $i }}</a></li>
        @endif
        @endfor
		<li>
			<a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), [])) }}?page={{$pagination['next']}}" aria-label="Next">
				<i class="fa fa-caret-right"></i>
			</a>
		</li>
	</ul>
</nav>
@endif
