@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<section id="login">
    <div class="container padding0">
        <div class="row">
            <div class="col-md-12 text-center">
				<h2>忘記密碼</h2>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            	<div class="frombox">
					<form class="form-horizontal" id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					  <div class="form-group">
						  <div class="col-sm-12 text-center">
						  	請輸入註冊時填寫的電子郵件：
						  </div>
						<div class="col-sm-8 col-sm-offset-2">
						  <input type="email" class="form-control required" id="data-email" placeholder="Email" name="email">
						</div>
					  </div>
					  
					  <div class="form-group">
						<div class="col-sm-12 text-center">

						  <a href="{{ asset('login') }}" class="btn btn-default btn-sm">取消</a>

						  <a href="#" class="btn btn-sm btn-primary" id="submit_but">驗證</a>


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
        $("#submit_but").click(function(e){
            e.preventDefault();
            $("#form1").submit();
            $.blockUI({ message: null });
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