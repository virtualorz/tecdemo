@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <!-- Header -->
    <header class="max767none">
        <div class="container-fluid">
            <ul id="hexGrid">
            	<!-- s1 -->
                @foreach($schoolResut as $k=>$v)
	            <li class="hex @if($k>=5) s{{$k+1}} @endif">
	                <a class="hexIn" href="{{ asset('school/plan/id-'.$v['school_id']) }}">
	                    <img src="{{ $v['photo'] }}" alt="" />
	                    <p>{{ $twCity[$v['city']] }}</p>
                        <h1>{{ $v['school_name'] }}</h1>
	                </a>
	            </li>
                @endforeach
	        </ul>
        </div>
    </header>

	<!-- Header -->
    <header class="min768none">
	  <div id="carousel1" class="carousel slide" data-ride="carousel">
		  <ol class="carousel-indicators">
            @foreach($schoolResut as $k=>$v)
            <li data-target="#carousel1" data-slide-to="{{$k}}" @if($k == 0) class="active" @endif></li>
            @endforeach
	    </ol>
		  	<div class="carousel-inner" role="listbox">
            @foreach($schoolResut as $k=>$v)
		    <a href="{{ asset('school/plan/id-'.$v['school_id']) }}" @if($k == 0) class="item active" @else class="item" @endif><img src="{{ $v['photo'] }}" alt="First slide image" class="center-block">
		      <div class="carousel-caption">
		        <h3>{{ $v['school_name'] }}</h3>
		        <p>{{ $twCity[$v['city']] }}</p>
	          </div>
	        </a>
            @endforeach
	    	</div>
		  <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#carousel1" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a>
        </div>
    </header>

    <!-- Services Section -->
    <section id="inx_news">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-heading">
                    	<img src="{{ asset('assets/official/img/title_news.png') }}" width="225" height="40" alt=""/>
                        <a href="{{ asset('news') }}" class="btn btn-default btn-sm floatright max767none">MORE <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    </h2>
                </div>
            </div>
            
            @foreach($newsResut as $k=>$v)
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>{{$v['created_at']}}</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="{{ asset('news/content/id-'.$v['id']) }}">{{$v['title']}}</a>               
                </div>
            </div>
            @endforeach
            
            <div class="row text-center">
                <div class="col-md-12">
            		<a href="{{ asset('news') }}" class="btn btn-default btn-sm min768none mt--l">MORE <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
            </div>
            
        </div>
    </section>

    <!-- Video Section -->
    <section id="portfolio" class="bg-light-green">
        <div class="container">
        	<div class="row">
                <div class="col-lg-12">
                    <h2 class="section-heading">
                    	<img src="{{ asset('assets/official/img/title_video.png') }}" width="225" height="40" alt=""/>
                        <a href="{{ asset('video') }}" class="btn btn-default btn-sm floatright max767none">MORE <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    </h2>
                </div>
            </div>
            
            <div class="row">
                @foreach($videoResut as $k=>$v)
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
            
            <div class="row text-center">
                <div class="col-md-12">
            		<a href="{{ asset('video') }}" class="btn btn-default btn-sm min768none mt--s">MORE <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
            </div>
            
            
        </div>
    </section>

    <!-- About Section -->
    <section id="inxschool">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">
                    	<img src="{{ asset('assets/official/img/title_school.png') }}" width="225" height="40" alt=""/>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <a href="{{ asset('school') }}?location=1" class="whereschool1"></a>
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <a href="{{ asset('school') }}?location=2" class="whereschool2"></a>
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <a href="{{ asset('school') }}?location=3" class="whereschool3"></a>
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <a href="{{ asset('school') }}?location=4" class="whereschool4"></a>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Portfolio Modal 1 -->
    @foreach($videoResut as $k=>$v)
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
    
    
    <!-- Clients Aside -->
    <aside class="inxcontact bg-darkest-green">
        <div class="container">
            <div class="row">
            	<div class="spacer3015"></div>
                <div class="col-md-4 col-sm-4">
                    <a class="inxcontact1">02-7734-6574 吳助理</a>
                </div>
                
                <div class="col-md-4 col-sm-4">
                    <a class="inxcontact2" href="#">ptscstc2015@gmail.com</a>
                </div>
                
                <div class="col-md-4 col-sm-4">
                    <a href="https://www.facebook.com/%E7%89%B9%E8%89%B2%E9%81%8A%E5%AD%B8%E3%84%90%E3%84%A7%E3%84%A4%CB%8B-%E7%8E%A9%E5%B0%B1%E5%B0%8D%E4%BA%86-1593895404185767/?fref=ts" target="_blank" class="inxcontact3">特色遊學ㄐ一ㄤˋ玩就對了</a>
                </div>
                <div class="spacer3015"></div>
            </div>
        </div>
    </aside>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
       

    });
    
</script>
@endsection