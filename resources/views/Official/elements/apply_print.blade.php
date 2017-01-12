<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>{{ $memberResult['name'] }} 資料申請表</title>
</head>

	<style>
		body {
    font-size: 10pt;
	font-family:Meiryo, Microsoft YaHei, Microsoft JhengHei, Helvetica, Geneva, 思源黑體, 微軟正黑體, Arial, sans-serif;
    letter-spacing: 0.09em;
    line-height: 1.3em;
		}
		.table{border-collapse:collapse; margin: auto}
		.table thead th{border: 1px solid #000; padding: 7.5px 11px}
		.table td{border:1px solid #000; padding: 7.5px 11px}
		.small {font-size: 10px}
	
		.floatleft{float: left}
		.floatright{float: right}
		
		.clearfix:before,.clearfix:after{content:"\0020";display:block;height:0;overflow:hidden}
		.clearfix:after{clear:both}
		.clearfix{clear: both;zoom:1}

		.color_blue{color: #1e6fa3;}
		.textcenter{text-align: center}
		
		.mb--b{margin-bottom: 15px}
		h3{ margin-top: 30px ;margin-bottom: 15px}
		
		/* table min-width */
		.ttt200{min-width:200px}
		.ttt160{min-width:160px}
		.ttt120{min-width:120px}
		.ttt110{min-width:110px}
		.ttt105{min-width:105px}
		.ttt100{min-width:100px}
		.ttt80{min-width:80px}
		.ttt50{min-width:50px}

		.ttw200{width:200px}
		.ttw160{width:160px}
		.ttw120{width:120px}
		.ttw110{width:110px}
		.ttw105{width:105px}
		.ttw100{width:100px}
		.ttw80{width:80px}
		.ttw50{width:50px}
	</style>


<body>
       	  	  
       	  	  	<h2 class="textcenter">
       	  	  	<img src="{{asset('assets/official/img/logotc.png')}}" width="130" height="53" alt=""/><br><br>
       	  	  	科技共同空間電子護照申請表<br>
       	  	  	</h2>
				
				             		
             		<div class="table-responsive" style="width: 700px; margin: auto">
              		<table class="table table-striped" cellspacing="0" cellpadding="0"> 
						<thead> 
							<tr>
							
							<th colspan="3">本人遞交本表時，同意臺大生科院科技共同空間之個人資料處理方式備註</th>
							</tr>
						</thead> 

						<tbody> 
							<tr>
							  	<td rowspan="5" width="15"><strong>申請護照資訊</strong></td>
                                  <td width="300">姓名： {{ $memberResult['name'] }}</td>
							  	<td width="300">機關/學校： {{ $memberResult['organize_name'] }}</td>
							</tr>
							
							<tr>
							  <td>學號/證件卡號： {{ $memberResult['card_id_number'] }}</td>
							  <td>系所/部門： {{ $memberResult['department_name'] }}</td>
					  	  	</tr>
					  	  	
							<tr>
							  	<td>身分證字號： {{ $memberResult['id_number'] }}</td>
							  	<td>職稱： {{ $memberResult['title'] }}</td>
							</tr>
							
							<tr>
							  <td>電子郵件： {{ $memberResult['email'] }}</td>
							  <td>聯絡電話： {{ $memberResult['phone'] }}</td>
					  	  	</tr>
						  	
							<tr>
							  	<td>指導教授親簽：
					  	    	<br>
						  	    <br>
						  	    <br>
						  	    <br>
									<p style="font-size: 8pt">本人為申請者之指導教授或計畫主持人，並同意作為申請者使用費之請款對象。</p>
						  	    </td>
							  	
							  	<td>申請人簽名：
								<br>
						  	    <br>
						  	    <br>
						  	    <br>
									<p style="font-size: 8pt">本人了解 TechComm 個資聲明及服務條款，遵守實驗室安全守則、門禁規範及儀器使用規定。</p>
								</td>
							</tr> 
						</tbody> 
					</table>
				  	</div>
				  	<br>
				  	<br>
				  	
<p class="textcenter">工作記錄欄，以下交由管理單位審核、填寫</p>            	
				  	
             		<div class="table-responsive" style="width: 700px; margin: auto">
              		<table class="table table-striped" cellspacing="0" cellpadding="0"> 
						

						<tbody> 
							<tr>
							  	<td rowspan="6" width="15"><strong>管理系統核對</strong></td>
							  	<td width="250">指導教授 (結帳)：</td>
							  	<td width="400">□ 生科院 □ 台大校內 □ 校外學術 □ 校外產業</td>
							</tr>
							
							<tr>
							  <td>生效日期：</td>
							  <td>使用期限：</td>
						  	</tr>
							<tr>
							  	<td valign="top">
							  	□ 已建立卡片條碼<br>
							  	□ 已核對請款對象<br>
							  	□
							  	</td>
							  	<td>經手人核章：
							  	<br>
						  	    <br>
						  	    <br>
						  	    <br>
						  	    <br>
						  	    建檔日期：
						  	    <br>
						  	    <br>
							  	</td>
							</tr>
							
							<tr>
								<td colspan="2">身分轉換註記：
					    		<br>
					    		<br>
					    		<br>
					    		</td>
						    </tr>
						  	
						  	
							<tr>
							  <td colspan="2">其他註記： 
                                <br>
                                <br>
                                <br>
                              </td>
						  </tr>
							<tr>
							  	<td colspan="2">
							  		<br>
									<br>
									<br>
<br>
						  	    </td>
						  	</tr> 
						</tbody> 
					</table>
				  	</div>


</body>
</html>
