@expr(ViewHelper::plugin()->load('jquery'))
@expr(ViewHelper::plugin()->load('jqueryui'))
@expr(ViewHelper::plugin()->load('json'))
@expr(ViewHelper::plugin()->load('bootstrap'))
@expr(ViewHelper::plugin()->load('jqueryvalidation'))
@expr(ViewHelper::plugin()->load('jscookie'))
<!DOCTYPE html>
<html>
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ trans('app.backend_name') }} - {{ $_pageTitle }}</title>      
        <link rel="Shortcut Icon" type="image/x-icon" href="{{asset('assets/official/img/favicon-20160909095846820.ico')}}" />
        {!! ViewHelper::plugin()->renderCss() !!}
        <link rel="stylesheet" type="text/css" href="{{ asset('joli/css/theme-white.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('joli/css/style.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/btseditor/btseditor_content.css')}}">

        <script type="text/javascript">
            var urlHome = {!! json_encode(Sitemap::getUrl("backend")) !!};
                    var urlUploader = {!! json_encode(Sitemap::getUrl("backend.upload")) !!};
                    var urlUploaderDelete = {!! json_encode(Sitemap::getUrl("backend.upload.delete")) !!};
                    var urlUpload = {!! json_encode(FileUpload::getRootUrl()) !!};
                    var urlLogin = {!! json_encode(Sitemap::getUrl("backend.login")) !!};
                    var urlBase = {!! json_encode($_urlBase) !!};
                    var urlBasePath = {!! json_encode($_urlBasePath) !!};
                    var urlLast = {!! json_encode($_urlLast) !!};
                    var urlBack = {!! json_encode($_urlBack) !!};
                    var urlCurr = {!! json_encode($_urlCurr) !!} + window.location.hash;
                    var urlLoadingIcon = {!! json_encode(asset('joli/img/loaders/default.gif')) !!};
                    var routeName = {!! json_encode($_routeName) !!};
                    var csrf_token = {!! json_encode(csrf_token()) !!};
                    var appLocale = {!! json_encode($_appLocale) !!};
                    var isSearch = {!! json_encode($_isSearch) !!};
                    var currMenuClass = {!! $_currMenuClassJson !!};
        </script>        
        @yield('head')
    </head>
    <body>
        <div class="page-container">
            @include('backend.elements.menu_left')
            <div class="page-content">
                @include('backend.elements.menu_top')

                <ul class="breadcrumb">
                    {!! $_pageNaviPath !!}
                </ul>

                <div class="page-title">
                    <h2>{{ $_pageTitle }}</h2>
                </div>

                <div class="page-content-wrap">
                    @yield('content')
                </div>

                @include('backend.elements.footer')
            </div>
        </div> 

        <!-- MESSAGE BOX-->
        <!-- Alert -->
        <div class="message-box animated fadeIn" id="mbAlert">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title">
                        <span class="fa"></span> <span class="mb-title-text"></span>
                        <div class="pull-right">
                            <button class="btn btn-default btn-lg btnMbClose">{{ trans('page.btn.close') }}</button>
                        </div>
                    </div>
                    <div class="mb-content">
                        <p class="mb-content-text"></p>
                    </div>
                    <div class="mb-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- end Alert -->

        <!-- YesNo -->
        <div class="message-box animated fadeIn" id="mbYesNo">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title">
                        <span class="fa"></span> <span class="mb-title-text"></span>
                        <div class="pull-right">
                            <button class="btn btn-success btn-lg btnMbYes">{{ trans('page.btn.yes') }}</button>
                            <button class="btn btn-default btn-lg btnMbNo">{{ trans('page.btn.no') }}</button>
                        </div>
                    </div>
                    <div class="mb-content">
                        <p class="mb-content-text"></p>
                    </div>
                    <div class="mb-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- end YesNo -->
        <!-- END MESSAGE BOX-->

        
        {!! ViewHelper::plugin()->renderJs() !!}
        <script type="text/javascript" src="{{asset('/joli/js/plugins/bootstrap/bootstrap-select.js')}}"></script>
        <script type="text/javascript" src="{{asset('/joli/js/plugins/icheck/icheck.min.js')}}"></script> 
        <script type="text/javascript" src="{{asset('/joli/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/joli/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript">$.extend($.fn.dataTable.defaults, {"language": {"url": "{{asset('/joli/js/plugins/datatables/i18n/' . $_appLocale . '.json')}}"}});</script>
        <script type="text/javascript" src="{{asset('/plugins/utility/messagebox.js')}}"></script>
        @yield('script_include')    
        <script type="text/javascript" src="{{asset('/assets/backend/js/global.js')}}"></script>
        <script type="text/javascript" src="{{asset('/joli/js/settings.js')}}"></script> 
        <script type="text/javascript" src="{{asset('/joli/js/plugins.js')}}"></script> 
        <script type="text/javascript" src="{{asset('/joli/js/actions.js')}}"></script> 
        @yield('script')
        <script type="text/javascript">
            $(document).ready(function () {
                setUrlLast();
            });
        </script>
    </body>
</html>
