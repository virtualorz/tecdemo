<div id="dialogChangePassword" class="modal" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-body">           
                        <form id="formChangePassword" method="post" class="form-horizontal " action="{{ Sitemap::node()->getChildren('submit_change_password')->getUrl() }}">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="red">*</span>{{ trans('validation.attributes.password_old') }}</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password_old" id="data-password_old" class="form-control required " maxlength="20" minlength="6" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="red">*</span>{{ trans('validation.attributes.password_new') }}</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password_new" id="data-password_new" class="form-control required " maxlength="20" minlength="6" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="red">*</span>{{ trans('validation.attributes.password_new_confirmation') }}</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password_new_confirmation" id="data-password_new_confirmation" class="form-control required "  equalto="#data-password_new" maxlength="20" minlength="6" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-default btnSubmit">{{ trans('page.btn.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function init_dialog_change_password() {
        $('#btnChangePassword').click(function () {
            resetAllInput($('#dialogChangePassword'));
            $('#dialogChangePassword').modal('show');
        });

        $('#formChangePassword').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                    resultOk: function (response) {
                        alert(response.msg);
                        $('#dialogChangePassword').modal('hide');
                    }
                }) === false) {
                    return false;
                }
            }
        });
    }
</script>