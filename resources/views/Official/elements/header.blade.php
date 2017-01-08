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
                    @if(User::Id() == null)
                    <li>
                        <a href="{{ asset('login') }}">登入</a>
                    </li>
                    @else
                    <li>
                        <a href="{{ asset('member') }}">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                         你好，{{User::get('name')}}
                         <span class="badge">1</span>
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