@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
           @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">新增期刊</h2>
              	
           	  	<div class="messagebox">
           	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 col-xs-4"><h5>新增日期</h5></div>
                        <div class="col-md-10 col-sm-9 col-xs-8">{{ $dataResult['created_at'] }}</div>
          	  		</div>
					
         	  		<div class="line-schoolpage"></div>
          	  		
          	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>學術產出</h5></div>
                        <div class="col-md-10 col-sm-9 col-xs-8">{{ $journal[$dataResult['type']] }}</div>
					</div>
       	  			
       	  			<div class="line-schoolpage"></div>
       	  			
       	  			<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>發表日期</h5></div>
						<div class="col-md-10 col-sm-9 col-xs-8">{{ $dataResult['release_dt'] }}</div>
					</div>
       	  			
       	  			<div class="line-schoolpage"></div>
       	  			
       	  			
       	  			<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>發表題目</h5></div>
						<div class="col-md-10 col-sm-9 col-xs-8">{{ $dataResult['topic'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>期刊</h5></div>
						<div class="col-md-10 col-sm-9 col-xs-8">{{ $dataResult['journal'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>作者</h5></div>
						<div class="col-md-10 col-sm-9 col-xs-8">{{ $dataResult['author'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>發表超連結</h5></div>
						<div class="col-md-10 col-sm-9 col-xs-8">{{ $dataResult['author'] }}</div>
						
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						 <a href="{{ asset('member/journal') }}" class="btn btn-default btn-sm"><i class="fa fa-angle-left"></i> 回上一頁</a>
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