@extends('official.layouts.master')
@expr(ViewHelper::plugin()->load('jqueryfileupload'))
@expr(ViewHelper::plugin()->load('btseditor'))


@section('head')
{!! ViewHelper::plugin()->renderCss() !!}
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
   	@include('official.elements.school_header')
    
	<div class="spacer3015"></div>
    

    
    <div class="container">
        <div class="row">
            @include('official.elements.member_menu')
            
            <div id="visitdetail-content" class="col-md-10 col-sm-9 col-xs-12">
       	    	
                <div class="bigtitle">
                	<img src="{{asset('assets/official/img/title_do.png') }}" width="225" height="40" alt=""/>
                </div>
            
            	<span class="line-schoolpage"></span>
                
                <form id="form1" class="form-horizontal" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
                
                	<div class="form-group">
                        <div class="col-sm-3">
                        	<h3 class="color_green">日期</h3>
                        </div>
                        <div class="col-sm-9">
                        	<input type="text" name="date" id="data-date" class="form-control datepicker required">
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
                
                	<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">參與對象</h3>
                        </div>
                        <div class="col-sm-9">
                          <input type="text" class="form-control required" name="member" id="data-member">
                        </div>
                    </div>
                
                <span class="line-schoolpage"></span>
                	
                    <div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">執行紀錄</h3>
                        </div>
                        <div class="col-sm-9">
                        <div name="content" id="content" class="btseditor" data-name="content"></div>
                        </div>
                    </div>
               <span class="line-schoolpage"></span> 
                	
                    <div class="form-group">
                        <div class="col-sm-3">
                        	<h3 class="color_green">上傳附件</h3>
                        </div>
                        <div class="col-sm-9">
                        	<div id="data-file" class="jqfuUploader" name="file" data-name="file" data-category="execute_file" data-file_ext="jpg|jpeg|png|gif|docx|doc|pdf|ppt|pptx" data-file_size="10MB" ></div>                      	
                            <span id='file_result'>
                            </span>
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
                	
                    <div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">執行照片</h3>
                        </div>
                        <div class="col-sm-9">
                          	<div id="data-photo" class="jqfuUploader_photo" name="photo" data-name="photo" data-category="execute_photo" data-file_ext="jpg|jpeg|png|gif" data-file_size="5MB" data-img_scale="960_720" data-file_px="360_270"></div>
                          	<p>上傳解析度 : 960 * 720</p>
                              <span id='photo_result'>
                            </span>
                          
                        </div>
                    </div>
                
                </form>
                <div class="width100 text-center">
                	<a class="btn btn-default btn-lg" id="back_btn">取消</a>
                    <a class="btn btn-primary btn-lg" id="submit_btn"><i class="fa fa-download" aria-hidden="true"></i> 儲存</a>
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
        initUpload();
        initBtsEditor();

        $("#submit_btn").click(function(e){
            e.preventDefault();
            $('#form1').submit();
        });

        $("#back_btn").click(function(e){
            e.preventDefault();
            location.href = "{{ asset('member/execute') }}"
        });

        $(document).on("click",".del_file",function(e){
            e.preventDefault();
            $(this).parent().remove();
        });

        $(".datepicker").datepicker();
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
                                location.href = "{{ asset('member/execute')}}";
                            },2500);
                        }
                    }
                }
                $(form).ajaxSubmit(ajaxProp);
            }
        });
    }

    function initUpload() {
        $('.jqfuUploader').each(function () {
            new Jqfu($(this).attr('id'),{
                spanStyle:"background-color:#ffffff;width:56px;height:40px;",
                icon: '<a class="btn btn-default addbtn" style="color:#055d25"> 上傳</a>',
                show_info:false,
                error_process:function(e, config ,data,txt){
                    append_html = "<span class='files_error'>";
                    append_html+= "<p style='color:red'>"+txt+"</p><br></span>";
                    $("#file_result").append(append_html);
                    setTimeout(function(){
                        $(".files_error").remove()
                    },3000);
                },
                loading_progress:function(e, config ,data){
                },
                complete:function(e, config,data){
                    if(data.result.result == "ok")
                    {
                        append_html = "<span class='files'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][id]' value='"+data.result.file.id+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][name]' value='"+data.result.file.name+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][ext]' value='"+data.result.file.ext+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][dir]' value='"+data.result.file.dir+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][scale]' value='"+data.result.file.scale+"'>";
                        append_html+= '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>'+data.result.file.name+'<a href="#" class="del_file"><i class="fa fa-trash" aria-hidden="true"></i></a><br></span>';
                        $("#file_result").append(append_html);
                    }
                    else
                    {
                        append_html = "<span class='files_error'>";
                        append_html+= "<p style='color:red'>"+data.result.msg+"</p><br></span>";
                        $("#file_result").append(append_html);
                        setTimeout(function(){
                            $(".files_error").remove()
                        },3000);
                    }
                }
            });
        });
        $('.jqfuUploader_photo').each(function () {
            new Jqfu($(this).attr('id'),{
                spanStyle:"background-color:#ffffff;width:111px;height:46px;",
                icon: '<a class="btn btn-default btn-lg mb--s inlineblock" style="color:#055d25">上傳照片</a>',
                show_info:false,
                error_process:function(e, config ,data,txt){
                    append_html = "<span class='photo_error'>";
                    append_html+= "<p style='color:red'>"+txt+"</p><br></span>";
                    $("#photo_result").append(append_html);
                    setTimeout(function(){
                        $(".photo_error").remove()
                    },3000);
                },
                loading_progress:function(e, config ,data){
                },
                complete:function(e, config,data){
                    if(data.result.result == "ok")
                    {
                        append_html = "<span class='photos'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][id]' value='"+data.result.file.id+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][name]' value='"+data.result.file.name+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][ext]' value='"+data.result.file.ext+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][dir]' value='"+data.result.file.dir+"'>";
                        append_html+= "<input type='hidden' name='"+config.name+"["+data.result.file.id+"][scale]' value='"+data.result.file.scale+"'>";
                        if(typeof $("#curr_photo_loaf").val() == "undefined")
                        {
                            append_html+= '<div class="school-visitpic" id="curr_photo_loaf"><div class="col-sm-6 col-xs-12"><img class="img-responsive" src="'+config.url_upload + '/' + data.result.file.dir + '/' + data.result.file.id + '.' + data.result.file.ext+'" alt=""><input type="text" class="form-control mb--s" name="photo_text['+data.result.file.id+']"></div></div>';

                            $("#photo_result").append(append_html);
                        }
                        else
                        {
                            append_html+= '<div class="col-sm-6 col-xs-12"><img class="img-responsive" src="'+config.url_upload + '/' + data.result.file.dir + '/' + data.result.file.id + '.' + data.result.file.ext+'" alt=""><input type="text" class="form-control mb--s" name="photo_text['+data.result.file.id+']"></div>';

                            $("#curr_photo_loaf").append(append_html);
                            $("#curr_photo_loaf").removeAttr('id');
                        }
                    }
                    else
                    {
                        append_html = "<span class='photo_error'>";
                        append_html+= "<p style='color:red'>"+data.result.msg+"</p><br></span>";
                        $("#photo_result").append(append_html);
                        setTimeout(function(){
                            $(".photo_error").remove()
                        },3000);
                    }
                }
            });
        });
    }

    function initBtsEditor() {
        $('.btseditor').each(function () {
            new BtsEditor($(this).attr('id'), {
                jqfu_file_size: "10 MB",
                jqfu_category: 'school_execute',
                menu: ["pic", "text"]
            })
        });
    }
    
</script>
@endsection