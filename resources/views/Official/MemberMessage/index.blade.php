@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="container">
    	
        <div class="row">
            @include('Official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">通知訊息</h2>
              	<!--通知訊息-->
              	
           	  	<div class="tablebox">
             		<div class="table-responsive">
              		<table class="table"> 
               		<thead> 
                    	<tr> 
                   	  		<th class="ttw100">日期</th> 
                   	  		<th>主題</th>
							<th class="ttw80">狀態</th>
                   	    	<th class="ttw80">發訊者</th> 
                   	    </tr> 
                    </thead> 
                    
                    <tbody> 
						@foreach($listResult as $k=>$v)
                    	<tr> 
                       	  <td>{{ $v['created_at'] }}</td> 
							<td>
                          	<a href="{{ asset('member/message/detail/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['title'] }}</a>
                           	</td>
                            <td><span class="label label-{{trans('enum.label.is_read.'.$v['is_read'])}}">{{ trans('enum.is_read.'.$v['is_read']) }}</span></td> 
                            <td>{{ $v['create_admin_name'] }}</td>
                        </tr> 
						@endforeach
                    </tbody> 
                </table>
					</div>
					
					
					@include('Official.elements.pagination')
					
				</div>
          
          		<div class="text-center min768none">	
          	  	<a href="member.html" class="btn btn-sm btn-default">
          	  	<i class="fa fa-angle-left"></i> 
          	  	回會員專區
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
        
    });
    
</script>
@endsection