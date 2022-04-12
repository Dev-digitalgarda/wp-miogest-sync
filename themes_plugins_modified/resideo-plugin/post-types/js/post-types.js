(function($) {
    "use strict";

    $('.datePicker').datepickerc({
        'format' : 'yyyy-mm-dd'
    });

    function fixedEncodeURIComponent(str) {
        return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
            return '%' + c.charCodeAt(0).toString(16);
        });
    }

    function jsonParser(str) {
        try {
          return JSON.parse(str);
        } catch(ex) {
          return null;
        }
    }

    // Photo Gallery
    if ($('#property_gallery').length > 0) {
        var galleryFrame;
        var galleryEditFrame;
        var photos = [];
        var editPhotoID;
        var editPhotoItem;
        var galleryIDs = $('#property_gallery').val().split(',');

        if (galleryIDs.length > 0) {
            galleryIDs.forEach(function(id) {
                if(id != '') {
                    photos.push(parseInt(id));
                }
            });
        }

        $('#prop-gallery-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                photos = [];
                $('#prop-gallery-list li').each(function(index, el) {
                    photos.push(parseInt($(this).attr('data-id')));
                });
                $('#property_gallery').val(photos.toString());
            }
        }).disableSelection();

        $('#add-photo-btn').click(function(event) {
            event.preventDefault();

            if(galleryFrame) {
                galleryFrame.open();
                return;
            }

            galleryFrame = wp.media({
                title: pt_vars.gallery_title,
                button: {
                    text: pt_vars.gallery_btn
                },
                library: {
                    type: 'image'
                },
                multiple: true
            });

            galleryFrame.on('select', function() {
                var attachment = galleryFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    if($.inArray(value.id, photos) == -1 && value.id != '') {
                        photos.push(value.id);

                        $('#prop-gallery-list').append('<li class="list-group-item" data-id="' + value.id + '"><img class="pull-left" src="' + value.url + '" />' + 
                            '<div class="list-group-item-info">' + 
                            '<div class="list-group-item-info-title">' + value.name + '</div>' + 
                            '<div class="list-group-item-info-caption">' + value.caption + '</div>' + 
                            '<div class="clearfix"></div>' + 
                            '</div>' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn del-photo"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn edit-photo"><span class="fa fa-pencil"></span></a>' + 
                            '<div class="clearfix"></div></li>');
                    }
                });

                $('#property_gallery').val(photos.toString());
            });

            galleryFrame.on('open', function() {
                var selection = galleryFrame.state().get('selection');
                var ids = $('#property_gallery').val().split(',');

                ids.forEach(function(id) {
                    var attachment = wp.media.attachment(id);

                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            });

            galleryFrame.open();
        });

        $(document).on('click', '.edit-photo', function(event) {
            event.preventDefault();
            editPhotoItem = $(this);
            editPhotoID = editPhotoItem.parent().attr('data-id');

            if(galleryEditFrame) {
                galleryEditFrame.open();
                return;
            }

            galleryEditFrame = wp.media({
                title: pt_vars.gallery_title,
                button: {
                    text: pt_vars.gallery_btn
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            galleryEditFrame.on('select', function() {
                var attachment = galleryEditFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    var editPhotoIndex = photos.indexOf(parseInt(editPhotoID));

                    if(editPhotoIndex !== -1 && $.inArray(value.id, photos) === -1) {
                        photos[editPhotoIndex] = value.id;

                        editPhotoItem.parent().attr('data-id', value.id);
                        editPhotoItem.parent().children('img').attr('src', value.url);
                        editPhotoItem.parent().children('.list-group-item-info').children('.list-group-item-info-title').text(value.name);
                        editPhotoItem.parent().children('.list-group-item-info').children('.list-group-item-info-caption').text(value.caption);
                    }

                    $('#property_gallery').val(photos.toString());
                });
            });

            galleryEditFrame.on('open', function() {
                var selection = galleryEditFrame.state().get('selection');
                var attachment = wp.media.attachment(editPhotoID);

                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });

            galleryEditFrame.open();
        });

        $(document).on('click', '.del-photo', function() {
            var delImage = $(this).parent().attr('data-id');

            photos = $.grep(photos, function(id) {
                return id != delImage;
            });

            $('#property_gallery').val(photos.toString());
            $(this).parent().remove();
        });
    }

    // Floor Plans
    if ($('#property_floor_plans').length > 0) {
        var data = {
            'plans' : []
        }
        var floorPlans = '';
        var floorPlansRaw = $('#property_floor_plans').val();

        if (floorPlansRaw != '') {
            floorPlans = jsonParser(decodeURIComponent(floorPlansRaw.replace(/\+/g, ' ')));

            if (floorPlans !== null) {
                data = floorPlans;
            }
        }

        $('#add-floor-plan-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-new-floor-plan').show();
        });

        $('#ok-floor-plan').on('click', function(event) {
            event.preventDefault();

            var title       = $('#property_floor_plan_title').val();
            var beds        = $('#property_floor_plan_beds').val();
            var baths       = $('#property_floor_plan_baths').val();
            var size        = $('#property_floor_plan_size').val();
            var description = $('#property_floor_plan_description').val();
            var image       = $('#property_floor_plan_image').val();
            var image_src   = $('#property_floor_plan_image').attr('data-src');

            data.plans.push({
                'title'      : title,
                'beds'       : beds,
                'baths'      : baths,
                'size'       : size,
                'description': description,
                'image'      : image
            });

            $('#property_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));

            var info = '';
            if (beds != '') {
                info += beds + ' ' + pt_vars.beds_label + ' | ';
            }
            if (baths != '') {
                info += baths + ' ' + pt_vars.baths_label + ' | ';
            }
            if (size != '') {
                info += size + ' ' + pt_vars.unit;
            }

            $('#prop-floor-plans-list').append(
                '<li class="list-group-item" data-id="' + image + '" ' + 
                        'data-title="' + title + '" ' + 
                        'data-beds="' + beds + '" ' + 
                        'data-baths="' + baths + '" ' + 
                        'data-size="' + size + '" ' + 
                        'data-description="' + description + '" ' + 
                        'data-src="' + image_src + '">' + 
                    '<div class="floor-plan-item-container">' + 
                        '<img class="pull-left rtl-pull-right" src="' + image_src + '">' + 
                        '<div class="list-group-item-info">' + 
                            '<div class="list-group-item-info-title">' + title + '</div>' + 
                            '<div class="list-group-item-info-caption">' + info + '</div>' + 
                            '<div class="clearfix"></div>' + 
                        '</div>' + 
                        '<a href="javascript:void(0);" class="pull-right del-btn del-new-floor-plan"><span class="fa fa-trash-o"></span></a>' + 
                        '<a href="javascript:void(0);" class="pull-right edit-btn edit-new-floor-plan"><span class="fa fa-pencil"></span></a>' + 
                        '<div class="clearfix"></div>' + 
                    '</div>' + 
                '</li>'
            );

            $('#property_floor_plan_title').val('');
            $('#property_floor_plan_beds').val('');
            $('#property_floor_plan_baths').val('');
            $('#property_floor_plan_size').val('');
            $('#property_floor_plan_description').val('');
            $('#property_floor_plan_image').val('');

            $('#property-floor-plan-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.property-floor-plan-image-placeholder-container').removeClass('has-image');

            $('.pxp-new-floor-plan').hide();
            $('#add-floor-plan-btn').show();

            $('.edit-new-floor-plan').unbind('click').on('click', function(event) {
                editFloorPlan($(this));
            });
            $('.del-new-floor-plan').unbind('click').on('click', function(event) {
                delFloorPlan($(this));
            });
        });

        $('#cancel-floor-plan').on('click', function(event) {
            event.preventDefault();

            $('#property_floor_plan_title').val('');
            $('#property_floor_plan_beds').val('');
            $('#property_floor_plan_baths').val('');
            $('#property_floor_plan_size').val('');
            $('#property_floor_plan_description').val('');
            $('#property_floor_plan_image').val('');

            $('#property-floor-plan-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.property-floor-plan-image-placeholder-container').removeClass('has-image');

            $('.pxp-new-floor-plan').hide();
            $('#add-floor-plan-btn').show();
        });

        $('#property-floor-plan-image-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.floor_plan_title,
                button: {
                    text: pt_vars.floor_plan_btn
                },
                multiple: false
            });
    
            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#property_floor_plan_image').val(value.id).attr('data-src', value.url);
                    $('#property-floor-plan-image-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.property-floor-plan-image-placeholder-container').addClass('has-image');
                });
            });

            frame.open();
        });

        $('#delete-property-floor-plan-image').on('click', function() {
            $('#property_floor_plan_image').val('');
            $('#property-floor-plan-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.property-floor-plan-image-placeholder-container').removeClass('has-image');
        });

        $('#prop-floor-plans-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                data.plans = [];

                $('#prop-floor-plans-list li').each(function(index, el) {
                    data.plans.push({
                        'title'      : $(this).attr('data-title'),
                        'beds'       : $(this).attr('data-beds'),
                        'baths'      : $(this).attr('data-baths'),
                        'size'       : $(this).attr('data-size'),
                        'description': $(this).attr('data-description'),
                        'image'      : $(this).attr('data-id')
                    });

                });

                $('#property_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
            }
        }).disableSelection();

        $('.del-floor-plan').on('click', function(event) {
            delFloorPlan($(this));
        });

        $('.edit-floor-plan').on('click', function(event) {
            editFloorPlan($(this));
        });
    }

    function delFloorPlan(btn) {
        btn.parent().parent().remove();

        data.plans = [];

        $('#prop-floor-plans-list li').each(function(index, el) {
            data.plans.push({
                'title'      : $(this).attr('data-title'),
                'beds'       : $(this).attr('data-beds'),
                'baths'      : $(this).attr('data-baths'),
                'size'       : $(this).attr('data-size'),
                'description': $(this).attr('data-description'),
                'image'      : $(this).attr('data-id')
            });

        });

        $('#property_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
    }

    function editFloorPlan(btn) {
        var editImgSrc      = btn.parent().parent().attr('data-src');
        var editImgId       = btn.parent().parent().attr('data-id');
        var editTitle       = btn.parent().parent().attr('data-title');
        var editBeds        = btn.parent().parent().attr('data-beds');
        var editBaths       = btn.parent().parent().attr('data-baths');
        var editSize        = btn.parent().parent().attr('data-size');
        var editDescription = btn.parent().parent().attr('data-description');

        var editFloorPlanForm = 
            '<div class="pxp-edit-floor-plan">' + 
                '<div class="pxp-new-floor-plan-container">' + 
                    '<div><b>' + pt_vars.edit_floor_plan + '</b></div>' + 
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">' + 
                        '<tr>' + 
                            '<td width="50%" valign="top">' + 
                                '<div class="adminField">' + 
                                    '<label for="edit_property_floor_plan_title">' + pt_vars.edit_floor_plan_title_label + '</label><br>' + 
                                    '<input type="text" class="formInput" id="edit_property_floor_plan_title" name="edit_property_floor_plan_title" placeholder="' + pt_vars.edit_floor_plan_title_placeholder + '" value="' + editTitle + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="50%" valign="top">&nbsp;</td>' + 
                        '</tr>' + 
                    '</table>' + 
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">' + 
                        '<tr>' + 
                            '<td width="33%" valign="top">' + 
                                '<div class="adminField">' + 
                                    '<label for="edit_property_floor_plan_beds">' + pt_vars.edit_floor_plan_beds_label + '</label><br>' + 
                                    '<input type="text" class="formInput" id="edit_property_floor_plan_beds" name="edit_property_floor_plan_beds" placeholder="' + pt_vars.edit_floor_plan_beds_placeholder + '" value="' + editBeds + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="33%" valign="top">' + 
                                '<div class="adminField">' + 
                                    '<label for="edit_property_floor_plan_baths">' + pt_vars.edit_floor_plan_baths_label + '</label><br>' + 
                                    '<input type="text" class="formInput" id="edit_property_floor_plan_baths" name="edit_property_floor_plan_baths" placeholder="' + pt_vars.edit_floor_plan_baths_placeholder + '" value="' + editBaths + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="33%" valign="top">' + 
                                '<div class="adminField">' + 
                                    '<label for="edit_property_floor_plan_size">' + pt_vars.edit_floor_plan_size_label + ' (' + pt_vars.unit + ')' + '</label><br />' + 
                                    '<input type="text" class="formInput" id="edit_property_floor_plan_size" name="edit_property_floor_plan_size" placeholder="' + pt_vars.edit_floor_plan_size_placeholder + '" value="' + editSize + '">' + 
                                '</div>' + 
                            '</td>' + 
                        '</tr>' + 
                    '</table>' + 
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">' + 
                        '<tr>' + 
                            '<td width="66%" valign="top">' + 
                                '<div class="adminField">' + 
                                    '<label for="edit_property_floor_plan_description">' + pt_vars.edit_floor_plan_description_label + '</label><br>' + 
                                    '<textarea id="edit_property_floor_plan_description" name="edit_property_floor_plan_description" placeholder="' + pt_vars.edit_floor_plan_description_placeholder + '" style="width: 100%; height: 140px;">' + editDescription + '</textarea>' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="33%" valign="top">' + 
                                '<div class="adminField">' + 
                                    '<label>' + pt_vars.edit_floor_plan_image_label + '</label>' + 
                                    '<input type="hidden" id="edit_property_floor_plan_image" name="edit_property_floor_plan_image" data-src="' + editImgSrc + '" value="' + editImgId + '">';
        if (editImgId != '') {
            editFloorPlanForm += 
                                    '<div class="property-edit-floor-plan-image-placeholder-container has-image">' + 
                                        '<div id="property-edit-floor-plan-image-placeholder" style="background-image: url(' + editImgSrc + ');"></div>';
        } else {
            editFloorPlanForm += 
                                    '<div class="property-edit-floor-plan-image-placeholder-container">' + 
                                        '<div id="property-edit-floor-plan-image-placeholder" style="background-image: url(' + pt_vars.plugin_url +  'images/image-placeholder.png);"></div>';
        }
        editFloorPlanForm +=
                                        '<div id="delete-property-edit-floor-plan-image"><span class="fa fa-trash-o"></span></div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</td>' + 
                        '</tr>' + 
                    '</table>' + 
                    '<button type="button" id="ok-edit-floor-plan" class="button media-button button-primary">' + pt_vars.edit_floor_plan_ok_btn + '</button>&nbsp;' + 
                    '<button type="button" id="cancel-edit-floor-plan" class="button media-button button-default">' + pt_vars.edit_floor_plan_cancel_btn + '</button>' + 
                '</div>' + 
            '</div>';

        btn.parent().hide();
        btn.parent().parent().append(editFloorPlanForm);

        $('#prop-floor-plans-list').sortable('disable');
        $('#prop-floor-plans-list .list-group-item').css('cursor', 'auto');
        $('.edit-floor-plan').hide();
        $('.del-floor-plan').hide();
        $('.edit-new-floor-plan').hide();
        $('.del-new-floor-plan').hide();
        $('#add-floor-plan-btn').hide();
        $('.pxp-new-floor-plan').hide();

        $('#property-edit-floor-plan-image-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.floor_plan_title,
                button: {
                    text: pt_vars.floor_plan_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#edit_property_floor_plan_image').val(value.id).attr('data-src', value.url);
                    $('#property-edit-floor-plan-image-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.property-edit-floor-plan-image-placeholder-container').addClass('has-image');
                });
            });

            frame.open();
        });

        $('#delete-property-edit-floor-plan-image').on('click', function() {
            $('#edit_property_floor_plan_image').val('').attr('data-src', '');
            $('#property-edit-floor-plan-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.property-edit-floor-plan-image-placeholder-container').removeClass('has-image');
        });

        $('#ok-edit-floor-plan').on('click', function(event) {
            var eImgSrc      = $(this).parent().find('#edit_property_floor_plan_image').attr('data-src');
            var eImgId       = $(this).parent().find('#edit_property_floor_plan_image').val();
            var eTitle       = $(this).parent().find('#edit_property_floor_plan_title').val();
            var eBeds        = $(this).parent().find('#edit_property_floor_plan_beds').val();
            var eBaths       = $(this).parent().find('#edit_property_floor_plan_baths').val();
            var eSize        = $(this).parent().find('#edit_property_floor_plan_size').val();
            var eDescription = $(this).parent().find('#edit_property_floor_plan_description').val();
            var listElem     = $(this).parent().parent().parent();

            listElem.attr({
                'data-id': eImgId,
                'data-src': eImgSrc,
                'data-title': eTitle,
                'data-beds': eBeds,
                'data-baths': eBaths,
                'data-size': eSize,
                'data-description': eDescription
            });

            listElem.find('img').attr('src', eImgSrc);
            listElem.find('.list-group-item-info-title').text(eTitle);

            var eInfo = '';
            if (eBeds != '') {
                eInfo += eBeds + ' ' + pt_vars.beds_label + ' | ';
            }
            if (eBaths != '') {
                eInfo += eBaths + ' ' + pt_vars.baths_label + ' | ';
            }
            if (eSize != '') {
                eInfo += eSize + ' ' + pt_vars.unit;
            }

            listElem.find('.list-group-item-info-caption').html(eInfo);

            $(this).parent().parent().remove();
            listElem.find('.floor-plan-item-container').show();

            $('#prop-floor-plans-list').sortable('enable');
            $('#prop-floor-plans-list .list-group-item').css('cursor', 'move');
            $('.edit-floor-plan').show();
            $('.del-floor-plan').show();
            $('.edit-new-floor-plan').show();
            $('.del-new-floor-plan').show();
            $('#add-floor-plan-btn').show();

            data.plans = [];
            $('#prop-floor-plans-list li').each(function(index, el) {
                data.plans.push({
                    'title'      : $(this).attr('data-title'),
                    'beds'       : $(this).attr('data-beds'),
                    'baths'      : $(this).attr('data-baths'),
                    'size'       : $(this).attr('data-size'),
                    'description': $(this).attr('data-description'),
                    'image'      : $(this).attr('data-id')
                });

            });

            $('#property_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
        });

        $('#cancel-edit-floor-plan').on('click', function(event) {
            var listElem = $(this).parent().parent().parent();

            $(this).parent().parent().remove();
            listElem.find('.floor-plan-item-container').show();

            $('#prop-floor-plans-list').sortable('enable');
            $('#prop-floor-plans-list .list-group-item').css('cursor', 'move');
            $('.edit-floor-plan').show();
            $('.del-floor-plan').show();
            $('.edit-new-floor-plan').show();
            $('.del-new-floor-plan').show();
            $('#add-floor-plan-btn').show();
        });
    }

    // Upload agent/owner avatar
    $('#avatar-image-placeholder').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: pt_vars.avatar_title,
            button: {
                text: pt_vars.avatar_btn
            },
            multiple: false
        });

        frame.on( 'select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#agent_avatar').val(value.id);
                $('#avatar-image-placeholder').css('background-image', 'url(' + value.url + ')');
                $('.avatar-placeholder-container').addClass('has-image');
            });
        });

        frame.open();
    });

    $('#delete-avatar-image').click(function() {
        $('#agent_avatar').val('');
        $('#avatar-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/avatar-placeholder.png)');
        $('.avatar-placeholder-container').removeClass('has-image');
    });

    // Upload memberhip plan icon
    $('#icon-image-placeholder').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: pt_vars.avatar_title,
            button: {
                text: pt_vars.avatar_btn
            },
            multiple: false
        });

        frame.on( 'select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#membership_icon').val(value.id);
                $('#icon-image-placeholder').css('background-image', 'url(' + value.url + ')');
                $('.icon-placeholder-container').addClass('has-image');
            });
        });

        frame.open();
    });

    $('#delete-icon-image').click(function() {
        $('#membership_icon').val('');
        $('#icon-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/icon-placeholder.png)');
        $('.icon-placeholder-container').removeClass('has-image');
    });

    // Header type settings
    if($('#page-header-section').length > 0) {
        $('input[name=page_header_type]').on('change', function() {
            var selected = $(this).val();

            $('.header-settings').hide();
            $('.header-' + selected + '-settings').show();
        });

        // Slideshow gallery
        var slideshowGalleryFrame;
        var slideshowGalleryEditFrame;
        var slideshowPhotos = [];
        var editSlideshowPhotoID;
        var editSlideshowPhotoItem;
        var slideshowGalleryIDs = $('#page_header_slideshow_gallery').val().split(',');

        if(slideshowGalleryIDs.length > 0) {
            slideshowGalleryIDs.forEach(function(id) {
                if(id != '') {
                    slideshowPhotos.push(parseInt(id));
                }
            });
        }

        $('#slideshow-gallery-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                slideshowPhotos = [];
                $('#slideshow-gallery-list li').each(function(index, el) {
                    slideshowPhotos.push(parseInt($(this).attr('data-id')));
                });
                $('#page_header_slideshow_gallery').val(slideshowPhotos.toString());
            }
        }).disableSelection();

        $('#slideshow-add-photo-btn').click(function(event) {
            event.preventDefault();

            if(slideshowGalleryFrame) {
                slideshowGalleryFrame.open();
                return;
            }

            slideshowGalleryFrame = wp.media({
                title: pt_vars.slideshow_title,
                button: {
                    text: pt_vars.slideshow_btn
                },
                library: {
                    type: 'image'
                },
                multiple: true
            });

            slideshowGalleryFrame.on('select', function() {
                var attachment = slideshowGalleryFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    if($.inArray(value.id, slideshowPhotos) == -1) {
                        slideshowPhotos.push(value.id);

                        $('#slideshow-gallery-list').append('<li class="list-group-item" data-id="' + value.id + '"><img class="pull-left" src="' + value.url + '" />' + 
                            '<a href="javascript:void(0);" class="pull-right del-btn slideshow-del-photo"><span class="fa fa-trash-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pull-right edit-btn slideshow-edit-photo"><span class="fa fa-pencil"></span></a>' + 
                            '<div class="clearfix"></div></li>');
                    }
                });

                $('#page_header_slideshow_gallery').val(slideshowPhotos.toString());
            });

            slideshowGalleryFrame.on('open', function() {
                var selection = slideshowGalleryFrame.state().get('selection');
                var ids = $('#page_header_slideshow_gallery').val().split(',');

                ids.forEach(function(id) {
                    var attachment = wp.media.attachment(id);

                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            });

            slideshowGalleryFrame.open();
        });

        $(document).on('click', '.slideshow-edit-photo', function(event) {
            event.preventDefault();
            editSlideshowPhotoItem = $(this);
            editSlideshowPhotoID = editSlideshowPhotoItem.parent().attr('data-id');

            if(slideshowGalleryEditFrame) {
                slideshowGalleryEditFrame.open();
                return;
            }

            slideshowGalleryEditFrame = wp.media({
                title: pt_vars.slideshow_title,
                button: {
                    text: pt_vars.slideshow_btn
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            slideshowGalleryEditFrame.on('select', function() {
                var attachment = slideshowGalleryEditFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    var editPhotoIndex = slideshowPhotos.indexOf(parseInt(editSlideshowPhotoID));

                    if(editPhotoIndex !== -1 && $.inArray(value.id, slideshowPhotos) === -1) {
                        slideshowPhotos[editPhotoIndex] = value.id;

                        editSlideshowPhotoItem.parent().attr('data-id', value.id);
                        editSlideshowPhotoItem.parent().children('img').attr('src', value.url);
                    }

                    $('#page_header_slideshow_gallery').val(slideshowPhotos.toString());
                });
            });

            slideshowGalleryEditFrame.on('open', function() {
                var selection = slideshowGalleryEditFrame.state().get('selection');
                var attachment = wp.media.attachment(editSlideshowPhotoID);

                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });

            slideshowGalleryEditFrame.open();
        });

        $(document).on('click', '.slideshow-del-photo', function() {
            var delImage = $(this).parent().attr('data-id');

            slideshowPhotos = $.grep(slideshowPhotos, function(id) {
                return id != delImage;
            });

            $('#page_header_slideshow_gallery').val(slideshowPhotos.toString());
            $(this).parent().remove();
        });

        $('input[name=page_header_slideshow_show_cta]').on('change', function() {
            var selected = $(this).is(':checked');

            if(selected === true) {
                $('.slideshow-cta-settings').show();
            } else {
                $('.slideshow-cta-settings').hide();
            }
        });

        $('input[name=page_header_slideshow_show_search]').on('change', function() {
            var selected = $(this).is(':checked');

            if(selected === true) {
                $('.slideshow-search-settings').show();
            } else {
                $('.slideshow-search-settings').hide();
            }
        });

        // Slider Settings
        var slides = [];
        var sliderNewFrame;
        var newImage;
        var sliderFrame;
        var editSlide;

        $('#slider-image-placeholder').click(function(event) {
            event.preventDefault();

            if(sliderNewFrame) {
                sliderNewFrame.open();
                return;
            }

            sliderNewFrame = wp.media({
                title: pt_vars.slider_title,
                button: {
                    text: pt_vars.slider_btn
                },
                multiple: false
            });

            sliderNewFrame.on('select', function() {
                var attachment = sliderNewFrame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#page_header_slider_id').val(value.id);
                    $('#slider-image-placeholder').css('background-image', 'url(' + value.url + ')');
                    newImage = value.url;
                });
            });

            sliderNewFrame.open();
        });

        $('.pxp-color-field').wpColorPicker({
            defaultColor: '#333333',
        });

        $('#add_slider_item').click(function() {
            var id       = $('#page_header_slider_id');
            var title    = $('#page_header_slider_caption_title');
            var subtitle = $('#page_header_slider_caption_subtitle');
            var ctaText  = $('#page_header_slider_caption_cta_text');
            var ctaLink  = $('#page_header_slider_caption_cta_link');
            var color    = $('#page_header_slider_caption_solid_color');

            if (id.val() != '') {
                $('#slider-list').append(
                    '<li class="list-group-item" data-id="' + id.val() + '">' + 
                        '<div class="list-group-item-img">' + 
                            '<img class="pull-left" src="' + newImage + '" />' + 
                            '<div class="list-group-item-img-edit">' + 
                                '<span class="fa fa-pencil"></span>' + 
                            '</div>' + 
                        '</div>' + 
                        '<div class="list-group-item-info">' + 
                            '<div class="list-group-item-info-title">' + 
                                '<div class="editable">' + 
                                    '<span class="slide-title">' + title.val() + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="list-group-item-info-caption">' + 
                                '<div class="editable">' + 
                                    '<span class="slide-subtitle">' + subtitle.val() + '</span>' + 
                                '</div>' +
                            '</div>' + 
                            '<span style="display: none;" class="slide-cta-text" data-value="' + ctaText.val() + '"></span>' + 
                            '<span style="display: none;" class="slide-cta-link" data-value="' + ctaLink.val() + '"></span>' + 
                            '<span style="display: none;" class="slide-color" data-value="' + color.val() + '"></span>' + 
                        '</div>' + 
                        '<div class="list-group-item-edit">' + 
                            '<input type="text" class="edit-slide-title" placeholder="' + pt_vars.slider_caption_title + '">' + 
                            '<input type="text" class="edit-slide-cta-text" placeholder="' + pt_vars.slider_cta_text + '">' + 
                            '<br>' + 
                            '<input type="text" class="edit-slide-subtitle" placeholder="' + pt_vars.slider_caption_subtitle + '">' + 
                            '<input type="text" class="edit-slide-cta-link" placeholder="' + pt_vars.slider_cta_link + '">' + 
                        '</div>' + 
                        '<a href="javascript:void(0);" class="pull-right del-btn del-slide"><span class="fa fa-trash-o"></span></a>' + 
                        '<a href="javascript:void(0);" class="pull-right edit-btn edit-slide"><span class="fa fa-pencil"></span></a>' + 
                        '<input type="button" class="button edit-slide-ok" value="' + pt_vars.slider_edit_ok + '">' + 
                        '<div class="clearfix"></div>' + 
                    '</li>'
                );

                id.val('');
                title.val('');
                subtitle.val('');
                ctaText.val('');
                ctaLink.val('');
                color.val('');
                $('#slider-image-placeholder').css('background-image', 'url(' + pt_vars.img_placeholder + ')');

                updateSliders();
            } else {
                alert(pt_vars.slider_error);
                return;
            }
        });

        $(document).on('click', '.edit-slide', function(event) {
            var title    = $(this).parent().find('.slide-title').text();
            var subtitle = $(this).parent().find('.slide-subtitle').text();
            var ctaText  = $(this).parent().find('.slide-cta-text').attr('data-value');
            var ctaLink  = $(this).parent().find('.slide-cta-link').attr('data-value');
            var color    = $(this).parent().find('.slide-color').attr('data-value');

            $(this).parent().find('.edit-slide-title').val(title);
            $(this).parent().find('.edit-slide-subtitle').val(subtitle);
            $(this).parent().find('.edit-slide-cta-text').val(ctaText);
            $(this).parent().find('.edit-slide-cta-link').val(ctaLink);
            $(this).parent().find('.edit-slide-color').val(color).wpColorPicker({
                defaultColor: '#333333',
            });

            $('.list-group-item-img').addClass('is-editable');

            $(this).parent().find('.list-group-item-info').hide();
            $(this).parent().find('.list-group-item-edit').show();

            $(this).hide();
            $(this).parent().find('.edit-slide').hide();
            $(this).parent().find('.edit-slide-ok').show();
        });

        $(document).on('click', '.edit-slide-ok', function(event) {
            var title    = $(this).parent().find('.edit-slide-title').val();
            var subtitle = $(this).parent().find('.edit-slide-subtitle').val();
            var ctaText  = $(this).parent().find('.edit-slide-cta-text').val();
            var ctaLink  = $(this).parent().find('.edit-slide-cta-link').val();
            var color    = $(this).parent().find('.edit-slide-color').val();

            $(this).parent().find('.slide-title').text(title);
            $(this).parent().find('.slide-subtitle').text(subtitle);
            $(this).parent().find('.slide-cta-text').attr('data-value', ctaText);
            $(this).parent().find('.slide-cta-link').attr('data-value', ctaLink);
            $(this).parent().find('.slide-color').attr('data-value', color);

            $(this).parent().find('.edit-slide-title').val('');
            $(this).parent().find('.edit-slide-subtitle').val('');
            $(this).parent().find('.edit-slide-cta-text').val('');
            $(this).parent().find('.edit-slide-cta-link').val('');
            $(this).parent().find('.edit-slide-color').val('');

            $('.list-group-item-img').removeClass('is-editable');

            $(this).parent().find('.list-group-item-edit').hide();
            $(this).parent().find('.list-group-item-info').show();

            $(this).hide();
            $(this).parent().find('.edit-slide-ok').hide();
            $(this).parent().find('.edit-slide').show();

            updateSliders();
        });

        $(document).on('click', '.list-group-item-img.is-editable', function(event) {
            editSlide = $(this);

            event.preventDefault();

            if(sliderFrame) {
                sliderFrame.open();
                return;
            }

            sliderFrame = wp.media({
                title: pt_vars.slider_title,
                button: {
                    text: pt_vars.slider_btn
                },
                multiple: false
            });

            sliderFrame.on('select', function() {
                var attachment = sliderFrame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    editSlide.parent().attr('data-id', value.id);
                    editSlide.find('img').attr('src', value.url);
                });
            });

            sliderFrame.open();
        });

        $('#slider-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                updateSliders();
            }
        }).disableSelection();

        $(document).on('click', '.del-slide', function(event) {
            $(this).parent().remove();
            updateSliders();
        });

        $('input[name=page_header_slider_show_search]').on('change', function() {
            var selected = $(this).is(':checked');

            if(selected === true) {
                $('.slider-search-settings').show();
            } else {
                $('.slider-search-settings').hide();
            }
        });

        function updateSliders() {
            slides = [];

            if($('#slider-list .list-group-item').length > 0) {
                $('#slider-list .list-group-item').each(function() {
                    var id       = $(this).attr('data-id');
                    var title    = $(this).find('.slide-title').text();
                    var subtitle = $(this).find('.slide-subtitle').text();
                    var ctaText  = $(this).find('.slide-cta-text').attr('data-value');
                    var ctaLink  = $(this).find('.slide-cta-link').attr('data-value');
                    var color    = $(this).find('.slide-color').attr('data-value');

                    var elem = {
                        'id'       : id,
                        'title'    : title,
                        'subtitle' : subtitle,
                        'cta_text' : ctaText,
                        'cta_link' : ctaLink,
                        'color' : color,
                    };

                    $('#slider-list .slider-list-empty').remove();

                    slides.push(elem);
                });

                $('#page_header_slider').val(JSON.stringify(slides));
            } else {
                $('#slider-list').append('<li class="slider-list-empty"><p class="help">' + pt_vars.slider_empty + '</p></li>');
                slides = [];
                $('#page_header_slider').val('');
            }
        }

        // Properties Slider Settings
        var properties = [];
        var propertyIDs = $('#page_header_p_slider').val().split(',');

        if(propertyIDs.length > 0) {
            propertyIDs.forEach(function(id) {
                if(id != '') {
                    properties.push(parseInt(id));
                }
            });
        }

        $('#add_p_slider_item').click(function() {
            var modal = '<div tabindex="0" role="dialog" id="properties-modal" class="custom-modal">' + 
                                '<div class="media-modal wp-core-ui">' + 
                                    '<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>' + 
                                    '<div class="media-modal-content">' + 
                                        '<div class="media-frame mode-select wp-core-ui" id="">' + 
                                            '<div class="media-frame-title">' + 
                                                '<h1>' + pt_vars.modal_properties + '</h1>' + 
                                                '<div class="search-items-wrapper">' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-sm-12 col-md-4 search-items">' + 
                                                            '<span class="fa fa-search"></span><input type="text" id="search-props-input" class="form-control search-items-input" placeholder="' + pt_vars.search_properties + '">' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="media-frame-content">' +
                                                '<div class="properties-modal-subtitle"><span class="properties-modal-total">--</span> ' + pt_vars.modal_properties_results + '</div>' + 
                                                '<div id="properties-modal-list" class="row"></div>' + 
                                                '<div class="properties-modal-list-more-container">' + 
                                                    '<button type="button" id="properties-modal-list-more" class="button media-button button-default button-large"><img src="' + pt_vars.plugin_url + 'images/loader-dark.svg"><span>' + pt_vars.load_more_properties + '</span></button>' + 
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
                var ajaxURL = pt_vars.ajaxurl;
                var security = $('#security-modal-props').val();

                pageNumber++;

                if(change_total === true) {
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
                        'action'   : 'resideo_get_modal_properties',
                        'security' : security,
                        'page_no'  : pageNumber,
                        'keyword'  : keyword
                    },
                    success: function(data) {
                        if(data.getprops === true) {
                            var list = '';

                            $('.properties-modal-total').text(data.total);

                            $.each(data.props, function(i, prop) {
                                var imgSrc = (prop.photo === false) ? pt_vars.plugin_url + 'images/image-placeholder.png' : prop.photo[0];

                                list += '<div class="col-xs-6 col-sm-4 col-md-3 is-modal-prop">' + 
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

                            if($('.modal-properties-item').length == data.total) {
                                $('#properties-modal-list-more').hide();
                            }
                        } else {
                            $('#properties-modal-list').append('<div class="col-xs-12">' + pt_vars.modal_no_properties + '</div>');
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

                if ($.inArray(parseInt(id), properties) == -1) {
                    properties.push(parseInt(id));
                    $('#page_header_p_slider').val(properties.toString());

                    $('#p-slider-list').append('<li class="list-group-item" data-id="' + id + '">' + 
                                                    '<img class="pull-left" src="' + image + '" />' + 
                                                    '<div class="list-group-item-info">' + 
                                                        '<div class="list-group-item-info-title">' + 
                                                            '<span class="p-slide-title">' + title + '</span>' + 
                                                        '</div>' + 
                                                        '<div class="list-group-item-info-caption">' + 
                                                            '<span class="p-slide-address">' + address + '</span>' + 
                                                        '</div>' + 
                                                    '</div>' + 
                                                    '<a href="javascript:void(0);" class="pull-right del-btn del-p-slide"><span class="fa fa-trash-o"></span></a>' + 
                                                    '<div class="clearfix"></div>' + 
                                                '</li>');
                    $('#p-slider-list .p-slider-list-empty').remove();
                }

                $('#properties-modal').remove();
            });
        });

        $('#p-slider-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                properties = [];
                $('#p-slider-list li').each(function(index, el) {
                    properties.push(parseInt($(this).attr('data-id')));
                });
                $('#page_header_p_slider').val(properties.toString());
            }
        }).disableSelection();

        $(document).on('click', '.del-p-slide', function(event) {
            var pID = $(this).parent().attr('data-id');

            properties = $.grep(properties, function(id) {
                return id != pID;
            });

            $('#page_header_p_slider').val(properties.toString());
            $(this).parent().remove();

            if(properties.length <= 0) {
                $('#p-slider-list').append('<li class="p-slider-list-empty"><p class="help">' + pt_vars.p_slider_empty + '</p></li>');
            }
        });

        // Video header settings
        $('#header-video-cover-placeholder').click(function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.header_video_cover_title,
                button: {
                    text: pt_vars.header_video_cover_btn
                },
                multiple: false
            });

            frame.on( 'select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#page_header_video_cover').val(value.id);
                    $('#header-video-cover-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.header-video-cover-placeholder-container').addClass('has-image');
                });
            });

            frame.open();
        });

        $('#delete-header-video-cover').click(function() {
            $('#page_header_video_cover').val('');
            $('#header-video-cover-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.header-video-cover-placeholder-container').removeClass('has-image');
        });

        // Image header settings
        $('#header-image-placeholder').click(function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.header_image_title,
                button: {
                    text: pt_vars.header_image_btn
                },
                multiple: false
            });

            frame.on( 'select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#page_header_image').val(value.id);
                    $('#header-image-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.header-image-placeholder-container').addClass('has-image');
                });
            });

            frame.open();
        });

        $('#delete-header-image').click(function() {
            $('#page_header_image').val('');
            $('#header-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.header-image-placeholder-container').removeClass('has-image');
        });

        $('input[name=page_header_image_show_cta]').on('change', function() {
            var selected = $(this).is(':checked');

            if (selected === true) {
                $('.image-cta-settings').show();
            } else {
                $('.image-cta-settings').hide();
            }
        });

        $('input[name=page_header_image_show_search]').on('change', function() {
            var selected = $(this).is(':checked');

            if (selected === true) {
                $('.image-search-settings').show();
            } else {
                $('.image-search-settings').hide();
            }
        });

        // Image header settings
        $('#header-contact-form-image-placeholder').click(function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.contact_form_header_image_title,
                button: {
                    text: pt_vars.contact_form_header_image_btn
                },
                multiple: false
            });

            frame.on( 'select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#page_header_contact_form_image').val(value.id);
                    $('#header-contact-form-image-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.header-contact-form-image-placeholder-container').addClass('has-image');
                });
            });

            frame.open();
        });

        $('#delete-header-contact-form-image').click(function() {
            $('#page_header_contact_form_image').val('');
            $('#header-contact-form-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'images/image-placeholder.png)');
            $('.header-contact-form-image-placeholder-container').removeClass('has-image');
        });

        $('#page_header_image_height').on('change', function() {
            if ($(this).val() == 'half') {
                 $('.pxp-js-page-header-image-half').show();
                 $('.pxp-js-page-header-image-full').hide();
            } else {
                $('.pxp-js-page-header-image-half').hide();
                $('.pxp-js-page-header-image-full').show();
            }
        });
    }

    // Init tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Upload property type marker image
    $('#marker-image-placeholder').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: pt_vars.marker_title,
            button: {
                text: pt_vars.marker_btn
            },
            multiple: false
        });

        frame.on( 'select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#property_type_marker').val(value.id);
                $('#marker-image-placeholder').css('background-image', 'url(' + value.url + ')');
                $('.marker-placeholder-container').addClass('has-image');
            });
        });

        frame.open();
    });

    $('#delete-marker-image').click(function() {
        $('#property_type_marker').val('');
        $('#marker-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/marker-placeholder.png)');
        $('.marker-placeholder-container').removeClass('has-image');
    });

    // Upload testimonial avatar
    $('#testimonial-avatar-image-placeholder').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: pt_vars.testimonial_avatar_title,
            button: {
                text: pt_vars.avatar_btn
            },
            multiple: false
        });

        frame.on( 'select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#testimonial_avatar').val(value.id);
                $('#testimonial-avatar-image-placeholder').css('background-image', 'url(' + value.url + ')');
                $('.testimonial-avatar-placeholder-container').addClass('has-image');
            });
        });

        frame.open();
    });

    $('#delete-testimonial-avatar-image').click(function() {
        $('#testimonial_avatar').val('');
        $('#testimonial-avatar-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/avatar-placeholder.png)');
        $('.testimonial-avatar-placeholder-container').removeClass('has-image');
    });

    // Toggle page custom metaboxes according to the page template
    if (wp.data) {
        const { select, subscribe } = wp.data;
        class PageTemplateSwitcher {
            constructor() {
                this.template = null;
            }
            init() {
                subscribe(() => {
                    if (select('core/editor')) {
                        const newTemplate = select('core/editor').getEditedPostAttribute('template');
        
                        if (newTemplate && newTemplate !== this.template) {
                            this.template = newTemplate;
                            this.changeTemplate();
                        }
                    }
                });
            }
            changeTemplate() {
                $('#page-header-section').toggle(
                    this.template != 'property-search.php' && 
                    this.template != 'contact-page.php' && 
                    this.template != 'wish-list.php' && 
                    this.template != 'my-leads.php' && 
                    this.template != 'account-settings.php' && 
                    this.template != 'my-properties.php' && 
                    this.template != 'paypal-processor.php' && 
                    this.template != 'saved-searches.php' && 
                    this.template != 'submit-property.php' && 
                    this.template != 'agents-list.php'
                );
                $('#page-settings-section').toggle(
                    this.template != 'property-search.php' && 
                    this.template != 'contact-page.php' && 
                    this.template != 'contact-page-office.php' && 
                    this.template != 'wish-list.php' && 
                    this.template != 'my-leads.php' && 
                    this.template != 'account-settings.php' && 
                    this.template != 'my-properties.php' && 
                    this.template != 'paypal-processor.php' && 
                    this.template != 'saved-searches.php' && 
                    this.template != 'submit-property.php' && 
                    this.template != 'agents-list.php' 
                );
                $('#page-template-section').toggle(this.template == 'property-search.php');
                $('#page-contact-settings-section').toggle(this.template == 'contact-page.php');
                $('#page-contact-office-settings-section').toggle(this.template == 'contact-page-office.php');
                $('#page-agents-settings-section').toggle(this.template == 'agents-list.php');
            }
        }
        new PageTemplateSwitcher().init();
    }

    $('#page_template, .editor-page-attributes__template select').change(function() {
        $('#page-header-section').toggle(
            $(this).val() != 'property-search.php' && 
            $(this).val() != 'contact-page.php' && 
            $(this).val() != 'contact-page-office.php' && 
            $(this).val() != 'wish-list.php' && 
            $(this).val() != 'my-leads.php' && 
            $(this).val() != 'account-settings.php' && 
            $(this).val() != 'my-properties.php' && 
            $(this).val() != 'paypal-processor.php' && 
            $(this).val() != 'saved-searches.php' && 
            $(this).val() != 'submit-property.php' && 
            $(this).val() != 'agents-list.php'
        );
        $('#page-settings-section').toggle(
            $(this).val() != 'property-search.php' && 
            $(this).val() != 'contact-page.php' && 
            $(this).val() != 'contact-page-office.php' && 
            $(this).val() != 'wish-list.php' && 
            $(this).val() != 'my-leads.php' && 
            $(this).val() != 'account-settings.php' && 
            $(this).val() != 'my-properties.php' && 
            $(this).val() != 'paypal-processor.php' && 
            $(this).val() != 'saved-searches.php' && 
            $(this).val() != 'submit-property.php' && 
            $(this).val() != 'agents-list.php'
        );
        $('#page-template-section').toggle($(this).val() == 'property-search.php');
        $('#page-contact-settings-section').toggle($(this).val() == 'contact-page.php');
        $('#page-contact-office-settings-section').toggle($(this).val() == 'contact-page-office.php');
        $('#page-agents-settings-section').toggle($(this).val() == 'agents-list.php');
    }).change();

    // Page templates settings
    if($('#page-template-section').length > 0) {
        $('input[name=page_template_type]').on('change', function() {
            var selected = $(this).val();

            $('.page-templates-settings').hide();
            $('.page-templates-' + selected + '-settings').show();
        });
    }

    // Half Map Left Side Settings Slider
    var halfMapLeftWidth = $('#page_template_half_map_left').val();

    if(halfMapLeftWidth != '') {
        $('.half-map-left-slider-min').text(halfMapLeftWidth + '%');
        $('.half-map-left-slider-max').text((100 - parseInt(halfMapLeftWidth)) + '%');
    } else {
        $('.half-map-left-slider-min').text('50%');
        $('.half-map-left-slider-max').text('50%');
        $('#page_template_half_map_left').val(50);
    }

    $("#half-map-left-slider").slider({
        min: 30,
        max: 70,
        step: 5,
        value: $('#page_template_half_map_left').val(),
        slide: function(event, ui) {
            $('.half-map-left-slider-min').text(ui.value + '%');
            $('.half-map-left-slider-max').text((100 - ui.value) + '%');
            $('#page_template_half_map_left').val(ui.value);
        }
    });

    // Half Map Right Side Settings Slider
    var halfMapRightWidth = $('#page_template_half_map_right').val();

    if(halfMapRightWidth != '') {
        $('.half-map-right-slider-min').text(halfMapRightWidth + '%');
        $('.half-map-right-slider-max').text((100 - parseInt(halfMapRightWidth)) + '%');
    } else {
        $('.half-map-right-slider-min').text('50%');
        $('.half-map-right-slider-max').text('50%');
        $('#page_template_half_map_right').val(50);
    }

    $("#half-map-right-slider").slider({
        min: 30,
        max: 70,
        step: 5,
        value: $('#page_template_half_map_right').val(),
        slide: function(event, ui) {
            $('.half-map-right-slider-min').text(ui.value + '%');
            $('.half-map-right-slider-max').text((100 - ui.value) + '%');
            $('#page_template_half_map_right').val(ui.value);
        }
    });

    var offices = [];

    function updateOffices() {
        offices = [];

        if ($('#offices-list .list-group-item').length > 0) {
            $('#offices-list .list-group-item').each(function() {
                var title    = $(this).attr('data-title');
                var address1 = $(this).attr('data-address1');
                var address2 = $(this).attr('data-address2');
                var lat      = $(this).attr('data-lat');
                var lng      = $(this).attr('data-lng');
                var phone    = $(this).attr('data-phone');
                var email    = $(this).attr('data-email');

                var elem = {
                    'title': title,
                    'address1': address1,
                    'address2': address2,
                    'lat': lat,
                    'lng': lng,
                    'phone': phone,
                    'email': email,
                };

                $('#offices-list .offices-list-empty').remove();

                offices.push(elem);
            });

            $('#contact_page_offices').val(JSON.stringify(offices));
        } else {
            $('#offices-list').append('<li class="offices-list-empty"><p class="help">' + pt_vars.offices_empty + '</p></li>');
            $('#contact_page_offices').val('');
            offices = [];
        }
    }

    // Contact page settings
    if ($('#page-contact-settings-section').length > 0) {
        $('#contact_page_office_add').click(function() {
            var title    = $('#contact_page_office_title');
            var address1 = $('#contact_page_office_address_line_1');
            var address2 = $('#contact_page_office_address_line_2');
            var lat      = $('#contact_page_office_lat');
            var lng      = $('#contact_page_office_lng');
            var phone    = $('#contact_page_office_phone');
            var email    = $('#contact_page_office_email');

            if (title.val() != '') {
                $('#offices-list').append(
                    '<li class="list-group-item" ' + 
                        'data-title="' + title.val() + '"' + 
                        'data-address1="' + address1.val() + '"' + 
                        'data-address2="' + address2.val() + '"' + 
                        'data-lat="' + lat.val() + '"' + 
                        'data-lng="' + lng.val() + '"' + 
                        'data-phone="' + phone.val() + '"' + 
                        'data-email="' + email.val() + '"' + 
                    '>' + 
                        '<div class="list-group-item-info">' + 
                            '<div class="list-group-item-info-title">' + 
                                '<div class="editable">' + 
                                    '<span class="slide-title">' + title.val() + '</span>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                        '<a href="javascript:void(0);" class="pull-right del-btn del-office"><span class="fa fa-trash-o"></span></a>' + 
                        '<div class="clearfix"></div>' + 
                    '</li>'
                );

                title.val('');
                address1.val('');
                address2.val('');
                lat.val('');
                lng.val('');
                phone.val('');
                email.val('');

                updateOffices();
            } else {
                alert(pt_vars.offices_error);
                return;
            }
        });

        $('#offices-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                updateOffices();
            }
        }).disableSelection();

        $(document).on('click', '.del-office', function(event) {
            $(this).parent().remove();
            updateOffices();
        });
    }

})(jQuery);