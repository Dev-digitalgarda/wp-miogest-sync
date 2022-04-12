(function($) {
    "use strict";

    $(function() {
        $('.color-field').wpColorPicker();
    });

    // Property custom fields
    if ($('#customFieldsTable').length > 0) {
        $('.table-field-type').each(function(index, el) {
            if ($(this).val() == 'list_field') {
                $(this).next('input').show();
            }
        });
    }

    $("#custom_field_type").change(function() {
        if ($(this).val() == 'list_field') {
            $('#custom_list_field_items').show();
            $(this).siblings('.help').show();
        } else {
            $('#custom_list_field_items').val('').hide();
            $(this).siblings('.help').hide();
        }
    });

    $(".table-field-type").each(function(index, el) {
        $(this).change(function() {
            if ($(this).val() == 'list_field') {
                $(this).next('input').show();
            } else {
                $(this).next('input').val('').hide();
            }
        });
    });

    $('#add_fields_btn').click(function() {
        var security = $('#securityAddCustomFields').val();
        var _self = $(this);

        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'     : 'resideo_add_custom_fields',
                'name'       : $('#custom_field_name').val(),
                'label'      : $('#custom_field_label').val(),
                'type'       : $('#custom_field_type').val(),
                'list'       : $('#custom_list_field_items').val(),
                'mandatory'  : $('#custom_field_mandatory').val(),
                'position'   : $('#custom_field_position').val(),
                'search'     : $('#custom_field_search').val(),
                'filter'     : $('#custom_field_filter').val(),
                'comparison' : $('#custom_field_comparison').val(),
                'security'   : security
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=fields';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-field', function() {
        var _self = $(this);
        var field_name = $(this).attr('data-row');
        var security = $('#securityAddCustomFields').val();
        var _self = $(this);

        _self.children('.preloader').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'     : 'resideo_delete_custom_fields',
                'field_name' : field_name,
                'security'   : security
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=fields';
                } else {
                    _self.children('.preloader').hide();
                }
            }
        });
    });

    // Contact fields
    if ($('#contactFieldsTable').length > 0) {
        $('.table-contact-field-type').each(function(index, el) {
            if ($(this).val() == 'select_field') {
                $(this).next('input').show();
            }
        });
    }

    $("#contact_field_type").change(function() {
        if ($(this).val() == 'select_field') {
            $('#contact_list_field_items').show();
            $(this).siblings('.help').show();
        } else {
            $('#contact_list_field_items').val('').hide();
            $(this).siblings('.help').hide();
        }
    });

    $(".table-contact-field-type").each(function(index, el) {
        $(this).change(function() {
            if ($(this).val() == 'select_field') {
                $(this).next('input').show();
            } else {
                $(this).next('input').val('').hide();
            }
        });
    });

    $('#add_contact_fields_btn').click(function() {
        var security = $('#securityAddContactFields').val();
        var _self = $(this);

        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_add_contact_fields',
                'name'     : $('#contact_field_name').val(),
                'label'    : $('#contact_field_label').val(),
                'type'     : $('#contact_field_type').val(),
                'list'     : $('#contact_list_field_items').val(),
                'mandatory': $('#contact_field_mandatory').val(),
                'position' : $('#contact_field_position').val(),
                'security' : security
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=contact_fields';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-contact-field', function() {
        var _self = $(this);
        var field_name = $(this).attr('data-row');
        var security = $('#securityAddContactFields').val();
        var _self = $(this);

        _self.children('.preloader').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'    : 'resideo_delete_contact_fields',
                'field_name': field_name,
                'security'  : security
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=contact_fields';
                } else {
                    _self.children('.preloader').hide();
                }
            }
        });
    });

    // Amenities
    $('#add_amenity_btn').click(function() {
        var security = $('#securityAddAmenities').val();
        var _self = $(this);

        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_add_amenities',
                'name'     : $('#amenity_name').val(),
                'label'    : $('#amenity_label').val(),
                'icon'     : $('#amenity_icon').val(),
                'position' : $('#amenity_position').val(),
                'security' : security
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=amenities';
                } else {
                    _self.removeAttr('disabled');   
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.delete-amenity', function() {
        var _self = $(this);
        var amenity_name = $(this).attr('data-row');
        var security = $('#securityAddAmenities').val();

        _self.children('.preloader').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action': 'resideo_delete_amenities',
                'amenity_name': amenity_name,
                'security': security
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=amenities';
                } else {
                    _self.children('.preloader').hide();
                }
            }
        });
    });

    // Add city to the cities list
    $('#add_city_btn').click(function() {
        var security = $('#securityAddCities').val();
        var _self    = $(this);

        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_add_cities',
                'id'       : $('#city_id').val(),
                'name'     : $('#city_name').val(),
                'position' : $('#city_position').val(),
                'security' : security
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=cities';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    // Remove a city from the city list
    $(document).on('click', '.delete-city', function() {
        var _self    = $(this);
        var city_id  = $(this).attr('data-row');
        var security = $('#securityAddCities').val();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_delete_cities',
                'city_id'  : city_id,
                'security' : security
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=cities';
                }
            }
        });
    });

    // Add neighborhood to the cities list
    $('#add_neighborhood_btn').click(function() {
        var security = $('#securityAddNeighborhoods').val();
        var _self    = $(this);

        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_add_neighborhoods',
                'id'       : $('#neighborhood_id').val(),
                'name'     : $('#neighborhood_name').val(),
                'position' : $('#neighborhood_position').val(),
                'security' : security
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=neighborhoods';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    // Remove a neighborhood from the city list
    $(document).on('click', '.delete-neighborhood', function() {
        var _self           = $(this);
        var neighborhood_id = $(this).attr('data-row');
        var security        = $('#securityAddNeighborhoods').val();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'          : 'resideo_delete_neighborhoods',
                'neighborhood_id' : neighborhood_id,
                'security'        : security
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=neighborhoods';
                }
            }
        });
    });


    function openIconsWindow(btn) {
        var faIcons = ['fa fa-500px', 'fa fa-address-book', 'fa fa-address-book-o', 'fa fa-address-card', 'fa fa-address-card-o', 'fa fa-adjust', 'fa fa-adn', 'fa fa-align-center', 'fa fa-align-justify', 'fa fa-align-left', 'fa fa-align-right', 'fa fa-amazon', 'fa fa-ambulance', 'fa fa-american-sign-language-interpreting', 'fa fa-anchor', 'fa fa-android', 'fa fa-angellist', 'fa fa-angle-double-down', 'fa fa-angle-double-left', 'fa fa-angle-double-right', 'fa fa-angle-double-up', 'fa fa-angle-down', 'fa fa-angle-left', 'fa fa-angle-right', 'fa fa-angle-up', 'fa fa-apple', 'fa fa-archive', 'fa fa-area-chart', 'fa fa-arrow-circle-down', 'fa fa-arrow-circle-left', 'fa fa-arrow-circle-o-down', 'fa fa-arrow-circle-o-left', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-o-up', 'fa fa-arrow-circle-right', 'fa fa-arrow-circle-up', 'fa fa-arrow-down', 'fa fa-arrow-left', 'fa fa-arrow-right', 'fa fa-arrow-up', 'fa fa-arrows', 'fa fa-arrows-alt', 'fa fa-arrows-h', 'fa fa-arrows-v', 'fa fa-asl-interpreting', 'fa fa-assistive-listening-systems', 'fa fa-asterisk', 'fa fa-at', 'fa fa-audio-description', 'fa fa-automobile', 'fa fa-backward', 'fa fa-balance-scale', 'fa fa-ban', 'fa fa-bandcamp', 'fa fa-bank', 'fa fa-bar-chart', 'fa fa-bar-chart-o', 'fa fa-barcode', 'fa fa-bars', 'fa fa-bath', 'fa fa-bathtub', 'fa fa-battery', 'fa fa-battery-0', 'fa fa-battery-1', 'fa fa-battery-2', 'fa fa-battery-3', 'fa fa-battery-4', 'fa fa-battery-empty', 'fa fa-battery-full', 'fa fa-battery-half', 'fa fa-battery-quarter', 'fa fa-battery-three-quarters', 'fa fa-bed', 'fa fa-beer', 'fa fa-behance', 'fa fa-behance-square', 'fa fa-bell', 'fa fa-bell-o', 'fa fa-bell-slash', 'fa fa-bell-slash-o', 'fa fa-bicycle', 'fa fa-binoculars', 'fa fa-birthday-cake', 'fa fa-bitbucket', 'fa fa-bitbucket-square', 'fa fa-bitcoin', 'fa fa-black-tie', 'fa fa-blind', 'fa fa-bluetooth', 'fa fa-bluetooth-b', 'fa fa-bold', 'fa fa-bolt', 'fa fa-bomb', 'fa fa-book', 'fa fa-bookmark', 'fa fa-bookmark-o', 'fa fa-braille', 'fa fa-briefcase', 'fa fa-btc', 'fa fa-bug', 'fa fa-building', 'fa fa-building-o', 'fa fa-bullhorn', 'fa fa-bullseye', 'fa fa-bus', 'fa fa-buysellads', 'fa fa-cab', 'fa fa-calculator', 'fa fa-calendar', 'fa fa-calendar-check-o', 'fa fa-calendar-minus-o', 'fa fa-calendar-o', 'fa fa-calendar-plus-o', 'fa fa-calendar-times-o', 'fa fa-camera', 'fa fa-camera-retro', 'fa fa-car', 'fa fa-caret-down', 'fa fa-caret-left', 'fa fa-caret-right', 'fa fa-caret-square-o-down', 'fa fa-caret-square-o-left', 'fa fa-caret-square-o-right', 'fa fa-caret-square-o-up', 'fa fa-caret-up', 'fa fa-cart-arrow-down', 'fa fa-cart-plus', 'fa fa-cc', 'fa fa-cc-amex', 'fa fa-cc-diners-club', 'fa fa-cc-discover', 'fa fa-cc-jcb', 'fa fa-cc-mastercard', 'fa fa-cc-paypal', 'fa fa-cc-stripe', 'fa fa-cc-visa', 'fa fa-certificate', 'fa fa-chain', 'fa fa-chain-broken', 'fa fa-check', 'fa fa-check-circle', 'fa fa-check-circle-o', 'fa fa-check-square', 'fa fa-check-square-o', 'fa fa-chevron-circle-down', 'fa fa-chevron-circle-left', 'fa fa-chevron-circle-right', 'fa fa-chevron-circle-up', 'fa fa-chevron-down', 'fa fa-chevron-left', 'fa fa-chevron-right', 'fa fa-chevron-up', 'fa fa-child', 'fa fa-chrome', 'fa fa-circle', 'fa fa-circle-o', 'fa fa-circle-o-notch', 'fa fa-circle-thin', 'fa fa-clipboard', 'fa fa-clock-o', 'fa fa-clone', 'fa fa-close', 'fa fa-cloud', 'fa fa-cloud-download', 'fa fa-cloud-upload', 'fa fa-cny', 'fa fa-code', 'fa fa-code-fork', 'fa fa-codepen', 'fa fa-codiepie', 'fa fa-coffee', 'fa fa-cog', 'fa fa-cogs', 'fa fa-columns', 'fa fa-comment', 'fa fa-comment-o', 'fa fa-commenting', 'fa fa-commenting-o', 'fa fa-comments', 'fa fa-comments-o', 'fa fa-compass', 'fa fa-compress', 'fa fa-connectdevelop', 'fa fa-contao', 'fa fa-copy', 'fa fa-copyright', 'fa fa-creative-commons', 'fa fa-credit-card', 'fa fa-credit-card-alt', 'fa fa-crop', 'fa fa-crosshairs', 'fa fa-css3', 'fa fa-cube', 'fa fa-cubes', 'fa fa-cut', 'fa fa-cutlery', 'fa fa-dashboard', 'fa fa-dashcube', 'fa fa-database', 'fa fa-deaf', 'fa fa-deafness', 'fa fa-dedent', 'fa fa-delicious', 'fa fa-desktop', 'fa fa-deviantart', 'fa fa-diamond', 'fa fa-digg', 'fa fa-dollar', 'fa fa-dot-circle-o', 'fa fa-download', 'fa fa-dribbble', 'fa fa-drivers-license', 'fa fa-drivers-license-o', 'fa fa-dropbox', 'fa fa-drupal', 'fa fa-edge', 'fa fa-edit', 'fa fa-eercast', 'fa fa-eject', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-empire', 'fa fa-envelope', 'fa fa-envelope-o', 'fa fa-envelope-open', 'fa fa-envelope-open-o', 'fa fa-envelope-square', 'fa fa-envira', 'fa fa-eraser', 'fa fa-etsy', 'fa fa-eur', 'fa fa-euro', 'fa fa-exchange', 'fa fa-exclamation', 'fa fa-exclamation-circle', 'fa fa-exclamation-triangle', 'fa fa-expand', 'fa fa-expeditedssl', 'fa fa-external-link', 'fa fa-external-link-square', 'fa fa-eye', 'fa fa-eye-slash', 'fa fa-eyedropper', 'fa fa-fa', 'fa fa-facebook', 'fa fa-facebook-f', 'fa fa-facebook-official', 'fa fa-facebook-square', 'fa fa-fast-backward', 'fa fa-fast-forward', 'fa fa-fax', 'fa fa-feed', 'fa fa-female', 'fa fa-fighter-jet', 'fa fa-file', 'fa fa-file-archive-o', 'fa fa-file-audio-o', 'fa fa-file-code-o', 'fa fa-file-excel-o', 'fa fa-file-image-o', 'fa fa-file-movie-o', 'fa fa-file-o', 'fa fa-file-pdf-o', 'fa fa-file-photo-o', 'fa fa-file-picture-o', 'fa fa-file-powerpoint-o', 'fa fa-file-sound-o', 'fa fa-file-text', 'fa fa-file-text-o', 'fa fa-file-video-o', 'fa fa-file-word-o', 'fa fa-file-zip-o', 'fa fa-files-o', 'fa fa-film', 'fa fa-filter', 'fa fa-fire', 'fa fa-fire-extinguisher', 'fa fa-firefox', 'fa fa-first-order', 'fa fa-flag', 'fa fa-flag-checkered', 'fa fa-flag-o', 'fa fa-flash', 'fa fa-flask', 'fa fa-flickr', 'fa fa-floppy-o', 'fa fa-folder', 'fa fa-folder-o', 'fa fa-folder-open', 'fa fa-folder-open-o', 'fa fa-font', 'fa fa-font-awesome', 'fa fa-fonticons', 'fa fa-fort-awesome', 'fa fa-forumbee', 'fa fa-forward', 'fa fa-foursquare', 'fa fa-free-code-camp', 'fa fa-frown-o', 'fa fa-futbol-o', 'fa fa-gamepad', 'fa fa-gavel', 'fa fa-gbp', 'fa fa-ge', 'fa fa-gear', 'fa fa-gears', 'fa fa-genderless', 'fa fa-get-pocket', 'fa fa-gg', 'fa fa-gg-circle', 'fa fa-gift', 'fa fa-git', 'fa fa-git-square', 'fa fa-github', 'fa fa-github-alt', 'fa fa-github-square', 'fa fa-gitlab', 'fa fa-gittip', 'fa fa-glass', 'fa fa-glide', 'fa fa-glide-g', 'fa fa-globe', 'fa fa-google', 'fa fa-google-plus', 'fa fa-google-plus-circle', 'fa fa-google-plus-official', 'fa fa-google-plus-square', 'fa fa-google-wallet', 'fa fa-graduation-cap', 'fa fa-gratipay', 'fa fa-grav', 'fa fa-group', 'fa fa-h-square', 'fa fa-hacker-news', 'fa fa-hand-grab-o', 'fa fa-hand-lizard-o', 'fa fa-hand-o-down', 'fa fa-hand-o-left', 'fa fa-hand-o-right', 'fa fa-hand-o-up', 'fa fa-hand-paper-o', 'fa fa-hand-peace-o', 'fa fa-hand-pointer-o', 'fa fa-hand-rock-o', 'fa fa-hand-scissors-o', 'fa fa-hand-spock-o', 'fa fa-hand-stop-o', 'fa fa-handshake-o', 'fa fa-hard-of-hearing', 'fa fa-hashtag', 'fa fa-hdd-o', 'fa fa-header', 'fa fa-headphones', 'fa fa-heart', 'fa fa-heart-o', 'fa fa-heartbeat', 'fa fa-history', 'fa fa-home', 'fa fa-hospital-o', 'fa fa-hotel', 'fa fa-hourglass', 'fa fa-hourglass-1', 'fa fa-hourglass-2', 'fa fa-hourglass-3', 'fa fa-hourglass-end', 'fa fa-hourglass-half', 'fa fa-hourglass-o', 'fa fa-hourglass-start', 'fa fa-houzz', 'fa fa-html5', 'fa fa-i-cursor', 'fa fa-id-badge', 'fa fa-id-card', 'fa fa-id-card-o', 'fa fa-ils', 'fa fa-image', 'fa fa-imdb', 'fa fa-inbox', 'fa fa-indent', 'fa fa-industry', 'fa fa-info', 'fa fa-info-circle', 'fa fa-inr', 'fa fa-instagram', 'fa fa-institution', 'fa fa-internet-explorer', 'fa fa-intersex', 'fa fa-ioxhost', 'fa fa-italic', 'fa fa-joomla', 'fa fa-jpy', 'fa fa-jsfiddle', 'fa fa-key', 'fa fa-keyboard-o', 'fa fa-krw', 'fa fa-language', 'fa fa-laptop', 'fa fa-lastfm', 'fa fa-lastfm-square', 'fa fa-leaf', 'fa fa-leanpub', 'fa fa-legal', 'fa fa-lemon-o', 'fa fa-level-down', 'fa fa-level-up', 'fa fa-life-bouy', 'fa fa-life-buoy', 'fa fa-life-ring', 'fa fa-life-saver', 'fa fa-lightbulb-o', 'fa fa-line-chart', 'fa fa-link', 'fa fa-linkedin', 'fa fa-linkedin-square', 'fa fa-linode', 'fa fa-linux', 'fa fa-list', 'fa fa-list-alt', 'fa fa-list-ol', 'fa fa-list-ul', 'fa fa-location-arrow', 'fa fa-lock', 'fa fa-long-arrow-down', 'fa fa-long-arrow-left', 'fa fa-long-arrow-right', 'fa fa-long-arrow-up', 'fa fa-low-vision', 'fa fa-magic', 'fa fa-magnet', 'fa fa-mail-forward', 'fa fa-mail-reply', 'fa fa-mail-reply-all', 'fa fa-male', 'fa fa-map', 'fa fa-map-marker', 'fa fa-map-o', 'fa fa-map-pin', 'fa fa-map-signs', 'fa fa-mars', 'fa fa-mars-double', 'fa fa-mars-stroke', 'fa fa-mars-stroke-h', 'fa fa-mars-stroke-v', 'fa fa-maxcdn', 'fa fa-meanpath', 'fa fa-medium', 'fa fa-medkit', 'fa fa-meetup', 'fa fa-meh-o', 'fa fa-mercury', 'fa fa-microchip', 'fa fa-microphone', 'fa fa-microphone-slash', 'fa fa-minus', 'fa fa-minus-circle', 'fa fa-minus-square', 'fa fa-minus-square-o', 'fa fa-mixcloud', 'fa fa-mobile', 'fa fa-mobile-phone', 'fa fa-modx', 'fa fa-money', 'fa fa-moon-o', 'fa fa-mortar-board', 'fa fa-motorcycle', 'fa fa-mouse-pointer', 'fa fa-music', 'fa fa-navicon', 'fa fa-neuter', 'fa fa-newspaper-o', 'fa fa-object-group', 'fa fa-object-ungroup', 'fa fa-odnoklassniki', 'fa fa-odnoklassniki-square', 'fa fa-opencart', 'fa fa-openid', 'fa fa-opera', 'fa fa-optin-monster', 'fa fa-outdent', 'fa fa-pagelines', 'fa fa-paint-brush', 'fa fa-paper-plane', 'fa fa-paper-plane-o', 'fa fa-paperclip', 'fa fa-paragraph', 'fa fa-paste', 'fa fa-pause', 'fa fa-pause-circle', 'fa fa-pause-circle-o', 'fa fa-paw', 'fa fa-paypal', 'fa fa-pencil', 'fa fa-pencil-square', 'fa fa-pencil-square-o', 'fa fa-percent', 'fa fa-phone', 'fa fa-phone-square', 'fa fa-photo', 'fa fa-picture-o', 'fa fa-pie-chart', 'fa fa-pied-piper', 'fa fa-pied-piper-alt', 'fa fa-pied-piper-pp', 'fa fa-pinterest', 'fa fa-pinterest-p', 'fa fa-pinterest-square', 'fa fa-plane', 'fa fa-play', 'fa fa-play-circle', 'fa fa-play-circle-o', 'fa fa-plug', 'fa fa-plus', 'fa fa-plus-circle', 'fa fa-plus-square', 'fa fa-plus-square-o', 'fa fa-podcast', 'fa fa-power-off', 'fa fa-print', 'fa fa-product-hunt', 'fa fa-puzzle-piece', 'fa fa-qq', 'fa fa-qrcode', 'fa fa-question', 'fa fa-question-circle', 'fa fa-question-circle-o', 'fa fa-quora', 'fa fa-quote-left', 'fa fa-quote-right', 'fa fa-ra', 'fa fa-random', 'fa fa-ravelry', 'fa fa-rebel', 'fa fa-recycle', 'fa fa-reddit', 'fa fa-reddit-alien', 'fa fa-reddit-square', 'fa fa-refresh', 'fa fa-registered', 'fa fa-remove', 'fa fa-renren', 'fa fa-reorder', 'fa fa-repeat', 'fa fa-reply', 'fa fa-reply-all', 'fa fa-resistance', 'fa fa-retweet', 'fa fa-rmb', 'fa fa-road', 'fa fa-rocket', 'fa fa-rotate-left', 'fa fa-rotate-right', 'fa fa-rouble', 'fa fa-rss', 'fa fa-rss-square', 'fa fa-rub', 'fa fa-ruble', 'fa fa-rupee', 'fa fa-s15', 'fa fa-safari', 'fa fa-save', 'fa fa-scissors', 'fa fa-scribd', 'fa fa-search', 'fa fa-search-minus', 'fa fa-search-plus', 'fa fa-sellsy', 'fa fa-send', 'fa fa-send-o', 'fa fa-server', 'fa fa-share', 'fa fa-share-alt', 'fa fa-share-alt-square', 'fa fa-share-square', 'fa fa-share-square-o', 'fa fa-shekel', 'fa fa-sheqel', 'fa fa-shield', 'fa fa-ship', 'fa fa-shirtsinbulk', 'fa fa-shopping-bag', 'fa fa-shopping-basket', 'fa fa-shopping-cart', 'fa fa-shower', 'fa fa-sign-in', 'fa fa-sign-language', 'fa fa-sign-out', 'fa fa-signal', 'fa fa-signing', 'fa fa-simplybuilt', 'fa fa-sitemap', 'fa fa-skyatlas', 'fa fa-skype', 'fa fa-slack', 'fa fa-sliders', 'fa fa-slideshare', 'fa fa-smile-o', 'fa fa-snapchat', 'fa fa-snapchat-ghost', 'fa fa-snapchat-square', 'fa fa-snowflake-o', 'fa fa-soccer-ball-o', 'fa fa-sort', 'fa fa-sort-alpha-asc', 'fa fa-sort-alpha-desc', 'fa fa-sort-amount-asc', 'fa fa-sort-amount-desc', 'fa fa-sort-asc', 'fa fa-sort-desc', 'fa fa-sort-down', 'fa fa-sort-numeric-asc', 'fa fa-sort-numeric-desc', 'fa fa-sort-up', 'fa fa-soundcloud', 'fa fa-space-shuttle', 'fa fa-spinner', 'fa fa-spoon', 'fa fa-spotify', 'fa fa-square', 'fa fa-square-o', 'fa fa-stack-exchange', 'fa fa-stack-overflow', 'fa fa-star', 'fa fa-star-half', 'fa fa-star-half-empty', 'fa fa-star-half-full', 'fa fa-star-half-o', 'fa fa-star-o', 'fa fa-steam', 'fa fa-steam-square', 'fa fa-step-backward', 'fa fa-step-forward', 'fa fa-stethoscope', 'fa fa-sticky-note', 'fa fa-sticky-note-o', 'fa fa-stop', 'fa fa-stop-circle', 'fa fa-stop-circle-o', 'fa fa-street-view', 'fa fa-strikethrough', 'fa fa-stumbleupon', 'fa fa-stumbleupon-circle', 'fa fa-subscript', 'fa fa-subway', 'fa fa-suitcase', 'fa fa-sun-o', 'fa fa-superpowers', 'fa fa-superscript', 'fa fa-support', 'fa fa-table', 'fa fa-tablet', 'fa fa-tachometer', 'fa fa-tag', 'fa fa-tags', 'fa fa-tasks', 'fa fa-taxi', 'fa fa-telegram', 'fa fa-television', 'fa fa-tencent-weibo', 'fa fa-terminal', 'fa fa-text-height', 'fa fa-text-width', 'fa fa-th', 'fa fa-th-large', 'fa fa-th-list', 'fa fa-themeisle', 'fa fa-thermometer', 'fa fa-thermometer-0', 'fa fa-thermometer-1', 'fa fa-thermometer-2', 'fa fa-thermometer-3', 'fa fa-thermometer-4', 'fa fa-thermometer-empty', 'fa fa-thermometer-full', 'fa fa-thermometer-half', 'fa fa-thermometer-quarter', 'fa fa-thermometer-three-quarters', 'fa fa-thumb-tack', 'fa fa-thumbs-down', 'fa fa-thumbs-o-down', 'fa fa-thumbs-o-up', 'fa fa-thumbs-up', 'fa fa-ticket', 'fa fa-times', 'fa fa-times-circle', 'fa fa-times-circle-o', 'fa fa-times-rectangle', 'fa fa-times-rectangle-o', 'fa fa-tint', 'fa fa-toggle-down', 'fa fa-toggle-left', 'fa fa-toggle-off', 'fa fa-toggle-on', 'fa fa-toggle-right', 'fa fa-toggle-up', 'fa fa-trademark', 'fa fa-train', 'fa fa-transgender', 'fa fa-transgender-alt', 'fa fa-trash', 'fa fa-trash-o', 'fa fa-tree', 'fa fa-trello', 'fa fa-tripadvisor', 'fa fa-trophy', 'fa fa-truck', 'fa fa-try', 'fa fa-tty', 'fa fa-tumblr', 'fa fa-tumblr-square', 'fa fa-turkish-lira', 'fa fa-tv', 'fa fa-twitch', 'fa fa-twitter', 'fa fa-twitter-square', 'fa fa-umbrella', 'fa fa-underline', 'fa fa-undo', 'fa fa-universal-access', 'fa fa-university', 'fa fa-unlink', 'fa fa-unlock', 'fa fa-unlock-alt', 'fa fa-unsorted', 'fa fa-upload', 'fa fa-usb', 'fa fa-usd', 'fa fa-user', 'fa fa-user-circle', 'fa fa-user-circle-o', 'fa fa-user-md', 'fa fa-user-o', 'fa fa-user-plus', 'fa fa-user-secret', 'fa fa-user-times', 'fa fa-users', 'fa fa-vcard', 'fa fa-vcard-o', 'fa fa-venus', 'fa fa-venus-double', 'fa fa-venus-mars', 'fa fa-viacoin', 'fa fa-viadeo', 'fa fa-viadeo-square', 'fa fa-video-camera', 'fa fa-vimeo', 'fa fa-vimeo-square', 'fa fa-vine', 'fa fa-vk', 'fa fa-volume-control-phone', 'fa fa-volume-down', 'fa fa-volume-off', 'fa fa-volume-up', 'fa fa-warning', 'fa fa-wechat', 'fa fa-weibo', 'fa fa-weixin', 'fa fa-whatsapp', 'fa fa-wheelchair', 'fa fa-wheelchair-alt', 'fa fa-wifi', 'fa fa-wikipedia-w', 'fa fa-window-close', 'fa fa-window-close-o', 'fa fa-window-maximize', 'fa fa-window-minimize', 'fa fa-window-restore', 'fa fa-windows', 'fa fa-won', 'fa fa-wordpress', 'fa fa-wpbeginner', 'fa fa-wpexplorer', 'fa fa-wpforms', 'fa fa-wrench', 'fa fa-xing', 'fa fa-xing-square', 'fa fa-y-combinator', 'fa fa-y-combinator-square', 'fa fa-yahoo', 'fa fa-yc', 'fa fa-yc-square', 'fa fa-yelp', 'fa fa-yen', 'fa fa-yoast', 'fa fa-youtube', 'fa fa-youtube-play', 'fa fa-youtube-square'];

        var iconsWindow = '<div tabindex="0" role="dialog" id="icons-modal" style="position: relative; display: none;">' + 
                                '<div class="media-modal wp-core-ui">' + 
                                    '<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close</span></span></button>' + 
                                    '<div class="media-modal-content">' + 
                                        '<div class="media-frame mode-select wp-core-ui">' + 
                                            '<div class="media-frame-title">' + 
                                                '<h1>Icons</h1>' + 
                                                '<div class="search-icons-wrapper">' + 
                                                    '<div class="row">' + 
                                                        '<div class="col-xs-12 col-sm-12 col-md-4 search-icons rtl-pull-right">' +
                                                            '<span class="fa fa-search"></span>' + 
                                                            '<input type="text" id="search-icons-input" class="form-control" placeholder="Search icons">' + 
                                                        '</div>' +
                                                    '</div>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="media-frame-content">' + 
                                                '<div class="icons-frame-content">' + 
                                                    '<div class="icons-modal-subtitle">Font Awesome</div>' + 
                                                    '<div class="icons-list icons-list-fa"></div>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="media-modal-backdrop"></div>' + 
                            '</div>';

        $('body').append(iconsWindow);

        $('#icons-modal .media-modal-close').on('click', function(e) {
            $('#icons-modal').hide().remove();
            e.preventDefault();
        });
        $('#icons-modal').on('keyup',function(e) {
            if (e.keyCode == 27) {
               $(this).hide().remove();
               e.preventDefault();
            }
        });

        var faList = '<div class="row">';
        for (var i = 0; i < faIcons.length; i++) {
            faList += '<div class="col-xs-6 col-sm-4 col-md-3 is-icon">' + 
                            '<div class="icons-item">' + 
                                '<span class="' + faIcons[i] + '"></span> ' + faIcons[i] + 
                            '</div>' + 
                        '</div>';
        }
        faList += '</div>';
        $('#icons-modal .icons-list-fa').append(faList);

        $('#search-icons-input').on('keyup', function() {
            var value = this.value;

            $('.is-icon').each(function(index) {
                var id = $(this).text();
                $(this).toggle(id.indexOf(value) !== -1);
            });
        });

        var selected = $(btn).find('span').attr('class');
        $('.icons-item span').each(function() {
            if ($(this).hasClass(selected)) {
                $(this).parent().addClass('is-selected');
            }
        });

        $('.icons-item').on('click', function(e) {
            var cName = $(this).find('span').attr('class');

            $(btn).html('<span class="' + cName + '"></span> ' + cName);
            $(btn).prev('input').val(cName);

            $('#icons-modal').hide().remove();
            e.preventDefault();
        });

        $('#icons-modal').show().focus();
    }

    $('.choose-icon').click(function(event) {
        openIconsWindow(this);
    });

    $('.icons-field').each(function(index, el) {
        var fieldValue = $(this).val();
        if (fieldValue != '') {
            $(this).next('.choose-icon').html('<span class="' + fieldValue + '"></span> ' + fieldValue);
        }
    });

    // user profile picture / avatar
    $('#user-avatar-image-placeholder').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: settings_vars.avatar_title,
            button: {
                text: settings_vars.avatar_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#avatar').val(value.id);
                $('#user-avatar-image-placeholder').css('background-image', 'url(' + value.url + ')');
                $('.user-avatar-placeholder-container').addClass('has-image');
            });
        });

        frame.open();
    });

    $('#delete-user-avatar-image').click(function() {
        $('#avatar').val('');
        $('#user-avatar-image-placeholder').css('background-image', 'url(' + settings_vars.plugin_url + 'post-types/images/avatar-placeholder.png)');
        $('.user-avatar-placeholder-container').removeClass('has-image');
    });

    // Manage dismiss pages missing warning notice
    $(document).on('click', '.notice-pages .notice-dismiss', function() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_dismiss_pages_warning',
                'security' : $('#security-dismiss-pages-warning').val()
            },
            success: function(data) {},
            error: function(errorThrown) {}
        });
    });

    // Manage theme setup options
    $('input[name=demo_version]').change(function() {
        $('.theme-setup-btn').hide();

        if ($(this).is(':checked')) {
            $(this).parent().next('.theme-setup-btn').show();
        } else {
            $(this).parent().next('.theme-setup-btn').hide();
        }
    });

    var setup_msgs;
    var setup_success;
    var demo_version;

    $('.theme-setup-btn > input').click(function() {
        var _this  = $(this);
        var btn    = $(this).parent();

        demo_version = _this.attr('data-demo');
        setup_msgs   = btn.next('ul');
        setup_success = btn.next('ul').next('.theme-setup-done');

        btn.hide();
        setup_msgs.find('.theme-setup-permalinks-msg').show();

        $('input[name=demo_version]').attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_setup_permalinks',
                'security' : $('#securityThemeSetup').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.theme-setup-permalinks-msg .fa-spinner').hide();
                    setup_msgs.find('.theme-setup-permalinks-msg .fa-check').show();
                    setup_msgs.find('.theme-setup-permalinks-msg .msg-done').show();

                    setupReadingPages();
                }
            }
        });
    });

    function setupReadingPages() {
        setup_msgs.find('.theme-setup-homepage-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_setup_reading_pages',
                'security' : $('#securityThemeSetup').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.theme-setup-homepage-msg .fa-spinner').hide();
                    setup_msgs.find('.theme-setup-homepage-msg .fa-check').show();
                    setup_msgs.find('.theme-setup-homepage-msg .msg-done').show();

                    setupMenu();
                }
            }
        });
    }

    function setupMenu() {
        setup_msgs.find('.theme-setup-menu-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_setup_menu',
                'security' : $('#securityThemeSetup').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.theme-setup-menu-msg .fa-spinner').hide();
                    setup_msgs.find('.theme-setup-menu-msg .fa-check').show();
                    setup_msgs.find('.theme-setup-menu-msg .msg-done').show();

                    setupWidgets();
                }
            }
        });
    }

    function setupWidgets() {
        setup_msgs.find('.theme-setup-widgets-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_setup_widgets',
                'demo'     : demo_version,
                'security' : $('#securityThemeSetup').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.theme-setup-widgets-msg .fa-spinner').hide();
                    setup_msgs.find('.theme-setup-widgets-msg .fa-check').show();
                    setup_msgs.find('.theme-setup-widgets-msg .msg-done').show();

                    setupOptions();
                }
            }
        });
    }

    function setupOptions() {
        setup_msgs.find('.theme-setup-options-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'resideo_setup_options',
                'demo'     : demo_version,
                'security' : $('#securityThemeSetup').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.theme-setup-options-msg .fa-spinner').hide();
                    setup_msgs.find('.theme-setup-options-msg .fa-check').show();
                    setup_msgs.find('.theme-setup-options-msg .msg-done').show();
                    setup_success.show();
                }
            }
        });
    }

    var propLayoutDesign = $('.pxp-property-page-layout select').val();
    if (propLayoutDesign == 'd1' || propLayoutDesign == 'd3' || propLayoutDesign == 'd6' || propLayoutDesign == 'd7') {
        $('.pxp-top-element-settings').show();
    } else {
        $('.pxp-top-element-settings').hide();
    }
    if (propLayoutDesign == 'd4') {
        $('.pxp-map-position-settings').show();
    } else {
        $('.pxp-map-position-settings').hide();
    }
    $('.pxp-property-page-layout select').on('change', function() {
        var propLayoutOption = $('.pxp-top-element-settings select').val();
        var mapPositionSettings = $('.pxp-map-position-settings select').val();

        $('.pxp-property-page-layout-preview').hide();

        if ($(this).val() == 'd1') {
            $('.pxp-top-element-settings').show();
            $('.pxp-map-position-settings').hide();
            if (propLayoutOption == 'title') {
                $('.pxp-is-d1-title').show();
            } else {
                $('.pxp-is-d1-gallery').show();
            }
        }
        if ($(this).val() == 'd2') {
            $('.pxp-top-element-settings').hide();
            $('.pxp-map-position-settings').hide();
            $('.pxp-is-d2').show();
        }
        if ($(this).val() == 'd3') {
            $('.pxp-top-element-settings').show();
            $('.pxp-map-position-settings').hide();
            if (propLayoutOption == 'title') {
                $('.pxp-is-d3-title').show();
            } else {
                $('.pxp-is-d3-gallery').show();
            }
        }
        if ($(this).val() == 'd4') {
            $('.pxp-top-element-settings').hide();
            $('.pxp-map-position-settings').show();
            if (mapPositionSettings == 'left') {
                $('.pxp-is-d4-left').show();
            } else {
                $('.pxp-is-d4-right').show();
            }
        }
        if ($(this).val() == 'd5') {
            $('.pxp-top-element-settings').hide();
            $('.pxp-map-position-settings').hide();
            $('.pxp-is-d5').show();
        }
        if ($(this).val() == 'd6') {
            $('.pxp-top-element-settings').show();
            $('.pxp-map-position-settings').hide();
            if (propLayoutOption == 'title') {
                $('.pxp-is-d6-title').show();
            } else {
                $('.pxp-is-d6-gallery').show();
            }
        }
        if ($(this).val() == 'd7') {
            $('.pxp-top-element-settings').show();
            $('.pxp-map-position-settings').hide();
            if (propLayoutOption == 'title') {
                $('.pxp-is-d7-title').show();
            } else {
                $('.pxp-is-d7-gallery').show();
            }
        }
    });
    $('.pxp-top-element-settings select').on('change', function() {
        var propLayoutDesignChange = $('.pxp-property-page-layout select').val();

        $('.pxp-property-page-layout-preview').hide();

        if ($(this).val() == 'title') {
            if (propLayoutDesignChange == 'd1') {
                $('.pxp-is-d1-title').show();
            }
            if (propLayoutDesignChange == 'd3') {
                $('.pxp-is-d3-title').show();
            }
            if (propLayoutDesignChange == 'd6') {
                $('.pxp-is-d6-title').show();
            }
            if (propLayoutDesignChange == 'd7') {
                $('.pxp-is-d7-title').show();
            }
        }
        if ($(this).val() == 'gallery') {
            if (propLayoutDesignChange == 'd1') {
                $('.pxp-is-d1-gallery').show();
            }
            if (propLayoutDesignChange == 'd3') {
                $('.pxp-is-d3-gallery').show();
            }
            if (propLayoutDesignChange == 'd6') {
                $('.pxp-is-d6-gallery').show();
            }
            if (propLayoutDesignChange == 'd7') {
                $('.pxp-is-d7-gallery').show();
            }
        }
    });
    $('.pxp-map-position-settings select').on('change', function() {
        $('.pxp-property-page-layout-preview').hide();

        if ($(this).val() == 'left') {
            $('.pxp-is-d4-left').show();
        } else {
            $('.pxp-is-d4-right').show();
        }
    });
})(jQuery);