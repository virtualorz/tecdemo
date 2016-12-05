<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Ladouce訂購成功確認信件</title>
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
                          <td height="75" valign="middle"><span style="color:#fff;font-size:22px">Ladouce</span></td>
                        </tr>
                        </tbody>
                        </table>
                        <table border="0" cellspacing="0" cellpadding="0" align="right">
                        <tbody>
                        <tr>
                        <td height="75" align="right" style="color:#fff; padding-right:25px">{{$data_email_send['title']}}</td>
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


<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  	<tbody>
        <tr>
        <td align="center">
        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
            <tbody>
                <tr>
                    <td>
                    <table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0">
                    
                        <tbody>
                        <tr>
                          <td>
                             <p style="font:14px/24px Helvetica,Arial,sans-serif;color:#4a4a4a"><br>
                             親愛的朋友您好，很高興您訂購Ladouce的產品，以下為這次訂購的品項及金額<br>
                             @foreach($data_email_send['description'] as $k=>$v)
                             {{$v}}<br>
                             @endforeach
                             <br>
                             總消費金額為 : {{$data_email_send['total']}} 元
                             <br>
                             ，商品將於您轉帳成功後兩日內寄出，謝謝您的支持。
                             <br>
                             </p>
                              
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
                        	<td height="25" align="center" style="font:11px Helvetica,Arial,sans-serif;color:#ffffff">Ladouce
email：customerservice@ladouce.com.tw</td>
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
