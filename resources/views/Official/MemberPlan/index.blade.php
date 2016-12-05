@extends('official.layouts.master')
@expr(ViewHelper::plugin()->load('jqueryfileupload'))
@expr(ViewHelper::plugin()->load('btseditor'))


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
                	<img src="{{ asset('assets/official/img/title_about.png') }}" width="225" height="40" alt=""/>
                </div>
                
                <span class="line-schoolpage"></span>

				<form id="form1" class="form-horizontal" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">計畫主題</h3>
                        </div>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" name="topic" id="data-topic" value="{{ $dataResult[0]['topic'] }}">
                        </div>
                    </div>
                    
            	<span class="line-schoolpage"></span>
                
                	<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">計畫理念</h3>
                        </div>
                        <div class="col-sm-9">
                          <textarea class="form-control" rows="3" name="idea" id="data-idea" >{{ $dataResult[0]['idea'] }}</textarea>
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
                
       	  	  		
                	<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">課程規劃</h3>
                        </div>
                        <div class="col-sm-9">
                        	<div name="plan" id="plan" class="btseditor" data-name="plan" data-value="{{ $dataResult[0]['plan'] }}"></div>
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
                    
                    <div class="form-group">
                        <div class="col-sm-3">
                        	<h3 class="color_green">上傳附件</h3>
                        </div>
                        <div class="col-sm-9">
                        	<div id="data-file" class="jqfuUploader" name="file" data-name="file" data-category="execute_file" data-file_ext="jpg|jpeg|png|gif|docx|doc|pdf|ppt|pptx" data-file_size="10MB"></div>                      	
                            <span id='org_file_result'>
                            @foreach($dataResult[0]['file'] as $k=>$v)
                            <span class='files'>
                                <input type='hidden' name='file[{{ $v["id"] }}][id]' value='{{ $v["id"] }}'>
                                <input type='hidden' name='file[{{ $v["id"] }}][name]' value='{{ $v["name"] }}'>
                                <input type='hidden' name='file[{{ $v["id"] }}][ext]' value='{{ $v["ext"] }}'>
                                <input type='hidden' name='file[{{ $v["id"] }}][dir]' value='{{ $v["dir"] }}'>
                                <input type='hidden' name='file[{{ $v["id"] }}][scale]' value=''>
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>{{ $v['name'] }}<a href="#" class="del_file"><i class="fa fa-trash" aria-hidden="true"></i></a><br>
                            </span>
                            @endforeach
                            </span>
                            <span id='file_result'>
                            </span>
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
            
                <div class="form-group">
                        <div class="col-sm-3">
                       		<h3 class="color_green">相關合作場域 / 團體</h3>
                        </div>
                        <div class="col-sm-9">
                        	<input type="text" class="form-control partner" id="rel_group_text">
                            <a class="btn btn-default inlineblock addbtn" id="add_rel_group">新增</a>
                        	<div class="spacer-10"></div>
                            
                            <span id="result_rel_group">
                            @foreach($dataResult[0]['related_group'] as $k=>$v)
                            <p><input type='hidden' name='related_group[]' value='{{$v}}'>{{$v}}<a href="#" class="del_file"> <i class="fa fa-trash" aria-hidden="true"></i></a></p>
                            @endforeach
                            </span>
                        	
                        </div>
                    </div>
           	  	
                
                
                <span class="line-schoolpage"></span>
                
                
                	<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">聯絡方式</h3>
                        </div>
                        <div class="col-sm-9">
                        	
                            
                            <label class="col-sm-2 control-label padding0">聯絡人</label>
                            <div class="col-sm-10 mb--m padding0">
                              <input type="text" class="form-control" placeholder="姓名" name="contact_name" value="{{ $dataResult[0]['contact_name'] }}">
                            </div>
                            
                            <label class="col-sm-2 control-label padding0">信箱</label>
                            <div class="col-sm-10 mb--m padding0">
                              <input type="email" class="form-control" placeholder="Email" name="contact_email" value="{{ $dataResult[0]['contact_email'] }}">
                            </div>
                            
                            <label class="col-sm-2 control-label padding0">電話</label>
                            <div class="col-sm-10 mb--m padding0">
                              <input type="tel" class="form-control" name="contact_tel" value="{{ $dataResult[0]['contact_tel'] }}">
                            </div>
                            
                            
                        </div>
                    </div>
              	
                <span class="line-schoolpage"></span>
                
              		<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">相關網站</h3>
                        </div>
                        <div class="col-sm-9">
                        	
                            
                            <label class="col-sm-2 control-label padding0">網站名稱</label>
                            <div class="col-sm-10 mb--m padding0">
                              <input type="text" class="form-control" id="rel_url_name">
                            </div>
                            
                            <label class="col-sm-2 control-label padding0">網址</label>
                            <div class="col-sm-10 mb--m padding0">
                              <input type="url" class="form-control urlinput" id="rel_url_web">
                              <a class="btn btn-default addbtn" id="add_rel_url">新增</a>
                            </div>

                            <span id="result_rel_url">
                            @foreach($dataResult[0]['related_url'] as $k=>$v)
                            <p>
                            <input type='hidden' name='related_url_name[]' value="{{$v['name']}}">
                            <input type='hidden' name='related_url_web[]' value="{{$v['url']}}">
                            {{$v['name']}}
                            {{$v['url']}}
                            <a href="#" class="del_file"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                            </p>
                            @endforeach
                            </span>
                            
                        </div>
                    </div>
                <input type="hidden" name="id" value="{{ $dataResult[0]['id'] }}" />
                </form>
                
                <span class="line-schoolpage"></span>
                
                <div class="width100 text-center">
                	<a class="btn btn-default btn-lg" id="back_btn">取消</a>
                    <a class="btn btn-primary btn-lg" id="submit_btn">儲存</a>
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
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        initBtsEditor();
        initUpload();

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

        $("#add_rel_group").click(function(){
            $("#result_rel_group").append("<p><input type='hidden' name='related_group[]' value='"+$("#rel_group_text").val()+"'>"+$("#rel_group_text").val()+"<a href='#' class='del_file'> <i class='fa fa-trash' aria-hidden='true'></i></a></p>");
            $("#rel_group_text").val("");
        });

        $("#add_rel_url").click(function(){
            $("#result_rel_url").append("<p><input type='hidden' name='related_url_name[]' value='"+ $("#rel_url_name").val()+"'><input type='hidden' name='related_url_web[]' value='"+$("#rel_url_web").val()+"'>"+$("#rel_url_name").val() +" "+ $("#rel_url_web").val()+" <a href='#' class='del_file'> <i class='fa fa-trash' aria-hidden='true'></i></a></p>");
            $("#rel_url_web").val("");
            $("#rel_url_name").val("");
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
    }

    function initBtsEditor() {
        $('.btseditor').each(function () {
            new BtsEditor($(this).attr('id'), {
                jqfu_file_size: "10 MB",
                jqfu_category: 'school_plan',
                menu: [ "text"]
            })
        });
    }
    
</script>
@endsection