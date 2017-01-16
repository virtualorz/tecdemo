@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('Official.elements.member_menu')
          	
          	
          	
            
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
                          	<a href="{{ asset('member/message/detail/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['title'] }}</a>
                           	</td>
                            <td><span class="label label-{{trans('enum.label.is_read.'.$v['is_read'])}}">{{ trans('enum.is_read.'.$v['is_read']) }}</span></td> 
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
								<a href="{{ asset('member/bill/detail/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['pi_name'] }}</a>
								</td>
								<td>
									@if($v['print_member_id'] == null)
									<span class="label label-success">未列印</span>
									@else
									<span class="label label-default">已列印</span>
									@endif
								</td>
								<td>
									@if($v['payment_count'] != 0)
									<span class="label label-default">已繳費</span>
									@else
									<span class="label label-success">未繳費</span>
									@endif
								</td>
								<td class="text-center max767none">
									@if($v['create_admin_id'] != null && $v['payment_count'] == 0)
									<a href="{{ asset('member/bill/detail/print/id-'.$v['uid'].'-'.$v['salt']) }}"><i class="fa fa-print"></i></a>
									@endif
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
								<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['activity_name'] }}</a>
								</td>
								<td>{{ $v['level'] }}</td>
								<td>{{ $v['time'] }}hr</td> 
								<td class="text-center">
									<a href="#" class="cancel_activity" data-id="{{ $v['id'].'_'.$v['created_at'] }}"> 
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
									<a href="{{ asset('instrument/reservation/id-'.$v['instrument_uid'].'-'.$v['instrument_salt']) }}">{{ $v['name'] }}</a>
									</td>
									<td>{{ $v['start_time'] }} - {{ $v['end_time'] }}</td>
									<td class="text-center">
								  	<a href="#" class="cancel_instrument" data-id="{{ $v['instrument_reservation_data_id'].'_'.$v['create_date'] }}"> 
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
	<form id="form1" method="post" action="">
    	<input type='hidden' name='id' id='cancel_id'>
    </form>
    
    <div class="spacer6030"></div>
@endsection

@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        urlBack = location.href;
        $(".cancel_activity").click(function(e){
            e.preventDefault();
            $("#form1").attr('action',"{{ Sitemap::node()->getChildren('cancel_activity')->getUrl() }}");
            $("#cancel_id").val($(this).attr('data-id'));
            $("#form1").submit();

            $(this).parent().parent().animate({
                opacity: 0,
            }, 1000, function() {
                // Animation complete.
				location.reload();
            });
        });

		$(".cancel_instrument").click(function(e){
            e.preventDefault();
            $("#form1").attr('action',"{{ Sitemap::node()->getChildren('cancel_instrument')->getUrl() }}");
            $("#cancel_id").val($(this).attr('data-id'));
            $("#form1").submit();

            $(this).parent().parent().animate({
                opacity: 0,
            }, 1000, function() {
                // Animation complete.
				location.reload();
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