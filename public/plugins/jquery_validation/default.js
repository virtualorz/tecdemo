jQuery.validator.setDefaults({
    errorPlacement: function (error, element) {
        if (element.is(":radio") || element.is(":checkbox")) {
            var $form = element.parents("form:first");
            var eid = element.attr('name');
            $form.find('input[name="' + eid + '"]:last').parents("label:first").after(error);
        } else if ($.fn.selectpicker && element.is('select') && element.hasClass('form-control select')) {
            element.siblings('.bootstrap-select:first').after(error);
        } else {
            error.insertAfter(element);
        }
    },
    ignore: 'input[type="hidden"]',
    invalidHandler: function (event, validator) {
        $.each(validator.errorList, function (k, v) {
            var $element = $(v.element);
            if ($element.parents('.form_locale_data').length > 0) {
                var locale = $element.parents('.form_locale_data:first').data('locale');
                formLocale.changeCurrent($('.select_locale_con .btnLocale.' + locale));
            }
        })
    }
});