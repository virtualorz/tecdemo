@expr(ViewHelper::plugin()->load('jqueryui'))
@expr(ViewHelper::plugin()->load('jqueryvalidation'))

<!DOCTYPE html>
<html lang="zh-TW">
    <head>   
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>TechComm 科技共同空間</title>
    <!-- InstanceEndEditable -->
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/official/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    {!! ViewHelper::plugin()->renderCss() !!}
    <link href="{{asset('assets/official/css/combine.css')}}" rel="stylesheet">
    <link href="{{asset('assets/official/css/page.css')}}" rel="stylesheet">
    <link href="{{asset('assets/official/css/qa.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/btseditor/btseditor_content.css')}}">
    
    <!-- Hexagons CSS -->
	<link href="{{asset('assets/official/css/hexagons.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom Fonts -->
    <link href="{{asset('assets/official/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css">

	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        div.growlUI {
            background: url("{{ asset('assets/official/img/approved.png') }}") no-repeat 10px 10px;
            background-size:48px 48px;
        }
        div.growlUI2 {
            background: url("{{ asset('assets/official/img/remove.png') }}") no-repeat 10px 10px;
            background-size:48px 48px;
        }
    </style>
        <script type="text/javascript">
            var urlHome = {!! json_encode(Sitemap::getUrl("official")) !!};
                    var urlUploader = {!! json_encode(Sitemap::getUrl("official.upload")) !!};
                    var urlUploaderDelete = {!! json_encode(Sitemap::getUrl("official.upload.delete")) !!};
                    var urlUpload = {!! json_encode(FileUpload::getRootUrl()) !!};
                    var urlUploadABS = {!! json_encode(FileUpload::getRootUrlABS()) !!};
                    var urlLogin = {!! json_encode(Sitemap::getUrl("official.login")) !!};
                    var urlBase = {!! json_encode($_urlBase) !!};
                    var urlBasePath = {!! json_encode($_urlBasePath) !!};
                    var urlLast = {!! json_encode($_urlLast) !!};
                    var urlBack = {!! json_encode($_urlBack) !!};
                    var urlCurr = {!! json_encode($_urlCurr) !!} + window.location.hash;
                    var urlLoadingIcon = {!! json_encode(asset('joli/img/loaders/default.gif')) !!};
                    var routeName = {!! json_encode($_routeName) !!};
                    var csrf_token = {!! json_encode(csrf_token()) !!};
                    var appLocale = {!! json_encode($_appLocale) !!};
                    var message_width = 350;
                    var message_top = 100;
                    
        </script> 
    </head>
    <body id="page-top" class="index">
        @yield('head')
        @include('official.elements.header')
        <div class="contentmt">
        @yield('content')
        <div class="clearfix_"></div>
        </div>
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                	<img class="marginauto" src="{{ asset('assets/official/img/footerlogo.png') }}" width="170" height="55" alt=""/><br>
                    <p>國立臺灣大學 生命科學院</p>
                    <p>Copyright© 2017  All rights reserved.</p>
                </div>
                
          </div>
        </div>
    </footer>
    <div class="growlUI" style="display: none">
        <h1 style="padding:5px 5px 5px 75px;text-align:left;font-size:25px"></h1>
        <h2 style="padding:5px 5px 5px 75px;text-align:left;font-size:20px"></h2>
    </div>
    <div class="growlUI2" style="display: none">
        <h1 style="padding:5px 5px 5px 75px;text-align:left;font-size:25px"></h1>
        <h2 style="padding:5px 5px 5px 75px;text-align:left;font-size:20px"></h2>
    </div>
 
   

<!-- jQuery -->
    <script src="{{asset('assets/official/js/jquery.js')}}"></script>
    <script src="{{asset('plugins/jquery/jquery_extend.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('assets/official/js/bootstrap.min.js')}}"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{asset('assets/official/js/classie.js')}}"></script>
    <script src="{{asset('assets/official/js/cbpAnimatedHeader.js')}}"></script>

  	<!-- QA JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/node-waves/0.7.4/waves.min.js"></script>
    <script src="{{asset('assets/official/js/qa.js')}}"></script>

    <script src="{{asset('assets/official/js/jquery.blockUI.js')}}"></script>
    <script type="text/javascript" src="{{asset('/assets/official/js/global.js')}}"></script>
    {!! ViewHelper::plugin()->renderJs() !!}
    <script type="text/javascript">
            var csrf_token = {!! json_encode(csrf_token()) !!};
        </script>        
    @yield('script')
</body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        setbodyheight();
        adjmessagelocation();

       $(window).resize(function(){
            setbodyheight();
            adjmessagelocation();
        });

    });

    function setbodyheight()
    {
        if(($("body").height() + parseInt($(".navbar").css('height'))) - $(".clearfix_").height() < $(document).height())
        {
            $(".clearfix_").height("0");
            $(".clearfix_").height($(document).height() -  $("body").height() - parseInt($(".navbar").css('height')));
        }
    }

    function adjmessagelocation()
    {
        if($(document).width() <768)
        {
            message_width = parseInt($(document).width())-20;
            message_top = 60;
        }
        else
        {
            message_width = 350;
            message_top = 100;
        }
    }
    
</script>