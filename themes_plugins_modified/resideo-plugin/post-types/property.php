<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register property custom post type
 */
if (!function_exists('resideo_register_property_type')): 
    function resideo_register_property_type() {
        register_post_type('property', array(
            'labels' => array(
                'name'               => __('Properties', 'resideo'),
                'singular_name'      => __('Property', 'resideo'),
                'add_new'            => __('Add New Property', 'resideo'),
                'add_new_item'       => __('Add Property', 'resideo'),
                'edit'               => __('Edit', 'resideo'),
                'edit_item'          => __('Edit Property', 'resideo'),
                'new_item'           => __('New Property', 'resideo'),
                'view'               => __('View', 'resideo'),
                'view_item'          => __('View Property', 'resideo'),
                'search_items'       => __('Search Properties', 'resideo'),
                'not_found'          => __('No Properties found', 'resideo'),
                'not_found_in_trash' => __('No Properties found in Trash', 'resideo'),
                'parent'             => __('Parent Property', 'resideo'),
            ),
            'public'                => true,
            'exclude_from_search '  => false,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('properties', 'URL SLUG', 'resideo')),
            'supports'              => array('title', 'editor', 'comments'),
            'show_in_rest'          => false,
            'can_export'            => true,
            'register_meta_box_cb'  => 'resideo_add_property_metaboxes',
            'menu_icon'             => 'dashicons-location',
        ));

        // add property type custom taxonomy (e.g. apartments/houses)
        register_taxonomy('property_type', 'property', array(
            'labels' => array(
                'name'                       => __('Property Types', 'resideo'),
                'singular_name'              => __('Property Type', 'resideo'),
                'search_items'               => __('Search Property Types', 'resideo'),
                'popular_items'              => __('Popular Property Types', 'resideo'),
                'all_items'                  => __('All Property Types', 'resideo'),
                'edit_item'                  => __('Edit Property Type', 'resideo'),
                'update_item'                => __('Update Property Type', 'resideo'),
                'add_new_item'               => __('Add New Property Type', 'resideo'),
                'new_item_name'              => __('New Property Type Name', 'resideo'),
                'separate_items_with_commas' => __('Separate property types with commas', 'resideo'),
                'add_or_remove_items'        => __('Add or remove property types', 'resideo'),
                'choose_from_most_used'      => __('Choose from the most used property types', 'resideo'),
                'not_found'                  => __('No property type found.', 'resideo'),
                'menu_name'                  => __('Property Types', 'resideo'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'type')
        ));

        // add property status custom taxonomy (e.g. for rent/for sale)
        register_taxonomy('property_status', 'property', array(
            'labels' => array(
                'name'                       => __('Property Statuses', 'resideo'),
                'singular_name'              => __('Property Status', 'resideo'),
                'search_items'               => __('Search Property Statuses', 'resideo'),
                'popular_items'              => __('Popular Property Statuses', 'resideo'),
                'all_items'                  => __('All Property Statuses', 'resideo'),
                'edit_item'                  => __('Edit Property Status', 'resideo'),
                'update_item'                => __('Update Property Status', 'resideo'),
                'add_new_item'               => __('Add New Property Status', 'resideo'),
                'new_item_name'              => __('New Property Status Name', 'resideo'),
                'separate_items_with_commas' => __('Separate property statuses with commas', 'resideo'),
                'add_or_remove_items'        => __('Add or remove property statuses', 'resideo'),
                'choose_from_most_used'      => __('Choose from the most used property statuses', 'resideo'),
                'not_found'                  => __('No property status found.', 'resideo'),
                'menu_name'                  => __('Property Statuses', 'resideo'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'status')
        ));
    }
endif;
add_action('init', 'resideo_register_property_type');

/**
 * Sanitize data
 */
if (!function_exists('resideo_sanitize_term_meta')): 
    function resideo_sanitize_term_meta($value) {
        return sanitize_text_field($value);
    }
endif;

/**
 * Insert default property types and statuses
 */
if (!function_exists('resideo_insert_default_terms')): 
    function resideo_insert_default_terms() {
        resideo_register_property_type();

        wp_insert_term('Apartment', 'property_type', $args = array());
        wp_insert_term('House', 'property_type', $args = array());
        wp_insert_term('Land', 'property_type', $args = array());
        wp_insert_term('For Rent', 'property_status', $args = array());
        wp_insert_term('For Sale', 'property_status', $args = array());
    }
endif;
add_action('after_switch_theme', 'resideo_insert_default_terms', 10 , 2);

/**
 * Add property post type metaboxes
 */
if (!function_exists('resideo_add_property_metaboxes')): 
    function resideo_add_property_metaboxes() {
        add_meta_box('property-location-section', __('Location', 'resideo'), 'resideo_property_location_render', 'property', 'normal', 'default');
        add_meta_box('property-details-section', __('Details', 'resideo'), 'resideo_property_details_render', 'property', 'normal', 'default');
        add_meta_box('property-key-details-section', __('Key Details', 'resideo'), 'resideo_property_key_details_render', 'property', 'normal', 'default');
        add_meta_box('property-amenities-section', __('Amenities', 'resideo'), 'resideo_property_amenities_render', 'property', 'normal', 'default');
        add_meta_box('property-video-section', __('Video', 'resideo'), 'resideo_property_video_render', 'property', 'normal', 'default');
        add_meta_box('property-virtual-tour-section', __('Virtual Tour', 'resideo'), 'resideo_property_virtual_tour_render', 'property', 'normal', 'default');
        add_meta_box('property-gallery-section', __('Photo Gallery', 'resideo'), 'resideo_property_gallery_render', 'property', 'normal', 'default');
        add_meta_box('property-floor-plans-section', __('Floor Plans', 'resideo'), 'resideo_property_floor_plans_render', 'property', 'normal', 'default');
        add_meta_box('property-calc-section', __('Mortgage Calculator', 'resideo'), 'resideo_property_calc_render', 'property', 'side', 'default');
        add_meta_box('property-featured-section', __('Featured', 'resideo'), 'resideo_property_featured_render', 'property', 'side', 'default');
        add_meta_box('property-agent-section', __('Agent', 'resideo'), 'resideo_property_agent_render', 'property', 'side', 'default');
    }
endif;

if (!function_exists('resideo_property_location_render')): 
    function resideo_property_location_render($post) {
        wp_nonce_field('resideo_property', 'property_noncename');
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="property_address">' . __('Address', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_address" name="property_address" placeholder="' . __('Enter address', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_address', true)) . '" />
                        </div>
                    </td>
                    <td width="2%" valign="top">
                        <div class="adminField">
                            <label>&nbsp;</label><br />
                            <button id="get_position_btn" title="' . __('Position pin by address', 'resideo') . '" class="button"><span class="icon-target"></span></button>
                        </div>
                    </td>
                    <td width="24%" valign="top">
                        <div class="adminField">
                            <label for="property_lat">' . __('Latitude', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_lat" name="property_lat" value="' . esc_attr(get_post_meta($post->ID, 'property_lat', true)) . '" />
                        </div>
                    </td>
                    <td width="24%" valign="top">
                        <div class="adminField">
                            <label for="property_lng">' . __('Longitude', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_lng" name="property_lng" value="' . esc_attr(get_post_meta($post->ID, 'property_lng', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>';
        if (wp_script_is('gmaps', 'enqueued')) {
            print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div id="admin-property-map"></div>
                    </td>
                </tr>
            </table>';
        }
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="20%" valign="top">
                        <div class="adminField">
                            <label for="street_number">' . __('Street No', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="street_number" name="street_number" placeholder="' . __('Enter street no', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'street_number', true)) . '" />
                        </div>
                    </td>
                    <td width="40%" valign="top">
                        <div class="adminField">
                            <label for="route">' . __('Street Name', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="route" name="route" placeholder="' . __('Enter street name', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'route', true)) . '" />
                        </div>
                    </td>
                    <td width="40%" valign="top">
                        <div class="adminField">
                            <label for="neighborhood">' . __('Neighborhood', 'resideo') . '</label><br />';
                            $fields_settings = get_option('resideo_prop_fields_settings');
                            $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';

                            if ($neighborhood_type == 'list') {
                                $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings');

                                print '
                                    <select id="neighborhood" name="neighborhood" class="formInput">
                                        <option value="">' . __('Select a neighborhood', 'resideo') . '</option>';
                                        if (is_array($resideo_neighborhoods_settings) && count($resideo_neighborhoods_settings) > 0) {
                                            uasort($resideo_neighborhoods_settings, "resideo_compare_position");

                                            foreach ($resideo_neighborhoods_settings as $key => $value) {
                                                print '<option value="' . $key . '"';
                                                if (get_post_meta($post->ID, 'neighborhood', true) == $key) {
                                                    print ' selected ';
                                                }
                                                print '>' . $value['name'] . '</option>';
                                            }
                                        }

                                print '</select>';
                            } else {
                                print '<input type="text" class="formInput" id="neighborhood" name="neighborhood" placeholder="' . __('Enter neighborhood', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'neighborhood', true)) . '" />';
                            }

                    print '
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="40%" valign="top">
                        <div class="adminField">
                            <label for="locality">' . __('City', 'resideo') . '</label><br />';
                            $city_type = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';

                            if ($city_type == 'list') {
                                $resideo_cities_settings = get_option('resideo_cities_settings');

                                print '<select id="locality" name="locality" class="formInput">
                                            <option value="">' . __('Select a city', 'resideo') . '</option>';
                                            if (is_array($resideo_cities_settings) && count($resideo_cities_settings) > 0) {
                                                uasort($resideo_cities_settings, "resideo_compare_position");

                                                foreach ($resideo_cities_settings as $key => $value) {
                                                    print '<option value="' . $key . '"';
                                                    if (get_post_meta($post->ID, 'locality', true) == $key) {
                                                        print ' selected ';
                                                    }
                                                    print '>' . $value['name'] . '</option>';
                                                }
                                            }
                                print '</select>';
                            } else {
                                print '<input type="text" class="formInput" id="locality" name="locality" placeholder="' . __('Enter city', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'locality', true)) . '" />';
                            }

                    print '
                        </div>
                    </td>
                    <td width="40%" valign="top">
                        <div class="adminField">
                            <label for="administrative_area_level_1">' . __('County/State', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="administrative_area_level_1" name="administrative_area_level_1" placeholder="' . __('Enter county/state', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'administrative_area_level_1', true)) . '" />
                        </div>
                    </td>
                    <td width="20%" valign="top">
                        <div class="adminField">
                            <label for="postal_code">' . __('Zip Code', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="postal_code" name="postal_code" placeholder="' . __('Enter zip code', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'postal_code', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>
        ';
    }
