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
                	<img src="{{ asset('assets/official/img/title_do.png') }}" width="225" height="40" alt=""/>
                </div>
                
                <span class="line-schoolpage"></span>
            	
                @if(isset($dataResult[0]['id']))
                @foreach($dataResult as $k=>$v)
           	  	<div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="listitem">
                        <a href="{{ asset('school/execute/content/id-'.$v['id']) }}" class="listitem_img">
                            <img class="img-responsive" src="{{ $v['front'] }}" alt="">
                        </a>
                        
                        <a href="{{ asset('school/execute/content/id-'.$v['id']) }}" class="listitem_caption">
                            <h4 class="text-center">{{ $v['date'] }}</h4>
                        </a>
                    </div>
                </div>
                @endforeach
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