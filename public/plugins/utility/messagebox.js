;
(function ($) {
    $.fn.extend({
        mbClone: function () {
            var $clone = $(this).clone(true);
            $clone.attr('id', '').removeClass('open').addClass('mb-clone').insertAfter($(this));
            return $clone;
        },
        mbReset: function () {
            $(this).attr('class', 'message-box animated fadeIn');
            $(this).find('.mb-title .fa').attr('class', 'fa');
            $(this).find('.mb-title-text,.mb-content-text,.mb-footer').empty();
            $(this).find('.btn').off();
            $(this).find('.btnMbNo,.btnMbClose').on('click', function () {
                $(this).parents(".message-box").mbClose();
            });
            return $(this);
        },
        mbStyle: function (style) {
            $(this).removeClass('message-box-success message-box-warning message-box-info message-box-danger');
            $(this).find('.mb-title .fa').attr('class', 'fa');
            switch (style) {
                case 'success':
                    $(this).addClass('message-box-success');
                    $(this).find('.mb-title .fa').addClass('fa-check');
                    break;
                case 'warning':
                    $(this).addClass('message-box-warning');
                    $(this).find('.mb-title .fa').addClass('fa-warning');
                    break;
                case 'info':
                    $(this).addClass('message-box-info');
                    $(this).find('.mb-title .fa').addClass('fa-info');
                    break;
                case 'danger':
                    $(this).addClass('message-box-danger');
                    $(this).find('.mb-title .fa').addClass('fa-times');
                    break;
            }
            return $(this);
        },
        mbOpen: function () {
            if ($(this).hasClass('mb-clone')) {
                $(this).insertAfter($('.message-box:last'));
            }
            $(this).addClass('open');
            $(this).find('.btnMbClose,.btnMbNo').focus();
            return $(this);
        },
        mbClose: function () {
            if ($(this).hasClass('mb-clone')) {
                $(this).remove();
            } else {
                $(this).removeClass('open');
            }
            return $(this);
        },
        mbTitle: function () {
            return $(this).find('.mb-title-text');
        },
        mbTitleText: function (text) {
            $(this).find('.mb-title-text').text(text);
            return $(this)
        },
        mbTitleHtml: function (html) {
            $(this).find('.mb-title-text').html(html);
            return $(this);
        },
        mbContent: function () {
            return $(this).find('.mb-content-text');
        },
        mbContentText: function (text) {
            $(this).find('.mb-content-text').text(text);
            return $(this)
        },
        mbContentHtml: function (html) {
            $(this).find('.mb-content-text').html(html);
            return $(this)
        },
        mbBtn: function (name){
            name = name.toLowerCase();
            switch(name){
                case 'yes':
                    return $(this).find('.btnMbYes');
                    break;
                case 'no':
                    return $(this).find('.btnMbNo');
                    break;
                case 'close':
                    return $(this).find('.btnMbClose');
                    break;
            }
            return null;
        }
    });
    $.mbAlert = function(){
        return $('#mbAlert').mbClone().mbReset();
    }
    $.mbYesNo = function(){
        return $('#mbYesNo').mbClone().mbReset();
    }
})(jQuery);