endif;

if (!function_exists('resideo_property_details_render')): 
    function resideo_property_details_render($post) {
        $resideo_general_settings = get_option('resideo_general_settings');
        $currency_symbol = isset($resideo_general_settings['resideo_currency_symbol_field']) ? $resideo_general_settings['resideo_currency_symbol_field'] : '';
        $unit_label = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : '';

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_price">' . __('Price', 'resideo') . ' (' . esc_html($currency_symbol) . ')' . '</label><br />
                            <input type="text" class="formInput" id="property_price" name="property_price" placeholder="' . __('Enter price', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_price', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_price_label">' . __('Price Label (e.g. "per month")', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_price_label" name="property_price_label" placeholder="' . __('Enter price label', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_price_label', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_taxes">' . __('Property Taxes', 'resideo') . ' (' . esc_html($currency_symbol) . ')' . '</label><br />
                            <input type="number" class="formInput" id="property_taxes" name="property_taxes" placeholder="' . __('Enter property taxes', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_taxes', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_hoa_dues">' . __('HOA Dues', 'resideo') . ' (' . esc_html($currency_symbol) . ')' . '</label><br />
                            <input type="number" class="formInput" id="property_hoa_dues" name="property_hoa_dues" placeholder="' . __('Enter HOA dues', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_hoa_dues', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_bedrooms">' . __('Beds', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_beds" name="property_beds" placeholder="' . __('Enter number of beds', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_beds', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_bathrooms">' . __('Baths', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_baths" name="property_baths" placeholder="' . __('Enter number of baths', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_baths', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_area">' . __('Size', 'resideo') . ' (' . esc_html($unit_label) . ')' . '</label><br />
                            <input type="text" class="formInput" id="property_size" name="property_size" placeholder="' . __('Enter size', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'property_size', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>
        ';
    }
endif;

if (!function_exists('resideo_property_key_details_render')): 
    function resideo_property_key_details_render($post) {
        $resideo_fields_settings = get_option('resideo_fields_settings');
        $counter = 0;

        if (is_array($resideo_fields_settings)) {
            uasort($resideo_fields_settings, 'resideo_compare_position');

            print '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>';
                    foreach ($resideo_fields_settings as $key => $value) {
                        $counter++;

                        if (($counter - 1) % 3 == 0) {
                            print '<tr>';
                        }

                        print '
                            <td width="33%" valign="top">
                                <div class="adminField">
                                    <label for="' . $key . '">' . $value['label'] . '</label><br />';
                                    if ($value['type'] == 'date_field') {
                                        print '<input type="text" name="' . $key . '" id="' . $key . '" class="formInput datePicker" value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '" />';
                                    } else if ($value['type'] == 'list_field') {
                                        $list = explode(',', $value['list']);

                                        print '<select name="' . $key . '" id="' . $key . '" class="formInput">';
                                        print '<option value="">' . __('Select', 'resideo') . '</option>';

                                        for ($i = 0; $i < count($list); $i++) {
                                            print '
                                                <option value="' . $i . '"';
                                                    $list_value = get_post_meta($post->ID, $key, true);

                                                    if ($list_value != '' && $list_value == $i) {
                                                        print ' selected';
                                                    }
                                                    print '>' . $list[$i] . '
                                                </option>';
                                        }

                                        print '</select>';
                                    } else {
                                        print '<input type="text" name="' . $key . '" id="' . $key . '" class="formInput" value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '" />';
                                    }
                                    print '
                                </div>
                            </td>';

                        if ($counter % 3 == 0) {
                            print '
                        </tr>';
                        }
                    }
            print '
                </table>';
        } else {
            print __('No Key Details fields defined. You can define them here: Resideo > Property Custom Fields', 'resideo');
        }
    }
endif;

if (!function_exists('resideo_property_amenities_render')): 
    function resideo_property_amenities_render($post) {
        $resideo_amenities_settings = get_option('resideo_amenities_settings');
        $counter = 0;

        if (is_array($resideo_amenities_settings) && count($resideo_amenities_settings) > 0) {
            uasort($resideo_amenities_settings, "resideo_compare_position");

            print '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>';
                    foreach ($resideo_amenities_settings as $key => $value) {
                        $counter++;

                        if (($counter - 1) % 3 == 0) {
                            print '<tr>';
                        }
                        print '
                        <td width="33%" valign="top">
                            <p class="meta-options"> 
                                <input type="hidden" name="' . $key . '" value="">
                                <input type="checkbox" name="' . $key . '" value="1" ';
                        if (get_post_meta($post->ID, $key, true) == 1) {
                            print ' checked ';
                        }
                        print ' />
                                <label for="' . $key . '">' . $value['label'] . '</label>
                            </p>
                        </td>';
                        if ($counter % 3 == 0) {
                            print '
                    </tr>';
                        }
                    }
            print '
                </table>';
        } else {
            print __('No amenities defined. You can define them here: Resideo > Amenities', 'resideo');
        }
    }
endif;

if (!function_exists('resideo_property_video_render')): 
    function resideo_property_video_render($post) {
        $video = get_post_meta($post->ID, 'property_video', true);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="property_agent">' . __('YouTube Video ID', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="property_video" name="property_video" placeholder="' . __('Enter the YouTube video ID', 'resideo') . '" value="' . esc_attr($video) . '">
                            <p class="help" style="margin-top: 5px; font-size: 11px !important;">E.g. <span style="color: #999;">https://www.youtube.com/watch?v=</span><strong style="color: green; font-style: normal;">Ur1Nrz23sSI</strong></p>
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_property_virtual_tour_render')): 
    function resideo_property_virtual_tour_render($post) {
        $virtual_tour = get_post_meta($post->ID, 'property_virtual_tour', true);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div class="adminField">
                            <label for="property_agent">' . __('Virtual Tour Embed Code', 'resideo') . '</label><br />
                            <textarea style="width: 100%; height: 140px;" id="property_virtual_tour" name="property_virtual_tour" placeholder="' . __('Paste your virtual tour embed code here...', 'resideo') . '">' . $virtual_tour . '</textarea>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_property_agent_render')): 
    function resideo_property_agent_render($post) {
        $agent_list = '';
        $selected_agent = esc_html(get_post_meta($post->ID, 'property_agent', true));

        $args = array(
            'post_type' => 'agent',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $agent_selection = new WP_Query($args);
        $agent_selection_arr  = get_object_vars($agent_selection);

        if (is_array($agent_selection_arr['posts']) && count($agent_selection_arr['posts']) > 0) {
            foreach ($agent_selection_arr['posts'] as $agent) {
                $agent_list .= '<option value="' . esc_attr($agent->ID) . '"';
                if ($agent->ID == $selected_agent) {
                    $agent_list .= ' selected';
                }
                $agent_list .= '>' . $agent->post_title . '</option>';
            }
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="property_agent">' . __('Assign an Agent', 'resideo') . '</label><br />
                            <select id="property_agent" name="property_agent">
                                <option value="">' . __('None', 'resideo') . '</option>
                                ' . $agent_list . '
                            </select>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_property_gallery_render')): 
    function resideo_property_gallery_render($post) {
        $photos = get_post_meta($post->ID, 'property_gallery', true);
        $ids = explode(',', $photos);

        print '
            <input type="hidden" id="property_gallery" name="property_gallery" value="' . esc_attr($photos) . '" />
            <ul class="list-group" id="prop-gallery-list">';
        foreach ($ids as $id) {
            if ($id != '') {
                $photo_src = wp_get_attachment_image_src($id, 'pxp-agent');
                $photo_info = resideo_get_attachment($id);

                print '
                <li class="list-group-item" data-id="' . esc_attr($id) . '">
                    <img class="pull-left rtl-pull-right" src="' . esc_url($photo_src[0]) . '" />
                    <div class="list-group-item-info">
                        <div class="list-group-item-info-title">' . $photo_info['title'] . '</div>
                        <div class="list-group-item-info-caption">' . $photo_info['caption'] . '</div>
                        <div class="clearfix"></div>
                    </div>
                    <a href="javascript:void(0);" class="pull-right del-btn del-photo"><span class="fa fa-trash-o"></span></a>
                    <a href="javascript:void(0);" class="pull-right edit-btn edit-photo"><span class="fa fa-pencil"></span></a>
                    <div class="clearfix"></div>
                </li>';
            }
        }
        print '
            </ul>
            <input id="add-photo-btn" type="button" class="button" value="' . __('Add Photos', 'resideo') . '" />';
    }
endif;

if (!function_exists('resideo_property_floor_plans_render')): 
    function resideo_property_floor_plans_render($post) {
        $floor_plans = get_post_meta($post->ID, 'property_floor_plans', true);

        $floor_plans_list = array();

        if ($floor_plans != '') {
            $floor_plans_data = json_decode(urldecode($floor_plans));

            if (isset($floor_plans_data)) {
                $floor_plans_list = $floor_plans_data->plans;
            }
        }

        $resideo_general_settings = get_option('resideo_general_settings');
        $beds_label = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
        $baths_label = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
        $unit  = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : '';

        print '
            <input type="hidden" id="property_floor_plans" name="property_floor_plans" value="' . esc_attr($floor_plans) . '" />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="prop-floor-plans-list">';
        if (count($floor_plans_list) > 0) {
            foreach ($floor_plans_list as $floor_plan) {
                $image = wp_get_attachment_image_src($floor_plan->image, 'pxp-thmb');
                $image_src = RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png';

                if ($image !== false) { 
                    $image_src = $image[0];
                }

                $info = '';
                if ($floor_plan->beds != '') {
                    $info .= $floor_plan->beds . ' ' . $beds_label . ' | ';
                }
                if ($floor_plan->baths != '') {
                    $info .= $floor_plan->baths . ' ' . $baths_label . ' | ';
                }
                if ($floor_plan->size != '') {
                    $info .= $floor_plan->size . ' ' . $unit;
                }

                print '
                            <li class="list-group-item" 
                                    data-id="' . esc_attr($floor_plan->image) . '" 
                                    data-title="' . esc_attr($floor_plan->title) . '" 
                                    data-beds="' . esc_attr($floor_plan->beds) . '" 
                                    data-baths="' . esc_attr($floor_plan->baths) . '" 
                                    data-size="' . esc_attr($floor_plan->size) . '" 
                                    data-description="' . esc_attr($floor_plan->description) . '" 
                                    data-src="' . esc_url($image_src) . '">
                                <div class="floor-plan-item-container">
                                    <img class="pull-left rtl-pull-right" src="' . esc_url($image_src) . '" />
                                    <div class="list-group-item-info">
                                        <div class="list-group-item-info-title">' . esc_html($floor_plan->title) . '</div>
                                        <div class="list-group-item-info-caption">' . esc_html($info) . '</div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <a href="javascript:void(0);" class="pull-right del-btn del-floor-plan"><span class="fa fa-trash-o"></span></a>
                                    <a href="javascript:void(0);" class="pull-right edit-btn edit-floor-plan"><span class="fa fa-pencil"></span></a>
                                    <div class="clearfix"></div>
                                </div>
                            </li>';
            }

            print'
                        </ul>';
        }
        print '         </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td>
                <tr>
                    <td width="100%" valign="top"><input id="add-floor-plan-btn" type="button" class="button" value="' . esc_html__('Add Floor Plan', 'resideo') . '" /></td>
                </tr>
            </table>
            <div class="pxp-new-floor-plan">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-new-floor-plan-container">
                                <div><b>' . esc_html__('New Floor Plan', 'resideo') . '</b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="50%" valign="top">
                                            <div class="adminField">
                                                <label for="property_floor_plan_title">' . esc_html__('Title', 'resideo') . '</label><br>
                                                <input type="text" class="formInput" id="property_floor_plan_title" name="property_floor_plan_title" placeholder="' . esc_attr__('Enter plan title', 'resideo') . '">
                                            </div>
                                        </td>
                                        <td width="50%" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="33%" valign="top">
                                            <div class="adminField">
                                                <label for="property_floor_plan_beds">' . esc_html__('Beds', 'resideo') . '</label><br>
                                                <input type="text" class="formInput" id="property_floor_plan_beds" name="property_floor_plan_beds" placeholder="' . esc_attr__('Enter plan number of beds', 'resideo') . '">
                                            </div>
                                        </td>
                                        <td width="33%" valign="top">
                                            <div class="adminField">
                                                <label for="property_floor_plan_baths">' . esc_html__('Baths', 'resideo') . '</label><br>
                                                <input type="text" class="formInput" id="property_floor_plan_baths" name="property_floor_plan_baths" placeholder="' . esc_attr__('Enter plan number of baths', 'resideo') . '">
                                            </div>
                                        </td>
                                        <td width="33%" valign="top">
                                            <div class="adminField">
                                                <label for="property_floor_plan_size">' . esc_html__('Size', 'resideo') . ' (' . esc_html($unit) . ')' . '</label><br />
                                                <input type="text" class="formInput" id="property_floor_plan_size" name="property_floor_plan_size" placeholder="' . esc_attr__('Enter plan size', 'resideo') . '">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="66%" valign="top">
                                            <div class="adminField">
                                                <label for="property_floor_plan_description">' . esc_html__('Description', 'resideo') . '</label><br>
                                                <textarea id="property_floor_plan_description" name="property_floor_plan_description" placeholder="' . esc_html__('Enter plan description here...', 'resideo') . '" style="width: 100%; height: 140px;"></textarea>
                                            </div>
                                        </td>
                                        <td width="33%" valign="top">
                                            <div class="adminField">
                                                <label>' . esc_html__('Image', 'resideo') . '</label>
                                                <input type="hidden" id="property_floor_plan_image" name="property_floor_plan_image">
                                                <div class="property-floor-plan-image-placeholder-container">
                                                    <div id="property-floor-plan-image-placeholder" style="background-image: url(' . esc_url(RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png') . ');"></div>
                                                    <div id="delete-property-floor-plan-image"><span class="fa fa-trash-o"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="adminField">
                                    <button type="button" id="ok-floor-plan" class="button media-button button-primary">' . esc_html__('Add', 'resideo') . '</button>
                                    <button type="button" id="cancel-floor-plan" class="button media-button button-default">' . esc_html__('Cancel', 'resideo') . '</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>';
    }
endif;

if (!function_exists('resideo_property_calc_render')): 
    function resideo_property_calc_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="property_calc" value="">
                            <input type="checkbox" name="property_calc" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'property_calc', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="property_calc">' . __('Show Mortgage Calculator', 'resideo') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_property_featured_render')): 
    function resideo_property_featured_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="property_featured" value="">
                            <input type="checkbox" name="property_featured" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'property_featured', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="property_featured">' . __('Set as Featured', 'resideo') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_property_meta_save')): 
    function resideo_property_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['property_noncename']) && wp_verify_nonce($_POST['property_noncename'], 'resideo_property')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['property_address'])) {
            update_post_meta($post_id, 'property_address', sanitize_text_field($_POST['property_address']));
        }
        if (isset($_POST['property_lat'])) {
            update_post_meta($post_id, 'property_lat', sanitize_text_field($_POST['property_lat']));
        }
        if (isset($_POST['property_lng'])) {
            update_post_meta($post_id, 'property_lng', sanitize_text_field($_POST['property_lng']));
        }
        if (isset($_POST['street_number'])) {
            update_post_meta($post_id, 'street_number', sanitize_text_field($_POST['street_number']));
        }
        if (isset($_POST['route'])) {
            update_post_meta($post_id, 'route', sanitize_text_field($_POST['route']));
        }
        if (isset($_POST['neighborhood'])) {
            update_post_meta($post_id, 'neighborhood', sanitize_text_field($_POST['neighborhood']));
        }
        if (isset($_POST['locality'])) {
            update_post_meta($post_id, 'locality', sanitize_text_field($_POST['locality']));
        }
        if (isset($_POST['administrative_area_level_1'])) {
            update_post_meta($post_id, 'administrative_area_level_1', sanitize_text_field($_POST['administrative_area_level_1']));
        }
        if (isset($_POST['postal_code'])) {
            update_post_meta($post_id, 'postal_code', sanitize_text_field($_POST['postal_code']));
        }
        if (isset($_POST['property_price'])) {
            update_post_meta($post_id, 'property_price', sanitize_text_field($_POST['property_price']));
        }
        if (isset($_POST['property_price_label'])) {
            update_post_meta($post_id, 'property_price_label', sanitize_text_field($_POST['property_price_label']));
        }
        if (isset($_POST['property_taxes'])) {
            update_post_meta($post_id, 'property_taxes', sanitize_text_field($_POST['property_taxes']));
        }
        if (isset($_POST['property_hoa_dues'])) {
            update_post_meta($post_id, 'property_hoa_dues', sanitize_text_field($_POST['property_hoa_dues']));
        }
        if (isset($_POST['property_beds'])) {
            update_post_meta($post_id, 'property_beds', sanitize_text_field($_POST['property_beds']));
        }
        if (isset($_POST['property_baths'])) {
            update_post_meta($post_id, 'property_baths', sanitize_text_field($_POST['property_baths']));
        }
        if (isset($_POST['property_size'])) {
            update_post_meta($post_id, 'property_size', sanitize_text_field($_POST['property_size']));
        }

        $resideo_amenities_settings = get_option('resideo_amenities_settings');
        if (is_array($resideo_amenities_settings) && count($resideo_amenities_settings) > 0) {
            foreach ($resideo_amenities_settings as $key => $value) {
                if (isset($_POST[$key])) {
                    update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
                }
            }
        }

        $resideo_fields_settings = get_option('resideo_fields_settings');
        if (is_array($resideo_fields_settings)) {
            foreach ($resideo_fields_settings as $key => $value) {
                if (isset($_POST[$key])) {
                    update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
                }
            }
        }
        if (isset($_POST['property_video'])) {
            update_post_meta($post_id, 'property_video', sanitize_text_field($_POST['property_video']));
        }
        if (isset($_POST['property_virtual_tour'])) {
            $virtual_tour_allowed_html = array(
                'iframe' => array(
                    'align' => true,
                    'width' => true,
                    'height' => true,
                    'frameborder' => true,
                    'name' => true,
                    'src' => true,
                    'id' => true,
                    'class' => true,
                    'style' => true,
                    'scrolling' => true,
                    'marginwidth' => true,
                    'marginheight' => true,
                    'allowfullscreen' => true,
                    'allow' => true
                )
            );
            update_post_meta($post_id, 'property_virtual_tour', wp_kses($_POST['property_virtual_tour'], $virtual_tour_allowed_html));
        }

        if (isset($_POST['property_agent'])) {
            update_post_meta($post_id, 'property_agent', sanitize_text_field($_POST['property_agent']));
        }
        if (isset($_POST['property_gallery'])) {
            update_post_meta($post_id, 'property_gallery', sanitize_text_field($_POST['property_gallery']));
        }
        if (isset($_POST['property_floor_plans'])) {
            $floor_plans_list = array();
            $floor_plans_data_raw = urldecode($_POST['property_floor_plans']);
            $floor_plans_data = json_decode($floor_plans_data_raw);

            $floor_plans_data_encoded = '';

            if (isset($floor_plans_data)) {
                $new_data = new stdClass();
                $new_plans = array();

                $floor_plans_list = $floor_plans_data->plans;

                foreach ($floor_plans_list as $floor_plan) {
                    $new_plan = new stdClass();

                    $new_plan->title       = sanitize_text_field($floor_plan->title);
                    $new_plan->beds        = sanitize_text_field($floor_plan->beds);
                    $new_plan->baths       = sanitize_text_field($floor_plan->baths);
                    $new_plan->size        = sanitize_text_field($floor_plan->size);
                    $new_plan->description = sanitize_text_field($floor_plan->description);
                    $new_plan->image       = sanitize_text_field($floor_plan->image);

                    array_push($new_plans, $new_plan);
                }

                $new_data->plans = $new_plans;

                $floor_plans_data_before = json_encode($new_data);
                $floor_plans_data_encoded = urlencode($floor_plans_data_before);
            }

            update_post_meta($post_id, 'property_floor_plans', $floor_plans_data_encoded);
        }
        if (isset($_POST['property_calc'])) {
            update_post_meta($post_id, 'property_calc', sanitize_text_field($_POST['property_calc']));
        }
        if (isset($_POST['property_featured'])) {
            update_post_meta($post_id, 'property_featured', sanitize_text_field($_POST['property_featured']));
        }
    }
