(function($) {
    "use strict";

    tinymce.PluginManager.add('res_gallery_carousel', function(editor, url) {
        var shortcodeTag = 'res_gallery_carousel';
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

            return '<img src="' + placeholder + '" class="mceItem res-gallery-carousel-module sc-module ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function replaceShortcodes(content) {
            return content.replace(/\[res_gallery_carousel([^\]]*)\]([^\]]*)\[\/res_gallery_carousel\]/g, function(all, attr, con) {
                return getHTML('wp-res_gallery_carousel', attr , con);
            });
        }

        function restoreShortcodes(content) {
            return content.replace(/(?:<p(?: [^>]+)?>)*(<img class=\"mceItem res-gallery-carousel-module [^>]+>)(?:<\/p>)*/g, function(match, image) {
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

            editor.execCommand('res_gallery_carousel_panel_popup', '', {
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
                                    '<h1>' + sh_vars.gallery_carousel_title + '</h1>' + 
                                '</div>' + 
                                '<div class="media-frame-content">' + 
                                    '<div style="padding: 20px;">' + 
                                        '<div class="sortcode-modal-subtitle">' + sh_vars.gallery_carousel_photos + '</div>' + 
                                        '<ul class="list-group" id="gallery-carousel-list">';

            var photos = getObjectProperty(short, "photos");

            if ($.isArray(photos)) {
                if (photos.length > 0) {
                    $.each(photos, function(index, elem) {
                        modalContent += 
                                            '<li class="list-group-item">' + 
                                                '<div class="list-group-item-elem">' + 
                                                    '<div class="list-group-item-img" data-src="' + elem.src + '" data-value="' + elem.value + '">' + 
                                                        '<div class="list-group-item-img-container" style="background-image: url(' + elem.src + ');"></div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-gallery-carousel-photo"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<a href="javascript:void(0);" class="pull-right edit-btn edit-gallery-carousel-photo"><span class="fa fa-pencil"></span></a>' + 
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
                photos = [];
            }

            modalContent += 
                                        '</ul>' + 
                                        '<button type="button" id="add-gallery-carousel-photo" class="button media-button button-default">' + sh_vars.add_gallery_carousel_photo_btn + '</button>' + 
                                        '<div class="shortcode-modal-new-container" style="display: none;">' + 
                                            '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.new_gallery_carousel_photo_header + '</div>' + 
                                            '<div class="row">' + 
                                                '<div class="col-xs-12 col-md-3 rtl-pull-right">' + 
                                                    '<div class="form-group">' + 
                                                        '<input type="hidden" id="sh-new-gallery-carousel-photo-img" data-src="">' + 
                                                        '<div class="sh-new-gallery-carousel-photo-img-container">' + 
                                                            '<div class="sh-new-gallery-carousel-photo-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>' + 
                                                            '<div class="sh-new-gallery-carousel-photo-add-btns">' + 
                                                                '<div class="sh-new-gallery-carousel-photo-add-img">' + sh_vars.gallery_carousel_photo_add_img + '</div>' + 
                                                            '</div>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<button type="button" id="ok-gallery-carousel-photo" class="button media-button button-primary">' + sh_vars.ok_gallery_carousel_photo_btn + '</button>' + 
                                            '<button type="button" id="cancel-gallery-carousel-photo" class="button media-button button-default">' + sh_vars.cancel_gallery_carousel_photo_btn + '</button>' + 
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
                    'photos' : photos
                }
                var shortcodeStr = '[' + shortcodeTag + ' data_content="' + encodeURIComponent(JSON.stringify(shortVal)) + '"' + '][/' + shortcodeTag + ']';

                editor.insertContent(shortcodeStr);

                setTimeout(function() {
                    $('#shortcode-modal').remove();
                }, 100);
                e.preventDefault();
            });

            $('.sh-new-gallery-carousel-photo-add-img').on('click', function(event) {
                openMediaLibrary($(this));
            });

            $('#add-gallery-carousel-photo').on('click', function(event) {
                $(this).hide();
                $('.shortcode-modal-new-container').show();
            });

            $('#cancel-gallery-carousel-photo').on('click', function(event) {
                $('.shortcode-modal-new-container').hide();
                $('.sh-new-gallery-carousel-photo-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-gallery-carousel-photo-img').val('').attr('data-src', '');
                $('#add-gallery-carousel-photo').show();
            });

            $('#ok-gallery-carousel-photo').on('click', function(event) {
                var newImgSrc = $('#sh-new-gallery-carousel-photo-img').attr('data-src');
                var newImgValue = $('#sh-new-gallery-carousel-photo-img').val();

                photos.push({
                    'src': newImgSrc,
                    'value': newImgValue
                });

                var newPhoto = 
                    '<li class="list-group-item">' + 
                        '<div class="list-group-item-elem">' + 
                            '<div class="list-group-item-img" data-src="' + newImgSrc + '" data-value="' + newImgValue + '">' + 
                                '<div class="list-group-item-img-container" style="background-image: url(' + newImgSrc + ');"></div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-new-gallery-carousel-photo"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-gallery-carousel-photo"><span class="fa fa-pencil"></span></a>' + 
                        '</div>' + 
                    '<li>';
                $('#gallery-carousel-list .sortcode-modal-empty').remove();
                $('#gallery-carousel-list').append(newPhoto);

                $('.shortcode-modal-new-container').hide();
                $('.sh-new-gallery-carousel-photo-img-placeholder').empty().css('background-image', 'url(' + sh_vars.plugin_url + 'images/image-placeholder.png)').removeClass('has-image');
                $('#sh-new-gallery-carousel-photo-img').val('').attr('data-src', '');
                $('#add-gallery-carousel-photo').show();

                $('.edit-new-gallery-carousel-photo').unbind('click').on('click', function(event) {
                    editGalleryCarouselPhoto($(this));
                });
                $('.del-new-gallery-carousel-photo').unbind('click').on('click', function(event) {
                    delGalleryCarouselPhoto($(this));
                });
            });

            $('#gallery-carousel-list').sortable({
                placeholder: 'sortable-placeholder',
                opacity: 0.7,
                stop: function(event, ui) {
                    photos = [];

                    $('#gallery-carousel-list .list-group-item').each(function() {
                        var newImgSrc = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');

                        photos.push({
                            'src': newImgSrc,
                            'value': newImgValue
                        });
                    });
                }
            }).disableSelection();

            $('.edit-gallery-carousel-photo').on('click', function(event) {
                editGalleryCarouselPhoto($(this));
            });

            function editGalleryCarouselPhoto(btn) {
                var editImgSrc = btn.parent().find('.list-group-item-img').attr('data-src');
                var editImgValue = btn.parent().find('.list-group-item-img').attr('data-value');

                var editGalleryCarouselPhotoForm = 
                    '<div class="sh-edit-gallery-carousel-photo">' + 
                        '<div class="sortcode-modal-subtitle" style="padding-top: 0; padding-bottom: 10px;">' + sh_vars.edit_gallery_carousel_photo_header + '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-xs-12 col-sm-12 col-md-3 rtl-pull-right">' + 
                                '<div class="form-group">' + 
                                    '<input type="hidden" id="sh-edit-gallery-carousel-photo-img" data-src="' + editImgSrc + '" value="' + editImgValue + '">' + 
                                    '<div class="sh-new-gallery-carousel-photo-img-container">';
                if (editImgSrc != '') {
                    editGalleryCarouselPhotoForm += 
                                    '<div class="sh-new-gallery-carousel-photo-img-placeholder has-image" style="background-image: url(' + editImgSrc + ');"></div>';
                } else {
                    editGalleryCarouselPhotoForm += 
                                    '<div class="sh-new-gallery-carousel-photo-img-placeholder" style="background-image: url(' + sh_vars.plugin_url + 'images/image-placeholder.png);"></div>';
                }
                editGalleryCarouselPhotoForm += 
                                        '<div class="sh-new-gallery-carousel-photo-add-btns">' + 
                                            '<div class="sh-new-gallery-carousel-photo-add-img">' + sh_vars.gallery_carousel_photo_add_img + '</div>' + 
                                        '</div>'+ 
                                    '</div>'+ 
                                '</div>'+ 
                            '</div>'+ 
                        '</div>'+ 
                        '<button type="button" id="ok-edit-gallery-carousel-photo" class="button media-button button-primary">' + sh_vars.ok_edit_gallery_carousel_photo_btn + '</button>' + 
                        '<button type="button" id="cancel-edit-gallery-carousel-photo" class="button media-button button-default">' + sh_vars.cancel_gallery_carousel_photo_btn + '</button>' + 
                    '</div>';

                btn.parent().hide();
                btn.parent().parent().append(editGalleryCarouselPhotoForm);

                $('#gallery-carousel-list').sortable('disable');
                $('#gallery-carousel-list .list-group-item').css('cursor', 'auto');
                $('.edit-gallery-carousel-photo').hide();
                $('.del-gallery-carousel-photo').hide();
                $('.edit-new-gallery-carousel-photo').hide();
                $('.del-new-gallery-carousel-photo').hide();
                $('#add-gallery-carousel-photo').hide();
                $('.shortcode-modal-new-container').hide();

                $('.sh-new-gallery-carousel-photo-add-img').on('click', function(event) {
                    openMediaLibrary($(this));
                });

                $('#ok-edit-gallery-carousel-photo').on('click', function(event) {
                    photos = [];

                    var eImgSrc   = $(this).parent().find('#sh-edit-gallery-carousel-photo-img').attr('data-src');
                    var eImgValue = $(this).parent().find('#sh-edit-gallery-carousel-photo-img').val();
                    var listElem  = $(this).parent().parent().find('.list-group-item-elem');

                    listElem.find('.list-group-item-img').attr('data-src', eImgSrc).attr('data-value', eImgValue);
                    listElem.find('.list-group-item-img').html('<div class="list-group-item-img-container" style="background-image: url(' + eImgSrc + ');"></div>');

                    $(this).parent().remove();
                    listElem.show();

                    $('#gallery-carousel-list').sortable('enable');
                    $('#gallery-carousel-list .list-group-item').css('cursor', 'move');
                    $('.edit-gallery-carousel-photo').show();
                    $('.del-gallery-carousel-photo').show();
                    $('.edit-new-gallery-carousel-photo').show();
                    $('.del-new-gallery-carousel-photo').show();
                    $('#add-gallery-carousel-photo').show();

                    $('#gallery-carousel-list .list-group-item').each(function() {
                        var newImgSrc = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');

                        photos.push({
                            'src': newImgSrc,
                            'value': newImgValue,
                        });
                    });
                });

                $('#cancel-edit-gallery-carousel-photo').on('click', function(event) {
                    var listElem = $(this).parent().parent().find('.list-group-item-elem');

                    $(this).parent().remove();
                    listElem.show();
                    $('#gallery-carousel-list').sortable('enable');
                    $('#gallery-carousel-list .list-group-item').css('cursor', 'move');
                    $('.edit-gallery-carousel-photo').show();
                    $('.del-gallery-carousel-photo').show();
                    $('.edit-new-gallery-carousel-photo').show();
                    $('.del-new-gallery-carousel-photo').show();
                    $('#add-gallery-carousel-photo').show();
                });
            }

            $('.del-gallery-carousel-photo').on('click', function(event) {
                delGalleryCarouselPhoto($(this));
            });

            function delGalleryCarouselPhoto(btn) {
                photos = [];

                btn.parent().parent().remove();
                if ($('#gallery-carousel-list .list-group-item').length > 0) {
                    $('#gallery-carousel-list .list-group-item').each(function() {
                        var newImgSrc = $(this).find('.list-group-item-img').attr('data-src');
                        var newImgValue = $(this).find('.list-group-item-img').attr('data-value');

                        photos.push({
                            'src': newImgSrc,
                            'value': newImgValue
                        });
                    });
                } else {
                    $('#gallery-carousel-list').append('<li class="sortcode-modal-empty">' + sh_vars.empty_list + '</li>');
                }
            }
        }

        // Open Media Library
        function openMediaLibrary(btn) {
            var frame = wp.media({
                title: sh_vars.gallery_carousel_photo,
                button: {
                    text: sh_vars.gallery_carousel_insert_photo
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    btn.parent().parent().parent().children('input').val(value.id).attr('data-src', value.url);
                    btn.parent().parent().find('.sh-new-gallery-carousel-photo-img-placeholder').css('background-image', 'url(' + value.url + ')').addClass('has-image').empty();
                });
            });

            frame.open();
        }

        // Open modal
        editor.addCommand('res_gallery_carousel_panel_popup', function(ui, v) {
            var data_content = '';

            if(v.data_content) {
                data_content = v.data_content;
            }

            openShortcodeModal(data_content);
        });

        editor.addCommand('res_gallery_carousel_remove', function() {
            removeShortcode();
        });

        editor.addCommand('res_gallery_carousel_edit', function() {
            editShortcode();
        });

        // Add button
        editor.addButton('res_gallery_carousel', {
            image: url + '/../images/gallery-carousel-btn.png',
            tooltip: sh_vars.gallery_carousel_title,
            onclick: function() {
                editor.execCommand('res_gallery_carousel_panel_popup', '', {
                    data_content: '{ "photos": [] }',
                });
            }
        });

        // Register remove shortcode button
        editor.addButton('remove_gallery_carousel_shortcode', {
            text: sh_vars.remove_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-no',
            cmd : 'res_gallery_carousel_remove',
        });

        // Register edit shortcode button
        editor.addButton('edit_gallery_carousel_shortcode', {
            text: sh_vars.edit_btn,
            icon: 'mce-ico mce-i-dashicon dashicons-edit',
            cmd : 'res_gallery_carousel_edit',
        });

        // Add toolbar on image placeholder
        editor.once('preinit', function() {
            if (editor.wp && editor.wp._createToolbar) {
                toolbar = editor.wp._createToolbar([
                    'remove_gallery_carousel_shortcode',
                    'edit_gallery_carousel_shortcode',
                ]);
            }
        });

        editor.on('wptoolbar', function(e) {
            if (e.element.nodeName == 'IMG' && e.element.className.indexOf('wp-res_gallery_carousel') > -1) {
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
            var cls = e.target.className.indexOf('wp-res_gallery_carousel');

            if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-res_gallery_carousel') > -1) {
                var data = e.target.attributes['data-sh-attr'].value;
                data = window.decodeURIComponent(data);
                var content = e.target.attributes['data-sh-content'].value;
                editor.execCommand('res_gallery_carousel_panel_popup', '', {
                    data_content : getAttr(data, 'data_content')
                });
            }
        });
    });
})(jQuery);