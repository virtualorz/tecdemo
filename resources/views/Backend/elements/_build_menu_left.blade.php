@if($pos === 'root')
@foreach(($nodeAllMenu = $node->getChildren(null, ['menu' => true])) as $k => $v)
{!! view('backend.elements._build_menu_left')->with('node', $v)->with('pos', 'head')->with('permission', $permission)->render() !!}
@endforeach




@elseif($pos === 'head')
@if($node->prop('permission') < 2 || in_array($node->getPath(), $permission) )
@if(!is_null($node->prop('route')))
<li class="xn-title menuitem_{{ str_replace('.', '_', $node->getPath()) }}"><a href="{{ $node->getUrl() }}">{{ $node->getName() }}</a></li>
@else
<li class="xn-title menuitem_{{ str_replace('.', '_', $node->getPath()) }}">{{ $node->getName() }}</li>
@endif
@foreach(($nodeAllMenu = $node->getChildren(null, ['menu' => true])) as $k => $v)
{!! view('backend.elements._build_menu_left')->with('node', $v)->with('pos', 'item')->with('permission', $permission)->render() !!}
@endforeach
@endif




@else
@if($node->prop('permission') < 2 || in_array($node->getPath(), $permission) )
@if( count($nodeAllMenu = $node->getChildren(null, ['menu' => true, 'permission' => function($k) use ($node, $permission){
    return ($k < 2 || in_array($node->getPath(), $permission));
} ])) > 0)
<li class="xn-openable menuitem_{{ str_replace('.', '_', $node->getPath()) }}">
@else
<li class="menuitem_{{ str_replace('.', '_', $node->getPath()) }}">
@endif

    @if(!is_null($node->prop('route')))
    <a href="{{ $node->getUrl() }}">
        <span class="fa {{ $node->prop('icon_class', 'fa-files-o') }}"></span> 
        <span class="xn-text">{{ $node->getName() }}</span>        
    </a>
    @else
    <a>
        <span class="fa {{ $node->prop('icon_class', 'fa-files-o') }}"></span> 
        <span class="xn-text">{{ $node->getName() }}</span>
    </a>
    @endif

    @if(count($nodeAllMenu) > 0)
    <ul>
        @foreach($nodeAllMenu as $k => $v)
        {!! view('backend.elements._build_menu_left')->with('node', $v)->with('pos', 'item')->with('permission', $permission)->render() !!}
        @endforeach
    </ul>
    @endif
</li>
@endif
@endif