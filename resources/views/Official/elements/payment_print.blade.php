<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>eportfolio</title>
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
       	  	  	TechComm 科技共同空間繳費單<br>
       	  	  	<span class="small">國立臺灣大學生命科學院科技共同空間</span>
       	  	  	</h2>
				
				
       	  	  	  <div style="width: 1000px; margin: auto; margin-top: 40px">
				  <p class="clearfix">
				  	帳單月份：{{ $dataResult['pay_year'] }}年{{ $dataResult['pay_month'] }}月<br>
					應繳金額：{{ $dataResult['total'] }}元<br>
					單號：{{ date('ym',strtotime($dataResult['pay_year'].'-'.$dataResult['pay_month'].'-01')) }}{{ $dataResult['salt'] }}<br>
					使用單位：{{ $dataResult['organize_name'] }}/{{ $dataResult['department_name'] }}<br>
					計畫主持人：{{ $dataResult['pi_name'] }}
            	  </p>
				  </div>
				  
				  
					<h3 class="mb--b textcenter">使用紀錄</h3>            		
             		<div class="table-responsive" style="width: 1000px; margin: auto">
              		<table class="table table-striped" cellspacing="0" cellpadding="0"> 
						<thead> 
							<tr>
							
							<th class="ttw80 textcenter">繳費代碼</th> 
							<th class="ttw80 textcenter">日期<br></th>
							<th class="ttt100 textcenter">時段</th>
							<th class="ttt80 textcenter">儀器名稱</th>
							<th class="ttt50 textcenter">使用人</th>
							<th class="ttt50 textcenter">折扣</th>
							<th class="ttt50 textcenter">使用費小計</th>
							</tr>
						</thead> 

						<tbody> 
                            @foreach($reservationlogResult as $k=>$v)
							<tr> 
                                <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
							  	<td>{{ $v['date'] }}</td>
							  	<td>{{ $v['use_dt_start'] }}-{{ $v['use_dt_end'] }}</td>
							  	<td>
								{{ $v['instrument_name'] }}
								</td>
							  	<td>{{ $v['member_name'] }}</td>
                                <td>
									@if($v['discount_JSON'] != '')
									{{ $discount_type[$v['discount_JSON']['type']] }} : <br>
                                    {{ $v['discount_JSON']['number'] }}
									@endif
								</td>
								<td>{{ $v['pay'] }}</td>
							</tr> 
                            @endforeach
						</tbody> 
					</table>
				  	</div>
				  	
				  	
				  	
				  	<h3 class="mb--b textcenter">耗材花費</h3>            		
             		<div class="table-responsive" style="width: 1000px; margin: auto">
              		<table class="table table-striped" cellspacing="0" cellpadding="0"> 
						<thead> 
							<tr>
							
							<th class="textcenter" width="200">繳費代碼</th>
							<th class="textcenter" width="200">項目</th>
							<th class="textcenter" width="200">使用數量</th>
							<th class="textcenter" width="200">材料費小計</th>
							</tr>
						</thead> 

						<tbody> 
                            @foreach($reservationlogResult as $k=>$v)
                                @foreach($v['supplies_JOSN'] as $k1=>$v1)
                                <tr>
                                    <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
                                    <td>{{ $v1['name'] }}</td>
                                    <td>{{ $v1['count'] }}個</td>
                                    <td>{{ $v1['total'] }}</td>
                                </tr>
                                @endforeach
                            @endforeach
						</tbody> 
					</table>
				  	</div>
				  	
				  	


</body>
</html>
