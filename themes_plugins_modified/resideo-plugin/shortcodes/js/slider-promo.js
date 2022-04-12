(function($) {
    "use strict";

    tinymce.PluginManager.add('res_slider_promo', function(editor, url) {
        var shortcodeTag = 'res_slider_promo';
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

            return '<img src="' + placeholder + '" class="mceItem res-slider-promo-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_slider_promo([^\]]*)\]([^\]]*)\[\/res_slider_promo\]/g, function(all, attr, con) {
                return getHTML('wp-res_slider_promo', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-slider-promo-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_slider_promo_panel_popup', '', {
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
                                    '<h1>' + sh_vars.slider_promo_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-promo-slider-position">' + sh_vars.caption_position_label + '</label>' + 
                                                    '<select id="sh-promo-slider-position" class="form-control">';
            var position = [
                { id: 'topLeft', label: sh_vars.top_left_label }, 
                { id: 'topRight', label: sh_vars.top_right_label }, 
                { id: 'centerLeft', label: sh_vars.center_left_label }, 
                { id: 'center', label: sh_vars.center_label }, 
                { id: 'centerRight', label: sh_vars.center_right_label }, 
                { id: 'bottomLeft', label: sh_vars.bottom_left_label }, 
                { id: 'bottomRight', label: sh_vars.bottom_right_label }
            ];
            $.each(position, function(index, value) {
                modalContent += 
                                                        '<option value="' + value.id + '"';
                if (getObjectProperty(short, "position") == value.id) {
                    modalContent += 
                                                            ' selected="selected"';
                }
                modalContent += 
                                                        '>' + value.label + '</option>';
            });
            modalContent +=
                                                    '</select>' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-promo-slider-margin">' + sh_vars.margin_label + '</label>';
            var margin_is_no = '';
            var margin_is_yes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                margin_is_no = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                margin_is_yes = ' selected="selected"';
            }
            modalContent += 
                                                    '<select class="form-control" id="sh-promo-slider-margin">' + 
                                                        '<option value="no"' + margin_is_no + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + margin_is_yes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-promo-slider-ctas-color" style="display:block;margin-bottom:2px;">' + sh_vars.cta_buttons_color + '</label>' + 
                                                    '<input type="text" id="sh-promo-slider-ctas-color" class="color-field" value="' + getObjectProperty(short, "ctas_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-promo-slider-interval" style="display:block;margin-bottom:2px;">' + sh_vars.interval_label + '</label>' + 
                                                    '<input type="text" id="sh-promo-slider-interval" value="' + getObjectProperty(short, "interval") + '" placeholder="0"> ' + sh_vars.seconds_label + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.promo_slides + '</div>' + 
                                        '<ul class="list-group" id="promo-slider-list">';

            var slides = getObjectProperty(short, "slides");

            if ($.isArray(slides)) {
                if (slides.length > 0) {
                    $.each(slides, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem">' + 
                                                    '<div class="list-group-item-img" data-src="' + elem.src + '" data-value="' + elem.value + '">' + 
                                                        '<div class="list-group-item-img-container" style="background-image: url(' + elem.src + ');"></div>' + 
                                                    '</div>' + 
                                                    '<div class="list-group-item-info" data-ctatext="' + elem.cta_text + '" data-ctalink="' + elem.cta_link + '">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="promo-slide-title">' + elem.title + '</span>' + 
                                                        '</div>' + 
                                                        '<div class="list-group-item-info-caption">' + 
                                                            '<span class="promo-slide-text">' + elem.text + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-promo-slide"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<a href="javascript:void(0);" class="pull-right edit-btn edit-promo-slide"><span class="fa fa-pencil"></span></a>' + 
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
                slides = [];
            }

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-promo-slide" class="button media-button button-default">' + sh_vars.add_promo_slide_btn + '</button>' + 
                                        '<div class="shortcode-modal-new-container" style="display: none;">' + 
                                            '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.new_promo_slide_header + '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<input type="hidden" id="sh-new-promo-slide-img" data-src="">' + 
                                                        '<div class="sh-new-promo-slide-img-container">' + 
                                                            '<div class="sh-new-promo-slide-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>' + 
                                                            '<div class="sh-new-promo-slide-add-btns">' + 
                                                                '<div class="sh-new-promo-slide-add-img">' + sh_vars.promo_slide_add_img + '</div>' +  
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>' + 
                                                '</div>'+ 
                                                '<div class="col-xs-12 col-md-9 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-promo-slide-title">' + sh_vars.promo_slide_title_label + '</label>' + 
                                                        '<input type="text" id="sh-new-promo-slide-title" class="form-control" placeholder="' + sh_vars.promo_slide_title_placeholder + '">' + 
                                                    '</div>'+ 
                                                    '<div class="form-group">' + 
                                                        '<label for="sh-new-promo-slide-text">' + sh_vars.promo_slide_text_label + '</label>' + 
                                                        '<textarea id="sh-new-promo-slide-text" class="form-control" rows="3" placeholder="' + sh_vars.promo_slide_text_placeholder + '"></textarea>' + 
                                                    '</div>'+ 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-promo-slide-cta-text">' + sh_vars.cta_text_label + '</label>' + 
                                                                '<input type="text" id="sh-new-promo-slide-cta-text" class="form-control" placeholder="' + sh_vars.cta_text_placeholder + '">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                        '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-promo-slide-cta-link">' + sh_vars.cta_link_label + '</label>' + 
                                                                '<input type="text" id="sh-new-promo-slide-cta-link" class="form-control" placeholder="' + sh_vars.cta_link_placeholder + '">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<button type="button" id="ok-promo-slide" class="button media-button button-primary">' + sh_vars.ok_promo_slide_btn + '</button>' + 
                                            '<button type="button" id="cancel-promo-slide" class="button media-button button-default">' + sh_vars.cancel_promo_slide_btn + '</button>' + 
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

            $('.color-field').wpColorPicker({
                defaultColor: '#333333',
            });

            $('#shortcode-modal #insert-button').on('click', function(e) {
                var shortVal = {
                    'position'  : $('#sh-promo-slider-position').val(),
                    'margin'    : $('#sh-promo-slider-margin').val(),
                    'ctas_color': $('#sh-promo-slider-ctas-color').val(),
                    'interval'  : $('#sh-promo-slider-interval').val(),
                    'slides'    : slides
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('.sh-new-promo-slide-add-img').on('click', function(event) {
                openMediaLibrary($(this));
            });

            $('#add-promo-slide').on('click', function(event) {
                $(this).hide();
                $('.shortcode-modal-new-container').show();
            });

            $('#cancel-promo-slide').on('click', function(event) {
                $('.shortcode-modal-new-container').hide();
                $('.sh-new-promo-slide-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-promo-slide-img').val('').attr('data-src', '');
                $('#sh-new-promo-slide-title').val('');
                $('#sh-new-promo-slide-text').val('');
                $('#sh-new-promo-slide-cta-text').val('');
                $('#sh-new-promo-slide-cta-link').val('');
                $('#add-promo-slide').show();
            });

            $('#ok-promo-slide').on('click', function(event) {
                var newImgSrc   = $('#sh-new-promo-slide-img').attr('data-src');
                var newImgValue = $('#sh-new-promo-slide-img').val();
                var newTitle    = $('#sh-new-promo-slide-title').val();
                var newText     = $('#sh-new-promo-slide-text').val();
                var newCTAText  = $('#sh-new-promo-slide-cta-text').val();
                var newCTALink  = $('#sh-new-promo-slide-cta-link').val();

                slides.push({
                    'src'     : newImgSrc,
                    'value'   : newImgValue,
                    'title'   : newTitle,
                    'text'    : newText,
                    'cta_text': newCTAText,
                    'cta_link': newCTALink
                });

                var newSlide = 
                    '<li class="list-group-item">' + 
                        '<div class="list-group-item-elem">' + 
                            '<div class="list-group-item-img" data-src="' + newImgSrc + '" data-value="' + newImgValue + '">' + 
                                '<div class="list-group-item-img-container" style="background-image: url(' + newImgSrc + ');"></div>' + 
                            '</div>' + 
                            '<div class="list-group-item-info" data-ctatext="' + newCTAText + '" data-ctalink="' + newCTALink + '">' + 
                                '<div class="list-group-item-info-title">' + 
                                    '<span class="promo-slide-title">' + newTitle + '</span>' + 
                                '</div>' + 
                                '<div class="list-group-item-info-caption">' + 
                                    '<span class="promo-slide-text">' + newText + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-new-promo-slide"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-promo-slide"><span class="fa fa-pencil"></span></a>' + 
                        '</div>' + 
                    '<li>';
                $('#promo-slider-list .sortcode-modal-empty').remove();
                $('#promo-slider-list').append(newSlide);

                $('.shortcode-modal-new-container').hide();
                $('.sh-new-promo-slide-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-promo-slide-img').val('').attr('data-src', '');
                $('#sh-new-promo-slide-title').val('');
                $('#sh-new-promo-slide-text').val('');
                $('#sh-new-promo-slide-cta-text').val('');
                $('#sh-new-promo-slide-cta-link').val('');
                $('#add-promo-slide').show();

                $('.edit-new-promo-slide').unbind('click').on('click', function(event) {
                    editSlide($(this));
                });
                $('.del-new-promo-slide').unbind('click').on('click', function(event) {
                    delSlide($(this));
                });
            });

            $('#promo-slider-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    slides = [];

                    $('#promo-slider-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newTitle    = $(this).find('.promo-slide-title').text();
                        var newText     = $(this).find('.promo-slide-text').text();
                        var newCTAText  = $(this).find('.list-group-item-info').attr('data-ctatext');
                        var newCTALink  = $(this).find('.list-group-item-info').attr('data-ctalink');

                        slides.push({
                            'src'     : newImgSrc,
                            'value'   : newImgValue,
                            'title'   : newTitle,
                            'text'    : newText,
                            'cta_text': newCTAText,
                            'cta_link': newCTALink
                        });
                    });
                }
            }).disableSelection();

            $('.edit-promo-slide').on('click', function(event) {
                editSlide($(this));
            });

            function editSlide(btn) {
                var editImgSrc   = btn.parent().find('.list-group-item-img').attr('data-src');
                var editImgValue = btn.parent().find('.list-group-item-img').attr('data-value');
                var editTitle    = btn.parent().find('.promo-slide-title').text();
                var editText     = btn.parent().find('.promo-slide-text').text();
                var editCTAText  = btn.parent().find('.list-group-item-info').attr('data-ctatext');
                var editCTALink  = btn.parent().find('.list-group-item-info').attr('data-ctalink');

                var editSlideForm = 
                    '<div class="sh-edit-promo-slide">' + 
                        '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.edit_slide_header + '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<input type="hidden" id="sh-edit-promo-slide-img" data-src="' + editImgSrc + '" value="' + editImgValue + '">' + 
                                    '<div class="sh-new-promo-slide-img-container">';
                if (editImgSrc != '') {
                    editSlideForm += 
                                        '<div class="sh-new-promo-slide-img-placeholder has-image" style="background-image: url(' + editImgSrc + ');"></div>';
                } else {
                    editSlideForm += 
                                        '<div class="sh-new-promo-slide-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>';
                }
                editSlideForm += 
                                        '<div class="sh-new-promo-slide-add-btns">' + 
                                            '<div class="sh-new-promo-slide-add-img">' + sh_vars.promo_slide_add_img + '</div>' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                            '<div class="col-xs-12 col-md-9 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-promo-slide-title">' + sh_vars.promo_slide_title_label + '</label>' + 
                                    '<input type="text" id="sh-edit-promo-slide-title" class="form-control" placeholder="' + sh_vars.promo_slide_title_placeholder + '" value="' + editTitle + '">' + 
                                '</div>'+ 
                                '<div class="form-group">' + 
                                    '<label for="sh-edit-promo-slide-text">' + sh_vars.promo_slide_text_label + '</label>' + 
                                    '<textarea id="sh-edit-promo-slide-text" class="form-control" rows="3" placeholder="' + sh_vars.promo_slide_text_placeholder + '">' + editText + '</textarea>' + 
                                '</div>'+ 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-promo-slide-cta-text">' + sh_vars.cta_text_label + '</label>' + 
                                            '<input type="text" id="sh-edit-promo-slide-cta-text" class="form-control" placeholder="' + sh_vars.cta_text_placeholder + '" value="' + editCTAText + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-promo-slide-cta-link">' + sh_vars.cta_link_label + '</label>' + 
                                            '<input type="text" id="sh-edit-promo-slide-cta-link" class="form-control" placeholder="' + sh_vars.cta_link_placeholder + '" value="' + editCTALink + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                        '</div>'+ 
                        '<button type="button" id="ok-edit-promo-slide" class="button media-button button-primary">' + sh_vars.ok_edit_promo_slide_btn + '</button>' + 
                        '<button type="button" id="cancel-edit-promo-slide" class="button media-button button-default">' + sh_vars.cancel_edit_promo_slide_btn + '</button>' + 
                    '</div>';

                btn.parent().hide();
                btn.parent().parent().append(editSlideForm);

                $('#promo-slider-list').sortable('disable');
                $('#promo-slider-list .list-group-item').css('cursor', 'auto');
                $('.edit-promo-slide').hide();
                $('.del-promo-slide').hide();
                $('.edit-new-promo-slide').hide();
                $('.del-new-promo-slide').hide();
                $('#add-promo-slide').hide();
                $('.shortcode-modal-new-container').hide();

                $('.sh-new-promo-slide-add-img').on('click', function(event) {
                    openMediaLibrary($(this));
                });

                $('#ok-edit-promo-slide').on('click', function(event) {
                    slides = [];

                    var eImgSrc   = $(this).parent().find('#sh-edit-promo-slide-img').attr('data-src');
                    var eImgValue = $(this).parent().find('#sh-edit-promo-slide-img').val();
                    var eTitle    = $(this).parent().find('#sh-edit-promo-slide-title').val();
                    var eText     = $(this).parent().find('#sh-edit-promo-slide-text').val();
                    var eCTAText  = $(this).parent().find('#sh-edit-promo-slide-cta-text').val();
                    var eCTALink  = $(this).parent().find('#sh-edit-promo-slide-cta-link').val();
                    var listElem  = $(this).parent().parent().find('.list-group-item-elem');

                    listElem.find('.list-group-item-img').attr('data-src', eImgSrc).attr('data-value', eImgValue);
                    listElem.find('.list-group-item-img').html('<div class="list-group-item-img-container" style="background-image: url(' + eImgSrc + ');"></div>');
                    listElem.find('.promo-slide-title').text(eTitle);
                    listElem.find('.promo-slide-text').text(eText);
                    listElem.find('.list-group-item-info').attr('data-ctatext', eCTAText).attr('data-ctalink', eCTALink);

                    $(this).parent().remove();
                    listElem.show();

                    $('#promo-slider-list').sortable('enable');
                    $('#promo-slider-list .list-group-item').css('cursor', 'move');
                    $('.edit-promo-slide').show();
                    $('.del-promo-slide').show();
                    $('.edit-new-promo-slide').show();
                    $('.del-new-promo-slide').show();
                    $('#add-promo-slide').show();

                    $('#promo-slider-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newTitle    = $(this).find('.promo-slide-title').text();
                        var newText     = $(this).find('.promo-slide-text').text();
                        var newCTAText  = $(this).find('.list-group-item-info').attr('data-ctatext');
                        var newCTALink  = $(this).find('.list-group-item-info').attr('data-ctalink');

                        slides.push({
                            'src'     : newImgSrc,
                            'value'   : newImgValue,
                            'title'   : newTitle,
                            'text'    : newText,
                            'cta_text': newCTAText,
                            'cta_link': newCTALink
                        });
                    });
                });

                $('#cancel-edit-promo-slide').on('click', function(event) {
                    var listElem = $(this).parent().parent().find('.list-group-item-elem');

                    $(this).parent().remove();
                    listElem.show();
                    $('#promo-slider-list').sortable('enable');
                    $('#promo-slider-list .list-group-item').css('cursor', 'move');
                    $('.edit-promo-slide').show();
                    $('.del-promo-slide').show();
                    $('.edit-new-promo-slide').show();
                    $('.del-new-promo-slide').show();
                    $('#add-promo-slide').show();
                });
            }

            $('.del-promo-slide').on('click', function(event) {
                delSlide($(this));
            });

            function delSlide(btn) {
                slides = [];

                btn.parent().parent().remove();
                if ($('#promo-slider-list .list-group-item').length > 0) {
                    $('#promo-slider-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newTitle    = $(this).find('.promo-slide-title').text();
                        var newText     = $(this).find('.promo-slide-text').text();
                        var newCTAText  = $(this).find('.list-group-item-info').attr('data-ctatext');
                        var newCTALink  = $(this).find('.list-group-item-info').attr('data-ctalink');

                        slides.push({
                            'src'     : newImgSrc,
                            'value'   : newImgValue,
                            'title'   : newTitle,
                            'text'    : newText,
                            'cta_text': newCTAText,
                            'cta_link': newCTALink
                        });
                    });
                } else {
                    $('#promo-slider-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>');
                }
            }
        }

        // Open Media Library
        function openMediaLibrary(btn) {
            var frame = wp.media({
                title: sh_vars.slide_image,
                button: {
                    text: sh_vars.slide_insert_image
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    btn.parent().parent().parent().children('input').val(value.id).attr('data-src', value.url);
                    btn.parent().parent().find('.sh-new-promo-slide-img-placeholder').css('background-image', 'url(' + value.url + ')').addClass('has-image').empty();
                });
            });

            frame.open();
        }

        // Open modal
        editor.addCommand('res_slider_promo_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_slider_promo_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_slider_promo_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_slider_promo', {
            image: url + '/../images/promo-slider-btn.png',
            tooltip: sh_vars.slider_promo_title,
            onclick: function() {
                editor.execCommand('res_slider_promo_panel_popup', '', {
                    data_content: '{ "position": "topLeft", "margin": "no", "ctas_color": "#333333", "interval": "0", "slides": [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_slider_promo_shortcode', {
            text: sh_vars.remove_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-no',
            cmd : 'res_slider_promo_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_slider_promo_shortcode', {
            text: sh_vars.edit_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-edit',
            cmd : 'res_slider_promo_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_slider_promo_shortcode',
                    'edit_slider_promo_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_slider_promo') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_slider_promo');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_slider_promo') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_slider_promo_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });
})(jQuery);