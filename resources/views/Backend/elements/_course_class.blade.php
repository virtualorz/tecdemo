@if(count($list_course_class) > 0)
@foreach($list_course_class as $k => $v)
<label class="check">
    <input type="radio" name="course_class-id" id="data-course_class-id-{{ $v['id'] }}" class="iradio required" value="{{ $v['id'] }}" @if($v['id'] == Request::input('course_class-id', ''))checked="checked"@endif />
    @if($v['date_unlimited'] == 1)
    {{ trans('validation.attributes.course_class-date_unlimited') }}
    @else
    {{ $v['date_start'] }} ~ {{ $v['date_end'] }}
    @endif
    {{ $v['course_class_locale_name'] }}
    {{ trans('enum.course_class-class_time.' .  $v['class_time']) }}
</label><br />                             
@endforeach
@else
{{ trans('message.info.norecord') }}
@endif