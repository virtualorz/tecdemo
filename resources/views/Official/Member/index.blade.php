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
            @include('official.elements.member_menu')
            
            <div id="visitdetail-content" class="col-md-10 col-sm-9 col-xs-12 edit-content">
       	    	<div class="bigtitle">
                	<img src="{{ asset('assets/official/img/title_schooldata.png') }}" width="225" height="40" alt=""/>
                </div>
                <form id="form1" class="form-horizontal" >
                <span class="line-schoolpage"></span>

					<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">地區</h3>
                        </div>
                        <div class="col-sm-9">
                          {{ $location[$dataResult[0]['location']] }}
                        </div>
                    </div>
                    
            	<span class="line-schoolpage"></span>
                
                	<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">縣市</h3>
                        </div>
                        <div class="col-sm-9">
                          {{ $twCity[$dataResult[0]['city']] }} / {{ $twTown[$dataResult[0]['city']][$dataResult[0]['town']][1] }}
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
                
       	  	  		
                	<div class="form-group">
                        <div class="col-sm-3">
                        <h3 class="color_green">帳號</h3>
                        </div>
                        <div class="col-sm-9">
                        	{{ $dataResult[0]['account'] }}
                        </div>
                    </div>
                    
                <span class="line-schoolpage"></span>
                    
                    <div class="form-group">
                        <div class="col-sm-3">
                        	<h3 class="color_green">學校</h3>
                        </div>
                        <div class="col-sm-9">
                        	{{ $dataResult[0]['school_name'] }}
                        </div>
                    </div>
        
                
                <span class="line-schoolpage"></span>
                </form>
          </div>
        </div>
    </div>
    
    <div class="spacer6030"></div>
    
	@include('official.elements.member_menu_mobile')
	<!-- InstanceEndEditable -->
    </div>
@endsection

@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        

    });
    
</script>
@endsection