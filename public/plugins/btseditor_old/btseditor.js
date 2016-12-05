function BtsEditor(id, options) {
    var _this = this;
    this.elementId = id;
    this.BtsEditor = null;
    this.config = {};

    //=============================================

    this.init = function (options) {
        _this.BtsEditor = $('#' + _this.elementId);
        if (_this.BtsEditor.length <= 0) {
            alert('element not found.');
            return null;
        }

        //config
        var _config = {
            name: 'btseditor',
            value: '[]',
            height: '100%',
            cke_config: 'config_btseditor.js',
            jqfu_url_uploader: urlUploader,
            jqfu_url_upload: urlUpload,
            jqfu_file_size: "2 MB",
            jqfu_category: '',
            menu: ["pic", "video", "text"]
        };
        var config = $.extend(true, _config, _this.BtsEditor.data(), options);
        _this.config = config;

        // editor
        var $mainLayout = _this.getMainLayout();
        _this.BtsEditor.empty().append($mainLayout);
        _this.BtsEditor.data('cntRow', 0);
        _this.BtsEditor.data('cntCell', 0);
        _this.BtsEditor.data('cntItem', 0);
        _this.BtsEditor.data('cntDivider', 0);
        if (typeof _this.config.value == 'object' || typeof _this.config.value == 'array') {
            _this.BtsEditor.find('.btseditor-value').val(JSON.stringify(_this.config.value));
        } else {
            _this.BtsEditor.find('.btseditor-value').val(_this.config.value);
        }

        //parent
        _this.BtsEditor.wrap($('<div></div>').addClass('btseditor_parent'));

        //order panel
        if ($('.btseditor-order-panel').length <= 0) {
            var $orderPanel = _this.getOrderPanel();
            $orderPanel.appendTo('body');
        }

        //insert last divider
        if (_this.BtsEditor.find('.btseditor-content .btseditor-divider-row').length <= 0) {
            var $itemDividerLast = _this.getDividerRow().addClass('last-divider');
            _this.BtsEditor.find('.btseditor-content').prepend($itemDividerLast);
            _this.bindItemEvent.dividerRow($itemDividerLast);
        }

        _this.bindEvent.resize();
        $(window).resize(function () {
            _this.bindEvent.resize();
        });

        try {
            _this.importValue($.parseJSON(_this.getValue()));
        } catch (e) {
            _this.reset();
            _this.BtsEditor.find('.btseditor-value').val('[]');
        }
    };
    this.addRow = function ($dest) {
        var $itemDividerRow = _this.getDividerRow();
        var $dataRow = _this.getRow();
        $dest.before($itemDividerRow);
        $dest.before($dataRow);
        _this.bindItemEvent.dividerRow($itemDividerRow);

        return $dataRow;
    };
    this.addCell = function ($dest, weight) {
        var $itemDividerItem = _this.getDividerItem().addClass('last-divider');
        var $dataCell = _this.getCell();

        var column = $dest.data('column');
        var gcd = _this.getDividScaleGcd(weight, column);
        var gcdScaleA = weight / gcd;
        var gcdScaleB = column / gcd;
        $dataCell.data('weight', weight).addClass('column_' + gcdScaleA + '_' + gcdScaleB);
//        $itemDividerItem.find('.divid-scale-a-val').text(weight);
//        $itemDividerItem.find('.divid-scale-b-val').text(column);
//        $itemDividerItem.find('.divid-scale').data('scale_a', weight).data('scale_b', weight);
        
        $dataCell.prepend($itemDividerItem);        
        $dest.append($dataCell);
        _this.bindItemEvent.dividerItem($itemDividerItem);

        return $dataCell;
    };
    this.addItemText = function ($dest, open) {
        var $itemDividerItem = _this.getDividerItem();
        var $item = _this.getItemText();
        
//        $itemDividerItem.find('.divid-scale-a-val').text($dest.parents('.btseditor-data-cell:first').data('weight'));
//        $itemDividerItem.find('.divid-scale-b-val').text($dest.parents('.btseditor-data-row:first').data('column'));
//        $itemDividerItem.find('.divid-scale').data('scale_a', weight).data('scale_b', weight);
        
        $dest.before($itemDividerItem);
        $dest.before($item);
        _this.bindItemEvent.dividerItem($itemDividerItem);
        _this.bindItemEvent.itemText($item, open);

        return $item;
    };
    this.addItemPic = function ($dest) {
        var $itemDividerItem = _this.getDividerItem();
        var $item = _this.getItemPic();
        $dest.before($itemDividerItem);
        $dest.before($item);
        _this.bindItemEvent.dividerItem($itemDividerItem);
        _this.bindItemEvent.itemPic($item);

        return $item;
    };
    this.addItemVideo = function ($dest) {
        var $itemDividerItem = _this.getDividerItem();
        var $item = _this.getItemVideo();
        $dest.before($itemDividerItem);
        $dest.before($item);
        _this.bindItemEvent.dividerItem($itemDividerItem);
        _this.bindItemEvent.itemVideo($item);

        return $item;
    };
    this.deleteItem = function ($item) {
        $item.prev('.btseditor-divider').remove();
        if ($item.hasClass('.item-text') && !!CKEDITOR.instances['btseditorcke-' + $item.data('id') + '__' + _this.elementId]) {
            CKEDITOR.instances['btseditorcke-' + $item.data('id') + '__' + _this.elementId].destroy();
        }
        var $dataRow = $item.parents('.btseditor-data-row:first');
        $item.remove();
        if ($dataRow.length > 0) {
            if ($dataRow.find('.btseditor-data-item').length <= 0) {
                $dataRow.prev('.btseditor-divider').remove();
                $dataRow.remove();
            }
        }
        _this.setValue();
    };
    this.bindEvent = {
        resize: function () {
            var inner_port = window.innerWidth || $(document).width();
            if (inner_port <= 768) {
                _this.BtsEditor.find('.btseditor-sideleft').css('margin-bottom', _this.BtsEditor.find('.btseditor-sideleft-menu').outerHeight() + 10);
            } else {
                _this.BtsEditor.find('.btseditor-sideleft').css('margin-bottom', 0);
            }
        },
        divideColumn: function ($element) {
            $element.find('.divide-up').click(function () {
                var $divideValue = $(this).parents('.menu-set-divide-column:first').find('.divide-column-value');
                var value = $divideValue.data('value') + 1;
                if (value <= 4) {
                    $divideValue.text(value).data('value', value);
                }
            });
            $element.find('.divide-down').click(function () {
                var $divideValue = $(this).parents('.menu-set-divide-column:first').find('.divide-column-value');
                var value = $divideValue.data('value') - 1;
                if (value >= 1) {
                    $divideValue.text(value).data('value', value);
                }
            });
        },
        divideScale: function ($element) {
            $element.find('.divide-up.divid-scale-a').click(function () {
                var $divideValue = $(this).parents('.menu-set-divide-column:first').find('.divide-column-value');
                var value = $divideValue.data('value') + 1;
                if (value <= 12) {
                    $divideValue.text(value).data('value', value);
                }
            });
            $element.find('.divide-down.divid-scale-a').click(function () {
                var $divideValue = $(this).parents('.menu-set-divide-column:first').find('.divide-column-value');
                var value = $divideValue.data('value') - 1;
                if (value >= 1) {
                    $divideValue.text(value).data('value', value);
                }
            });
        },
        menuAddText: function ($element) {
            $element.find('.btseditor-menu-text').click(function (e) {
                e.preventDefault();
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
                _this.BtsEditor.addClass('editing');

                var isAddCell = false;
                var column = 1;
                if ($element.hasClass('btseditor-divider-row')) {
                    column = $element.find('.divide-column-value').data('value');
                    var isAddCell = true;
                }

                var $item = null;
                if (isAddCell) {
                    var $dataRow = _this.addRow($element).data('column', column);
                    for (var i = 0; i < column; i++) {
                        var $dataCell = _this.addCell($dataRow, 1);
                    }

                    var $tmpDivider = $dataRow.find('.btseditor-data-cell:first .btseditor-divider:last');
                    $item = _this.addItemText($tmpDivider, true);
                } else {
                    $item = _this.addItemText($element, true);
                }
                $item.addClass('editing');
                $item.removeClass('cke-hide');
            });
        },
        menuAddPic: function ($element) {
            new JqfuBtseditor("btseditor-menu-pic-" + $element.data('id') + "__" + _this.elementId, {
                url_uploader: _this.config.jqfu_url_uploader,
                url_upload: _this.config.jqfu_url_upload,
                file_size: _this.config.jqfu_file_size,
                file_limit: 0,
                category: _this.config.jqfu_category,
                btseditor: _this,
                editor_dest: $element
            });
            $element.find('.btseditor-menu-pic').click(function (e) {
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
            });
        },
        menuAddVideo: function ($element) {
            $element.find('.btseditor-menu-video').click(function (e) {
                e.preventDefault();
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
                _this.BtsEditor.addClass('editing');

                var isAddCell = false;
                var column = 1;
                if ($element.hasClass('btseditor-divider-row')) {
                    column = $element.find('.divide-column-value').data('value');
                    var isAddCell = true;
                }

                var $item = null;
                if (isAddCell) {
                    var $dataRow = _this.addRow($element).data('column', column);
                    for (var i = 0; i < column; i++) {
                        var $dataCell = _this.addCell($dataRow, 1);
                    }

                    var $tmpDivider = $dataRow.find('.btseditor-data-cell:first .btseditor-divider:last');
                    $item = _this.addItemVideo($tmpDivider);
                } else {
                    $item = _this.addItemVideo($element);
                }
                $item.addClass('editing');

                $(this).find('.item-edit-input textarea').focus();
            });
        },
        menuDelete: function ($element) {
            $element.find('.btseditor-menu-delete').click(function (e) {
                e.preventDefault();
                _this.deleteItem($element);
            });
        },
        menuOrder: function ($element) {
            $element.find('.btseditor-menu-order').click(function (e) {
                e.preventDefault();

                var $orderModal = $('.btseditor-order-panel');
                var $orderPanel = $orderModal.find('.btseditor-order-panel-sortable');


                $orderPanel.empty();
                $orderModal.modal('show');

                //$orderPanel.sortable("destroy");

                _this.BtsEditor.find('.btseditor-data-cell').each(function () {
                    var $li = _this.getOrderItem();
                    $li.data('sourceId', $(this).attr('id'));
                    $li.attr('id', 'btseditor-order_item_' + $(this).data('id') + '__' + _this.elementId);
                    $orderPanel.append($li);

                    var $dataItem = $(this).find('.btseditor-data-item:first');
                    if ($dataItem.length > 0) {
                        if ($dataItem.hasClass('item-text')) {
                            if ($.trim($dataItem.find('.btseditor-item-title h2').text()) != '') {
                                $li.find('.btseditor-order-panel-sortable-title').text($dataItem.find('.btseditor-item-title h2').text().substr(0, 30));
                            } else {
                                $li.find('.btseditor-order-panel-sortable-title').text($dataItem.find('.cke_wysiwyg_div').text().substr(0, 30));
                            }

                        } else if ($dataItem.hasClass('item-pic')) {
                            var imgFile = $dataItem.find('.btseditor-item-content').data('file');
                            if (!!imgFile) {
                                var src = _this.config.jqfu_url_upload + '/' + imgFile.dir + '/' + imgFile.id + '_thumb.' + imgFile.ext;
                                var $img = $('<img />').attr('src', src).height($li.height() - 10);
                                $li.find('.btseditor-order-panel-sortable-title').append($img);
                            } else {
                                $li.find('.btseditor-order-panel-sortable-title').text('image');
                            }
                        } else if ($dataItem.hasClass('item-video')) {
                            var $iframe = $dataItem.find('.video-container iframe:first');
                            if ($iframe.length > 0 && !!$iframe.attr('src')) {
                                $li.find('.btseditor-order-panel-sortable-title').text('video: ' + $iframe.attr('src'));
                            } else {
                                $li.find('.btseditor-order-panel-sortable-title').text('video');
                            }
                        } else {
                            $li.find('.btseditor-order-panel-sortable-title').text('empty');
                        }
                    } else {
                        $li.find('.btseditor-order-panel-sortable-title').text('empty');
                    }
                });
                $orderPanel.sortable({
                    stop: function (e, ui) {
                        var srcId = ui.item.data('sourceId');
                        var destId = null;
                        if (ui.item.next('li').length > 0) {
                            destId = ui.item.next('li').data('sourceId');
                        }
                        var $srcCell = $('#' + srcId);
                        var $destCell = null;
                        var $srcRow = $srcCell.parents('.btseditor-data-row:first');
                        var $destRow = _this.BtsEditor.find('.btseditor-data-row:last');
                        if (!!destId) {
                            $destCell = $('#' + destId);
                            $destRow = $destCell.parents('.btseditor-data-row:first');
                        }
                        var emptyRows = [];
                        if ($srcRow.index() < $destRow.index() || ($srcRow.index() == $destRow.index() && (!$destCell || $srcCell.index() < $destCell.index()))) {
                            if ($srcRow.index() !== $destRow.index()) {
                                $srcCell.removeClass('column_1_' + $srcRow.data('column'))
                                        .addClass('column_1_' + $destRow.data('column'));
                            }
                            if (!!$destCell) {
                                $destCell.before($srcCell);
                            } else {
                                $destRow.append($srcCell);
                            }

                            var $tmpRowS = $destRow;
                            while ($tmpRowS.index() > $srcRow.index()) {
                                var $tmpRow = $tmpRowS.prevAll('.btseditor-data-row:first');
                                var $tmpCell = $tmpRowS.find('.btseditor-data-cell:first');
                                $tmpCell.removeClass('column_1_' + $tmpRowS.data('column'))
                                        .addClass('column_1_' + $tmpRow.data('column'));
                                $tmpRow.append($tmpCell);
                                if ($tmpRowS.find('.btseditor-data-item').length <= 0) {
                                    emptyRows.push($tmpRowS);
                                }
                                $tmpRowS = $tmpRow;
                            }

                        } else {
                            if ($srcRow.index() != $destRow.index()) {
                                $srcCell.removeClass('column_1_' + $srcRow.data('column'))
                                        .addClass('column_1_' + $destRow.data('column'));
                            }
                            $destCell.before($srcCell);

                            var $tmpRowS = $destRow;
                            while ($tmpRowS.index() < $srcRow.index()) {
                                var $tmpRow = $tmpRowS.nextAll('.btseditor-data-row:first');
                                var $tmpCell = $tmpRowS.find('.btseditor-data-cell:last');
                                $tmpCell.removeClass('column_1_' + $tmpRowS.data('column'))
                                        .addClass('column_1_' + $tmpRow.data('column'));
                                $tmpRow.prepend($tmpCell);
                                if ($tmpRowS.find('.btseditor-data-item').length <= 0) {
                                    emptyRows.push($tmpRowS);
                                }
                                $tmpRowS = $tmpRow;
                            }
                        }
                        if ($srcRow.find('.btseditor-data-item').length <= 0) {
                            emptyRows.push($tmpRowS);
                        }
                        $.each(emptyRows, function (k, v) {
                            v.find('.btseditor-data-cell').each(function () {
                                $('#btseditor-order_item_' + $(this).data('id') + '__' + _this.elementId).remove();
                            });
                            v.prev('.btseditor-divider').remove();
                            v.remove();
                        });
                        _this.setValue();
                    }
                });
            });
        },
        menuEditText: function ($element) {
            $element.find('.btseditor-menu-edit').click(function (e) {
                e.preventDefault();
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
                _this.BtsEditor.addClass('editing');
                $element.addClass('editing');
                $element.removeClass('cke-hide');
                $element.find('.btseditor-item-title-edit input').val($element.data('val_title'));
            });
        },
        menuEditPic: function ($element) {
            new JqfuBtseditor("item-pic-upload-" + $element.data('id') + "__" + _this.elementId, {
                url_uploader: _this.config.jqfu_url_uploader,
                url_upload: _this.config.jqfu_url_upload,
                file_size: _this.config.jqfu_file_size,
                file_limit: 0,
                category: _this.config.jqfu_category,
                btseditor: _this,
                editor_dest: $element
            });
            $element.find('.btseditor-menu-edit').click(function (e) {
                e.preventDefault();
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
                _this.BtsEditor.addClass('editing');
                $element.addClass('editing');

                var file = $element.data('val_file');
                $element.find('.btseditor-item-pic').data('file', file);
                $element.find('.btseditor-item-url input').val($element.data('val_url'));
                $element.find('.btseditor-item-url input').focus();
            });
        },
        menuEditVideo: function ($element) {
            $element.find('.btseditor-menu-edit').click(function (e) {
                e.preventDefault();
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
                _this.BtsEditor.addClass('editing');
                $element.addClass('editing');

                var val = '';
                if ($element.data('val_content') != null) {
                    val = $element.data('val_content');
                }
                $element.find('.item-edit-input textarea').val(val);
                $element.find('.item-edit-msg').text('');
                $element.find('.item-edit-input textarea').focus();
            });
        },
        jqfuAddPic: function ($dest) {
            _this.BtsEditor.addClass('editing');
            var isAddCell = false;
            var column = 1;
            if ($dest.hasClass('btseditor-divider-row')) {
                column = $dest.find('.divide-column-value').data('value');
                var isAddCell = true;
            }
            var $item = null;

            if (isAddCell) {
                var $destCell = null;
                var $prevRow = $dest.prev('.btseditor-data-row.uploading');
                if ($prevRow.length <= 0
                        || $prevRow.find('.btseditor-data-cell:last').hasClass('uploading')) {
                    var $dataRow = _this.addRow($dest).addClass('uploading').data('column', column);
                    for (var i = 0; i < column; i++) {
                        var $dataCell = _this.addCell($dataRow, 1);
                    }
                    $destCell = $dataRow.find('.btseditor-data-cell:first').addClass('uploading');
                } else {
                    $destCell = $prevRow.find('.btseditor-data-cell:not(.uploading):first').addClass('uploading');
                }

                //add item   
                var $destDivider = $destCell.find('.btseditor-divider:last');
                var $itemUploadProgress = _this.getItemPicProgress();
                var $item = _this.addItemPic($destDivider).addClass('editing');
                $item.find('.btseditor-item-pic').before($itemUploadProgress);
            } else if ($dest.hasClass('btseditor-divider')) {
                var $itemUploadProgress = _this.getItemPicProgress();
                var $item = _this.addItemPic($dest).addClass('editing');
                $item.find('.btseditor-item-pic').before($itemUploadProgress);
            } else {
                $item = $dest.addClass('editing');
                var $itemUploadProgress = _this.getItemPicProgress();
                $item.find('.upload-progress').remove();
                $item.find('.btseditor-item-pic img').remove();
                $item.find('.btseditor-item-pic').before($itemUploadProgress);
            }

            return $item;
        },
        itemText: function ($element, open) {
            $element.find('.btseditor-item-content').attr('contenteditable', 'false');
            CKEDITOR.disableAutoInline = true;
            CKEDITOR.replace('btseditorcke-' + $element.data('id') + '__' + _this.elementId, {
                customConfig: _this.config.cke_config,
            });

            $element.find('.item-edit-btn-ok').click(function (e) {
                e.preventDefault();

                var title = $element.find('.btseditor-item-title-edit input').val();
                var content = CKEDITOR.instances['btseditorcke-' + $element.data('id') + '__' + _this.elementId].getData();
                if (title.length <= 0 && content.length <= 0) {
                    _this.deleteItem($element);
                } else {
                    $element.addClass('cke-hide');
                    $element.find('.btseditor-item-title h2').text(title);
                    $element.data('val_title', title);
                    $element.data('val_content', content);
                    _this.setValue();
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
            $element.find('.item-edit-btn-no').click(function (e) {
                e.preventDefault();

                var title = $element.data('val_title');
                var content = $element.data('val_content');
                if (title.length <= 0 && content.length <= 0) {
                    _this.deleteItem($element);
                } else {
                    $element.addClass('cke-hide');
                    $element.find('.btseditor-item-title h2').text(title);
                    CKEDITOR.instances['btseditorcke-' + $element.data('id') + '__' + _this.elementId].setData(content);
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
        },
        itemPic: function ($element) {
            $element.find('.item-edit-btn-ok').click(function (e) {
                e.preventDefault();

                var file = $element.find('.btseditor-item-pic').data('file');
                if (file == null) {
                    _this.deleteItem($element);
                } else {
                    $element.data('val_file', file);
                    $element.data('val_url', $element.find('.btseditor-item-url input').val());
                    _this.setValue();
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
            $element.find('.item-edit-btn-no').click(function (e) {
                e.preventDefault();

                var file = $element.data('val_file');
                if (file == null) {
                    _this.deleteItem($element);
                } else {
                    var $img = $element.find('.btseditor-item-pic img');
                    if ($img.length <= 0) {
                        $img = $('<img />').prependTo($element.find('.btseditor-item-pic'));
                    }
                    $img.attr('src', _this.config.jqfu_url_upload + '/' + file.dir + '/' + file.id + '.' + file.ext);
                    $element.find('.btseditor-item-url input').val($element.data('val_url'));
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
        },
        itemVideo: function ($element) {
            $element.find('.item-edit-btn-ok').click(function (e) {
                e.preventDefault();

                var val = $.trim($element.find('.item-edit-input textarea').val());
                if (val.length <= 0) {
                    _this.deleteItem($element);
                    _this.BtsEditor.removeClass('editing');
                } else {
                    var $wrap = $('<div></div>');
                    $wrap.append($.parseHTML(val));

                    if ($wrap.find('iframe').length > 0) {
                        var $iframe = $wrap.find('iframe:first').width('').height('').removeAttr('width').removeAttr('height');
                        var iframeHtml = $iframe.clone().wrap('<p>').parent().html();
                        $element.data('val_content', iframeHtml);
                        $element.find('.btseditor-item-content .video-container').html(iframeHtml);
                        _this.setValue();
                        $element.removeClass('editing');
                        _this.BtsEditor.removeClass('editing');
                    } else {
                        $element.find('.item-edit-msg').text('請輸入正確的 iframe ');
                    }
                }
            });
            $element.find('.item-edit-btn-no').click(function (e) {
                e.preventDefault();

                if ($element.data('val_content') == null) {
                    _this.deleteItem($element);
                } else {
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
        },
        initCkeditor: function ($element, open) {

        }
    };
    this.bindItemEvent = {
        dividerRow: function ($element) {
            _this.bindEvent.divideColumn($element);
            _this.bindEvent.menuAddText($element);
            _this.bindEvent.menuAddPic($element);
            _this.bindEvent.menuAddVideo($element);
        },
        dividerItem: function ($element) {
            _this.bindEvent.divideScale($element);
            _this.bindEvent.menuAddText($element);
            _this.bindEvent.menuAddPic($element);
            _this.bindEvent.menuAddVideo($element);
        },
        itemText: function ($element, open) {
            _this.bindEvent.menuEditText($element);
            _this.bindEvent.menuOrder($element);
            _this.bindEvent.menuDelete($element);
            _this.bindEvent.itemText($element, open);
        },
        itemPic: function ($element) {
            _this.bindEvent.menuEditPic($element);
            _this.bindEvent.menuOrder($element);
            _this.bindEvent.menuDelete($element);
            _this.bindEvent.itemPic($element);
        },
        itemVideo: function ($element) {
            _this.bindEvent.menuEditVideo($element);
            _this.bindEvent.menuOrder($element);
            _this.bindEvent.menuDelete($element);
            _this.bindEvent.itemVideo($element);
        }
    }


    this.exportValue = function () {
        var editorData = [];
        _this.BtsEditor.find('.btseditor-data-row').each(function () {
            var $dataRow = $(this);
            if ($dataRow.find('.btseditor-data-item').length <= 0) {
                return true;
            }
            var dataRow = {
                column: $dataRow.data('column'),
                cell: []
            };
            var totalWeight = 0;
            $dataRow.find('.btseditor-data-cell').each(function () {
                var $dataCell = $(this);
                var dataCell = {
                    weight: $dataCell.data('weight'),
                    item: []
                };
                $dataCell.find('.btseditor-data-item').each(function () {
                    var $dataItem = $(this);
                    var dataItem = {
                        type: $dataItem.data('type')
                    };
                    switch ($dataItem.data('type')) {
                        case 'text':
                            dataItem['title'] = $dataItem.data('val_title');
                            dataItem['content'] = $dataItem.data('val_content');
                            if (dataItem['title'] == '' && dataItem['content'] == '') {
                                dataItem = null;
                            }
                            break;
                        case 'pic':
                            dataItem['file'] = $dataItem.data('val_file');
                            dataItem['url'] = $dataItem.data('val_url');
                            if (dataItem['file'] == null) {
                                dataItem = null;
                            }
                            break;
                        case 'video':
                            dataItem['content'] = $dataItem.data('val_content');
                            if (dataItem['content'] == '') {
                                dataItem = null;
                            }
                            break;
                        default:
                            return true;
                            break;
                    }
                    if (dataItem !== null) {
                        dataCell.item.push(dataItem);
                    }
                });

                dataRow.cell.push(dataCell);
                totalWeight += $dataCell.data('weight');
            });
            if (totalWeight < dataRow.column) {
                var diff = dataRow.column - totalWeight;
                for (var i = 0; i < diff; i++) {
                    dataRow.cell.push({
                        weight: 1,
                        item: []
                    });
                }
            }
            editorData.push(dataRow);
        });

        return editorData;
    }
    this.importValue = function (value) {
        _this.reset();
        var $lastDivederRow = _this.BtsEditor.find('.btseditor-divider-row.last-divider');
        $.each(value, function (k, v) {
            var $dataRow = _this.addRow($lastDivederRow).data('column', v.column);
            $.each(v.cell, function (kk, vv) {
                var $dataCell = _this.addCell($dataRow, vv.weight);
                var $lastDivederItem = $dataCell.find('.btseditor-divider-item.last-divider');
                $.each(vv.item, function (kkk, vvv) {
                    var $dataItem;
                    switch (vvv.type) {
                        case 'text':
                            $dataItem = _this.addItemText($lastDivederItem, false);
                            $dataItem.find('.btseditor-item-title h2').text(vvv.title);
                            CKEDITOR.instances['btseditorcke-' + $dataItem.data('id') + '__' + _this.elementId].setData(vvv.content);
                            $dataItem.data('val_title', vvv.title);
                            $dataItem.data('val_content', vvv.content);
                            break;
                        case 'pic':
                            $dataItem = _this.addItemPic($lastDivederItem);
                            var src = _this.config.jqfu_url_upload + '/' + vvv.file.dir + '/' + vvv.file.id + '.' + vvv.file.ext;
                            var $img = $('<img />').attr('src', src);
                            $dataItem.find('.btseditor-item-pic').prepend($img).data('file', vvv.file);
                            $dataItem.data('val_file', vvv.file);
                            if ('url' in vvv) {
                                $dataItem.find('.btseditor-item-url input').val(vvv.url);
                                $dataItem.data('val_url', vvv.url);
                            }
                            break;
                        case 'video':
                            $dataItem = _this.addItemVideo($lastDivederItem);
                            $dataItem.find('.btseditor-item-content .video-container').html(vvv.content);
                            $dataItem.data('val_content', vvv.content);
                            break;
                    }
                });
            });
        });
    }
    this.getValue = function () {
        return _this.BtsEditor.find('.btseditor-value').val();
    }
    this.setValue = function () {
        _this.BtsEditor.find('.btseditor-value').val(JSON.stringify(_this.exportValue()));
    }
    this.reset = function () {
        _this.BtsEditor.find('.btseditor-content').children().not(':last').remove();
    }
    this.getDividScaleGcd = function (a, b) {
        if (a == 0 || b == 0) {
            return Math.abs(Math.max(Math.abs(a), Math.abs(b)));
        }
        var r = a % b;
        return (r != 0) ? _this.getDividScaleGcd(b, r) : Math.abs(b);
    }

    this.getMainLayout = function () {
        var html =
                '<div class="btseditor-container">' +
                '    <div class="btseditor-content">' +
                '    </div>' +
                '</div>' +
                '<input type="hidden" name="' + _this.config.name + '" class="btseditor-value" />';

        var $element = $(html);

        return $element;
    };
    this.getOrderPanel = function () {
        var html =
                '<div class="modal btseditor-order-panel" tabindex="-1" role="dialog" aria-labelledby="defModalHead" aria-hidden="true">' +
                '    <div class="modal-dialog">' +
                '        <div class="modal-content">' +
                '            <div class="modal-header">' +
                '                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
                '                <h4 class="modal-title" id="defModalHead">&nbsp</h4>' +
                '            </div>' +
                '            <div class="modal-body">' +
                '                <ul class="btseditor-order-panel-sortable"></ul>' +
                '            </div>' +
                '        </div>' +
                '    </div>' +
                '</div>';

        var $element = $(html).hide();

        return $element;
    };
    this.getOrderItem = function () {
        var html =
                '<li class="ui-state-default">' +
                '    <table>' +
                '        <tr>' +
                '            <td width="50" style="width:50px;min-width:50px;" align="center"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>' +
                '            <td><span class="btseditor-order-panel-sortable-title"></span></td>' +
                '        </tr>' +
                '    </table>' +
                '</li>';

        var $element = $(html);

        return $element;
    };
    this.getDividerRow = function () {
        var html =
                '<div class="btseditor-divider btseditor-divider-row">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '        <li class="divide-column"><a class="btseditor-menu-divide menu-set-divide-column"><span class="divide-column-con"><span class="ele_seg">分割</span>' +
                '            <span class="divide-con ele_seg">' +
                '                <span class="divide-up fa fa-sort-up"></span>' +
                '                <span class="divide-down fa fa-sort-down"></span>' +
                '            </span>' +
                '            <span class="divide-column-value ele_seg"></span><span>欄</span>' +
                '        </a><span></li>' +
                (($.inArray("text", _this.config.menu) !== -1) ? '       <li><a class="btseditor-menu-text" >文字</a></li>' : "") +
                (($.inArray("pic", _this.config.menu) !== -1) ? '       <li><a class="btseditor-menu-pic" >圖片</a></li>' : "") +
                (($.inArray("video", _this.config.menu) !== -1) ? '       <li><a class="btseditor-menu-video" >影片</a></li>' : "") +
                '   </ul>' +
                '   <div class="btseditor-divider-text">+ 新增2欄以上的資料</div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntDivider')) + 1;
        _this.BtsEditor.data('cntDivider', id);
        $element.data('id', id);
        $element.find('.divide-column-value').text('1').data('value', 1);
        $element.find('.btseditor-menu-pic').attr('id', 'btseditor-menu-pic-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getDividerItem = function () {
        var html =
                '<div class="btseditor-divider btseditor-divider-item">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                (($.inArray("text", _this.config.menu) !== -1) ? '       <li><a class="btseditor-menu-text" >文字</a></li>' : "") +
                (($.inArray("pic", _this.config.menu) !== -1) ? '       <li><a class="btseditor-menu-pic" >圖片</a></li>' : "") +
                (($.inArray("video", _this.config.menu) !== -1) ? '       <li><a class="btseditor-menu-video" >影片</a></li>' : "") +
//                '        <li class="divide-column divid-scale"><a class="btseditor-menu-divide menu-set-divide-column"><span class="divide-column-con"><span class="ele_seg">比例</span>' +
//                '            <span class="divide-con ele_seg">' +
//                '                <span class="divide-up divid-scale-a fa fa-sort-up"></span>' +
//                '                <span class="divide-down divid-scale-a fa fa-sort-down"></span>' +
//                '            </span>' +
//                '            <span class="divide-column-value divid-scale-a-val ele_seg"></span>' +
//                '            <span class="divide-con ele_seg">:</span>' +
//                '            <span class="divide-column-value divid-scale-b-val ele_seg"></span>' +
//                '            <span class="divide-con ele_seg">' +
//                '                <span class="divide-up divid-scale-b fa fa-sort-up"></span>' +
//                '                <span class="divide-down divid-scale-b fa fa-sort-down"></span>' +
//                '            </span>' +
//                '        </a><span></li>' +
                '   </ul>' +
                '   <div class="btseditor-divider-text">' +
                '+ 新增段落' +
                ((($.inArray("text", _this.config.menu) !== -1) ? '/文字' : '') +
                        (($.inArray("pic", _this.config.menu) !== -1) ? '/圖片' : '') +
                        (($.inArray("video", _this.config.menu) !== -1) ? '/影片' : '')).substr(1) +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntDivider')) + 1;
        _this.BtsEditor.data('cntDivider', id);
        $element.data('id', id);
        $element.find('.btseditor-menu-pic').attr('id', 'btseditor-menu-pic-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getRow = function () {
        var html =
                '<div class="btseditor-data-row">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '   </ul>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntRow')) + 1;
        _this.BtsEditor.data('cntRow', id);
        $element.data('id', id);
        $element.attr('id', 'btseditor-data-row-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getCell = function () {
        var html =
                '<div class="btseditor-data-cell">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-menu-order" >排序</a></li>' +
                '       <li><a class="btseditor-menu-delete" >刪除</a></li>' +
                '   </ul>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntCell')) + 1;
        _this.BtsEditor.data('cntCell', id);
        $element.data('id', id);
        $element.attr('id', 'btseditor-data-cell-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getItemText = function () {
        var html =
                '<div class="btseditor-data-item item-text cke-hide">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-menu-edit" >編輯</a></li>' +
                '       <li><a class="btseditor-menu-order" >排序</a></li>' +
                '       <li><a class="btseditor-menu-delete" >刪除</a></li>' +
                '   </ul>' +
                '   <div class="btseditor-item-title-edit">' +
                '       <input type="text" maxlength="50" placeholder="標題：" />' +
                '   </div>' +
                '   <div class="btseditor-item-title">' +
                '       <h2></h2>' +
                '   </div>' +
                '   <div class="btseditor-item-content"></div>' +
                '   <div class="btseditor-item-edit-cmd">' +
                '       <ul class="item-edit-btn">' +
                '           <li><a class="item-edit-btn-ok" >確定</a></li>' +
                '           <li><a class="item-edit-btn-no" >取消</a></li>' +
                '       </ul>' +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntItem')) + 1;
        _this.BtsEditor.data('cntItem', id);
        $element.data('id', id);
        $element.data("type", "text");
        $element.data("val_title", '');
        $element.data("val_content", '');
        $element.attr('id', 'btseditor-data-item-' + id + '__' + _this.elementId);
        $element.find('.btseditor-item-content').attr('id', 'btseditorcke-' + id + '__' + _this.elementId);
        $element.find('.btseditor-item-title-edit input').keypress(function (e) {
            if (e.which == 13 || e.keyCode == 13) {
                return false;
            }
        });

        return $element;
    };
    this.getItemPic = function () {
        var html =
                '<div class="btseditor-data-item item-pic">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-menu-edit" >編輯</a></li>' +
                '       <li><a class="btseditor-menu-order" >排序</a></li>' +
                '       <li><a class="btseditor-menu-delete" >刪除</a></li>' +
                '   </ul>' +
                '   <div class="btseditor-item-pic">' +
                '       <div class="item-pic-mask"></div>' +
                '       <ul class="item-pic-menu">' +
                '           <li><a class="item-pic-upload">重新上傳</a></li>' +
                '       </ul>' +
                '   </div>' +
                '   <div class="btseditor-item-url">' +
                '       <input type="text" class="" maxlength="200" placeholder="超連結網址：" />' +
                '   </div>' +
                '   <div class="btseditor-item-edit-cmd">' +
                '       <ul class="item-edit-btn">' +
                '           <li><a class="item-edit-btn-ok" >確定</a></li>' +
                '           <li><a class="item-edit-btn-no" >取消</a></li>' +
                '       </ul>' +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntItem')) + 1;
        _this.BtsEditor.data('cntItem', id);
        $element.data('id', id);
        $element.data("type", "pic");
        $element.data("val_file", null);
        $element.data("val_url", '');
        $element.attr('id', 'btseditor-data-item-' + id + '__' + _this.elementId);
        $element.find('.item-pic-upload').attr('id', 'item-pic-upload-' + id + '__' + _this.elementId);
        $element.find('.btseditor-item-pic').data("file", null);
        $element.find('.btseditor-item-url input').keypress(function (e) {
            if (e.which == 13 || e.keyCode == 13) {
                return false;
            }
        });

        return $element;
    };
    this.getItemPicProgress = function () {
        var html =
                '<div class="upload-progress">' +
                '   <div class="progress">' +
                '       <div class="progress-bar progress-bar-success"></div>' +
                '   </div>' +
                '</div>';

        var $element = $(html);

        return $element;
    };
    this.getItemVideo = function () {
        var html =
                '<div class="btseditor-data-item item-video">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-menu-edit" >編輯</a></li>' +
                '       <li><a class="btseditor-menu-order" >排序</a></li>' +
                '       <li><a class="btseditor-menu-delete" >刪除</a></li>' +
                '   </ul>' +
                '   <div class="btseditor-item-content-edit">' +
                '       <div class="item-edit-input">' +
                '           <textarea></textarea>' +
                '       </div>' +
                '       <div class="item-edit-info">' +
                '           <span class="item-edit-comment">請輸入 iframe </span><br />' +
                '           <span class="item-edit-msg"></span>' +
                '       </div>' +
                '   </div>' +
                '   <div class="btseditor-item-content">' +
                '       <div class="video-container"></div>' +
                '   </div>' +
                '   <div class="btseditor-item-edit-cmd">' +
                '       <ul class="item-edit-btn">' +
                '           <li><a class="item-edit-btn-ok" >確定</a></li>' +
                '           <li><a class="item-edit-btn-no" >取消</a></li>' +
                '       </ul>' +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntItem')) + 1;
        _this.BtsEditor.data('cntItem', id);
        $element.data('id', id);
        $element.data("type", "video");
        $element.data("val_content", null);
        $element.attr('id', 'btseditor-data-item-' + id + '__' + _this.elementId);

        return $element;
    };

    //=============================================
    this.init(options);
}