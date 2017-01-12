@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<section id="login">
    <div class="container padding0">
        <div class="row">
            <div class="col-md-12 text-center">
				<h2>確認信送出</h2>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            	<div class="frombox">
					<form class="form-horizontal" id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					  <div class="form-group">
						  <div class="col-sm-12 text-center">系統已經發送驗證信到您所填寫的信箱中，<br>
					      請查看信件來重新設定密碼</div>
						
					  </div>
					  
					  <div class="form-group">
						<div class="col-sm-12 text-center">
						  <a href="#" class="btn btn-sm btn-primary" id='resend' disabled="disabled">重新發送認證(<span id="time">59</span>)</a>

						</div>
					  </div>
					</form>
				</div>
            </div>
            
            
            <div class="spacer6030"></div>
        </div> 
         
    </div>
    </section>
@endsection


@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        urlBack = "{{ asset('forget_pw/finish/') }}";
        initValidation();
        var can_send = 0;
        $("#resend").click(function(e){
            e.preventDefault();
            if(can_send == 1)
            {
                $("#form1").submit();
                $.blockUI({ message: null });
            }
        });

        var myVar = setInterval(function(){
            $("#time").html(parseInt($("#time").html()) - 1);
            if($("#time").html() == '0')
            {
                clearInterval(myVar);
                can_send = 1;
                $("#resend").removeAttr("disabled");
                $("#resend").html("重新發送認證");
            }
        },1000);

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