@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<section id="login">
    <div class="container padding0">
        <div class="row">
            <div class="col-md-12 text-center">
				<h2>會員登入</h2>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            	<div class="frombox">
					<form class="form-horizontal" id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					  <div class="form-group">
						<label class="col-sm-3 control-label">帳號</label>
						<div class="col-sm-8">
						  <input type="email" name="account" class="form-control" id="data-account" placeholder="Email">
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-3 control-label">密碼</label>
						<div class="col-sm-8">
						  <input type="password" name="password" class="form-control" id="data-password" placeholder="Password">
						</div>

					  </div>
					  <div class="form-group">
						<div class="col-sm-12 text-center">
						  <a href="#" class="btn btn-sm btn-primary" id="submit_but">登入</a>
						</div>
					  </div>
					</form>
				</div>
           		
           		
						<div class="col-sm-12 text-center mt--b">
						
						<a href="{{ asset('forget_pw') }}" class="btn btn-default btn-sm"><i class="fa fa-key"></i> 忘記密碼</a>
						
						<a href="{{ asset('register') }}" class="btn btn-default btn-sm"><i class="fa fa-user-plus"></i> 註冊帳號</a>
						
						
						</div>
           
            </div>
            
            
            <div class="spacer6030"></div>
        </div> 
         
    </div>
    </section>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        $("#submit_but").click(function(e){
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