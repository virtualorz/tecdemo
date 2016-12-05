<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TP pdf</title>
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/bootstrap.min.css') }}" />
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/official/css/page.css') }}" />
    </head>

    <style>
        .wa{width:1024px}
        .wahead,wacontent,wafooter{width:100%}
        .wacontent{padding:20px}
        /* 文字色 */
        .color_red{color:#E54F0A;}
        .color_black{color:#000000;}
        .color_dgray{color:#444;}
        .color_gray{color:#666;}
        .color_white{color:#FFFFFF;}
        .color_dbrown{ color:#552b1c;}
        .color_brown{ color:#645046;}
        .color_yellow{color:#fac101;}
        .color_blue{color:#608BC4;}

        h1{ font-family:arial}
        h2{ margin:0;font-family:arial}
        p { font: 17px/180% Arial;}

        .wacontent .watable{ border-top:1px #666 solid;border-left:1px #666 solid}
        .wacontent .watable td{ border-bottom:1px #666 solid;border-right:1px #666 solid; padding:5px}

        .wacontent .watable td p{font-size:15px}



    </style>

    <body>
        <div class="wa">
            <div class="wahead">
                <img src="{{ asset('assets/official/img/watop.png') }}" width="1024" height="80" alt=""/> </div>

            <div class="wacontent">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td width="260" align="center" valign="middle">
                                @if($data_teacher['photo'] != false)
                                <img src="{{ $data_teacher['photo']['urlScale0'] }}" alt="" width="230"/>
                                @endif
                            </td>
                            <td align="left" valign="bottom">
                                <p class="color_black content-text">{{ $data_teacher['title'] }}</p>
                                <h1 class="color_blue">{{ $data_teacher['name_cht'] }} {{ $data_teacher['name_eng'] }}</h1>
                                <p class="color_red content-text">{{ $data_teacher['pro_title'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top">
                                <p class="color_black">&nbsp;</p>
                                <p class="color_black">認證：</p>
                                <p class="content-text">@nl2br($data_teacher['cert'])
                                    {{--
                                    @expr($flagFirst = true)
                                    @foreach($data_teacher['cert'] as $k => $v)
                                    @if($flagFirst)
                                    @expr($flagFirst = false)
                                    @else 
                                    、
                                    @endif
                                    {{ $v['course_cert_name'] }}
                                    @endforeach
                                    --}}
                                </p>

                                <br>
                                <br>
                                <p class="color_black">專長：</p>
                                <p class="content-text">
                                    @nl2br($data_teacher['skill'])
                                </p>



                                {{--
                                <ul>
                                    <li>
                                        <p>Joni Zeng has a good mix of experience in
                                            networking, software and system technologies for 11 years
                                        </p>
                                    </li>

                                    <li>
                                        <p>His working experience include System Integration, installation, maintenance as well as delivering technical training on Cisco, Juniper, Tandberg and Sun
                                        </p>
                                    </li>

                                    <li>
                                        <p>In Training Partners, in additional to his role on delivering technical training, he is also tasked to design, develop & deployed web/eLearning application for both internal & external needs
                                        </p>
                                    </li>
                                </ul> 
                                --}}
                            </td>
                        </tr>


                        {{--
                        <tr>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top">
                                <h2 class="color_blue">Training Capabilities</h2>
                                <p>Certified instructor for the following courses:</p>
                                <ul>
                                    <li>
                                        <p>Cisco</p>
                                    </li>
                                    <li>
                                        <p>Cisco Certified Networking Associate (CCNA) • ICND 1 & 2</p>
                                        <ul>
                                            <li>
                                                <p>CIPTV1 (Implementing Cisco IP Telephony and Video, Part 1)</p>
                                            </li>
                                            <li>
                                                <p>CIPTV2 (Implementing Cisco IP Telephony and Video, Part 2)</p>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <p>CCNAX</p>
                                    </li>
                                    <li>
                                        <p>Cisco Certified Voice Professional</p>
                                    </li>
                                    <li>
                                        <p>CIPTV1 (Implementing Cisco IP Telephony and Video, Part 1)</p>
                                    </li>
                                    <li>
                                        <p>CIPTV2 (Implementing Cisco IP Telephony and Video, Part 2)</p>
                                    </li>
                                </ul>


                                <br>
                                <br>

                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top">



                                <table class="watable" width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td height="30" align="center" valign="middle" bgcolor="#282828">
                                                <p class="color_white">Certifications and Trainings </p>
                                            </td>
                                            <td height="30" align="center" valign="middle" bgcolor="#282828">
                                                <p class="color_white">Vendor </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td height="30" align="left" valign="top">
                                                <p>Cisco Certified Systems Instructor (CCSI) (CCSI#31629) </p>
                                            </td>
                                            <td height="30" align="left" valign="top"><p>Sun Mircosystem</p></td>
                                        </tr>


                                        <tr>
                                            <td height="30" align="left" valign="top" bgcolor="#E8E8E8"><p>Cisco Certified Network Associate (CCNA) - R&S, Video , DataCenter, Security</p></td>
                                            <td height="30" align="left" valign="top" bgcolor="#E8E8E8"><p>TANDBERG</p></td>
                                        </tr>


                                        <tr>
                                            <td height="30" align="left" valign="top"><div title="Page 5">
                                                    <div>
                                                        <div>
                                                            <div>
                                                                <p>Cisco Certified Systems Instructor (CCSI) (CCSI#31629) </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div></td>
                                            <td height="30" align="left" valign="top"><p>Sun Mircosystem</p></td>
                                        </tr>


                                    </tbody>
                                </table>



                            </td>
                        </tr>
                        --}}
                    </tbody>
                </table>

            </div>

            <div class="wafooter">
                <img src="{{ asset('assets/official/img/wafotter.png') }}" alt="" width="1024" height="60"/> </div>
        </div>
    </body>
</html>
