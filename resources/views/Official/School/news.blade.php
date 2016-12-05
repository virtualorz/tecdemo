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
            
            <div id="school-content" class="col-md-10 col-sm-9 col-xs-12">
            	<div class="bigtitle">
                	<img src="{{ asset('assets/official/img/title_news.png') }}" width="225" height="40" alt=""/>
                </div>
                
                <span class="line-schoolpage"></span>
              
              	<div class="school_news">
                
                @if(isset($dataResult[0]['id']))
                @foreach($dataResult as $k=>$v)
                <div class="row inxnewslist">
                    <div class="col-md-2 col-sm-2">
                        <p>{{ $v['created_at'] }}</p>
                    </div>
                    <div class="col-md-8 col-sm-10">
                        <a href="{{ asset('school/news/content/id-'.$v['id']) }}">{{ $v['title'] }}</a>               
                    </div>
                </div>
                @endforeach
                @endif
                
                @if(isset($pagination) && $pagination['last'] > 1)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="{{ asset('school').'?page='.$pagination['prev'] }}" class="btn btn-default btn-lg max767none"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
                        <a href="{{ asset('school').'?page='.$pagination['next'] }}" class="btn btn-default btn-lg max767none">下一頁 <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                @endif
            
                </div>
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