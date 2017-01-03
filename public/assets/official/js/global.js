var ajaxRequest = {
    defaultResult: {
        error: function (jqXHR) {
            var message = $('div.growlUI2');
            $('div.growlUI2').find("h1").html("ERROR : "+jqXHR.status);
            $('div.growlUI2').find("h2").html(jqXHR.statusText);
            $.blockUI({ 
                        message: message, 
                        fadeIn: 700, 
                        fadeOut: 700, 
                        timeout: 2000, 
                        showOverlay: false, 
                        centerY: false, 
                        css: { 
                            width: '350px', 
                            top: '100px', 
                            left: '', 
                            right: '10px', 
                            border: 'none', 
                            padding: '5px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .6, 
                            color: '#fff' 
                        } 
                    }); 
            
        },
        ok: function (response) {
            var detailText = ajaxRequest.getDetailText(response.detail);

            var message = $('div.growlUI');
            $('div.growlUI').find("h1").html(response.msg);
            $('div.growlUI').find("h2").html(detailText);
            $.blockUI({ 
                        message: message, 
                        fadeIn: 700, 
                        fadeOut: 700, 
                        timeout: 2000, 
                        showOverlay: false, 
                        centerY: false, 
                        css: { 
                            width: '350px', 
                            top: '100px', 
                            left: '', 
                            right: '10px', 
                            border: 'none', 
                            padding: '5px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .6, 
                            color: '#fff' 
                        } 
                    }); 
        },
        no: function (response) {
            var detailText = ajaxRequest.getDetailText(response.detail);

            var message = $('div.growlUI2');
            $('div.growlUI2').find("h1").html(response.msg);
            $('div.growlUI2').find("h2").html(detailText);
            $.blockUI({ 
                        message: message, 
                        fadeIn: 700, 
                        fadeOut: 700, 
                        timeout: 2000, 
                        showOverlay: false, 
                        centerY: false, 
                        css: { 
                            width: '350px', 
                            top: '100px', 
                            left: '', 
                            right: '10px', 
                            border: 'none', 
                            padding: '5px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .6, 
                            color: '#fff' 
                        } 
                    }); 
        },
        noLogin: function (response) {
            var detailText = ajaxRequest.getDetailText(response.detail);

            return $.mbAlert().mbStyle('info').mbTitleText(response.msg).mbContentHtml(detailText).mbOpen();
        },
        noAccess: function (response) {
            var detailText = ajaxRequest.getDetailText(response.detail);

            return $.mbAlert().mbStyle('info').mbTitleText(response.msg).mbContentHtml(detailText).mbOpen();
        }
    },
    getDetailText: function (detail) {
        var detailText = '';
        $.each(detail, function (k, v) {
            if ($.type(v) == 'object' || $.type(v) == 'array') {
                if (k.toString().substr(0, 8) == '_locale_') {
                    detailText += k.toString().substr(8).htmlEncode() + ':<br />';
                }
                $.each(v, function (kk, vv) {
                    if ($.type(vv) == 'object' || $.type(vv) == 'array') {
                        $.each(vv, function (kkk, vvv) {
                            detailText += vvv.toString().htmlEncode() + '<br />';
                        });
                    } else {
                        detailText += vv.toString().htmlEncode() + '<br />';
                    }
                });
            } else {
                detailText += v.toString().htmlEncode() + '<br />';
            }
            detailText += '<br />';
        });
        return detailText;
    },
    getOption: function () {
        return {
            url: '',
            data: {},
            ajaxProp: {},
            senderSelector: '',
            msgSelector: '.msg',
            resultError: function (jqXHR, textStatus, errorThrown) {
                ajaxRequest.defaultResult.error(jqXHR);
            },
            resultOk: function (response) {
                var $mb = ajaxRequest.defaultResult.ok(response);

                window.setTimeout(function () {
                    window.location.href = urlBack;
                }, 2000);
            },
            resultNo: function (response) {
                ajaxRequest.defaultResult.no(response);
            },
            resultNoLogin: function (response) {
                var $mb = ajaxRequest.defaultResult.noLogin(response);
                
                window.setTimeout(function () {
                    window.location.href = urlLogin;
                }, 2000);
            },
            resultNoAccess: function (response) {
                var $mb = ajaxRequest.defaultResult.noAccess(response);

                window.setTimeout(function () {
                    window.location.href = urlHome;
                }, 2000);
            },
            finish: function (response) {
            }
        };
    },
    request: function (opt) {
        var option = this.getOption();
        $.extend(true, option, opt);
        if ($.type(option.senderSelector) == "string") {
            option.senderSelector = $(option.senderSelector == '' ? '.btnSubmit' : option.senderSelector);
        }
        if (option.senderSelector.hasClass('ajaxDisabled')) {
            return;
        }
        option.senderSelector.prop("disabled", true).addClass('ajaxDisabled');
        option.data['_token'] = csrf_token;

        var ajaxProp = {
            url: option.url,
            type: "POST",
            dataType: "json",
            data: option.data,
            error: function (jqXHR, textStatus, errorThrown) {
                option.senderSelector.prop("disabled", false).removeClass('ajaxDisabled');
                option.resultError(jqXHR, textStatus, errorThrown);
            },
            success: function (response) {
                option.senderSelector.prop("disabled", false).removeClass('ajaxDisabled');
                if (response.result == "ok") {
                    option.resultOk(response);
                } else if (response.result == "no") {
                    option.resultNo(response);
                } else if (response.result == "login") {
                    option.resultNoLogin(response);
                } else if (response.result == "access") {
                    option.resultNoAccess(response);
                }
                option.finish(response);
            }
        }
        $.extend(ajaxProp, option.ajaxProp);

        $.ajax(ajaxProp);
    },
    submit: function (form, opt) {
        var option = this.getOption();
        $.extend(true, option, opt);
        if ($.type(option.senderSelector) == "string") {
            if (option.senderSelector == '') {
                option.senderSelector = $(form).find('.btnSubmit');
            } else {
                option.senderSelector = $(option.senderSelector);
            }
        }
        if (option.senderSelector.hasClass('ajaxDisabled')) {
            return;
        }
        option.senderSelector.prop("disabled", true).addClass('ajaxDisabled');

        var ajaxProp = {
            dataType: "json",
            data: {_token: csrf_token},
            beforeSubmit: function () {
                $(form).find(option.msgSelector).text("資料處理中...").css('color', 'blue');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                option.senderSelector.prop("disabled", false).removeClass('ajaxDisabled');
                option.resultError(jqXHR, textStatus, errorThrown);
            },
            success: function (response) {
                option.senderSelector.prop("disabled", false).removeClass('ajaxDisabled');
                $(form).find(option.msgSelector).text("");

                if (response.result == "ok") {
                    option.resultOk(response);
                } else if (response.result == "no") {
                    option.resultNo(response);
                } else if (response.result == "login") {
                    option.resultNoLogin(response);
                } else if (response.result == "access") {
                    option.resultNoAccess(response);
                }
                option.finish(response);
            }
        }
        $.extend(ajaxProp, option.ajaxProp);

        // btseditor
        if ($(form).find('.btseditor .btseditor-data-item.editing').length > 0) {
            $(form).find('.btseditor .btseditor-data-item.editing').each(function(){
                $(this).find('.item-edit-btn-ok').click();
            });                
            var interval;
            var second = 0;
            interval = setInterval(function () {
                if ($(form).find('.btseditor .btseditor-data-item.editing').length <= 0 || second > 5000) {
                    $(form).ajaxSubmit(ajaxProp);
                    clearInterval(interval);
                }
                second += 10;
            }, 10);
        } else {
            $(form).ajaxSubmit(ajaxProp);
        }

        return true;
    },
    relationDdl: function (opt) {
        var option = this.getOption();
        option['dest'] = '';
        option['destName'] = '';
        option['value'] = {
            'opt-val': 'id',
            'opt-txt': 'name'
        };
        $.extend(true, option, opt);
        option.data['_token'] = csrf_token;

        var $dest = $(option.dest);
        var $destTmp = $(option.dest + 'Tmp');

        var ajaxProp = {
            url: option.url,
            type: "post",
            dataType: "json",
            data: option.data,
            error: function (jqXHR, textStatus, errorThrown) {
                option.resultError(jqXHR, textStatus, errorThrown);
            },
            success: function (response) {
                if (response.result == "ok") {
                    var destOldVal = $dest.val();
                    $dest.find('option').not('.default_opt').remove();
                    $.each(response.data[0], function (k, v) {
                        var $option = $('<option></option>');
                        $.each(option.value, function (kk, vv) {
                            var value = '';
                            if (vv == '__k') {
                                value = k;
                            } else {
                                value = v[vv];
                            }

                            if (kk == 'opt-val') {
                                $option.val(value);
                                if (value == destOldVal) {
                                    $option.prop('selected', true);
                                }
                            } else if (kk == 'opt-txt') {
                                $option.text(value);
                            } else {
                                $option.data(kk, value)
                            }
                        });

                        $dest.append($option);
                    });
                    if ($.fn.selectpicker && $dest.hasClass('select')) {
                        $dest.selectpicker('refresh');
                    }

                    if ($destTmp.length > 0 && $destTmp.val() != "") {
                        if ($dest.find("option").filter(function () {
                            return $(this).val() == $destTmp.val();
                        }).length > 0) {
                            $dest.val($destTmp.val());
                        }
                        $destTmp.val("");
                    } else {
                        $dest.find("option:first").prop('selected', true);
                    }
                    $dest.change();
                } else if (response.result == "no") {
                    option.resultNo(response);
                } else if (response.result == "login") {
                    option.resultNoLogin(response);
                } else if (response.result == "access") {
                    ajaxRequest.defaultResult.noAccess(response);
                }
                option.finish(response);
            }
        }
        $.extend(ajaxProp, option.ajaxProp);
        $.ajax(ajaxProp);
    }
};

