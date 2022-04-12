(function($) {
    "use strict";

    tinymce.PluginManager.add('res_services', function(editor, url) {
        var shortcodeTag = 'res_services';
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

            return '<img src="' + placeholder + '" class="mceItem res-services-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_services([^\]]*)\]([^\]]*)\[\/res_services\]/g, function(all, attr, con) {
                return getHTML('wp-res_services', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-services-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_services_panel_popup', '', {
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
                                    '<h1>' + sh_vars.services_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-services-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-services-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-services-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-services-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label>' + sh_vars.layout_label + '</label>' + 
                                                    '<div class="layout-radio-container" id="sh-services-layout">' + 
                                                        '<div class="layout-radio layout-radio-services-1"><label class="';
            var cta_style = '';
            var img_style = '';
            var display_style = '';
            if (getObjectProperty(short, "layout") == '1') {
                cta_style = 'display: none;';
                display_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="1"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-services-2"><label class="';
            if (getObjectProperty(short, "layout") == '2') {
                display_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="2"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-services-3"><label class="';
            if (getObjectProperty(short, "layout") == '3') {
                display_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="3"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-services-4"><label class="';
            if (getObjectProperty(short, "layout") == '4') {
                cta_style = 'display: none;';
                img_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="4"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-services-5"><label class="';
            if (getObjectProperty(short, "layout") == '5') {
                display_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="5"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-services-6"><label class="';
            if (getObjectProperty(short, "layout") == '6') {
                cta_style = 'display: none;';
                img_style = 'display: none;';
                display_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="6"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-services-7"><label class="';
            if (getObjectProperty(short, "layout") == '7') {
                cta_style = 'display: none;';
                img_style = 'display: none;';
                display_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_services_layout" value="7"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="clearfix"></div>' + 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-services-margin">' + sh_vars.margin_label + '</label>';
            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }
            modalContent += 
                                                    '<select class="form-control" id="sh-services-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                                '<div class="form-group pxp-is-display" style="' + display_style + '">' + 
                                                    '<label for="sh-services-display">' + sh_vars.display_label + '</label>';
            var displayColumns   = '';
            var displayGrid = '';
            if (getObjectProperty(short, "display") == 'columns') {
                displayColumns = ' selected="selected"';
            }
            if (getObjectProperty(short, "display") == 'grid') {
                displayGrid = ' selected="selected"';
            }
            modalContent += 
                                                    '<select class="form-control" id="sh-services-display">' + 
                                                        '<option value="columns"' + displayColumns + '>' + sh_vars.display_columns + '</option>' + 
                                                        '<option value="grid"' + displayGrid + '>' + sh_vars.display_grid + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                                '<div class="form-group is-custom" style="' + cta_style + '">' + 
                                                    '<label for="sh-services-cta-label">' + sh_vars.cta_text_label + '</label>' + 
                                                    '<input type="text" id="sh-services-cta-label" class="form-control" value="' + getObjectProperty(short, "cta_label") + '" placeholder="' + sh_vars.cta_text_placeholder + '">' + 
                                                '</div>'+ 
                                                '<div class="form-group is-custom" style="' + cta_style + '">' + 
                                                    '<label for="sh-services-cta-link">' + sh_vars.cta_link_label + '</label>' + 
                                                    '<input type="text" id="sh-services-cta-link" class="form-control" value="' + getObjectProperty(short, "cta_link") + '" placeholder="' + sh_vars.cta_link_placeholder + '">' + 
                                                '</div>'+ 
                                                '<div class="form-group is-custom" style="' + cta_style + '">' + 
                                                    '<label for="sh-services-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.cta_color_label + '</label>' + 
                                                    '<input type="text" id="sh-services-cta-color" class="color-field" value="' + getObjectProperty(short, "cta_color") + '">' + 
                                                '</div>'+ 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-services-text-color" style="display:block;margin-bottom:2px;">' + sh_vars.text_color_label + '</label>' + 
                                                    '<input type="text" id="sh-services-text-color" class="color-field" value="' + getObjectProperty(short, "text_color") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-4 rtl-pull-right pxp-is-img" style="' + img_style + '">' + 
                                                '<div class="form-group">' + 
                                                    '<label style="display: block; padding-bottom: 3px;">' + sh_vars.img_label + '</label>' + 
                                                    '<input type="hidden" id="sh-services-image" name="sh-services-image" data-src="' + getObjectProperty(short, "image_src") + '" value="' + getObjectProperty(short, "image") + '">' + 
                                                    '<div class="sh-services-image-placeholder-container';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += 
                                                        ' has-image'; 
            }
                modalContent += 
                                                    '"><div id="sh-services-image-placeholder" style="background-image: url(';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += getObjectProperty(short, "image_src");
            } else { 
                modalContent += sh_vars.plugin_url + 'images/image-placeholder.png'; 
            }
            modalContent += 
                                                        ');"></div>'+
                                                        '<div id="delete-services-image"><span class="fa fa-trash-o"></span></div>' +
                                                    '</div>' +
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.services_list + '</div>' + 
                                        '<ul class="list-group" id="services-list">';

            var services = getObjectProperty(short, "services");

            if ($.isArray(services)) {
                if (services.length > 0) {
                    $.each(services, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem">' + 
                                                    '<div class="list-group-item-img" data-src="' + elem.src + '" data-isicon="' + elem.isicon + '" data-value="' + elem.value + '" data-bgsrc="' + getObjectProperty(elem, "bgsrc") + '" data-bgvalue="' + getObjectProperty(elem, "bgvalue") + '" data-color="' + elem.color + '">';
                        if (elem.isicon == '1') {
                            modalContent += 
                                                        '<span class="' + elem.value + '" style="color: ' + elem.color + '"></span>';
                        } else {
                            modalContent += 
                                                        '<div class="list-group-item-img-container" style="background-image: url(' + elem.src + ');"></div>';
                        }
                        modalContent += 
                                                    '</div>' + 
                                                    '<div class="list-group-item-info" data-link="' + elem.link + '" data-cta="' + elem.cta + '" data-ctacolor="' + elem.ctacolor + '">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="service-title">' + elem.title + '</span>' + 
                                                        '</div>' + 
                                                        '<div class="list-group-item-info-caption">' + 
                                                            '<span class="service-text">' + elem.text + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-service"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<a href="javascript:void(0);" class="pull-right edit-btn edit-service"><span class="fa fa-pencil"></span></a>' + 
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
                services = [];
            }

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-service" class="button media-button button-default">' + sh_vars.add_service_btn + '</button>' + 
                                        '<div class="shortcode-modal-new-container" style="display: none;">' + 
                                            '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.new_service_header + '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<input type="hidden" id="sh-new-service-img" data-isicon="" data-src="">' + 
                                                        '<div class="sh-new-service-img-container">' + 
                                                            '<div class="sh-new-service-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>' + 
                                                            '<div class="sh-new-service-add-btns">' + 
                                                                '<div class="sh-new-service-add-img">' + sh_vars.service_add_img + '</div>' + 
                                                                '<div class="sh-new-service-add-icon">' + sh_vars.service_add_icon + '</div>' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>'+ 
                                                    '<div class="form-group">' + 
                                                        '<label style="display: block; padding-bottom: 3px;">' + sh_vars.img_label + '</label>' + 
                                                        '<input type="hidden" id="sh-new-service-bg-image" name="sh-new-service-bg-image" data-src="">' + 
                                                        '<div class="sh-new-service-bg-image-placeholder-container">' + 
                                                            '<div id="sh-new-service-bg-image-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>' + 
                                                            '<div id="delete-new-service-bg-image"><span class="fa fa-trash-o"></span></div>' +
                                                        '</div>' +
                                                    '</div>' + 
                                                '</div>'+ 
                                                '<div class="col-xs-12 col-md-9 rtl-pull-right">' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-service-title">' + sh_vars.service_title_label + '</label>' + 
                                                                '<input type="text" id="sh-new-service-title" class="form-control" placeholder="' + sh_vars.service_title_placeholder + '">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                        '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-service-text">' + sh_vars.service_text_label + '</label>' + 
                                                                '<input type="text" id="sh-new-service-text" class="form-control" placeholder="' + sh_vars.service_text_placeholder + '">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>'+ 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-service-link">' + sh_vars.service_link_label + '</label>' + 
                                                                '<input type="text" id="sh-new-service-link" class="form-control" placeholder="' + sh_vars.service_link_placeholder + '">' + 
                                                            '</div>'+
                                                        '</div>'+ 
                                                        '<div class="col-xs-12 col-sm-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-service-cta-label">' + sh_vars.service_cta_label + '</label>' + 
                                                                '<input type="text" id="sh-new-service-cta-label" class="form-control" placeholder="' + sh_vars.service_cta_label_placeholder + '">' + 
                                                            '</div>'+
                                                        '</div>'+ 
                                                        '<div class="col-xs-12 col-sm-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-service-color" style="display:block;margin-bottom:2px;">' + sh_vars.service_icon_color + '</label>' + 
                                                                '<input type="text" id="sh-new-service-color" class="color-field" value="#333333">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                        '<div class="col-xs-12 col-sm-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-service-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.service_cta_color + '</label>' + 
                                                                '<input type="text" id="sh-new-service-cta-color" class="color-field" value="#333333">' + 
                                                            '</div>'+ 
                                                        '</div>'+ 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<button type="button" id="ok-service" class="button media-button button-primary">' + sh_vars.ok_service_btn + '</button>' + 
                                            '<button type="button" id="cancel-service" class="button media-button button-default">' + sh_vars.cancel_service_btn + '</button>' + 
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
                    'title'     : $('#sh-services-title').val(),
                    'subtitle'  : $('#sh-services-subtitle').val(),
                    'image'     : $('#sh-services-image').val(),
                    'image_src' : $('#sh-services-image').attr('data-src'),
                    'layout'    : $('#sh-services-layout .layout-active > input').val(),
                    'margin'    : $('#sh-services-margin').val(),
                    'display'   : $('#sh-services-display').val(),
                    'text_color': $('#sh-services-text-color').val(),
                    'cta_label' : $('#sh-services-cta-label').val(),
                    'cta_link'  : $('#sh-services-cta-link').val(),
                    'cta_color' : $('#sh-services-cta-color').val(),
                    'services'  : services
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('#sh-services-layout label').on('click', function() {
                $('#sh-services-layout label').removeClass('layout-active');
                $(this).addClass('layout-active');

                if ($(this).parent().hasClass('layout-radio-services-2') || $(this).parent().hasClass('layout-radio-services-3') || $(this).parent().hasClass('layout-radio-services-5')) {
                    $('.form-group.is-custom').show();
                } else {
                    $('.form-group.is-custom').hide();
                }

                if ($(this).parent().hasClass('layout-radio-services-4') || $(this).parent().hasClass('layout-radio-services-6') || $(this).parent().hasClass('layout-radio-services-7')) {
                    $('.pxp-is-img').hide();
                } else {
                    $('.pxp-is-img').show();
                }

                if ($(this).parent().hasClass('layout-radio-services-4')) {
                    $('.pxp-is-display').show();
                } else {
                    $('.pxp-is-display').hide();
                }
            });

            $('#sh-services-image-placeholder').on('click', function(event) {
                event.preventDefault();

                var frame = wp.media({
                    title: sh_vars.media_services_image_title,
                    button: {
                        text: sh_vars.media_services_image_btn
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').toJSON();
                    $.each(attachment, function(index, value) {
                        $('#sh-services-image').val(value.id).attr('data-src', value.url);
                        $('#sh-services-image-placeholder').css('background-image', 'url(' + value.url + ')');
                        $('.sh-services-image-placeholder-container').addClass('has-image');
                    });
                });

                frame.open();
            });

            $('#delete-services-image').on('click', function() {
                $('#sh-services-image').val('').attr('data-src', '');
                $('#sh-services-image-placeholder').css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)');
                $('.sh-services-image-placeholder-container').removeClass('has-image');
            });

            $('.sh-new-service-add-img').on('click', function(event) {
                openMediaLibrary($(this));
            });

            $('.sh-new-service-add-icon').on('click', function(event) {
                openIconsModal($(this));
            });

            $('#sh-new-service-bg-image-placeholder').on('click', function(event) {
                event.preventDefault();

                var frame = wp.media({
                    title: sh_vars.media_services_image_title,
                    button: {
                        text: sh_vars.media_services_image_btn
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').toJSON();
                    $.each(attachment, function(index, value) {
                        $('#sh-new-service-bg-image').val(value.id).attr('data-src', value.url);
                        $('#sh-new-service-bg-image-placeholder').css('background-image', 'url(' + value.url + ')');
                        $('.sh-new-service-bg-image-placeholder-container').addClass('has-image');
                    });
                });

                frame.open();
            });

            $('#delete-new-service-bg-image').on('click', function() {
                $('#sh-new-service-bg-image').val('').attr('data-src', '');
                $('#sh-new-service-bg-image-placeholder').css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)');
                $('.sh-new-service-bg-image-placeholder-container').removeClass('has-image');
            });

            $('#add-service').on('click', function(event) {
                $(this).hide();
                $('.shortcode-modal-new-container').show();
            });

            $('#cancel-service').on('click', function(event) {
                $('.shortcode-modal-new-container').hide();
                $('.sh-new-service-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-service-img').val('').attr({
                    'data-isicon' : '0',
                    'data-src'    : '',
                });
                $('#sh-new-service-bg-image-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-service-bg-image').val('').attr({
                    'data-src' : '',
                });
                $('.sh-new-service-bg-image-placeholder-container').removeClass('has-image');
                $('#sh-new-service-title').val('');
                $('#sh-new-service-text').val('');
                $('#sh-new-service-link').val('');
                $('#sh-new-service-cta-label').val('');
                $('#sh-new-service-color').wpColorPicker('color', '#333333');
                $('#sh-new-service-cta-color').wpColorPicker('color', '#333333');
                $('#add-service').show();
            });

            $('#ok-service').on('click', function(event) {
                var newImgSrc     = $('#sh-new-service-img').attr('data-src');
                var newIsIcon     = $('#sh-new-service-img').attr('data-isicon');
                var newImgValue   = $('#sh-new-service-img').val();
                var newBgImgSrc   = $('#sh-new-service-bg-image').attr('data-src');
                var newBgImgValue = $('#sh-new-service-bg-image').val();
                var newTitle      = $('#sh-new-service-title').val();
                var newText       = $('#sh-new-service-text').val();
                var newLink       = $('#sh-new-service-link').val();
                var newCtalabel   = $('#sh-new-service-cta-label').val();
                var newColor      = $('#sh-new-service-color').val();
                var newCTAColor   = $('#sh-new-service-cta-color').val();

                services.push({
                    'src'     : newImgSrc,
                    'isicon'  : newIsIcon,
                    'value'   : newImgValue,
                    'bgsrc'   : newBgImgSrc,
                    'bgvalue' : newBgImgValue,
                    'title'   : newTitle,
                    'text'    : newText,
                    'link'    : newLink,
                    'cta'     : newCtalabel,
                    'color'   : newColor,
                    'ctacolor': newCTAColor
                });

                var newService = 
                    '<li class="list-group-item">' + 
                        '<div class="list-group-item-elem">' + 
                            '<div class="list-group-item-img" data-src="' + newImgSrc + '" data-isicon="' + newIsIcon + '" data-value="' + newImgValue + '" data-bgsrc="' + newBgImgSrc + '" data-bgvalue="' + newBgImgValue + '" data-color="' + newColor + '">';
                if (newIsIcon == '1') {
                    newService += 
                                '<span class="' + newImgValue + '" style="color: ' + newColor + '"></span>';
                } else {
                    newService += 
                                '<div class="list-group-item-img-container" style="background-image: url(' + newImgSrc + ');"></div>';
                }
                newService += 
                            '</div>' + 
                            '<div class="list-group-item-info" data-link="' + newLink + '" data-cta="' + newCtalabel + '" data-ctacolor="' + newCTAColor + '">' + 
                                '<div class="list-group-item-info-title">' + 
                                    '<span class="service-title">' + newTitle + '</span>' + 
                                '</div>' + 
                                '<div class="list-group-item-info-caption">' + 
                                    '<span class="service-text">' + newText + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-new-service"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-service"><span class="fa fa-pencil"></span></a>' + 
                        '</div>' + 
                    '<li>';
                $('#services-list .sortcode-modal-empty').remove();
                $('#services-list').append(newService);

                $('.shortcode-modal-new-container').hide();
                $('.sh-new-service-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-service-img').val('').attr({
                    'data-isicon' : '0',
                    'data-src'    : '',
                });
                $('#sh-new-service-bg-image-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-service-bg-image').val('').attr({
                    'data-src' : '',
                });
                $('#sh-new-service-title').val('');
                $('#sh-new-service-text').val('');
                $('#sh-new-service-link').val('');
                $('#sh-new-service-cta-link').val('');
                $('#sh-new-service-color').wpColorPicker('color', '#333333');
                $('#sh-new-service-cta-color').wpColorPicker('color', '#333333');
                $('#add-service').show();

                $('.edit-new-service').unbind('click').on('click', function(event) {
                    editService($(this));
                });
                $('.del-new-service').unbind('click').on('click', function(event) {
                    delService($(this));
                });
            });

            $('#services-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    services = [];

                    $('#services-list .list-group-item').each(function() {
                        var newImgSrc     = $(this).find('.list-group-item-img').attr('data-src');
                        var newIsIcon     = $(this).find('.list-group-item-img').attr('data-isicon');
                        var newImgValue   = $(this).find('.list-group-item-img').attr('data-value');
                        var newBgImgSrc   = $(this).find('.list-group-item-img').attr('data-bgsrc');
                        var newBgImgValue = $(this).find('.list-group-item-img').attr('data-bgvalue');
                        var newTitle      = $(this).find('.service-title').text();
                        var newText       = $(this).find('.service-text').text();
                        var newLink       = $(this).find('.list-group-item-info').attr('data-link');
                        var newCtalabel   = $(this).find('.list-group-item-info').attr('data-cta');
                        var newColor      = $(this).find('.list-group-item-img').attr('data-color');
                        var newCTAColor   = $(this).find('.list-group-item-info').attr('data-ctacolor');

                        services.push({
                            'src'     : newImgSrc,
                            'isicon'  : newIsIcon,
                            'value'   : newImgValue,
                            'bgsrc'   : newBgImgSrc,
                            'bgvalue' : newBgImgValue,
                            'title'   : newTitle,
                            'text'    : newText,
                            'link'    : newLink,
                            'cta'     : newCtalabel,
                            'color'   : newColor,
                            'ctacolor': newCTAColor
                        });
                    });
                }
            }).disableSelection();

            $('.edit-service').on('click', function(event) {
                editService($(this));
            });

            function editService(btn) {
                var editImgSrc     = btn.parent().find('.list-group-item-img').attr('data-src');
                var editIsIcon     = btn.parent().find('.list-group-item-img').attr('data-isicon');
                var editImgValue   = btn.parent().find('.list-group-item-img').attr('data-value');
                var editBgImgSrc   = btn.parent().find('.list-group-item-img').attr('data-bgsrc');
                var editBgImgValue = btn.parent().find('.list-group-item-img').attr('data-bgvalue');
                var editTitle      = btn.parent().find('.service-title').text();
                var editText       = btn.parent().find('.service-text').text();
                var editLink       = btn.parent().find('.list-group-item-info').attr('data-link');
                var editCtaLabel   = btn.parent().find('.list-group-item-info').attr('data-cta');
                var editColor      = btn.parent().find('.list-group-item-img').attr('data-color');
                var editCTAColor   = btn.parent().find('.list-group-item-info').attr('data-ctacolor');

                var editServiceForm = 
                    '<div class="sh-edit-service">' + 
                        '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.edit_service_header + '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<input type="hidden" id="sh-edit-service-img" data-isicon="' + editIsIcon + '" data-src="' + editImgSrc + '" value="' + editImgValue + '" data-color="' + editColor + '">' + 
                                    '<div class="sh-new-service-img-container">';
                if (editIsIcon == '1') {
                    editServiceForm += 
                                        '<div class="sh-new-service-img-placeholder"><span class="' + editImgValue + '" style="color: ' + editColor + '"></span></div>';
                } else {
                    if (editImgSrc != '') {
                        editServiceForm += 
                                        '<div class="sh-new-service-img-placeholder has-image" style="background-image: url(' + editImgSrc + ');"></div>';
                    } else {
                        editServiceForm += 
                                        '<div class="sh-new-service-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>';
                    }
                }
                editServiceForm += 
                                        '<div class="sh-new-service-add-btns">' + 
                                            '<div class="sh-new-service-add-img">' + sh_vars.service_add_img + '</div>' + 
                                            '<div class="sh-new-service-add-icon">' + sh_vars.service_add_icon + '</div>' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                                '<div class="form-group">' + 
                                    '<label style="display: block; padding-bottom: 3px;">' + sh_vars.img_label + '</label>' + 
                                    '<input type="hidden" id="sh-edit-service-bg-image" name="sh-edit-service-bg-image" data-src="' + editBgImgSrc + '" value="' + editBgImgValue + '">' + 
                                    '<div class="sh-edit-service-bg-image-placeholder-container';
                if (editBgImgSrc != '') { 
                    editServiceForm += 
                                        ' has-image'; 
                }
                editServiceForm += 
                                        '"><div id="sh-edit-service-bg-image-placeholder" style="background-image: url(';
                if (editBgImgSrc != '') { 
                    editServiceForm += editBgImgSrc;
                } else { 
                    editServiceForm += sh_vars.plugin_url + 'images/image-placeholder.png'; 
                }
                editServiceForm += 
                                        ');"></div>'+
                                        '<div id="delete-edit-service-bg-image"><span class="fa fa-trash-o"></span></div>' +
                                    '</div>' +
                                '</div>' + 
                            '</div>'+ 
                            '<div class="col-xs-12 col-md-9 rtl-pull-right">' + 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-service-title">' + sh_vars.service_title_label + '</label>' + 
                                            '<input type="text" id="sh-edit-service-title" class="form-control" placeholder="' + sh_vars.service_title_placeholder + '" value="' + editTitle + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-service-text">' + sh_vars.service_text_label + '</label>' + 
                                            '<input type="text" id="sh-edit-service-text" class="form-control" placeholder="' + sh_vars.service_text_placeholder + '" value="' + editText + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-service-link">' + sh_vars.service_link_label + '</label>' + 
                                            '<input type="text" id="sh-edit-service-link" class="form-control" placeholder="' + sh_vars.service_link_placeholder + '" value="' + editLink + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-service-cta-label">' + sh_vars.service_cta_label + '</label>' + 
                                            '<input type="text" id="sh-edit-service-cta-label" class="form-control" placeholder="' + sh_vars.service_cta_label_placeholder + '" value="' + editCtaLabel + '">' + 
                                        '</div>'+
                                    '</div>'+ 
                                '</div>'+ 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-service-color" style="display:block;margin-bottom:2px;">' + sh_vars.service_icon_color + '</label>' + 
                                            '<input type="text" id="sh-edit-service-color" class="color-field" value="' + editColor + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-service-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.service_cta_color + '</label>' + 
                                            '<input type="text" id="sh-edit-service-cta-color" class="color-field" value="' + editCTAColor + '">' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                        '</div>'+ 
                        '<button type="button" id="ok-edit-service" class="button media-button button-primary">' + sh_vars.ok_edit_service_btn + '</button>' + 
                        '<button type="button" id="cancel-edit-service" class="button media-button button-default">' + sh_vars.cancel_service_btn + '</button>' + 
                    '</div>';

                btn.parent().hide();
                btn.parent().parent().append(editServiceForm);

                $('#services-list').sortable('disable');
                $('#services-list .list-group-item').css('cursor', 'auto');
                $('.edit-service').hide();
                $('.del-service').hide();
                $('.edit-new-service').hide();
                $('.del-new-service').hide();
                $('#add-service').hide();
                $('.shortcode-modal-new-container').hide();

                $('.sh-new-service-add-img').on('click', function(event) {
                    openMediaLibrary($(this));
                });

                $('.sh-new-service-add-icon').on('click', function(event) {
                    openIconsModal($(this));
                });

                $('#sh-edit-service-bg-image-placeholder').on('click', function(event) {
                    event.preventDefault();
    
                    var frame = wp.media({
                        title: sh_vars.media_services_image_title,
                        button: {
                            text: sh_vars.media_services_image_btn
                        },
                        multiple: false
                    });
    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').toJSON();
                        $.each(attachment, function(index, value) {
                            $('#sh-edit-service-bg-image').val(value.id).attr('data-src', value.url);
                            $('#sh-edit-service-bg-image-placeholder').css('background-image', 'url(' + value.url + ')');
                            $('.sh-edit-service-bg-image-placeholder-container').addClass('has-image');
                        });
                    });
    
                    frame.open();
                });
    
                $('#delete-edit-service-bg-image').on('click', function() {
                    $('#sh-edit-service-bg-image').val('').attr('data-src', '');
                    $('#sh-edit-service-bg-image-placeholder').css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)');
                    $('.sh-edit-service-bg-image-placeholder-container').removeClass('has-image');
                });

                $('.color-field').wpColorPicker({
                    defaultColor: '#333333',
                });

                $('#ok-edit-service').on('click', function(event) {
                    services = [];

                    var eImgSrc   = $(this).parent().find('#sh-edit-service-img').attr('data-src');
                    var eIsIcon   = $(this).parent().find('#sh-edit-service-img').attr('data-isicon');
                    var eColor    = $(this).parent().find('#sh-edit-service-color').val();
                    var eCTAColor = $(this).parent().find('#sh-edit-service-cta-color').val();
                    var eImgValue = $(this).parent().find('#sh-edit-service-img').val();
                    var eBgImgSrc   = $(this).parent().find('#sh-edit-service-bg-image').attr('data-src');
                    var eBgImgValue = $(this).parent().find('#sh-edit-service-bg-image').val();
                    var eTitle    = $(this).parent().find('#sh-edit-service-title').val();
                    var eText     = $(this).parent().find('#sh-edit-service-text').val();
                    var eLink     = $(this).parent().find('#sh-edit-service-link').val();
                    var eCtaLabel = $(this).parent().find('#sh-edit-service-cta-label').val();
                    var listElem  = $(this).parent().parent().find('.list-group-item-elem');

                    listElem.find('.list-group-item-img').attr({
                        'data-src'    : eImgSrc,
                        'data-isicon' : eIsIcon,
                        'data-value'  : eImgValue,
                        'data-bgsrc'  : eBgImgSrc,
                        'data-bgvalue': eBgImgValue,
                        'data-color'  : eColor
                    });

                    if (eIsIcon == '1') {
                        listElem.find('.list-group-item-img').html('<span class="' + eImgValue + '" style="color: ' + eColor + '"></span>');
                    } else {
                        listElem.find('.list-group-item-img').html('<div class="list-group-item-img-container" style="background-image: url(' + eImgSrc + ');"></div>');
                    }

                    listElem.find('.service-title').text(eTitle);
                    listElem.find('.service-text').text(eText);
                    listElem.find('.list-group-item-info').attr('data-link', eLink).attr('data-cta', eCtaLabel).attr('data-ctacolor', eCTAColor);

                    $(this).parent().remove();
                    listElem.show();

                    $('#services-list').sortable('enable');
                    $('#services-list .list-group-item').css('cursor', 'move');
                    $('.edit-service').show();
                    $('.del-service').show();
                    $('.edit-new-service').show();
                    $('.del-new-service').show();
                    $('#add-service').show();

                    $('#services-list .list-group-item').each(function() {
                        var newImgSrc   = $(this).find('.list-group-item-img').attr('data-src');
                        var newIsIcon   = $(this).find('.list-group-item-img').attr('data-isicon');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');
                        var newBgImgSrc   = $(this).find('.list-group-item-img').attr('data-bgsrc');
                        var newBgImgValue = $(this).find('.list-group-item-img').attr('data-bgvalue');
                        var newColor    = $(this).find('.list-group-item-img').attr('data-color');
                        var newCTAColor = $(this).find('.list-group-item-info').attr('data-ctacolor');
                        var newTitle    = $(this).find('.service-title').text();
                        var newText     = $(this).find('.service-text').text();
                        var newLink     = $(this).find('.list-group-item-info').attr('data-link');
                        var newCtalabel = $(this).find('.list-group-item-info').attr('data-cta');

                        services.push({
                            'src'     : newImgSrc,
                            'isicon'  : newIsIcon,
                            'value'   : newImgValue,
                            'bgsrc'   : newBgImgSrc,
                            'bgvalue' : newBgImgValue,
                            'title'   : newTitle,
                            'text'    : newText,
                            'link'    : newLink,
                            'cta'     : newCtalabel,
                            'color'   : newColor,
                            'ctacolor': newCTAColor,
                        });
                    });
                });

                $('#cancel-edit-service').on('click', function(event) {
                    var listElem = $(this).parent().parent().find('.list-group-item-elem');

                    $(this).parent().remove();
                    listElem.show();
                    $('#services-list').sortable('enable');
                    $('#services-list .list-group-item').css('cursor', 'move');
                    $('.edit-service').show();
                    $('.del-service').show();
                    $('.edit-new-service').show();
                    $('.del-new-service').show();
                    $('#add-service').show();
                });
            }

            $('.del-service').on('click', function(event) {
                delService($(this));
            });

            function delService(btn) {
                services = [];

                btn.parent().parent().remove();
                if ($('#services-list .list-group-item').length > 0) {
                    $('#services-list .list-group-item').each(function() {
                        var newImgSrc     = $(this).find('.list-group-item-img').attr('data-src');
                        var newIsIcon     = $(this).find('.list-group-item-img').attr('data-isicon');
                        var newImgValue   = $(this).find('.list-group-item-img').attr('data-value');
                        var newBgImgSrc   = $(this).find('.list-group-item-img').attr('data-bgsrc');
                        var newBgImgValue = $(this).find('.list-group-item-img').attr('data-bgvalue');
                        var newColor      = $(this).find('.list-group-item-img').attr('data-color');
                        var newCTAColor   = $(this).find('.list-group-item-info').attr('data-ctacolor');
                        var newTitle      = $(this).find('.service-title').text();
                        var newText       = $(this).find('.service-text').text();
                        var newLink       = $(this).find('.list-group-item-info').attr('data-link');
                        var newCtalabel   = $(this).find('.list-group-item-info').attr('data-cta');

                        services.push({
                            'src'     : newImgSrc,
                            'isicon'  : newIsIcon,
                            'value'   : newImgValue,
                            'bgsrc'   : newBgImgSrc,
                            'bgvalue' : newBgImgValue,
                            'title'   : newTitle,
                            'text'    : newText,
                            'link'    : newLink,
                            'cta'     : newCtalabel,
                            'color'   : newColor,
                            'ctacolor': newCTAColor,
                        });
                    });
                } else {
                    $('#services-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>');
                }
            }

            $('.color-field').wpColorPicker({
                defaultColor: '#333333',
            });
        }

        // Open Media Library
        function openMediaLibrary(btn) {
            var frame = wp.media({
                title: sh_vars.service_image,
                button: {
                    text: sh_vars.service_insert_image
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    btn.parent().parent().parent().children('input').val(value.id).attr({
                        'data-isicon' : '0',
                        'data-src'    : value.url,
                    });
                    btn.parent().parent().find('.sh-new-service-img-placeholder').css('background-image', 'url(' + value.url + ')').addClass('has-image').empty();
                });
            });

            frame.open();
        }

        // Open icons modal
        function openIconsModal(btn) {
            var faIcons = ['fa fa-500px', 'fa fa-address-book', 'fa fa-address-book-o', 'fa fa-address-card', 'fa fa-address-card-o', 'fa fa-adjust', 'fa fa-adn', 'fa fa-align-center', 'fa fa-align-justify', 'fa fa-align-left', 'fa fa-align-right', 'fa fa-amazon', 'fa fa-ambulance', 'fa fa-american-sign-language-interpreting', 'fa fa-anchor', 'fa fa-android', 'fa fa-angellist', 'fa fa-angle-double-down', 'fa fa-angle-double-left', 'fa fa-angle-double-right', 'fa fa-angle-double-up', 'fa fa-angle-down', 'fa fa-angle-left', 'fa fa-angle-right', 'fa fa-angle-up', 'fa fa-apple', 'fa fa-archive', 'fa fa-area-chart', 'fa fa-arrow-circle-down', 'fa fa-arrow-circle-left', 'fa fa-arrow-circle-o-down', 'fa fa-arrow-circle-o-left', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-o-up', 'fa fa-arrow-circle-right', 'fa fa-arrow-circle-up', 'fa fa-arrow-down', 'fa fa-arrow-left', 'fa fa-arrow-right', 'fa fa-arrow-up', 'fa fa-arrows', 'fa fa-arrows-alt', 'fa fa-arrows-h', 'fa fa-arrows-v', 'fa fa-asl-interpreting', 'fa fa-assistive-listening-systems', 'fa fa-asterisk', 'fa fa-at', 'fa fa-audio-description', 'fa fa-automobile', 'fa fa-backward', 'fa fa-balance-scale', 'fa fa-ban', 'fa fa-bandcamp', 'fa fa-bank', 'fa fa-bar-chart', 'fa fa-bar-chart-o', 'fa fa-barcode', 'fa fa-bars', 'fa fa-bath', 'fa fa-bathtub', 'fa fa-battery', 'fa fa-battery-0', 'fa fa-battery-1', 'fa fa-battery-2', 'fa fa-battery-3', 'fa fa-battery-4', 'fa fa-battery-empty', 'fa fa-battery-full', 'fa fa-battery-half', 'fa fa-battery-quarter', 'fa fa-battery-three-quarters', 'fa fa-bed', 'fa fa-beer', 'fa fa-behance', 'fa fa-behance-square', 'fa fa-bell', 'fa fa-bell-o', 'fa fa-bell-slash', 'fa fa-bell-slash-o', 'fa fa-bicycle', 'fa fa-binoculars', 'fa fa-birthday-cake', 'fa fa-bitbucket', 'fa fa-bitbucket-square', 'fa fa-bitcoin', 'fa fa-black-tie', 'fa fa-blind', 'fa fa-bluetooth', 'fa fa-bluetooth-b', 'fa fa-bold', 'fa fa-bolt', 'fa fa-bomb', 'fa fa-book', 'fa fa-bookmark', 'fa fa-bookmark-o', 'fa fa-braille', 'fa fa-briefcase', 'fa fa-btc', 'fa fa-bug', 'fa fa-building', 'fa fa-building-o', 'fa fa-bullhorn', 'fa fa-bullseye', 'fa fa-bus', 'fa fa-buysellads', 'fa fa-cab', 'fa fa-calculator', 'fa fa-calendar', 'fa fa-calendar-check-o', 'fa fa-calendar-minus-o', 'fa fa-calendar-o', 'fa fa-calendar-plus-o', 'fa fa-calendar-times-o', 'fa fa-camera', 'fa fa-camera-retro', 'fa fa-car', 'fa fa-caret-down', 'fa fa-caret-left', 'fa fa-caret-right', 'fa fa-caret-square-o-down', 'fa fa-caret-square-o-left', 'fa fa-caret-square-o-right', 'fa fa-caret-square-o-up', 'fa fa-caret-up', 'fa fa-cart-arrow-down', 'fa fa-cart-plus', 'fa fa-cc', 'fa fa-cc-amex', 'fa fa-cc-diners-club', 'fa fa-cc-discover', 'fa fa-cc-jcb', 'fa fa-cc-mastercard', 'fa fa-cc-paypal', 'fa fa-cc-stripe', 'fa fa-cc-visa', 'fa fa-certificate', 'fa fa-chain', 'fa fa-chain-broken', 'fa fa-check', 'fa fa-check-circle', 'fa fa-check-circle-o', 'fa fa-check-square', 'fa fa-check-square-o', 'fa fa-chevron-circle-down', 'fa fa-chevron-circle-left', 'fa fa-chevron-circle-right', 'fa fa-chevron-circle-up', 'fa fa-chevron-down', 'fa fa-chevron-left', 'fa fa-chevron-right', 'fa fa-chevron-up', 'fa fa-child', 'fa fa-chrome', 'fa fa-circle', 'fa fa-circle-o', 'fa fa-circle-o-notch', 'fa fa-circle-thin', 'fa fa-clipboard', 'fa fa-clock-o', 'fa fa-clone', 'fa fa-close', 'fa fa-cloud', 'fa fa-cloud-download', 'fa fa-cloud-upload', 'fa fa-cny', 'fa fa-code', 'fa fa-code-fork', 'fa fa-codepen', 'fa fa-codiepie', 'fa fa-coffee', 'fa fa-cog', 'fa fa-cogs', 'fa fa-columns', 'fa fa-comment', 'fa fa-comment-o', 'fa fa-commenting', 'fa fa-commenting-o', 'fa fa-comments', 'fa fa-comments-o', 'fa fa-compass', 'fa fa-compress', 'fa fa-connectdevelop', 'fa fa-contao', 'fa fa-copy', 'fa fa-copyright', 'fa fa-creative-commons', 'fa fa-credit-card', 'fa fa-credit-card-alt', 'fa fa-crop', 'fa fa-crosshairs', 'fa fa-css3', 'fa fa-cube', 'fa fa-cubes', 'fa fa-cut', 'fa fa-cutlery', 'fa fa-dashboard', 'fa fa-dashcube', 'fa fa-database', 'fa fa-deaf', 'fa fa-deafness', 'fa fa-dedent', 'fa fa-delicious', 'fa fa-desktop', 'fa fa-deviantart', 'fa fa-diamond', 'fa fa-digg', 'fa fa-dollar', 'fa fa-dot-circle-o', 'fa fa-download', 'fa fa-dribbble', 'fa fa-drivers-license', 'fa fa-drivers-license-o', 'fa fa-dropbox', 'fa fa-drupal', 'fa fa-edge', 'fa fa-edit', 'fa fa-eercast', 'fa fa-eject', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-empire', 'fa fa-envelope', 'fa fa-envelope-o', 'fa fa-envelope-open', 'fa fa-envelope-open-o', 'fa fa-envelope-square', 'fa fa-envira', 'fa fa-eraser', 'fa fa-etsy', 'fa fa-eur', 'fa fa-euro', 'fa fa-exchange', 'fa fa-exclamation', 'fa fa-exclamation-circle', 'fa fa-exclamation-triangle', 'fa fa-expand', 'fa fa-expeditedssl', 'fa fa-external-link', 'fa fa-external-link-square', 'fa fa-eye', 'fa fa-eye-slash', 'fa fa-eyedropper', 'fa fa-fa', 'fa fa-facebook', 'fa fa-facebook-f', 'fa fa-facebook-official', 'fa fa-facebook-square', 'fa fa-fast-backward', 'fa fa-fast-forward', 'fa fa-fax', 'fa fa-feed', 'fa fa-female', 'fa fa-fighter-jet', 'fa fa-file', 'fa fa-file-archive-o', 'fa fa-file-audio-o', 'fa fa-file-code-o', 'fa fa-file-excel-o', 'fa fa-file-image-o', 'fa fa-file-movie-o', 'fa fa-file-o', 'fa fa-file-pdf-o', 'fa fa-file-photo-o', 'fa fa-file-picture-o', 'fa fa-file-powerpoint-o', 'fa fa-file-sound-o', 'fa fa-file-text', 'fa fa-file-text-o', 'fa fa-file-video-o', 'fa fa-file-word-o', 'fa fa-file-zip-o', 'fa fa-files-o', 'fa fa-film', 'fa fa-filter', 'fa fa-fire', 'fa fa-fire-extinguisher', 'fa fa-firefox', 'fa fa-first-order', 'fa fa-flag', 'fa fa-flag-checkered', 'fa fa-flag-o', 'fa fa-flash', 'fa fa-flask', 'fa fa-flickr', 'fa fa-floppy-o', 'fa fa-folder', 'fa fa-folder-o', 'fa fa-folder-open', 'fa fa-folder-open-o', 'fa fa-font', 'fa fa-font-awesome', 'fa fa-fonticons', 'fa fa-fort-awesome', 'fa fa-forumbee', 'fa fa-forward', 'fa fa-foursquare', 'fa fa-free-code-camp', 'fa fa-frown-o', 'fa fa-futbol-o', 'fa fa-gamepad', 'fa fa-gavel', 'fa fa-gbp', 'fa fa-ge', 'fa fa-gear', 'fa fa-gears', 'fa fa-genderless', 'fa fa-get-pocket', 'fa fa-gg', 'fa fa-gg-circle', 'fa fa-gift', 'fa fa-git', 'fa fa-git-square', 'fa fa-github', 'fa fa-github-alt', 'fa fa-github-square', 'fa fa-gitlab', 'fa fa-gittip', 'fa fa-glass', 'fa fa-glide', 'fa fa-glide-g', 'fa fa-globe', 'fa fa-google', 'fa fa-google-plus', 'fa fa-google-plus-circle', 'fa fa-google-plus-official', 'fa fa-google-plus-square', 'fa fa-google-wallet', 'fa fa-graduation-cap', 'fa fa-gratipay', 'fa fa-grav', 'fa fa-group', 'fa fa-h-square', 'fa fa-hacker-news', 'fa fa-hand-grab-o', 'fa fa-hand-lizard-o', 'fa fa-hand-o-down', 'fa fa-hand-o-left', 'fa fa-hand-o-right', 'fa fa-hand-o-up', 'fa fa-hand-paper-o', 'fa fa-hand-peace-o', 'fa fa-hand-pointer-o', 'fa fa-hand-rock-o', 'fa fa-hand-scissors-o', 'fa fa-hand-spock-o', 'fa fa-hand-stop-o', 'fa fa-handshake-o', 'fa fa-hard-of-hearing', 'fa fa-hashtag', 'fa fa-hdd-o', 'fa fa-header', 'fa fa-headphones', 'fa fa-heart', 'fa fa-heart-o', 'fa fa-heartbeat', 'fa fa-history', 'fa fa-home', 'fa fa-hospital-o', 'fa fa-hotel', 'fa fa-hourglass', 'fa fa-hourglass-1', 'fa fa-hourglass-2', 'fa fa-hourglass-3', 'fa fa-hourglass-end', 'fa fa-hourglass-half', 'fa fa-hourglass-o', 'fa fa-hourglass-start', 'fa fa-houzz', 'fa fa-html5', 'fa fa-i-cursor', 'fa fa-id-badge', 'fa fa-id-card', 'fa fa-id-card-o', 'fa fa-ils', 'fa fa-image', 'fa fa-imdb', 'fa fa-inbox', 'fa fa-indent', 'fa fa-industry', 'fa fa-info', 'fa fa-info-circle', 'fa fa-inr', 'fa fa-instagram', 'fa fa-institution', 'fa fa-internet-explorer', 'fa fa-intersex', 'fa fa-ioxhost', 'fa fa-italic', 'fa fa-joomla', 'fa fa-jpy', 'fa fa-jsfiddle', 'fa fa-key', 'fa fa-keyboard-o', 'fa fa-krw', 'fa fa-language', 'fa fa-laptop', 'fa fa-lastfm', 'fa fa-lastfm-square', 'fa fa-leaf', 'fa fa-leanpub', 'fa fa-legal', 'fa fa-lemon-o', 'fa fa-level-down', 'fa fa-level-up', 'fa fa-life-bouy', 'fa fa-life-buoy', 'fa fa-life-ring', 'fa fa-life-saver', 'fa fa-lightbulb-o', 'fa fa-line-chart', 'fa fa-link', 'fa fa-linkedin', 'fa fa-linkedin-square', 'fa fa-linode', 'fa fa-linux', 'fa fa-list', 'fa fa-list-alt', 'fa fa-list-ol', 'fa fa-list-ul', 'fa fa-location-arrow', 'fa fa-lock', 'fa fa-long-arrow-down', 'fa fa-long-arrow-left', 'fa fa-long-arrow-right', 'fa fa-long-arrow-up', 'fa fa-low-vision', 'fa fa-magic', 'fa fa-magnet', 'fa fa-mail-forward', 'fa fa-mail-reply', 'fa fa-mail-reply-all', 'fa fa-male', 'fa fa-map', 'fa fa-map-marker', 'fa fa-map-o', 'fa fa-map-pin', 'fa fa-map-signs', 'fa fa-mars', 'fa fa-mars-double', 'fa fa-mars-stroke', 'fa fa-mars-stroke-h', 'fa fa-mars-stroke-v', 'fa fa-maxcdn', 'fa fa-meanpath', 'fa fa-medium', 'fa fa-medkit', 'fa fa-meetup', 'fa fa-meh-o', 'fa fa-mercury', 'fa fa-microchip', 'fa fa-microphone', 'fa fa-microphone-slash', 'fa fa-minus', 'fa fa-minus-circle', 'fa fa-minus-square', 'fa fa-minus-square-o', 'fa fa-mixcloud', 'fa fa-mobile', 'fa fa-mobile-phone', 'fa fa-modx', 'fa fa-money', 'fa fa-moon-o', 'fa fa-mortar-board', 'fa fa-motorcycle', 'fa fa-mouse-pointer', 'fa fa-music', 'fa fa-navicon', 'fa fa-neuter', 'fa fa-newspaper-o', 'fa fa-object-group', 'fa fa-object-ungroup', 'fa fa-odnoklassniki', 'fa fa-odnoklassniki-square', 'fa fa-opencart', 'fa fa-openid', 'fa fa-opera', 'fa fa-optin-monster', 'fa fa-outdent', 'fa fa-pagelines', 'fa fa-paint-brush', 'fa fa-paper-plane', 'fa fa-paper-plane-o', 'fa fa-paperclip', 'fa fa-paragraph', 'fa fa-paste', 'fa fa-pause', 'fa fa-pause-circle', 'fa fa-pause-circle-o', 'fa fa-paw', 'fa fa-paypal', 'fa fa-pencil', 'fa fa-pencil-square', 'fa fa-pencil-square-o', 'fa fa-percent', 'fa fa-phone', 'fa fa-phone-square', 'fa fa-photo', 'fa fa-picture-o', 'fa fa-pie-chart', 'fa fa-pied-piper', 'fa fa-pied-piper-alt', 'fa fa-pied-piper-pp', 'fa fa-pinterest', 'fa fa-pinterest-p', 'fa fa-pinterest-square', 'fa fa-plane', 'fa fa-play', 'fa fa-play-circle', 'fa fa-play-circle-o', 'fa fa-plug', 'fa fa-plus', 'fa fa-plus-circle', 'fa fa-plus-square', 'fa fa-plus-square-o', 'fa fa-podcast', 'fa fa-power-off', 'fa fa-print', 'fa fa-product-hunt', 'fa fa-puzzle-piece', 'fa fa-qq', 'fa fa-qrcode', 'fa fa-question', 'fa fa-question-circle', 'fa fa-question-circle-o', 'fa fa-quora', 'fa fa-quote-left', 'fa fa-quote-right', 'fa fa-ra', 'fa fa-random', 'fa fa-ravelry', 'fa fa-rebel', 'fa fa-recycle', 'fa fa-reddit', 'fa fa-reddit-alien', 'fa fa-reddit-square', 'fa fa-refresh', 'fa fa-registered', 'fa fa-remove', 'fa fa-renren', 'fa fa-reorder', 'fa fa-repeat', 'fa fa-reply', 'fa fa-reply-all', 'fa fa-resistance', 'fa fa-retweet', 'fa fa-rmb', 'fa fa-road', 'fa fa-rocket', 'fa fa-rotate-left', 'fa fa-rotate-right', 'fa fa-rouble', 'fa fa-rss', 'fa fa-rss-square', 'fa fa-rub', 'fa fa-ruble', 'fa fa-rupee', 'fa fa-s15', 'fa fa-safari', 'fa fa-save', 'fa fa-scissors', 'fa fa-scribd', 'fa fa-search', 'fa fa-search-minus', 'fa fa-search-plus', 'fa fa-sellsy', 'fa fa-send', 'fa fa-send-o', 'fa fa-server', 'fa fa-share', 'fa fa-share-alt', 'fa fa-share-alt-square', 'fa fa-share-square', 'fa fa-share-square-o', 'fa fa-shekel', 'fa fa-sheqel', 'fa fa-shield', 'fa fa-ship', 'fa fa-shirtsinbulk', 'fa fa-shopping-bag', 'fa fa-shopping-basket', 'fa fa-shopping-cart', 'fa fa-shower', 'fa fa-sign-in', 'fa fa-sign-language', 'fa fa-sign-out', 'fa fa-signal', 'fa fa-signing', 'fa fa-simplybuilt', 'fa fa-sitemap', 'fa fa-skyatlas', 'fa fa-skype', 'fa fa-slack', 'fa fa-sliders', 'fa fa-slideshare', 'fa fa-smile-o', 'fa fa-snapchat', 'fa fa-snapchat-ghost', 'fa fa-snapchat-square', 'fa fa-snowflake-o', 'fa fa-soccer-ball-o', 'fa fa-sort', 'fa fa-sort-alpha-asc', 'fa fa-sort-alpha-desc', 'fa fa-sort-amount-asc', 'fa fa-sort-amount-desc', 'fa fa-sort-asc', 'fa fa-sort-desc', 'fa fa-sort-down', 'fa fa-sort-numeric-asc', 'fa fa-sort-numeric-desc', 'fa fa-sort-up', 'fa fa-soundcloud', 'fa fa-space-shuttle', 'fa fa-spinner', 'fa fa-spoon', 'fa fa-spotify', 'fa fa-square', 'fa fa-square-o', 'fa fa-stack-exchange', 'fa fa-stack-overflow', 'fa fa-star', 'fa fa-star-half', 'fa fa-star-half-empty', 'fa fa-star-half-full', 'fa fa-star-half-o', 'fa fa-star-o', 'fa fa-steam', 'fa fa-steam-square', 'fa fa-step-backward', 'fa fa-step-forward', 'fa fa-stethoscope', 'fa fa-sticky-note', 'fa fa-sticky-note-o', 'fa fa-stop', 'fa fa-stop-circle', 'fa fa-stop-circle-o', 'fa fa-street-view', 'fa fa-strikethrough', 'fa fa-stumbleupon', 'fa fa-stumbleupon-circle', 'fa fa-subscript', 'fa fa-subway', 'fa fa-suitcase', 'fa fa-sun-o', 'fa fa-superpowers', 'fa fa-superscript', 'fa fa-support', 'fa fa-table', 'fa fa-tablet', 'fa fa-tachometer', 'fa fa-tag', 'fa fa-tags', 'fa fa-tasks', 'fa fa-taxi', 'fa fa-telegram', 'fa fa-television', 'fa fa-tencent-weibo', 'fa fa-terminal', 'fa fa-text-height', 'fa fa-text-width', 'fa fa-th', 'fa fa-th-large', 'fa fa-th-list', 'fa fa-themeisle', 'fa fa-thermometer', 'fa fa-thermometer-0', 'fa fa-thermometer-1', 'fa fa-thermometer-2', 'fa fa-thermometer-3', 'fa fa-thermometer-4', 'fa fa-thermometer-empty', 'fa fa-thermometer-full', 'fa fa-thermometer-half', 'fa fa-thermometer-quarter', 'fa fa-thermometer-three-quarters', 'fa fa-thumb-tack', 'fa fa-thumbs-down', 'fa fa-thumbs-o-down', 'fa fa-thumbs-o-up', 'fa fa-thumbs-up', 'fa fa-ticket', 'fa fa-times', 'fa fa-times-circle', 'fa fa-times-circle-o', 'fa fa-times-rectangle', 'fa fa-times-rectangle-o', 'fa fa-tint', 'fa fa-toggle-down', 'fa fa-toggle-left', 'fa fa-toggle-off', 'fa fa-toggle-on', 'fa fa-toggle-right', 'fa fa-toggle-up', 'fa fa-trademark', 'fa fa-train', 'fa fa-transgender', 'fa fa-transgender-alt', 'fa fa-trash', 'fa fa-trash-o', 'fa fa-tree', 'fa fa-trello', 'fa fa-tripadvisor', 'fa fa-trophy', 'fa fa-truck', 'fa fa-try', 'fa fa-tty', 'fa fa-tumblr', 'fa fa-tumblr-square', 'fa fa-turkish-lira', 'fa fa-tv', 'fa fa-twitch', 'fa fa-twitter', 'fa fa-twitter-square', 'fa fa-umbrella', 'fa fa-underline', 'fa fa-undo', 'fa fa-universal-access', 'fa fa-university', 'fa fa-unlink', 'fa fa-unlock', 'fa fa-unlock-alt', 'fa fa-unsorted', 'fa fa-upload', 'fa fa-usb', 'fa fa-usd', 'fa fa-user', 'fa fa-user-circle', 'fa fa-user-circle-o', 'fa fa-user-md', 'fa fa-user-o', 'fa fa-user-plus', 'fa fa-user-secret', 'fa fa-user-times', 'fa fa-users', 'fa fa-vcard', 'fa fa-vcard-o', 'fa fa-venus', 'fa fa-venus-double', 'fa fa-venus-mars', 'fa fa-viacoin', 'fa fa-viadeo', 'fa fa-viadeo-square', 'fa fa-video-camera', 'fa fa-vimeo', 'fa fa-vimeo-square', 'fa fa-vine', 'fa fa-vk', 'fa fa-volume-control-phone', 'fa fa-volume-down', 'fa fa-volume-off', 'fa fa-volume-up', 'fa fa-warning', 'fa fa-wechat', 'fa fa-weibo', 'fa fa-weixin', 'fa fa-whatsapp', 'fa fa-wheelchair', 'fa fa-wheelchair-alt', 'fa fa-wifi', 'fa fa-wikipedia-w', 'fa fa-window-close', 'fa fa-window-close-o', 'fa fa-window-maximize', 'fa fa-window-minimize', 'fa fa-window-restore', 'fa fa-windows', 'fa fa-won', 'fa fa-wordpress', 'fa fa-wpbeginner', 'fa fa-wpexplorer', 'fa fa-wpforms', 'fa fa-wrench', 'fa fa-xing', 'fa fa-xing-square', 'fa fa-y-combinator', 'fa fa-y-combinator-square', 'fa fa-yahoo', 'fa fa-yc', 'fa fa-yc-square', 'fa fa-yelp', 'fa fa-yen', 'fa fa-yoast', 'fa fa-youtube', 'fa fa-youtube-play', 'fa fa-youtube-square'];

            var iconsWindow = 
                '<div tabindex="0" role="dialog" id="icons-modal" style="position: relative;">' + 
                    '<div class="media-modal wp-core-ui">' + 
                        '<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close</span></span></button>' + 
                        '<div class="media-modal-content">' + 
                            '<div class="media-frame mode-select wp-core-ui">' + 
                                '<div class="media-frame-title">' + 
                                    '<h1>Icons</h1>' + 
                                    '<div class="search-icons-wrapper">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-4 search-icons rtl-pull-right">' +
                                                '<span class="fa fa-search"></span>' + 
                                                '<input type="text" id="search-icons-input" class="form-control" placeholder="Search icons">' + 
                                            '</div>' +
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div class="icons-frame-content">' + 
                                        '<div class="icons-modal-subtitle">Font Awesome</div>' + 
                                        '<div class="icons-list icons-list-fa"></div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                    '</div>' + 
                    '<div class="media-modal-backdrop"></div>' + 
                '</div>';

            $('body').append(iconsWindow);

            $('#icons-modal .media-modal-close').on('click', function(e) {
                $('#icons-modal').remove();
                $('#shortcode-modal').focus();
                e.preventDefault();
            });
            $('#icons-modal').on('keyup',function(e) {
                if (e.keyCode == 27) {
                   $(this).remove();
                   $('#shortcode-modal').focus();
                   e.preventDefault();
                }
            });

            var faList = 
                '<div class="row">';
            for (var i = 0; i < faIcons.length; i++) {
                faList += 
                    '<div class="col-xs-6 col-sm-4 col-md-3 is-icon rtl-pull-right">' + 
                        '<div class="icons-item">' + 
                            '<span class="' + faIcons[i] + '"></span> ' + faIcons[i] + 
                        '</div>' + 
                    '</div>';
            }
            faList += 
                '</div>';
            $('#icons-modal .icons-list-fa').append(faList);

            $('#search-icons-input').on('keyup', function() {
                var value = this.value;

                $('.is-icon').each(function(index) {
                    var id = $(this).text();
                    $(this).toggle(id.indexOf(value) !== -1);
                });
            });

            var selected = btn.parent().parent().parent().children('input').val();
            $('.icons-item span').each(function() {
                if ($(this).hasClass(selected)) {
                    $(this).parent().addClass('is-selected');
                }
            });

            $('.icons-item').on('click', function(e) {
                var cName = $(this).find('span').attr('class');

                btn.parent().parent().find('.sh-new-service-img-placeholder').html('<span class="' + cName + '"></span>').css('background-image', 'none').removeClass('has-image');
                btn.parent().parent().parent().children('input').val(cName).attr({
                    'data-isicon' : '1',
                    'data-src'    : ''
                });

                $('#icons-modal').remove();
                $('#shortcode-modal').focus();
                e.preventDefault();
            });

            $('#icons-modal').focus();
        }

        // Open modal
        editor.addCommand('res_services_panel_popup', function(ui, v) {
            var data_content = '';

            if(v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_services_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_services_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_services', {
            image: url + '/../images/services-btn.png',
            tooltip: sh_vars.services_title,
            onclick: function() {
                editor.execCommand('res_services_panel_popup', '', {
                    data_content: '{ "title": "", "subtitle": "", "image": "", "image_src": "", "layout": "1", "margin": "no", "display": "columns", "text_color": "", "cta_label": "", "cta_link": "", "cta_color": "", "services": [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_services_shortcode', {
            text: sh_vars.remove_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-no',
            cmd : 'res_services_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_services_shortcode', {
            text: sh_vars.edit_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-edit',
            cmd : 'res_services_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_services_shortcode',
                    'edit_services_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_services') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_services');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_services') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_services_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);