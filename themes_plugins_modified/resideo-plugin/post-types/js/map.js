(function($) {
    "use strict";

    if ($('#admin-property-map').length > 0) {
        var geocoder;
        var options = {
            zoom : 14,
            panControl: false,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: true,
            overviewMapControl: false,
            scrollwheel: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL,
                position: google.maps.ControlPosition.RIGHT_TOP
            },
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            }
        };
        var newMarker = null;
        var map = new google.maps.Map(document.getElementById('admin-property-map'), options);

        var mapLat = 0, mapLng = 0;
        var address = document.getElementById('property_address');

        if ($('#property_lat').val() != '' && $('#property_lng').val() != '') {
            mapLat = $('#property_lat').val();
            mapLng = $('#property_lng').val();
        } else if(ptm_vars.default_lat != '' && ptm_vars.default_lng != '') {
            mapLat = ptm_vars.default_lat;
            mapLng = ptm_vars.default_lng;
        }

        var mapCenter = new google.maps.LatLng(mapLat, mapLng);
        map.setCenter(mapCenter);
        map.setZoom(14);

        newMarker = new google.maps.Marker({
            position: mapCenter,
            map: map,
            icon: new google.maps.MarkerImage( 
                ptm_vars.plugin_url + 'images/marker-new.png',
                null,
                null,
                null,
                new google.maps.Size(38, 52)
            ),
            draggable: true,
            animation: google.maps.Animation.DROP,
        });

        google.maps.event.addListener(newMarker, 'mouseup', function(event) {
            $('#property_lat').val(this.position.lat());
            $('#property_lng').val(this.position.lng());
        });

        var componentForm = {
            neighborhood                : 'long_name',
            street_number               : 'short_name',
            route                       : 'long_name',
            locality                    : 'long_name',
            administrative_area_level_1 : 'short_name',
            postal_code                 : 'short_name'
        };
        var addressOptions;

        if(ptm_vars.auto_country != '') {
            addressOptions = {
                types: ['geocode'],
                componentRestrictions: { country: ptm_vars.auto_country }
            }
        } else {
            addressOptions = {
                types: ['geocode']
            }
        }

        var addressAuto = new google.maps.places.Autocomplete(address, addressOptions);

        google.maps.event.addListener(addressAuto, 'place_changed', function() {
            for(var component in componentForm) {
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            var place = addressAuto.getPlace();

            if("undefined" != typeof place.address_components) {
                for(var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];

                    if(componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        document.getElementById(addressType).value = val;
                    }
                }
            }

            if ("undefined" != typeof place.geometry) {
                if("undefined" != typeof place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                }

                newMarker.setPosition(place.geometry.location);
            }

            newMarker.setVisible(true);

            $('#property_lat').val(newMarker.getPosition().lat());
            $('#property_lng').val(newMarker.getPosition().lng());

            return false;
        });
        $('#property_address').keydown(function(e) {
            if(e.which == 13 && $('.pac-container:visible').length) return false;
        });

        $('#get_position_btn').click(function() {
            geocoder = new google.maps.Geocoder();
            var address = document.getElementById('property_address').value;
            
            geocoder.geocode({ 'address': address }, function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    newMarker.setPosition(results[0].geometry.location);
                    newMarker.setVisible(true);
                    $('#property_lat').val(newMarker.getPosition().lat());
                    $('#property_lng').val(newMarker.getPosition().lng());
                } else {
                    alert(ptm_vars.geocode_error + ': ' + status);
                }
            });

            return false;
        });
    }

    // Contact page settings
    if ($('#page-contact-settings-section').length > 0) {
        var cp_geocoder;
        var cp_options = {
            zoom : 14,
            panControl: false,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            scrollwheel: false,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
            },
            fullscreenControl: false,
        };
        var cp_newMarker = null;
        var cp_map = new google.maps.Map(document.getElementById('contact_page_office_map'), cp_options);

        var cp_mapLat = 0, cp_mapLng = 0;

        if ($('#contact_page_office_lat').val() != '' && $('#contact_page_office_lng').val() != '') {
            cp_mapLat = $('#contact_page_office_lat').val();
            cp_mapLng = $('#contact_page_office_lng').val();
        } else if (ptm_vars.default_lat != '' && ptm_vars.default_lng != '') {
            cp_mapLat = ptm_vars.default_lat;
            cp_mapLng = ptm_vars.default_lng;
        }

        var cp_mapCenter = new google.maps.LatLng(cp_mapLat, cp_mapLng);
        cp_map.setCenter(cp_mapCenter);
        cp_map.setZoom(14);

        cp_newMarker = new google.maps.Marker({
            position: cp_mapCenter,
            map: cp_map,
            draggable: true
        });

        google.maps.event.addListener(cp_newMarker, 'mouseup', function(event) {
            $('#contact_page_office_lat').val(this.position.lat());
            $('#contact_page_office_lng').val(this.position.lng());
        });

        $('#contact_page_office_position_btn').click(function() {
            cp_geocoder = new google.maps.Geocoder();
            var cp_address_1 = $('#contact_page_office_address_line_1').val();
            var cp_address_2 = $('#contact_page_office_address_line_2').val();
            var cp_address = cp_address_1 + ', ' + cp_address_2;

            cp_geocoder.geocode({ 'address': cp_address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    cp_map.setCenter(results[0].geometry.location);
                    cp_newMarker.setPosition(results[0].geometry.location);
                    cp_newMarker.setVisible(true);

                    $('#contact_page_office_lat').val(cp_newMarker.getPosition().lat());
                    $('#contact_page_office_lng').val(cp_newMarker.getPosition().lng());
                } else {
                    alert(ptm_vars.geocode_error + ': ' + status);
                }
            });

            return false;
        });
    }

    if ($('#page-contact-office-settings-section').length > 0) {
        var cpo_geocoder;
        var cpo_options = {
            zoom : 14,
            panControl: false,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            scrollwheel: false,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
            },
            fullscreenControl: false,
        };
        var cpo_newMarker = null;
        var cpo_map = new google.maps.Map(document.getElementById('contact_page_single_office_map'), cpo_options);

        var cpo_mapLat = 0, cpo_mapLng = 0;

        if ($('#contact_page_single_office_lat').val() != '' && $('#contact_page_single_office_lng').val() != '') {
            cpo_mapLat = $('#contact_page_single_office_lat').val();
            cpo_mapLng = $('#contact_page_single_office_lng').val();
        } else if (ptm_vars.default_lat != '' && ptm_vars.default_lng != '') {
            cpo_mapLat = ptm_vars.default_lat;
            cpo_mapLng = ptm_vars.default_lng;
        }

        var cpo_mapCenter = new google.maps.LatLng(cpo_mapLat, cpo_mapLng);
        cpo_map.setCenter(cpo_mapCenter);
        cpo_map.setZoom(14);

        cpo_newMarker = new google.maps.Marker({
            position: cpo_mapCenter,
            map: cpo_map,
            draggable: true
        });

        google.maps.event.addListener(cpo_newMarker, 'mouseup', function(event) {
            $('#contact_page_single_office_lat').val(this.position.lat());
            $('#contact_page_single_office_lng').val(this.position.lng());
        });

        $('#contact_page_single_office_position_btn').click(function() {
            cpo_geocoder = new google.maps.Geocoder();
            var cpo_address_1 = $('#contact_page_single_office_address_line_1').val();
            var cpo_address_2 = $('#contact_page_single_office_address_line_2').val();
            var cpo_address = cpo_address_1 + ', ' + cpo_address_2;

            cpo_geocoder.geocode({ 'address': cpo_address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    cpo_map.setCenter(results[0].geometry.location);
                    cpo_newMarker.setPosition(results[0].geometry.location);
                    cpo_newMarker.setVisible(true);

                    $('#contact_page_single_office_lat').val(cpo_newMarker.getPosition().lat());
                    $('#contact_page_single_office_lng').val(cpo_newMarker.getPosition().lng());
                } else {
                    alert(ptm_vars.geocode_error + ': ' + status);
                }
            });

            return false;
        });
    }

})(jQuery);