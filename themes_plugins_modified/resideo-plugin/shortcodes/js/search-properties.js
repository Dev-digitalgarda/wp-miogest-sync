(function($) {
    "use strict";

    tinymce.PluginManager.add('res_search_properties', function(editor, url) {
        var shortcodeTag = 'res_search_properties';
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

            return '<img src="' + placeholder + '" class="mceItem res-search-properties-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_search_properties([^\]]*)\]([^\]]*)\[\/res_search_properties\]/g, function(all, attr, con) {
                return getHTML('wp-res_search_properties', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class="mceItem res-search-properties-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_search_properties_panel_popup', '', {
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
                                    '<h1>' + sh_vars.search_properties_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;" id="sh-search-properties">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-5 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-search-properties-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-5 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-search-properties-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-align">' + sh_vars.align_label + '</label>';
                                                    var alignLeft   = '';
                                                    var alignCenter = '';
                                                    if (getObjectProperty(short, "align") == 'left') {
                                                        alignLeft = ' selected="selected"';
                                                    }
                                                    if (getObjectProperty(short, "align") == 'center') {
                                                        alignCenter = ' selected="selected"';
                                                    }
                                                    modalContent += '<select class="form-control" id="sh-search-properties-align">' + 
                                                        '<option value="left"' + alignLeft + '>' + sh_vars.left_label + '</option>' + 
                                                        '<option value="center"' + alignCenter + '>' + sh_vars.center_label + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' +
                                        '<div class="sortcode-modal-subtitle" style="padding-bottom: 10px;">' + sh_vars.fields_list_label + '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-status">';
                                                        var statusChecked = '';
                                                        if (getObjectProperty(short, "status") == '1') {
                                                            statusChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-status"' + statusChecked + '>' + sh_vars.sp_status_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-address">';
                                                        var addressChecked = '';
                                                        if (getObjectProperty(short, "address") == '1') {
                                                            addressChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-address"' + addressChecked + '>' + sh_vars.sp_address_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-city">';
                                                        var cityChecked = '';
                                                        if (getObjectProperty(short, "city") == '1') {
                                                            cityChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-city"' + cityChecked + '>' + sh_vars.sp_city_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-neighborhood">';
                                                        var neighborhoodChecked = '';
                                                        if (getObjectProperty(short, "neighborhood") == '1') {
                                                            neighborhoodChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-neighborhood"' + neighborhoodChecked + '>' + sh_vars.sp_neighborhood_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-state">';
                                                        var stateChecked = '';
                                                        if (getObjectProperty(short, "state") == '1') {
                                                            stateChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-state"' + stateChecked + '>' + sh_vars.sp_state_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-type">';
                                                        var typeChecked = '';
                                                        if (getObjectProperty(short, "type") == '1') {
                                                            typeChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-type"' + typeChecked + '>' + sh_vars.sp_type_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-price">';
                                                        var priceChecked = '';
                                                        if (getObjectProperty(short, "price") == '1') {
                                                            priceChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-price"' + priceChecked + '>' + sh_vars.sp_price_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-beds">';
                                                        var bedsChecked = '';
                                                        if (getObjectProperty(short, "beds") == '1') {
                                                            bedsChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-beds"' + bedsChecked + '>' + sh_vars.sp_beds_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-baths">';
                                                        var bathsChecked = '';
                                                        if (getObjectProperty(short, "baths") == '1') {
                                                            bathsChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-baths"' + bathsChecked + '>' + sh_vars.sp_baths_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-size">';
                                                        var sizeChecked = '';
                                                        if (getObjectProperty(short, "size") == '1') {
                                                            sizeChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-size"' + sizeChecked + '>' + sh_vars.sp_size_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-keywords">';
                                                        var keywordsChecked = '';
                                                        if (getObjectProperty(short, "keywords") == '1') {
                                                            keywordsChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-keywords"' + keywordsChecked + '>' + sh_vars.sp_keywords_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-id">';
                                                        var idChecked = '';
                                                        if (getObjectProperty(short, "id") == '1') {
                                                            idChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-id"' + idChecked + '>' + sh_vars.sp_id_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-search-properties-amenities">';
                                                        var amenitiesChecked = '';
                                                        if (getObjectProperty(short, "amenities") == '1') {
                                                            amenitiesChecked = ' checked="checked"';
                                                        }
                                                        modalContent += '<input type="checkbox" id="sh-search-properties-amenities"' + amenitiesChecked + '>' + sh_vars.sp_amenities_label + 
                                                    '</label>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle" style="padding-bottom: 10px;">' + sh_vars.custom_fields_list_label + ' <span class="fa fa-spin fa-spinner" id="sh-search-properties-custom-load"></span></div>' + 
                                        '<div class="row" id="sh-search-properties-custom"></div>' + 
                                        '<div class="sortcode-modal-subtitle" style="padding-bottom: 10px;">' + sh_vars.limit_main_fields_label + '</div>' +
                                        '<div class="form-group">' + 
                                            '<label for="sh-search-properties-limit">' + sh_vars.fields_display_label + '</label> <input type="number" id="sh-search-properties-limit" min="0" value="' + getObjectProperty(short, "limit") + '" style="width: 46px;" placeholder="0"> ' + sh_vars.fields_main_area_label + 
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

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: sh_vars.ajaxurl,
                data: {
                    'action': 'resideo_get_custom_fields_settings'
                },
                success: function(data) {
                    var cFields = '';
                    if (data.getfields === true) {
                        for (var field in data.fields) {
                            cFields +=  '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                            '<div class="form-group">' + 
                                                '<label for="sh-search-properties-' + field + '">';
                                                    var fieldChecked = '';
                                                    if (getObjectProperty(short, field) == '1') {
                                                        fieldChecked = ' checked="checked"';
                                                    }
                                                    cFields += '<input type="checkbox" class="custom-field-input" id="sh-search-properties-' + field + '"' + fieldChecked + ' data-field="' + field + '">' + data.fields[field].label +
                                                '</label>' + 
                                            '</div>' + 
                                        '</div>';
                        }
                    }
                    $('#sh-search-properties-custom-load').hide();
                    $('#sh-search-properties-custom').html(cFields);
                },
                error: function(errorThrown) {}
            });

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
                    'title'       : $('#sh-search-properties-title').val(),
                    'subtitle'    : $('#sh-search-properties-subtitle').val(),
                    'align'       : $('#sh-search-properties-align').val(),
                    'id'          : $('#sh-search-properties-id').is(':checked') ? '1' : '0',
                    'address'     : $('#sh-search-properties-address').is(':checked') ? '1' : '0',
                    'city'        : $('#sh-search-properties-city').is(':checked') ? '1' : '0',
                    'neighborhood': $('#sh-search-properties-neighborhood').is(':checked') ? '1' : '0',
                    'state'       : $('#sh-search-properties-state').is(':checked') ? '1' : '0',
                    'price'       : $('#sh-search-properties-price').is(':checked') ? '1' : '0',
                    'size'        : $('#sh-search-properties-size').is(':checked') ? '1' : '0',
                    'beds'        : $('#sh-search-properties-beds').is(':checked') ? '1' : '0',
                    'baths'       : $('#sh-search-properties-baths').is(':checked') ? '1' : '0',
                    'type'        : $('#sh-search-properties-type').is(':checked') ? '1' : '0',
                    'status'      : $('#sh-search-properties-status').is(':checked') ? '1' : '0',
                    'keywords'    : $('#sh-search-properties-keywords').is(':checked') ? '1' : '0',
                    'amenities'   : $('#sh-search-properties-amenities').is(':checked') ? '1' : '0',
                    'limit'       : $('#sh-search-properties-limit').val(),
                }

                $('.custom-field-input').each(function() {
                    var field = $(this).attr('data-field');
                    shortVal[field] = $(this).is(':checked') ? '1' : '0';
                });

                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });
        }

        // Open modal
        editor.addCommand('res_search_properties_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_search_properties_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_search_properties_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_search_properties', {
            image: url + '/../images/search-properties-btn.png',
            tooltip: sh_vars.search_properties_title,
            onclick: function() {
                editor.execCommand('res_search_properties_panel_popup', '', {
                    data_content : '{ "title": "", "subtitle": "", "align": "left", "id": "", "address": "", "city": "", "neighborhood": "", "state": "", "price": "", "size": "", "beds": "", "baths": "", "type": "", "status": "", "keywords": "", "limit": "2", "amenities": "" }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_search_properties_shortcode', {
            text : sh_vars.remove_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-no',
            cmd  : 'res_search_properties_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_search_properties_shortcode', {
            text : sh_vars.edit_btn,
            icon : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd  : 'res_search_properties_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_search_properties_shortcode',
                    'edit_search_properties_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_search_properties') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_search_properties');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_search_properties') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_search_properties_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });
})(jQuery);