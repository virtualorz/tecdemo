;
(function ($) {
    $.fn.dropdownDate = function (options) {
        return $(this).each(function () {
            var $this = $(this);
            var config = $.extend(_options, options, $this.data());
            var $year, $month, $day, $date;
            var yStart, yEnd;
            var yText = '', mText = '', dText = '';
            var yValue = '', mValue = '', dValue = '';
            var dtNow = new Date();
            if (!!$this.attr('id')) {
                config.id = $this.attr('id');
            }
            var segText = config.default_text.split('/');
            if (0 in segText) {
                yText = segText[0];
            }
            if (1 in segText) {
                mText = segText[1];
            }
            if (2 in segText) {
                dText = segText[2];
            }
            if (config.default_value == "now") {
                yValue = dtNow.getFullYear().toString().padLeft(4, '0');
                mValue = (dtNow.getMonth() + 1).toString().padLeft(2, '0');
                dValue = dtNow.getDate().toString().padLeft(2, '0');
            } else {
                var segValue = config.default_value.split('/');
                if (0 in segValue) {
                    yValue = segValue[0];
                }
                if (1 in segText) {
                    mValue = segValue[1];
                }
                if (2 in segText) {
                    dValue = segValue[2];
                }
            }
            if (config.year_start == 'now') {
                yStart = dtNow.getFullYear();
            } else {
                yStart = config.year_start;
            }
            if (config.year_end == 'now') {
                yEnd = dtNow.getFullYear() + config.year_end_addition;
            } else {
                yEnd = config.year_end + config.year_end_addition;
            }


            // layout
            var $defaultOpt = $('<option></option>').addClass('default_opt').val('');
            $year = $('<select></select>').addClass('form-control select' + ' ' + config.class_trinity + ' ' + config.class_year).attr('id', config.id + 'Year').append($defaultOpt.clone().text(yText));
            $month = $('<select></select>').addClass('form-control select' + ' ' + config.class_trinity + ' ' + config.class_month).attr('id', config.id + 'Month').append($defaultOpt.clone().text(mText));
            $day = $('<select></select>').addClass('form-control select' + ' ' + config.class_trinity + ' ' + config.class_day).attr('id', config.id + 'Day').append($defaultOpt.clone().text(dText));
            $date = $('<input />').attr('type', 'hidden').attr('id', 'data-' + config.id);
            if (config.request_type == 'all') {
                $year.attr('name', config.id + 'Year');
                $month.attr('name', config.id + 'Month');
                $day.attr('name', config.id + 'Day');
                $date.attr('name', config.id);
            } else if (config.request_type == 'divide') {
                $year.attr('name', config.id + 'Year');
                $month.attr('name', config.id + 'Month');
                $day.attr('name', config.id + 'Day');
            } else {
                $date.attr('name', config.id);
            }
            $this.append($year).append($month).append($day).append($date);
            $.each([$year, $month, $day], function () {
                $(this).wrap($('<div></div>').addClass(config.column_size));
            });

            // selectpicker
            if ($.fn.selectpicker) {
                if ($('.selectpickerCon').length <= 0) {
                    $('body').append($('<div></div>').addClass('selectpickerCon'));
                }
                $.each([$year, $month, $day], function () {
                    $(this).selectpicker({container: '.selectpickerCon'});
                    $(this).on("change", function () {
                        if ($(this).val() == "" || null === $(this).val()) {
                            if (!$(this).attr("multiple"))
                                $(this).val("").find("option").removeAttr("selected").prop("selected", false);
                        } else {
                            $(this).find("option[value=" + $(this).val() + "]").attr("selected", true);
                        }
                    });
                });
            }

            // select event
            $.each([$year, $month, $day], function () {
                $(this).change(function () {
                    func.dropdownDateChange($year, $month, $day, $date);
                });
            });


            func.buildYear($year, yStart, yEnd);
            func.dropdownDateChange($year, $month, $day, $date);

            $year.val(yValue);
            $year.change();
            $month.val(mValue);
            $month.change();
            $day.val(dValue);
            $day.change();
        });
    };
    var _options = {
        id: "dropdownDate",
        year_start: 'now',
        year_end: 'now',
        year_end_addition: 0,
        default_value: '',
        default_text: 'Year/Month/Day',
        column_size: 'col-md-2 col-sm-4',
        class_year: '',
        class_month: '',
        class_day: '',
        class_trinity: '',
        request_type: 'date',
    };

    var func = {
        dropdownDateChange: function ($year, $month, $day, $date) {
            $date.val('');
            if (isNaN(parseInt($year.val(), 10))) {
                $month.find('option').not('.default_opt').remove();
                $day.find('option').not('.default_opt').remove();
            } else {
                //month
                if ($month.find('option').not('.default_opt').length <= 0) {
                    this.buildMonth($month);
                }

                //  day
                if (isNaN(parseInt($month.val(), 10))) {
                    $day.find('option').not('.default_opt').remove();
                } else {
                    var oldDays = $day.find('option').not('.default_opt').length;
                    var newDays = this.getDaysInMonth($year.val(), $month.val());
                    if (oldDays < newDays) {
                        this.buildDay($day, oldDays + 1, newDays);
                    } else if (oldDays > newDays) {
                        $day.find('option').not('.default_opt').filter(':gt(' + (newDays - 1) + ')').remove();
                    }

                    if (!isNaN(parseInt($day.val(), 10))) {
                        $date.val($year.val() + '/' + $month.val() + '/' + $day.val());
                    }
                }
            }

            if ($.fn.selectpicker) {
                $year.selectpicker('refresh');
                $month.selectpicker('refresh');
                $day.selectpicker('refresh');
            }
        },
        buildYear: function ($year, s, e) {
            $year.find('option').not('.default_opt').remove();
            for (var i = s; i <= e; i++) {
                var year = i.toString().padLeft(4, '0');
                $year.append('<option value="' + year + '" >' + year + '</option>');
            }
        },
        buildMonth: function ($month) {
            $month.find('option').not('.default_opt').remove();
            for (var i = 1; i <= 12; i++) {
                var month = i.toString().padLeft(2, '0');
                $month.append('<option value="' + month + '" >' + month + '</option>');
            }
        },
        buildDay: function ($day, s, e) {
            for (var i = s; i <= e; i++) {
                var day = i.toString().padLeft(2, '0');
                $day.append('<option value="' + day + '" >' + day + '</option>');
            }
        },
        getDaysInMonth: function (year, month) {
            return new Date(year, month, 0).getDate();
        }
    };

})(jQuery);