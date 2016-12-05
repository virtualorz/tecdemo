@foreach ($nodes as $k => $v)
@if($v->prop('permission') < 2 || in_array($v->getPath(), $permission) )
<div class="menu_top_item text-align-center">
    @if(!is_null($v->prop('route')))
    <a href="{{ $v->getUrl() }}">
        <i class="fa {{ $v->prop('icon_class', 'fa-cogs') }}"></i>
        <span class="text">{{ $v->getName() }}</span>
    </a>
    @else
    <a href="#">
        <i class="fa {{ $v->prop('icon_class', 'fa-cogs') }}"></i>
        <span class="text">{{ $v->getName() }}</span>
    </a>
    @endif
</div>
@endif
@endforeach