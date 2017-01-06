@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
           @include('official.elements.member_menu')
            
            <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">新增期刊</h2>
              	
           	  	<div class="messagebox">
           	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 col-xs-4"><h5>新增日期</h5></div>
                        <div class="col-md-10 col-sm-9 col-xs-8">{{ date('Y.m.d') }}</div>
          	  		</div>
					
         	  		<div class="line-schoolpage"></div>
          	  		
          	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>學術產出*</h5></div>
						<div class="col-md-7 col-sm-9">
							<select name="journal_type" id="data-journal_type" class="form-control required">
                                        <option value="">{{trans('page.text.select_item')}}</option>
                                        @foreach($journal as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                            </select>
						</div>
					</div>
       	  			
       	  			<div class="line-schoolpage"></div>
       	  			
       	  			<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>發表日期*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="date" class="form-control required" name="release_dt" id="data-release_dt">
						</div>
					</div>
       	  			
       	  			<div class="line-schoolpage"></div>
       	  			
       	  			
       	  			<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>發表題目*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="topic" id="data-topic">
						</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>期刊*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="journal" id="data-journal">
						</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>作者*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="author" id="data-author">
						</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-2 col-sm-3 mb-xs-10"><h5>發表超連結*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="url" id="data-url">
						</div>
						
					</div>
					<div class="row">
						<div class="col-sm-12 text-center mt--b mb--b">

						  <a href="{{ asset('member/journal') }}" class="btn btn-default btn-sm">取消</a>

						  <a href="#" class="btn btn-sm btn-primary" id="save_btn">送出</a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						 <a href="{{ asset('member/journal') }}" class="btn btn-default btn-sm"><i class="fa fa-angle-left"></i> 回上一頁</a>
					</div>
				</div>	
			</div>
            </form>
          		
            </div>
    </div>
    <div class="spacer6030"></div>
@endsection

@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        $("#save_btn").click(function(e){
            e.preventDefault();
            $("#form1").submit();
        });
    });

    function initValidation() {
        $('#form1').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                }) === false) {
                    return false;
                }
            }
        });
    }
    
</script>
@endsection