var btnClickDefault = {
    clickCancel: function ($sender) {
        if (!$sender.attr('data-url')) {
            $sender.attr('data-url', urlBack);
        }
    },
    clickSetUrlBack: function ($sender) {
        if (!!$sender.attr('data-routename')) {
            try {
                sessionStorage.setItem('urlBack', window.location.href);
            } catch (e) {
                Cookies.set(urlBasePath + '_urlBack_' + $sender.attr('data-routename'), urlCurr);
                Cookies.set(urlBasePath + '_urlBackRouteName', $sender.attr('data-routename'));
            }
        }
    },
    clickLink: function ($sender) {
        if (!!$sender.attr('data-url')) {
            if (!!$sender.attr('target') && $sender.attr('target') == "_blank") {
                window.open($sender.attr('data-url'));
            } else {
                window.location.href = $sender.attr('data-url');
            }
        }
    },
    clickReset: function ($sender) {
        var $form = $sender.parents('form:first');
        resetAllInput($form);
    },
    clickDelete: function ($sender) {
        var $mbYesNo = $.mbYesNo().mbStyle('danger');
        if (!!$sender.attr('data-mbTitle')) {
            $mbYesNo.mbTitleText($sender.attr('data-mbTitle'));
        }
        $mbYesNo.mbOpen();
        if (!!$sender.attr('data-url')) {
            $mbYesNo.mbBtn('yes').click(function () {
                var selectedId = [];
                $('.ckbItem:checked').each(function () {
                    selectedId.push($(this).val());
                });

                ajaxRequest.request({
                    url: $sender.attr('data-url'),
                    data: {id: selectedId},
                    senderSelector: $sender,
                    resultOk: function (response) {
                        var $mb = ajaxRequest.defaultResult.ok(response);

                        $mb.mbBtn('close').click(function () {
                            window.location.reload();
                        });
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                });
                $mbYesNo.mbClose();
            });
        }
    },
    clickOrder: function ($sender) {
        $('.dialogOrder .conOrderList').empty().append($('<span></span>').text('Loading...').css('color', '#aaa'));
        $('.dialogOrder').modal('show');
        ajaxRequest.request({
            url: $sender.attr('data-url'),
            data: {page: $sender.attr('data-page')},
            senderSelector: $sender,
            resultOk: function (response) {
                $('.dialogOrder .conOrderList').empty().html(response.data.html);

                var orderData = [];
                $('.jqSortCon .sortItem').each(function () {
                    orderData.push($(this).find('.orderItemVal').val());
                });
                $(".jqSortCon").data('order', orderData);

                $(".jqSortCon").sortable({
                    items: ' .sortItem',
                    cursor: "move",
                    helper: function (e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone().css('background-color', '#ececec');
                        $helper.children().each(function (k) {
                            $(this).width($originals.eq(k).width());
                        });
                        return $helper;
                    },
                    stop: function (e, ui) {
                        var orderData = $(".jqSortCon").data('order');
                        $('.jqSortCon .sortItem').each(function () {
                            $(this).find('.orderItemVal').val(orderData[$(this).index()]);
                        });
                    }
                });
                $('#formOrder').validate({
                    submitHandler: function (form) {
                        if (ajaxRequest.submit(form, {
                            resultOk: function (response) {
                                var $mb = ajaxRequest.defaultResult.ok(response);

                                $mb.mbBtn('close').click(function () {
                                    window.location.reload();
                                });
                                window.setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            }
                        }) === false) {
                            return false;
                        }
                    }
                });
            }
        });
    },
    clickEnable: function ($sender) {
        var $mbYesNo = $.mbYesNo().mbStyle('info');
        if (!!$sender.attr('data-mbTitle')) {
            $mbYesNo.mbTitleText($sender.attr('data-mbTitle'));
        }
        $mbYesNo.mbOpen();
        if (!!$sender.attr('data-url')) {
            $mbYesNo.mbBtn('yes').click(function () {
                var selectedId = [];
                $('.ckbItem:checked').each(function () {
                    selectedId.push($(this).val());
                });

                var name = 'enable';
                if (!!$sender.attr('data-name')) {
                    name = $sender.attr('data-name');
                }
                var data = {};
                data['id'] = selectedId;
                data[name] = 1;

                ajaxRequest.request({
                    url: $sender.attr('data-url'),
                    data: data,
                    senderSelector: $sender,
                    resultOk: function (response) {
                        var $mb = ajaxRequest.defaultResult.ok(response);

                        $mb.mbBtn('close').click(function () {
                            window.location.reload();
                        });
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                });
                $mbYesNo.mbClose();
            });
        }
    },
    clickDisable: function ($sender) {
        var $mbYesNo = $.mbYesNo().mbStyle('info');
        if (!!$sender.attr('data-mbTitle')) {
            $mbYesNo.mbTitleText($sender.attr('data-mbTitle'));
        }
        $mbYesNo.mbOpen();
        if (!!$sender.attr('data-url')) {
            $mbYesNo.mbBtn('yes').click(function () {
                var selectedId = [];
                $('.ckbItem:checked').each(function () {
                    selectedId.push($(this).val());
                });

                var name = 'enable';
                if (!!$sender.attr('data-name')) {
                    name = $sender.attr('data-name');
                }
                var data = {};
                data['id'] = selectedId;
                data[name] = 0;

                ajaxRequest.request({
                    url: $sender.attr('data-url'),
                    data: data,
                    senderSelector: $sender,
                    resultOk: function (response) {
                        var $mb = ajaxRequest.defaultResult.ok(response);

                        $mb.mbBtn('close').click(function () {
                            window.location.reload();
                        });
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                });
                $mbYesNo.mbClose();
            });
        }
    },
    clickLogout: function ($sender) {
        var $mbYesNo = $.mbYesNo();
        $mbYesNo.find('.mb-title .fa').addClass('fa-sign-out');
        if (!!$sender.attr('data-mbTitle')) {
            $mbYesNo.mbTitleText($sender.attr('data-mbTitle'));
        }
        $mbYesNo.mbOpen();
        if (!!$sender.attr('data-url')) {
            $mbYesNo.mbBtn('yes').click(function () {
                ajaxRequest.request({
                    url: $sender.attr('data-url'),
                    senderSelector: $sender,
                    resultOk: function (response) {
                        var $mb = ajaxRequest.defaultResult.ok(response);

                        $mb.mbBtn('close').click(function () {
                            window.location.href = urlLogin;
                        });
                        window.setTimeout(function () {
                            window.location.href = urlLogin;
                        }, 2000);
                    }
                });
                $mbYesNo.mbClose();
            });
        }



    }
};

var formLocale = {
    init: function () {
        $('.form_locale_data :input').prop('disabled', true);
        $('.form_locale_data').addClass('hide');
        $('.selectedLocale').remove();

        $('.select_locale_con .btnLocale.' + appLocale).addClass('default selected current');
        $('.select_locale_con .btnLocale').each(function () {
            var locale = $(this).data('locale');
            if ($(this).hasClass('selected')) {
                $('.form_locale_data.' + locale + ' :input').prop('disabled', false);
                formLocale.addLocale(locale);
            }
            $('.form_locale_data.' + locale).each(function () {
                $(this).data('locale', locale);
            });
        });
        $('.form_locale_data.' + $('.select_locale_con .btnLocale.current').data('locale')).not('.noshow').removeClass('hide');
        $('.select_locale_con .btnLocale').click(function () {
            formLocale.changeSelected($(this));
            formLocale.changeCurrent($(this));
        });
    },
    changeSelected: function ($btn) {
        var isDefault = $btn.hasClass('default');
        var isSelected = $btn.hasClass('selected');
        var isCurrent = $btn.hasClass('current');
        var isDisableSelect = $btn.hasClass('disableSelect');

        if (isDisableSelect) {
            return;
        }
        if (isSelected) {
            if (!isDefault && isCurrent) {
                $btn.removeClass('selected');
                $('.form_locale_data.' + $btn.data('locale') + ' :input').prop('disabled', true);
                formLocale.removeLocale($btn.data('locale'));
            }
        } else {
            $btn.addClass('selected');
            $('.form_locale_data.' + $btn.data('locale') + ' :input').prop('disabled', false);
            formLocale.addLocale($btn.data('locale'));
        }
    },
    changeCurrent: function ($btn) {
        var isCurrent = $btn.hasClass('current');

        if (!isCurrent) {
            var currnetLocale = $('.select_locale_con .btnLocale.current').data('locale');
            $('.select_locale_con .btnLocale.current').removeClass('current');
            $('.form_locale_data.' + currnetLocale).addClass('hide');

            $btn.addClass('current');
            $('.form_locale_data.' + $btn.data('locale')).not('.noshow').removeClass('hide');
        }
    },
    addLocale: function (locale) {
        var $ele = $('<input  />').attr({
            type: 'hidden',
            name: 'selected_locale[' + locale + ']',
            id: 'selected_locale_' + locale
        }).addClass('selectedLocale').val(locale);
        $('.form_locale_data.' + locale + ':first').parents('form:first').append($ele);
    },
    removeLocale: function (locale) {
        $('#selected_locale_' + locale).remove();
    }
}

function resetAllInput($container) {
    $container.find('input[type=text],input[type=password],textarea').val('');
    $container.find('input[type=radio],input[type=checkbox]').each(function () {
        if ($(this).hasClass('icheckbox') || $(this).hasClass('iradio')) {
            $(this).iCheck('uncheck');
        } else {
            $(this).prop('checked', false);
        }
    });
    $container.find('select').each(function () {
        if ($(this).hasClass('select')) {
            $(this).selectpicker('val', $(this).find('option:first').val());
        } else {
            $(this).find('option:first').prop('selected', true);
        }
        $(this).change();
    });
}

function setUrlLast() {
    try {
        sessionStorage.setItem('urlLast', window.location.href);
    } catch (e) {
        Cookies.set(urlBasePath + '_urlLast', urlCurr);
    }
}

$(document).ready(function () {
    // url history
    try {
        urlLast = sessionStorage.getItem('urlLast') || urlLast;
        urlBack = sessionStorage.getItem('urlBack') || urlBack;
    } catch (e) {
    }

    //menu active
    if (typeof currMenuClass != "undefined") {
        $.each(currMenuClass, function (k, v) {
            $('.' + v).addClass('active defaultActive');
        });
    }

    // button
    $('.btnCheckShow').hide();
    $('.btnCancel,.btnBack').each(function () {
        btnClickDefault.clickCancel($(this));
    });
    $('.btnSetUrlBack').click(function () {
        btnClickDefault.clickSetUrlBack($(this));
    });
    $('.btnLink').click(function () {
        btnClickDefault.clickLink($(this));
    });
    $('.btnReset').click(function () {
        btnClickDefault.clickReset($(this));
    });
    $('.btnDelete').click(function () {
        btnClickDefault.clickDelete($(this));
    });
    $('.btnEnable').click(function () {
        btnClickDefault.clickEnable($(this));
    });
    $('.btnDisable').click(function () {
        btnClickDefault.clickDisable($(this));
    });
    $('.btnOrder').click(function () {
        btnClickDefault.clickOrder($(this));
    });
    $('.btnLogout').click(function () {
        btnClickDefault.clickLogout($(this));
        return false;
    })

    // check item 
    $('.ckbItem,.ckbItemAll').each(function () {
        $(this).prop('checked', false);
    });
    $('.ckbItemAll').on('ifClicked', function () {
        $('.ckbItemAll').not($(this)).iCheck($(this).prop('checked') ? 'uncheck' : 'check');
        $('.ckbItem').iCheck($(this).prop('checked') ? 'uncheck' : 'check');
    });
    $('.ckbItem').on('ifChanged', function () {
        if ($('.ckbItem').not(':checked').length > 0) {
            $('.ckbItemAll').iCheck('uncheck');
        } else {
            $('.ckbItemAll').iCheck('check');
        }
    });
    $('.ckbItem,.ckbItemAll').on('ifChanged', function () {
        if ($('.ckbItem:checked').length <= 0) {
            $('.btnCheckShow').hide();
        } else {
            $('.btnCheckShow').show();
        }
    });

    //search
    if (typeof isSearch != "undefined" && isSearch == "1") {
        panel_collapse($('#formq').parents('.panel:first'), "hidden");
    }

    //form locale
    formLocale.init();

    $('form').each(function () {
        $(this)[0].reset();
    });
});
