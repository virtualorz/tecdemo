@extends('Official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="container">
    	
        <div class="row">            
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
				<h2 class="bigtitle">會員使用條款 </h2>
              	<!--通知訊息-->
              	
           	  	<div class="messagebox">
           	  		<div class="row mb-xs-20 mb--b">
						<div class="col-sm-2 col-xs-3"><h5>關於使用：</h5>
						</div>
						<div class="col-sm-10 col-xs-9">
							<p>
						臺大生命科學院科技共同空間 (以下稱 TechComm) 提供服務予經註冊申請成功的使用者，其服務範圍包含 (但不限於) 服務清單載列之項目。
						<br>
						TechComm 保有視場地設施或人員的安全性及良好性，調整服務範圍、時間與內容之權利。使用者必須具備以下條件，始得開始使用本單位服務： <br>(1) 申請註冊登錄成為使用者 <br>(2) 經過諮詢或訓練認證通過 <br>(3) 經其計畫主持人或主管保證能支付相關使用費。
						<br><br>
						使用時，遵守各儀器使用規則、門禁管理規則、預約系統使用準則，並同意本單位違規使用罰則。倘若各設施有更嚴格的使用規範，從其規定。如使用當中發生故障或異常，需立即向管理單位反應。
						<br><br>特別提醒：各項設施預約後不得無故遲到或未到，並禁絕任何冒名頂替使用、出席、簽到退等情事。每個月的使用費將於下個月初結算，請自行前往本單位使用者平台查詢並進行核銷作業。倘若使用費款項拖欠達六個月，將會暫停您所屬計畫主持人的實驗室的使用權限。
							</p>
         	  			
         	  			</div>
          	  		</div>
				
         	  		<div class="line-schoolpage"></div>
         	  		<div class="row mb-xs-20 mb--b">
						<div class="col-sm-2"><h5>關於個資：</h5></div>
						<div class="col-sm-10 mt-xs-10">
						TechComm 個資蒐集同意聲明：為落實個人資料之保護，依照個人資料保護法第 8 條規定進行蒐集前之告知：
						<br><br>
						一、個人資料蒐集之目的及用途：目的在於進行使用者參與活動、使用設施之記錄管理及辦理相關行政作業，TechComm 將利用您所提供之 Email或聯絡電話通知您與本單位相關的訊息。
						<br><br>
						二、蒐集之個人資料類別：中/英文姓名、任職或就讀之機關學校及其系所部門單位、職稱、學號及身分證字號、聯絡電話、電子郵件信箱、參加本單位主辦、協辦活動之記錄、使用儀器設施之記錄及其使用費。亦包含申請者的指導教授或計畫主持人姓名、單位、職稱、計畫編號、著作發表資訊、帳務聯絡人之聯絡方式。倘若主辦或協同辦理活動另外需收集之資料，由主辦單位另訂之。
						<br><br>
						三、個人資料利用之期間、地區、對象：<br>
						(1) 期間：您同意由申辦電子護照之日起十年，由 TechComm 保存您的個人資料，以作為本單位及上行機關查詢、確認證明之用。 <br>
						(2) 地區：您的個人資料將用於 TechComm 提供服務之地區。 <br>
						(3) 對象：申辦電子護照之使用者及其計畫主持人。<br><br>
						
						四、依據個資法第 3 條規定，報名者對個人資料於保存期限內得行使以下權利：<br>(1) 查詢或請求閱覽。<br> (2) 請求製給複製本。<br> (3) 請求補充或更正。<br>(4) 請求停止蒐集、處理或利用。<br> (5) 請求刪除。<br><br>
         	  			
          	  			五、提醒：您可自由選擇提供個人資料，若其提供之資料不足或有誤時，將可能導致電子護照申請失敗或無法參加 TechComm 相關活動，亦或使用 TechComm 設施之權利。

          	  			</div>
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
        
    });
    
</script>
@endsection