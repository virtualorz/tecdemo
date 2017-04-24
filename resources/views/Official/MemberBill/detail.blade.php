@extends('Official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('Official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">帳單內容</h2>
				
       	  	  	<div class="messagebox">
					<div class="row">
						<div class="col-xs-4 col-sm-2">帳單月份</div>
						<div class="col-xs-8 col-sm-10">{{ $dataResult['pay_year'] }}年{{ $dataResult['pay_month'] }}月</div>
					</div>
         			<div class="line-schoolpage"></div>
         			
         			<div class="row">
						<div class="col-xs-4 col-sm-2">應繳金額</div>
						<div class="col-xs-8 col-sm-10">{{ $dataResult['total'] }}元</div>
					</div>
         			<div class="line-schoolpage"></div>
         			
         			<div class="row">
						<div class="col-xs-4 col-sm-2">單號</div>
						<div class="col-xs-8 col-sm-10">{{ date('ym',strtotime($dataResult['pay_year'].'-'.$dataResult['pay_month'].'-01')) }}{{ $dataResult['salt'] }}</div>
					</div>
         			<div class="line-schoolpage"></div>
         			
         			<div class="row">
						<div class="col-xs-4 col-sm-2">使用單位</div>
                        <div class="col-xs-8 col-sm-10">{{ $dataResult['organize_name'] }}/{{ $dataResult['department_name'] }}</div>
					</div>
         			<div class="line-schoolpage"></div>
         			
					<h4 class="mb--xs">使用記錄</h4>
        			
             		<div class="table-responsive" style="border: 1px solid #ddd">
              		<table class="table table-striped" style="margin-bottom: 0"> 
						<thead> 
							<tr> 
                            <th class="ttt80">繳費代碼</th>
							<th class="ttw100">日期</th>
							<th class="ttt100">時段</th>
							<th><span class="ttw100">儀器名稱</span></th>
							<th class="ttt80">使用人</th>
							<th class="ttt80">應繳金額</th>
                            <th class="ttt80">折扣</th>
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($reservationlogResult as $k=>$v)
							<tr> 
                                <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
							  	<td>{{ $v['date'] }}</td>
							  	<td>{{ $v['use_dt_start'] }}-{{ $v['use_dt_end'] }}</td>
							  	<td>
								{{ $v['instrument_name'] }}
								</td>
							  	<td>{{ $v['member_name'] }}</td>
								<td>{{ $v['pay'] }}</td>
                                <td>
									@if($v['discount_JSON'] != "")
									{{ $discount_type[$v['discount_JSON']['type']] }} : <br>
                                    {{ $v['discount_JSON']['number'] }}
									@if($v['discount_JSON']['type'] == 1) % @else 元 @endif
									@endif
								</td>
							</tr> 
                            @endforeach
						</tbody> 
					</table>
				  	</div>
       			  <div class="line-schoolpage"></div>
        					
        			<h4 class="mb--xs">耗材花費</h4>
         			
             		<div class="table-responsive" style="border: 1px solid #ddd">
              		<table class="table table-striped" style="margin-bottom: 0"> 
						<thead> 
							<tr>
							<th class="ttt80">繳費代碼</th>
                            <th><span class="ttw100">項目</span></th> 
							<th class="ttw100">數量</th>
							<th class="ttt80">應繳金額</th>
							</tr> 
						</thead> 

						<tbody> 
							@foreach($reservationlogResult as $k=>$v)
                                @foreach($v['supplies_JOSN'] as $k1=>$v1)
                                <tr>
                                    <td>{{ $v['create_date_ym'] }}{{ $v['salt'] }}</td>
                                    <td>{{ $v1['name'] }}</td>
                                    <td>{{ $v1['count'] }}個</td>
                                    <td>{{ $v1['total'] }}</td>
                                </tr>
                                @endforeach
                            @endforeach
						</tbody> 
					</table>
				  	</div>
        			<div class="line-schoolpage"></div>
        			
        			<div class="row">
						<div class="col-xs-4 col-sm-2">備註</div>
						<div class="col-xs-8 col-sm-10">{{ $dataResult['remark'] }}</div>
					</div>
        			<div class="line-schoolpage"></div>
         			
         			<div class="row">
					 	@if($dataResult['create_admin_id'] != null && $dataResult['payment_count'] == 0)
						<div class="col-sm-12 text-center mb--b">
						  <a href="{{ asset('member/bill/print/id-'.$id) }}" class="btn btn-sm btn-primary max767none"><i class="fa fa-print"></i> 列印</a>
						</div>
						@endif
					</div>
          		</div>
           			<div class="row">
						<div class="col-sm-12 text-center">
						  <a href="{{ asset('member/bill') }}" class="btn btn-default btn-sm"><i class="fa fa-angle-left"></i> 回上一頁</a>
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