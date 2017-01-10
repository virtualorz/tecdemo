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
                                    @if($v['reason'] == null)
								  	<a href="{{ asset('member/activity/reg/id-'.$v['uid'].'-'.$v['salt']) }}"> 
									<i class="fa fa-pencil" aria-hidden="true"></i> 補登記
									</a>
                                    @endif
								</td>
								<td class="text-center">
									<a href="#" class="cancel" data-id="{{ $v['id'].'_'.$v['created_at'] }}"> 
									<i class="fa fa-times" aria-hidden="true"></i>
									</a>
								</td>
							</tr> 
                            @endforeach
						</tbody> 
					</table>
                    <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('cancel')->getUrl() }}">
                        <input type='hidden' name='id' id='cancel_id'>
                    </form>
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
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($historyResult as $k=>$v)
							<tr> 
							  	<td>{{ $v['start_dt'] }}</td>
							  	<td>{{ $v['activity_id'] }}</td> 
								<td>
								<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['activity_name'] }}</a>
								</td>
								<td>{{ $v['time'] }}hr</td>
								<td>
                                    @if($v['reservation_status'] == 0)
                                    <span class="label label-warning">預約取消</span>
                                    @elseif($v['reservation_status'] == 1 && $v['attend_status'] == 0)
                                    <span class="label label-danger">未出席</span>
                                    @elseif($v['reservation_status'] == 1 && $v['attend_status'] == 1 && $v['pass_status'] == 1)
                                    <span class="label label-success">已通過</span>
                                    @elseif($v['reservation_status'] == 1 && $v['attend_status'] == 1 && $v['pass_status'] == 0 && $v['pass_type'] == 2)
                                    <span class="label label-info">審核中</span>
                                    @endif

                                </td>
							</tr> 
                            @endforeach

						</tbody> 
					</table>
				  	</div>
				
         		@include('official.elements.pagination')

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
        initValidation();
        urlBack = location.href;
        $(".cancel").click(function(e){
            e.preventDefault();
            
            $("#cancel_id").val($(this).attr('data-id'));
            $("#form1").submit();

            $(this).parent().parent().animate({
                opacity: 0,
            }, 1000, function() {
                // Animation complete.

            });
        });
    });

    function initValidation() {
        $('#form1').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                }) === false) {
                    return false;
                }
            }
        });
    }
    
</script>
@endsection