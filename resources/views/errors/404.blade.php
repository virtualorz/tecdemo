<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/book.dwt" codeOutsideHTMLIsLocked="false" -->

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>404｜TechComm 科技共同空間</title>
    <!-- InstanceEndEditable -->
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/official/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('assets/official/css/combine.css')}}" rel="stylesheet">
    <link href="{{asset('assets/official/css/page.css')}}" rel="stylesheet">
    <link href="{{asset('assets/official/css/qa.css')}}" rel="stylesheet">
    
    <!-- Hexagons CSS -->
	<link href="{{asset('assets/official/css/hexagons.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom Fonts -->
    <link href="{{asset('assets/official/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css">
    
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <a class="navbar-brand" href="{{ asset('/') }}"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                
                <ul class="nav navbar-nav navbar-right">
                   
                    <li>
                        <a href="{{ asset('activity') }}">參與活動</a>
                    </li>
                    
                    <li>
                        <a href="{{ asset('instrument') }}">預約儀器</a>
                    </li>
                    
                    <li>
                        <a href="{{ asset('contact_us') }}">聯絡我們</a>
                    </li>
                    @if(User::id() == null)
                    <li>
                        <a href="{{ asset('login') }}">登入</a>
                    </li>
                    @else
                    <li>
                        <a href="{{ asset('member') }}">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                         你好，{{User::get('name')}}
                         @if(isset($message_count) && $message_count != 0)
                         <span class="badge">{{ $message_count }}</span>
                         @endif
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ asset('login/logout') }}">登出</a>
                    </li>
                    @endif
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    
    
	<div class="contentmt">    
    <!-- InstanceBeginEditable name="schoolcontent" -->
    <section id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
            	<img class="max767none" src="{{asset('assets/official/img/icon_404.png')}}" width="180" height="180" alt=""/>
				<h1 style="font-size: 72px">404</h1>
			  	<h2>Oops! Page not found.</h2><br>
				<p>Sorry，此頁面已不存在！<br>無法找到您想找的網頁，頁面將在<span id='time'>3</span>秒後返回首頁。</p>
            </div>
            <div class="spacer6030"></div>
            
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-xs-12">
					<div class="col-sm-12 text-center">

					  <a href="#" class="btn btn-default btn-sm btnBack">回上一頁</a>

					  <a href="{{ asset('/') }}" class="btn btn-sm btn-primary">回首頁</a>


			  </div>
            </div>
            
            
            <div class="spacer6030"></div>
        </div> 
         
    </div>
    </section>
    <!-- InstanceEndEditable -->
    </div>
    
    
    <!-- Clients Aside -->

    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                	<img class="marginauto" src="{{asset('assets/official/img/footerlogo.png')}}" width="170" height="55" alt=""/><br>
                    <p>國立臺灣大學 生命科學院</p>
                    <p>Copyright© 2017  All rights reserved.</p>
                </div>
                
          </div>
        </div>
    </footer>

 
   

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
</body>

<!-- InstanceEnd --></html>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btnBack").click(function(e){
            e.preventDefault();
            window.history.back();
        });

        setInterval(function(){
            $("#time").html(parseInt($("#time").html())-1);
            if($("#time").html() == "0")
            {
                location.href = "{{ asset('/') }}";
            }
        },1000);

    });
    
</script>
