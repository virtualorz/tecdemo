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
            		<img src="{{ asset('assets/official/img/title_abidea.png') }}" width="225" height="40" alt=""/>
                </div>
            </div>
    	</div>
        
        <div class="row">
            @include('official.elements.plan_menu')
            
            <div class="col-md-10 col-sm-9 col-xs-12">
                
              	@if(isset($dataResult['content']))
                @include('official.elements.btseditor', ['btseditorContent' => $dataResult['content']])
                @endif
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