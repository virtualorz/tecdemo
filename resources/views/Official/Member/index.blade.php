@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
          	
          	
          	
            
            <div class="col-sm-9 col-xs-12 max767none">
				<h2 class="bigtitle">會員專區</h2>
              	<!--通知訊息-->
                @if(count($noticeResult) != 0)
           	  	<div class="tablebox">
				<h4 class="mb--b">通知訊息</h4>
             		<div class="table-responsive">
              		<table class="table table-striped"> 
               		<thead> 
                    	<tr> 
                   	  		<th class="ttw100">日期</th> 
                   	  		<th>主題</th>
							<th class="ttw80">狀態</th>
                   	    	<th class="ttw80">發訊者</th> 
                   	    </tr> 
                    </thead> 
                    
                    <tbody> 
                        @foreach($noticeResult as $k=>$v)
                    	<tr> 
                             <td>{{ $v['created_at'] }}</td> 
							<td>
                          	<a href="{{ asset('member/message/detail/id-'.$v['member_notice_log_id']) }}">{{ $v['title'] }}</a>
                           	</td>
                            <td><span class="label label-default">{{ trans('enum.is_read.'.$v['is_read']) }}</span></td> 
                            <td>{{ $v['create_admin_name'] }}</td>
                        </tr> 
                        @endforeach
                    </tbody> 
                </table>
					</div>
				</div>
                @endif
          
          		<!--未繳費紀錄-->
             @if(count($paymentResult) != 0)
       	  	  <div class="tablebox">
				<h4 class="mb--b">未繳費紀錄</h4>
             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
								<th class="ttw100">月份</th> 
								<th>名稱</th>
								<th class="ttw80">列印狀態</th>
								<th class="ttw80">繳費狀態</th>
								<th class="ttw50 text-center max767none">列印</th> 
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($paymentResult as $k=>$v)
							<tr> 
                              <td>{{ $v['pay_year'] }}.{{ $v['pay_month'] }}</td> 
								<td>
								<a href="#">{{ $v['pi_name'] }}</a>
								</td>
								<td><span class="label label-default">已列印</span></td>
								<td><span class="label label-default">已繳費</span></td>
								<td class="text-center max767none">
									<a href="#"><i class="fa fa-print"></i></a>
								</td>
							</tr>
                            @endforeach 
						</tbody> 
					</table>
				  	</div>
				</div>
                @endif
          
          		<!--預約活動-->
            @if(count($activityResult) != 0)
       	  	  <div class="tablebox">
				<h4 class="mb--b">預約活動</h4>
             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
							<th class="ttw100">日期</th> 
							<th>活動名稱</th>
							<th class="ttt80">等級</th>
							<th class="ttt80">時數</th> 
							<th class="ttw50 text-center">取消</th> 
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($activityResult as $k=>$v)
							<tr> 
                              <td>{{ $v['start_dt'] }}</td> 
								<td>
								<a href="#">{{ $v['activity_name'] }}</a>
								</td>
								<td>{{ $v['level'] }}</td>
								<td>{{ $v['time'] }}hr</td> 
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
           
           		<!--預約儀器-->
            @if(count($instrumentResult) != 0)
       	  	<div class="tablebox">
				<h4 class="mb--b">預約儀器</h4>
             		<div class="table-responsive">
              			<table class="table table-striped"> 
							<thead> 
								<tr> 
								<th class="ttw100">日期</th> 
								<th>儀器名稱</th>
								<th class="ttt80">時段</th>
								<th class="ttw50 text-center">取消</th> 
								</tr> 
							</thead> 
                    
							<tbody> 
                                @foreach($instrumentResult as $k=>$v)
								<tr> 
								  <td>{{ $v['reservation_dt'] }}</td> 
									<td>
									<a href="#">{{ $v['name'] }}</a>
									</td>
									<td>{{ $v['start_time'] }} - {{ $v['end_time'] }}</td>
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