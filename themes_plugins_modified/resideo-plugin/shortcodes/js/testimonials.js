(function($) {
    "use strict";

    tinymce.PluginManager.add('res_testimonials', function(editor, url) {
        var shortcodeTag = 'res_testimonials';
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

            return '<img src="' + placeholder + '" class="mceItem res-testimonials-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_testimonials([^\]]*)\]([^\]]*)\[\/res_testimonials\]/g, function(all, attr, con) {
                return getHTML('wp-res_testimonials', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class="mceItem res-testimonials-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_testimonials_panel_popup', '', {
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
                                    '<h1>' + sh_vars.testimonials_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;" id="sh-testimonials">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-testimonials-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-testimonials-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-testimonials-title">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-testimonials-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label>' + sh_vars.layout_label + '</label>' + 
                                                    '<div class="layout-radio-container" id="sh-testimonials-layout">' + 
                                                        '<div class="layout-radio layout-radio-testimonials-1"><label class="';
            if (getObjectProperty(short, "layout") == '1' || getObjectProperty(short, "layout") == '') {
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_testimonials_layout" value="1"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-testimonials-2"><label class="';
            var cta_style = '';
            var img_style = '';
            if (getObjectProperty(short, "layout") == '2') {
                cta_style = 'display: none;';
                img_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_testimonials_layout" value="2"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="clearfix"></div>' + 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-testimonials-margin">' + sh_vars.margin_label + '</label>';
            var margin_is_no = '';
            var margin_is_yes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                margin_is_no = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                margin_is_yes = ' selected="selected"';
            }
            modalContent +=
                                                    '<select class="form-control" id="sh-testimonials-margin">' + 
                                                        '<option value="no"' + margin_is_no + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + margin_is_yes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>'+ 
                                                '<div class="form-group is-testim-custom" style="' + cta_style + '">' + 
                                                    '<label for="sh-testimonials-cta-text">' + sh_vars.cta_text_label + '</label>' + 
                                                    '<input type="text" id="sh-testimonials-cta-text" class="form-control" value="' + getObjectProperty(short, "cta_text") + '" placeholder="' + sh_vars.cta_text_placeholder + '">' + 
                                                '</div>'+ 
                                                '<div class="form-group is-testim-custom" style="' + cta_style + '">' + 
                                                    '<label for="sh-testimonials-cta-link">' + sh_vars.cta_link_label + '</label>' + 
                                                    '<input type="text" id="sh-testimonials-cta-link" class="form-control" value="' + getObjectProperty(short, "cta_link") + '" placeholder="' + sh_vars.cta_link_placeholder + '">' + 
                                                '</div>'+ 
                                                '<div class="form-group is-testim-custom" style="' + cta_style + '">' + 
                                                    '<label for="sh-testimonials-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.cta_color_label + '</label>' + 
                                                    '<input type="text" id="sh-testimonials-cta-color" class="color-field" value="' + getObjectProperty(short, "cta_color") + '">' + 
                                                '</div>'+ 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-testimonials-text-color" style="display:block;margin-bottom:2px;">' + sh_vars.text_color_label + '</label>' + 
                                                    '<input type="text" id="sh-testimonials-text-color" class="color-field" value="' + getObjectProperty(short, "text_color") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 

                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right pxp-is-testim-img" style="' + img_style + '">' + 
                                                '<div class="row">' + 
                                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                        '<div class="form-group">' + 
                                                            '<label style="display: block; padding-bottom: 3px;">' + sh_vars.img_label + '</label>' + 
                                                            '<input type="hidden" id="sh-testimonials-image" name="sh-testimonials-image" data-src="' + getObjectProperty(short, "image_src") + '" value="' + getObjectProperty(short, "image") + '">' + 
                                                            '<div class="sh-testimonials-image-placeholder-container';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += 
                                                                    ' has-image'; 
            }
            modalContent += 
                                                                '"><div id="sh-testimonials-image-placeholder" style="background-image: url(';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += 
                                                                    getObjectProperty(short, "image_src");
            } else { 
                modalContent += 
                                                                    sh_vars.plugin_url + 'images/image-placeholder.png'; 
            }
            modalContent += 
                                                                ');"></div>'+
                                                                '<div id="delete-testimonials-image"><span class="fa fa-trash-o"></span></div>' +
                                                            '</div>' +
                                                        '</div>' + 
                                                    '</div>' + 
                                                '</div>' + 
                                            '</div>' + 
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
                    'title'     : $('#sh-testimonials-title').val(),
                    'subtitle'  : $('#sh-testimonials-subtitle').val(),
                    'cta_text'  : $('#sh-testimonials-cta-text').val(),
                    'cta_link'  : $('#sh-testimonials-cta-link').val(),
                    'cta_color' : $('#sh-testimonials-cta-color').val(),
                    'image'     : $('#sh-testimonials-image').val(),
                    'image_src' : $('#sh-testimonials-image').attr('data-src'),
                    'margin'    : $('#sh-testimonials-margin').val(),
                    'text_color': $('#sh-testimonials-text-color').val(),
                    'layout'    : $('#sh-testimonials-layout .layout-active > input').val(),
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('#sh-testimonials-layout label').on('click', function() {
                $('#sh-testimonials-layout label').removeClass('layout-active');
                $(this).addClass('layout-active');

                if ($(this).parent().hasClass('layout-radio-testimonials-1')) {
                    $('.form-group.is-testim-custom').show();
                    $('.pxp-is-testim-img').show();
                } else {
                    $('.form-group.is-testim-custom').hide();
                    $('.pxp-is-testim-img').hide();
                }
            });

            $('#sh-testimonials-image-placeholder').on('click', function(event) {
                event.preventDefault();

                var frame = wp.media({
                    title: sh_vars.media_testimonials_image_title,
                    button: {
                        text: sh_vars.media_testimonials_image_btn
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').toJSON();
                    $.each(attachment, function(index, value) {
                        $('#sh-testimonials-image').val(value.id).attr('data-src', value.url);
                        $('#sh-testimonials-image-placeholder').css('background-image', 'url(' + value.url + ')');
                        $('.sh-testimonials-image-placeholder-container').addClass('has-image');
                    });
                });

                frame.open();
            });

            $('#delete-testimonials-image').on('click', function() {
                $('#sh-testimonials-image').val('').attr('data-src', '');
                $('#sh-testimonials-image-placeholder').css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)');
                $('.sh-testimonials-image-placeholder-container').removeClass('has-image');
            });

            $('.color-field').wpColorPicker({
                defaultColor: '#333333',
            });
        }

        // Open modal
        editor.addCommand('res_testimonials_panel_popup', function(ui, v) {
            var data_content = '';

            if(v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_testimonials_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_testimonials_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_testimonials', {
            image: url + '/../images/testimonials-btn.png',
            tooltip: sh_vars.testimonials_title,
            onclick: function() {
                editor.execCommand('res_testimonials_panel_popup', '', {
                    data_content : '{ "title": "", "subtitle": "", "cta_text": "", "cta_link": "", "cta_color": "", "image": "", "image_src": "", "margin": "no", "text_color": "", "layout": "1" }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_testimonials_shortcode', {
            text : sh_vars.remove_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-no',
            cmd  : 'res_testimonials_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_testimonials_shortcode', {
            text : sh_vars.edit_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd  : 'res_testimonials_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_testimonials_shortcode',
                    'edit_testimonials_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_testimonials') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_testimonials');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_testimonials') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_testimonials_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);