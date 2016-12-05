@if(isset($pagination) && $pagination['last'] > 1)
@expr( $qs = Request::getQueryString() )
<ul class="pagination pull-right" style="width: auto;" >
    <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(Route::input('optional', []))  ])) }}?page={{$pagination['first']}}" title='{{ trans('pagination.first') }}'>{{ trans('pagination.first') }}</a></li>
    <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(Route::input('optional', []))  ])) }}?page={{$pagination['prev']}}" title='{{ trans('pagination.previous') }}'>{{ trans('pagination.previous') }}</a></li>
    @for($i = $pagination['start']; $i <= $pagination['end']; $i++)
    @if($i == $pagination['curr'])
    <li class='active'><a class='active'>{{ $i }}</a></li>
    @else
    <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(Route::input('optional', []))   ])) }}?page={{$i}}" title='{{ $i }}'>{{ $i }}</a></li>
    @endif
    @endfor
    <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(Route::input('optional', []))   ])) }}?page={{$pagination['next']}}" title='{{ trans('pagination.next') }}'>{{ trans('pagination.next') }}</a></li>
    <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(Route::input('optional', []))   ])) }}?page={{$pagination['last']}}" title='{{ trans('pagination.last') }}'>{{ trans('pagination.last') }}</a></li>
</ul>
@endif
