@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <section id="visit_list">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center"><img src="{{ asset('assets/official/img/title_learning.png') }}" width="225" height="40" alt=""/></div>
            
            <div class="spacer6030"></div>

             @foreach($dataResult as $k=>$v)
        	<div class="col-md-4 col-sm-6 col-xs-12">
            	<div class="listitem">
                    <a href="{{ asset('learning/content/id-'.$v['id'] ) }}" class="listitem_img">
                        <img class="img-responsive" src="{{ $v['front'] }}" alt="">
                    </a>
                    
                    <a href="{{ asset('learning/content/id-'.$v['id'] ) }}" class="listitem_caption">
                        <p class="text-center">{{ $v['date'] }}</p>
                    	<h4 class="text-center"> {{ $twCity[$v['city']] }} {{ $v['school_name'] }}</h4>
                    </a>
                </div>
            </div>
            @endforeach
      	</div>
      
      	@if(isset($pagination) && $pagination['last'] > 1)
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="{{ asset('learning').'?page='.$pagination['prev'] }}" class="btn btn-default btn-lg max767none"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
                    <a href="{{ asset('learning').'?page='.$pagination['next'] }}" class="btn btn-default btn-lg max767none">下一頁 <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div>
            </div>
        @endif
    </div>
    </section>
    
    <div class="clearfix"></div>
    
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
            $(".clearfix").height($(document).height() -  $(".navbar-fixed-top").height() - $(".contentmt").height() - $("footer").height() - 40);
        }
    }
    
</script>
@endsection