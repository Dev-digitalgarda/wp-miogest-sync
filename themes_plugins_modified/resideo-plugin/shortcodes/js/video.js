(function($) {
    "use strict";

    tinymce.PluginManager.add('res_video', function(editor, url) {
        var shortcodeTag = 'res_video';
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

            return '<img src="' + placeholder + '" class="mceItem res-video-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_video([^\]]*)\]([^\]]*)\[\/res_video\]/g, function(all, attr, con) {
                return getHTML('wp-res_video', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class="mceItem res-video-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_video_panel_popup', '', {
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
                                    '<h1>' + sh_vars.video_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;" id="sh-video">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-video-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-video-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-video-title">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-video-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="row">' + 
                                                    '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                        '<div class="form-group">' + 
                                                            '<label style="display: block; padding-bottom: 3px;">' + sh_vars.img_label + '</label>' + 
                                                            '<input type="hidden" id="sh-video-image" name="sh-video-image" data-src="' + getObjectProperty(short, "image_src") + '" value="' + getObjectProperty(short, "image") + '">' + 
                                                            '<div class="sh-video-image-placeholder-container';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += 
                                                                    ' has-image'; 
            }
            modalContent += 
                                                                '"><div id="sh-video-image-placeholder" style="background-image: url(';
            if (getObjectProperty(short, "image_src") != '') { 
                modalContent += 
                                                                    getObjectProperty(short, "image_src");
            } else { 
                modalContent += 
                                                                    sh_vars.plugin_url + 'images/image-placeholder.png'; 
            }
            modalContent += 
                                                                ');"></div>'+
                                                                '<div id="delete-video-image"><span class="fa fa-trash-o"></span></div>' +
                                                            '</div>' +
                                                        '</div>' + 
                                                    '</div>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-video-id">' + sh_vars.yt_video_id_label + '</label>' + 
                                                    '<input type="text" id="sh-video-id" class="form-control" value="' + getObjectProperty(short, "video") + '" placeholder="d1EaFyBqH5o">' + 
                                                    '<p class="help" style="margin-top: 5px; font-size: 11px !important;">E.g. <span style="color: #999;">https://www.youtube.com/watch?v=</span><strong style="color: green; font-style: normal;">d1EaFyBqH5o</strong></p>' +
                                                '</div>'+ 
                                                '<div class="row">' + 
                                                    '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                                        '<div class="form-group">' + 
                                                            '<label for="sh-video-margin">' + sh_vars.margin_label + '</label>';
            var margin_is_no = '';
            var margin_is_yes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                margin_is_no = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                margin_is_yes = ' selected="selected"';
            }
            modalContent +=
                                                            '<select class="form-control" id="sh-video-margin">' + 
                                                                '<option value="no"' + margin_is_no + '>' + sh_vars.margin_no + '</option>' + 
                                                                '<option value="yes"' + margin_is_yes + '>' + sh_vars.margin_yes + '</option>' + 
                                                            '</select>' + 
                                                        '</div>'+ 
                                                    '</div>'+ 
                                                    '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                                        '<div class="form-group">' + 
                                                            '<label for="sh-video-text-color" style="display:block;margin-bottom:2px;">' + sh_vars.text_color_label + '</label>' + 
                                                            '<input type="text" id="sh-video-text-color" class="color-field" value="' + getObjectProperty(short, "text_color") + '">' + 
                                                        '</div>'+ 
                                                    '</div>'+ 
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
                    'title'     : $('#sh-video-title').val(),
                    'subtitle'  : $('#sh-video-subtitle').val(),
                    'image'     : $('#sh-video-image').val(),
                    'image_src' : $('#sh-video-image').attr('data-src'),
                    'video'     : $('#sh-video-id').val(),
                    'margin'    : $('#sh-video-margin').val(),
                    'text_color': $('#sh-video-text-color').val()
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('#sh-video-image-placeholder').on('click', function(event) {
                event.preventDefault();

                var frame = wp.media({
                    title: sh_vars.media_video_image_title,
                    button: {
                        text: sh_vars.media_video_image_btn
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').toJSON();
                    $.each(attachment, function(index, value) {
                        $('#sh-video-image').val(value.id).attr('data-src', value.url);
                        $('#sh-video-image-placeholder').css('background-image', 'url(' + value.url + ')');
                        $('.sh-video-image-placeholder-container').addClass('has-image');
                    });
                });

                frame.open();
            });

            $('#delete-video-image').on('click', function() {
                $('#sh-video-image').val('').attr('data-src', '');
                $('#sh-video-image-placeholder').css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)');
                $('.sh-video-image-placeholder-container').removeClass('has-image');
            });

            $('.color-field').wpColorPicker({
                defaultColor: '#ffffff',
            });
        }

        // Open modal
        editor.addCommand('res_video_panel_popup', function(ui, v) {
            var data_content = '';

            if(v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_video_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_video_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_video', {
            image: url + '/../images/video-btn.png',
            tooltip: sh_vars.video_title,
            onclick: function() {
                editor.execCommand('res_video_panel_popup', '', {
                    data_content : '{ "title": "", "subtitle": "", "image": "", "image_src": "", "video": "", "margin": "no", "text_color": "" }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_video_shortcode', {
            text : sh_vars.remove_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-no',
            cmd  : 'res_video_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_video_shortcode', {
            text : sh_vars.edit_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd  : 'res_video_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_video_shortcode',
                    'edit_video_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_video') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_video');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_video') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_video_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);