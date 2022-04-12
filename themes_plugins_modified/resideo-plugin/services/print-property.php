<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Print property
 */
if( !function_exists('resideo_print_property') ): 
    function resideo_print_property() {
        check_ajax_referer('print_ajax_nonce', 'security');

        $prop_id = isset($_POST['propID']) ? sanitize_text_field($_POST['propID']) : '';

        $title = get_the_title($prop_id);

        $fields_settings   = get_option('resideo_prop_fields_settings');
        $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
        $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
        $neighborhoods     = get_option('resideo_neighborhoods_settings');
        $cities            = get_option('resideo_cities_settings');

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

        $general_settings = get_option('resideo_general_settings');
        $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
        $currency         = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
        $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
        $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
        $currency_pos     = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
        $locale           = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
        $decimals         = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
        setlocale(LC_MONETARY, $locale);

        $price       = get_post_meta($prop_id, 'property_price', true);
        $price_label = get_post_meta($prop_id, 'property_price_label', true);

        if (is_numeric($price)) {
            if ($decimals == '1') {
                $price = money_format('%!i', $price);
            } else {
                $price = money_format('%!.0i', $price);
            }
        } else {
            $price_label = '';
            $currency = '';
        }

        $beds  = get_post_meta($prop_id, 'property_beds', true);
        $baths = get_post_meta($prop_id, 'property_baths', true);
        $size  = get_post_meta($prop_id, 'property_size', true);

        $gallery = get_post_meta($prop_id, 'property_gallery', true);
        $photos  = explode(',', $gallery);

        $floor_plans = get_post_meta($prop_id, 'property_floor_plans', true);

        $status = wp_get_post_terms($prop_id, 'property_status');
        $type   = wp_get_post_terms($prop_id, 'property_type');

        $custom_fields_settings = get_option('resideo_fields_settings');

        $page_data = get_post($prop_id);
        $overview = $page_data->post_content;

        $amenities_settings = get_option('resideo_amenities_settings');
        $amenities_count = 0;

        if (is_array($amenities_settings) && count($amenities_settings) > 0) {
            foreach ($amenities_settings as $key => $value) {
                if (get_post_meta($prop_id, $key, true) == 1) {
                    $amenities_count++;
                }
            }
        }

        $agent_id = get_post_meta($prop_id, 'property_agent', true);
        $agent    = ($agent_id != '') ? get_post($agent_id) : ''; ?>

        <!DOCTYPE html>
        <html lang="en">
            <head>
                <link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>">
            </head>
            <body onload="print()">
                <p><strong><?php echo get_bloginfo('name'); ?></strong></p>
                <h2 class="pxp-sp-top-title"><?php echo esc_html($title); ?></h2>
                <p class="pxp-sp-top-address pxp-text-light"><?php echo esc_html($address); ?></p>
                <div class="pxp-sp-top-price" style="float: none;">
                    <?php if ($currency_pos == 'before') {
                        echo esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                    } else {
                        echo esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
                    } ?>
                </div>
                <div class="pxp-sp-top-feat" style="float: none; margin-left: 0;">
                    <?php if ($beds != '') { ?>
                        <div style="margin-left: 0; margin-right: 10px;"><?php echo esc_html($beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                    <?php }
                    if ($baths != '') { ?>
                        <div style="margin-left: 0; margin-right: 10px;"><?php echo esc_html($baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                    <?php }
                    if ($size != '') { ?>
                        <div style="margin-left: 0; margin-right: 10px;"><?php echo esc_html($size); ?> <span><?php echo esc_html($unit); ?></span></div>
                    <?php } ?>
                </div>
                <div style="display: flex; flex-wrap: wrap;">
                    <?php for ($i = 0; $i < count($photos); $i++) {
                        $p_photo_gallery = wp_get_attachment_image_src($photos[$i], 'pxp-gallery'); ?>
                        <img src="<?php echo esc_url($p_photo_gallery[0]); ?>" alt="" style="width: 48%; height: auto; margin: 1%;">
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
                <div class="pxp-single-property-section">
                    <h3><?php esc_html_e('Key Details', 'resideo'); ?></h3>
                    <table width="100%">
                        <tbody>
                            <?php if ($status) { ?>
                                <tr>
                                    <th scope="row" width="50%" align="left"><?php esc_html_e('Status', 'resideo'); ?></th>
                                    <td width="50%"><?php echo esc_html($status[0]->name); ?></td>
                                </tr>
                            <?php } 

                            if ($type) { ?>
                                <tr>
                                    <th scope="row" width="50%" align="left"><?php esc_html_e('Type', 'resideo'); ?></th>
                                    <td width="50%"><?php echo esc_html($type[0]->name); ?></td>
                                </tr>
                            <?php } 

                            if (is_array($custom_fields_settings)) {
                                uasort($custom_fields_settings, "resideo_compare_position");

                                foreach ($custom_fields_settings as $key => $value) {
                                    $cf_label = $value['label'];
                                    if (function_exists('icl_translate')) {
                                        $cf_label = icl_translate('resideo', 'resideo_property_field_' . $value['label'], $value['label']);
                                    }

                                    $field_value = get_post_meta($prop_id, $key, true);

                                    if ($field_value != '') { ?>
                                        <tr>
                                            <?php if ($value['type'] == 'list_field') {
                                                $list = explode(',', $value['list']); ?>
                                                <th scope="row" width="50%" align="left"><?php echo esc_html($cf_label); ?></th>
                                                <td width="50%"><?php echo esc_html($list[$field_value]); ?></td>
                                            <?php } else { ?>
                                                <th scope="row" width="50%" align="left"><?php echo esc_html($cf_label); ?></th>
                                                <td width="50%"><?php echo esc_html($field_value); ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($overview != '') { ?>
                    <div class="pxp-single-property-section">
                        <h3><?php esc_html_e('Overview', 'resideo'); ?></h3>
                        <?php echo $overview; ?>
                    </div>
                <?php }

                if ($amenities_count > 0) { ?>
                    <div class="pxp-single-property-section">
                        <h3><?php esc_html_e('Amenities', 'resideo'); ?></h3>
                        <div style="display: flex; flex-wrap: wrap;">
                            <?php if (is_array($amenities_settings) && count($amenities_settings) > 0) {
                                uasort($amenities_settings, "resideo_compare_position");

                                foreach ($amenities_settings as $key => $value) {
                                    $am_label = $value['label'];
                                    if (function_exists('icl_translate')) {
                                        $am_label = icl_translate('resideo', 'resideo_property_amenity_' . $value['label'], $value['label']);
                                    }

                                    if (get_post_meta($prop_id, $key, true) == 1) { ?>
                                        <div style="width: 50%;"><?php echo esc_html($am_label); ?></div>
                                    <?php }
                                }
                            } ?>
                        </div>
                    </div>
                <?php }

                $floor_plans_list = array();

                if ($floor_plans != '') {
                    $floor_plans_data = json_decode(urldecode($floor_plans));

                    if (isset($floor_plans_data)) {
                        $floor_plans_list = $floor_plans_data->plans;
                    }
                }

                if (count($floor_plans_list) > 0) { ?>
                    <div class="pxp-single-property-section">
                        <h3><?php esc_html_e('Floor Plans', 'resideo'); ?></h3>
                        <?php foreach ($floor_plans_list as $floor_plan) {
                            $floor_plan_image = wp_get_attachment_image_src($floor_plan->image, 'pxp-full'); ?>

                            <div class="pxp-sp-floor-plans-item-title" style="font-weight: bold;"><?php echo esc_html($floor_plan->title); ?></div>
                            <div class="pxp-sp-floor-plans-item-info" style="text-align: left;">
                                <?php if ($floor_plan->beds != '') { ?>
                                    <div style="display: inline-block; margin-right: 10px;"><?php echo esc_html($floor_plan->beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                                <?php } ?>
                                <?php if ($floor_plan->baths != '') { ?>
                                    <div style="display: inline-block; margin-right: 10px;"><?php echo esc_html($floor_plan->baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                                <?php } ?>
                                <?php if ($floor_plan->size != '') { ?>
                                    <div style="display: inline-block; margin-right: 10px;"><?php echo esc_html($floor_plan->size); ?> <span><?php echo esc_html($unit); ?></span></div>
                                <?php } ?>
                            </div>
                            <?php if ($floor_plan_image != '') { ?>
                                <img src="<?php echo esc_url($floor_plan_image[0]); ?>" alt="" style="width: 100%; height: auto;">
                            <?php } ?>
                            <p><?php echo esc_html($floor_plan->description); ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ($agent_id != '') { 
                    $agent_avatar       = get_post_meta($agent_id, 'agent_avatar', true);
                    $agent_avatar_photo = wp_get_attachment_image_src($agent_avatar, 'pxp-thmb');

                    if ($agent_avatar_photo != '') {
                        $a_photo = $agent_avatar_photo[0];
                    } else {
                        $a_photo = RESIDEO_LOCATION . '/images/avatar-default.png';
                    }

                    $agent_name  = get_the_title($agent_id);
                    $agent_email = get_post_meta($agent_id, 'agent_email', true);
                    $agent_phone = get_post_meta($agent_id, 'agent_phone', true); ?>

                    <div class="pxp-single-property-section">
                        <h3><?php esc_html_e('Listed By', 'resideo'); ?></h3>
                        <img src="<?php echo esc_attr($a_photo); ?>" style="width: 100px; height: auto;">
                        <div class="pxp-sp-agent-info-name"><?php echo esc_html($agent_name); ?></div>

                        <?php if ($agent_email != '') { ?>
                            <p><?php echo esc_html($agent_email); ?></p>
                        <?php }

                        if ($agent_phone != '') { ?>
                            <p><?php echo esc_html($agent_phone); ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
            </body>
        </html>

        <?php exit();
        die();
    }
endif;
add_action( 'wp_ajax_nopriv_resideo_print_property', 'resideo_print_property' );
add_action( 'wp_ajax_resideo_print_property', 'resideo_print_property' );
?>