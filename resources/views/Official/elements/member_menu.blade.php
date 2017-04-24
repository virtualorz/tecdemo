<div class="col-sm-3 col-xs-12">
            	<h2 class="bigtitle min768none text-center">會員專區</h2>
				<div class="spacer-40 max767none"></div>
                <a href="{{ asset('member/basic') }}" class="col-xs-6 contentbtn slmenu01">
                基本資料管理
                </a>
                
                <a href="{{ asset('member/journal') }}" class="col-xs-6 contentbtn slmenu02">
                我的期刊發表
                </a>
                
                <a href="{{ asset('member/message') }}" class="col-xs-6 contentbtn slmenu04">
                通知訊息@if($message_count != 0)<span class="badge">{{ $message_count }}</span>@endif
                </a>
                
                <a href="{{ asset('member/activity') }}" class="col-xs-6 contentbtn slmenu05">
                活動參與紀錄
                </a>

                <a href="{{ asset('member/e_portfolio') }}" class="col-xs-6 contentbtn slmenu03">
                活動認證
                </a>
                
                <a href="{{ asset('member/instrument') }}" class="col-xs-6 contentbtn slmenu06">
                儀器預約記錄
                </a>
                
                <a href="{{ asset('member/bill') }}" class="col-xs-6 contentbtn slmenu07">
                帳務管理
                </a>
                
          	</div>