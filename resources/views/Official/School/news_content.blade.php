@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
   	@include('official.elements.school_header')
    
	<div class="spacer3015"></div>
    

    
    <div class="container">
        <div class="row">
            @include('official.elements.school_menu')
            
            <div id="visitdetail-content" class="col-md-10 col-sm-9 col-xs-12">
              	<span class="line-schoolpage"></span>
                  	<div class="row">
                    <div class="col-xs-12">
                        <h2 class="color_green">{{ $dataResult[0]['title'] }}</h2>
                        <div class="spacer-10"></div>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12">
                      	<p><i class="fa fa-clock-o" aria-hidden="true"></i> 發表日期：{{ $dataResult[0]['created_at'] }}</p>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12">
                     	<p class="whoedit"><i class="fa fa-pencil" aria-hidden="true"></i> 發表單位：{{ $dataResult[0]['school_name'] }}</p>
                    </div>
                    
                    </div>
           	  	<span class="line-schoolpage"></span>
                
                @if(isset($dataResult[0]['content']))
                @include('official.elements.btseditor', ['btseditorContent' => $dataResult[0]['content']])
                @endif

                <span class="line-schoolpage"></span>
                		/分享列/
                <span class="line-schoolpage"></span>
                
                <div class="row text-center">
                    <div class="col-md-12 text-center">
                        <a href="{{ asset('school/news/id-'.$dataResult[0]['school_id']) }}" class="btn btn-default btn-lg"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="spacer6030"></div>
    
	@include('official.elements.school_menu_mobile')
	<!-- InstanceEndEditable -->
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
       

    });
    
</script>
@endsection