@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">            
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
				
         	  		<div class="row">		
         	  			<div class="col-md-8">
         	  				<h2 class="bigtitle">參與活動</h2>
						</div>
        	  			<form id="formq" method="get" action="{{ Sitemap::node()->getUrl() }}">
         	  			<div class="col-md-4">
         	  				<div class="searchbox">
							<input type="text" name="keyword" class="form-control urlinput" value="{{ Request::input('keyword', '') }}">
							<a href="#" class="btn btn-primary btn-sm addbtn" id="btn_search">
								搜尋
							</a>
							</div>
            			</div>
                         </form>
					</div>        	
           	  	
                @if(count($searchResult) !=0)
           	  	<div class="tablebox" id="search_table">
					<h4>搜尋結果：</h4>
            		
					<p class="mb--b">關鍵字 : {{ Request::input('keyword', '') }}
          				<a href="#" class="btn btn-default btn-xs max767right" id="cancel_btn">
							取消
						</a>
          			</p>
             		      		<div class="table-responsive">
       							<table class="table table-striped"> 
									<thead> 
										<tr>
										<th class="ttw100">平台</th> 
										<th class="ttw100">日期</th>
										<th class="ttw100">活動編號</th> 
										<th>活動名稱</th>
										<th class="ttw80">類型</th>
										<th class="ttw80 text-center">學分數</th>
										<th class="ttw80 text-center">時數</th>
										<th class="ttw100">狀態</th>
										</tr> 
									</thead> 

									<tbody> 
                                        @foreach($searchResult as $k=>$v) 
										<tr>
											<td>{{ $instrument_type[$v['relative_plateform'][0]] }}</td> 
                                            @if($v['end_dt'] == null)
                                            <td>{{ $v['start_dt'] }} 起</td>
                                            @else
                                            <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                                            @endif
											<td>{{ $v['activity_id'] }}</td> 
											<td>
											<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt'])}}">{{ $v['activity_name'] }}</a>
											</td>
											<td>{{ $v['type_name'] }}</td>
											<td class="text-center">{{ $v['score'] }}</td>
											<td class="text-center">{{ $v['time'] }}hr</td>
											@if($v['end_dt'] == null or (strtotime($v['end_dt_org']) > strtotime(date('Y-m-d'))))
                                            <td><span class="label label-success">已報名: {{ $v['reservation_count'] }}人</span></td>
                                            @else
                                            <td><span class="label label-default">已結束</span></td>
                                            @endif
										</tr>
                                        @endforeach
									</tbody> 
								</table>
				  			</div>
				</div>
                @endif
           	  	
           	  	@if(count($liest_aResult) !=0)
           	  	<div class="tablebox">
					<h4 class="mb--b">預約活動</h4>
             		      		<div class="table-responsive">
       							<table class="table table-striped"> 
									<thead> 
										<tr>
										<th class="ttw100">平台</th> 
										<th class="ttw100">日期</th>
										<th class="ttw100">活動編號</th> 
										<th>活動名稱</th>
										<th class="ttw80">類型</th>
										<th class="ttw80 text-center">學分數</th>
										<th class="ttw80 text-center">時數</th>
										<th class="ttw100">狀態</th>
										</tr> 
									</thead> 

									<tbody>
                                        @foreach($liest_aResult as $k=>$v) 
										<tr>
                                            <td>{{ $instrument_type[json_decode($v['relative_plateform'],true)[0]] }}</td> 
                                            @if($v['end_dt'] == null)
                                            <td>{{ $v['start_dt'] }} 起</td>
                                            @else
                                            <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                                            @endif
											<td>{{ $v['activity_id'] }}</td> 
											<td>
											<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt'])}}">{{ $v['activity_name'] }}</a>
											</td>
											<td>{{ $v['type_name'] }}</td>
											<td class="text-center">{{ $v['score'] }}</td>
											<td class="text-center">{{ $v['time'] }}hr</td>
                                            <td><span class="label label-success">已報名: {{ $v['reservation_count'] }}人</span></td>
										</tr>
                                        @endforeach
									</tbody> 
								</table>
				  			</div>
				</div>
                @endif
         		
                 @if(count($liest_unaResult) !=0)
         		<div class="tablebox">
       				<h4 class="mb--b">已結束</h4>
            		
             		      		<div class="table-responsive">
       							<table class="table table-striped"> 
									<thead> 
										<tr>
										<th class="ttw100">平台</th> 
										<th class="ttw100">日期</th>
										<th class="ttw100">活動編號</th> 
										<th>活動名稱</th>
										<th class="ttw100">開通儀器</th>
										<th class="ttw80">類型</th>
										<th class="ttw80 text-center">學分數</th>
										<th class="ttw80 text-center">時數</th>
										<th class="ttw100">狀態</th>
										</tr> 
									</thead> 

									<tbody> 
                                        @foreach($liest_unaResult as $k=>$v) 
										<tr>
                                            <td>{{ $instrument_type[json_decode($v['relative_plateform'],true)[0]] }}</td> 
                                            @if($v['end_dt'] == null)
                                            <td>{{ $v['start_dt'] }} 起</td>
                                            @else
                                            <td>{{ $v['start_dt'] }} - {{ $v['end_dt'] }}</td>
                                            @endif
											<td>{{ $v['activity_id'] }}</td> 
											<td>
											<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt'])}}">{{ $v['activity_name'] }}</a>
											</td>
											<td>
												{{--*/ $instrument_name = $v['instrument_name'] /*--}}
												@foreach($permission as $k1=>$v1)
												{{--*/ $instrument_name = str_replace('('.$k1.')','('.$permission[$k1].')',$instrument_name) /*--}}
												@endforeach
												{{ $instrument_name }}
											</td>
											<td>{{ $v['type_name'] }}</td>
											<td class="text-center">{{ $v['score'] }}</td>
											<td class="text-center">{{ $v['time'] }}hr</td>
                                            <td><span class="label label-default">已結束</span></td>
										</tr>
                                        @endforeach
									</tbody> 
								</table>
								
				  			</div>
                            @include('Official.elements.pagination')
			  </div>
              @endif
			  <p>過期活動請連結至<a href='http://techcomm.lifescience.ntu.edu.tw/workshop%20news.htm' target='_blank'>這裡</a>查詢</p>
            </div>
    	</div>
    </div>
    
    <div class="spacer6030"></div>
@endsection


@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        

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
                location.href= "{{ asset('activity') }}";
            });
        });
    });
    
</script>
@endsection