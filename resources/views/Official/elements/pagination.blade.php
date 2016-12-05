@if(isset($pagination) && $pagination['last'] > 1)
@expr( $qs = Request::getQueryString() )
<div class="text-center">
    <div class="pagination scott">
        <ul>
            <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(array_merge(Route::input('optional', []), ['page' => $pagination['first']]))  ])) . ($qs ? '?' . $qs : '') }}" title='{{ trans('pagination.first') }}'>{{ trans('pagination.first') }}</a></li>
            <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(array_merge(Route::input('optional', []), ['page' => $pagination['prev']]))   ])) . ($qs ? '?' . $qs : '') }}" title='{{ trans('pagination.previous') }}'>{{ trans('pagination.previous') }}</a></li>
            @for($i = $pagination['start']; $i <= $pagination['end']; $i++)
            @if($i == $pagination['curr'])
            <li class='current'>{{ $i }}</li>
            @else
            <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(array_merge(Route::input('optional', []), ['page' => $i]))   ])) . ($qs ? '?' . $qs : '') }}" title='{{ $i }}'>{{ $i }}</a></li>
            @endif
            @endfor
            <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(array_merge(Route::input('optional', []), ['page' => $pagination['next']]))   ])) . ($qs ? '?' . $qs : '') }}" title='{{ trans('pagination.next') }}'>{{ trans('pagination.next') }}</a></li>
            <li><a href="{{ route($pagination['routeName'], array_merge(Route::current()->parameters(), ['optional' => Sitemap::formatOptionalParam(array_merge(Route::input('optional', []), ['page' => $pagination['last']]))   ])) . ($qs ? '?' . $qs : '') }}" title='{{ trans('pagination.last') }}'>{{ trans('pagination.last') }}</a></li>
        </ul>
    </div>
</div>
@endif
