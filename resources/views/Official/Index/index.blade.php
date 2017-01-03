@extends('official.layouts.master')



@section('head')
@endsection



@section('content')
<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <!-- Header -->
    <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>國立台灣大學生命科學院<br>
                        TechComm 科技共同空間</h1>
                        <h3 class="mt--m mb--s max767none">TechComm 是台大生命科學院的科技共同空間，提供生物科學相關科技服務，開放師生使用。</h3>
                        <input type="text" class="form-control intro-message-search">
                        <ul class="list-inline intro-social-buttons">
                            <li>
                                <a href="https://github.com/IronSummitMedia/startbootstrap" class="btn btn-lg btn-primary"><span class="network-name">找活動</span></a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-default btn-lg btn-primary"><span class="network-name">找儀器</span></a>
                            </li>
                      </ul>
                  </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>

	<!-- Header -->

    <!-- 系統公告 -->
    <section id="inx_news">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-heading">
                    	系統公告
                    </h3>
                </div>
            </div>
            
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.3.31</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="news.html">要在 TC Booking 預約，需先領有TC Passport</a>。
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.3.1</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="news.html">使用資格需經認證，護照上記錄所有認證項目 (取得認證 4.4)。</a>               
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.2.28</p>
                </div>
                <div class="col-md-8 col-sm-10">
					<a href="news.html">每項儀器預約次數每人限定三次，用畢後方能再行預約 (2009.05.18 正式實施)。</a>              
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2015.11.13</p>
                </div>
				<div class="col-md-8 col-sm-10"><a href="news.html">颱風三樓淹水，今日三樓儀器停用</a>
                </div>
            </div>
        </div>
    </section>
    
    
    <!-- 最新活動 -->
    <section id="inx_news2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-heading">
                    	最新活動
                    </h3>
                </div>
            </div>
            
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.12.31</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="#">染色質免疫沉澱 (ChIP) 自動化樣本製備工作坊</a>               
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.12.27</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="#">TC 年度說明會 (會後開放 TC 空間參觀)</a>
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.12.25</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="#">流式細胞分析儀工作坊 (下午上機)</a>
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.11.19</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="#">Optical Mapping and Genome de novo Assembly</a>               
                </div>
            </div>
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-sm-2">
                    <p>2016.11.17</p>
                </div>
                <div class="col-md-8 col-sm-10">
                	<a href="#">化學系蛋白質體質譜核心與生科院 TechComm 蛋白質體平台聯合說明會</a>               
                </div>
            </div>
            
            <div class="row text-center">
                <div class="col-md-12">
            		<a href="activity.html" class="btn btn-default btn-sm mt--b">MORE <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
            </div>
            
        </div>
    </section>
    
    
    
    <!-- 聯絡負責人
    <section id="inx_contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="color_blue mb--b">
                    	如有任何預約問題，請 E-Mail 通知各平台技術人員：
                    </h4>
                </div>
            </div>
            
            
            <div class="row inxnewslist">
                <div class="col-md-2 col-xs-6">
                	<a href="#">TC1 高毓鄖</a>
                </div>
                <div class="col-md-2 col-xs-6">
                	<a href="#">TC2 周慧柔</a>
                </div>
                <div class="col-md-2 col-xs-6">
                	<a href="#">TC3 董于瑄</a>
                </div>
                <div class="col-md-2 col-xs-6">
                	<a href="#">TC4 江榮春</a>
                </div>
                <div class="col-md-2 col-xs-6">
                	<a href="#">TC5 莊以君</a>
                </div>
                <div class="col-md-2 col-xs-6">
                	<a href="#">TCX 湯凱鈞</a>
                </div>
            </div>
          
            
        </div>
    </section> -->
    <!-- InstanceEndEditable -->
    </div>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function () {
       

    });
    
</script>
@endsection