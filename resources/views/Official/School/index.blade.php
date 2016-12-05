@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <section id="school_list">
  
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center"><img src="{{ asset('assets/official/img/title_school.png') }}" width="225" height="40" alt=""/></div>
            
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-12">
                    <div id="container5">
                        <div id="container4">
                            <div id="container3">
                                <div id="container2">
                                    <div id="container1">
                                        <div id="col1">
                                            <a href="{{ asset('school') }}" class="whereschool5"></a>
                                        </div>
                                        <div id="col2">
                                            <a href="{{ asset('school') }}?location=1" class="whereschool1"></a>
                                        </div>
                                        <div id="col3">
                                           	<a href="{{ asset('school') }}?location=2" class="whereschool2"></a>
                                        </div>
                                        <div id="col4">
                                            <a href="{{ asset('school') }}?location=3" class="whereschool3"></a>
                                        </div>
                                        <div id="col5">
                                            <a href="{{ asset('school') }}?location=4" class="whereschool4"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @foreach($listResult as $k=>$v)
        	<div class="col-md-4 col-sm-6 col-xs-12">
            	<div class="listitem">
                    <a href="{{ asset('school/plan/id-'.$v['id']) }}" class="listitem_img">
                        <img class="img-responsive" src="{{ $v['photo'] }}" alt="">
                    </a>
                    
                    <a href="{{ asset('school/plan/id-'.$v['id']) }}" class="listitem_caption">
                   		<h3 class="text-center"> {{ $twCity[$v['city']] }} {{ $v['school_name'] }}</h3>
                    </a>
                </div>
            </div>
            @endforeach
      	</div>
        
        @if(isset($pagination) && $pagination['last'] > 1)
      	<div class="row">
            <div class="col-md-12 text-center">
                @if($location !='')
                <a href="{{ asset('school').'?location='.$location.'&page='.$pagination['prev'] }}" class="btn btn-default btn-lg max767none"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
      			<a href="{{ asset('school').'?location='.$location.'&page='.$pagination['next'] }}" class="btn btn-default btn-lg max767none">下一頁 <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                @else
                <a href="{{ asset('school').'?page='.$pagination['prev'] }}" class="btn btn-default btn-lg max767none"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
      			<a href="{{ asset('school').'?page='.$pagination['next'] }}" class="btn btn-default btn-lg max767none">下一頁 <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                @endif
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