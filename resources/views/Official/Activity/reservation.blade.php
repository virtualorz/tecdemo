@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">            
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
				<h2 class="bigtitle">活動資訊</h2>
              	<!--通知訊息-->
              	
           	  	<div class="messagebox">
           	  		<div class="row mb--b">
           	  			<div class="col-xs-12">
						<h4>{{ $dataResult['activity_name'] }}
                        @if($dataResult['end_dt'] == null or strtotime($dataResult['end_dt_org']) > strtotime(date('Y-m-d'))) 
                        <span class="label label-success">已報名: {{ $dataResult['reservation_count'] }}人</span>
                        @else
						<span class="label label-default">已結束</span>
                        @endif
						</h4>
						</div>
					</div>
          	  		
          	  		<div class="line-schoolpage"></div>
          	  		
           	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4"><h5>活動編號</h5></div>
						<div class="col-sm-10 col-xs-8">{{ $dataResult['activity_id'] }}</div>
          	  		</div>
          	  		
          	  		<div class="line-schoolpage"></div>
          	  		
           	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4"><h5>日期</h5></div>
                        @if($dataResult['end_dt'] == null)
                        <div class="col-sm-10 col-xs-8">{{ $dataResult['start_dt'] }} 起</div>
                        @else
						<div class="col-sm-10 col-xs-8">{{ $dataResult['start_dt'] }} - {{ $dataResult['end_dt'] }}</div>
                        @endif
          	  		</div>
          	  		
          	  		<div class="line-schoolpage"></div>
         	  		
          	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4">
						  <h5>類別</h5></div>
						<div class="col-sm-10 mt-xs-8">{{ $dataResult['type_name'] }}</div>
					</div>
        	  		
					<div class="line-schoolpage"></div>
        	  		
        	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4"><h5>相關平台</h5></div>
						<div class="col-sm-10 col-xs-8">
                            @foreach($dataResult['instrument_type'] as $k=>$v)
                            {{ $v }} <br>
                            @endforeach
                        </div>
          	  		</div>
					
					<div class="line-schoolpage"></div>
        	  		
        	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4"><h5>等級</h5></div>
						<div class="col-sm-10 col-xs-8">{{ $dataResult['level'] }}</div>
          	  		</div>
          	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4"><h5>活動時數</h5></div>
						<div class="col-sm-10 col-xs-8">{{ $dataResult['time'] }}hr</div>
          	  		</div>
          	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb--b">
         	  			<div class="col-sm-2 col-xs-4"><h5>學分數</h5></div>
						<div class="col-sm-4 col-xs-8">{{ $dataResult['score'] }}學分</div>
          	  		</div>
          	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-4"><h5>開通儀器</h5></div>
						<div class="col-sm-10 col-xs-8">
                            @foreach($dataResult['instrument'] as $k=>$v)
                            {{ $v }} <br>
                            @endforeach
                        </div>
          	  		</div>
          	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb--b">
						<div class="col-sm-2"><h5>內容</h5></div>
					 	<div class="col-sm-10 mt-xs-10">
                        @if(isset($dataResult['content']))
                        @include('official.elements.btseditor', ['btseditorContent' => $dataResult['content']])
                        @endif
         	  			</div>
					</div>
					
					<div class="line-schoolpage"></div>
					
					<div class="row mb--b">
					@if($dataResult['end_dt'] == null || (strtotime($dataResult['end_dt_org']) > strtotime(date('Y-m-d'))))
                        <form class="form-horizontal" id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
                        @if($dataResult['is_reservation'] == 0)
						<div class="col-xs-12 text-center">
                            <input type='hidden' name='reservation' value='1'>
                            <input type='hidden' name='activity_id' value="{{ $dataResult['id'] }}">
							<a href="#" class="btn btn-xl btn-primary submit_btn">預約</a>
						</div>
                        @endif
						
                        @if($dataResult['can_cancel'] == 1)
						<div class="col-xs-12 text-center">
                            <input type='hidden' name='reservation' value='0'>
                            <input type='hidden' name='activity_id' value="{{ $dataResult['id'] }}">
							<a href="#" class="btn btn-xl btn-default submit_btn">取消預約</a>
						</div>
                         @endif
                         </form>
                    @endif
					</div>
					
				</div>
          		
          		<div class="text-center">	
          	  	<a href="{{ asset('activity') }}" class="btn btn-sm btn-default">
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
        

        initValidation();
        $(".submit_btn").click(function(e){
            e.preventDefault();
            $("#form1").submit();
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