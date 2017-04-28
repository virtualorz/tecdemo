@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">            
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
                <h3 class="bigtitle">{{ $dataResult['name'] }}</h3>
				
       	  	  <div id="instrument_reservation" class="tablebox">
					<h4 class="mb--b">{{ $dataResult['instrument_id'] }}</h4>
			  	
				  	<p class="mb--l">
				  		{{ $dataResult['function'] }}
           		    </p>
            		      		
            		      		
            		      	<div class="row">	
            		      		<div class="col-md-6">
				  				<div class="mb--b text-center">
									<input type="date" class="form-control urlinput" id="search_date" value="{{ Request::input('search_date', '') }}">
									<a href="#" class="btn btn-primary btn-sm addbtn" id="search_btn">
									搜尋
									</a>
				  				</div>
								</div>
				  			</div>
           		      		
           		      		
            		      		<h5 class="mb--s mt--b color_dgray">
									2個月內 ({{ $total_start_dt }} - {{ $total_end_dt }}) 的預約狀況：
            		      		</h5>
				  				<div class="text-center pt-20 pb-20 bg-light instrument_date">
									<h4>
										<a href="#" class="week_btn" data-start_dt="{{ $start_dt_prev }}" data-end_dt="{{ $end_dt_prev }}">
											<i class="fa fa-chevron-circle-left"></i>
										</a>
										{{ $start_dt }} - {{ $end_dt }}
										<a href="#" class="week_btn" data-start_dt="{{ $start_dt_next }}" data-end_dt="{{ $end_dt_next }}">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
									</h4>
				  				</div>
            		      		
             		      		<div class="table-responsive">
       							<table class="table table-bordered"> 
									<thead> 
										<tr>
                                        <th class="ttw100 text-center">時段</th>
                                        @for($i=0;$i<7;$i++)
                                            <th class="ttw80 text-center">{{ trans('enum.week_name.'.$i) }}<br>
                                            {{ date('Y/m/d',strtotime('+'.$i.' days',strtotime($start_dt_org))) }}</th>
                                        @endfor
										</tr> 
									</thead> 

									<tbody>
                                        @foreach($sectionResult as $k=>$v) 
										<tr>
                                            <td>{{ $v['start_time'] }}-{{ $v['end_time'] }}</td>
                                            @for($i=0;$i<7;$i++)
                                            <td>
                                                @if(!isset($v['reservation_log']))
                                                    @if(!isset($v['can_use']) || in_array(date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))),$vacationResult))
                                                    尚未開放
                                                    @else
                                                    <a href="#" class="btn btn-info reservation" data-id="1_{{$v['id']}}_{{ date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))) }}_{{ $dataResult['id'] }}" 
                                                        @if($reservation_count >= $dataResult['reservation_limit'] 
                                                            || strtotime('+'.$i.' days',strtotime($start_dt_org)) < strtotime(date('Y-m-d')) 
                                                            || strtotime(date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))).' '.$v['start_time']) < strtotime(date('Y-m-d H:i'))) disabled @endif
                                                    >預約</a>
                                                    @endif
                                                @else 
                                                    @if(array_key_exists(date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))),$v['reservation_log']))
                                                        @if($v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['attend_status'] == '1')
                                                        {{$v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['member_name']}} 使用中
                                                        @elseif($v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['reservation_status'] == '1' || $v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['reservation_status'] == '0')
                                                            @if($v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['member_id'] == User::Id())
                                                            <a href="#" class="btn btn-default reservation" data-id="0_{{$v['id']}}_{{ date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))) }}_{{ $dataResult['id'] }}" 
                                                                @if(strtotime('+'.$i.' days',strtotime($start_dt_org)) <= strtotime($dataResult['cancel_limit_dt'])) disabled @endif
                                                            >取消</a>
                                                            @else
                                                                {{$v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['member_name']}} 已預約
                                                                <!--
                                                                @if(!isset($v['can_use']) || in_array(date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))),$vacationResult) || User::id() == null)
                                                                {{$v['reservation_log'][date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org)))]['member_name']}} 已預約
                                                                @else
                                                                <a href="#" class="btn btn-default reservation" data-id="1_{{$v['id']}}_{{ date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))) }}_{{ $dataResult['id'] }}" 
                                                                    @if($reservation_count >= $dataResult['reservation_limit'] || strtotime('+'.$i.' days',strtotime($start_dt_org)) < strtotime(date('Y-m-d'))) disabled @endif
                                                                >候補</a>
                                                                @endif
                                                                -->
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if(!isset($v['can_use']) || in_array(date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))),$vacationResult))
                                                        尚未開放
                                                        @else
                                                        <a href="#" class="btn btn-info reservation" data-id="1_{{$v['id']}}_{{ date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))) }}_{{ $dataResult['id'] }}" 
                                                            @if($reservation_count >= $dataResult['reservation_limit'] 
                                                                || strtotime('+'.$i.' days',strtotime($start_dt_org)) < strtotime(date('Y-m-d')) 
                                                                || strtotime(date('Y-m-d',strtotime('+'.$i.' days',strtotime($start_dt_org))).' '.$v['start_time']) < strtotime(date('Y-m-d H:i'))) disabled @endif
                                                        >預約</a>
                                                        @endif
                                                    @endif
                                                @endif

											</td>
                                            @endfor
										</tr>
										@endforeach
									</tbody> 
								</table>
			  			  </div>
				</div>
         		
          		<div class="text-center">	
          	  	<a href="#" class="btn btn-sm btn-default" id="btn-back">
          	  	<i class="fa fa-angle-left"></i> 
          	  	回上一頁
          	  	</a>
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
        
        $(".week_btn").click(function(e){
            e.preventDefault();
            if($(this).attr('data-start_dt') != "")
            {
                location.href="{{ asset('instrument/reservation/id-'.$id) }}?start_dt="+$(this).attr('data-start_dt')+"&end_dt="+$(this).attr('data-end_dt');
            }
        });

        $("#search_btn").click(function(e){
            e.preventDefault();
            if($("#search_date").val() != "")
            {
                location.href="{{ asset('instrument/reservation/id-'.$id) }}?search_date="+$("#search_date").val();
            }
        });

        $(".reservation").click(function(e){
            e.preventDefault();
            $.blockUI({ message: null }); 
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('submit')->getUrl() }}",
                type: "post",
                dataType: "json",
                data: {'id':$(this).attr('data-id'),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    
                    if(response.result == "ok")
                    {
                        var message = $('div.growlUI');
                        $('div.growlUI').find("h1").html(response.msg);
                        $('div.growlUI2').find("h2").html();
                    }
                    else
                    {
                        var message = $('div.growlUI2');
                        $('div.growlUI2').find("h1").html(response.msg);
                        $('div.growlUI2').find("h2").html(response.detail);
                    }
                    
                    $.blockUI({ 
                                message: message, 
                                fadeIn: 700, 
                                fadeOut: 700, 
                                timeout: 2000, 
                                showOverlay: false, 
                                centerY: false, 
                                css: { 
                                    width: '350px', 
                                    top: '100px', 
                                    left: '', 
                                    right: '10px', 
                                    border: 'none', 
                                    padding: '5px', 
                                    backgroundColor: '#000', 
                                    '-webkit-border-radius': '10px', 
                                    '-moz-border-radius': '10px', 
                                    opacity: .6, 
                                    color: '#fff' 
                                } 
                            }); 
                    setTimeout(function() {
                        if(response.login == 0)
                        {
                            sessionStorage.setItem('urlBack', window.location.href);
                            location.href = "{{ asset('login') }}";
                        }
                        else
                        {
                            location.reload();
                        }
                    }, 2000);
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $("#btn-back").click(function(e){
			e.preventDefault();
			history.back();
		});
    });
    
</script>
@endsection