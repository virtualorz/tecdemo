@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
           @include('Official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">基本資料管理
             	<a href="{{ asset('member/basic/print') }}" class="btn btn-default btn-sm floatright max767none"><i class="fa fa-print" aria-hidden="true"></i> 列印</a>
             	
             	</h2>
             
              	<form id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
           	  	<div class="messagebox">
           	  		
          	  		
          	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>姓名*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="name" id="data-name" value="{{ $dataResult['name'] }}">
						</div>
					</div>
       	  			
       	  			<div class="line-schoolpage"></div>
       	  			
       	  			<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>卡號</h5></div>
                        <div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['card_id_number'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10">
						  <h5>機關/學校</h5></div>
                        <div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['organize_name'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>系所/部門</h5></div>
						<div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['department_name'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>職稱</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control" name="title" id="data-title" value="{{ $dataResult['title'] }}">
						</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>E-Mail</h5></div>
						<div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['email'] }}</div>
					</div>

                    <div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>密碼*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="password" class="form-control required" name="password" id="data-password" value="{{ $dataResult['password'] }}">
						</div>
					</div>

                    <div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>密碼確認*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="password" class="form-control required" name="passwordR" id="data-password" value="{{ $dataResult['password'] }}">
						</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>聯絡電話*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="phone" id="data-phone" value="{{ $dataResult['phone'] }}">
						</div>
					</div>
         	  		
         	  		<div class="line-schoolpage"></div>

                    <div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>實驗室電話*</h5></div>
						<div class="col-md-7 col-sm-9">
							<input type="text" class="form-control required" name="lab_phone" id="data-lab_phone" value="{{ $dataResult['lab_phone'] }}">
						</div>
					</div>
         	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>指導教授</h5></div>
						<div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['pi_name'] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 mb-xs-10"><h5>身份類別</h5></div>
						<div class="col-md-9 col-sm-9 col-xs-8">{{ $member_typeResult[$dataResult['type']] }}</div>
					</div>
        	  		
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 col-xs-4"><h5>生效日期</h5></div>
						<div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['start_dt'] }}</div>
          	  		</div>
					
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 col-xs-4"><h5>使用期限</h5></div>
                        <div class="col-md-9 col-sm-9 col-xs-8">{{ $dataResult['start_dt'] }} - {{ date('Y.m.d',strtotime('+'.$dataResult['limit_month'].' month',strtotime($dataResult['start_dt_org']))) }}</div>
          	  		</div>
					
         	  		<div class="line-schoolpage"></div>
         	  		
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-md-3 col-sm-3 col-xs-4"><h5>使用權限</h5></div>
						<div class="col-md-9 col-sm-9 col-xs-8">
                            @foreach($permission as $k=>$v)
                                        @if(in_array($k,$permissionResult)) {{ $v.' ' }} @endif
                            @endforeach
                        </div>
          	  		</div>
					
         	  		<div class="line-schoolpage"></div>
         	  		
					<div class="row">
						<div class="col-sm-12 text-center mt--b mb--b">

						  <a href="{{ asset('member') }}" class="btn btn-default btn-sm">取消</a>

						  <a href="#" class="btn btn-sm btn-primary" id="save_btn">儲存</a>
						</div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-sm-12 text-center min768none">
						 <a href="member.html" class="btn btn-default btn-sm"><i class="fa fa-angle-left"></i> 回會員專區</a>
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