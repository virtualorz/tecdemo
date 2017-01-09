@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
				<h2>重設密碼</h2>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            	<div class="frombox">
                    @if($status == 0)
                    <div class="form-group">時效已經過期，請重新申請</div>
                    @else
					<form class="form-horizontal" id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					  <div class="form-group">
						<label class="col-sm-3 control-label">E-Mail</label>
						<div class="col-sm-8">
						  <input type="email" class="form-control" placeholder="email" name="email" id="data-email">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">新密碼</label>
						<div class="col-sm-8">
						  <input type="password" class="form-control" placeholder="Password" name="password" id="data-password">
						</div>
					  </div>
						
					  <div class="form-group">
						<label class="col-sm-3 control-label">密碼確認</label>
						<div class="col-sm-8">
						  <input type="password" class="form-control" placeholder="Password" name="passwordR" id="data-passwordR">
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-12 text-center">

						  <a href="{{ asset('/') }}" class="btn btn-default btn-sm">取消</a>

						  <a href="#" class="btn btn-sm btn-primary" id="submit_but">重設密碼</a>
                          <input type='hidden' name='id' id='data-id' value='{{ $id }}'>

						</div>
					  </div>
					</form>
                    @endif
				</div>
            </div>
            
            
            <div class="spacer6030"></div>
        </div> 
         
    </div>
    </section>
@endsection


@section('script')
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        $("#submit_but").click(function(e){
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