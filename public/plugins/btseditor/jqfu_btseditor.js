function JqfuBtseditor(id, options) {
    var _this = this;
    this.elementId = id;
    this.JqfuBtseditor = null;
    this.config = {};
    this.defaultSetting = {
        'ext': 'jpg|jpeg|png|gif|doc|docx|xls|xlsx|pdf|zip|rar|7z'
    };

    //=============================================

    this.init = function (options) {
        _this.JqfuBtseditor = $('#' + _this.elementId);
        if (_this.JqfuBtseditor.length <= 0) {
            alert('element not found.');
            return null;
        }

        //config
        var _config = {
            url_uploader: urlUploader,
            url_uploader_delete: urlUploaderDelete,
            url_upload: urlUpload,
            url_uploadABS: urlUploadABS,
            file_ext: _this.defaultSetting.ext,
            file_size: '10M',
            file_limit: 0,
            category: 'files',
            img_scale: '',
            value: '[]',
            btseditor: null,
            editor_dest: null,
            type: 'pic'
        };
        var config = $.extend(true, _config, _this.JqfuBtseditor.data(), options);
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
        _this.JqfuBtseditor.addClass('jqfubtseditor').append(_this.getLayout());
        _this.JqfuBtseditor.data('instance', _this);

        var fileUploader = _this.JqfuBtseditor.find('.jqfubtseditor-btn-input').fileupload({
            url: _this.config.url_uploader,
            type: 'POST',
            dataType: 'json',
            dropZone: _this.JqfuBtseditor,
            pasteZone: _this.JqfuBtseditor,
            paramName: 'jqfuFile',
            singleFileUploads: true,
            autoUpload: true,
            sequentialUploads: true,
            formData: {
                _token: csrf_token,
                category: _this.config.category,
                fileExt: _this.config.file_ext,
                fileSize: _this.config.file_size,
                //imgScale: _this.config.img_scale,
                uploadType: "btseditor"
            },
            add: function (e, data) {
                if (e.isDefaultPrevented()) {
                    return false;
                }

                var $item;
                if (_this.config.type == "url_file") {
                    $item = _this.config.btseditor.bindEvent.jqfuAddUrlFile(_this.config.editor_dest);
                } else {
                    $item = _this.config.btseditor.bindEvent.jqfuAddPic(_this.config.editor_dest);
                }

                $item.find('.upload-progress').removeClass('progress-bar-danger');
                data.context = $item;
                var valid = true;
                $.each(data.files, function (k, v) {
                    data.sizeUnit = _this.convertFileSizeToUnit(v.size);

                    if (!_this.config.file_ext_regexp.test(v.name)) {
                        _this.setError($item, '檔案類型不合法');
                        valid = false;
                    } else if (_this.config.file_size > 0 && v.size > _this.config.file_size) {
                        _this.setError($item, '超過檔案大小上限');
                        valid = false;
                    } else if (_this.config.file_limit > 0
                            && _this.JqfuBtseditor.find('.jqfu-filelist').children().not('.fail').length > _this.config.file_limit) {
                        _this.setError($item, '超過檔案數量上限');
                        valid = false;
                    }
                });
                if (!valid) {
                    if (_this.config.type == "url_file") {

                    } else if (_this.config.btseditor.BtsEditor.find('.btseditor-item.item-pic.editing').length <= 1) {
                        _this.config.btseditor.BtsEditor.find('.uploading').removeClass('uploading');
                        _this.config.btseditor.BtsEditor.find('.btseditor-item.item-pic.editing .item-pic-url input').focus();
                    }
                    return false;
                } else {
                    data.process().done(function () {
                        data.submit();
                    });
                }
            },
            sent: function (e, data) {
            },
            done: function (e, data) {
                var $item = data.context;
                if (data.result.result == 'ok') {
                    $item.find('.upload-progress').remove();
                    if (_this.config.type == "url_file") {
                        var url = _this.config.url_uploadABS + data.result.file.dir + '/' + data.result.file.id + '.' + data.result.file.ext;
                        $item.data('file', data.result.file);
                        $item.find('.item-pic-url-con .type_2').text(url);
                    } else {
                        var src = _this.config.url_uploadABS + data.result.file.dir + '/' + data.result.file.id + '.' + data.result.file.ext;
                        var $img = $('<img />').attr('src', src);
                        $item.find('.item-pic-photo-con').empty().prepend($img);
                        $item.find('.item-pic-photo').data('file', data.result.file);
                    }
                } else {
                    _this.setError($item, data.result.msg);
                }
            },
            fail: function (e, data) {
                var $item = data.context;
                _this.setError($item, '上傳失敗: ' + data.errorThrown);
            },
            stop: function (e) {
                if (_this.config.type == "url_file") {
                } else {
                    _this.config.btseditor.BtsEditor.find('.uploading').removeClass('uploading');
                    _this.config.btseditor.BtsEditor.find('.btseditor-item.item-pic.editing').not(':last').each(function () {
                        $(this).data('val_file', $(this).find('.item-pic-photo').data('file'));
                        $(this).removeClass('editing');
                    });
                    _this.config.btseditor.setValue();
                    _this.config.btseditor.BtsEditor.find('.btseditor-item.item-pic.editing .item-pic-url-con .type_1').focus();
                }
            },
            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 10000, 10) / 100;
                var progressText = progress + '%';
                if (progress >= 100) {
                    progressText = '上傳完成，檔案處理中...';
                }
                data.context.find('.progress-bar').css('width', progress + '%').text(progressText);
            },
            progressall: function (e, data) {
            }
        });
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
        var htmlArr = [
            '<span id="jqfubtseditor-btn-' + _this.elementId + '" class="jqfubtseditor-btn">',
            '  <input type="file" id="jqfubtseditor-btn-input-' + _this.elementId + '" class="jqfubtseditor-btn-input" multiple />',
            '  <a></a>',
            '</span>'
        ];

        var $element = $(htmlArr.join(''));
        return $element;
    };
    this.setError = function ($item, errmsg) {
        $item.find('.progress-bar').addClass('progress-bar-danger').css('width', '100%').text(errmsg);
    }
    //=============================================

    this.init(options);
}

