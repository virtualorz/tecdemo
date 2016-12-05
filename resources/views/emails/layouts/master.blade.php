<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ $subject or '' }}</title>
    </head>
    <body>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tbody><tr>
                    <td height="39">&nbsp;</td>
                </tr>
            </tbody>
        </table>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tbody>
                <tr>
                    <td align="center">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tbody>
                                <tr>
                                    <td>
                                        <table width="100%" bgcolor="#000000" border="0" cellspacing="0" cellpadding="0" align="center" style="border-radius:5px 5px 0 0;background-color:#000000">
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <table border="0" cellspacing="0" cellpadding="0" align="left">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="15" height="75">&nbsp;</td>
                                                                    <td height="75" valign="middle"><img src="{{ asset('assets/official/img/logo_email.jpg') }}" width="240" height="30" alt=""/></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table border="0" cellspacing="0" cellpadding="0" align="right">
                                                            <tbody>
                                                                <tr>
                                                                    <td height="75" align="right" style="color:#fff; padding-right:25px">{{ $subject or '' }}</td>
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
                    </td>
                </tr>
            </tbody>
        </table>
        
        @yield('content')

        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tbody>
                <tr>
                    <td align="center">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tbody>
                                <tr>
                                    <td>
                                        <table width="100%" bgcolor="#464646" border="0" cellspacing="0" cellpadding="0" align="center" style="border-radius:0 0 7px 7px">
                                            <tbody>
                                                <tr>
                                                    <td height="18">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td height="25" align="center" style="font:11px Helvetica,Arial,sans-serif;color:#ffffff">TP國際網路認證學院 • 服務專線：02-2171-1500 • 地址：台北市105南京東路五段188號2樓之2</td>
                                                </tr>
                                                <tr>
                                                    <td height="18">&nbsp;</td>
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
    </body>
</html>