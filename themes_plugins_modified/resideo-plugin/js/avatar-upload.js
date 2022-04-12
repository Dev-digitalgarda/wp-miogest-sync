(function($) {
    "use strict";

    var max = avatar_upload_vars.plupload.max_files;

    if ($('.pxp-account-settings-avatar').length > 0) {
        $('body').addClass('no-overflow');
    }

    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(avatar_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-account-settings-upload-avatar-status').append('<div id="' + file.id + '" class="pxp-account-settings-upload-progress"></div>');
                }

                filesNo = filesNo + 1;
            });

            up.refresh();
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('.pxp-account-settings-avatar').empty();
            $('#' + file.id).addClass('pxp-is-active').html('<div class="progress">' + 
                '<div class="progress-bar" role="progressbar" aria-valuenow="' + file.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + file.percent + '%">' + file.percent + '%</div>' + 
            '</div>');
        });

        // On error occur
        uploader.bind('Error', function(up, err) {
            $('.pxp-account-settings-upload-avatar-status').append('<div>Error: ' + err.code +
                ', Message: ' + err.message +
                (err.file ? ', File: ' + err.file.name : '') +
                '</div>');

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            $('#' + file.id).remove();

            if (result.success) {
                $('.pxp-account-settings-avatar').html(
                    '<div class="pxp-account-settings-avatar-photo has-animation" data-id="' + result.attach + '" style="background-image: url(' + result.html + ')">' +
                        '<button class="pxp-account-settings-avatar-delete-photo"><span class="fa fa-trash-o"></span></button>' + 
                    '</div>'
                );
                $('#as_avatar').val(result.attach);
            }
        });

        $('#aaiu-uploader-avatar').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }
    $('.pxp-account-settings-avatar').on('click', '.pxp-account-settings-avatar-delete-photo', function(e) {
        e.preventDefault();

        var _parent = $(this).parent();

        _parent.removeClass('has-animation')
                .fadeOut('slow', function() {
                    $(this).remove();
                    $('#as_avatar').val('');
                    $('.pxp-account-settings-avatar').html(
                        '<div class="pxp-account-settings-avatar-photo has-animation" data-id="" style="background-image: url(' + avatar_upload_vars.plugin_url + 'images/avatar-default.png)"></div>'
                    );
                });
    });
})(jQuery);
