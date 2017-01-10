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
		.table thead th{border: 1px solid #000; padding: 15px}
		.table td{border:1px solid #000; padding: 15px}
		.small {font-size: 10px}
	
		.floatleft{float: left}
		.floatright{float: right}
		
		.clearfix:before,.clearfix:after{content:"\0020";display:block;height:0;overflow:hidden}
		.clearfix:after{clear:both}
		.clearfix{clear: both;zoom:1}

		.color_blue{color: #1e6fa3;}
		.textcenter{text-align: center}
		
		.mb--b{margin-bottom: 15px}
		h3{ margin-top: 60px ;margin-bottom: 15px}
	
	</style>


<body>
       	  	  
       	  	  	<h2 class="textcenter">
       	  	  	<img src="{{ asset('assets/official/img/logotc.png') }}" width="180" height="73" alt=""/><br><br>
       	  	  	臺大生科院科技共同空間活動記錄與認證<br>
       	  	  	<span class="small">Certification of participation and techniques</span>
       	  	  	</h2>
				
				
       	  	  
				  <h3 class="clearfix textcenter">
				 使用者姓名<span class="small">Name of user</span> : {{ User::get('name')}}
            	  </h3>
            	  
					<p class="mb--b textcenter">
					茲證明使用者參加臺大生科院科技共同空間各式活動記錄及能力認證<br>
					This is to certify the user has participated the following activities and passed related assessments.
				  	</p>
            	  
            		
             		<div class="table-responsive">
              		<table class="table table-striped" cellspacing="0" cellpadding="0"> 
						<thead> 
							<tr> 
							<th class="ttw100 text-center">活動日期<br><span class="small">Date</span></th>
							<th class="ttw160 text-center">活動名稱<br><span class="small">Training</span></th>
							<th class="ttw120 text-center">能力關聯<br><span class="small">Related ability</span></th>
							<th class="ttt50 text-center">時數<br><span class="small">Hours</span></th>
							<th class="ttt50 text-center">等級<br><span class="small">Level</span></th>
							<th class="ttt80 text-center">認證<br><span class="small">Certificate</span></th>
							<th class="ttt80 text-center">分數<br><span class="small">Score</span></th>
							<th class="ttt80 text-center">學分<br><span class="small">Credits</span></th>
							</tr>
						</thead> 

						<tbody> 
                            @foreach($activityResult as $k=>$v)
							<tr> 
							  	<td>{{ $v['start_dt'] }}</td>
							  	<td>
								<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['activity_name'] }}</a>
								</td>
							  	<td>{{ $v['plate_formResult_string'] }}</td>
							  	<td class="text-center">{{ $v['time'] }}</td>
							  	<td class="text-center">{{ $v['level'] }}</td>
								<td>出席 /<br>
                                @if($v['pass_status'] == 1)
							    通過 /<br>
                                @else
                                未通過 /<br>
                                @endif
                                @if($v['pass_type'] == 2 && $v['pass_status'] == 1)
							    考試後通過</td>
                                @endif
								<td class="text-center">{{ $v['pass_score'] }}</td>
								<td class="text-center">{{ $v['score'] }}</td>
							</tr>  
                            @endforeach
							
							<tr> 
							  	<td>2014.6.2</td>
							  	<td>EndNote 書目管理軟體工作坊</td>
							  	<td>TC3 蛋白質體</td>
							  	<td class="text-center">1.5</td>
							  	<td class="text-center">初階</td>
								<td>出席 /<br>
							    通過 /<br>
							    考試後通過</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							</tr> 
						</tbody> 
					</table>
				  	</div>
				  	
				  	
					<p class="textcenter small">Technology Commons, College of Life Science, National Taiwan University
                      <br>
                本證明請回到 TechComm 用印始證明生效 <br>
                The certification is valid after seal from TechComm
                	</p>


</body>
</html>