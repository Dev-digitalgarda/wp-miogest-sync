(function($) {
    "use strict";

    tinymce.PluginManager.add('res_featured_agents', function(editor, url) {
        var shortcodeTag = 'res_featured_agents';
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

            return '<img src="' + placeholder + '" class="mceItem res-featured-agents-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_featured_agents([^\]]*)\]([^\]]*)\[\/res_featured_agents\]/g, function(all, attr, con) {
                return getHTML('wp-res_featured_agents', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class="mceItem res-featured-agents-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_featured_agents_panel_popup', '', {
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
                                    '<h1>' + sh_vars.featured_agents_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;" id="sh-featured-agents">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-cta-label">' + sh_vars.cta_text_label + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-cta-label" class="form-control" value="' + getObjectProperty(short, "cta_label") + '" placeholder="' + sh_vars.cta_text_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-4 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-cta-link">' + sh_vars.cta_link_label + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-cta-link" class="form-control" value="' + getObjectProperty(short, "cta_link") + '" placeholder="' + sh_vars.cta_link_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.cta_button_color + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-cta-color" class="color-field" value="' + getObjectProperty(short, "cta_color") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-number">' + sh_vars.agents_number_label + '</label>' + 
                                                    '<input type="number" id="sh-featured-agents-number" class="form-control" value="' + getObjectProperty(short, "number") + '" placeholder="' + sh_vars.agents_number_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-margin">' + sh_vars.margin_label + '</label>';

            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-featured-agents-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-4 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-card-cta-text">' + sh_vars.card_cta_text_label + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-card-cta-text" class="form-control" value="' + getObjectProperty(short, "card_cta") + '" placeholder="' + sh_vars.card_cta_text_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-featured-agents-card-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.agent_card_cta_color + '</label>' + 
                                                    '<input type="text" id="sh-featured-agents-card-cta-color" class="color-field" value="' + getObjectProperty(short, "card_cta_color") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>'+ 
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
                    'title'         : $('#sh-featured-agents-title').val(),
                    'subtitle'      : $('#sh-featured-agents-subtitle').val(),
                    'cta_label'     : $('#sh-featured-agents-cta-label').val(),
                    'cta_link'      : $('#sh-featured-agents-cta-link').val(),
                    'cta_color'     : $('#sh-featured-agents-cta-color').val(),
                    'number'        : $('#sh-featured-agents-number').val(),
                    'margin'        : $('#sh-featured-agents-margin').val(),
                    'card_cta'      : $('#sh-featured-agents-card-cta-text').val(),
                    'card_cta_color': $('#sh-featured-agents-card-cta-color').val(),
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });
        }

        // Open modal
        editor.addCommand('res_featured_agents_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_featured_agents_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_featured_agents_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_featured_agents', {
            image: url + '/../images/featured-agents-btn.png',
            tooltip: sh_vars.featured_agents_title,
            onclick: function() {
                editor.execCommand('res_featured_agents_panel_popup', '', {
                    data_content : '{ "title": "", "subtitle": "", "cta_label": "", "cta_link": "", "cta_color": "#333333", "number": "4", "margin": "no", "card_cta": "", "card_cta_color": "#333333" }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_featured_agents_shortcode', {
            text : sh_vars.remove_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-no',
            cmd  : 'res_featured_agents_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_featured_agents_shortcode', {
            text : sh_vars.edit_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd  : 'res_featured_agents_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_featured_agents_shortcode',
                    'edit_featured_agents_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_featured_agents') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_featured_agents');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_featured_agents') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_featured_agents_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);