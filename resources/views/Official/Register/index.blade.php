@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<section id="login">
    <div class="container padding0">
        <div class="row">
            <div class="col-md-12 text-center">
				<h2>註冊</h2>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
            	<div class="frombox">
					<form class="form-horizontal" id="form1" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
					  <div class="form-group">
						<label class="col-sm-3 control-label">E-Mail</label>
						<div class="col-sm-8">
						  <input type="email" name="email" id="data-email" class="form-control required" placeholder="Email">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">登入密碼</label>
						<div class="col-sm-8">
						  <input type="password" name="password" id="data-password"class="form-control required" placeholder="Password">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">確認密碼</label>
						<div class="col-sm-8">
						  <input type="password" name="passwordR" id="data-passwordR" class="form-control required" placeholder="Password">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">姓名</label>
						<div class="col-sm-8">
						  <input type="text" name="name" id="data-name" class="form-control required" placeholder="">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">學號/證件卡號</label>
						<div class="col-sm-8">
						  <input type="text" name="card_id_number" id="data-card_id_number" class="form-control required" placeholder="">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">機關/學校</label>
						<div class="col-sm-8">
						  	<select class="form-control required" name="organize" id="data-organize">
                                <option value="">{{trans('page.text.select_item')}}</option>
                                @foreach($organizeResult as $k=>$v)
                                <option value="{{$v['id']}}">{{$v['name']}}</option>
                                @endforeach
							</select>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">系所/部門</label>
						<div class="col-sm-8">
						  	<select class="form-control required" name="department" id="data-department">
                                <option value="">{{trans('page.text.select_item')}}</option>
							</select>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">職稱</label>
						<div class="col-sm-8">
						  <input type="text" name="title" id="data-title" class="form-control" placeholder="">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-sm-3 control-label">指導教授</label>
                        <div class="col-sm-8">
						  	<select class="form-control required" name="pi" id="data-pi">
                                <option value="">{{trans('page.text.select_item')}}</option>
							</select>
					    </div>
                     </div>

                     <div class="form-group">
						<label class="col-sm-3 control-label">聯絡電話</label>
						<div class="col-sm-8">
						  <input type="text" name="phone" id="data-phone" class="form-control required" placeholder="">
						</div>
					  </div>

                      <div class="form-group">
						<label class="col-sm-3 control-label">實驗室電話</label>
						<div class="col-sm-8">
						  <input type="text" name="lab_phone" id="data-lab_phone" class="form-control required" placeholder="">
						</div>
					  </div>
					  	
					  	<div class="form-group">
							<div class="col-sm-12 text-center">
								<div class="checkbox"> 
									<label> 
										<input type="checkbox" name="member_agree" class='required'> 我同意<a href="{{ asset('policy')}}" Target="_blank">會員使用條款</a>
									</label> 
								</div>
							</div>
						</div>
					  
					  <div class="form-group">
						<div class="col-sm-12 text-center">

						  <a href="#" class="btn btn-default btn-sm">取消</a>

						  <a href="#" class="btn btn-sm btn-primary" id="submit_but">送出</a>


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
{!! ViewHelper::plugin()->renderJs() !!}
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
        urlBack = "{{ asset('register/finish') }}";
        $("#data-organize").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_department')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-organize").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['name']+"</option>";
                    }
                    $("#data-department").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });

        $("#data-department").change(function(){
            var ajaxProp = {
                url: "{{ Sitemap::node()->getChildren('get_pi')->getUrl() }}",
                type: "get",
                dataType: "json",
                data: {'id':$("#data-department").val(),'_token':csrf_token},
                error: function (jqXHR, textStatus, errorThrown) {
                    
                },
                success: function (response) {
                    $html = "<option value=''>{{trans('page.text.select_item')}}</option>";
                    for(var key in response)
                    {
                        $html += "<option value='"+response[key]['id']+"'>"+response[key]['name']+"</option>";
                    }
                    $("#data-pi").html($html);
                    
                }
            }
            $.ajax(ajaxProp);
        });

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