endif;
add_action('save_post', 'resideo_property_meta_save');

if (!function_exists('resideo_substr45')): 
    function resideo_substr45($string) {
        return substr($string, 0, 45);
    }
endif;

if (!function_exists('resideo_change_property_default_title')): 
    function resideo_change_property_default_title($title) {
        $screen = get_current_screen();

        if ('property' == $screen->post_type) {
            $title = __('Enter property title here', 'resideo');
        }

        return $title;
    }
endif;
add_filter('enter_title_here', 'resideo_change_property_default_title');

if (!function_exists('resideo_sanitize_multi_array')) :
    function resideo_sanitize_multi_array(&$item, $key) {
        $item = sanitize_text_field($item);
    }
endif;

/**
 * Add custom columns in properties list
 */
if (!function_exists('resideo_properties_columns')): 
    function resideo_properties_columns($columns) {
        $date  = $columns['date'];

        unset($columns['comments']);
        unset($columns['date']);

        $columns['thumb']    = __('Thumb', 'resideo');
        $columns['type']     = __('Type', 'resideo');
        $columns['status']   = __('Status', 'resideo');
        $columns['featured'] = __('Featured', 'resideo');
        $columns['date']     = $date;

        return $columns;
    }
endif;
add_filter('manage_property_posts_columns', 'resideo_properties_columns');

