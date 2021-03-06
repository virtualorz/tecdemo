<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>{{ User::get('name')}} E-Portfolio</title>
</head>

	<style>
		body {
    font-size: 10pt;
	font-family:Meiryo, Microsoft YaHei, Microsoft JhengHei, Helvetica, Geneva, 思源黑體, 微軟正黑體, Arial, sans-serif;
    letter-spacing: 0.09em;
    line-height: 1.3em;
		}
		.table{border-collapse:collapse; margin: auto}
		.table thead th{border: 1px solid #000; padding: 7.5px 15px}
		.table td{border:1px solid #000; padding: 7.5px 15px}
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
							<th class="ttw100 textcenter">活動日期<br><span class="small">Date</span></th>
							<th class="ttw160 textcenter">活動名稱<br><span class="small">Training</span></th>
							<th class="ttw120 textcenter">能力關聯<br><span class="small">Related ability</span></th>
							<th class="ttt50 textcenter">時數<br><span class="small">Hours</span></th>
							<th class="ttt50 textcenter">等級<br><span class="small">Level</span></th>
							<th class="ttt80 textcenter">認證<br><span class="small">Certificate</span></th>
							<th class="ttt50 textcenter">分數<br><span class="small">Score</span></th>
							<th class="ttt50 textcenter">學分<br><span class="small">Credits</span></th>
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
