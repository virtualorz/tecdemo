<div class="btseditor_content">
    @foreach($btseditorContent as $k => $v)
    <div class="btseditor_content-data-row">
        @expr($tmpCountHalf = 0)

        @foreach($v['cell'] as $kk => $vv)
        @expr($rate_class="column_" . $vv['weight'] . "_" . $v['column'])

        @if($tmpCountHalf == 1)
        @expr($rate_class="column_7_10")
        
        @elseif($vv['weight'] = 1 && $v['column'] == 2)
        @expr($rate_class="column_3_10")
        @expr($tmpCountHalf = 1)
        
        @endif
        <div class="btseditor_content-data-cell {{ $rate_class }}">
            @if(count($vv['item']) > 0)
            @foreach($vv['item'] as $kkk => $vvv)
            @if($vvv['type'] == 'text')
            <div class="btseditor_content-data-item item-text">
                @if(trim($vvv['title']) != '')
                <h3 class="item-text-title">{{ $vvv['title'] }}</h3>
                @endif
                <div class="item-text-content">{!! $vvv['content'] !!}</div>
            </div>
            @elseif($vvv['type'] == 'pic')
            <div class="btseditor_content-data-item item-pic {{ $imgAlign or '' }} ">
                @if(isset($vvv['url']) && $vvv['url'] != "")
                <a href="{{ $vvv['url'] }}"  @if(isset($imgLinkTarget)) target="{{ $imgLinkTarget }}" @endif>
                    <img src="{{ FileUpload::getRootUrl() . $vvv['file']['dir'] . '/' . $vvv['file']['id'] . '.' . $vvv['file']['ext'] }}" alt="{{ $vvv['file']['name'] }}" />
                </a>
                @else
                <img src="{{ FileUpload::getRootUrl() . $vvv['file']['dir'] . '/' . $vvv['file']['id'] . '.' . $vvv['file']['ext'] }}" alt="{{ $vvv['file']['name'] }}" />
                @endif
            </div>
            @elseif($vvv['type'] == 'video')
            <div class="btseditor_content-data-item item-video">
                <div class="video-container">
                    {!! $vvv['content'] !!}
                </div>
            </div>
            @endif
            @endforeach
            @else
            <div class="btseditor_content-data-item">&nbsp;</div>
            @endif
        </div>
        @endforeach
    </div>
    @endforeach
</div>
