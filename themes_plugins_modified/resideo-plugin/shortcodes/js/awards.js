(function($) {
    "use strict";

    tinymce.PluginManager.add('res_awards', function(editor, url) {
        var shortcodeTag = 'res_awards';
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

            return '<img src="' + placeholder + '" class="mceItem res-awards-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_awards([^\]]*)\]([^\]]*)\[\/res_awards\]/g, function(all, attr, con) {
                return getHTML('wp-res_awards', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-awards-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_awards_panel_popup', '', {
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
                                    '<h1>' + sh_vars.awards_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-awards-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-awards-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-awards-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-awards-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-awards-text">' + sh_vars.text_label + '</label>' + 
                                                    '<textarea id="sh-awards-text" class="form-control" rows="3" placeholder="' + sh_vars.text_placeholder + '">' + getObjectProperty(short, "text") + '</textarea>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-awards-margin">' + sh_vars.margin_label + '</label>';
            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }
            modalContent += 
                                                    '<select class="form-control" id="sh-awards-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.awards_list + '</div>' + 
                                        '<ul class="list-group" id="awards-list">';

            var awards = getObjectProperty(short, "awards");

            if ($.isArray(awards)) {
                if (awards.length > 0) {
                    $.each(awards, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem">' + 
                                                    '<div class="list-group-item-img" data-src="' + elem.src + '" data-value="' + elem.value + '">' + 
                                                        '<div class="list-group-item-img-container" style="background-image: url(' + elem.src + ');"></div>' + 
                                                    '</div>' + 
                                                    '<div class="list-group-item-info">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="award-title">' + elem.title + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-award"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<a href="javascript:void(0);" class="pull-right edit-btn edit-award"><span class="fa fa-pencil"></span></a>' + 
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
                awards = [];
            }

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-award" class="button media-button button-default">' + sh_vars.add_award_btn + '</button>' + 
                                        '<div class="shortcode-modal-new-container" style="display: none;">' + 
                                            '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.new_award_header + '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<input type="hidden" id="sh-new-award-img" data-src="">' + 
                                                        '<div class="sh-new-award-img-container">' + 
                                                            '<div class="sh-new-award-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>' + 
                                                            '<div class="sh-new-award-add-btns">' + 
                                                                '<div class="sh-new-award-add-img">' + sh_vars.award_add_img + '</div>' +  
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>' + 
                                                '</div>'+ 
                                                '<div class="col-xs-12 col-md-9 rtl-pull-right">' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-award-title">' + sh_vars.award_title_label + '</label>' + 
                                                                '<input type="text" id="sh-new-award-title" class="form-control" placeholder="' + sh_vars.award_title_placeholder + '">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<button type="button" id="ok-award" class="button media-button button-primary">' + sh_vars.ok_award_btn + '</button>' + 
                                            '<button type="button" id="cancel-award" class="button media-button button-default">' + sh_vars.cancel_award_btn + '</button>' + 
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
                    'title'   : $('#sh-awards-title').val(),
                    'subtitle': $('#sh-awards-subtitle').val(),
                    'text'    : $('#sh-awards-text').val(),
                    'margin'  : $('#sh-awards-margin').val(),
                    'awards'  : awards
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('.sh-new-award-add-img').on('click', function(event) {
                openMediaLibrary($(this));
            });

            $('#add-award').on('click', function(event) {
                $(this).hide();
                $('.shortcode-modal-new-container').show();
            });

            $('#cancel-award').on('click', function(event) {
                $('.shortcode-modal-new-container').hide();
                $('.sh-new-award-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-award-img').val('').attr('data-src', '');
                $('#sh-new-award-title').val('');
                $('#add-award').show();
            });

            $('#ok-award').on('click', function(event) {
                var newImgSrc   = $('#sh-new-award-img').attr('data-src');
                var newImgValue = $('#sh-new-award-img').val();
                var newTitle    = $('#sh-new-award-title').val();

                awards.push({
                    'src'     : newImgSrc,
                    'value'   : newImgValue,
                    'title'   : newTitle
                });

                var newAward = 
                    '<li class="list-group-item">' + 
                        '<div class="list-group-item-elem">' + 
                            '<div class="list-group-item-img" data-src="' + newImgSrc + '" data-value="' + newImgValue + '">' + 
                                '<div class="list-group-item-img-container" style="background-image: url(' + newImgSrc + ');"></div>' + 
                            '</div>' + 
                            '<div class="list-group-item-info">' + 
                                '<div class="list-group-item-info-title">' + 
                                    '<span class="award-title">' + newTitle + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-new-award"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-award"><span class="fa fa-pencil"></span></a>' + 
                        '</div>' + 
                    '<li>';
                $('#awards-list .sortcode-modal-empty').remove();
                $('#awards-list').append(newAward);

                $('.shortcode-modal-new-container').hide();
                $('.sh-new-award-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-award-img').val('').attr('data-src', '');
                $('#sh-new-award-title').val('');
                $('#add-award').show();

                $('.edit-new-award').unbind('click').on('click', function(event) {
                    editAward($(this));
                });
                $('.del-new-award').unbind('click').on('click', function(event) {
                    delAward($(this));
                });
            });

            $('#awards-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    awards = [];

                    $('#awards-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newTitle    = $(this).find('.award-title').text();

                        awards.push({
                            'src'  : newImgSrc,
                            'value': newImgValue,
                            'title': newTitle
                        });
                    });
                }
            }).disableSelection();

            $('.edit-award').on('click', function(event) {
                editAward($(this));
            });

            function editAward(btn) {
                var editImgSrc   = btn.parent().find('.list-group-item-img').attr('data-src');
                var editImgValue = btn.parent().find('.list-group-item-img').attr('data-value');
                var editTitle    = btn.parent().find('.award-title').text();

                var editAwardForm = 
                    '<div class="sh-edit-award">' + 
                        '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.edit_award_header + '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<input type="hidden" id="sh-edit-award-img" data-src="' + editImgSrc + '" value="' + editImgValue + '">' + 
                                    '<div class="sh-new-award-img-container">';
                if (editImgSrc != '') {
                    editAwardForm += 
                                        '<div class="sh-new-award-img-placeholder has-image" style="background-image: url(' + editImgSrc + ');"></div>';
                } else {
                    editAwardForm += 
                                        '<div class="sh-new-award-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>';
                }
                editAwardForm += 
                                        '<div class="sh-new-award-add-btns">' + 
                                            '<div class="sh-new-award-add-img">' + sh_vars.award_add_img + '</div>' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                            '<div class="col-xs-12 col-md-9 rtl-pull-right">' + 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-award-title">' + sh_vars.award_title_label + '</label>' + 
                                            '<input type="text" id="sh-edit-award-title" class="form-control" placeholder="' + sh_vars.award_title_placeholder + '" value="' + editTitle + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                        '</div>'+ 
                        '<button type="button" id="ok-edit-award" class="button media-button button-primary">' + sh_vars.ok_edit_award_btn + '</button>' + 
                        '<button type="button" id="cancel-edit-award" class="button media-button button-default">' + sh_vars.cancel_award_btn + '</button>' + 
                    '</div>';

                btn.parent().hide();
                btn.parent().parent().append(editAwardForm);

                $('#awards-list').sortable('disable');
                $('#awards-list .list-group-item').css('cursor', 'auto');
                $('.edit-award').hide();
                $('.del-award').hide();
                $('.edit-new-award').hide();
                $('.del-new-award').hide();
                $('#add-award').hide();
                $('.shortcode-modal-new-container').hide();

                $('.sh-new-award-add-img').on('click', function(event) {
                    openMediaLibrary($(this));
                });

                $('#ok-edit-award').on('click', function(event) {
                    awards = [];

                    var eImgSrc   = $(this).parent().find('#sh-edit-award-img').attr('data-src');
                    var eImgValue = $(this).parent().find('#sh-edit-award-img').val();
                    var eTitle    = $(this).parent().find('#sh-edit-award-title').val();
                    var listElem  = $(this).parent().parent().find('.list-group-item-elem');

                    listElem.find('.list-group-item-img').attr('data-src', eImgSrc).attr('data-value', eImgValue);
                    listElem.find('.list-group-item-img').html('<div class="list-group-item-img-container" style="background-image: url(' + eImgSrc + ');"></div>');
                    listElem.find('.award-title').text(eTitle);

                    $(this).parent().remove();
                    listElem.show();

                    $('#awards-list').sortable('enable');
                    $('#awards-list .list-group-item').css('cursor', 'move');
                    $('.edit-award').show();
                    $('.del-award').show();
                    $('.edit-new-award').show();
                    $('.del-new-award').show();
                    $('#add-award').show();

                    $('#awards-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newTitle    = $(this).find('.award-title').text();

                        awards.push({
                            'src'  : newImgSrc,
                            'value': newImgValue,
                            'title': newTitle
                        });
                    });
                });

                $('#cancel-edit-award').on('click', function(event) {
                    var listElem = $(this).parent().parent().find('.list-group-item-elem');

                    $(this).parent().remove();
                    listElem.show();
                    $('#awards-list').sortable('enable');
                    $('#awards-list .list-group-item').css('cursor', 'move');
                    $('.edit-award').show();
                    $('.del-award').show();
                    $('.edit-new-award').show();
                    $('.del-new-award').show();
                    $('#add-award').show();
                });
            }

            $('.del-award').on('click', function(event) {
                delAward($(this));
            });

            function delAward(btn) {
                awards = [];

                btn.parent().parent().remove();
                if ($('#awards-list .list-group-item').length > 0) {
                    $('#awards-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newTitle    = $(this).find('.award-title').text();

                        awards.push({
                            'src'  : newImgSrc,
                            'value': newImgValue,
                            'title': newTitle
                        });
                    });
                } else {
                    $('#awards-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>');
                }
            }
        }

        // Open Media Library
        function openMediaLibrary(btn) {
            var frame = wp.media({
                title: sh_vars.award_image,
                button: {
                    text: sh_vars.award_insert_image
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    btn.parent().parent().parent().children('input').val(value.id).attr('data-src', value.url);
                    btn.parent().parent().find('.sh-new-award-img-placeholder').css('background-image', 'url(' + value.url + ')').addClass('has-image').empty();
                });
            });

            frame.open();
        }

        // Open modal
        editor.addCommand('res_awards_panel_popup', function(ui, v) {
            var data_content = '';

            if(v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_awards_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_awards_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_awards', {
            image: url + '/../images/awards-btn.png',
            tooltip: sh_vars.awards_title,
            onclick: function() {
                editor.execCommand('res_awards_panel_popup', '', {
                    data_content: '{ "title": "", "subtitle": "", "margin": "no", "text": "", "awards": [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_awards_shortcode', {
            text: sh_vars.remove_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-no',
            cmd : 'res_awards_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_awards_shortcode', {
            text: sh_vars.edit_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-edit',
            cmd : 'res_awards_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_awards_shortcode',
                    'edit_awards_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_awards') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_awards');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_awards') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_awards_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });
})(jQuery);