if (!function_exists('resideo_properties_custom_column')): 
    function resideo_properties_custom_column($column, $post_id) {
        switch ($column) {
            case 'thumb':
                $gallery     = get_post_meta($post_id, 'property_gallery', true);
                $photos      = explode(',', $gallery);
                $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-agent');

                if ($first_photo != '') {
                    $photo = $first_photo[0];
                } else {
                    $photo = RESIDEO_PLUGIN_PATH . 'images/property-tile.png';
                }

                echo '<img src="' . esc_attr($photo) . '" style="width: 60px; height: 60px">';

                break;
            case 'type':
                $type = wp_get_post_terms($post_id, 'property_type');

                if (count($type) > 0) {
                    echo esc_html($type[0]->name);
                }

                break;
            case 'status':
                $status = wp_get_post_terms($post_id, 'property_status');

                if (count($status) > 0) {
                    echo esc_html($status[0]->name);
                }

                break;
            case 'featured':
                $featured = get_post_meta($post_id, 'property_featured', true);

                if ($featured == '1') {
                    echo __('Yes', 'resideo');
                } else {
                    echo __('No', 'resideo');
                }
                break;
        }
    }
endif;
add_action('manage_property_posts_custom_column', 'resideo_properties_custom_column', 10, 2);

if (!function_exists('resideo_properties_sortable_columns')): 
    function resideo_properties_sortable_columns($columns) {
        $columns['type']     = 'type';
        $columns['status']   = 'status';
        $columns['featured'] = 'featured';

        return $columns;
    }
