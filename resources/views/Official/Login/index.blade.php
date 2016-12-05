@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <section id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
            	<img src="{{ asset('assets/official/img/title_login.png') }}" width="225" height="40" alt=""/>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            <form id="form1" class="form-horizontal" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">帳號</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="data-account" placeholder="帳號">
                </div>
              </div>
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">密碼</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="data-password" placeholder="Password">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12 text-center">
                  <a href="#" class="btn btn-default btn-lg" id="login_but">登入</a>
                </div>
              </div>
            </form>

            </div>
            
            
            <div class="spacer6030"></div>
            <div class="clearfix"></div>
        </div> 
         
    </div>
    </section>
    <!-- InstanceEndEditable -->
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        setbodyheight();

       $(window).resize(function(){
            setbodyheight();
        });

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

    function setbodyheight()
    {
        if($(".contentmt").height() < $(document).height())
        {
            $(".clearfix").height("0");
            $(".clearfix").height($(document).height() -  $(".navbar-fixed-top").height() - $(".contentmt").height() - $("footer").height() - 40);
        }
    }
    
</script>
@endsection