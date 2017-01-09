@extends('official.layouts.master')


@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">
            @include('official.elements.member_menu')
            
            <div class="col-sm-9 col-xs-12">
				<h2 class="bigtitle">臺大生科院科技共同空間活動記錄與認證
					<p>Certification of participation and techniques</p>
				</h2>

				<p class="mb--b">
					茲證明使用者參加臺大生科院科技共同空間各式活動記錄及能力認證<br>
					This is to certify the user has participated the following activities and passed related assessments.
				</p>
          
          		<!--預約活動 預約中-->
       	  	  <div class="messagebox">
				  <h4 class="clearfix mb-sm-10 mb--l">
           				<div class="floatleft">
							使用者姓名<p>Name of user</p>
				  		</div>
				  		
					  	<div class="floatleft" style="margin-left: 10px; padding-top: 5px">
                            <h3>: {{ User::get('name')}}</h3>
           	  			</div>
            	  </h4>
            	  
            		
             		<div class="table-responsive">
              		<table class="table table-striped"> 
						<thead> 
							<tr> 
							<th class="ttw100 text-center">活動日期<br><span class="small">Date</span></th>
							<th class="ttw160 text-center">活動名稱<br><span class="small">Training</span></th>
							<th class="ttw120 text-center">能力關聯<br><span class="small">Related ability</span></th>
							<th class="ttt50 text-center">時數<br><span class="small">Hours</span></th>
							<th class="ttt50 text-center">等級<br><span class="small">Level</span></th>
							<th class="ttt80 text-center">認證<br><span class="small">Certificate</span></th>
							<th class="ttt80 text-center">分數<br><span class="small">Score</span></th>
							<th class="ttt80 text-center">學分<br><span class="small">Credits</span></th>
							</tr>
						</thead> 

						<tbody> 
                            @foreach($activityResult as $k=>$v)
							<tr> 
                                <td>{{ $v['start_dt'] }}</td>
							  	<td>
								<a href="{{ asset('activity/reservation/id-'.$v['uid'].'-'.$v['salt']) }}">{{ $v['activity_name'] }}</a>
								</td>
							  	<td>{{ $v['plate_formResult_string'] }}</td>
							  	<td class="text-center">{{ $v['time'] }}</td>
							  	<td class="text-center">{{ $v['level'] }}</td>
								<td>出席 /<br>
                                @if($v['pass_status'] == 1)
							    通過 /<br>
                                @else
                                未通過 /<br>
                                @endif
                                @if($v['pass_type'] == 2 && $v['pass_status'] == 1)
							    考試後通過</td>
                                @endif
								<td class="text-center">{{ $v['pass_score'] }}</td>
								<td class="text-center">{{ $v['score'] }}</td>
							</tr> 
                            @endforeach
							
						</tbody> 
					</table>
				  	</div>
				  	
				  	
				    
			</div>
				<p class="text-center">Technology Commons, College of Life Science, National Taiwan University
                      <br>
                本證明請回到 TechComm 用印始證明生效 <br>
                The certification is valid after seal from TechComm</p>
                
                
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
        initValidation();
        urlBack = location.href;
        $(".cancel").click(function(e){
            e.preventDefault();
            
            $("#cancel_id").val($(this).attr('data-id'));
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