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
                <ul class="nav navbar-nav navbar-left">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    
                    <li>
                        <a href="{{ asset('school') }}">特色學校</a>
                    </li>
                    
                    <li>
                        <a href="{{ asset('news') }}">活動消息</a>
                    </li>
                    
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">計畫介紹 <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ asset('plan') }}">計畫理念</a></li>
                        <li><a href="{{ asset('plan/time') }}">計畫時程</a></li>
                        <li><a href="{{ asset('plan/target') }}">計畫指標</a></li>
                      </ul>
                    </li>
                    
                    <li>
                        <a href="{{ asset('tutor') }}">輔導訪視</a>
                    </li>
                    
                    <li>
                        <a href="{{ asset('learning') }}">研習活動</a>
                    </li>
                    
                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    @if(User::Id() == null)
                    <li>
                        <a href="{{asset('login') }}">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                         登入
                        </a>
                    </li>
                    @else
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">你好，{{User::get('name')}} <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ asset('member') }}">學校專區</a></li>
                        <li><a href="{{ asset('login/logout') }}">登出</a></li>
                      </ul>
                    </li>
                    @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>