(function($) {
    "use strict";

    var max = gallery_upload_vars.plupload.max_files;
    var gallery = [];

    if ($('.pxp-submit-property-gallery').length > 0) {
        $('body').addClass('no-overflow');

        if ($('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').length >= max) {
            $('#aaiu-uploader-gallery').hide();
        }
    }

    $('.pxp-submit-property-gallery').sortable({
        placeholder: 'sortable-placeholder',
        opacity: 0.7,
        start: function(event, ui) {
            $('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').removeClass('has-animation');
        },
        stop: function(event, ui) {
            gallery = [];

            $('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').addClass('has-animation');
            $('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').each(function(index, el) {
                gallery.push(parseInt($(this).attr('data-id')));
            });
            $('#new_gallery').val(gallery.toString());
        }
    }).disableSelection();

    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(gallery_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-submit-property-upload-gallery-status').append('<div id="' + file.id + '" class="pxp-submit-property-upload-progress"></div>');
                }

                filesNo = filesNo + 1;
            });

            up.refresh();
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('#' + file.id).addClass('pxp-is-active').html('<div class="progress">' + 
                '<div class="progress-bar" role="progressbar" aria-valuenow="' + file.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + file.percent + '%">' + file.percent + '%</div>' + 
            '</div>');
        });

        // On error occur
        uploader.bind('Error', function(up, err) {
            $('.pxp-submit-property-upload-gallery-status').append('<div>Error: ' + err.code +
                ', Message: ' + err.message +
                (err.file ? ', File: ' + err.file.name : '') +
                '</div>');

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            $('#' + file.id).remove();

            if (result.success) {
                if ($('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').length < max) {
                    gallery = [];

                    $('.pxp-submit-property-gallery').append(
                        '<div class="pxp-submit-property-gallery-photo has-animation" data-id="' + result.attach + '" style="background-image: url(' + result.html + ')">' +
                            '<button class="pxp-submit-property-gallery-delete-photo"><span class="fa fa-trash-o"></span></button>' + 
                        '</div>'
                    );
                    $('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').each(function(index, el) {
                        gallery.push(parseInt($(this).attr('data-id')));
                    });
                    $('#new_gallery').val(gallery.toString());

                    if ($('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').length >= max) {
                        $('#aaiu-uploader-gallery').hide();
                    }
                } else {
                    $('#aaiu-uploader-gallery').hide();
                }
            }
        });

        $('#aaiu-uploader-gallery').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }

    $('.pxp-submit-property-gallery').on('click', '.pxp-submit-property-gallery-delete-photo', function(e) {
        e.preventDefault();

        var _parent = $(this).parent();

        gallery = [];

        _parent.removeClass('has-animation')
                .fadeOut('slow', function() {
                    $(this).remove();

                    $('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').each(function(index, el) {
                        gallery.push(parseInt($(this).attr('data-id')));
                    });
                    $('#new_gallery').val(gallery.toString());
    
                    if ($('.pxp-submit-property-gallery .pxp-submit-property-gallery-photo').length < max) {
                        $('#aaiu-uploader-gallery').show();
                    }
                });
    });
})(jQuery);
