@extends('Backend.layouts.login')


@section('head')
@endsection


@section('content')
<div class="login-box animated fadeInDown">                
    <div class="login-body">
        <form id="form1" class="form-horizontal" method="post" action="{{ Sitemap::node()->getChildren('submit')->getUrl() }}">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" name='admin-account' id="data-admin-account" class="form-control required" maxlength="50" placeholder="{{ trans('validation.attributes.admin-account') }}"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="password" name='admin-password' id="data-admin-password" class="form-control required" maxlength="50" placeholder="{{ trans('validation.attributes.admin-password') }}"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-info btn-block btnSubmit">{{ trans('page.btn.login') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="login-footer">
        <div class="pull-left">
        </div>
    </div>
</div>
@endsection




@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        initLayout();
        initValidation();
    });

    function onResize() {
        $('.login-container').css('min-height', $(window).height() - $('footer').outerHeight());
    }

    function initLayout() {
        onResize();
        $(window).resize(function () {
            onResize();
        });
    }

    function initValidation() {
        $('#form1').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                    resultOk: function (response) {
                        var $mb = ajaxRequest.defaultResult.ok(response);

                        $mb.mbBtn('close').click(function () {
                            window.location.href = urlLast;
                        });
                        window.setTimeout(function () {
                            window.location.href = urlLast;
                        }, 2000);
                    },
                    resultNo: function (response) {
                        var $mb = ajaxRequest.defaultResult.no(response);

                        $mb.mbBtn('close').click(function () {
                            $('#data-admin-password').val('').focus();
                        });
                    },
                }) === false) {
                    return false;
                }
            }
        });
    }
</script>
@endsection
