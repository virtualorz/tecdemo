@extends('emails.layouts.master')


@section('content')
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tbody>
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tbody>
                        <tr>
                            <td>

                                <table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td align="center">
                                                <div align="center">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td width="122" height="60">
                                                                    <p>{{ trans('validation.attributes.course-course_code') }}</p>
                                                                </td>
                                                                <td width="480" height="60">
                                                                    <p>{{ $data_course_class['course_code'] }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="122" height="60">
                                                                    <p>{{ trans('validation.attributes.course-name') }}</p>
                                                                </td>
                                                                <td width="480" height="60">
                                                                    <p>
                                                                        {{ $data_course_class['course_name_cht'] }}
                                                                        ({{ $data_course_class['course_name_eng'] }})
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="122" height="60">
                                                                    <p>{{ trans('validation.attributes.course_class-date') }}</p>
                                                                </td>
                                                                <td width="480" height="60">
                                                                    <p>
                                                                        @if($data_course_class['date_unlimited'] == 1)
                                                                        {{ trans('validation.attributes.course_class-date_unlimited') }}
                                                                        @else
                                                                        {{ $data_course_class['date_start'] }} ~ {{ $data_course_class['date_end'] }}
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="122" height="60">
                                                                    <p>{{ trans('validation.attributes.course_class-class_time') }}</p>
                                                                </td>
                                                                <td width="480" height="60">
                                                                    <p>
                                                                        {{ trans('enum.course_class-class_time.' . $data_course_class['class_time']) }}                                                                        
                                                                        {{ $data_course_class['course_class_time'] }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="122" height="60">
                                                                    <p>{{ trans('validation.attributes.course_class_locale-address') }}</p>
                                                                </td>
                                                                <td width="480" height="60">
                                                                    <p>{{ $data_course_class['course_class_locale_address'] }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="122" height="60">
                                                                    <p>餐點服務</p>
                                                                </td>
                                                                <td width="480" height="60">
                                                                    <p>備早餐、午餐及點心、飲料</p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @foreach($data_course_class['course_class_locale_traffic'] as $k => $v)
                                                <img src="{{ $v['urlScale0'] }}" width="526" height="202" alt="{{ $v['name'] }}" />
                                                @endforeach
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td height="47" align="center">&nbsp;</td>
        </tr>
    </tbody>
</table>

@endsection
