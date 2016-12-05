<div class="page-sidebar">    
    <!-- X-NAVIGATION -->
    <ul class="x-navigation">
        <!-- Logo -->
        <li class="xn-logo">
            <a href="{{ Sitemap::getUrl('backend') }}"><img src="{{ asset('joli/img/logo.png') }}" alt=""/></a>     
            <a href="#" class="x-navigation-control"></a>       
        </li>
        <!-- End Logo -->

        <!-- Member Profile -->
        <li class="xn-profile">
            <div class="profile">
                <div class="profile-data">
                    <div class="profile-data-name">{{ User::get('name') }}</div>
                    <div class="profile-data-title">{{ User::get('title') }}</div>
                </div>
            </div>                                                                        
        </li>
        <!-- End Member Profile -->


        <!-- Mobile Main Menu -->
        <li class="menu_top_panel notpc"></li>
        <!-- End Mobile Main Menu -->


        <!-- Left Side Menu-->
        {!! $_pageMenuLeft !!}
        <!-- End Left Side Menu -->
    </ul>
    <!-- END X-NAVIGATION -->
</div>
