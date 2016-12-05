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
                        <form id="formChangePassword" method="post" action="{{ Sitemap::node()->getChildren('submit_change_password')->getUrl() }}">
                            <table class="table datatable_simple nohead">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="15%"><span class="red">*</span>{{ trans('validation.attributes.password_old') }}</th>
                                        <td>
                                            <input type="password" name="password_old" id="data-password_old" class="form-control required " maxlength="20" minlength="6" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><span class="red">*</span>{{ trans('validation.attributes.password_new') }}</th>
                                        <td>
                                            <input type="password" name="password_new" id="data-password_new" class="form-control required " maxlength="20" minlength="6" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><span class="red">*</span>{{ trans('validation.attributes.password_new_confirmation') }}</th>
                                        <td>
                                            <input type="password" name="password_new_confirmation" id="data-password_new_confirmation" class="form-control required "  equalto="#data-password_new" maxlength="20" minlength="6" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <td> 
                                            {!! ViewHelper::button('submit') !!}
                                            <input type="hidden" name="id" value="{{ $id }}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                        var $mb = ajaxRequest.defaultResult.ok(response);
                        $mb.mbBtn('close').click(function () {
                            $('#dialogChangePassword').modal('hide');
                        });
                        window.setTimeout(function () {
                            $('#dialogChangePassword').modal('hide');
                            $mb.mbClose();
                        }, 2000);
                    }
                }) === false) {
                    return false;
                }
            }
        });
    }
</script>