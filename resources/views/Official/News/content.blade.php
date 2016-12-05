@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->

	<div class="container">
            <div class="row">
    
              <div id="visitdetail-content" class="col-md-push-4 col-md-8">
                    <span class="line-schoolpage"></span>
                  	<div class="row">
                    <div class="col-xs-12">
                        <h2 class="color_green">{{ $dataResult['title'] }}</h2>
                        <div class="spacer-10"></div>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12">
                      	<p><i class="fa fa-clock-o" aria-hidden="true"></i> 發表日期：{{ $dataResult['created_at'] }}</p>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12">
                     	<p class="whoedit"><i class="fa fa-pencil" aria-hidden="true"></i> 發表單位：{{ $dataResult['school_name'] }}</p>
                    </div>
              	    
                    </div>
                    <span class="line-schoolpage"></span>
                    
                    @if(isset($dataResult['content']))
                    @include('official.elements.btseditor', ['btseditorContent' => $dataResult['content']])
                    @endif
                    
                    <span class="line-schoolpage"></span>
                            <!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="addthis_sharing_toolbox"></div>
                    <span class="line-schoolpage"></span>
                	
                    <div class="row text-center">
                        <div class="col-md-12 text-center">
                            <a href="{{ asset('news') }}" class="btn btn-default btn-lg"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
                        </div>
                    </div>
                
                </div>
                
                <div class="col-md-pull-8 col-md-4">
                <div class="othernews">
                	<span class="line-schoolpage"></span>
                    
           	  	  <h3 class="mb--s color_green">其他活動訊息</h3>
                    
                    @foreach($listResult as $k=>$v)
		  	  	    <p class="newsdate">{{ $v['created_at'] }}</p>
                    <p>
                        <a href="{{ asset('news/content/id-'.$v['id']) }}">{{ $v['title'] }}</a>
                    </p>
                    <hr>
                    @endforeach
                    
                    <span class="line-schoolpage"></span>
                    
                    <div class="text-center">
                    	<a href="{{ asset('news') }}" class="btn btn-default btn-lg">More</a>
                    </div>
                </div>   
                </div> 
    		</div>
    	</div>
    
    <div class="spacer6030"></div>
    <div class="clearfix"></div>

	<!-- InstanceEndEditable -->
    </div>
@endsection


@section('script')
<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56c41de38a334fb3"></script> 
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