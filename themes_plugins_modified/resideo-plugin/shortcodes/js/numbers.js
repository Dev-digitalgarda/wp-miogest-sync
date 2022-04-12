(function($) {
    "use strict";

    tinymce.PluginManager.add('res_numbers', function(editor, url) {
        var shortcodeTag = 'res_numbers';
        var toolbar;
        var content;

        function getAttr(s, n) {
            n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
            return n ? window.decodeURIComponent(n[1]) : '';
        };

        function getHTML(cls, data ,con) {
            var placeholder = url + '/../images/bg.png';
            data = window.encodeURIComponent(data);
            content = window.encodeURIComponent(con);

            return '<img src="' + placeholder + '" class="mceItem res-numbers-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_numbers([^\]]*)\]([^\]]*)\[\/res_numbers\]/g, function(all, attr, con) {
                return getHTML('wp-res_numbers', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-numbers-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
                var data = getAttr(image, 'data-sh-attr');
                var con = getAttr(image, 'data-sh-content');

                if (data) {
                    return '<p>[' + shortcodeTag + data + ']' + con + '[/' + shortcodeTag + ']</p>';
                }
                return match;
            });
        }

        function removeShortcode() {
            var img = editor.selection.getNode();

            toolbar.hide();

            editor.windowManager.confirm(sh_vars.sh_delete_confirmation, function(s) {
                if (s) {
                    editor.dom.remove(img);
                    editor.nodeChanged();
                    toolbar.hide();
                } else {
                    toolbar.show();
                }
            });
        }

        function editShortcode() {
            var img = editor.selection.getNode();
            var data = img.attributes['data-sh-attr'].value;
            data = window.decodeURIComponent(data);
            var content = img.attributes['data-sh-content'].value;

            editor.execCommand('res_numbers_panel_popup', '', {
                data_content : getAttr(data, 'data_content'),
            });
        }

        function getObjectProperty(obj, prop) {
            var prop = typeof prop !== 'undefined' ? prop : '';
            var obj = typeof obj !== 'undefined' ? obj : '';

            if (!prop || !obj) {
                return '';
            }

            var ret = obj.hasOwnProperty(prop) ? ( String(obj[prop]) !== ''? obj[prop] : '') : '';

            return ret;
        }

        // Open shortcode modal
        function openShortcodeModal(obj) {
            var short = $.parseJSON(obj);

            var modalContent = 
                '<div tabindex="0" role="dialog" id="shortcode-modal" style="position: relative;">' + 
                    '<div class="media-modal wp-core-ui">' + 
                        '<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>' + 
                        '<div class="media-modal-content">' + 
                            '<div class="media-frame mode-select wp-core-ui" id="">' + 
                                '<div class="media-frame-title">' + 
                                    '<h1>' + sh_vars.numbers_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-numbers-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-numbers-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-numbers-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-numbers-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-numbers-margin">' + sh_vars.margin_label + '</label>';
            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }
            modalContent += 
                                                    '<select class="form-control" id="sh-numbers-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label style="display: block; padding-bottom: 3px;">' + sh_vars.img_label + '</label>' + 
                                                    '<input type="hidden" id="sh-numbers-image" name="sh-numbers-image" data-src="' + getObjectProperty(short, "image_src") + '" value="' + getObjectProperty(short, "image") + '">' + 
                                                    '<div class="sh-numbers-image-placeholder-container';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += 
                                                        ' has-image'; 
            }
                modalContent += 
                                                    '"><div id="sh-numbers-image-placeholder" style="background-image: url(';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += getObjectProperty(short, "image_src");
            } else { 
                modalContent += sh_vars.plugin_url + 'images/image-placeholder.png'; 
            }
            modalContent += 
                                                        ');"></div>'+
                                                        '<div id="delete-numbers-image"><span class="fa fa-trash-o"></span></div>' +
                                                    '</div>' +
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.numbers_list + '</div>' + 
                                        '<ul class="list-group" id="numbers-list">';

            var numbers = getObjectProperty(short, "numbers");

            if ($.isArray(numbers)) {
                if (numbers.length > 0) {
                    $.each(numbers, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem">' + 
                                                    '<div class="list-group-item-img">' + 
                                                        '<span>' + elem.sum + elem.sign + '</span>' + 
                                                    '</div>' + 
                                                    '<div class="list-group-item-info" data-sum="' + elem.sum + '" data-sign="' + elem.sign + '" data-delay="' + elem.delay + '" data-increment="' + elem.increment + '">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="number-title">' + elem.title + '</span>' + 
                                                        '</div>' + 
                                                        '<div class="list-group-item-info-caption">' + 
                                                            '<span class="number-text">' + elem.text + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-number"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<a href="javascript:void(0);" class="pull-right edit-btn edit-number"><span class="fa fa-pencil"></span></a>' + 
                                                '</div>' + 
                                            '<li>';
                    });
                } else {
                    modalContent += 
                                            '<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>';
                }
            } else {
                modalContent += 
                                            '<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>';
                numbers = [];
            }

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-number" class="button media-button button-default">' + sh_vars.add_number_btn + '</button>' + 
                                        '<div class="shortcode-modal-new-container" style="display: none;">' + 
                                            '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.new_number_header + '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-number-sum">' + sh_vars.number_sum_label + '</label>' + 
                                                        '<input type="number" min="1" id="sh-new-number-sum" class="form-control" placeholder="1" value="1">' + 
                                                    '</div>' + 
                                                '</div>' + 
                                                '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-number-sign">' + sh_vars.number_sign_label + '</label>' + 
                                                        '<input type="text" id="sh-new-number-sign" class="form-control" placeholder="' + sh_vars.number_sign_placeholder + '">' + 
                                                    '</div>' + 
                                                '</div>' + 
                                                '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-number-delay">' + sh_vars.number_delay_label + '</label>' + 
                                                        '<input type="number" min="1" id="sh-new-number-delay" class="form-control" placeholder="1" value="1">' + 
                                                    '</div>' + 
                                                '</div>' + 
                                                '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-number-increment">' + sh_vars.number_increment_label + '</label>' + 
                                                        '<input type="number" min="1" id="sh-new-number-increment" class="form-control" placeholder="1" value="1">' + 
                                                    '</div>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-number-title">' + sh_vars.number_title_label + '</label>' + 
                                                        '<input type="text" id="sh-new-number-title" class="form-control" placeholder="' + sh_vars.number_title_placeholder + '">' + 
                                                    '</div>'+ 
                                                '</div>'+ 
                                                '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-number-text">' + sh_vars.number_text_label + '</label>' + 
                                                        '<input type="text" id="sh-new-number-text" class="form-control" placeholder="' + sh_vars.number_text_placeholder + '">' + 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<button type="button" id="ok-number" class="button media-button button-primary">' + sh_vars.ok_number_btn + '</button>' + 
                                            '<button type="button" id="cancel-number" class="button media-button button-default">' + sh_vars.cancel_number_btn + '</button>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="media-frame-toolbar" style="left: 0;">' + 
                                    '<div class="media-toolbar">' + 
                                        '<div class="media-toolbar-primary search-form">' + 
                                            '<button type="button" id="cancel-button" class="button media-button button-default button-large">' + sh_vars.cancel_btn + '</button>' + 
                                            '<button type="button" id="insert-button" class="button media-button button-primary button-large">' + sh_vars.insert_btn + '</button>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                    '</div>' + 
                    '<div class="media-modal-backdrop"></div>' + 
                '</div>';

            $('body').append(modalContent);

            $('#shortcode-modal .media-modal-close').on('click', function(e) {
                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });
            $('#shortcode-modal #cancel-button').on('click', function(e) {
                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });
            $('#shortcode-modal').on('keyup',function(e) {
                var _self = $(this);
                if (e.keyCode == 27) {
                    setTimeout(function() {
                        _self.remove();
                    }, 100);
                    e.preventDefault();
                }
            });

            $('#shortcode-modal #insert-button').on('click', function(e) {
                var shortVal = {
                    'title'    : $('#sh-numbers-title').val(),
                    'subtitle' : $('#sh-numbers-subtitle').val(),
                    'image'    : $('#sh-numbers-image').val(),
                    'image_src': $('#sh-numbers-image').attr('data-src'),
                    'margin'   : $('#sh-numbers-margin').val(),
                    'numbers'  : numbers
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('#sh-numbers-image-placeholder').on('click', function(event) {
                event.preventDefault();

                var frame = wp.media({
                    title: sh_vars.media_numbers_image_title,
                    button: {
                        text: sh_vars.media_numbers_image_btn
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').toJSON();
                    $.each(attachment, function(index, value) {
                        $('#sh-numbers-image').val(value.id).attr('data-src', value.url);
                        $('#sh-numbers-image-placeholder').css('background-image', 'url(' + value.url + ')');
                        $('.sh-numbers-image-placeholder-container').addClass('has-image');
                    });
                });

                frame.open();
            });

            $('#delete-numbers-image').on('click', function() {
                $('#sh-numbers-image').val('').attr('data-src', '');
                $('#sh-numbers-image-placeholder').css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)');
                $('.sh-numbers-image-placeholder-container').removeClass('has-image');
            });

            $('#add-number').on('click', function(event) {
                $(this).hide();
                $('.shortcode-modal-new-container').show();
            });

            $('#cancel-number').on('click', function(event) {
                $('.shortcode-modal-new-container').hide();
                $('#sh-new-number-sum').val('1');
                $('#sh-new-number-sign').val('');
                $('#sh-new-number-delay').val('1');
                $('#sh-new-number-increment').val('1');
                $('#sh-new-number-title').val('');
                $('#sh-new-number-text').val('');
                $('#add-number').show();
            });

            $('#ok-number').on('click', function(event) {
                var newSum       = $('#sh-new-number-sum').val();
                var newSign      = $('#sh-new-number-sign').val();
                var newDelay     = $('#sh-new-number-delay').val();
                var newIncrement = $('#sh-new-number-increment').val();
                var newTitle     = $('#sh-new-number-title').val();
                var newText      = $('#sh-new-number-text').val();

                numbers.push({
                    'sum'      : newSum,
                    'sign'     : newSign,
                    'delay'    : newDelay,
                    'increment': newIncrement,
                    'title'    : newTitle,
                    'text'     : newText
                });

                var newNumber = 
                    '<li class="list-group-item">' + 
                        '<div class="list-group-item-elem">' + 
                            '<div class="list-group-item-img">' + 
                                '<span>' + newSum + newSign + '</span>' + 
                            '</div>' + 
                            '<div class="list-group-item-info" data-sum="' + newSum + '" data-sign="' + newSign + '" data-delay="' + newDelay + '" data-increment="' + newIncrement + '">' + 
                                '<div class="list-group-item-info-title">' + 
                                    '<span class="number-title">' + newTitle + '</span>' + 
                                '</div>' + 
                                '<div class="list-group-item-info-caption">' + 
                                    '<span class="number-text">' + newText + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-new-number"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-number"><span class="fa fa-pencil"></span></a>' + 
                        '</div>' + 
                    '<li>';
                $('#numbers-list .sortcode-modal-empty').remove();
                $('#numbers-list').append(newNumber);

                $('.shortcode-modal-new-container').hide();
                $('#sh-new-number-sum').val('1');
                $('#sh-new-number-sign').val('');
                $('#sh-new-number-delay').val('1');
                $('#sh-new-number-increment').val('1');
                $('#sh-new-number-title').val('');
                $('#sh-new-number-text').val('');
                $('#add-number').show();

                $('.edit-new-number').unbind('click').on('click', function(event) {
                    editNumber($(this));
                });
                $('.del-new-number').unbind('click').on('click', function(event) {
                    delNumber($(this));
                });
            });

            $('#numbers-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    numbers = [];

                    $('#numbers-list .list-group-item').each(function() {
                        var newSum       = $(this).find('.list-group-item-info').attr('data-sum');
                        var newSign      = $(this).find('.list-group-item-info').attr('data-sign');
                        var newDelay     = $(this).find('.list-group-item-info').attr('data-delay');
                        var newIncrement = $(this).find('.list-group-item-info').attr('data-increment');
                        var newTitle     = $(this).find('.number-title').text();
                        var newText      = $(this).find('.number-text').text();

                        numbers.push({
                            'sum'      : newSum,
                            'sign'     : newSign,
                            'delay'    : newDelay,
                            'increment': newIncrement,
                            'title'    : newTitle,
                            'text'     : newText
                        });
                    });
                }
            }).disableSelection();

            $('.edit-number').on('click', function(event) {
                editNumber($(this));
            });

            function editNumber(btn) {
                var editSum       = btn.parent().find('.list-group-item-info').attr('data-sum');
                var editSign      = btn.parent().find('.list-group-item-info').attr('data-sign');
                var editDelay     = btn.parent().find('.list-group-item-info').attr('data-delay');
                var editIncrement = btn.parent().find('.list-group-item-info').attr('data-increment');
                var editTitle     = btn.parent().find('.number-title').text();
                var editText      = btn.parent().find('.number-text').text();

                var editNumberForm = 
                    '<div class="sh-edit-number">' + 
                        '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.edit_number_header + '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-number-sum">' + sh_vars.number_sum_label + '</label>' + 
                                    '<input type="number" min="1" id="sh-edit-number-sum" class="form-control" placeholder="1" value="' + editSum + '">' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-number-sign">' + sh_vars.number_sign_label + '</label>' + 
                                    '<input type="text" id="sh-edit-number-sign" class="form-control" placeholder="' + sh_vars.number_sign_placeholder + '" value="' + editSign + '">' + 
                                '</div>' + 
                            '</div>'+ 
                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-number-delay">' + sh_vars.number_delay_label + '</label>' + 
                                    '<input type="number" min="1" id="sh-edit-number-delay" class="form-control" placeholder="1" value="' + editDelay + '">' + 
                                '</div>' + 
                            '</div>'+ 
                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-number-increment">' + sh_vars.number_increment_label + '</label>' + 
                                    '<input type="number" min="1" id="sh-edit-number-increment" class="form-control" placeholder="1" value="' + editIncrement + '">' + 
                                '</div>' + 
                            '</div>'+ 
                        '</div>'+ 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-number-title">' + sh_vars.number_title_label + '</label>' + 
                                    '<input type="text" id="sh-edit-number-title" class="form-control" placeholder="' + sh_vars.number_title_placeholder + '" value="' + editTitle + '">' + 
                                '</div>'+ 
                            '</div>'+ 
                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-number-text">' + sh_vars.number_text_label + '</label>' + 
                                    '<input type="text" id="sh-edit-number-text" class="form-control" placeholder="' + sh_vars.number_text_placeholder + '" value="' + editText + '">' + 
                                '</div>'+ 
                            '</div>'+ 
                        '</div>'+ 
                        '<button type="button" id="ok-edit-number" class="button media-button button-primary">' + sh_vars.ok_edit_number_btn + '</button>' + 
                        '<button type="button" id="cancel-edit-number" class="button media-button button-default">' + sh_vars.cancel_number_btn + '</button>' + 
                    '</div>';

                btn.parent().hide();
                btn.parent().parent().append(editNumberForm);

                $('#numbers-list').sortable('disable');
                $('#numbers-list .list-group-item').css('cursor', 'auto');
                $('.edit-number').hide();
                $('.del-number').hide();
                $('.edit-new-number').hide();
                $('.del-new-number').hide();
                $('#add-number').hide();
                $('.shortcode-modal-new-container').hide();

                $('#ok-edit-number').on('click', function(event) {
                    numbers = [];

                    var eSum       = $(this).parent().find('#sh-edit-number-sum').val();
                    var eSign      = $(this).parent().find('#sh-edit-number-sign').val();
                    var eDelay     = $(this).parent().find('#sh-edit-number-delay').val();
                    var eIncrement = $(this).parent().find('#sh-edit-number-increment').val();
                    var eTitle     = $(this).parent().find('#sh-edit-number-title').val();
                    var eText      = $(this).parent().find('#sh-edit-number-text').val();
                    var listElem   = $(this).parent().parent().find('.list-group-item-elem');

                    listElem.find('.list-group-item-img').html('<span>' + eSum + eSign + '</span>');
                    listElem.find('.number-title').text(eTitle);
                    listElem.find('.number-text').text(eText);
                    listElem.find('.list-group-item-info').attr('data-sum', eSum).attr('data-sign', eSign).attr('data-delay', eDelay).attr('data-increment', eIncrement);

                    $(this).parent().remove();
                    listElem.show();

                    $('#numbers-list').sortable('enable');
                    $('#numbers-list .list-group-item').css('cursor', 'move');
                    $('.edit-number').show();
                    $('.del-number').show();
                    $('.edit-new-number').show();
                    $('.del-new-number').show();
                    $('#add-number').show();

                    $('#numbers-list .list-group-item').each(function() {
                        var newSum       = $(this).find('.list-group-item-info').attr('data-sum');
                        var newSign      = $(this).find('.list-group-item-info').attr('data-sign');
                        var newDelay     = $(this).find('.list-group-item-info').attr('data-delay');
                        var newIncrement = $(this).find('.list-group-item-info').attr('data-increment');
                        var newTitle     = $(this).find('.number-title').text();
                        var newText      = $(this).find('.number-text').text();

                        numbers.push({
                            'sum'      : newSum,
                            'sign'     : newSign,
                            'delay'    : newDelay,
                            'increment': newIncrement,
                            'title'    : newTitle,
                            'text'     : newText
                        });
                    });
                });

                $('#cancel-edit-number').on('click', function(event) {
                    var listElem = $(this).parent().parent().find('.list-group-item-elem');

                    $(this).parent().remove();
                    listElem.show();
                    $('#numbers-list').sortable('enable');
                    $('#numbers-list .list-group-item').css('cursor', 'move');
                    $('.edit-number').show();
                    $('.del-number').show();
                    $('.edit-new-number').show();
                    $('.del-new-number').show();
                    $('#add-number').show();
                });
            }

            $('.del-number').on('click', function(event) {
                delNumber($(this));
            });

            function delNumber(btn) {
                numbers = [];

                btn.parent().parent().remove();
                if ($('#numbers-list .list-group-item').length > 0) {
                    $('#numbers-list .list-group-item').each(function() {
                        var newSum       = $(this).find('.list-group-item-info').attr('data-sum');
                        var newSign      = $(this).find('.list-group-item-info').attr('data-sign');
                        var newDelay     = $(this).find('.list-group-item-info').attr('data-delay');
                        var newIncrement = $(this).find('.list-group-item-info').attr('data-increment');
                        var newTitle     = $(this).find('.number-title').text();
                        var newText      = $(this).find('.number-text').text();

                        numbers.push({
                            'sum'      : newSum,
                            'sign'     : newSign,
                            'delay'    : newDelay,
                            'increment': newIncrement,
                            'title'    : newTitle,
                            'text'     : newText
                        });
                    });
                } else {
                    $('#numbers-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>');
                }
            }
        }

        // Open modal
        editor.addCommand('res_numbers_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_numbers_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_numbers_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_numbers', {
            image: url + '/../images/numbers-btn.png',
            tooltip: sh_vars.numbers_title,
            onclick: function() {
                editor.execCommand('res_numbers_panel_popup', '', {
                    data_content: '{ "title": "", "subtitle": "", "image": "", "image_src": "", "margin": "no", "numbers": [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_numbers_shortcode', {
            text: sh_vars.remove_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-no',
            cmd : 'res_numbers_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_numbers_shortcode', {
            text: sh_vars.edit_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-edit',
            cmd : 'res_numbers_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_numbers_shortcode',
                    'edit_numbers_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_numbers') > -1) {
                e.toolbar = toolbar;
            }
        });

        // Replace shortcode with an image placeholder
        editor.on('BeforeSetcontent', function(e) { 
            e.content = replaceShortcodes(e.content);
        });

        // Replace image placeholder with shortcode
        editor.on('GetContent', function(e) {
            e.content = restoreShortcodes(e.content);
        });

        // Open popup when double click on placeholder
        editor.on('DblClick', function(e) {
            var cls = e.target.className.indexOf('wp-res_numbers');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_numbers') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_numbers_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });
})(jQuery);