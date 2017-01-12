@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">儀器預約記錄</h2>
              	
           		<!--預約儀器-->
				@if(count($instrumentResult) != 0)
       	  		<div class="tablebox">
				<h4 class="mb--b">預約中</h4>
             		<div class="table-responsive">
              			<table class="table table-striped"> 
							<thead> 
								<tr>
								  	<th class="ttw100">預約單號</th>
								  	<th class="ttw100">日期</th>
								  	<th class="ttt100">時段</th>
								  	<th class="ttw100">服務編號</th> 
									<th class="ttt160">儀器名稱</th>
									<th class="ttw80">狀態</th>
									<th class="ttw50 text-center">取消</th> 
								</tr> 
							</thead> 
                    
							<tbody> 
                                @foreach($instrumentResult as $k=>$v)
								<tr>
                                  <td>{{ $v['reservation_dt_ym'] }}{{ $v['salt'] }}</td>
								  <td>{{ $v['reservation_dt'] }}</td>
								  <td>{{ $v['start_time'] }} - {{ $v['end_time'] }}</td>
								  <td>{{ $v['instrument_id'] }}</td> 
									<td>
									<a href="{{ asset('instrument/reservation/id-'.$v['instrument_uid'].'-'.$v['instrument_salt']) }}">{{ $v['name'] }}</a>
									</td>
									<td>
                                        @if($v['reservation_status'] === 0)
                                        <span class="label label-default">候補中</span>
                                        @elseif($v['reservation_status'] === 1)
                                        <span class="label label-success">預約中</span>
                                        @endif
                                    </td>
									<td class="text-center">
								  	<a href="#" class="cancel" data-id="{{ $v['instrument_reservation_data_id'].'_'.$v['create_date'] }}"> 
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
           		
           		<!--預約儀器-->
       	  		<div class="tablebox">
				<h4 class="mb--b">歷史紀錄</h4>
             		<div class="table-responsive">
              			<table class="table table-striped"> 
							<thead> 
								<tr>
								  	<th class="ttw100">預約單號</th>
								  	<th class="ttw100">日期</th>
								  	<th class="ttt100">時段</th>
								  	<th class="ttw100">服務編號</th> 
									<th class="ttt160">儀器名稱</th>
									<th class="ttw80">狀態</th>
								</tr> 
							</thead> 
                    
							<tbody> 
                                @foreach($historyResult as $k=>$v)
								<tr>
								  <td>{{ $v['reservation_dt_ym'] }}{{ $v['salt'] }}</td>
								  <td>{{ $v['reservation_dt'] }}</td>
								  <td>{{ $v['start_time'] }} - {{ $v['end_time'] }}</td>
								  <td>{{ $v['instrument_id'] }}</td> 
									<td>
									<a href="{{ asset('instrument/reservation/id-'.$v['instrument_uid'].'-'.$v['instrument_salt']) }}">{{ $v['name'] }}</a>
									</td>
									<td>
                                        @if(($v['reservation_status'] === 1 || $v['reservation_status'] === 0) && $v['attend_status'] == 0)
                                        <span class="label label-default">未出席</span>
                                        @elseif(($v['reservation_status'] === 1 || $v['reservation_status'] === 0) && $v['attend_status'] == 1)
                                        <span class="label label-success">已完成</span>
                                        @elseif($v['reservation_status'] === 2)
                                        <span class="label label-default">已取消</span>
                                        @elseif($v['reservation_status'] == null)
                                        <span class="label label-default">未候補</span>
                                        @endif
                                    </td>
								</tr> 
                                @endforeach
							</tbody> 
						</table>
				  	</div>
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