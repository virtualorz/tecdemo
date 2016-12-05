@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <section id="portfolio" class="videolist">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center"><img src="{{ asset('assets/official/img/title_video.png') }}" width="225" height="40" alt=""/></div>
            
            <div class="spacer6030"></div>

                @foreach($listResult as $k=>$v)
            	<div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal{{$k}}" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover"></div>
                        <div class="embed-responsive embed-responsive-16by9">
                    		<iframe class="embed-responsive-item" src="{{ $v['url'] }}" frameborder="0" allowfullscreen=""></iframe>
                        </div>
                    	<div class="portfolio-caption">
                        	<h4>{{ $v['title'] }}</h4>
                        	<p>{{ $v['date'] }}</p>
                    	</div>
                    </a>
                </div>
                @endforeach
      	</div>
      
      	@if(isset($pagination) && $pagination['last'] > 1)
      	<div class="row">
            <div class="col-md-12 text-center">
                <a href="{{ asset('video').'?page='.$pagination['prev'] }}" class="btn btn-default btn-lg max767none"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
      			<a href="{{ asset('video').'?page='.$pagination['next'] }}" class="btn btn-default btn-lg max767none">下一頁 <i class="fa fa-angle-right" aria-hidden="true"></i></a>
    		</div>
    	</div>
        @endif
    </div>
    </section>
    
    
        <!-- Portfolio Modal 1 -->
    @foreach($listResult as $k=>$v)
    <div class="portfolio-modal modal fade" id="portfolioModal{{$k}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>{{ $v['title'] }}</h2>
                            <p class="item-intro text-muted">{{ $v['date'] }}</p>

							<div class="embed-responsive embed-responsive-16by9">
                    			<iframe class="embed-responsive-item" src="{{ $v['url'] }}" frameborder="0" allowfullscreen=""></iframe>
                        	</div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
    <!-- InstanceEndEditable -->
    </div>
    <div class="clearfix"></div>
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