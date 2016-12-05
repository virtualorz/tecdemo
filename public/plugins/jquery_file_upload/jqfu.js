function Jqfu(id, options) {
    var _this = this;
    this.elementId = id;
    this.Jqfu = null;
    this.config = {};
    this.defaultSetting = {
        'ext': 'jpg|jpeg|png|gif|doc|docx|xls|xlsx|pdf|zip|rar|7z'
    };

    //=============================================

    this.init = function (options) {
        _this.Jqfu = $('#' + _this.elementId);
        if (_this.Jqfu.length <= 0) {
            alert('element not found.');
            return null;
        }

        //config
        var _config = {
            url_uploader: urlUploader,
            url_uploader_delete: urlUploaderDelete,
            url_upload: urlUpload,
            file_ext: _this.defaultSetting.ext,
            file_size: '10M',
            file_limit: 0,
            file_px:'0',
            category: 'files',
            img_scale: '',
            name: 'jqfu',
            value: '[]',
            on_success: function ($con, data) {
                var $localeCon = $con.parents('.form_locale_data:first');
                if ($localeCon.length > 0 && $localeCon.hasClass(appLocale)) {
                    var $uploaders = $con.parents('td:first').find('.jqfu').not($con);
                    $uploaders.each(function () {
                        $(this).data('instance').reListFileItem([data.context.data('file')]);
                    });
                }
            }
        };
        var config = $.extend(true, _config, _this.Jqfu.data(), options);
        _this.config = config;
        // file ext
        _this.config.file_ext = _this.filterFileExt(_this.config.file_ext, _this.defaultSetting.ext);
        _this.config.file_ext_regexp = new RegExp('^(.*)\\.(' + _this.config.file_ext + ')$', 'i');
        // file size
        _this.config.file_size = _this.convertFileSizeToByte(_this.config.file_size);
        _this.config.file_size_unit = _this.convertFileSizeToUnit(_this.config.file_size);
        // file limit
        _this.config.file_limit = parseInt(_this.config.file_limit, 10);
        
        // jqfu
        _this.Jqfu.empty().addClass('jqfu').data('cnt', 0).append(_this.getLayout());
        _this.Jqfu.data('instance', _this);

        var fileUploader = _this.Jqfu.find('.jqfu-btn-input').fileupload({
            url: _this.config.url_uploader,
            type: 'POST',
            dataType: 'json',
            dropZone: _this.Jqfu,
            pasteZone: _this.Jqfu,
            paramName: 'jqfuFile',
            singleFileUploads: true,
            autoUpload: true,
            sequentialUploads: true,
            formData: {
                _token: csrf_token,
                category: _this.config.category,
                fileExt: _this.config.file_ext,
                fileSize: _this.config.file_size,
                filePx: _this.config.file_px,
                imgScale: _this.config.img_scale
            },
            add: function (e, data) {
                if (e.isDefaultPrevented()) {
                    return false;
                }
                //add loading progress
                if(typeof options.loading_progress != "undefined")
                {
                    $(".jqfu-filelist").hide();
                    options.loading_progress(e,_this.config,data);
                }

                var $this = $(this);

                var valid = true;
                $.each(data.files, function (k, v) {
                    var cnt = _this.Jqfu.data('cnt') + 1;
                    _this.Jqfu.data('cnt', cnt);

                    data.sizeUnit = _this.convertFileSizeToUnit(v.size);

                    var $context = _this.getFileitem();
                    $context.data('file', null);
                    $context.find('.jqfu-file-name').append($('<a></a>').text(v.name));
                    $context.find('.jqfu-file-size').text(data.sizeUnit);
                    $context.find('.jqfu-file-remove-btn').text('刪除').click(function () {
                        data.abort();
                        _this.removeItem($context);
                    });

                    if (!_this.Jqfu.find('.jqfu-filelist-con').is(':visible')) {
                        _this.Jqfu.find('.jqfu-filelist-con').show();
                    }
                    $context.appendTo(_this.Jqfu.find('.jqfu-filelist'));
                    data.context = $context;


                    if (!_this.config.file_ext_regexp.test(v.name)) {
                        $context.addClass('fail');
                        $context.find('.jqfu-file-pgstatus').addClass('fail').text('檔案類型不合法');
                        valid = false;
                    } else if (_this.config.file_size > 0 && v.size > _this.config.file_size) {
                        $context.addClass('fail');
                        $context.find('.jqfu-file-pgstatus').addClass('fail').text('超過檔案大小上限');
                        valid = false;
                    } else if (_this.config.file_limit > 0
                            && _this.Jqfu.find('.jqfu-filelist').children().not('.fail').length > _this.config.file_limit) {
                        $context.addClass('fail');
                        $context.find('.jqfu-file-pgstatus').addClass('fail').text('超過檔案數量上限');
                        valid = false;
                    }
                });
                if (!valid) {
                    //add error progress
                    if(typeof options.error_process != "undefined")
                    {
                        $(".jqfu-filelist-con").hide();
                        options.error_process(e,_this.config,data,$(".jqfu-filelist-con").find('.jqfu-file-pgstatus').text());
                    }
                    return false;
                }


                data.process().done(function () {
                    data.submit();
                });
            },
            sent: function (e, data) {
            },
            done: function (e, data) {
                if (data.result.result == 'ok') {
                    data.context.data('file', data.result.file);

                    if ($.inArray(data.result.file.ext, ['jpg', 'jpeg', 'png', 'gif']) !== -1) {
                        var $img = $('<img />').attr('src', _this.config.url_upload + data.result.file.dir + '/' + data.result.file.id + '_thumb.' + data.result.file.ext);
                        data.context.find('.jqfu-file-col1').append($img);
                    }
                    var $link = $('<a></a>').attr('href', _this.config.url_upload + data.result.file.dir + '/' + data.result.file.id + '.' + data.result.file.ext).attr('target', '_blank');
                    $link.text(data.result.file.name);
                    data.context.find('.jqfu-file-name').empty().append($link);

                    data.context.find('.jqfu-file-pgstatus').addClass('done').removeClass('percent').text('上傳成功');

                    _this.config.on_success(_this.Jqfu, data);
                } else {
                    data.context.addClass('fail');
                    data.context.find('.jqfu-file-pgstatus').addClass('fail').removeClass('percent').text('上傳失敗: ' + data.result.msg);
                }
                if(typeof options.complete != "undefined")
                    {
                        options.complete(e,_this.config,data);
                    }
            },
            fail: function (e, data) {
                data.context.addClass('fail');
                data.context.find('.jqfu-file-pgstatus').addClass('fail').removeClass('percent').text('上傳失敗: ' + data.errorThrown);
            },
            stop: function (e) {
                _this.setValue();
            },
            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 10000, 10) / 100;
                var progressText = progress + '%';
                if (progress >= 100) {
                    progressText = '上傳完成，檔案處理中...';
                }
                data.context.find('.jqfu-file-pgstatus').addClass('percent').text(progressText);
                data.context.find('.jqfu-file-pg').css('width', progress + '%');
            },
            progressall: function (e, data) {
            }
        });
        
        // jqfu value
        var oldData = [];
        if (typeof _this.config.value == 'object' || typeof _this.config.value == 'array') {
            _this.Jqfu.find('.jqfu-value').val(JSON.stringify(_this.config.value));
            oldData = _this.config.value;
        } else {
            if(_this.config.value == ''){
                _this.config.value == '[]';
            }
            _this.Jqfu.find('.jqfu-value').val(_this.config.value);
            oldData = JSON.parse(_this.config.value);
        }
        _this.reListFileItem(oldData);
    };
    this.filterFileExt = function (ext, extAll) {
        var tmpArr = ext.toLowerCase().split('|');
        var tmpArrAll = extAll.toLowerCase().split('|');
        var extArr = [];
        $.each(tmpArr, function (k, v) {
            var tmpV = $.trim(v);
            if ($.inArray(tmpV, tmpArrAll) !== -1) {
                extArr.push(tmpV);
            }
        });
        if (extArr.length > 0) {
            return extArr.join('|');
        } else {
            return extAll.toLowerCase();
        }
    };
    this.convertFileSizeToByte = function (size) {
        var sizeByte = 0;
        var tmpArr = size.toString().match(/^([0-9]+)\s*(b|k|kb|m|mb|g|gb)$/i);
        if (tmpArr != null) {
            switch (tmpArr[2].toLowerCase()) {
                case 'b':
                    sizeByte = parseInt(tmpArr[1], 10);
                    break;
                case 'k':
                case 'kb':
                    sizeByte = parseInt(tmpArr[1], 10) * 1024;
                    break;
                case 'm':
                case 'mb':
                    sizeByte = parseInt(tmpArr[1], 10) * 1024 * 1024;
                    break;
                case 'g':
                case 'gb':
                    sizeByte = parseInt(tmpArr[1], 10) * 1024 * 1024 * 1024;
                    break;
            }
        } else {
            var num = parseInt(size, 10);
            if (!isNaN(num)) {
                sizeByte = num;
            }
        }
        return sizeByte;
    };
    this.convertFileSizeToUnit = function (size) {
        var sizeUnit;
        if (size >= 1073741824) {
            sizeUnit = (Math.round(size / 1073741824 * 100) / 100) + ' GB';
        } else if (size >= 1048576) {
            sizeUnit = (Math.round(size / 1048576 * 100) / 100) + ' MB';
        } else if (size >= 1024) {
            sizeUnit = (Math.round(size / 1024 * 100) / 100) + ' KB';
        } else if (size > 1) {
            sizeUnit = size + ' bytes';
        } else {
            sizeUnit = size + ' byte';
        }

        return sizeUnit;
    };
    this.getLayout = function () {
        var imgScaleHtml = '';
        if (_this.config.img_scale.length > 0) {
            imgScaleHtml = '圖片尺寸: ';
            var scales = _this.config.img_scale.split(',');
            $.each(scales, function (k, v) {
                var parts = v.split('_');
                imgScaleHtml += '[' + parts[0] + ' * ' + parts[1] + ']';
            });
        }

        var localeBtnHtml = '';
        var $localeCon = _this.Jqfu.parents('.form_locale_data:first');
        if ($localeCon.length > 0 && !$localeCon.hasClass(appLocale)) {
            localeBtnHtml = '<button type="button" class="jqfu-btn-add-from-main-locale btn btn-default" style="float:right;">加入主語言資料</button>';
        }

        var Icon = '<a>選擇檔案</a>';
        if(typeof options.icon != "undefined")
        {
            Icon = options.icon;
        }
        var spanStyle = '';
        if(typeof options.spanStyle != "undefined")
        {
            spanStyle = options.spanStyle;
        }
        var show_info = '';
        if(typeof options.show_info != "undefined" && options.show_info == false)
        {
            show_info = 'style="display:none;"';
        }

        var htmlArr = [
            '<div>',
            '<span id="jqfu-btn-' + _this.elementId + '" class="jqfu-btn" style="'+spanStyle+'">',
            '  <input type="file" id="jqfu-btn-input-' + _this.elementId + '" class="jqfu-btn-input" multiple />',
            '  '+Icon,
            '</span><br />',
            '<span '+show_info+'>',
            '檔案大小: ' + _this.config.file_size_unit + '<br />',
            '檔案類型: ' + (((_this.config.file_ext).split('|')).join(', ')) + '<br />',
            '檔案數量: ' + (_this.config.file_limit == 0 ? '不限制' : _this.config.file_limit) + '<br />',
            imgScaleHtml,
            '</span>',
            localeBtnHtml,
            '<div style="clear:both;"></div>',
            '<div id="jqfu-filelist-con-' + _this.elementId + '" class="jqfu-filelist-con" style="display:none;" >',
            '  <ul id="jqfu-filelist-' + _this.elementId + '" class="jqfu-filelist" ></ul>',
            '</div>',
            '<input type="hidden" name="' + _this.config.name + '" class="jqfu-value" value="[]" />',
            '</div>'
        ];

        var $element = $(htmlArr.join(''));
        $element.find('.jqfu-btn-add-from-main-locale').click(function () {
            var $fileItems = _this.Jqfu.parents('td:first').find('.form_locale_data.' + appLocale + ' .jqfu-filelist .jqfu-file');
            $fileItems.each(function () {
                _this.reListFileItem(_this.Jqfu.find('.jqfu-btn-input'), [$(this).data('file')]);
            });
        });
        return $element;
    };
    this.getFileitem = function () {
        var htmlArr = [
            '<li id="" class="jqfu-file">',
            '  <table>',
            '    <tr>',
            '      <td class="jqfu-file-col1"></td>',
            '      <td class="jqfu-file-col2">',
            '        <span class="jqfu-file-name"></span>',
            '      </td>',
            '      <td class="jqfu-file-col3">',
            '        <span class="jqfu-file-size"></span>',
            '        <span class="jqfu-file-pgstatus"></span>',
            '        <div style="clear:both"></div>',
            '        <div class="jqfu-file-pgbar">',
            '          <div class="jqfu-file-pg"></div>',
            '        </div>',
            '      </td>',
            '      <td class="jqfu-file-col4">',
            '        <a class="jqfu-file-remove-btn cmd-btn" ></a>',
            '      </td>',
            '    </tr>',
            '  </table>',
            '</li>'
        ];

        var $element = $(htmlArr.join(''));
        return $element;
    };
    this.removeItem = function ($context) {
        if ($context.data('file') != null) {
//            var postData = {};
//            postData['_token'] = csrf_token;
//            postData[fileUploader.fileupload('option', 'paramName')] = $context.data('file');
//            $.ajax({
//                url: fileUploader.fileupload('option', 'urlDelete'),
//                type: "POST",
//                dataType: "json",
//                data: postData,
//            });
        }
        var $con = $context.parents('.jqfu-filelist-con:first');
        $context.remove();
        if ($con.find('.jqfu-file').length <= 0) {
            $con.hide();
        }
        _this.setValue();
    };
    this.reListFileItem = function (files) {
        $.each(files, function (k, file) {
            var $context = _this.getFileitem();
            $context.data('file', file);

            if ($.inArray(file.ext, ['jpg', 'jpeg', 'png', 'gif']) !== -1) {
                var $img = $('<img />').attr('src', _this.config.url_upload + file.dir + '/' + file.id + '_thumb.' + file.ext);
                $context.find('.jqfu-file-col1').append($img);
            }
            var $link = $('<a></a>').attr('href', _this.config.url_upload + file.dir + '/' + file.id + '.' + file.ext).attr('target', '_blank');
            $link.text(file.name);


            $context.find('.jqfu-file-name').empty().append($link);
            $context.find('.jqfu-file-size').text('');
            $context.find('.jqfu-file-pgstatus').addClass('done').text('已上傳');
            $context.find('.jqfu-file-pgbar').hide();
            $context.find('.jqfu-file-remove-btn').text('刪除').click(function () {
                _this.removeItem($context);
            });
            $context.appendTo(_this.Jqfu.find('.jqfu-filelist'));
        });
        if (_this.Jqfu.find('.jqfu-file').length > 0) {
            _this.Jqfu.find('.jqfu-filelist-con').show();
        }
    };
    this.setValue = function () {
        var data = [];
        _this.Jqfu.find('.jqfu-file').each(function () {
            if ($(this).data('file') != null) {
                data.push($(this).data('file'));
            }
        });

        _this.Jqfu.find('.jqfu-value').val(JSON.stringify(data));
    };

    //=============================================

    this.init(options);
}
