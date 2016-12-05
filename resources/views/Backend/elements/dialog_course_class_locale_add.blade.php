<div id="dialogCourseClassLocale" class="modal" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-body">                        
                        <form id="formAddCourseClassLocale" method="post" action="{{ Sitemap::node()->getChildren('submit_add_course_class_locale')->getUrl() }}">
                            <table class="table datatable_simple nohead">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="15%"><span class="red">*</span>{{ trans('validation.attributes.course_class_locale-name') }}</th>
                                        <td>
                                            <input type="text" name="course_class_locale-name" id="data-course_class_locale-name" class="form-control required" maxlength="50" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th ><span class="red">*</span>{{ trans('validation.attributes.course_class_locale-address') }}</th>
                                        <td>
                                            <input type="text" name="course_class_locale-address" id="data-course_class_locale-address" class="form-control required" maxlength="50" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('validation.attributes.course_class_locale-traffic') }}</th>
                                        <td>
                                            <div id="jqfu_course_class_locale_photo" class="jqfuUploader" 
                                                 data-name="course_class_locale-traffic"
                                                 data-category="course-class-locale-traffic"
                                                 data-file_ext="jpg|jpeg|png|gif"
                                                 data-file_limit="1"
                                                 data-img_scale="600_0"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <td> 
                                            <input type="hidden" name="dialog" value="1" />
                                            {!! ViewHelper::button('submit') !!}
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
    function init_dialog_course_class_locale_add() {
        $('#jqfu_course_class_locale_photo').each(function () {
            new Jqfu($(this).attr('id'), {
                
            });
        });
        $('#btnAddLocale').click(function () {
            resetAllInput($('#dialogCourseClassLocale'));
            $('#dialogCourseClassLocale').modal('show');
        });

        $('#formAddCourseClassLocale').validate({
            submitHandler: function (form) {
                if (ajaxRequest.submit(form, {
                    resultOk: function (response) {
                        var id = parseInt(response.data.id, 10);
                        if (id > 0) {
                            var $opt = $('<option></option>').val(id).text(response.data.name);
                            $('#data-course_class-course_class_locale_id').append($opt);
                            $opt.prop('selected', true);
                            if ($.fn.selectpicker) {
                                $('#data-course_class-course_class_locale_id').selectpicker('refresh');
                            }
                        }

                        var $mb = ajaxRequest.defaultResult.ok(response);
                        $mb.mbBtn('close').click(function () {
                            $('#dialogCourseClassLocale').modal('hide');
                        });
                        window.setTimeout(function () {
                            $('#dialogCourseClassLocale').modal('hide');
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