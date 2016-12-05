@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    
    <div class="container">
    	<div class="row">
    		<div class="col-md-12 text-center">
            	<div class="aboutitle">
       	    		<img src="{{ asset('assets/official/img/title_abtime.png') }}" width="225" height="40" alt=""/>
                </div>
            </div>
    	</div>
    
        <div class="row">
            @include('official.elements.plan_menu')
            
            <div id="school-content" class="col-md-10 col-sm-9 col-xs-12">            	
           	  	<h3 class="color_green">計畫執行時程規劃 - 105學年度計畫</h3>
            
            	<span class="line-schoolpage"></span>
                
                @foreach($dataResult as $k=>$v)
                <div class="planbox">
                	 <div class="col-md-3 col-sm-6">
                     	<h3 class="color_green" style="font-weight:600">{{ $v['name']}}</h3>
                        <h4 class="color_green">調查規劃期</h4>
                     </div>
                     
                     <div class="col-md-3 col-sm-6">
                     	<p>{{ $v['start_dt']}} - {{ $v['end_dt']}}</p>
                     </div>
                     
                     <div class="col-md-6 col-sm-12">
                     	<p>
                         @foreach($v['item'] as $k1=>$v1)
                         {{$v1}}<br>
                         @endforeach
                        </p>
                     </div>
                </div>
                
                @if($k != count($dataResult)-1)
                <div class="width100 text-center nextplan">
                	<i class="fa fa-caret-down" aria-hidden="true"></i>
                </div>
                @endif
                @endforeach
                
            </div>
        </div>
    </div>
    
    <div class="spacer6030"></div>
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