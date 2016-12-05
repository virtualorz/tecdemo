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



                                                                    @if(count($list_apply_item) > 0)
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2" align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        所報名的課程資訊
                                                                                    </p>
                                                                                </td>
                                                                            </tr> 
                                                                            @foreach($list_apply_item as $k => $v)
                                                                            <tr>
                                                                                <td colspan="2" align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        第{{ $k + 1 }}筆
                                                                                    </p>
                                                                                </td>
                                                                            </tr>

                                                                            <tr>  
                                                                                <td width="50%" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        課程代碼：{{ $v['course_code'] }}
                                                                                    </p>
                                                                                </td> 
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        課程名稱：{{ $v['course_name_cht'] }}({{ $v['course_name_eng'] }})
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        上課時間：
                                                                                        {{ $v['course_class_locale_name'] }} - {{ trans('enum.course_class-class_time.' . $v['class_time']) }}
                                                                                        <br />
                                                                                        上課日期：
                                                                                        @if($v['date_unlimited'] == 1)
                                                                                        {{ trans('validation.attributes.course_class-date_unlimited') }}
                                                                                        @else
                                                                                        {{ $v['date_start'] }} ~ {{ $v['date_end'] }}
                                                                                        @endif
                                                                                    </p>
                                                                                </td>
                                                                            </tr> 
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <br />
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        總金額
                                                                                    </p>
                                                                                </th>
                                                                            </tr> 
                                                                            <tr>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        NT$ {{ $data_apply['fee'] }}元
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br />
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        付款方式
                                                                                    </p>
                                                                                </th>
                                                                            </tr> 
                                                                            <tr>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        {{ trans('enum.apply_item-pay_way.' . $data_apply['pay_way']) }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br />

                                                                    @else    
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        查無資料
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    @endif



                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th colspan="2" align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        學員資料
                                                                                    </p>
                                                                                </th>
                                                                            </tr> 
                                                                            @if(count($data_member) > 0)
                                                                            <tr>  
                                                                                <td width="50%" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        學員姓名：{{ $data_member['name_cht'] }}
                                                                                    </p>
                                                                                </td> 
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        聯繫電話：
                                                                                        {{ $data_member['phone'] }}
                                                                                        @if($data_member['phone_ext'] != '') #{{ $data_member['phone_ext'] }}
                                                                                        @endif
                                                                                    </p>
                                                                                </td>
                                                                            </tr> 
                                                                            <tr> 
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        學員電郵：<a href="mailto:{{ $data_member['email'] }}">{{ $data_member['email'] }}</a>
                                                                                    </p>
                                                                                </td> 
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        手機聯繫號碼：{{ $data_member['cellphone'] }}
                                                                                    </p>
                                                                                </td> 
                                                                            </tr>
                                                                            <tr>                
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        公司名稱/學校名稱：{{ $data_member['work_name'] }}
                                                                                    </p>
                                                                                </td>                
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        &nbsp;
                                                                                    </p>
                                                                                </td>              
                                                                            </tr> 
                                                                            @else
                                                                            <tr>  
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        查無資料
                                                                                    </p>
                                                                                </td> 
                                                                            </tr> 
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                    <br />
                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th colspan="2" align="center"  valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        條款與條件
                                                                                    </p>
                                                                                </th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="150" nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    智慧財產權
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        所有智慧財產權皆屬於TP、Dimension Data或其授權人之專有財產，包括與此課程或課程中所提供或可取得之課程資料相關之版權、專利權以及設計權。此課程材料無論其全部或部分皆不得複製，除非事先已明確獲得TP書面同意。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    課程先決條件
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        公司及／或參加人必須確定其符合課程說明中之上課先決條件。*TP無須為不符合此先決條件之參加人於此課程中所面臨之困難負責。至於已經開始之課程，客戶若因不符合此課程之先決條件而要求變更課程，TP保留收取課程費用之權利，最高可收取新台幣$25,000元。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    確認課程
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        本公司將課程確認書寄給參加人之前，此課程時間表僅供暫時參考之用。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    取消課程
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        *若因無法預見之狀況而未達到所規定之最小班級人數，TP保留取消此課程之權利而無須為此負責。若課程取消，參加人事先會收到至少3個工作日以上之書面取消通知。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    此政策之例外情形
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        以優惠價格登記之課程不可延期或取消，但公司可另換參加人。個人參加人可選擇其他日期類似之課程，參加人若缺席，課程費用可能被沒收。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    付款條件
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        註冊手續確認後，即須於開課前五個工作日內付清所有款項。課程／考試費用須加收本地政府規定之所有稅金，例如貨物服務稅／加值稅。所有費用付清之後，席位即可保留。TP保留拒收之權利，參加人若未付清課程費用，TP可拒收至其付清所有費用為止。付款收據會寄給參加人。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    上課
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        註冊後12月之內，參加人必須在所有課程／考試中出席。若缺席而未完成課程／考試則不得退費，但可更換考生或另選擇類似／相等價值之課程／考試。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    申請延期聽課
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        收到書面通知之日期：<br />
                                                                                        超過10個工作日以上 – 不罰款<br />
                                                                                        在10個工作日以內 – 最高可收取新台幣$25,000元之罰金<br />
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    退費
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        收到書面通知之日期：<br />
                                                                                        超過10個工作日以上 – 不罰款<br />
                                                                                        在10個工作日以內 –  退費50%<br />
                                                                                        在3個工作日以內／且未出席 – 100%退費<br />
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    付款方式
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        支票付款：付給 台灣岱凱系統(股)公司.<br />
                                                                                        電匯付款：<br />
                                                                                        戶名 – 台灣岱凱系統(股)公司<br />
                                                                                        帳號 – 351-6503574（台幣）<br />
                                                                                        銀行代碼：076<br />
                                                                                        分行代號：0018<br />
                                                                                        銀行名稱：美商摩根大通銀行台北分行<br />
                                                                                        銀行地址：台北市信義路五段106號9樓<br /><br />

                                                                                        Beneficiary A/C Name – Dimension Data Taiwan Limited<br />
                                                                                        Beneficiary A/C # – 351-7902338（USD savings）<br />
                                                                                        SWIFT CODE：CHASTWTX<br />
                                                                                        Bank Name：JPMorgan Chase Bank N.A. Taipei Branch<br />
                                                                                        Bank Address：9F, No. 106, Xin Yi Rd., Sec 5, Taipei 11047, Taiwan, R.O.C.
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    責任限度
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        *無論求償者基於任何理由提出索賠，例如違反合約、違反保證或侵權，包括疏忽大意之情形，TP對所有索賠之總計／累積責任應限於求償者付予*TP之課程／考試費用。*TP無須為求償者所有與此課程有關之間接、懲罰、特別、偶然或必然之傷害負責。
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th nowrap="nowrap" valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    個人資料保護
                                                                                </th>
                                                                                <td valign="top" style="border-right:1px solid #ddd; border-bottom:1px solid #ddd; padding-left:5px">
                                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                                        1.	TP及本網站管理者台灣岱凱系統(股)公司將依據個人資料保護法及相關法令之規定，就台端提供之個人資料(包含但不限於C001辨識個人者、C002辨識財務者、C003政府資料中之辨識者、C038職業、C061受雇情形等)，為下列特定目的而為蒐集、處理及利用。<br />
                                                                                        069-契約關係<br />
                                                                                        090-消費者、客戶管理與服務<br />
                                                                                        152-廣告或商業行為管理<br />
                                                                                        157-調查、統計與研究分析<br />
                                                                                        158-學生資料管理<br />
                                                                                        2. 台端得依個人資料保護法，向TP及本網站管理者台灣岱凱系統(股)公司行使下列權利：<br />
                                                                                        (1)查詢或請求閱覽。<br />
                                                                                        (2)請求製給複製本。<br />
                                                                                        (3)請求補充或更正。<br />
                                                                                        (4)請求停止蒐集、處理及利用。<br />
                                                                                        (5)請求刪除。<br />
                                                                                        TP客服專線：0800-808-117
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a">
                                                                        *TP同時包括Dimension Data。在亞洲某些地區，TP是以Dimension Data公司贊助之下從事相關業務。<br />
                                                                        凡此條款與條件，*TP均可隨時變更。
                                                                    </p>
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




