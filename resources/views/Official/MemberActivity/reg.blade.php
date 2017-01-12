@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">

    	 <div class="row">
        @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">補登記</h2>
              	<!--通知訊息-->
              	
                <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
           	  	<div class="messagebox">
           	  		<div class="row mb-xs-20 mb--b">
						<div class="col-sm-2 col-xs-3"><h5>日期</h5></div>
                    <div class="col-sm-10 col-xs-9">{{ date('Y.m.d') }}</div>
          	  		</div>
					
         	  		<div class="line-schoolpage"></div>
          	  		
          	  		<div class="row mb-xs-20 mb--b">
						<div class="col-sm-2 col-xs-3"><h5>主題</h5></div>
                    <div class="col-sm-10 col-xs-9">{{ $dataResult['activity_name'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-sm-2"><h5>理由</h5></div>
						<div class="col-sm-10 mt-xs-10">
						<textarea class="form-control" rows="3" name='reason'></textarea>
						</div>
          	  		
          	  			<div class="col-sm-12 text-center mt--b">
                          <input type='hidden' name='id' value="{{ $dataResult['id'] }}">
						  <a href="{{ asset('member/activity') }}" class="btn btn-default btn-sm">取消</a>

						  <a href="member.html" class="btn btn-sm btn-primary" id="save_btn">送出</a>


						</div>

          	  		
					</div>
					
				</div>
                </form>
          		
          		<div class="text-center">	
          	  	<a href="{{ asset('member/activity') }}" class="btn btn-sm btn-default">
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
        $("#save_btn").click(function(e){
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