@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<section id="login">
    <div class="container padding0">
        <div class="row">
            <div class="col-md-12 text-center">
				<h2>註冊完成</h2>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            	<div class="frombox">
					<form class="form-horizontal">
					  <h4 class="col-sm-12 text-center mb--l">註冊完成，請列印紙本並繳交至職科所辦公室以開通使用權限</h4>
					  
					  <div class="form-group">
						<div class="col-sm-12 text-center">
                          <a href="#" class="btn btn-sm btn-primary">列印</a>
						  <a href="{{ asset('login') }}" class="btn btn-sm btn-primary">登入</a>

						</div>
					  </div>
					</form>
				</div>
            </div>
            
            
            <div class="spacer6030"></div>
        </div> 
         
    </div>
    </section>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        

    });
    
</script>
@endsection