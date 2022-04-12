(function($) {
    "use strict";

    var max = floor_plan_upload_vars.plupload.max_files;

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

    if ($('.pxp-submit-property-floor-plan').length > 0) {
        $('body').addClass('no-overflow');
    }

    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(floor_plan_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-submit-property-upload-floor-plan-status').append('<div id="' + file.id + '" class="pxp-submit-property-upload-progress"></div>');
                }

                filesNo = filesNo + 1;
            });

            up.refresh();
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('.pxp-submit-property-floor-plan').empty();
            $('#' + file.id).addClass('pxp-is-active').html('<div class="progress">' + 
                '<div class="progress-bar" role="progressbar" aria-valuenow="' + file.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + file.percent + '%">' + file.percent + '%</div>' + 
            '</div>');
        });

        // On error occur
        uploader.bind('Error', function(up, err) {
            $('.pxp-submit-property-upload-floor-plan-status').append('<div>Error: ' + err.code +
                ', Message: ' + err.message +
                (err.file ? ', File: ' + err.file.name : '') +
                '</div>');

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            $('#' + file.id).remove();

            if (result.success) {
                $('.pxp-submit-property-floor-plan').html(
                    '<div class="pxp-submit-property-floor-plan-image has-animation" data-id="' + result.attach + '" data-src="' + result.html + '" style="background-image: url(' + result.html + ')">' +
                        '<button class="pxp-submit-property-floor-plan-delete-image"><span class="fa fa-trash-o"></span></button>' + 
                    '</div>'
                );
                $('#pxp-new-floor-plan-image').val(result.attach);
            }
        });

        $('#aaiu-uploader-floor-plan').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }

    $('.pxp-submit-property-floor-plan').on('click', '.pxp-submit-property-floor-plan-delete-image', function(e) {
        e.preventDefault();

        var _parent = $(this).parent();

        _parent.removeClass('has-animation')
                .fadeOut('slow', function() {
                    $(this).remove();
                    $('#pxp-new-floor-plan-image').val('');
                    $('.pxp-submit-property-floor-plan').html(
                        '<div class="pxp-submit-property-floor-plan-image" data-id="" data-src="" style="background-image: url(' + floor_plan_upload_vars.plugin_url + 'images/image-placeholder.png)"></div>'
                    );
                });
    });

    if ($('#new_floor_plans').length > 0) {
        var data = {
            'plans' : []
        }
        var floorPlans = '';
        var floorPlansRaw = $('#new_floor_plans').val();

        if (floorPlansRaw != '') {
            floorPlans = jsonParser(decodeURIComponent(floorPlansRaw.replace(/\+/g, ' ')));

            if (floorPlans !== null) {
                data = floorPlans;
            }
        }

        $('.pxp-add-floor-plan-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-new-floor-plan').show();
        });

        $('.pxp-new-floor-plan-ok-btn').on('click', function(event) {
            event.preventDefault();

            var title       = $('#pxp-new-floor-plan-title').val();
            var beds        = $('#pxp-new-floor-plan-beds').val();
            var baths       = $('#pxp-new-floor-plan-baths').val();
            var size        = $('#pxp-new-floor-plan-size').val();
            var description = $('#pxp-new-floor-plan-description').val();
            var image       = $('.pxp-submit-property-floor-plan-image').attr('data-id');
            var image_src   = $('.pxp-submit-property-floor-plan-image').attr('data-src');

            data.plans.push({
                'title'      : title,
                'beds'       : beds,
                'baths'      : baths,
                'size'       : size,
                'description': description,
                'image'      : image
            });

            $('#new_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));

            var info = '';
            if (beds != '') {
                info += beds + ' ' + floor_plan_upload_vars.beds_label + ' <span>|</span> ';
            }
            if (baths != '') {
                info += baths + ' ' + floor_plan_upload_vars.baths_label + ' <span>|</span> ';
            }
            if (size != '') {
                info += size + ' ' + floor_plan_upload_vars.unit;
            }

            $('#pxp-submit-property-floor-plans-list').append(
                '<li class="pxp-sortable-list-item rounded-lg" data-id="' + image + '" ' + 
                        'data-title="' + title + '" ' + 
                        'data-beds="' + beds + '" ' + 
                        'data-baths="' + baths + '" ' + 
                        'data-size="' + size + '" ' + 
                        'data-description="' + description + '" ' + 
                        'data-src="' + image_src + '">' + 
                    '<div class="row align-items-center pxp-submit-property-floor-plan-item">' + 
                        '<div class="col-3 col-sm-2">' + 
                            '<div class="pxp-sortable-list-item-photo pxp-cover rounded-lg" style="background-image: url(' + image_src + ');"></div>' + 
                        '</div>' + 
                        '<div class="col-9 col-sm-10">' + 
                            '<div class="row align-items-center">' + 
                                '<div class="col-9 col-sm-8 col-lg-10">' + 
                                    '<div class="row align-items-center">' + 
                                        '<div class="col-lg-7 pxp-sortable-list-item-title">' + title + '</div>' + 
                                        '<div class="col-lg-5">' + 
                                            '<div class="pxp-sortable-list-item-features">' + info + '</div>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="col-3 col-sm-4 col-lg-2 text-right">' + 
                                    '<a href="javascript:void(0);" class="pxp-sortable-list-item-edit pxp-submit-property-floor-plans-item-edit-new"><span class="fa fa-pencil"></span></a>' + 
                                    '<a href="javascript:void(0);" class="pxp-sortable-list-item-delete pxp-submit-property-floor-plans-item-delete-new"><span class="fa fa-trash-o"></span></a>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                    '</div>' + 
                '</li>'
            );

            $('#pxp-new-floor-plan-title').val('');
            $('#pxp-new-floor-plan-beds').val('');
            $('#pxp-new-floor-plan-baths').val('');
            $('#pxp-new-floor-plan-size').val('');
            $('#pxp-new-floor-plan-description').val('');
            $('#pxp-new-floor-plan-image').val('');

            $('.pxp-submit-property-floor-plan-image')
                .css('background-image', 'url(' + floor_plan_upload_vars.plugin_url + 'images/image-placeholder.png)')
                .attr({
                    'data-id': '',
                    'data-src': ''
                })
                .removeClass('has-animation')
                .empty();

            $('.pxp-new-floor-plan').hide();
            $('.pxp-add-floor-plan-btn').show();

            $('.pxp-submit-property-floor-plans-item-edit-new').unbind('click').on('click', function(event) {
                editFloorPlan($(this));
            });
            $('.pxp-submit-property-floor-plans-item-delete-new').unbind('click').on('click', function(event) {
                delFloorPlan($(this));
            });

            $('.pxp-submit-property-floor-plans-item-delete').on('click', function() {
                event.preventDefault();
                $(this).parent().parent().parent().parent().parent().remove();
    
                data.plans = [];
    
                $('#pxp-submit-property-floor-plans-list li').each(function(index, el) {
                    data.plans.push({
                        'title'      : $(this).attr('data-title'),
                        'beds'       : $(this).attr('data-beds'),
                        'baths'      : $(this).attr('data-baths'),
                        'size'       : $(this).attr('data-size'),
                        'description': $(this).attr('data-description'),
                        'image'      : $(this).attr('data-id')
                    });
                });
    
                $('#new_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
            });
        });

        $('.pxp-new-floor-plan-cancel-btn').on('click', function(event) {
            event.preventDefault();

            $('#pxp-new-floor-plan-title').val('');
            $('#pxp-new-floor-plan-beds').val('');
            $('#pxp-new-floor-plan-baths').val('');
            $('#pxp-new-floor-plan-size').val('');
            $('#pxp-new-floor-plan-description').val('');
            $('#pxp-new-floor-plan-image').val('');

            $('.pxp-submit-property-floor-plan-image')
                .css('background-image', 'url(' + floor_plan_upload_vars.plugin_url + 'images/image-placeholder.png)')
                .attr({
                    'data-id': '',
                    'data-src': ''
                })
                .removeClass('has-animation')
                .empty();

            $('.pxp-new-floor-plan').hide();
            $('.pxp-add-floor-plan-btn').show();
        });

        $('#pxp-submit-property-floor-plans-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                data.plans = [];

                $('#pxp-submit-property-floor-plans-list li').each(function(index, el) {
                    data.plans.push({
                        'title'      : $(this).attr('data-title'),
                        'beds'       : $(this).attr('data-beds'),
                        'baths'      : $(this).attr('data-baths'),
                        'size'       : $(this).attr('data-size'),
                        'description': $(this).attr('data-description'),
                        'image'      : $(this).attr('data-id')
                    });

                });

                $('#new_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
            }
        }).disableSelection();

        $('.pxp-submit-property-floor-plans-item-delete').on('click', function(event) {
            event.preventDefault();
            delFloorPlan($(this));
        });

        $('.pxp-submit-property-floor-plans-item-edit').on('click', function(event) {
            event.preventDefault();
            editFloorPlan($(this));
        });

        function delFloorPlan(btn) {
            btn.parent().parent().parent().parent().parent().remove();

            data.plans = [];

            $('#pxp-submit-property-floor-plans-list li').each(function(index, el) {
                data.plans.push({
                    'title'      : $(this).attr('data-title'),
                    'beds'       : $(this).attr('data-beds'),
                    'baths'      : $(this).attr('data-baths'),
                    'size'       : $(this).attr('data-size'),
                    'description': $(this).attr('data-description'),
                    'image'      : $(this).attr('data-id')
                });
            });

            $('#new_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
        }

        function editFloorPlan(btn) {
            var editImgSrc      = btn.parent().parent().parent().parent().parent().attr('data-src');
            var editImgId       = btn.parent().parent().parent().parent().parent().attr('data-id');
            var editTitle       = btn.parent().parent().parent().parent().parent().attr('data-title');
            var editBeds        = btn.parent().parent().parent().parent().parent().attr('data-beds');
            var editBaths       = btn.parent().parent().parent().parent().parent().attr('data-baths');
            var editSize        = btn.parent().parent().parent().parent().parent().attr('data-size');
            var editDescription = btn.parent().parent().parent().parent().parent().attr('data-description');

            var editFloorPlanForm = 
                '<div class="pxp-edit-floor-plan rounded-lg">' + 
                    '<h4>' + floor_plan_upload_vars.edit_floor_plan + '</h4>' + 
                    '<div class="mt-3 mt-md-4">' + 
                        '<div class="form-group">' + 
                            '<label for="pxp-edit-floor-plan-title">' + floor_plan_upload_vars.edit_floor_plan_title_label + '</label>' + 
                            '<input type="text" name="pxp-edit-floor-plan-title" id="pxp-edit-floor-plan-title" class="form-control" placeholder="' + floor_plan_upload_vars.edit_floor_plan_title_placeholder + '" value="' + editTitle + '">' + 
                        '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-sm-4">' + 
                                '<div class="form-group">' + 
                                    '<label for="pxp-edit-floor-plan-beds">' + floor_plan_upload_vars.edit_floor_plan_beds_label + '</label>' + 
                                    '<input type="text" name="pxp-edit-floor-plan-beds" id="pxp-edit-floor-plan-beds" class="form-control" placeholder="' + floor_plan_upload_vars.edit_floor_plan_beds_placeholder + '" value="' + editBeds + '">' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-sm-4">' + 
                                '<div class="form-group">' + 
                                    '<label for="pxp-edit-floor-plan-baths">' + floor_plan_upload_vars.edit_floor_plan_baths_label + '</label>' + 
                                    '<input type="text" name="pxp-edit-floor-plan-baths" id="pxp-edit-floor-plan-baths" class="form-control" placeholder="' + floor_plan_upload_vars.edit_floor_plan_baths_placeholder + '" value="' + editBaths + '">' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-sm-4">' + 
                                '<div class="form-group">' + 
                                    '<label for="pxp-edit-floor-plan-size">' + floor_plan_upload_vars.edit_floor_plan_size_label + ' (' + floor_plan_upload_vars.unit + ')</label>' + 
                                    '<input type="text" name="pxp-edit-floor-plan-size" id="pxp-edit-floor-plan-size" class="form-control" placeholder="' + floor_plan_upload_vars.edit_floor_plan_size_placeholder + '" value="' + editSize + '">' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                        '<div class="row">' + 
                            '<div class="col-sm-8">' + 
                                '<div class="form-group">' + 
                                    '<label for="pxp-edit-floor-plan-description">' + floor_plan_upload_vars.edit_floor_plan_description_label + '</label>' + 
                                    '<textarea id="pxp-edit-floor-plan-description" name="pxp-edit-floor-plan-description" class="form-control" placeholder="' + floor_plan_upload_vars.edit_floor_plan_description_placeholder + '">' + editDescription + '</textarea>' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-sm-4">' + 
                                '<div class="form-group">' + 
                                    '<div class="pxp-edit-floor-plan-image-label">' + floor_plan_upload_vars.edit_floor_plan_image_label + '</div>' + 
                                    '<div class="position-relative">' + 
                                        '<div id="aaiu-upload-container-floor-plan-edit">' + 
                                            '<div class="pxp-submit-property-floor-plan-edit">';
            if (editImgId != '') {
                editFloorPlanForm += 
                                                '<div class="pxp-submit-property-floor-plan-image-edit has-animation" style="background-image: url(' + editImgSrc + ');" data-id="' + editImgId + '" data-src="' + editImgSrc + '">' + 
                                                    '<button class="pxp-submit-property-floor-plan-delete-image-edit"><span class="fa fa-trash-o"></span></button>' + 
                                                '</div>';
            } else {
                editFloorPlanForm += 
                                                '<div class="pxp-submit-property-floor-plan-image-edit" style="background-image: url(' + floor_plan_upload_vars.plugin_url + 'images/image-placeholder.png);" data-id=""></div>';
            }
            editFloorPlanForm += 
                                            '</div>' + 
                                            '<div class="pxp-submit-property-upload-floor-plan-status-edit"></div>' + 
                                            '<div class="clearfix"></div>' + 
                                            '<a role="button" id="aaiu-uploader-floor-plan-edit" class="pxp-browser-photos-btn"><span class="fa fa-picture-o"></span>&nbsp;&nbsp;&nbsp;' + floor_plan_upload_vars.edit_floor_plan_upload_btn + '</a>' + 
                                            '<input type="hidden" name="pxp-edit-floor-plan-image" id="pxp-edit-floor-plan-image" value="">' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                        '</div>' + 
                        '<a href="javascript:void(0);" class="btn pxp-edit-floor-plan-ok-btn mr-1">' + floor_plan_upload_vars.edit_floor_plan_ok_btn + '</a>' + 
                        '<a href="javascript:void(0);" class="btn pxp-edit-floor-plan-cancel-btn">' + floor_plan_upload_vars.edit_floor_plan_cancel_btn + '</a>' + 
                    '</div>' + 
                '</div>';

            btn.parent().parent().parent().parent().hide();
            btn.parent().parent().parent().parent().parent().append(editFloorPlanForm);

            $('#pxp-submit-property-floor-plans-list').sortable('disable');
            $('#pxp-submit-property-floor-plans-list .pxp-sortable-list-item').css('cursor', 'auto');
            $('.pxp-submit-property-floor-plans-item-edit').hide();
            $('.pxp-submit-property-floor-plans-item-delete').hide();
            $('.pxp-submit-property-floor-plans-item-edit-new').hide();
            $('.pxp-submit-property-floor-plans-item-delete-new').hide();
            $('.pxp-add-floor-plan-btn').hide();
            $('.pxp-new-floor-plan').hide();

            if (typeof(plupload) !== 'undefined') {
                var uploader = new plupload.Uploader(floor_plan_upload_vars.plupload_edit);

                uploader.init();

                uploader.bind('FilesAdded', function(up, files) {
                    var filesNo = 0;

                    $.each(files, function(i, file) {
                        if (filesNo < max) {
                            $('.pxp-submit-property-upload-floor-plan-status-edit').append('<div id="' + file.id + '" class="pxp-submit-property-upload-progress"></div>');
                        }

                        filesNo = filesNo + 1;
                    });

                    up.refresh();
                    uploader.start();
                });

                uploader.bind('UploadProgress', function(up, file) {
                    $('.pxp-submit-property-floor-plan-edit').empty();
                    $('#' + file.id).addClass('pxp-is-active').html('<div class="progress">' + 
                        '<div class="progress-bar" role="progressbar" aria-valuenow="' + file.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + file.percent + '%">' + file.percent + '%</div>' + 
                    '</div>');
                });

                // On error occur
                uploader.bind('Error', function(up, err) {
                    $('.pxp-submit-property-upload-floor-plan-status-edit').append('<div>Error: ' + err.code +
                        ', Message: ' + err.message +
                        (err.file ? ', File: ' + err.file.name : '') +
                        '</div>');

                    up.refresh();
                });

                uploader.bind('FileUploaded', function(up, file, response) {
                    var result = $.parseJSON(response.response);

                    $('#' + file.id).remove();

                    if (result.success) {
                        $('.pxp-submit-property-floor-plan-edit').html(
                            '<div class="pxp-submit-property-floor-plan-image-edit has-animation" data-id="' + result.attach + '" data-src="' + result.html + '" style="background-image: url(' + result.html + ')">' +
                                '<button class="pxp-submit-property-floor-plan-delete-image-edit"><span class="fa fa-trash-o"></span></button>' + 
                            '</div>'
                        );
                        $('#pxp-edit-floor-plan-image').val(result.attach);
                    }
                });

                $('#aaiu-uploader-floor-plan-edit').click(function(e) {
                    uploader.start();
                    e.preventDefault();
                });
            }

            $('.pxp-submit-property-floor-plan-edit').on('click', '.pxp-submit-property-floor-plan-delete-image-edit', function(e) {
                e.preventDefault();

                var _parent = $(this).parent();

                _parent.removeClass('has-animation')
                        .fadeOut('slow', function() {
                            $(this).remove();
                            $('#pxp-edit-floor-plan-image').val('');
                            $('.pxp-submit-property-floor-plan-edit').html(
                                '<div class="pxp-submit-property-floor-plan-image-edit" data-id="" data-src="" style="background-image: url(' + floor_plan_upload_vars.plugin_url + 'images/image-placeholder.png)"></div>'
                            );
                        });
            });

            $('.pxp-edit-floor-plan-ok-btn').on('click', function(event) {
                var eImgSrc      = $(this).parent().find('.pxp-submit-property-floor-plan-image-edit').attr('data-src');
                var eImgId       = $(this).parent().find('.pxp-submit-property-floor-plan-image-edit').attr('data-id');
                var eTitle       = $(this).parent().find('#pxp-edit-floor-plan-title').val();
                var eBeds        = $(this).parent().find('#pxp-edit-floor-plan-beds').val();
                var eBaths       = $(this).parent().find('#pxp-edit-floor-plan-baths').val();
                var eSize        = $(this).parent().find('#pxp-edit-floor-plan-size').val();
                var eDescription = $(this).parent().find('#pxp-edit-floor-plan-description').val();
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

                //listElem.find('.pxp-sortable-list-item-photo').css('backgroud-image', 'url(' + eImgSrc + ')');
                listElem.find('.pxp-sortable-list-item-photo').parent().html('<div class="pxp-sortable-list-item-photo pxp-cover rounded-lg" style="background-image: url(' + eImgSrc + ');"></div>');
                listElem.find('.pxp-sortable-list-item-title').text(eTitle);

                var eInfo = '';
                if (eBeds != '') {
                    eInfo += eBeds + ' ' + floor_plan_upload_vars.beds_label + ' <span>|</span> ';
                }
                if (eBaths != '') {
                    eInfo += eBaths + ' ' + floor_plan_upload_vars.baths_label + ' <span>|</span> ';
                }
                if (eSize != '') {
                    eInfo += eSize + ' ' + floor_plan_upload_vars.unit;
                }

                listElem.find('.pxp-sortable-list-item-features').html(eInfo);

                $(this).parent().parent().remove();
                listElem.find('.pxp-submit-property-floor-plan-item').show();

                $('#pxp-submit-property-floor-plans-list').sortable('enable');
                $('#pxp-submit-property-floor-plans-list .pxp-sortable-list-item').css('cursor', 'move');
                $('.pxp-submit-property-floor-plans-item-edit').show();
                $('.pxp-submit-property-floor-plans-item-delete').show();
                $('.pxp-submit-property-floor-plans-item-edit-new').show();
                $('.pxp-submit-property-floor-plans-item-delete-new').show();
                $('.pxp-add-floor-plan-btn').show();

                data.plans = [];
                $('#pxp-submit-property-floor-plans-list li').each(function(index, el) {
                    data.plans.push({
                        'title'      : $(this).attr('data-title'),
                        'beds'       : $(this).attr('data-beds'),
                        'baths'      : $(this).attr('data-baths'),
                        'size'       : $(this).attr('data-size'),
                        'description': $(this).attr('data-description'),
                        'image'      : $(this).attr('data-id')
                    });
                });

                $('#new_floor_plans').val(fixedEncodeURIComponent(JSON.stringify(data)));
            });

            $('.pxp-edit-floor-plan-cancel-btn').on('click', function(event) {
                var listElem = $(this).parent().parent().parent();

                $(this).parent().parent().remove();
                listElem.find('.pxp-submit-property-floor-plan-item').show();

                $('#pxp-submit-property-floor-plans-list').sortable('enable');
                $('#pxp-submit-property-floor-plans-list .pxp-sortable-list-item').css('cursor', 'move');
                $('.pxp-submit-property-floor-plans-item-edit').show();
                $('.pxp-submit-property-floor-plans-item-delete').show();
                $('.pxp-submit-property-floor-plans-item-edit-new').show();
                $('.pxp-submit-property-floor-plans-item-delete-new').show();
                $('.pxp-add-floor-plan-btn').show();
            });
        }
    }
})(jQuery);
