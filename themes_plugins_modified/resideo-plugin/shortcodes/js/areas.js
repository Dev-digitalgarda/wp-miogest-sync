(function($) {
    "use strict";

    tinymce.PluginManager.add('res_areas', function(editor, url) {
        var shortcodeTag = 'res_areas';
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

            return '<img src="' + placeholder + '" class="mceItem res-areas-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_areas([^\]]*)\]([^\]]*)\[\/res_areas\]/g, function(all, attr, con) {
                return getHTML('wp-res_areas', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-areas-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_areas_panel_popup', '', {
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
                                    '<h1>' + sh_vars.areas_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-areas-title">' + sh_vars.title_label + '</label>' + 
                                                    '<input type="text" id="sh-areas-title" class="form-control" value="' + getObjectProperty(short, "title") + '" placeholder="' + sh_vars.title_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-areas-subtitle">' + sh_vars.subtitle_label + '</label>' + 
                                                    '<input type="text" id="sh-areas-subtitle" class="form-control" value="' + getObjectProperty(short, "subtitle") + '" placeholder="' + sh_vars.subtitle_placeholder + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-areas-cta-label">' + sh_vars.cta_text_label + '</label>' + 
                                                    '<input type="text" id="sh-areas-cta-label" class="form-control" value="' + getObjectProperty(short, "cta_label") + '" placeholder="' + sh_vars.cta_text_placeholder + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                            '<div class="col-xs-12 col-md-4 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-areas-cta-link">' + sh_vars.cta_link_label + '</label>' + 
                                                    '<input type="text" id="sh-areas-cta-link" class="form-control" value="' + getObjectProperty(short, "cta_link") + '" placeholder="' + sh_vars.cta_link_placeholder + '">' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-2 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-areas-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.cta_button_color + '</label>' + 
                                                    '<input type="text" id="sh-areas-cta-color" class="color-field" value="' + getObjectProperty(short, "cta_color") + '">' + 
                                                '</div>'+ 
                                            '</div>'+ 
                                        '</div>' + 
                                        '<div class="row">' + 
                                            '<div class="col-xs-12 col-md-6 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label>' + sh_vars.layout_label + '</label>' + 
                                                    '<div class="layout-radio-container" id="sh-areas-layout">' + 
                                                        '<div class="layout-radio layout-radio-areas-1"><label class="';
            if (getObjectProperty(short, "layout") == '1' || getObjectProperty(short, "layout") == '') {
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_areas_layout" value="1"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="layout-radio layout-radio-areas-2"><label class="';
            var caption_position_style = '';
            if (getObjectProperty(short, "layout") == '2') {
                caption_position_style = 'display: none;';
                modalContent += 
                                                            'layout-active';
            }
            modalContent += 
                                                        '"><input type="radio" name="sh_areas_layout" value="2"><span><span class="fa fa-check"></span></span></label></div>' + 
                                                        '<div class="clearfix"></div>' + 
                                                    '</div>'+ 
                                                '</div>'+ 
                                            '</div>' + 
                                            '<div class="col-xs-12 col-md-4 rtl-pull-right">' + 
                                                '<div class="form-group">' + 
                                                    '<label for="sh-areas-margin">' + sh_vars.margin_label + '</label>';

            var marginNo   = '';
            var marginYes = '';
            if (getObjectProperty(short, "margin") == 'no') {
                marginNo = ' selected="selected"';
            }
            if (getObjectProperty(short, "margin") == 'yes') {
                marginYes = ' selected="selected"';
            }

            modalContent += 
                                                    '<select class="form-control" id="sh-areas-margin">' + 
                                                        '<option value="no"' + marginNo + '>' + sh_vars.margin_no + '</option>' + 
                                                        '<option value="yes"' + marginYes + '>' + sh_vars.margin_yes + '</option>' + 
                                                    '</select>' + 
                                                '</div>' + 
                                            '</div>' +
                                        '</div>' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.areas_list + '</div>' + 
                                        '<ul class="list-group" id="areas-list">';

            var areas = getObjectProperty(short, "areas");

            if ($.isArray(areas)) {
                if (areas.length > 0) {
                    $.each(areas, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem">' + 
                                                    '<div class="list-group-item-img" data-src="' + elem.src + '" data-id="' + elem.id + '">' + 
                                                        '<div class="list-group-item-img-container" style="background-image: url(' + elem.src + ');"></div>' + 
                                                    '</div>' + 
                                                    '<div class="list-group-item-info" data-ctacolor="' + elem.cta_color + '">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="area-neighborhood" data-id="' + elem.neighborhood_id + '">' + elem.neighborhood + '</span>' + 
                                                        '</div>' + 
                                                        '<div class="list-group-item-info-caption">' + 
                                                            '<span class="area-city" data-id="' + elem.city_id + '">' + elem.city + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-area"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<a href="javascript:void(0);" class="pull-right edit-btn edit-area"><span class="fa fa-pencil"></span></a>' + 
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
                areas = [];
            }

            var areas_cities_list = sh_vars.areas_cities_list.replace(/\+/g, '%20');
            areas_cities_list = areas_cities_list != '' ? $.parseJSON(decodeURIComponent(areas_cities_list)) : [];

            var areas_neighborhoods_list = sh_vars.areas_neighborhoods_list.replace(/\+/g, '%20');
            areas_neighborhoods_list = areas_neighborhoods_list != '' ? $.parseJSON(decodeURIComponent(areas_neighborhoods_list)) : [];

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-area" class="button media-button button-default">' + sh_vars.add_area_btn + '</button>' + 
                                        '<div class="shortcode-modal-new-container" style="display: none;">' + 
                                            '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.new_area_header + '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                                    '<input type="hidden" id="sh-new-area-img" data-src="">' + 
                                                    '<div class="sh-new-area-img-container">' + 
                                                        '<div class="sh-new-area-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>' + 
                                                        '<div class="sh-new-area-add-btns">' + 
                                                            '<div class="sh-new-area-add-img">' + sh_vars.area_add_img + '</div>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                '</div>' + 
                                                '<div class="col-xs-12 col-sm-12 col-md-9 rtl-pull-right">' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-area-neighborhood">' + sh_vars.area_neighborhood_label + '</label>';

            if (sh_vars.areas_neighborhood_type == 'list') {
                modalContent += 
                                                                '<select id="sh-new-area-neighborhood" class="form-control">' + 
                                                                    '<option value="">' + sh_vars.areas_select_neighborhood + '</option>';
                $.each(areas_neighborhoods_list, function(index, value) {
                    modalContent += 
                                                                    '<option value="' + value.id + '">' + value.name + '</option>';
                });

                modalContent += 
                                                                '</select>';
            } else {
                modalContent += 
                                                                '<input type="text" id="sh-new-area-neighborhood" class="form-control" placeholder="' + sh_vars.area_neighborhood_placeholder + '">';
            }

            modalContent += 
                                                            '</div>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-area-city">' + sh_vars.area_city_label + '</label>'; 
            if (sh_vars.areas_city_type == 'list') {
                modalContent += 
                                                                '<select id="sh-new-area-city" class="form-control">' + 
                                                                    '<option value="">' + sh_vars.areas_select_city + '</option>';
                $.each(areas_cities_list, function(index, value) {
                    modalContent += 
                                                                    '<option value="' + value.id + '">' + value.name + '</option>';
                });

                modalContent += 
                                                                '</select>';
            } else {
                modalContent += 
                                                                '<input type="text" id="sh-new-area-city" class="form-control" placeholder="' + sh_vars.area_city_placeholder + '">';
            }
            modalContent += 
                                                            '</div>' +
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                                            '<div class="form-group">' + 
                                                                '<label for="sh-new-area-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.area_cta_color + '</label>' + 
                                                                '<input type="text" id="sh-new-area-cta-color" class="color-field" value="#333333">' + 
                                                            '</div>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' + 
                                            '</div>' + 
                                            '<button type="button" id="ok-area" class="button media-button button-primary">' + sh_vars.ok_area_btn + '</button>' + 
                                            '<button type="button" id="cancel-area" class="button media-button button-default">' + sh_vars.cancel_area_btn + '</button>' + 
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
                    'title'    : $('#sh-areas-title').val(),
                    'subtitle' : $('#sh-areas-subtitle').val(),
                    'cta_label': $('#sh-areas-cta-label').val(),
                    'cta_link' : $('#sh-areas-cta-link').val(),
                    'cta_color': $('#sh-areas-cta-color').val(),
                    'margin'   : $('#sh-areas-margin').val(),
                    'layout'   : $('#sh-areas-layout .layout-active > input').val(),
                    'areas'    : areas
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('#sh-areas-layout label').on('click', function() {
                $('#sh-areas-layout label').removeClass('layout-active');
                $(this).addClass('layout-active');
            });

            $('.sh-new-area-add-img').on('click', function(event) {
                openMediaLibrary($(this));
            });

            $('#add-area').on('click', function(event) {
                $(this).hide();
                $('.shortcode-modal-new-container').show();
            });

            $('#cancel-area').on('click', function(event) {
                $('.shortcode-modal-new-container').hide();
                $('.sh-new-area-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-area-img').val('').attr({
                    'data-src'    : '',
                });
                $('#sh-new-area-neighborhood').val('');
                $('#sh-new-area-city').val('');
                $('#sh-new-area-cta-color').wpColorPicker('color', '#333333');
                $('#add-area').show();
            });

            $('#ok-area').on('click', function(event) {
                var newImgSrc = $('#sh-new-area-img').attr('data-src');
                var newImgID  = $('#sh-new-area-img').val();
                var newCTAColor = $('#sh-new-area-cta-color').val();

                if (sh_vars.areas_neighborhood_type == 'list') {
                    var newNeighbourhood   = $('#sh-new-area-neighborhood option:selected').val() != '' ? $('#sh-new-area-neighborhood option:selected').text() : '' ;
                    var newNeighbourhoodID = $('#sh-new-area-neighborhood').val();
                } else {
                    var newNeighbourhood   = $('#sh-new-area-neighborhood').val();
                    var newNeighbourhoodID = '';
                }

                if (sh_vars.areas_city_type == 'list') {
                    var newCity   = $('#sh-new-area-city option:selected').val() != '' ? $('#sh-new-area-city option:selected').text() : '';
                    var newCityID = $('#sh-new-area-city').val();
                } else {
                    var newCity   = $('#sh-new-area-city').val();
                    var newCityID = '';
                }

                areas.push({
                    'src'             : newImgSrc,
                    'id'              : newImgID,
                    'neighborhood'    : newNeighbourhood,
                    'neighborhood_id' : newNeighbourhoodID,
                    'city'            : newCity,
                    'city_id'         : newCityID,
                    'cta_color'       : newCTAColor
                });

                var newArea = 
                    '<li class="list-group-item">' + 
                        '<div class="list-group-item-elem">' + 
                            '<div class="list-group-item-img" data-src="' + newImgSrc + '" "data-id="' + newImgID + '">' + 
                                '<div class="list-group-item-img-container" style="background-image: url(' + newImgSrc + ');"></div>' + 
                            '</div>' + 
                            '<div class="list-group-item-info" data-ctacolor="' + newCTAColor + '">' + 
                                '<div class="list-group-item-info-title">' + 
                                    '<span class="area-neighborhood" data-id="' + newNeighbourhoodID + '">' + newNeighbourhood + '</span>' + 
                                '</div>' + 
                                '<div class="list-group-item-info-caption">' + 
                                    '<span class="area-city" data-id="' + newCityID + '">' + newCity + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-new-area"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-area"><span class="fa fa-pencil"></span></a>' + 
                        '</div>' + 
                    '<li>';
                $('#areas-list .sortcode-modal-empty').remove();
                $('#areas-list').append(newArea);

                $('.shortcode-modal-new-container').hide();
                $('.sh-new-area-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-area-img').val('').attr({
                    'data-src'    : '',
                });
                $('#sh-new-area-neighborhood').val('');
                $('#sh-new-area-city').val('');
                $('#sh-new-area-cta-color').wpColorPicker('color', '#333333');
                $('#add-area').show();

                $('.edit-new-area').unbind('click').on('click', function(event) {
                    editArea($(this));
                });
                $('.del-new-area').unbind('click').on('click', function(event) {
                    delArea($(this));
                });
            });

            $('#areas-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    areas = [];

                    $('#areas-list .list-group-item').each(function() {
                        var newImgSrc          = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgID           = $(this).find('.list-group-item-img').attr('data-id');
                        var newNeighbourhood   = $(this).find('.area-neighborhood').text();
                        var newNeighbourhoodID = $(this).find('.area-neighborhood').attr('data-id');
                        var newCity            = $(this).find('.area-city').text();
                        var newCityID          = $(this).find('.area-city').attr('data-id');
                        var newCTAColor        = $(this).find('.list-group-item-info').attr('data-ctacolor');

                        areas.push({
                            'src'             : newImgSrc,
                            'id'              : newImgID,
                            'neighborhood'    : newNeighbourhood,
                            'neighborhood_id' : newNeighbourhoodID,
                            'city'            : newCity,
                            'city_id'         : newCityID,
                            'cta_color'       : newCTAColor
                        });
                    });
                }
            }).disableSelection();

            $('.edit-area').on('click', function(event) {
                editArea($(this));
            });

            function editArea(btn) {
                var editImgSrc         = btn.parent().find('.list-group-item-img').attr('data-src');
                var editImgID          = btn.parent().find('.list-group-item-img').attr('data-id');
                var editNeighborhood   = btn.parent().find('.area-neighborhood').text();
                var editNeighborhoodID = btn.parent().find('.area-neighborhood').attr('data-id');
                var editCity           = btn.parent().find('.area-city').text();
                var editCityID         = btn.parent().find('.area-city').attr('data-id');
                var editCTAColor       = btn.parent().find('.list-group-item-info').attr('data-ctacolor');

                var editAreaForm = 
                    '<div class="sh-edit-area">' + 
                        '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.edit_area_header + '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                '<input type="hidden" id="sh-edit-area-img" data-src="' + editImgSrc + '" value="' + editImgID + '">' + 
                                '<div class="sh-new-area-img-container">';
                if (editImgSrc != '') {
                    editAreaForm += 
                                    '<div class="sh-new-area-img-placeholder has-image" style="background-image: url(' + editImgSrc + ');"></div>';
                } else {
                    editAreaForm += 
                                    '<div class="sh-new-area-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>';
                }
                editAreaForm += 
                                    '<div class="sh-new-area-add-btns">' + 
                                        '<div class="sh-new-area-add-img">' + sh_vars.area_add_img + '</div>' + 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                            '<div class="col-xs-12 col-sm-12 col-md-9 rtl-pull-right">' + 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-area-neighborhood">' + sh_vars.area_neighborhood_label + '</label>';

                if (sh_vars.areas_neighborhood_type == 'list') {
                    editAreaForm += 
                                            '<select id="sh-edit-area-neighborhood" class="form-control">' + 
                                                '<option value="">' + sh_vars.areas_select_neighborhood + '</option>';
                    $.each(areas_neighborhoods_list, function(index, value) {
                        editAreaForm += 
                                                '<option value="' + value.id + '"';
                        if (value.id == editNeighborhoodID) {
                            editAreaForm += 
                                                    ' selected="selected"';
                        }
                        editAreaForm += 
                                                '>' + value.name + '</option>';
                    });

                    editAreaForm += 
                                            '</select>';
                } else {
                    editAreaForm += 
                                            '<input type="text" id="sh-edit-area-neighborhood" class="form-control" placeholder="' + sh_vars.area_neighborhood_placeholder + '" value="' + editNeighborhood + '">';
                }

                editAreaForm += 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-area-city">' + sh_vars.area_city_label + '</label>';

                if (sh_vars.areas_city_type == 'list') {
                    editAreaForm += 
                                            '<select id="sh-edit-area-city" class="form-control">' + 
                                                '<option value="">' + sh_vars.areas_select_city + '</option>';
                    $.each(areas_cities_list, function(index, value) {
                        editAreaForm += 
                                                '<option value="' + value.id + '"';
                        if(value.id == editCityID) {
                            editAreaForm += 
                                                    ' selected="selected"';
                        }
                        editAreaForm += 
                                                '>' + value.name + '</option>';
                    });

                    editAreaForm += 
                                            '</select>';
                } else {
                    editAreaForm += 
                                            '<input type="text" id="sh-edit-area-city" class="form-control" placeholder="' + sh_vars.area_city_placeholder + '" value="' + editCity + '">';
                }

                editAreaForm += 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="row">' + 
                                    '<div class="col-xs-12 col-sm-12 col-md-6 rtl-pull-right">' + 
                                        '<div class="form-group">' + 
                                            '<label for="sh-edit-area-cta-color" style="display:block;margin-bottom:2px;">' + sh_vars.area_cta_color + '</label>' + 
                                            '<input type="text" id="sh-edit-area-cta-color" class="color-field" value="' + editCTAColor + '">' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                        '<button type="button" id="ok-edit-area" class="button media-button button-primary">' + sh_vars.ok_edit_area_btn + '</button>' + 
                        '<button type="button" id="cancel-edit-area" class="button media-button button-default">' + sh_vars.cancel_edit_area_btn + '</button>' + 
                    '</div>';

                btn.parent().hide();
                btn.parent().parent().append(editAreaForm);

                $('#areas-list').sortable('disable');
                $('#areas-list .list-group-item').css('cursor', 'auto');
                $('.edit-area').hide();
                $('.del-area').hide();
                $('.edit-new-area').hide();
                $('.del-new-area').hide();
                $('#add-area').hide();
                $('.shortcode-modal-new-container').hide();

                $('.sh-new-area-add-img').on('click', function(event) {
                    openMediaLibrary($(this));
                });

                $('.color-field').wpColorPicker({
                    defaultColor: '#333333',
                });

                $('#ok-edit-area').on('click', function(event) {
                    areas = [];

                    var eImgSrc   = $(this).parent().find('#sh-edit-area-img').attr('data-src');
                    var eImgID    = $(this).parent().find('#sh-edit-area-img').val();
                    var eCTAColor = $(this).parent().find('#sh-edit-area-cta-color').val();

                    if (sh_vars.areas_neighborhood_type == 'list') {
                        var eNeighborhood   = $(this).parent().find('#sh-edit-area-neighborhood option:selected').val() != '' ? $(this).parent().find('#sh-edit-area-neighborhood option:selected').text() : '';
                        var eNeighborhoodID = $(this).parent().find('#sh-edit-area-neighborhood').val();
                    } else {
                        var eNeighborhood   = $(this).parent().find('#sh-edit-area-neighborhood').val();
                        var eNeighborhoodID = '';
                    }

                    if (sh_vars.areas_city_type == 'list') {
                        var eCity   = $(this).parent().find('#sh-edit-area-city option:selected').val() != '' ? $(this).parent().find('#sh-edit-area-city option:selected').text() : '';
                        var eCityID = $(this).parent().find('#sh-edit-area-city').val();
                    } else {
                        var eCity   = $(this).parent().find('#sh-edit-area-city').val();
                        var eCityID = '';
                    }

                    var listElem      = $(this).parent().parent().find('.list-group-item-elem');
                    
                    listElem.find('.list-group-item-img').attr('data-src', eImgSrc).attr('data-id', eImgID).html('<div class="list-group-item-img-container" style="background-image: url(' + eImgSrc + ');"></div>');
                    listElem.find('.area-neighborhood').text(eNeighborhood).attr('data-id', eNeighborhoodID);
                    listElem.find('.area-city').text(eCity).attr('data-id', eCityID);
                    listElem.find('.list-group-item-info').attr('data-ctacolor', eCTAColor);

                    $(this).parent().remove();
                    listElem.show();

                    $('#areas-list').sortable('enable');
                    $('#areas-list .list-group-item').css('cursor', 'move');
                    $('.edit-area').show();
                    $('.del-area').show();
                    $('.edit-new-area').show();
                    $('.del-new-area').show();
                    $('#add-area').show();

                    $('#areas-list .list-group-item').each(function() {
                        var newImgSrc          = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgID           = $(this).find('.list-group-item-img').attr('data-id');
                        var newNeighbourhood   = $(this).find('.area-neighborhood').text();
                        var newNeighbourhoodID = $(this).find('.area-neighborhood').attr('data-id');
                        var newCity            = $(this).find('.area-city').text();
                        var newCityID          = $(this).find('.area-city').attr('data-id');
                        var newCTAColor        = $(this).find('.list-group-item-info').attr('data-ctacolor');

                        areas.push({
                            'src'            : newImgSrc,
                            'id'             : newImgID,
                            'neighborhood'   : newNeighbourhood,
                            'neighborhood_id': newNeighbourhoodID,
                            'city'           : newCity,
                            'city_id'        : newCityID,
                            'cta_color'      : newCTAColor
                        });
                    });
                });

                $('#cancel-edit-area').on('click', function(event) {
                    var listElem = $(this).parent().parent().find('.list-group-item-elem');

                    $(this).parent().remove();
                    listElem.show();
                    $('#areas-list').sortable('enable');
                    $('#areas-list .list-group-item').css('cursor', 'move');
                    $('.edit-area').show();
                    $('.del-area').show();
                    $('.edit-new-area').show();
                    $('.del-new-area').show();
                    $('#add-area').show();
                });
            }

            $('.del-area').on('click', function(event) {
                delArea($(this));
            });

            function delArea(btn) {
                areas = [];

                btn.parent().parent().remove();

                if ($('#areas-list .list-group-item').length > 0) {
                    $('#areas-list .list-group-item').each(function() {
                        var newImgSrc          = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgID           = $(this).find('.list-group-item-img').attr('data-id');
                        var newNeighbourhood   = $(this).find('.area-neighborhood').text();
                        var newNeighbourhoodID = $(this).find('.area-neighborhood').attr('data-id');
                        var newCity            = $(this).find('.area-city').text();
                        var newCityID          = $(this).find('.area-city').attr('data-id');
                        var newCTAColor        = $(this).find('.list-group-item-info').attr('data-ctacolor');

                        areas.push({
                            'src'            : newImgSrc,
                            'id'             : newImgID,
                            'neighborhood'   : newNeighbourhood,
                            'neighborhood_id': newNeighbourhoodID,
                            'city'           : newCity,
                            'city_id'        : newCityID,
                            'cta_color'      : newCTAColor,
                        });
                    });
                } else {
                    $('#areas-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list +  '</li>');
                }
            }

            $('.color-field').wpColorPicker({
                defaultColor: '#333333',
            });
        }

        // Open Media Library
        function openMediaLibrary(btn) {
            event.preventDefault();

            var frame = wp.media({
                title: sh_vars.area_image,
                button: {
                    text: sh_vars.area_insert_image
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    btn.parent().parent().parent().children('input').val(value.id).attr({
                        'data-src': value.url,
                    });
                    btn.parent().parent().find('.sh-new-area-img-placeholder').css('background-image', 'url(' + value.url + ')').addClass('has-image').empty();
                });
            });

            frame.open();
        }

        // Open modal
        editor.addCommand('res_areas_panel_popup', function(ui, v) {
            var data_content = '';

            if (v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_areas_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_areas_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_areas', {
            image: url + '/../images/areas-btn.png',
            tooltip: sh_vars.areas_title,
            onclick: function() {
                editor.execCommand('res_areas_panel_popup', '', {
                    data_content : '{ "title": "", "subtitle": "", "cta_label": "", "cta_link": "", "cta_color": "#333333", "margin": "no", "layout": "1", "areas": [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_areas_shortcode', {
            text  : sh_vars.remove_btn,
            icon  : 'mce-ico mce-i-dashicon dashicons-no',
            cmd   : 'res_areas_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_areas_shortcode', {
            text  : sh_vars.edit_btn,
            icon  : 'mce-ico mce-i-dashicon dashicons-edit',
            cmd   : 'res_areas_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_areas_shortcode',
                    'edit_areas_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_areas') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_areas');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_areas') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_areas_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });

})(jQuery);