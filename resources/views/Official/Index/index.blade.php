@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<!-- InstanceBeginEditable name="schoolcontent" -->
    <!-- Header -->
    <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>國立台灣大學生命科學院<br>
                        TechComm 科技共同空間</h1>
                        <h3 class="mt--m mb--s max767none">TechComm 是台大生命科學院的科技共同空間，提供生物科學相關科技服務，開放師生使用。</h3>
                        <input type="text" class="form-control intro-message-search" id="search_text">
                        <ul class="list-inline intro-social-buttons">
                            <li>
                                <a href="https://github.com/IronSummitMedia/startbootstrap" class="btn btn-lg btn-primary" id="find_activity"><span class="network-name">找活動</span></a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-default btn-lg btn-primary" id="find_instrument"><span class="network-name">找儀器</span></a>
                            </li>
                      </ul>
                  </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>

	<!-- Header -->

    <!-- 系統公告 -->
    <section id="inx_news">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-heading">
                    	系統公告
                    </h3>
                </div>
            </div>
            
            @foreach($newsResut as $k=>$v)
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>{{ $v['created_at']}}</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="{{ asset('news/id-'.$v['id']) }}">{{ $v['title']}}</a>
                </div>
            </div>
            @endforeach
            
        </div>
    </section>
    
    
    <!-- 最新活動 -->
    <section id="inx_news2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-heading">
                    	最新活動
                    </h3>
                </div>
            </div>
            
            @foreach($activityResut as $k=>$v)
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    @if($v['end_dt'] == null)
                    <p>{{ $v['start_dt']}} 起</p>
                    @else
                    <p>{{ $v['start_dt']}} <br>-<br> {{ $v['end_dt']}}</p>
                    @endif
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['activity_name']}}</a>               
                </div>
            </div>
            @endforeach
            
            <div class="row text-center">
                <div class="col-md-12">
            		<a href="{{ asset('activity') }}" class="btn btn-default btn-sm mt--b">MORE <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
            </div>
            
        </div>
    </section>
@endsection


@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
       $("#find_activity").click(function(e){
           e.preventDefault();
           location.href= "{{ asset('activity') }}?keyword="+$("#search_text").val();
       });
       $("#find_instrument").click(function(e){
           e.preventDefault();
           location.href= "{{ asset('instrument') }}?keyword="+$("#search_text").val();
       });

    });
    
</script>
@endsection