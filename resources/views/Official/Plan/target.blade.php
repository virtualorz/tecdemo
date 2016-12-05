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
            		<img src="{{ asset('assets/official/img/title_abgoal.png') }}" width="225" height="40" alt=""/>
                </div>
            </div>
    	</div>
    
      <div class="row">
            @include('official.elements.plan_menu')
            
          <div id="faq" class="col-md-10 col-sm-9 col-xs-12">
          	<div class="panel-group wrap" id="accordion" role="tablist" aria-multiselectable="true">
            					@foreach($dataResult as $k=>$v)
                                <div class="panel">
                                    <div class="panel-heading" role="tab" id="heading{{$k}}">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#faq{{$k}}" aria-expanded="false" aria-controls="collapse{{$k}}" class="collapsed">
                                        <h4 class="color_green">{{ $v['name']}}</h4>
                                        <p>{{ $v['start_dt']}} - {{ $v['end_dt']}}</p>
                                        </a>
                                    </h4>
                                    </div>
                                    
                                    <div id="faq{{$k}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$k}}" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                            @foreach($v['item'] as $k1=>$v1)
                                        	<div class="row faqrow">
                                                <div class="col-md-4 col-sm-5 col-xs-12">
                                                    <h4 class="color_green">
                                                        {{$v1['value']}}
                                                    </h4>
                                                </div>
                                                <div class="col-md-8 col-sm-7 col-xs-12">
                                                    <p class="color_dgray">
                                                        @foreach($v1['sub_item'] as $k2=>$v2)
                                                        {{$v2}}<br>
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
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