function BtsEditor(id, options) {
    var _this = this;
    this.elementId = id;
    this.BtsEditor = null;
    this.config = {};

    //=============================================
    this.init = function (options) {
        _this.BtsEditor = $('#' + _this.elementId);
        if (_this.BtsEditor.length <= 0) {
            alert('BtsEditor element not found.');
            return null;
        }

        //config
        var _config = {
            name: 'btseditor',
            value: '[]',
            cke_config: 'config_btseditor.js',
            jqfu_url_uploader: urlUploader,
            jqfu_url_upload: urlUpload,
            jqfu_file_size: "2 MB",
            jqfu_category: 'files',
            menu: ["text", "pic", "video", "divid"]
        };
        _this.config = $.extend(true, _config, _this.BtsEditor.data(), options);
        _this.config.menu = options.menu || _this.BtsEditor.data('menu') || _config.menu;
        _this.config.max_column = 10;

        // editor
        var $layout = _this.getElementLayout();
        _this.BtsEditor.empty().append($layout);
        _this.BtsEditor.data('cntRow', 0).data('cntCell', 0).data('cntItem', 0).data('cntDivider', 0);
        if (typeof _this.config.value == 'object' || typeof _this.config.value == 'array') {
            _this.BtsEditor.find('.btseditor-value').val(JSON.stringify(_this.config.value));
        } else {
            _this.BtsEditor.find('.btseditor-value').val(_this.config.value);
        }

        //wrap
        _this.BtsEditor.wrap($('<div></div>').addClass('btseditor-wrapper'));

        //order panel
        if ($('.btseditor-order-panel').length <= 0) {
            var $orderPanel = _this.getElementOrderPanel();
            $orderPanel.appendTo('body');
        }

        //insert last divider
        if (_this.BtsEditor.find('.btseditor-body .btseditor-divider.type-row').length <= 0) {
            var $divider = _this.getElementDividerRow().addClass('type-last');
            _this.BtsEditor.find('.btseditor-body').prepend($divider);
            _this.bindItemEvent.dividerRow($divider);
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
    this.markEditing = function () {
        if (_this.BtsEditor.hasClass('editing')) {
            return false;
        }
        _this.BtsEditor.addClass('editing');
        return true;
    }
    this.addRow = function ($dest, column) {
        var $dividerRow = _this.getElementDividerRow();
        var $row = _this.getElementRow().data('column', column);
        $dest.before($dividerRow);
        $dest.before($row);
        _this.bindItemEvent.dividerRow($dividerRow);

        return $row;
    };
    this.addCell = function ($dest, weight) {
        var $cell = _this.getElementCell().data('weight', weight);
        var column = $dest.data('column');
        $cell.addClass('column_' + weight + '_' + column);
        $dest.find('.btseditor-row-con').append($cell);

        if ($.inArray("divid", _this.config.menu) !== -1) {
            var $dividerItem = _this.getElementDividerItem().addClass('type-last');
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-child').text(weight);
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-parent').text(column);
            $cell.prepend($dividerItem);
            _this.bindItemEvent.dividerItem($dividerItem);
        } else {
            $cell.prepend($('<div></div>').addClass('btseditor-divider type-item type-last none'));
        }
        return $cell;
    };
    this.addItemText = function ($dest, open) {
        var $item = _this.getElementItemText();
        var weight = $dest.parents('.btseditor-cell:first').data('weight');
        var column = $dest.parents('.btseditor-row:first').data('column');

        if ($.inArray("divid", _this.config.menu) !== -1) {
            var $dividerItem = _this.getElementDividerItem();
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-child').text(weight);
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-parent').text(column);
            $dest.before($dividerItem);
            _this.bindItemEvent.dividerItem($dividerItem);
        }

        $dest.before($item);
        _this.bindItemEvent.itemText($item, open);

        return $item;
    };
    this.addItemPic = function ($dest) {
        var $item = _this.getElementItemPic();
        var weight = $dest.parents('.btseditor-cell:first').data('weight');
        var column = $dest.parents('.btseditor-row:first').data('column');

        if ($.inArray("divid", _this.config.menu) !== -1) {
            var $dividerItem = _this.getElementDividerItem();
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-child').text(weight);
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-parent').text(column);
            $dest.before($dividerItem);
            _this.bindItemEvent.dividerItem($dividerItem);
        }

        $dest.before($item);
        _this.bindItemEvent.itemPic($item);

        return $item;
    };
    this.addItemVideo = function ($dest) {
        var $item = _this.getElementItemVideo();
        var weight = $dest.parents('.btseditor-cell:first').data('weight');
        var column = $dest.parents('.btseditor-row:first').data('column');

        if ($.inArray("divid", _this.config.menu) !== -1) {
            var $dividerItem = _this.getElementDividerItem();
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-child').text(weight);
            $dividerItem.find('.btseditor-btn-ratio-value.ratio-parent').text(column);
            $dest.before($dividerItem);
            _this.bindItemEvent.dividerItem($dividerItem);
        }

        $dest.before($item);
        _this.bindItemEvent.itemVideo($item);

        return $item;
    };
    this.deleteItem = function ($item) {
        $item.prev('.btseditor-divider').remove();
        if ($item.hasClass('.item-text') && !!CKEDITOR.instances['btseditor-cke-' + $item.data('id') + '__' + _this.elementId]) {
            CKEDITOR.instances['btseditor-cke-' + $item.data('id') + '__' + _this.elementId].destroy();
        }
        var $row = $item.parents('.btseditor-row:first');
        $item.remove();
        if ($row.find('.btseditor-item').length <= 0) {
            $row.prev('.btseditor-divider').remove();
            $row.remove();
        }
        _this.setValue();
    };

    //
    this.bindEvent = {
        resize: function () {
        },
        btnRatioRow: function ($element) {
            $element.find('.btseditor-btn-ratio-up.ratio-child').click(function () {
                var $child = $(this).parents('li.cmd-ratio:first').find('.btseditor-btn-ratio-value.ratio-child');
                var val = $element.data('ratio_child') + 1;
                if (val <= $element.data('ratio_parent')) {
                    $element.data('ratio_child', val);
                    $child.text(val);
                }
            });
            $element.find('.btseditor-btn-ratio-down.ratio-child').click(function () {
                var $child = $(this).parents('li.cmd-ratio:first').find('.btseditor-btn-ratio-value.ratio-child');
                var val = $element.data('ratio_child') - 1;
                if (val >= 1) {
                    $element.data('ratio_child', val);
                    $child.text(val);
                }
            });
            $element.find('.btseditor-btn-ratio-up.ratio-parent').click(function () {
                var $parent = $(this).parents('li.cmd-ratio:first').find('.btseditor-btn-ratio-value.ratio-parent');
                var val = $element.data('ratio_parent') + 1;
                if (val <= _this.config.max_column) {
                    $element.data('ratio_parent', val);
                    $parent.text(val);
                }
            });
            $element.find('.btseditor-btn-ratio-down.ratio-parent').click(function () {
                var $parent = $(this).parents('li.cmd-ratio:first').find('.btseditor-btn-ratio-value.ratio-parent');
                var val = $element.data('ratio_parent') - 1;
                if (val >= $element.data('ratio_child')) {
                    $element.data('ratio_parent', val);
                    $parent.text(val);
                }
            });
        },
        btnRatioCell: function ($element) {
            $element.find('.btseditor-btn-ratio-up.ratio-child').click(function () {
                var $row = $(this).parents('.btseditor-row:first');
                var column = $row.data('column');
                var $cell = $(this).parents('.btseditor-cell:first');
                var $lastCell = $row.find('.btseditor-cell:last');
                if ($lastCell.find('.btseditor-item').length <= 0 && !$cell.is($lastCell)) {
                    var weight = $cell.data('weight');
                    var newWeight = weight + 1;
                    $cell.data('weight', newWeight);
                    $cell.removeClass('column_' + weight + '_' + column).addClass('column_' + newWeight + '_' + column);
                    $cell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                        $(this).text(newWeight);
                    });

                    var weightLast = $lastCell.data('weight');
                    var newWeightLast = weightLast - 1;
                    if (newWeightLast > 0) {
                        $lastCell.data('weight', newWeightLast);
                        $lastCell.removeClass('column_' + weightLast + '_' + column).addClass('column_' + newWeightLast + '_' + column);
                        $lastCell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                            $(this).text(newWeightLast);
                        });
                    } else {
                        $lastCell.remove();
                    }

                    _this.setValue();
                }
            });
            $element.find('.btseditor-btn-ratio-down.ratio-child').click(function () {
                var $row = $(this).parents('.btseditor-row:first');
                var column = $row.data('column');
                var $cell = $(this).parents('.btseditor-cell:first');
                var $lastCell = $row.find('.btseditor-cell:last');

                var weight = $cell.data('weight');
                var newWeight = weight - 1;
                if (newWeight > 0) {
                    $cell.data('weight', newWeight);
                    $cell.removeClass('column_' + weight + '_' + column).addClass('column_' + newWeight + '_' + column);
                    $cell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                        $(this).text(newWeight);
                    });
                } else {
                    if ($cell.find('.btseditor-item').length > 0 || $cell.is($lastCell)) {
                        return;
                    } else {
                        $cell.remove();
                    }
                }

                if ($lastCell.find('.btseditor-item').length > 0 || $cell.is($lastCell)) {
                    _this.addCell($row, 1);
                } else {
                    var weightLast = $lastCell.data('weight');
                    var newWeightLast = weightLast + 1;
                    $lastCell.data('weight', newWeightLast);
                    $lastCell.removeClass('column_' + weightLast + '_' + column).addClass('column_' + newWeightLast + '_' + column);
                    $lastCell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                        $(this).text(newWeightLast);
                    });
                }

                _this.setValue();
            });
            $element.find('.btseditor-btn-ratio-up.ratio-parent').click(function () {
                var $row = $(this).parents('.btseditor-row:first');
                var column = $row.data('column');
                var newColumn = column + 1;
                if (newColumn <= _this.config.max_column) {
                    var $cell = $row.find('.btseditor-cell:last');
                    if ($cell.find('.btseditor-item').length > 0) {
                        _this.addCell($row, 1);
                    } else {
                        var weight = $cell.data('weight');
                        var newWeight = weight + 1;
                        $cell.data('weight', newWeight);
                        $cell.removeClass('column_' + weight + '_' + column).addClass('column_' + newWeight + '_' + column);
                        $cell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                            $(this).text(newWeight);
                        });
                    }

                    $row.data('column', newColumn);
                    $row.find('.btseditor-cell').each(function () {
                        var weight = $(this).data('weight');
                        $(this).removeClass('column_' + weight + '_' + column).addClass('column_' + weight + '_' + newColumn);
                    });
                    $row.find('.btseditor-btn-ratio-value.ratio-parent').each(function () {
                        $(this).text(newColumn);
                    });

                    _this.setValue();
                }
            });
            $element.find('.btseditor-btn-ratio-down.ratio-parent').click(function () {
                var $row = $(this).parents('.btseditor-row:first');
                var column = $row.data('column');
                var newColumn = column - 1;
                var $cell = $row.find('.btseditor-cell:last');
                if (newColumn > 0 && $cell.find('.btseditor-item').length <= 0) {
                    var weight = $cell.data('weight');
                    var newWeight = weight - 1;
                    if (newWeight > 0) {
                        $cell.data('weight', newWeight);
                        $cell.removeClass('column_' + weight + '_' + column).addClass('column_' + newWeight + '_' + column);
                        $cell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                            $(this).text(newWeight);
                        });
                    } else {
                        $cell.remove();
                    }

                    $row.data('column', newColumn);
                    $row.find('.btseditor-cell').each(function () {
                        var weight = $(this).data('weight');
                        $(this).removeClass('column_' + weight + '_' + column).addClass('column_' + weight + '_' + newColumn);
                    });
                    $row.find('.btseditor-btn-ratio-value.ratio-parent').each(function () {
                        $(this).text(newColumn);
                    });

                    _this.setValue();
                }
            });
        },
        btnAddText: function ($element) {
            $element.find('.btseditor-btn-add-text').click(function (e) {
                e.preventDefault();
                if (_this.markEditing() === false) {
                    return false;
                }

                var $row = null;
                var $cell = null;
                var $divider = $element;
                if ($element.hasClass('type-row')) {
                    var column = $element.data('ratio_parent');
                    var weight = $element.data('ratio_child');
                    $row = _this.addRow($element, column);
                    $cell = _this.addCell($row, weight);
                    if (column - weight > 0) {
                        _this.addCell($row, (column - weight));
                    }
                    $divider = $row.find('.btseditor-cell:first .btseditor-divider:last');
                } else {
                    $row = $element.parents('.btseditor-row:first');
                    $cell = $element.parents('.btseditor-cell:first');
                }

                var $item = _this.addItemText($divider, true);
                $item.addClass('editing');
                $item.removeClass('cke-hide');
                CKEDITOR.instances['btseditor-cke-' + $item.data('id') + '__' + _this.elementId].focus();
            });
        },
        btnAddPic: function ($element) {
            new JqfuBtseditor("btseditor-jqfu-add-" + $element.data('id') + "__" + _this.elementId, {
                url_uploader: _this.config.jqfu_url_uploader,
                url_upload: _this.config.jqfu_url_upload,
                file_size: _this.config.jqfu_file_size,
                file_limit: 0,
                file_ext: 'jpg|jpeg|png|gif',
                category: _this.config.jqfu_category,
                btseditor: _this,
                editor_dest: $element,
                type: 'pic'
            });
            $element.find('.btseditor-btn-add-pic').click(function (e) {
                if (_this.BtsEditor.hasClass('editing')) {
                    return false;
                }
            });
        },
        btnAddVideo: function ($element) {
            $element.find('.btseditor-btn-add-video').click(function (e) {
                e.preventDefault();
                if (_this.markEditing() === false) {
                    return false;
                }

                var $row = null;
                var $cell = null;
                var $divider = $element;
                if ($element.hasClass('type-row')) {
                    var column = $element.data('ratio_parent');
                    var weight = $element.data('ratio_child');
                    $row = _this.addRow($element, column);
                    $cell = _this.addCell($row, weight);
                    if (column - weight > 0) {
                        _this.addCell($row, (column - weight));
                    }
                    $divider = $row.find('.btseditor-cell:first .btseditor-divider:last');
                } else {
                    $row = $element.parents('.btseditor-row:first');
                    $cell = $element.parents('.btseditor-cell:first');
                }

                var $item = _this.addItemVideo($divider, true);
                $item.addClass('editing');

                $(this).find('.item-video-edit-input textarea').focus();
            });
        },
        btnDelete: function ($element) {
            $element.find('.btseditor-btn-delete').click(function (e) {
                e.preventDefault();
                _this.deleteItem($element);
            });
        },
        btnOrder: function ($element) {
            $element.find('.btseditor-btn-order').click(function (e) {
                e.preventDefault();

                var $orderModal = $('.btseditor-order-panel');
                var $orderPanel = $orderModal.find('.btseditor-order-panel-sortable');


                $orderPanel.empty();
                $orderModal.modal('show');

                //$orderPanel.sortable("destroy");
                _this.BtsEditor.find('.btseditor-row').each(function () {
                    var $row = $(this);
                    var $eleRow = $('<div></div>').data('sourceId', $row.attr('id')).addClass('btseditor-order-row').appendTo($orderPanel);
                    $row.find('.btseditor-cell').each(function () {
                        var $cell = $(this);
                        var $eleCell = $('<div></div>').data('sourceId', $cell.attr('id')).addClass('btseditor-order-cell').addClass('column_' + $cell.data('weight') + '_' + $row.data('column')).appendTo($eleRow);
                        $cell.find('.btseditor-item').each(function () {
                            var $item = $(this);
                            var $eleItem = _this.getElementOrderItem().data('sourceId', $item.attr('id'));
                            if ($item.hasClass('item-text')) {
                                if ($.trim($item.find('.item-text-title h2').text()) != '') {
                                    $eleItem.find('.btseditor-order-item-title').text($item.find('.item-text-title h2').text().substr(0, 30));
                                } else {
                                    $eleItem.find('.btseditor-order-item-title').text($item.find('.cke_wysiwyg_div').text().substr(0, 30));
                                }
                            } else if ($item.hasClass('item-pic')) {
                                var imgFile = $item.find('.item-pic-photo').data('file');
                                if (!!imgFile) {
                                    var src = _this.config.jqfu_url_upload + imgFile.dir + '/' + imgFile.id + '_thumb.' + imgFile.ext;
                                    var $img = $('<img />').attr('src', src);
                                    $eleItem.find('.btseditor-order-item-title').append($img);
                                } else {
                                    $eleItem.find('.btseditor-order-item-title').text('image');
                                }
                            } else if ($item.hasClass('item-video')) {
                                var $iframe = $item.find('.item-video-iframe-con iframe:first');
                                if ($iframe.length > 0 && !!$iframe.attr('src')) {
                                    $eleItem.find('.btseditor-order-item-title').text('video: ' + $iframe.attr('src'));
                                } else {
                                    $eleItem.find('.btseditor-order-item-title').text('video');
                                }
                            } else {
                                $eleItem.find('.btseditor-order-item-title').text('empty');
                            }
                            $eleItem.appendTo($eleCell);
                        });
                    });
                });
                $orderPanel.find('.btseditor-order-cell').sortable({
                    connectWith: ".btseditor-order-cell",
                    placeholder: "btseditor-order-item-placeholder",
                    stop: function (e, ui) {
                        var $srcItem = $('#' + ui.item.data('sourceId'));
                        var $row = $('#' + ui.item.parents('.btseditor-order-row:first').data('sourceId'));
                        var $cell = $('#' + ui.item.parents('.btseditor-order-cell:first').data('sourceId'));
                        var $divider = $srcItem.prev('.btseditor-divider');
                        var $destDivider = $cell.find('.btseditor-divider.type-last');
                        var $destItem = null;
                        if (ui.item.next('.btseditor-order-item').length > 0) {
                            $destItem = $('#' + ui.item.next('.btseditor-order-item').data('sourceId'));
                            $destDivider = $destItem.prev('.btseditor-divider');
                        }

                        $destDivider.before($divider);
                        $destDivider.before($srcItem);

                        $divider.find('.btseditor-btn-ratio-value.ratio-child').text($cell.data('weight'));
                        $divider.find('.btseditor-btn-ratio-value.ratio-parent').text($row.data('column'));

                        $.each(_this.BtsEditor.find('.btseditor-row'), function () {
                            if ($(this).find('.btseditor-item').length <= 0) {
                                $(this).prev('.btseditor-divider').remove();
                                $(this).remove();
                            }
                        });

                        $orderPanel.find('.btseditor-order-row').each(function () {
                            var $tmpRow = $('#' + $(this).data('sourceId'));
                            if ($tmpRow.find('.btseditor-item').length <= 0) {
                                $tmpRow.prev('.btseditor-divider').remove();
                                $tmpRow.remove();
                                $(this).remove();
                            }
                        });

                        _this.setValue();
                    }
                });
            });
        },
        btnEditText: function ($element) {
            $element.find('.btseditor-btn-edit').click(function (e) {
                e.preventDefault();
                if (_this.markEditing() === false) {
                    return false;
                }

                $element.addClass('editing');
                $element.removeClass('cke-hide');
                $element.find('.item-text-title-edit input').val($element.data('val_title'));
                CKEDITOR.instances['btseditor-cke-' + $element.data('id') + '__' + _this.elementId].focus();
            });
        },
        btnEditPic: function ($element) {
            new JqfuBtseditor("btseditor-jqfu-edit-" + $element.data('id') + "__" + _this.elementId, {
                url_uploader: _this.config.jqfu_url_uploader,
                url_upload: _this.config.jqfu_url_upload,
                file_size: _this.config.jqfu_file_size,
                file_limit: 0,
                file_ext: 'jpg|jpeg|png|gif',
                category: _this.config.jqfu_category,
                btseditor: _this,
                editor_dest: $element,
                type: 'pic'
            });
            new JqfuBtseditor("btseditor-jqfu-edit-url-" + $element.data('id') + "__" + _this.elementId, {
                url_uploader: _this.config.jqfu_url_uploader,
                url_upload: _this.config.jqfu_url_upload,
                file_size: _this.config.jqfu_file_size,
                file_limit: 1,
                category: _this.config.jqfu_category,
                btseditor: _this,
                editor_dest: $element,
                type: 'url_file'
            });
            $element.find('.item-pic-url-option').click(function (e) {
                e.stopPropagation();
                $(this).toggleClass('open');
            });
            $('html').click(function () {
                $('.item-pic-url-option').removeClass('open');
            });
            $element.find('.item-pic-url-option-menu li a').each(function () {
                $(this).click(function (e) {
                    var $parent = $(this).parents('li:first');
                    $element.find('.item-pic-url-option-menu li').removeClass('active');
                    $parent.addClass('active');
                    $element.find('.item-pic-url-option-text').text($(this).text());

                    $element.find('.item-pic-url-con > *').removeClass('active');
                    if ($parent.hasClass('type_1')) {
                        $element.find('.item-pic-url-con .type_1').addClass('active');
                    } else if ($parent.hasClass('type_2')) {
                        $element.find('.item-pic-url-con .type_2').addClass('active');
                    }
                });
            });
            $element.find('.item-pic-url-con .type_2').click(function () {
                $element.find('.item-pic-url-jqfu .jqfubtseditor-btn-input').click();
            });

            $element.find('.item-pic-align button').click(function () {
                $element.find('.item-pic-align button').removeClass('active');
                $(this).addClass('active');
                $element.find('.item-pic-photo-con').removeClass('left center right').addClass($(this).data('val'));
            });

            $element.find('.btseditor-btn-edit').click(function (e) {
                e.preventDefault();
                if (_this.markEditing() === false) {
                    return false;
                }

                $element.addClass('editing');
                var file = $element.data('val_file');
                $element.find('.item-pic-photo').data('file', file);
                if (file != null) {
                    var file_url = _this.config.jqfu_url_upload + file.dir + '/' + file.id + '.' + file.ext;
                    if ($element.find('.item-pic-photo-con img').length <= 0) {
                        $element.find('.item-pic-photo-con').append($('<img />').attr('src', file_url));
                    } else if ($element.find('.item-pic-photo-con img').attr('src') != file_url) {
                        $element.find('.item-pic-photo-con img').attr('src', file_url);
                    }
                }

                var url_type = $element.data('val_url_type');
                var url_file = $element.data('val_url_file');
                $element.find('.item-pic-url-option-menu li.type_' + url_type + ' a').click();
                if (url_type == 1) {
                    $element.find('.item-pic-url').data('file', null);
                    $element.find('.item-pic-url-con .type_1').val($element.data('val_url'));
                    $element.find('.item-pic-url-con .type_2').empty();
                } else if (url_type == 2) {
                    $element.find('.item-pic-url').data('file', url_file);
                    $element.find('.item-pic-url-con .type_1').val('');
                    if (url_file != null) {
                        var url_file_url = _this.config.jqfu_url_upload + url_file.dir + '/' + url_file.id + '.' + url_file.ext;
                        $element.find('.item-pic-url-con .type_2').text(url_file_url);
                    } else {
                        $element.find('.item-pic-url-con .type_2').empty();
                    }
                }

                $element.find('.item-pic-align button.type_' + $element.data('val_align')).click();
            });
        },
        btnEditVideo: function ($element) {
            $element.find('.btseditor-btn-edit').click(function (e) {
                e.preventDefault();
                if (_this.markEditing() === false) {
                    return false;
                }

                $element.addClass('editing');
                $element.find('.item-video-edit-input textarea').val($element.data('val_content'));
                $element.find('.item-video-edit-msg').text('');
                $element.find('.item-video-edit-input textarea').focus();
            });
        },
        jqfuAddPic: function ($dest) {
            _this.BtsEditor.addClass('editing');

            var $row = null;
            var $cell = null;
            var $divider = null;
            var $item = null;
            if ($dest.hasClass('type-row')) {
                var column = $dest.data('ratio_parent');
                var weight = $dest.data('ratio_child');
                var $prevRow = $dest.prev('.btseditor-row.uploading');
                if ($prevRow.length <= 0
                        || $prevRow.find('.btseditor-cell:last').find('.btseditor-item').length > 0
                        || $prevRow.find('.btseditor-cell:last').data('weight') < weight) {
                    $row = _this.addRow($dest, column).addClass('uploading');
                    $cell = _this.addCell($row, weight).addClass('uploading');
                    if (column - weight > 0) {
                        _this.addCell($row, (column - weight));
                    }
                    $divider = $row.find('.btseditor-cell:first .btseditor-divider:last');
                } else {
                    $row = $prevRow;
                    $cell = $prevRow.find('.btseditor-cell:last').addClass('uploading');
                    var oldWeight = $cell.data('weight');
                    var remainWeight = oldWeight - weight;
                    $cell.data('weight', weight);
                    $cell.removeClass('column_' + oldWeight + '_' + column).addClass('column_' + weight + '_' + column);
                    $cell.find('.btseditor-btn-ratio-value.ratio-child').each(function () {
                        $(this).text(weight).data('value', weight);
                    });
                    $cell.find('.btseditor-btn-ratio-value.ratio-parent').each(function () {
                        $(this).text(column).data('value', column);
                    });
                    if (remainWeight > 0) {
                        _this.addCell($row, remainWeight);
                    }

                    $divider = $cell.find('.btseditor-divider:last');
                }
                $item = _this.addItemPic($divider).addClass('editing');
                var $itemUploadProgress = _this.getElementItemPicProgress();
                $item.find('.item-pic-photo').before($itemUploadProgress);
            } else if ($dest.hasClass('type-item')) {
                $item = _this.addItemPic($dest).addClass('editing');
                var $itemUploadProgress = _this.getElementItemPicProgress();
                $item.find('.item-pic-photo').before($itemUploadProgress);
            } else {
                $item = $dest.addClass('editing');
                var $itemUploadProgress = _this.getElementItemPicProgress();
                $item.find('.upload-progress').remove();
                $item.find('.item-pic-photo-con').empty();
                $item.find('.item-pic-photo').before($itemUploadProgress);
            }

            return $item;
        },
        jqfuAddUrlFile: function ($dest) {
            var $item = $dest.find('.item-pic-url');
            var $itemUploadProgress = _this.getElementItemPicProgress();
            $item.find('.item-pic-url-con .type_2').append($itemUploadProgress);

            return $item;
        },
        itemText: function ($element, open) {
            $element.find('.item-text-content').attr('contenteditable', 'false');
            CKEDITOR.disableAutoInline = true;
            CKEDITOR.replace('btseditor-cke-' + $element.data('id') + '__' + _this.elementId, {
                customConfig: _this.config.cke_config,
                on: {
                    'instanceReady': function (evt) {
                        if (open == true) {
                            this.focus();
                        }
                    }
                }
            });

            $element.find('.btseditor-btn-edit-ok').click(function (e) {
                e.preventDefault();

                var title = $element.find('.item-text-title-edit input').val();
                var content = CKEDITOR.instances['btseditor-cke-' + $element.data('id') + '__' + _this.elementId].getData();
                if (title.length <= 0 && content.length <= 0) {
                    _this.deleteItem($element);
                } else {
                    $element.addClass('cke-hide');
                    $element.find('.item-text-title h2').text(title);
                    $element.data('val_title', title);
                    $element.data('val_content', content);
                    _this.setValue();
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
            $element.find('.btseditor-btn-edit-no').click(function (e) {
                e.preventDefault();

                var title = $element.data('val_title');
                var content = $element.data('val_content');
                if (title.length <= 0 && content.length <= 0) {
                    _this.deleteItem($element);
                } else {
                    $element.addClass('cke-hide');
                    $element.find('.item-text-title h2').text(title);
                    CKEDITOR.instances['btseditor-cke-' + $element.data('id') + '__' + _this.elementId].setData(content);
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
        },
        itemPic: function ($element) {
            $element.find('.btseditor-btn-edit-ok').click(function (e) {
                e.preventDefault();

                var file = $element.find('.item-pic-photo').data('file');
                if (file == null) {
                    _this.deleteItem($element);
                } else {
                    $element.data('val_file', file);

                    var url_type = parseInt($element.find('.item-pic-url-option-menu li.active').data('val'), 10);
                    $element.data('val_url_type', url_type);
                    if (url_type == 1) {
                        $element.data('val_url', $element.find('.item-pic-url-con .type_1').val());
                        $element.data('val_url_file', null);
                    } else if (url_type == 2) {
                        $element.data('val_url', '');
                        $element.data('val_url_file', $element.find('.item-pic-url').data('file'));
                    }

                    $element.data('val_align', $element.find('.item-pic-align button.active').data('val'));
                    _this.setValue();
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
            $element.find('.btseditor-btn-edit-no').click(function (e) {
                e.preventDefault();

                var file = $element.data('val_file');
                if (file == null) {
                    _this.deleteItem($element);
                } else {
                    $element.find('.item-pic-photo').data('file', file);
                    var file_url = _this.config.jqfu_url_upload + file.dir + '/' + file.id + '.' + file.ext;
                    if ($element.find('.item-pic-photo-con img').length <= 0) {
                        $element.find('.item-pic-photo-con').append($('<img />').attr('src', file_url));
                    } else if ($element.find('.item-pic-photo-con img').attr('src') != file_url) {
                        $element.find('.item-pic-photo-con img').attr('src', file_url);
                    }

                    var url_type = $element.data('val_url_type');
                    var url_file = $element.data('val_url_file');
                    $element.find('.item-pic-url-option-menu li.type_' + url_type + ' a').click();
                    if (url_type == 1) {
                        $element.find('.item-pic-url').data('file', null);
                        $element.find('.item-pic-url-con .type_1').val($element.data('val_url'));
                        $element.find('.item-pic-url-con .type_2').empty();
                    } else if (url_type == 2) {
                        $element.find('.item-pic-url').data('file', url_file);
                        $element.find('.item-pic-url-con .type_1').val('');
                        if (url_file != null) {
                            var url_file_url = _this.config.jqfu_url_upload + url_file.dir + '/' + url_file.id + '.' + url_file.ext;
                            $element.find('.item-pic-url-con .type_2').text(url_file_url);
                        } else {
                            $element.find('.item-pic-url-con .type_2').empty();
                        }
                    }

                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
        },
        itemVideo: function ($element) {
            $element.find('.btseditor-btn-edit-ok').click(function (e) {
                e.preventDefault();

                var val = $.trim($element.find('.item-video-edit-input textarea').val());
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
                        $element.find('.item-video-iframe-con').html(iframeHtml);
                        _this.setValue();
                        $element.removeClass('editing');
                        _this.BtsEditor.removeClass('editing');
                    } else {
                        $element.find('.item-video-edit-msg').text('請輸入正確的 iframe ');
                    }
                }
            });
            $element.find('.btseditor-btn-edit-no').click(function (e) {
                e.preventDefault();

                var content = $element.data('val_content');
                if (content == '') {
                    _this.deleteItem($element);
                } else {
                    $element.find('.item-video-iframe-con').html(content);
                    $element.removeClass('editing');
                }
                _this.BtsEditor.removeClass('editing');
            });
        },
    };
    this.bindItemEvent = {
        dividerRow: function ($element) {
            _this.bindEvent.btnRatioRow($element);
            _this.bindEvent.btnAddText($element);
            _this.bindEvent.btnAddPic($element);
            _this.bindEvent.btnAddVideo($element);
        },
        dividerItem: function ($element) {
            _this.bindEvent.btnRatioCell($element);
            _this.bindEvent.btnAddText($element);
            _this.bindEvent.btnAddPic($element);
            _this.bindEvent.btnAddVideo($element);
        },
        itemText: function ($element, open) {
            _this.bindEvent.btnEditText($element);
            _this.bindEvent.btnOrder($element);
            _this.bindEvent.btnDelete($element);
            _this.bindEvent.itemText($element, open);
        },
        itemPic: function ($element) {
            _this.bindEvent.btnEditPic($element);
            _this.bindEvent.btnOrder($element);
            _this.bindEvent.btnDelete($element);
            _this.bindEvent.itemPic($element);
        },
        itemVideo: function ($element) {
            _this.bindEvent.btnEditVideo($element);
            _this.bindEvent.btnOrder($element);
            _this.bindEvent.btnDelete($element);
            _this.bindEvent.itemVideo($element);
        }
    }


    this.exportValue = function () {
        var editorData = [];
        _this.BtsEditor.find('.btseditor-row').each(function () {
            var $dataRow = $(this);
            if ($dataRow.find('.btseditor-item').length <= 0) {
                return true;
            }
            var dataRow = {
                column: $dataRow.data('column'),
                cell: []
            };
            var totalWeight = 0;
            $dataRow.find('.btseditor-cell').each(function () {
                var $dataCell = $(this);
                var dataCell = {
                    weight: $dataCell.data('weight'),
                    item: []
                };
                $dataCell.find('.btseditor-item').each(function () {
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
                            dataItem['url_type'] = $dataItem.data('val_url_type');
                            dataItem['url'] = $dataItem.data('val_url');
                            dataItem['url_file'] = $dataItem.data('val_url_file');
                            dataItem['align'] = $dataItem.data('val_align');
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
                dataRow.cell.push({
                    weight: diff,
                    item: []
                });
            }
            editorData.push(dataRow);
        });

        return editorData;
    }
    this.importValue = function (value) {
        _this.reset();
        var $lastDivederRow = _this.BtsEditor.find('.btseditor-divider.type-row.type-last');
        $.each(value, function (k, v) {
            var $dataRow = _this.addRow($lastDivederRow, v.column);
            $.each(v.cell, function (kk, vv) {
                var $dataCell = _this.addCell($dataRow, vv.weight);
                var $lastDivederItem = $dataCell.find('.btseditor-divider.type-item.type-last');
                $.each(vv.item, function (kkk, vvv) {
                    var $dataItem;
                    switch (vvv.type) {
                        case 'text':
                            $dataItem = _this.addItemText($lastDivederItem, false);
                            $dataItem.data('val_title', vvv.title);
                            $dataItem.data('val_content', vvv.content);
                            $dataItem.find('.item-text-title h2').text(vvv.title);
                            $dataItem.find('.item-text-title-edit input').val(vvv.title);
                            CKEDITOR.instances['btseditor-cke-' + $dataItem.data('id') + '__' + _this.elementId].setData(vvv.content);
                            break;
                        case 'pic':
                            $dataItem = _this.addItemPic($lastDivederItem);
                            $dataItem.data('val_file', vvv.file);
                            var file_src = _this.config.jqfu_url_upload + vvv.file.dir + '/' + vvv.file.id + '.' + vvv.file.ext;
                            $dataItem.find('.item-pic-photo').data('file', vvv.file)
                            $dataItem.find('.item-pic-photo-con').prepend($('<img />').attr('src', file_src));

                            if ('url_type' in vvv) {
                                $dataItem.data('val_url_type', vvv.url_type);
                                $dataItem.find('.item-pic-url-option-menu li.type_' + vvv.url_type + ' a').click();
                            }
                            if ('url' in vvv) {
                                $dataItem.data('val_url', vvv.url);
                                $dataItem.find('.item-pic-url-con .type_1').val(vvv.url);
                            }
                            if ('url_file' in vvv) {
                                $dataItem.data('val_url_file', vvv.url_file);
                                $dataItem.find('.item-pic-url').data('file', vvv.url_file);
                                if (vvv.url_file != null) {
                                    var url_file_src = _this.config.jqfu_url_upload + vvv.url_file.dir + '/' + vvv.url_file.id + '.' + vvv.url_file.ext;
                                    $dataItem.find('.item-pic-url-con .type_2').text(url_file_src);
                                } else {
                                    $dataItem.find('.item-pic-url-con .type_2').empty();
                                }
                            }
                            if ('align' in vvv) {
                                $dataItem.data('val_align', vvv.align);
                                $dataItem.find('.item-pic-align button.type_' + vvv.align).click();
                            }

                            break;
                        case 'video':
                            $dataItem = _this.addItemVideo($lastDivederItem);
                            $dataItem.data('val_content', vvv.content);
                            $dataItem.find('.item-video-iframe-con').html(vvv.content);
                            $dataItem.find('.item-video-edit-input textarea').val(vvv.content);
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
        _this.BtsEditor.find('.btseditor-body').children().not(':last').remove();
    }
    this.getRatioGcd = function (a, b) {
        if (a == 0 || b == 0) {
            return Math.abs(Math.max(Math.abs(a), Math.abs(b)));
        }
        var r = a % b;
        return (r != 0) ? _this.getRatioGcd(b, r) : Math.abs(b);
    }

    // html
    this.getElementLayout = function () {
        var html =
                '<div class="btseditor-body btseditor-col clearfloat">' +
                '</div>' +
                '<input type="hidden" name="' + _this.config.name + '" class="btseditor-value" />';

        var $element = $(html);
        return $element;
    };
    this.getElementRow = function () {
        var html =
                '<div class="btseditor-row">' +
                '   <div class="btseditor-row-con">' +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntRow')) + 1;
        _this.BtsEditor.data('cntRow', id);
        $element.data('id', id);
        $element.attr('id', 'btseditor-row-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getElementCell = function () {
        var html =
                '<div class="btseditor-cell">' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntCell')) + 1;
        _this.BtsEditor.data('cntCell', id);
        $element.data('id', id);
        $element.attr('id', 'btseditor-cell-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getElementDividerRow = function () {
        var html =
                '<div class="btseditor-divider type-row">' +
                '   <div class="btseditor-divider-text">&#x2795 新增2欄以上的資料</div>' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                (($.inArray("divid", _this.config.menu) !== -1) ?
                        '       <li class="cmd-ratio">' +
                        '           <a>' +
                        '               <span>分欄：</span>' +
                        '               <span class="ratio-con">' +
                        '                   <span class="btseditor-btn-ratio-up ratio-child fa fa-sort-up"></span>' +
                        '                   <span class="btseditor-btn-ratio-down ratio-child fa fa-sort-down"></span>' +
                        '               </span>' +
                        '               <span class="btseditor-btn-ratio-value ratio-child"></span>' +
                        '               <span>/</span>' +
                        '               <span class="btseditor-btn-ratio-value ratio-parent"></span>' +
                        '               <span class="up-down-con">' +
                        '                   <span class="btseditor-btn-ratio-up ratio-parent fa fa-sort-up"></span>' +
                        '                   <span class="btseditor-btn-ratio-down ratio-parent fa fa-sort-down"></span>' +
                        '               </span>' +
                        '           </a>' +
                        '       </li>'
                        : "") +
                (($.inArray("text", _this.config.menu) !== -1) ? '       <li><a class="btseditor-btn-add-text" >文字</a></li>' : "") +
                (($.inArray("pic", _this.config.menu) !== -1) ? '       <li><a class="btseditor-btn-add-pic" >圖片</a></li>' : '       <li style="display:none"><a class="btseditor-btn-add-pic" >圖片</a></li>') +
                (($.inArray("video", _this.config.menu) !== -1) ? '       <li><a class="btseditor-btn-add-video" >影片</a></li>' : "") +
                '   </ul>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntDivider')) + 1;
        _this.BtsEditor.data('cntDivider', id);
        $element.data('id', id).data('ratio_child', 1).data('ratio_parent', 1);
        $element.find('.btseditor-btn-ratio-value.ratio-child').text('1');
        $element.find('.btseditor-btn-ratio-value.ratio-parent').text('1');
        $element.find('.btseditor-btn-add-pic').attr('id', 'btseditor-jqfu-add-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getElementDividerItem = function () {
        var html =
                '<div class="btseditor-divider type-item">' +
                '   <div class="btseditor-divider-text">&#x2795</div>' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li class="cmd-ratio">' +
                '           <a>' +
                '               <span>分欄：</span>' +
                '               <span class="ratio-con">' +
                '                   <span class="btseditor-btn-ratio-up ratio-child fa fa-sort-up"></span>' +
                '                   <span class="btseditor-btn-ratio-down ratio-child fa fa-sort-down"></span>' +
                '               </span>' +
                '               <span class="btseditor-btn-ratio-value ratio-child"></span>' +
                '               <span>/</span>' +
                '               <span class="btseditor-btn-ratio-value ratio-parent"></span>' +
                '               <span class="up-down-con">' +
                '                   <span class="btseditor-btn-ratio-up ratio-parent fa fa-sort-up"></span>' +
                '                   <span class="btseditor-btn-ratio-down ratio-parent fa fa-sort-down"></span>' +
                '               </span>' +
                '           </a>' +
                '       </li>' +
                (($.inArray("text", _this.config.menu) !== -1) ? '       <li><a class="btseditor-btn-add-text" >文字</a></li>' : "") +
                (($.inArray("pic", _this.config.menu) !== -1) ? '       <li><a class="btseditor-btn-add-pic" >圖片</a></li>' : "") +
                (($.inArray("video", _this.config.menu) !== -1) ? '       <li><a class="btseditor-btn-add-video" >影片</a></li>' : "") +
                '   </ul>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntDivider')) + 1;
        _this.BtsEditor.data('cntDivider', id);
        $element.data('id', id);
        $element.find('.btseditor-btn-add-pic').attr('id', 'btseditor-jqfu-add-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getElementItemText = function () {
        var html =
                '<div class="btseditor-item item-text cke-hide">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-btn-edit" >編輯</a></li>' +
                '       <li><a class="btseditor-btn-order" >排序</a></li>' +
                '       <li><a class="btseditor-btn-delete" >刪除</a></li>' +
                '   </ul>' +
                '   <div class="item-text-title-edit clearfloat">' +
                '       <input type="text" maxlength="50" placeholder="標題：" />' +
                '   </div>' +
                '   <div class="item-text-title">' +
                '       <h2></h2>' +
                '   </div>' +
                '   <div class="item-text-content"></div>' +
                '   <div class="btseditor-item-save">' +
                '       <ul>' +
                '           <li class="btseditor-btn"><a class="btseditor-btn-edit-ok">確定</a></li>' +
                '           <li class="btseditor-btn"><a class="btseditor-btn-edit-no">取消</a></li>' +
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
        $element.attr('id', 'btseditor-item-' + id + '__' + _this.elementId);
        $element.find('.item-text-content').attr('id', 'btseditor-cke-' + id + '__' + _this.elementId);
        $element.find('.item-text-title-edit input').keypress(function (e) {
            if (e.which == 13 || e.keyCode == 13) {
                return false;
            }
        });

        return $element;
    };
    this.getElementItemPic = function () {
        var html =
                '<div class="btseditor-item item-pic">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-btn-edit" >編輯</a></li>' +
                '       <li><a class="btseditor-btn-order" >排序</a></li>' +
                '       <li><a class="btseditor-btn-delete" >刪除</a></li>' +
                '   </ul>' +
                '   <div class="item-pic-photo">' +
                '       <div class="item-pic-photo-con"></div>' +
                '       <div class="btseditor-mask"></div>' +
                '       <ul class="btseditor-menu">' +
                '           <li><a class="item-pic-photo-jqfu">重新上傳</a></li>' +
                '       </ul>' +
                '   </div>' +
                '   <div class="item-pic-url clearfloat">' +
                '       <div class="item-pic-url-option btn_style">' +
                '           <span class="item-pic-url-option-text">網址</span>' +
                '           <span class="item-pic-url-option-arrow">&#x25BC;</span>' +
                '           <ul class="item-pic-url-option-menu">' +
                '               <li data-val="1" class="type_1 active"><a>網址</a></li>' +
                '               <li data-val="2" class="type_2"><a class="item-pic-url-jqfu">檔案</a></li>' +
                '           </ul>' +
                '       </div>' +
                '       <div class="item-pic-url-con">' +
                '           <input type="text" class="type_1 active" maxlength="200" placeholder="超連結網址：" />' +
                '           <div class="type_2"></div>' +
                '       </div>' +
                '   </div>' +
                '   <div class="item-pic-align clearfloat">' +
                '       <button type="button" class="btn_style type_left" data-val="left">置左</button>' +
                '       <button type="button" class="btn_style type_center active" data-val="center">置中</button>' +
                '       <button type="button" class="btn_style type_right" data-val="right">置右</button>' +
                '   </div>' +
                '   <div class="btseditor-item-save">' +
                '       <ul>' +
                '           <li class="btseditor-btn"><a class="btseditor-btn-edit-ok">確定</a></li>' +
                '           <li class="btseditor-btn"><a class="btseditor-btn-edit-no">取消</a></li>' +
                '       </ul>' +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntItem')) + 1;
        _this.BtsEditor.data('cntItem', id);
        $element.data('id', id);
        $element.data("type", "pic");
        $element.data("val_file", null);
        $element.data("val_url_type", 1);
        $element.data("val_url", '');
        $element.data("val_url_file", null);
        $element.data("val_align", 'center');
        $element.attr('id', 'btseditor-item-' + id + '__' + _this.elementId);
        $element.find('.item-pic-photo-jqfu').attr('id', 'btseditor-jqfu-edit-' + id + '__' + _this.elementId);
        $element.find('.item-pic-url-jqfu').attr('id', 'btseditor-jqfu-edit-url-' + id + '__' + _this.elementId);

        $element.find('.item-pic-photo').data("file", null);
        $element.find('.item-pic-url').data("file", null);
        $element.find('.item-pic-url-con .type_1').keypress(function (e) {
            if (e.which == 13 || e.keyCode == 13) {
                return false;
            }
        });

        return $element;
    };
    this.getElementItemPicProgress = function () {
        var html =
                '<div class="upload-progress">' +
                '   <div class="progress">' +
                '       <div class="progress-bar progress-bar-success"></div>' +
                '   </div>' +
                '</div>';

        var $element = $(html);

        return $element;
    };
    this.getElementItemVideo = function () {
        var html =
                '<div class="btseditor-item item-video">' +
                '   <div class="btseditor-mask"></div>' +
                '   <ul class="btseditor-menu">' +
                '       <li><a class="btseditor-btn-edit" >編輯</a></li>' +
                '       <li><a class="btseditor-btn-order" >排序</a></li>' +
                '       <li><a class="btseditor-btn-delete" >刪除</a></li>' +
                '   </ul>' +
                '   <div class="item-video-edit">' +
                '       <div class="item-video-edit-input clearfloat">' +
                '           <textarea rows="5"></textarea>' +
                '       </div>' +
                '       <div class="item-video-edit-info">' +
                '           <span class="item-video-edit-comment">請輸入 iframe </span><br />' +
                '           <span class="item-video-edit-msg"></span>' +
                '       </div>' +
                '   </div>' +
                '   <div class="item-video-content">' +
                '       <div class="item-video-iframe-con"></div>' +
                '   </div>' +
                '   <div class="btseditor-item-save">' +
                '       <ul>' +
                '           <li class="btseditor-btn"><a class="btseditor-btn-edit-ok">確定</a></li>' +
                '           <li class="btseditor-btn"><a class="btseditor-btn-edit-no">取消</a></li>' +
                '       </ul>' +
                '   </div>' +
                '</div>';

        var $element = $(html);
        var id = (_this.BtsEditor.data('cntItem')) + 1;
        _this.BtsEditor.data('cntItem', id);
        $element.data('id', id);
        $element.data("type", "video");
        $element.data("val_content", '');
        $element.attr('id', 'btseditor-item-' + id + '__' + _this.elementId);

        return $element;
    };
    this.getElementOrderPanel = function () {
        var html =
                '<div class="modal btseditor-order-panel" tabindex="-1" role="dialog" aria-labelledby="defModalHead" aria-hidden="true">' +
                '    <div class="modal-dialog">' +
                '        <div class="modal-content">' +
                '            <div class="modal-header">' +
                '                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
                '                <h4 class="modal-title" id="defModalHead">&nbsp</h4>' +
                '            </div>' +
                '            <div class="modal-body">' +
                '                <div class="btseditor-order-panel-sortable btseditor-col clearfloat"></div>' +
                '            </div>' +
                '        </div>' +
                '    </div>' +
                '</div>';

        var $element = $(html).hide();

        return $element;
    };
    this.getElementOrderItem = function () {
        var html =
                '<div class="ui-state-default btseditor-order-item">' +
                '   <div class="btseditor-order-item-icon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>' +
                '   <div class="btseditor-order-item-title"></div>' +
                '</div>';

        var $element = $(html);

        return $element;
    };

    //=============================================
    this.init(options);
}