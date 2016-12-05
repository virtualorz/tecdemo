@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
   	@include('official.elements.school_header')
    
	<div class="spacer3015"></div>
    

    
    <div class="container">
        <div class="row">
            @include('official.elements.member_menu')
            
            <div id="visitdetail-content" class="col-md-10 col-sm-9 col-xs-12 edit-content">
       	    	
                <div class="bigtitle">
                	<img src="{{asset('assets/official/img/title_pw.png') }}" width="225" height="40" alt=""/>
                </div>
                
                <span class="line-schoolpage"></span>

				<form id="form1" class="form-horizontal" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">舊密碼</h3>
                        </div>
                        <div class="col-sm-9">
                          <input type="password" class="form-control required" name="oldpw" id="data-oldpw">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">新密碼</h3>
                        </div>
                        <div class="col-sm-9">
                          <input type="password" class="form-control required" name="newpw" id="data-newpw">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">確認密碼</h3>
                        </div>
                        <div class="col-sm-9">
                          <input type="password" class="form-control required" name="newpwR" id="data-newpwR">
                        </div>
                    </div>
                </form>
                                
                <div class="width100 text-center">
                    <a class="btn btn-primary btn-lg" id="submit_btn">送出</a>
                </div>
          </div>
        </div>
    </div>
    
    <div class="spacer6030"></div>
    
	@include('official.elements.member_menu_mobile')
	<!-- InstanceEndEditable -->
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        $("#submit_btn").click(function(e){
            e.preventDefault();
            if($("#data-newpw").val() == $("#data-newpwR").val())
            {
                $('#form1').submit();
            }
            else
            {
                message = $('div.growlUI2');
                $('div.growlUI2').find("h1").html("驗證錯誤!");
                $('div.growlUI2').find("h2").html("新密碼輸入錯誤");
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

            }
        });

    });

    function initValidation() {
        $('#form1').validate({
            submitHandler: function (form) {
                var ajaxProp = {
                    dataType: "json",
                    data: {_token: csrf_token},
                    error: function (jqXHR, textStatus, errorThrown) {
                        
                    },
                    success: function (response) {
                        var message = "";
                        if(response.result == 'no')
                        {
                            message = $('div.growlUI2');
                            $('div.growlUI2').find("h1").html(response.msg);
                            var h2 = "";
                            for(var k in response.detail)
                            {
                                h2 += response.detail[k]+"<br>"
                                
                            }
                            $('div.growlUI2').find("h2").html(h2);
                        }
                        else
                        {
                            message = $('div.growlUI');
                            $('div.growlUI').find("h1").html(response.msg);
                            $('div.growlUI').find("h2").html("");
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
                            setTimeout(function(){
                                location.reload();
                            },2500);
                        }
                    }
                }
                $(form).ajaxSubmit(ajaxProp);
            }
        });
    }
    
</script>
@endsection