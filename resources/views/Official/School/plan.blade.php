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
            @include('official.elements.school_menu')
            
            <div id="visitdetail-content" class="col-md-10 col-sm-9 col-xs-12">
            	<div class="bigtitle">
                	<img src="{{ asset('assets/official/img/title_about.png') }}" width="225" height="40" alt=""/>
                </div>
                <span class="line-schoolpage"></span>
              @if(isset($dataResult[0]['topic']))
              <h2 class="color_green">{{ $dataResult[0]['topic'] }}</h2>
            
            	<span class="line-schoolpage"></span>
                
                <h3 class="color_green">遊學課程理念</h3>
                <p>{{ $dataResult[0]['idea'] }}</p>
                
                <span class="line-schoolpage"></span>
                
       	  	  	<h3 class="color_green">課程規劃</h3>
                @if(isset($dataResult[0]['plan']))
                @include('official.elements.btseditor', ['btseditorContent' => $dataResult[0]['plan']])
                @endif
                <span class="line-schoolpage"></span>
            
                <h3 class="color_green">檔案下載</h3>
                @foreach($dataResult[0]['file'] as $k=>$v)
                    <a href="{$v['url']}}" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>{{$v['name']}}</a><br>
                @endforeach
                
                <span class="line-schoolpage"></span>
                
           	  	<h3 class="color_green">相關合作場域／團體</h3>
                <p>
                @foreach($dataResult[0]['related_group'] as $k=>$v)
                    {{$v}}<br>
                @endforeach
                </p>
                
                
                <span class="line-schoolpage"></span>
                
              	<h3 class="color_green">聯絡方式</h3>
                <p>
                聯絡人：{{$dataResult[0]['contact_name']}}<br>
                信箱： {{$dataResult[0]['contact_tel']}}<br>
                電話：{{$dataResult[0]['contact_email']}}
                </p>
                
                <span class="line-schoolpage"></span>
                
              	<h3 class="color_green">相關網站</h3>
                <p>
                    @foreach($dataResult[0]['related_url'] as $k=>$v)
                    <a href="{{$v['url']}}" target="_blank">{{$v['name']}}</a><br>
                    @endforeach
           	  </p>
             @endif
                
          </div>
        </div>
    </div>
    
    <div class="spacer6030"></div>
    <div class="clearfix"></div>
    
	@include('official.elements.school_menu_mobile')
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