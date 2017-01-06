@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">我的期刊發表</h2>

          		<div class="text-left mb-xs-0 mb--s">	
          	  	<a href="{{ asset('member/journal/add') }}" class="btn btn-sm btn-primary">
          	  	<i class="fa fa-plus"></i> 
          	  	新增期刊
          	  	</a>
				</div>
          		<!--預約活動 預約中-->
       	  	  <div class="tablebox">
             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
							<th>學術產出</th>
							<th class="ttw100">發表日期</th>
							<th>發表題目</th> 
							<th>期刊</th>
							<th class="ttw50 text-center">刪除</th> 
							</tr> 
						</thead> 

						<tbody> 
                            @foreach($listResult as $k=>$v)
							<tr> 
                                <td>{{ $journal[$v['type']] }}</td>
							  	<td>{{ $v['release_dt'] }}</td>
							  	<td><a href="{{ asset('member/journal/detail/id-'.$v['member_journal_id']) }}">{{ $v['topic'] }}</a></td> 
								<td>
								<a href="{{ asset('member/journal/detail/id-'.$v['member_journal_id']) }}">{{ $v['journal'] }}</a>
								</td>
								<td class="text-center">
									<a href="#" class="delete" data-id="{{ $v['member_journal_id'] }}"> 
									<i class="fa fa-times" aria-hidden="true"></i>
									</a>
								</td>
							</tr> 
                            @endforeach
						</tbody> 
					</table>
				  	</div>
                    <form id="form1" method="post" action="{{ Sitemap::node()->getChildren('delete')->getUrl() }}">
                        <input type='hidden' name='id' id='del_id'>
                    </form>
				  	@include('official.elements.pagination')

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
        initValidation();
        urlBack = location.href;
        $(".delete").click(function(e){
            e.preventDefault();
            
            $("#del_id").val($(this).attr('data-id'));
            $("#form1").submit();

            $(this).parent().parent().animate({
                opacity: 0,
            }, 1000, function() {
                // Animation complete.

            });
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