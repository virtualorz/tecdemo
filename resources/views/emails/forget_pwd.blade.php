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

                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        Email Address
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ $data_member['email'] }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="180" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        Your Password
                                                                                    </p>
                                                                                </th>
                                                                                <td height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p>
                                                                                        <span style="font:24px Courier New; color:#337AB7; background-color: #ffff00;">{{ $newPwd }}</span>
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" height="60" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        請以新密碼登入會員，再變更您的密碼
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
