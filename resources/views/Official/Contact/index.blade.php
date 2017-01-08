@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">            
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
				<h2 class="bigtitle">聯絡我們</h2>
          	  	
                @foreach($listResult as $k=>$v)
           	  	<div class="tablebox">
                    <h4 class="mb--b">{{ $v['name'] }}</h4>
                    @if(isset($v['content']))
                    @include('official.elements.btseditor', ['btseditorContent' => $v['content']])
                    @endif
				</div>
                @endforeach
         		
          		<div class="text-center">	
          	  	<a href="#" class="btn btn-sm btn-default" id="back_btn">
          	  	<i class="fa fa-angle-left"></i> 
          	  	回上一頁
          	  	</a>
				</div>
          
            </div>
    	</div>
    </div>
    
    <div class="spacer6030"></div>
@endsection


@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        
        $("#back_btn").attr('href',urlBack);
    });
    
</script>
@endsection