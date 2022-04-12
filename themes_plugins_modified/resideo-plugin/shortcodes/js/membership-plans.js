(function($) {
    "use strict";

    tinymce.PluginManager.add('res_membership_plans', function(editor, url) {
        var shortcodeTag = 'res_membership_plans';
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

            return '<img src="' + placeholder + '" class="mceItem res-membership-plans-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_membership_plans([^\]]*)\]([^\]]*)\[\/res_membership_plans\]/g, function(all, attr, con) {
                return getHTML('wp-res_membership_plans', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class="mceItem res-membership-plans-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_membership_plans_panel_popup', '', {
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
                                    '<h1>' + sh_vars.membership_plans_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;" id="sh-membership-plans">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' +
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-4 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-margin">' + sh_vars.margin_label + '</label>';

            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-membership-plans-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' +  
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-title-color" style="display:block;margin-bottom:2px;">' + sh_vars.plans_title_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-title-color" class="color-field" value="' + getObjectProperty(short, "title_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-price-color" style="display:block;margin-bottom:2px;">' + sh_vars.plans_price_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-price-color" class="color-field" value="' + getObjectProperty(short, "price_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.plans_cta_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-cta-color" class="color-field" value="' + getObjectProperty(short, "cta_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-featured-title-color" style="display:block;margin-bottom:2px;">' + sh_vars.featured_plan_title_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-featured-title-color" class="color-field" value="' + getObjectProperty(short, "featured_title_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-featured-price-color" style="display:block;margin-bottom:2px;">' + sh_vars.featured_plan_price_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-featured-price-color" class="color-field" value="' + getObjectProperty(short, "featured_price_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-featured-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.featured_plan_cta_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-featured-cta-color" class="color-field" value="' + getObjectProperty(short, "featured_cta_color") + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-membership-plans-featured-label-color" style="display:block;margin-bottom:2px;">' + sh_vars.featured_plan_label_color + '</label>' + 
                                                    '<input type="text" id="sh-membership-plans-featured-label-color" class="color-field" value="' + getObjectProperty(short, "featured_label_color") + '">' + 
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

            $('.color-field').wpColorPicker({
                defaultColor: '#333333',
            });

            $('#shortcode-modal #insert-button').on('click', function(e) {
                var shortVal = {
                    'title'               : $('#sh-membership-plans-title').val(),
                    'subtitle'            : $('#sh-membership-plans-subtitle').val(),
                    'margin'              : $('#sh-membership-plans-margin').val(),
                    'title_color'         : $('#sh-membership-plans-title-color').val(),
                    'price_color'         : $('#sh-membership-plans-price-color').val(),
                    'cta_color'           : $('#sh-membership-plans-cta-color').val(),
                    'featured_title_color': $('#sh-membership-plans-featured-title-color').val(),
                    'featured_price_color': $('#sh-membership-plans-featured-price-color').val(),
                    'featured_cta_color'  : $('#sh-membership-plans-featured-cta-color').val(),
                    'featured_label_color': $('#sh-membership-plans-featured-label-color').val(),
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
        editor.addCommand('res_membership_plans_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_membership_plans_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_membership_plans_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_membership_plans', {
            image: url + '/../images/membership-plans-btn.png',
            tooltip: sh_vars.membership_plans_title,
            onclick: function() {
                editor.execCommand('res_membership_plans_panel_popup', '', {
                    data_content : '{ "title" : "", "subtitle" : "", "margin" : "no", "title_color": "#333333", "price_color": "#333333", "cta_color": "#333333", "featured_title_color": "#333333", "featured_price_color": "#333333", "featured_cta_color": "#333333", "featured_label_color": "#333333" }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_membership_plans_shortcode', {
            text : sh_vars.remove_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-no',
            cmd  : 'res_membership_plans_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_membership_plans_shortcode', {
            text : sh_vars.edit_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd  : 'res_membership_plans_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_membership_plans_shortcode',
                    'edit_membership_plans_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_membership_plans') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_membership_plans');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_membership_plans') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_membership_plans_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);