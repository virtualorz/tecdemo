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
                <img src="{{ asset('assets/official/img/title_news.png') }}" width="225" height="40" alt=""/>
                </div>
            
           	  	<span class="line-schoolpage"></span>
                
                <div class="width100 text-center">
                    <a href="{{ asset('member/news/add') }}" class="btn btn-primary btn-lg">
                    	<i class="fa fa-plus" aria-hidden="true"></i> 新增一筆消息
                    </a>
                </div>
              	<span class="line-schoolpage"></span>
                <table class="table table-striped"> 
               		<thead> 
                    	<tr> <th>#</th> <th>日期</th> <th>標題</th> <th class="ttt80">功能</th> </tr> 
                    </thead> 
                    
                    <tbody> 
                        @if(isset($dataResult[0]['id']))
                        @foreach($dataResult as $k=>$v)
                    	<tr> 
                       	  <th scope="row">{{ $k+1 }}</th> 
                       	  <td>{{ $v['created_at'] }}</td> 
                            <td><a href="{{ asset('news/content/id-'.$v['id']) }}" target="_blank">{{ $v['title'] }}</a></td> 
                            <td>
                           	  <div class="width50 floatleft text-center">
                                <a href="{{ asset('member/news/edit/id-'.$v['id']) }}"> <i class="fa fa-pencil" aria-hidden="true"></i></a>
                              </div>
                              <div class="width50 floatleft text-center">
                                <a href="#" class="delete" data-id="{{ $v['id'] }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                              </div>
                            </td>
                        </tr> 
                        @endforeach
                        @endif
                    </tbody> 
                </table>
                
                
                
            </div>
        </div>
    </div>
    
    <div class="spacer6030"></div>
    <div class="clearfix"></div>
    
	@include('official.elements.member_menu_mobile')
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

       $(".delete").click(function(e){
           e.preventDefault();
           var confirm_log = confirm("確定要刪除資料?");
           if(confirm_log)
           {
               var ajaxProp = {
                    url: "{{ Sitemap::node()->getChildren('delete')->getUrl() }}",
                    type: "post",
                    dataType: "json",
                    data: {'id':[$(this).attr('data-id')],'_token':csrf_token},
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
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
                $.ajax(ajaxProp);
           }
       });

    });

    function setbodyheight()
    {
        if($(".contentmt").height() < $(document).height())
        {
            $(".clearfix").height("0");
            $(".clearfix").height($(document).height() -  $(".navbar-fixed-top").height() - $(".contentmt").height() - $("footer").height() - 40 - $("#mobile-menu").height());
        }
    }
    
</script>
@endsection