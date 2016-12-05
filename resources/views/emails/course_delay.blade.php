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
                                                                <td width="595" valign="top">
                                                                    @if(count($data_course_to) > 0)
                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a"><br>
                                                                        親愛的同學，您好：<br />
                                                                        非常感謝您選擇國際網路認證學院的教育訓練課程。<br />
                                                                        <br />
                                                                        貴公司報名參加的課程，由於報名人數未達開班最低標準，將延期至下一梯次，特此通知。
                                                                    </p>

                                                                    <hr />
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="90" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.member-name') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td width="200" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_member['name_cht'] }}
                                                                                    </p>
                                                                                </td>
                                                                                <th width="90" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.course-fee') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td width="200" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_course_to['fee'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="90" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.course-name') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td colspan="3" width="200" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_course_to['name_cht'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="90" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.course_class-date_start_from') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td width="200" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        @if($data_course_from['date_unlimited'] == 1)
                                                                                        {{ trans('validation.attributes.course_class-date_unlimited') }}
                                                                                        @else
                                                                                        {{ $data_course_from['date_start'] }} ~ {{ $data_course_from['date_end'] }}
                                                                                        @endif 
                                                                                        {{ $data_course_from['course_class_locale_name'] }}
                                                                                        {{ trans('enum.course_class-class_time.' .  $data_course_from['class_time']) }}
                                                                                    </p>
                                                                                </td>
                                                                                <th width="90" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.course_class-date_start_delay_to') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td width="200" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        @if($data_course_to['date_unlimited'] == 1)
                                                                                        {{ trans('validation.attributes.course_class-date_unlimited') }}
                                                                                        @else
                                                                                        {{ $data_course_to['date_start'] }} ~ {{ $data_course_to['date_end'] }}
                                                                                        @endif 
                                                                                        {{ $data_course_to['course_class_locale_name'] }}
                                                                                        {{ trans('enum.course_class-class_time.' .  $data_course_to['class_time']) }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <p>&nbsp;</p>
                                                </div>
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
    </tbody>
</table>
@endsection
