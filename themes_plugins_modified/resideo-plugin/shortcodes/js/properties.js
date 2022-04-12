(function($) {
    "use strict";

    tinymce.PluginManager.add('res_properties', function(editor, url) {
        var shortcodeTag = 'res_properties';
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

            return '<img src="' + placeholder + '" class="mceItem res-properties-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_properties([^\]]*)\]([^\]]*)\[\/res_properties\]/g, function(all, attr, con) {
                return getHTML('wp-res_properties', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class="mceItem res-properties-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_properties_panel_popup', '', {
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
                                    '<h1>' + sh_vars.properties_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;" id="sh-properties">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-properties-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-width">' + sh_vars.width_label + '</label>';

            var width_is_wide = '';
            var width_is_boxed = '';

            if (getObjectProperty(short, "width") == 'wide') {
                width_is_wide = ' selected="selected"';
            }

            if (getObjectProperty(short, "width") == 'boxed') {
                width_is_boxed = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-properties-width">' + 
                                                        '<option value="wide"' + width_is_wide + '>' + sh_vars.width_wide + '</option>' + 
                                                        '<option value="boxed"' + width_is_boxed + '>' + sh_vars.width_boxed + '</option>' + 
                                                    '</select>' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-opacity">' + sh_vars.opacity_label + '</label>' + 
                                                    '<select class="form-control" id="sh-properties-opacity">';
            var opacities = [
                { 'v' : '0', 't' : '0%' },
                { 'v' : '0.1', 't' : '10%' },
                { 'v' : '0.2', 't' : '20%' },
                { 'v' : '0.3', 't' : '30%' },
                { 'v' : '0.4', 't' : '40%' },
                { 'v' : '0.5', 't' : '50%' },
                { 'v' : '0.6', 't' : '60%' },
                { 'v' : '0.7', 't' : '70%' },
                { 'v' : '0.8', 't' : '80%' },
                { 'v' : '0.9', 't' : '90%' },
                { 'v' : '1', 't' : '100%' },
            ];

            $.each(opacities, function(index, value) {
                modalContent += 
                                                        '<option value="' + value.v + '"';
                if(getObjectProperty(short, "opacity") == value.v) {
                    modalContent += 
                                                            ' selected="selected"';
                }
                modalContent += 
                                                        '>' + value.t + '</option>';
            });
            modalContent += 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-autoslide">' + sh_vars.autoslide_label + '</label>';

            var autoslide_is_no = '';
            var autoslide_is_yes = '';

            if (getObjectProperty(short, "autoslide") == 'no') {
                autoslide_is_no = ' selected="selected"';
            }

            if (getObjectProperty(short, "autoslide") == 'yes') {
                autoslide_is_yes = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-properties-autoslide">' + 
                                                        '<option value="no"' + autoslide_is_no + '>' + sh_vars.autoslide_no + '</option>' + 
                                                        '<option value="yes"' + autoslide_is_yes + '>' + sh_vars.autoslide_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-interval">' + sh_vars.interval_label + '</label><br>' + 
                                                    '<input type="number" min="0" id="sh-properties-interval" value="' + getObjectProperty(short, "interval") + '" placeholder="0" style="width: 41%;">&nbsp;' + sh_vars.seconds_label + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-transition">' + sh_vars.transition_label + '</label>';

            var transition_is_slide = '';
            var transition_is_fade = '';

            if (getObjectProperty(short, "transition") == 'slide') {
                transition_is_slide = ' selected="selected"';
            }

            if (getObjectProperty(short, "transition") == 'fade') {
                transition_is_fade = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-properties-transition">' + 
                                                        '<option value="slide"' + transition_is_slide + '>' + sh_vars.transition_slide + '</option>' + 
                                                        '<option value="fade"' + transition_is_fade + '>' + sh_vars.transition_fade + '</option>' + 
                                                    '</select>' + 
                                                '</div>'+ 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-sm-6 col-md-3 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-properties-margin">' + sh_vars.margin_label + '</label>';

            var margin_is_no = '';
            var margin_is_yes = '';

            if (getObjectProperty(short, "margin") == 'no') {
                margin_is_no = ' selected="selected"';
            }

            if (getObjectProperty(short, "margin") == 'yes') {
                margin_is_yes = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-properties-margin">' + 
                                                        '<option value="no"' + margin_is_no + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + margin_is_yes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>'+ 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.properties_list + '</div>' + 
                                        '<ul class="list-group" id="properties-list">';

            var properties = getObjectProperty(short, "properties");

            if ($.isArray(properties)) {
                if (properties.length > 0) {
                    $.each(properties, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem" data-id="' + elem.id + '">' + 
                                                    '<div class="list-group-item-img" data-src="' + elem.src + '">' + 
                                                        '<div class="list-group-item-img-container" style="background-image: url(' + elem.src + ');"></div>' + 
                                                    '</div>' + 
                                                    '<div class="list-group-item-info">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="property-title">' + elem.title + '</span>' + 
                                                        '</div>' + 
                                                        '<div class="list-group-item-info-caption">' + 
                                                            '<span class="property-address">' + elem.address + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-property"><span class="fa fa-trash-o"></span></a>' + 
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
                properties = [];
            }

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-property" class="button media-button button-default">' + sh_vars.add_property_btn + '</button>' + 
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

            $('#add-property').click(function() {
                var modal = 
                    '<div tabindex="0" role="dialog" id="properties-modal" class="custom-modal">' + 
                        '<div class="media-modal wp-core-ui">' + 
                            '<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>' + 
                            '<div class="media-modal-content">' + 
                                '<div class="media-frame mode-select wp-core-ui" id="">' + 
                                    '<div class="media-frame-title">' + 
                                        '<h1>' + sh_vars.modal_properties + '</h1>' + 
                                        '<div class="search-items-wrapper">' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-sm-12 col-md-4 search-items rtl-pull-right">' + 
                                                    '<span class="fa fa-search"></span><input type="text" id="search-props-input" class="form-control search-items-input" placeholder="' + sh_vars.search_properties + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                    '</div>' + 
                                    '<div class="media-frame-content">' +
                                        '<div class="properties-modal-subtitle"><span class="properties-modal-total">--</span> ' + sh_vars.modal_properties_results + '</div>' + 
                                        '<div id="properties-modal-list" class="row"></div>' + 
                                        '<div class="properties-modal-list-more-container">' + 
                                            '<button type="button" id="properties-modal-list-more" class="button media-button button-default button-large"><img src="' + sh_vars.plugin_url + 'images/loader-dark.svg"><span>' + sh_vars.load_more_properties + '</span></button>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                        '<div class="media-modal-backdrop"></div>' + 
                    '</div>';

                $('body').append(modal);

                var pageNumber = 0;

                loadProperties('', true);

                function loadProperties(keyword, change_total) {
                    var ajaxURL = sh_vars.ajaxurl;
                    var security = $('#security-modal-props').val();

                    pageNumber++;

                    if (change_total === true) {
                        $('.properties-modal-total').text('--');
                    }

                    $('#properties-modal-list-more').show();
                    $('#properties-modal-list-more').prop('disabled', true);
                    $('#properties-modal-list-more img').show();
                    $('#properties-modal-list-more span').hide();

                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: ajaxURL,
                        data: {
                            'action'   : 'res_get_modal_properties',
                            'security' : security,
                            'page_no'  : pageNumber,
                            'keyword'  : keyword
                        },
                        success: function(data) {
                            if (data.getprops === true) {
                                var list = '';

                                $('.properties-modal-total').text(data.total);

                                $.each(data.props, function(i, prop) {
                                    var imgSrc = (prop.photo === false) ? sh_vars.plugin_url + 'images/image-placeholder.png' : prop.photo[0];

                                    list += '<div class="col-xs-6 col-sm-4 col-md-3 is-modal-prop rtl-pull-right">' + 
                                                '<div class="modal-properties-item" data-id="' + prop.id + '">' + 
                                                    '<img src="' + imgSrc + '">' + 
                                                    '<div class="modal-properties-item-info">' + 
                                                        '<div class="modal-properties-item-info-title">' + prop.title + '</div>' +
                                                        '<div class="modal-properties-item-info-address">' + prop.address + '</div>' +
                                                    '</div>' + 
                                                    '<div class="clearfix"></div>' + 
                                                '</div>' + 
                                            '</div>';
                                });

                                $('#properties-modal-list').append(list);

                                $('#properties-modal-list-more').prop('disabled', false);
                                $('#properties-modal-list-more img').hide();
                                $('#properties-modal-list-more span').show();

                                if ($('.modal-properties-item').length == data.total) {
                                    $('#properties-modal-list-more').hide();
                                }
                            } else {
                                $('#properties-modal-list').append('<div class="col-xs-12 rtl-pull-right">' + sh_vars.modal_no_properties + '</div>');
                                $('#properties-modal-list-more').hide();
                            }
                        },
                        error: function(errorThrown) {}
                    });
                }

                $('#properties-modal .media-modal-close').on('click', function(e) {
                    $('#properties-modal').remove();
                    e.preventDefault();
                });
                $('#properties-modal #cancel-p-btn').on('click', function(e) {
                    $('#properties-modal').remove();
                    e.preventDefault();
                });
                $('#properties-modal').on('keyup',function(e) {
                    if (e.keyCode == 27) {
                       $(this).remove();
                       e.preventDefault();
                    }
                });
                $('#properties-modal-list-more').on('click',function(e) {
                    loadProperties($('#search-props-input').val(), false);
                });

                var timeout;
                $('#properties-modal').on('keyup', '#search-props-input', function() {
                    var _self = $(this);

                    window.clearTimeout(timeout);
                    $('#properties-modal-list').empty();
                    pageNumber = 0;

                    timeout = window.setTimeout(function() {
                        loadProperties(_self.val(), true);
                    }, 500);
                });

                $('#properties-modal').on('click', '.modal-properties-item', function(e) {
                    var id      = $(this).attr('data-id');
                    var image   = $(this).find('img').attr('src');
                    var title   = $(this).find('.modal-properties-item-info-title').text();
                    var address = $(this).find('.modal-properties-item-info-address').text();

                    properties.push({
                        'id'      : id,
                        'src'     : image,
                        'title'   : title,
                        'address' : address,
                    });

                    $('#properties-list').append(
                        '<li class="list-group-item">' + 
                            '<div class="list-group-item-elem" data-id="' + id + '">' + 
                                '<div class="list-group-item-img" data-src="' + image + '">' + 
                                    '<div class="list-group-item-img-container" style="background-image: url(' + image + ');"></div>' + 
                                '</div>' + 
                                '<div class="list-group-item-info">' + 
                                    '<div class="list-group-item-info-title">' + 
                                        '<span class="property-title">' + title + '</span>' + 
                                    '</div>' + 
                                    '<div class="list-group-item-info-caption">' + 
                                        '<span class="property-address">' + address + '</span>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<a href="javascript:void(0);" class="pull-right del-btn del-property"><span class="fa fa-trash-o"></span></a>' + 
                            '</div>' + 
                        '<li>'
                    );

                    $('#properties-list .sortcode-modal-empty').remove();

                    $('#properties-modal').remove();
                });
            });

            $('#properties-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    properties = [];

                    $('#properties-list .list-group-item').each(function() {
                        var id      = $(this).find('.list-group-item-elem').attr('data-id');
                        var image   = $(this).find('.list-group-item-img').attr('data-src');
                        var title   = $(this).find('.property-title').text();
                        var address = $(this).find('.property-address').text();

                        properties.push({
                            'id'      : id,
                            'src'     : image,
                            'title'   : title,
                            'address' : address,
                        });
                    });
                }
            }).disableSelection();

            $(document).on('click', '#properties-list .del-property', function(event) {
                $(this).parent().parent().remove();

                properties = [];

                $('#properties-list .list-group-item').each(function() {
                    var id      = $(this).find('.list-group-item-elem').attr('data-id');
                    var image   = $(this).find('.list-group-item-img').attr('data-src');
                    var title   = $(this).find('.property-title').text();
                    var address = $(this).find('.property-address').text();

                    properties.push({
                        'id'      : id,
                        'src'     : image,
                        'title'   : title,
                        'address' : address,
                    });
                });

                if (properties.length <= 0) {
                    $('#properties-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>');
                }
            });

            $('#shortcode-modal #insert-button').on('click', function(e) {
                var shortVal = {
                    'title'      : $('#sh-properties-title').val(),
                    'autoslide'  : $('#sh-properties-autoslide').val(),
                    'interval'   : $('#sh-properties-interval').val(),
                    'transition' : $('#sh-properties-transition').val(),
                    'margin'     : $('#sh-properties-margin').val(),
                    'opacity'    : $('#sh-properties-opacity').val(),
                    'width'      : $('#sh-properties-width').val(),
                    'properties' : properties
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
        editor.addCommand('res_properties_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_properties_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_properties_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_properties', {
            image: url + '/../images/properties-btn.png',
            tooltip: sh_vars.properties_title,
            onclick: function() {
                editor.execCommand('res_properties_panel_popup', '', {
                    data_content : '{ "title" : "", "width" : "wide", "opacity" : "0", "autoslide" : "no", "interval" : "", "transition" : "slide", "margin" : "no", "properties" : [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_properties_shortcode', {
            text  : sh_vars.remove_btn,
            icon  : 'mce-ico mce-i-dashicon dashicons-no',
            cmd   : 'res_properties_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_properties_shortcode', {
            text  : sh_vars.edit_btn,
            icon  : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd   : 'res_properties_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_properties_shortcode',
                    'edit_properties_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_properties') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_properties');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_properties') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_properties_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);