endif;
add_filter('manage_edit-property_sortable_columns', 'resideo_properties_sortable_columns');

if (!function_exists('resideo_properties_custom_orderby')): 
    function resideo_properties_custom_orderby($query) {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('featured' == $orderby) {
            $query->set('meta_key', 'property_featured');
            $query->set('orderby', 'meta_value');
        }
    }
endif;
add_action('pre_get_posts', 'resideo_properties_custom_orderby');

if (!function_exists('resideo_properties_orderby_tax')): 
    function resideo_properties_orderby_tax($clauses, $wp_query) {
        global $wpdb;

        // Property Type Taxonomy
        if (isset($wp_query->query['orderby']) && $wp_query->query['orderby'] == 'type') {
            $clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
            $clauses['where'] .= "AND (taxonomy = 'property_type' OR taxonomy IS NULL)";
            $clauses['groupby'] = "object_id";
            $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";

            if (strtoupper($wp_query->get('order')) == 'ASC') {
                $clauses['orderby'] .= 'ASC';
            } else{
                $clauses['orderby'] .= 'DESC';
            }
        }

        // Property Status Taxonomy
        if (isset($wp_query->query['orderby']) && $wp_query->query['orderby'] == 'status') {
            $clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
            $clauses['where'] .= "AND (taxonomy = 'property_status' OR taxonomy IS NULL)";
            $clauses['groupby'] = "object_id";
            $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";

            if (strtoupper($wp_query->get('order')) == 'ASC'){
                $clauses['orderby'] .= 'ASC';
            } else{
                $clauses['orderby'] .= 'DESC';
            }
        }

        return $clauses;
    }
endif;
add_filter('posts_clauses', 'resideo_properties_orderby_tax', 10, 2);
?>