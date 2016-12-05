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
       	    	<div class="bigtitle">
                	<img src="{{ asset('assets/official/img/title_do.png') }}" width="225" height="40" alt=""/>
                </div>
            
            	<span class="line-schoolpage"></span>
                
                <h3 class="color_green">時間</h3>
                <p>{{ $dataResult[0]['date'] }} {{ $twCity[$dataResult[0]['city']] }} {{ $dataResult[0]['school_name'] }}</p>
                
                <span class="line-schoolpage"></span>
                
                <h3 class="color_green">參與對象</h3>
                <p>{{ $dataResult[0]['member'] }}</p>
                
                <span class="line-schoolpage"></span>
                
                <h3 class="color_green">執行紀錄</h3>
            	@if(isset($dataResult[0]['content']))
                @include('official.elements.btseditor', ['btseditorContent' => $dataResult[0]['content']])
                @endif
                
                <span class="line-schoolpage"></span>
                
                <h3 class="color_green">檔案下載</h3>	
                    @foreach($dataResult[0]['file'] as $k=>$v)
                    <a href="{{$v['url']}}" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>{{$v['name']}}</a><br>
                    @endforeach
              	
                <span class="line-schoolpage"></span>
                
                <h3 class="color_green">執行照片</h3>
                
                @foreach($dataResult[0]['photo'] as $k=>$v)
                    @if($k%2 == 0)
                    <div class="school-visitpic">
                    @endif

                    <div class="col-sm-6 col-xs-12">
                        <img class="img-responsive" src="{{ $v['urlScale0'] }}" alt="">
                        <p class="mt--s mb--s">{{ $v['text'] }}</p>
                    </div>

                    @if($k%2 == 1 || ($k == (count($dataResult[0]['photo']) -1) && $k%2 == 0))
                    </div>
                    @endif

                @endforeach
                
                <div class="row text-center">
                    <div class="col-md-12 text-center">
                        <a href="{{ asset('school/execute/id-'.$dataResult[0]['school_id']) }}" class="btn btn-default btn-lg"><i class="fa fa-angle-left" aria-hidden="true"></i> 上一頁</a>
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