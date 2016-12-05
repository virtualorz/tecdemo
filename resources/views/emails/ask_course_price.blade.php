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
                                                                    @if(count($data_contact_ask_course_price) > 0)
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-name_cht') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['name_cht'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-work_name') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['work_name'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-title') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['title'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <?php /*
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-email') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['email'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                             */ ?>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-phone') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['phone'] }}
                                                                                        @if($data_contact_ask_course_price['phone_ext'] != '') #{{ $data_contact_ask_course_price['phone_ext'] }}
                                                                                        @endif
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-cellphone') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['cellphone'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-know_from') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('enum.contact_ask_course_price-know_from.' .  $data_contact_ask_course_price['know_from']) }}
                                                                                        @if($data_contact_ask_course_price['know_from'] == 8){{ $data_contact_ask_course_price['know_from_etc'] }}
                                                                                        @endif
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('validation.attributes.contact_ask_course_price-course_id') }}
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_contact_ask_course_price['course_name_cht'] }}({{ $data_contact_ask_course_price['course_name_eng'] }})
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
