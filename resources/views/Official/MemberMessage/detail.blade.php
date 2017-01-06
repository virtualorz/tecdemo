@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">通知訊息</h2>
              	<!--通知訊息-->
              	
           	  	<div class="messagebox">
           	  		<div class="row mb--b">
						<div class="col-sm-2 col-xs-3"><h5>日期</h5></div>
                    <div class="col-sm-10 col-xs-9">{{ $dataResult['created_at'] }}</div>
          	  		</div>
					<div class="line-schoolpage"></div>
          	  		<div class="row mb--b">
						<div class="col-sm-2"><h5>主題</h5></div>
					<div class="col-sm-10 mt-xs-10">{{ $dataResult['title'] }}</div>
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
					
				</div>
          		
          		<div class="text-center">	
          	  	<a href="{{ asset('member/message') }}" class="btn btn-sm btn-default">
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
        urlBack = location.href;
        $(".delete").click(function(e){
            e.preventDefault();
            
            $("#del_id").val($(this).attr('data-id'));
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