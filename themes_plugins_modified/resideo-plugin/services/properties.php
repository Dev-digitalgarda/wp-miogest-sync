<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Get all properties for admin modal
 */
if (!function_exists('resideo_get_modal_properties')): 
    function resideo_get_modal_properties() {
        check_ajax_referer('modal_props_ajax_nonce', 'security');

        $fields_settings   = get_option('resideo_prop_fields_settings');
        $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
        $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
        $neighborhoods     = get_option('resideo_neighborhoods_settings');
        $cities            = get_option('resideo_cities_settings');

        $page    = isset($_POST['page_no']) ? $_POST['page_no'] : 0;
        $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';

        $args = array(
            'posts_per_page' => 20,
            'post_type'      => 'property',
            'orderby'        => 'post_date',
            'order'          => 'DESC',
            's'              => $keyword,
            'post_status'    => 'publish',
            'paged'          => $page,
        );
        $props = array();

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $total_p = $query->found_posts;
        } else {
            $total_p = false;
        }

        while ($query->have_posts()) {
            $query->the_post();

            $prop = new stdClass();

            $prop_id = get_the_ID();
            $prop->id = $prop_id;
            $prop->title = get_the_title();
            $gallery = get_post_meta($prop_id, 'property_gallery', true);
            $photos = explode(',', $gallery);
            $prop->photo = wp_get_attachment_image_src($photos[0], 'pxp-agent');

            $address_arr  = array();
            $address      = '';
            $street_no    = get_post_meta($prop_id, 'street_number', true);
            $street       = get_post_meta($prop_id, 'route', true);
            $neighborhood = get_post_meta($prop_id, 'neighborhood', true);
            $city         = get_post_meta($prop_id, 'locality', true);
            $state        = get_post_meta($prop_id, 'administrative_area_level_1', true);
            $zip          = get_post_meta($prop_id, 'postal_code', true);

            $neighborhood_value = resideo_get_field_value($neighborhood_type, $neighborhood, $neighborhoods);
            $city_value         = resideo_get_field_value($city_type, $city, $cities);

            $address_settings = get_option('resideo_address_settings');

            if (is_array($address_settings)) {
                uasort($address_settings, "resideo_compare_position");

                $address_default = array(
                    'street_number' => $street_no,
                    'street'        => $street,
                    'neighborhood'  => $neighborhood_value,
                    'city'          => $city_value,
                    'state'         => $state,
                    'zip'           => $zip
                );

                foreach ($address_settings as $key => $value) {
                    if ($address_default[$key] != '') {
                        array_push($address_arr, $address_default[$key]);
                    }
                }
            } else {
                if ($street_no != '') array_push($address_arr, $street_no);
                if ($street != '') array_push($address_arr, $street);
                if ($neighborhood_value != '') array_push($address_arr, $neighborhood_value);
                if ($city_value != '') array_push($address_arr, $city_value);
                if ($state != '') array_push($address_arr, $state);
                if ($zip != '') array_push($address_arr, $zip);
            }

            if (count($address_arr) > 0) $address = implode(', ', $address_arr);

            $prop->address = $address;

            array_push($props, $prop);
        }

        wp_reset_postdata();
        wp_reset_query();

        if (count($props) > 0) {
            echo json_encode(array('getprops'=>true, 'props'=>$props, 'total'=>$total_p));
            exit();
        } else {
            echo json_encode(array('getprops'=>false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_modal_properties', 'resideo_get_modal_properties');
add_action('wp_ajax_resideo_get_modal_properties', 'resideo_get_modal_properties');

/**
 * Get properties for autocomplete list
 */
if (!function_exists('resideo_get_autocomplete_properties')): 
    function resideo_get_autocomplete_properties() {
        $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';

        $args = array(
            'posts_per_page' => 20,
            'post_type'      => 'property',
            'orderby'        => 'post_date',
            'order'          => 'DESC',
            's'              => $keyword,
            'post_status'    => 'publish',
        );
        $props = array();

        $query = new WP_Query($args);

        while($query->have_posts()) {
            $query->the_post();

            $prop = new stdClass();

            $prop->id = get_the_ID();
            $prop->title = get_the_title();

            array_push($props, $prop);
        }

        wp_reset_postdata();
        wp_reset_query();

        if (count($props) > 0) {
            echo json_encode(array('getprops' => true, 'props' => $props));
            exit();
        } else {
            echo json_encode(array('getprops' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_autocomplete_properties', 'resideo_get_autocomplete_properties');
add_action('wp_ajax_resideo_get_autocomplete_properties', 'resideo_get_autocomplete_properties');

/**
 * Get properties for page header slider settings in back-end
 */
if (!function_exists('resideo_get_page_header_settings_slider_properties')): 
    function resideo_get_page_header_settings_slider_properties($ids) {
        $fields_settings   = get_option('resideo_prop_fields_settings');
        $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
        $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
        $neighborhoods     = get_option('resideo_neighborhoods_settings');
        $cities            = get_option('resideo_cities_settings');

        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'property',
            'orderby'        => 'post__in',
            'post_status'    => 'publish',
            'post__in'       => $ids,
        );
        $props = array();

        $query = new WP_Query($args);

        while ($query->have_posts()) {
            $query->the_post();

            $prop = new stdClass();
            
            $prop_id = get_the_ID();
            $prop->id = $prop_id;
            $prop->title = get_the_title();
            $gallery = get_post_meta($prop_id, 'property_gallery', true);
            $photos = explode(',', $gallery);
            $prop->photo = wp_get_attachment_image_src($photos[0], 'pxp-agent');

            $address_arr  = array();
            $address      = '';
            $street_no    = get_post_meta($prop_id, 'street_number', true);
            $street       = get_post_meta($prop_id, 'route', true);
            $neighborhood = get_post_meta($prop_id, 'neighborhood', true);
            $city         = get_post_meta($prop_id, 'locality', true);
            $state        = get_post_meta($prop_id, 'administrative_area_level_1', true);
            $zip          = get_post_meta($prop_id, 'postal_code', true);

            $neighborhood_value = resideo_get_field_value($neighborhood_type, $neighborhood, $neighborhoods);
            $city_value         = resideo_get_field_value($city_type, $city, $cities);

            $address_settings = get_option('resideo_address_settings');

            if (is_array($address_settings)) {
                uasort($address_settings, "resideo_compare_position");

                $address_default = array(
                    'street_number' => $street_no,
                    'street'        => $street,
                    'neighborhood'  => $neighborhood_value,
                    'city'          => $city_value,
                    'state'         => $state,
                    'zip'           => $zip
                );

                foreach ($address_settings as $key => $value) {
                    if ($address_default[$key] != '') {
                        array_push($address_arr, $address_default[$key]);
                    }
                }
            } else {
                if ($street_no != '') array_push($address_arr, $street_no);
                if ($street != '') array_push($address_arr, $street);
                if ($neighborhood_value != '') array_push($address_arr, $neighborhood_value);
                if ($city_value != '') array_push($address_arr, $city_value);
                if ($state != '') array_push($address_arr, $state);
                if ($zip != '') array_push($address_arr, $zip);
            }

            if (count($address_arr) > 0) $address = implode(', ', $address_arr);

            $prop->address = $address;

            array_push($props, $prop);
        }

        wp_reset_postdata();
        wp_reset_query();

        return $props;
    }
endif;

/**
 * Get properties for page header slider in front-end (this function is also used for properties slider shortcode)
 */
if (!function_exists('resideo_get_page_header_slider_properties')): 
    function resideo_get_page_header_slider_properties($ids) {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'property',
            'orderby'        => 'post__in',
            'post_status'    => 'publish',
            'post__in'       => $ids,
        );
        $props = array();

        $query = new WP_Query($args);

        while ($query->have_posts()) {
            $query->the_post();

            $prop = new stdClass();
            
            $prop_id = get_the_ID();
            $prop->id = $prop_id;
            $prop->title = get_the_title();

            $gallery = get_post_meta($prop_id, 'property_gallery', true);
            $photos = explode(',', $gallery);
            $prop->photo = wp_get_attachment_image_src($photos[0], 'pxp-full');
            $prop->thmb = wp_get_attachment_image_src($photos[0], 'pxp-thmb');

            $prop->beds = get_post_meta($prop_id, 'property_beds', true);
            $prop->baths = get_post_meta($prop_id, 'property_baths', true);
            $prop->size = get_post_meta($prop_id, 'property_size', true);

            $prop->price = get_post_meta($prop_id, 'property_price', true);
            $prop->price_label = get_post_meta($prop_id, 'property_price_label', true);

            $prop->link = get_permalink($prop_id);

            array_push($props, $prop);
        }

        wp_reset_postdata();
        wp_reset_query();

        return $props;
    }
endif;

/**
 * Get search properties page link
 */
if (!function_exists('resideo_get_search_properties_link')): 
    function resideo_get_search_properties_link() {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'property-search.php'
        ));

        if ($pages) {
            $search_submit = get_permalink($pages[0]->ID);
        } else {
            $search_submit = '';
        }

        return $search_submit;
    }
endif;
?>