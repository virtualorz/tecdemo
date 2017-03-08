@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">            
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
				
         	  		<div class="row">		
         	  			<div class="col-md-8">
         	  				<h2 class="bigtitle">預約儀器</h2>
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
					<h4 class="mb--b">搜尋結果：</h4>
					<p class="mb--b">關鍵字 : {{ Request::input('keyword', '') }}
          				<a href="#" class="btn btn-default btn-xs max767right" id="cancel_btn">
							取消
						</a>
          			</p>
							<div class="table-responsive">
       							<table class="table table-striped"> 
									<thead> 
										<tr>
										<th class="ttw50">平台</th>
										<th class="ttw100">儀器編號</th> 
										<th class="ttw100">儀器名稱</th>
										<th>功能簡述</th>
										<th class="ttw80">管理員</th>
										<th class="ttw80 text-center">地點</th>
										</tr> 
									</thead> 

									<tbody> 
                                        @foreach($searchResult as $k=>$v) 
										<tr>
                                          <td>{{ $v['type_name'] }}</td>
										  <td>{{ $v['instrument_id'] }}</td>
										  <td><a href="{{ asset('instrument/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['name'] }}</a></td> 
											<td>{{ $v['function'] }}</td>
											<td>{{ $v['admin_name'] }}</td>
											<td class="text-center">{{ $v['site_name'] }}</td>
										</tr>
                                        @endforeach
									</tbody> 
								</table>
				  			</div>
				</div>
           	  	@endif
           	  	
                @if(count($listResult) !=0)
                @foreach($listResult as $k=>$v) 
           	  	<div class="tablebox">
                    <h4 class="mb--b">{{ $listResult[$k][0]['type_name'] }}</h4>
             		      		<div class="table-responsive">
       							<table class="table table-striped"> 
									<thead> 
										<tr>
										<th class="ttw50">平台</th>
										<th class="ttw100">儀器編號</th> 
										<th class="ttt100">儀器名稱</th>
										<th>功能簡述</th>
										<th class="ttw80">管理員</th>
										<th class="ttw80 text-center">地點</th>
										</tr> 
									</thead> 
 
									<tbody> 
                                        @foreach($v as $k1=>$v1)
										<tr>
										  <td>{{ $v1['type_name'] }}</td>
										  <td>{{ $v1['instrument_id'] }}</td>
										  <td><a href="{{ asset('instrument/reservation/id-'.$v1['uid'].'-'.$v1['salt']) }}">{{ $v1['name'] }}</a></td> 
											<td>{{ $v1['function'] }}</td>
											<td>{{ $v1['admin_name'] }}</td>
											<td class="text-center">{{ $v1['site_name'] }}</td>
										</tr>
                                         @endforeach

									</tbody> 
								</table>
				  			</div>
				</div>
                @endforeach
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