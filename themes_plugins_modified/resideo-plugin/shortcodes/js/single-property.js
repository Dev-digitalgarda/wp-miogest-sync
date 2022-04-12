(function($) {
    "use strict";

    tinymce.PluginManager.add('res_single_property', function(editor, url) {
        var shortcodeTag = 'res_single_property';
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

            return '<img src="' + placeholder + '" class="mceItem res-single-property-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_single_property([^\]]*)\]([^\]]*)\[\/res_single_property\]/g, function(all, attr, con) {
                return getHTML('wp-res_single_property', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-single-property-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_single_property_panel_popup', '', {
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
                                    '<h1>' + sh_vars.single_property_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-single-property-name">' + sh_vars.single_property_name_label + '</label>' + 
                                                    '<input type="text" id="sh-single-property-name" class="form-control" value="' + getObjectProperty(short, "name") + '" placeholder="' + sh_vars.single_property_name_placeholder + '">' + 
                                                    '<input type="hidden" id="sh-single-property-id" value="' + getObjectProperty(short, "id") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-single-property-image-position">' + sh_vars.single_property_image_position_label + '</label>';

            var positionLeft   = '';
            var positionRight = '';
            if (getObjectProperty(short, "position") == 'left') {
                positionLeft = ' selected="selected"';
            }
            if (getObjectProperty(short, "position") == 'right') {
                positionRight = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-single-property-image-position">' + 
                                                        '<option value="left"' + positionLeft + '>' + sh_vars.left_label + '</option>' + 
                                                        '<option value="right"' + positionRight + '>' + sh_vars.right_label + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' +
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-single-property-margin">' + sh_vars.margin_label + '</label>';

            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-single-property-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' +
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-single-property-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.cta_button_color + '</label>' + 
                                                    '<input type="text" id="sh-single-property-cta-color" class="color-field" value="' + getObjectProperty(short, "cta_color") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
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
                    'name'     : $('#sh-single-property-name').val(),
                    'id'       : $('#sh-single-property-id').val(),
                    'position' : $('#sh-single-property-image-position').val(),
                    'margin'   : $('#sh-single-property-margin').val(),
                    'cta_color': $('#sh-single-property-cta-color').val()
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('#sh-single-property-name').autocomplete({
                source: function(request, response) {
                    var properties = [];
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sh_vars.ajaxurl,
                        data: {
                            'action': 'resideo_get_autocomplete_properties',
                            'keyword': request.term
                        },
                        success: function(data) {
                            if (data.getprops === true) {
                                properties = [];
                                for (var i = 0; i < data.props.length; i++) {
                                    properties.push({
                                        'label': data.props[i].title,
                                        'value': data.props[i].title,
                                        'id': data.props[i].id
                                    });
                                }
                                response(properties);
                            } else {
                                properties = [];
                                properties.push({
                                    'label': sh_vars.modal_no_properties,
                                    'value': sh_vars.modal_no_properties,
                                    'id': ''
                                });
                                response(properties);
                            }
                        },
                        error: function(errorThrown) {}
                    });
                },
                appendTo: '#shortcode-modal .media-modal-content',
                select: function(event, ui) {
                    $('#sh-single-property-id').val(ui.item.id);
                }
            });
        }

        // Open modal
        editor.addCommand('res_single_property_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_single_property_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_single_property_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_single_property', {
            image: url + '/../images/single-property-btn.png',
            tooltip: sh_vars.single_property_title,
            onclick: function() {
                editor.execCommand('res_single_property_panel_popup', '', {
                    data_content : '{ "name": "", "id": "", "position": "left", "margin": "no", "cta_color": "#333333" }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_single_property_shortcode', {
            text  : sh_vars.remove_btn,
            icon  : 'mce-ico mce-i-dashicon dashicons-no',
            cmd   : 'res_single_property_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_single_property_shortcode', {
            text  : sh_vars.edit_btn,
            icon  : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd   : 'res_single_property_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_single_property_shortcode',
                    'edit_single_property_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_single_property') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_single_property');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_single_property') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_single_property_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });
})(jQuery);