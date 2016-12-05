@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <section id="visit_list">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center"><img src="{{ asset('assets/official/img/title_news.png') }}" width="225" height="40" alt=""/></div>
            <div class="spacer6030"></div>
        </div>    
        <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">

                @if(isset($listResult[0]['id']))
                @foreach($listResult as $k=>$v)
        		<div class="row inxnewslist">
                    <div class="col-md-2 col-sm-2">
                        <p>{{ $v['created_at'] }}</p>
                    </div>
                    <div class="col-md-8 col-sm-10">
                        <a href="{{ asset('news/content/id-'.$v['id']) }}">{{ $v['title'] }}</a>               
                    </div>
                </div>
                @endforeach
                @endif
                
                
            @if(isset($pagination) && $pagination['last'] > 1)
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="{{ asset('news').'?page='.$pagination['prev'] }}" class="btn btn-default btn-lg max767none"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
                    <a href="{{ asset('news').'?page='.$pagination['next'] }}" class="btn btn-default btn-lg max767none">下一頁 <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div>
            </div>
            @endif
        </div>
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