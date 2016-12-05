<ul class="x-navigation x-navigation-horizontal x-navigation-panel">
    <!-- TOGGLE NAVIGATION -->
    <li class="xn-icon-button">
        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
    </li>
    <!-- END TOGGLE NAVIGATION -->

    <!-- SIGN OUT -->
    <li class="xn-icon-button pull-right">
        <a href="#" class="btnLogout" data-url="{{ Sitemap::getUrl('backend.login.logout') }}" data-mbTitle="{{ trans('message.question.logout') }}" ><span class="fa fa-sign-out"></span></a>                        
    </li> 
    <!-- END SIGN OUT -->
</ul>

<!-- Main Menu -->
<div class="menu_top_panel pc">
    <div class="menu_top_con">
        <div class="menu_top_list">
            {!! $_pageMenuTop !!}
            
            <a href="#" class="menu_top_more out">
                <span class="fa fa-chevron-up closeicon"></span>
                <span class="fa fa-chevron-down openicon"></span>
            </a>
        </div>
    </div>
</div>
<!-- End Main Menu -->