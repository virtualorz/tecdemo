@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">活動參與紀錄</h2>
              	
          
          		<!--預約活動 預約中-->
              @if(count($activityResult) != 0)
       	  	  <div class="tablebox">
				<h4 class="mb--b">預約中</h4>
             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
							<th class="ttw100">日期</th>
							<th class="ttw100">活動編號</th> 
							<th>活動名稱</th>
							<th class="ttt80">時數</th>
                            <th class="ttw80 text-center">功能</th>
							<th class="ttw50 text-center">取消</th> 
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($activityResult as $k=>$v)
							<tr> 
							  	<td>{{ $v['start_dt'] }}</td>
							  	<td>{{ $v['activity_id'] }}</td> 
								<td>
								<a href="{{ asset('activity/activity/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['activity_name'] }}</a>
								</td>
								<td>{{ $v['time'] }}hr</td>
                                <td class="text-center">
								  	<a href="member_activity_reg.html"> 
									<i class="fa fa-pencil" aria-hidden="true"></i> 補登記
									</a>
								</td>
								<td class="text-center">
									<a href="#"> 
									<i class="fa fa-times" aria-hidden="true"></i>
									</a>
								</td>
							</tr> 
                            @endforeach
						</tbody> 
					</table>
				  	</div>
				</div>
                @endif
           
           		<!--預約活動 歷史紀錄-->
       	  	  	<div class="tablebox">
				<h4 class="mb--b">歷史紀錄</h4>
             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
							<th class="ttw100">日期</th>
							<th class="ttw100">活動編號</th> 
							<th>活動名稱</th>
							<th class="ttt80">時數</th>
							<th class="ttw80">狀態</th>
							<th class="ttw80 text-center">功能</th> 
							</tr> 
						</thead> 

						<tbody> 
							<tr> 
							  	<td>2014.6.2</td>
							  	<td>P12345645</td> 
								<td>
								<a href="#">EndNote 書目管理軟體工作坊</a>
								</td>
								<td>1.5hr</td>
								<td><span class="label label-default">已結束</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 

							<tr> 
							  <td>2014.5.29</td>
							  <td>P45598632</td> 
								<td><a href="#">蛋白質質譜比對軟體使用工作坊</a></td>
								<td>3hr</td>
								<td><span class="label label-success">已通過</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 

							<tr> 
							  <td>2014.5.21</td>
							  <td>P17213245</td> 
								<td><a href="#">高解析度活細胞影像系統</a></td>
								<td>3hr</td>
								<td><span class="label label-warning">活動取消</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 
							
							<tr> 
							  <td>2014.5.21</td>
							  <td>P98754321</td> 
								<td><a href="#">高解析度活細胞影像系統</a></td>
								<td>3hr</td>
								<td><span class="label label-info">審核中</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 
							
							<tr> 
							  <td>2014.5.21</td>
							  <td>A25647891</td> 
								<td><a href="#">高解析度活細胞影像系統</a></td>
								<td>3hr</td>
								<td>
								<span class="label label-danger">未登記</span>
								</td>
								<td class="text-center">
								  	<a href="member_activity_reg.html"> 
									<i class="fa fa-pencil" aria-hidden="true"></i> 補登記
									</a>
								</td>
							</tr> 
							
							<tr> 
							  	<td>2014.6.2</td>
							  	<td>TC3161109</td> 
								<td>
								<a href="#">EndNote 書目管理軟體工作坊</a>
								</td>
								<td>1.5hr</td>
								<td><span class="label label-default">已結束</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 

					
							<tr> 
							  	<td>2014.6.2</td>
							  	<td>P12345645</td> 
								<td>
								<a href="#">EndNote 書目管理軟體工作坊</a>
								</td>
								<td>1.5hr</td>
								<td><span class="label label-default">已結束</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 
							
							<tr> 
							  	<td>2014.6.2</td>
							  	<td>P12345645</td> 
								<td>
								<a href="#">EndNote 書目管理軟體工作坊</a>
								</td>
								<td>1.5hr</td>
								<td><span class="label label-default">已結束</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 
					  	
					  		<tr> 
							  	<td>2014.6.2</td>
							  	<td>P12345677</td> 
								<td>
								<a href="#">EndNote 書目管理軟體工作坊</a>
								</td>
								<td>1.5hr</td>
								<td><span class="label label-default">已結束</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 
						  	
						  	<tr> 
							  	<td>2014.6.2</td>
							  	<td>P12345645</td> 
								<td>
								<a href="#">EndNote 書目管理軟體工作坊</a>
								</td>
								<td>1.5hr</td>
								<td><span class="label label-default">已結束</span></td>
								<td class="text-center">&nbsp;</td>
							</tr> 


						</tbody> 
					</table>
				  	</div>
				
         		<nav aria-label="Page navigation" class="text-center">
				  <ul class="pagination">
					<li>
					  <a href="#" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					  </a>
					</li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li>
					  <a href="#" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					  </a>
					</li>
				  </ul>
				</nav>

          		</div>
           		
           		
           			<div class="row">
					<div class="col-sm-12 text-center min768none">
						 <a href="member.html" class="btn btn-default btn-sm"><i class="fa fa-angle-left"></i> 回會員專區</a>
					</div>
					</div>	
            </div>
    	</div>
    </div>
    
    <div class="spacer6030"></div>
@endsection

@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        

    });
    
</script>
@endsection