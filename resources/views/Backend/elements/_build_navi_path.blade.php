@foreach ($nodeAllPath as $k => $v)
@if(!is_null($v->prop('route')) && $v->getPath() != $nodeCurr->getPath())
<li><a href="{{ $v->getUrl() }}">{{ $v->getName() }}</a></li>
@else
<li>{{ $v->getName() }}</li>
@endif
@endforeach