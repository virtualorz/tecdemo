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
    <title>特色遊學計畫</title>
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

        
        <script>
            var urlUploader = {!! json_encode(Sitemap::getUrl("official.upload")) !!};
            var urlUploaderDelete = {!! json_encode(Sitemap::getUrl("official.upload.delete")) !!};
            var urlUpload = {!! json_encode(FileUpload::getRootUrl()) !!};
        </script>
        @yield('head')
    </head>
    <body id="page-top" class="index">
        @include('official.elements.header')

        @yield('content')
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                	<img class="marginauto" src="{{ asset('assets/official/img/footerlogo.png') }}" width="160" height="95" alt=""/><br>
                  	<p class="max767none">主辦單位 : 教育部國民及學前教育署 承辦單位 : 國立臺灣師範大學
                  	<br>
			Design by <a href="http://btsdesign.com.tw/" target="_blank">BTS Design</a>
                  	</p>
                  	
                  	<div class="col-sm-6 col-xs-12 min768none">
                    	<p>主辦單位 : 教育部國民及學前教育署</p>
                        </div>
                    
                    <div class="col-sm-6 col-xs-12 min768none">
                    	<p>承辦單位 : 國立臺灣師範大學</p>
                    </div>
                    
                    <div class="col-xs-12 min768none">
                    	<p>
                    	Design by <a href="http://btsdesign.com.tw/" target="_blank">BTS Design</a>
                    	</p>
                    </div>
                    
                    <p>Copyright &copy; 特色遊學計畫 2016</p>
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
    {!! ViewHelper::plugin()->renderJs() !!}
    <script type="text/javascript">
            var csrf_token = {!! json_encode(csrf_token()) !!};
        </script>        
    @yield('script')
</body>
</html>
