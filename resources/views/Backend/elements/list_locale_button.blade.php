<div class="select_locale_con pull-right">
    {{ trans('page.text.select_language') }}:
    @expr( $_listLoacle = $_configLocale )
    @expr( $_listLoacleSelect = isset($listLocaleSelect) ? $listLocaleSelect : [] )
    @if(isset($_listLoacle[$_appLocale]))
        @expr( $_listLoacle=[$_appLocale => $_listLoacle[$_appLocale]] + $_listLoacle )
    @endif
        
    @foreach($_listLoacle as $k => $v)
    @if(in_array($k, $_listLoacleSelect))
    <button type="button" class="btn margin-5 btnLocale {{ $k }} selected " data-locale="{{ $k }}">{{ $v['name'] }}</button>
    @else
    <button type="button" class="btn margin-5 btnLocale {{ $k }} " data-locale="{{ $k }}">{{ $v['name'] }}</button>
    @endif
    @endforeach
    <div></div>
</div>