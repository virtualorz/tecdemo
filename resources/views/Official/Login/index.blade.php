@extends('official.layouts.master')



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
					<form class="form-horizontal">
					  <div class="form-group">
						<label class="col-sm-3 control-label">帳號</label>
						<div class="col-sm-8">
						  <input type="email" class="form-control" id="data-account" placeholder="Email">
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-sm-3 control-label">密碼</label>
						<div class="col-sm-8">
						  <input type="password" class="form-control" id="data-password" placeholder="Password">
						</div>

					  </div>
					  <div class="form-group">
						<div class="col-sm-12 text-center">
						  <a href="#" class="btn btn-sm btn-primary" id="login_but">登入</a>
						</div>
					  </div>
					</form>
				</div>
           		
           		
						<div class="col-sm-12 text-center mt--b">
						
						<a href="forget_pw.html" class="btn btn-default btn-sm"><i class="fa fa-key"></i> 忘記密碼</a>
						
						<a href="register.html" class="btn btn-default btn-sm"><i class="fa fa-user-plus"></i> 註冊帳號</a>
						
						
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

        $("#login_but").click(function(e){
            e.preventDefault();
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('submit')->getUrl() }}",
                type: "post",
                dataType: "json",
                data: {'account':$("#data-account").val(),'password':$("#data-password").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                },
                success: function (response) {

                    var message = "";
                    if(response.result == 'no')
                    {
                        message = $('div.growlUI2');
                        $('div.growlUI2').find("h1").html("登入失敗");
                        $('div.growlUI2').find("h2").html("帳號或密碼錯誤");
                        
                    }
                    else
                    {
                        message = $('div.growlUI');
                        $('div.growlUI').find("h1").html("登入成功");
                        $('div.growlUI').find("h2").html("正在前往會員頁面");
                    }
                    $.blockUI({ 
                        message: message, 
                        fadeIn: 700, 
                        fadeOut: 700, 
                        timeout: 2000, 
                        showOverlay: false, 
                        centerY: false, 
                        css: { 
                            width: '350px', 
                            top: '100px', 
                            left: '', 
                            right: '10px', 
                            border: 'none', 
                            padding: '5px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .6, 
                            color: '#fff' 
                        } 
                    }); 

                    if(response.result == 'ok')
                    {
                        $("#data-account").attr("disabled","disabled");
                        $("#data-password").attr("disabled","disabled");
                        setTimeout(function(){
                            location.href = "{{asset('member')}}";
                        },2500);
                    }
                    
                }
            }
            $.ajax(ajaxProp);
        });

    });
    
</script>
@endsection