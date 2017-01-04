@extends('official.layouts.master')



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
                                            {{ date('Y.m.d',strtotime('+'.$i.' days',strtotime($start_dt_org))) }}</th>
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
											    <a href="#" class="btn btn-info">預約</a>
                                                @else
                                                    @foreach($v['reservation_log'] as $k1=>$v1)
                                                    {{--*/ $check = 0 /*--}}
                                                    @if(strtotime($v1['reservation_dt']) == strtotime('+'.$i.' days',strtotime($start_dt_org)))
                                                        {{--*/ $check = 1 /*--}}
                                                        @if($v1['attend_status'] == '1')
                                                        {{$v1['member_name']}} 使用中
                                                        @elseif($v1['reservation_status'] == '1')
                                                        <a href="#" class="btn btn-default">候補</a>
                                                        @endif
                                                    @endif
                                                    @endforeach
                                                    @if($check == 0)
                                                    <a href="#" class="btn btn-info">預約</a>
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
          	  	<a href="index.html" class="btn btn-sm btn-default">
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

        $("#btn_search").click(function(e){
            e.preventDefault();
            $("#formq").submit();
        });

        $("#cancel_btn").click(function(e){
            e.preventDefault();
            //$("#search_table").hide("slow");
            $("#search_table").animate({
                opacity: 0,
            }, 1000, function() {
                // Animation complete.
                location.href= "{{ asset('instrument') }}";
            });
        });
    });
    
</script>
@endsection