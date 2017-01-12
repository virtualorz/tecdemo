@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
            
            <div id="faq" class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">帳務管理</h2>

			<form id="formq" method="get" action="{{ Sitemap::node()->getUrl() }}">
          	<div class="panel-group wrap" id="accordion" role="tablist" aria-multiselectable="true">
            					
                                <div class="panel">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#faqOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                        <h5>搜尋</h5>
                                        </a>
                                    </h4>
                                    </div>
                                    
                                    <div id="faqOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                        	<div class="row faqrow">
                                                <div class="col-md-2 col-sm-2 col-xs-3">
                                                年/月
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-9">
                                                    <input type="month" name="month" value="{{ Request::input('month', '') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="row faqrow">
                                                <div class="col-md-2 col-sm-2 col-xs-3">
                                                金額
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-9">
                                                    <input type="number" name="pay" value="{{ Request::input('pay', '') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="row faqrow">
                                                <div class="col-md-2 col-sm-2 col-xs-3">
                                                狀態
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-9" name="pay_status">
                                                    <select class="form-control" name="pay_status">
                                                      <option value="">{{trans('page.text.select_item')}}</option>
                                                      <option value='1' @if(Request::input('pay_status', '') == '1') selected @endif>已繳費</option>
													  <option value='0' @if(Request::input('pay_status', '') == '0') selected @endif>未繳費</option>
													</select>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
												<div class="col-xs-12 mt--s text-center">
													<a href="#" class="btn btn-default">重置</a>
													<a href="#" class="btn btn-primary" id="btn_search">搜尋</a>
												</div>
											</div>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                

          	</div>
            </form>
       	  	  	<div class="tablebox" id="search_table">
       	  	  		
                    @if(Request::input('month', '') != '' || Request::input('pay', '') != '' || Request::input('pay_status', '') != '')
					<h5>搜尋結果：</h5>
                    <p class="mb--b">{{ Request::input('month', '') }}/{{ Request::input('pay', '') }}元/{{ trans('enum.pay_status.'.Request::input('pay_status', '')) }}
          				<a href="#" class="btn btn-default btn-xs max767right" id="cancel_btn">
							取消
						</a>
          			</p>
                    @endif

             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
							<th class="ttw100">月份</th>
							<th><span class="ttw100">單號</span></th>
							<th class="ttt80">應繳金額</th>
							<th class="ttw80">狀態</th>
							<th class="ttw80 text-center max767none">列印</th> 
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($listResult as $k=>$v)
							<tr> 
                                <td>{{ $v['pay_year'] }}.{{ $v['pay_month'] }}</td>
							  	<td>
                                <a href="{{ asset('member/bill/detail/id-'.$v['uid'].'-'.$v['salt']) }}">{{ date('ym',strtotime($v['pay_year'].'-'.$v['pay_month'].'-01')) }}{{ $v['salt'] }}</a>
								</td>
                                <td>{{ $v['total'] }}</td>
								<td>
                                    @if($v['payment_count'] != 0)
                                    <span class="label label-default">已繳費</span>
                                    @else
                                    <span class="label label-success">未繳費</span>
                                    @endif
                                </td>
								<td class="text-center max767none">
                                    @if($v['payment_count'] == 0)
                                    <a href="{{ asset('member/bill/detail/print/id-'.$v['uid'].'-'.$v['salt']) }}"><i class="fa fa-print"></i></a>
                                    @endif
								</td>
							</tr> 
                            @endforeach
						</tbody> 
					</table>			  	
				  	</div>
				
         		@include('official.elements.pagination')

          		</div>
           
           		
			  		<div class="row">
					<div class="col-sm-12 text-center min768none">
						 <a href="member.html" class="btn btn-default btn-sm"><i class="fa fa-angle-left"></i> 回會員專區</a>
					</div>
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
        $("#btn_search").click(function(e){
            e.preventDefault();
            $("#formq").submit();
        });
        $("#cancel_btn").click(function(e){
            e.preventDefault();
            //$("#search_table").hide("slow");
            $("#search_table").animate({
                opacity: 0,
            }, 1000, function() {
                // Animation complete.
                location.href= "{{ asset('member/bill') }}";
            });
        });
    });
    
</script>
@endsection