<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Save property
 */
if (!function_exists('resideo_save_property')): 
    function resideo_save_property() {
        check_ajax_referer('submitproperty_ajax_nonce', 'security');

        $fields_settings = get_option('resideo_prop_fields_settings');

        $p_overview         = isset($fields_settings['resideo_p_overview_field']) ? $fields_settings['resideo_p_overview_field'] : '';
        $p_overview_req     = isset($fields_settings['resideo_p_overview_r_field']) ? $fields_settings['resideo_p_overview_r_field'] : '';
        $p_address          = isset($fields_settings['resideo_p_address_field']) ? $fields_settings['resideo_p_address_field'] : '';
        $p_address_req      = isset($fields_settings['resideo_p_address_r_field']) ? $fields_settings['resideo_p_address_r_field'] : '';
        $p_coordinates      = isset($fields_settings['resideo_p_coordinates_field']) ? $fields_settings['resideo_p_coordinates_field'] : '';
        $p_coordinates_req  = isset($fields_settings['resideo_p_coordinates_r_field']) ? $fields_settings['resideo_p_coordinates_r_field'] : '';
        $p_streetno         = isset($fields_settings['resideo_p_streetno_field']) ? $fields_settings['resideo_p_streetno_field'] : '';
        $p_streetno_req     = isset($fields_settings['resideo_p_streetno_r_field']) ? $fields_settings['resideo_p_streetno_r_field'] : '';
        $p_street           = isset($fields_settings['resideo_p_street_field']) ? $fields_settings['resideo_p_street_field'] : '';
        $p_street_req       = isset($fields_settings['resideo_p_street_r_field']) ? $fields_settings['resideo_p_street_r_field'] : '';
        $p_neighborhood     = isset($fields_settings['resideo_p_neighborhood_field']) ? $fields_settings['resideo_p_neighborhood_field'] : '';
        $p_neighborhood_req = isset($fields_settings['resideo_p_neighborhood_r_field']) ? $fields_settings['resideo_p_neighborhood_r_field'] : '';
        $p_city             = isset($fields_settings['resideo_p_city_field']) ? $fields_settings['resideo_p_city_field'] : '';
        $p_city_req         = isset($fields_settings['resideo_p_city_r_field']) ? $fields_settings['resideo_p_city_r_field'] : '';
        $p_state            = isset($fields_settings['resideo_p_state_field']) ? $fields_settings['resideo_p_state_field'] : '';
        $p_state_req        = isset($fields_settings['resideo_p_state_r_field']) ? $fields_settings['resideo_p_state_r_field'] : '';
        $p_zip              = isset($fields_settings['resideo_p_zip_field']) ? $fields_settings['resideo_p_zip_field'] : '';
        $p_zip_req          = isset($fields_settings['resideo_p_zip_r_field']) ? $fields_settings['resideo_p_zip_r_field'] : '';
        $p_price            = isset($fields_settings['resideo_p_price_field']) ? $fields_settings['resideo_p_price_field'] : '';
        $p_price_req        = isset($fields_settings['resideo_p_price_r_field']) ? $fields_settings['resideo_p_price_r_field'] : '';
        $p_size             = isset($fields_settings['resideo_p_size_field']) ? $fields_settings['resideo_p_size_field'] : '';
        $p_size_req         = isset($fields_settings['resideo_p_size_r_field']) ? $fields_settings['resideo_p_size_r_field'] : '';
        $p_beds             = isset($fields_settings['resideo_p_beds_field']) ? $fields_settings['resideo_p_beds_field'] : '';
        $p_beds_req         = isset($fields_settings['resideo_p_beds_r_field']) ? $fields_settings['resideo_p_beds_r_field'] : '';
        $p_baths            = isset($fields_settings['resideo_p_baths_field']) ? $fields_settings['resideo_p_baths_field'] : '';
        $p_baths_req        = isset($fields_settings['resideo_p_baths_r_field']) ? $fields_settings['resideo_p_baths_r_field'] : '';
        $p_type             = isset($fields_settings['resideo_p_type_field']) ? $fields_settings['resideo_p_type_field'] : '';
        $p_type_req         = isset($fields_settings['resideo_p_type_r_field']) ? $fields_settings['resideo_p_type_r_field'] : '';
        $p_status           = isset($fields_settings['resideo_p_status_field']) ? $fields_settings['resideo_p_status_field'] : '';
        $p_status_req       = isset($fields_settings['resideo_p_status_r_field']) ? $fields_settings['resideo_p_status_r_field'] : '';

        $user_id      = isset($_POST['user']) ? sanitize_text_field($_POST['user']) : '';
        $new_id       = isset($_POST['new_id']) ? sanitize_text_field($_POST['new_id']) : '';
        $title        = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $type         = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '0';
        $status       = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '0';
        $overvew      = isset($_POST['overvew']) ? $_POST['overvew'] : '';
        $address      = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
        $lat          = isset($_POST['lat']) ? sanitize_text_field($_POST['lat']) : '';
        $lng          = isset($_POST['lng']) ? sanitize_text_field($_POST['lng']) : '';
        $street_no    = isset($_POST['street_no']) ? sanitize_text_field($_POST['street_no']) : '';
        $street       = isset($_POST['street']) ? sanitize_text_field($_POST['street']) : '';
        $neighborhood = isset($_POST['neighborhood']) ? sanitize_text_field($_POST['neighborhood']) : '';
        $city         = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
        $state        = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
        $zip          = isset($_POST['zip']) ? sanitize_text_field($_POST['zip']) : '';
        $price        = isset($_POST['price']) ? sanitize_text_field($_POST['price']) : '';
        $price_label  = isset($_POST['price_label']) ? sanitize_text_field($_POST['price_label']) : '';
        $size         = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : '';
        $beds         = isset($_POST['beds']) ? sanitize_text_field($_POST['beds']) : '';
        $baths        = isset($_POST['baths']) ? sanitize_text_field($_POST['baths']) : '';
        $calculator   = isset($_POST['calculator']) ? sanitize_text_field($_POST['calculator']) : '';
        $taxes        = (isset($_POST['taxes']) && $_POST['taxes'] != '') ? sanitize_text_field($_POST['taxes']) : '0';
        $hoa          = (isset($_POST['hoa']) && $_POST['hoa'] != '') ? sanitize_text_field($_POST['hoa']) : '0';
        $gallery      = isset($_POST['gallery']) ? sanitize_text_field($_POST['gallery']) : '';
        $video        = isset($_POST['video']) ? sanitize_text_field($_POST['video']) : '';
        $featured     = isset($_POST['featured']) ? sanitize_text_field($_POST['featured']) : '';

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
        $virtual_tour = isset($_POST['virtual_tour']) ? wp_kses($_POST['virtual_tour'], $virtual_tour_allowed_html) : '';

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'resideo_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        $amenities = isset($_POST['amenities']) ? array_map('resideo_sanitize_item', $_POST['amenities']) : '';

        $agent_id = resideo_get_agent_by_userid($user_id);

        $general_settings = get_option('resideo_general_settings');
        $no_review = isset($general_settings['resideo_no_review_field']) ? $general_settings['resideo_no_review_field'] : '';

        if ($no_review != '' || ($new_id != '' && get_post_status($new_id) == 'publish')) {
            $prop_status = 'publish';
        } else {
            $prop_status = 'pending';
        }

        $prop = array(
            'post_title'   => $title,
            'post_content' => $overvew,
            'post_type'    => 'property',
            'post_status'  => $prop_status,
            'post_author'  => $user_id
        );

        if ($new_id != '') {
            $prop['ID'] = $new_id;
        }
        if ($title == '') {
            echo json_encode(array('save' => false, 'message' => __('Title field is mandatory.', 'resideo')));
            exit();
        }
        if ($overvew == '' && $p_overview == 'enabled' && $p_overview_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Overview field is mandatory.', 'resideo')));
            exit();
        }
        if ($address == '' && $p_address == 'enabled' && $p_address_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Address field is mandatory.', 'resideo')));
            exit();
        }
        if ($lat == '' && $lng == '' && $p_coordinates == 'enabled' && $p_coordinates_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Coordinates fields are mandatory.', 'resideo')));
            exit();
        }
        if ($street_no == '' && $p_streetno == 'enabled' && $p_streetno_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Street No field is mandatory.', 'resideo')));
            exit();
        }
        if ($street == '' && $p_street == 'enabled' && $p_street_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Street Name field is mandatory.', 'resideo')));
            exit();
        }
        if ($neighborhood == '' && $p_neighborhood == 'enabled' && $p_neighborhood_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Neighborhood field is mandatory.', 'resideo')));
            exit();
        }
        if ($city == '' && $p_city == 'enabled' && $p_city_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('City field is mandatory.', 'resideo')));
            exit();
        }
        if ($state == '' && $p_state == 'enabled' && $p_state_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('State field is mandatory.', 'resideo')));
            exit();
        }
        if ($zip == '' && $p_zip == 'enabled' && $p_zip_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Zip field is mandatory.', 'resideo')));
            exit();
        }
        if ($price == '' && $p_price == 'enabled' && $p_price_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Price field is mandatory.', 'resideo')));
            exit();
        }
        if ($size == '' && $p_size == 'enabled' && $p_size_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Size field is mandatory.', 'resideo')));
            exit();
        }
        if ($beds == '' && $p_beds == 'enabled' && $p_beds_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Beds field is mandatory.', 'resideo')));
            exit();
        }
        if ($baths == '' && $p_baths == 'enabled' && $p_baths_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Baths field is mandatory.', 'resideo')));
            exit();
        }
        if ($type == '0' && $p_type == 'enabled' && $p_type_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Type field is mandatory.', 'resideo')));
            exit();
        }
        if ($status == '0' && $p_status == 'enabled' && $p_status_req == 'required') {
            echo json_encode(array('save' => false, 'message' => __('Category field is mandatory.', 'resideo')));
            exit();
        }
        if ($custom_fields != '') {
            foreach ($custom_fields as $key => $value) {
                if ($value['field_mandatory'] == 'yes' && $value['field_value'] == '') {
                    echo json_encode(array('save' => false, 'message' => sprintf (__('%s field is mandatory.', 'resideo'), $value['field_label'])));
                    exit();
                }
            }
        }

        $prop_id = wp_insert_post($prop);
        $prop_link = get_permalink($prop_id);

        wp_set_object_terms($prop_id, array(intval($type)), 'property_type');
        wp_set_object_terms($prop_id, array(intval($status)), 'property_status');

        update_post_meta($prop_id, 'property_address', $address);
        update_post_meta($prop_id, 'property_lat', $lat);
        update_post_meta($prop_id, 'property_lng', $lng);
        update_post_meta($prop_id, 'street_number', $street_no);
        update_post_meta($prop_id, 'route', $street);
        update_post_meta($prop_id, 'neighborhood', $neighborhood);
        update_post_meta($prop_id, 'locality', $city);
        update_post_meta($prop_id, 'administrative_area_level_1', $state);
        update_post_meta($prop_id, 'postal_code', $zip);
        update_post_meta($prop_id, 'property_price', $price);
        update_post_meta($prop_id, 'property_price_label', $price_label);
        update_post_meta($prop_id, 'property_size', $size);
        update_post_meta($prop_id, 'property_beds', $beds);
        update_post_meta($prop_id, 'property_baths', $baths);
        update_post_meta($prop_id, 'property_agent', $agent_id);
        update_post_meta($prop_id, 'property_gallery', $gallery);
        update_post_meta($prop_id, 'property_calc', $calculator);
        update_post_meta($prop_id, 'property_featured', $featured);
        update_post_meta($prop_id, 'property_taxes', $taxes);
        update_post_meta($prop_id, 'property_hoa_dues', $hoa);
        update_post_meta($prop_id, 'property_video', $video);
        update_post_meta($prop_id, 'property_virtual_tour', $virtual_tour);

        if (isset($_POST['floor_plans'])) {
            $floor_plans_list = array();
            $floor_plans_data_raw = urldecode($_POST['floor_plans']);
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

            update_post_meta($prop_id, 'property_floor_plans', $floor_plans_data_encoded);
        }

        if ($new_id == '') {
            update_post_meta($prop_id, 'property_featured', '');
        }

        $amenities_settings = get_option('resideo_amenities_settings');

        if (is_array($amenities_settings) && count($amenities_settings) > 0) {
            foreach ($amenities_settings as $key => $value) {
                if (is_array($amenities) && in_array($key, $amenities)) {
                    update_post_meta($prop_id, $key, 1);
                } else {
                    update_post_meta($prop_id, $key, NULL);
                }
            }
        }

        if ($custom_fields != '') {
            foreach ($custom_fields as $key => $value) {
                update_post_meta($prop_id, $value['field_name'], $value['field_value']);
            }
        }

        $membership_settings = get_option('resideo_membership_settings');
        $payment_type        = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';
        $standard_unlim      = isset($membership_settings['resideo_free_submissions_unlim_field']) ? $membership_settings['resideo_free_submissions_unlim_field'] : '';
        $agent_payment       = get_post_meta($agent_id, 'agent_payment', true);

        if ($agent_payment == '1') {
            update_post_meta($prop_id, 'payment_status', 'paid');
        } else {
            // update the free standard submissions number on agent
            if ($new_id == '' && $payment_type == 'listing') {
                $agent_free_listings = get_post_meta($agent_id, 'agent_free_listings', true);
                $afl_int = intval($agent_free_listings);

                if ($afl_int > 0 || $standard_unlim == '1') {
                    update_post_meta($agent_id, 'agent_free_listings', $afl_int - 1);
                    update_post_meta($prop_id, 'payment_status', 'paid');
                } else {
                    $updated_prop = array('ID' => $prop_id, 'post_status' => 'pending');
                    wp_update_post($updated_prop);
                }
            }

            // update the membership submissions number for agent
            if ($new_id == '' && $payment_type == 'membership') {
                $agent_plan_listings = get_post_meta($agent_id, 'agent_plan_listings', true);
                $apl_int = intval($agent_plan_listings);

                update_post_meta($agent_id, 'agent_plan_listings', $apl_int - 1);
                update_post_meta($prop_id, 'payment_status', 'paid');
            }
        }

        if ($prop_id != 0) {
            $notifications_settings = get_option('resideo_notifications_settings');
            $notify_admin = isset($notifications_settings['resideo_notify_admin_publish_field']) ? $notifications_settings['resideo_notify_admin_publish_field'] : '';

            if ($notify_admin == 1) {
                resideo_admin_property_notification($title, $agent_id, $new_id);
            }

            if ($no_review != '' || ($new_id != '' && get_post_status($new_id) == 'publish')) {
                echo json_encode(array('save' => true, 'propID' => $prop_id, 'propLink' => $prop_link, 'propStatus' => 'publish', 'message' => __('The property was successfully saved. Redirecting...', 'resideo')));
                exit();
            } else {
                echo json_encode(array('save' => true, 'propID' => $prop_id, 'propLink' => $prop_link, 'propStatus' => 'pending', 'message' => __('The property was successfully saved. Redirecting...', 'resideo')));
                exit();
            }
        } else {
            echo json_encode(array('save' => false, 'message' => __('Something went wrong. The property was not saved.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_save_property', 'resideo_save_property');
add_action('wp_ajax_resideo_save_property', 'resideo_save_property');

/**
 * Admin notification when new property submitted
 */
if (!function_exists('resideo_admin_property_notification')): 
    function resideo_admin_property_notification($property_title, $agent_id, $edit) {
        if ($edit == '') {
            $message = sprintf( __('A new property was submitted on %s:', 'resideo'), get_option('blogname') ) . '<br /><br />';
            $message .= sprintf( __('Property title: %s', 'resideo'), esc_html($property_title) ) . '<br />';
            $message .= sprintf( __('Agent: %s', 'resideo'), get_the_title($agent_id) );

            wp_mail(
                get_option('admin_email'),
                sprintf(__('[%s] Property Submitted', 'resideo'), get_option('blogname') ),
                $message
            );
        } else {
            $message = sprintf( __('A property was updated on %s:', 'resideo'), get_option('blogname') ) . '<br /><br />';
            $message .= sprintf( __('Property title: %s', 'resideo'), esc_html($property_title) ) . '<br />';
            $message .= sprintf( __('Agent: %s', 'resideo'), get_the_title($agent_id) );

            wp_mail(
                get_option('admin_email'),
                sprintf(__('[%s] Property Updated', 'resideo'), get_option('blogname') ),
                $message
            );
        }
    }
endif;

/**
 * Delete property
 */
if (!function_exists('resideo_delete_property')): 
    function resideo_delete_property() {
        check_ajax_referer('submitproperty_ajax_nonce', 'security');

        $del_id = isset($_POST['new_id']) ? sanitize_text_field($_POST['new_id']) : '';

        $del_prop = wp_delete_post($del_id);

        if ($del_prop) {
            echo json_encode(array('delete' => true, 'message' => __('The property was successfully deleted. Redirecting...', 'resideo')));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'message' => __('Something went wrong. Please try again.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_property', 'resideo_delete_property');
add_action('wp_ajax_resideo_delete_property', 'resideo_delete_property');

/**
 * Upgrade property to featured
 */
if (!function_exists('resideo_upgrade_property_featured')): 
    function resideo_upgrade_property_featured() {
        check_ajax_referer('upgradeproperty_ajax_nonce', 'security');

        $prop_id       = isset($_POST['prop_id']) ? sanitize_text_field($_POST['prop_id']) : '';
        $agent_id      = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $agent_payment = get_post_meta($agent_id, 'agent_payment', true);

        $feat_prop = update_post_meta($prop_id, 'property_featured', 1);

        $agent_free_featured_listings = get_post_meta($agent_id, 'agent_free_featured_listings', true);
        $affl_int = intval($agent_free_featured_listings);

        if ($agent_payment != '1') {
            update_post_meta($agent_id, 'agent_free_featured_listings', $affl_int - 1);
        }

        if ($feat_prop) {
            echo json_encode(array('upgrade' => true, 'message' => __('The property was successfully upgraded to featured. Redirecting...', 'resideo')));
            exit();
        } else {
            echo json_encode(array('upgrade' => false, 'message' => __('Something went wrong. Please try again.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_upgrade_property_featured', 'resideo_upgrade_property_featured');
add_action('wp_ajax_resideo_upgrade_property_featured', 'resideo_upgrade_property_featured');

/**
 * Set property as featured from agent plan
 */
if (!function_exists('resideo_set_property_featured')): 
    function resideo_set_property_featured() {
        check_ajax_referer('featuredproperty_ajax_nonce', 'security');

        $prop_id       = isset($_POST['prop_id']) ? sanitize_text_field($_POST['prop_id']) : '';
        $agent_id      = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $agent_payment = get_post_meta($agent_id, 'agent_payment', true);

        $feat_prop = update_post_meta($prop_id, 'property_featured', 1);

        $agent_plan_featured_listings = get_post_meta($agent_id, 'agent_plan_featured', true);
        $apfl_int                     = intval($agent_plan_featured_listings);

        if ($agent_payment != '1') {
            update_post_meta($agent_id, 'agent_plan_featured', $apfl_int - 1);
        }

        if ($feat_prop) {
            echo json_encode(array('upgrade' => true, 'message' => __('The property was successfully set as featured. Redirecting...', 'resideo')));
            exit();
        } else {
            echo json_encode(array('upgrade' => false, 'message' => __('Something went wrong. The property was not set as featured.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_set_property_featured', 'resideo_set_property_featured');
add_action('wp_ajax_resideo_set_property_featured', 'resideo_set_property_featured');
?>