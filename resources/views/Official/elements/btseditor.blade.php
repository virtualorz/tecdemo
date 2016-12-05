<div class="btseditor_content btseditor-col">
    @foreach($btseditorContent as $k => $v)
    <div class="btseditor_content-row">
        @foreach($v['cell'] as $kk => $vv)
        <div class="btseditor_content-cell column_{{ $vv['weight'] }}_{{ $v['column'] }}">
            @if(count($vv['item']) > 0)
            @foreach($vv['item'] as $kkk => $vvv)
            @if($vvv['type'] == 'text')
            <div class="btseditor_content-item item-text">
                @if(trim($vvv['title']) != '')
                <h2 class="item-text-title">{{ $vvv['title'] }}</h2>
                @endif
                <div class="item-text-content">{!! $vvv['content'] !!}</div>
            </div>
            @elseif($vvv['type'] == 'pic')
            <div class="btseditor_content-item item-pic {{ $vvv['align'] or '' }}">
                @expr($pic_url = '')
                @if(!isset($vvv['url_type']) || $vvv['url_type'] == 1)
                @expr($pic_url = isset($vvv['url']) ? $vvv['url'] : '')
                @elseif($vvv['url_type'] == 2 && !is_null($vvv['url_file']) )
                @expr($pic_url = FileUpload::getRootUrl() . $vvv['url_file']['dir'] . '/' . $vvv['url_file']['id'] . '.' . $vvv['url_file']['ext'])
                @endif
                
                @if($pic_url != "")
                <a href="{{ $pic_url }}" target="_blank">
                    <img src="{{ FileUpload::getRootUrl() . $vvv['file']['dir'] . '/' . $vvv['file']['id'] . '.' . $vvv['file']['ext'] }}" alt="{{ $vvv['file']['name'] }}" />
                </a>
                @else
                <img src="{{ FileUpload::getRootUrl() . $vvv['file']['dir'] . '/' . $vvv['file']['id'] . '.' . $vvv['file']['ext'] }}" alt="{{ $vvv['file']['name'] }}" />
                @endif
            </div>
            @elseif($vvv['type'] == 'video')
            <div class="btseditor_content-item item-video">
                <div class="item-video-con">
                    {!! $vvv['content'] !!}
                </div>
            </div>
            @endif
            @endforeach
            @else
            <div class="btseditor_content-item">&nbsp;</div>
            @endif
        </div>
        @endforeach
    </div>
    @endforeach
</div>
