<?php

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();
$base_url_img == "https://img.miogest.com/";

while (have_posts()) : the_post();
    $prop_id = get_the_ID();
    $user = wp_get_current_user();

    $appearance_settings = get_option('resideo_appearance_settings');

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
    $price_reserved = get_post_meta($prop_id, 'property_price_reserved', true) == 'true';
    $price_label = get_post_meta($prop_id, 'property_price_label', true);

    $taxes = get_post_meta($prop_id, 'property_taxes', true);
    $hoa_dues = get_post_meta($prop_id, 'property_hoa_dues', true);

    if (!is_numeric($taxes)) {
        $taxes = 0;
    }

    if (!is_numeric($hoa_dues)) {
        $hoa_dues = 0;
    }

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
    $photos  = array_slice(explode(',', $gallery), 1);


    $floor_plans = get_post_meta($prop_id, 'property_floor_plans', true);

    $status = wp_get_post_terms($prop_id, 'property_status');
    $type   = wp_get_post_terms($prop_id, 'property_type');

    $custom_fields_settings = get_option('resideo_fields_settings');

    $overview = get_the_content();

    $amenities_settings = get_option('resideo_amenities_settings');
    $amenities_count = 0;

    if (is_array($amenities_settings) && count($amenities_settings) > 0) {
        foreach ($amenities_settings as $key => $value) {
            if (get_post_meta($prop_id, $key, true) == 1) {
                $amenities_count++;
            }
        }
    }

    $video = get_post_meta($prop_id, 'property_video', true);
    $virtual_tour = get_post_meta($prop_id, 'property_virtual_tour', true);

    $lat = get_post_meta($prop_id, 'property_lat', true);
    $lng = get_post_meta($prop_id, 'property_lng', true);

    $calculator = get_post_meta($prop_id, 'property_calc', true);

    $agent_id = get_post_meta($prop_id, 'property_agent', true);
    $agent    = ($agent_id != '') ? get_post($agent_id) : '';

    $top_element = isset($appearance_settings['resideo_property_top_field']) ? $appearance_settings['resideo_property_top_field'] : 'title';
    $gallery_class = $top_element == 'title' ? 'mt-4 mt-md-5' : 'pxp-single-property-top';

    $show_print = isset($general_settings['resideo_show_print_property_field']) ? $general_settings['resideo_show_print_property_field'] : '';
    $show_report = isset($general_settings['resideo_show_report_property_field']) ? $general_settings['resideo_show_report_property_field'] : ''; ?>

    <input type="hidden" name="single_id" id="single_id" value="<?php echo esc_attr($prop_id); ?>" />
    <input type="hidden" name="lat" id="lat" value="<?php echo esc_attr($lat); ?>" />
    <input type="hidden" name="lng" id="lng" value="<?php echo esc_attr($lng); ?>" />
    <input type="hidden" name="taxes" id="taxes" value="<?php echo esc_attr($taxes); ?>" />
    <input type="hidden" name="hoa_dues" id="hoa_dues" value="<?php echo esc_attr($hoa_dues); ?>" />

    <div class="pxp-content">
        <?php if ($top_element == 'title') { ?>
            <div class="pxp-single-property-top pxp-content-wrapper mt-100">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <h2 class="pxp-sp-top-title"><?php the_title(); ?></h2>

                            <p class="pxp-sp-top-address pxp-text-light"><?php echo esc_html($address); ?></p>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="pxp-sp-top-btns mt-2 mt-md-0">
                                <?php $wishlist = get_user_meta($user->ID, 'property_wishlist', true);
                                if (!empty($wishlist)) {
                                    if (is_user_logged_in()) {
                                        print '<input type="hidden" id="pxp-sp-top-uid" value="' . esc_attr($user->ID) . '">';

                                        if (in_array($prop_id, $wishlist) === false) {
                                            print '<a href="javascript:void(0);" class="pxp-sp-top-btn" id="pxp-sp-top-btn-save"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                        } else {
                                            print '<a href="javascript:void(0);" class="pxp-sp-top-btn pxp-is-saved" id="pxp-sp-top-btn-save"><span class="fa fa-star"></span> ' . esc_html__('Saved', 'resideo') . '</a>';
                                        }
                                    } else {
                                        print '<a href="javascript:void(0);" data-toggle="modal" data-target="#pxp-signin-modal" class="pxp-sp-top-btn"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    }
                                } else {
                                    if (is_user_logged_in()) {
                                        print '<input type="hidden" id="pxp-sp-top-uid" value="' . esc_attr($user->ID) . '">';
                                        print '<a href="javascript:void(0);" class="pxp-sp-top-btn" id="pxp-sp-top-btn-save"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    } else {
                                        print '<a href="javascript:void(0);" data-toggle="modal" data-target="#pxp-signin-modal" class="pxp-sp-top-btn"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    }
                                }
                                wp_nonce_field('wishlist_ajax_nonce', 'pxp-single-property-save-security', true);

                                if (function_exists('resideo_get_share_menu')) {
                                    resideo_get_share_menu($prop_id);
                                } ?>

                                <?php if ($show_print != '' || $show_report != '') { ?>
                                    <div class="dropdown">
                                        <a class="pxp-sp-top-btn" href="javascript:void(0);" role="button" id="moreOptionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-ellipsis-h mx-0"></span></a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="moreOptionsDropdown">
                                            <?php if ($show_print != '') { ?>
                                                <a class="dropdown-item" href="javascript:void(0);" id="pxp-print-property" data-id="<?php echo esc_attr($prop_id); ?>">
                                                    <span class="fa fa-print"></span> <?php esc_html_e('Print listing', 'resideo'); ?>
                                                </a>
                                                <?php wp_nonce_field('print_ajax_nonce', 'securityPrintProperty', true); ?>
                                            <?php }
                                            if ($show_report != '' && function_exists('resideo_get_report_property_modal')) {
                                                $report_modal_info          = array();
                                                $report_modal_info['link']  = get_permalink($prop_id);
                                                $report_modal_info['title'] = get_the_title(); ?>

                                                <a class="dropdown-item" href="#pxp-report-property-modal" data-toggle="modal" data-target="#pxp-report-property-modal">
                                                    <span class="fa fa-flag-o"></span> <?php esc_html_e('Report problem with listing', 'resideo'); ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix d-block d-xl-none"></div>
                            <div class="pxp-sp-top-feat mt-3 mt-md-0">
                                <?php if ($beds != '') { ?>
                                    <div><?php echo esc_html($beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                                <?php }
                                if ($baths != '') { ?>
                                    <div><?php echo esc_html($baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                                <?php }
                                if ($size != '') { ?>
                                    <div><?php echo esc_html($size); ?> <span><?php echo esc_html($unit); ?></span></div>
                                <?php } ?>
                            </div>
                            <div class="pxp-sp-top-price mt-3 mt-md-0">
                                <?php if ($currency_pos == 'before') {
                                    $price_str = esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                                } else {
                                    $price_str = esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
                                } 
                                
                                if ($price_reserved) {
                                    $price_str = 'Prezzo riservato' . ' <span>' . esc_html($price_label) . '</span>';
                                }
                                
                                echo $price_str; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($photos[0] != '') {
            $has_four = false;

            switch (count($photos)) {
                case 1:
                    $first_fig_class = 'pxp-is-full';
                    $fig_class = '';
                    break;
                case 2:
                    $first_fig_class = 'pxp-is-half';
                    $fig_class = 'pxp-is-half';
                    break;
                case 3:
                    $first_fig_class = 'pxp-is-half';
                    $fig_class = 'pxp-is-third';
                    break;
                case 4:
                    $first_fig_class = 'pxp-is-half';
                    $fig_class = 'pxp-is-third';
                    $has_four = true;
                    break;
                default:
                    $first_fig_class = 'pxp-is-half';
                    $fig_class = '';
                    break;
            } ?>
            <div class="pxp-single-property-gallery-container <?php echo esc_attr($gallery_class); ?>">
                <div class="pxp-single-property-gallery" itemscope itemtype="http://schema.org/ImageGallery">
                    <?php for ($i = 0; $i < count($photos); $i++) {
                        $p_photo_full = wp_get_attachment_image_src($photos[$i], 'pxp-full');
                        $p_photo_gallery = wp_get_attachment_image_src($photos[$i], 'pxp-gallery');
                        $p_photo_info = resideo_get_attachment($photos[$i]);

                        if ($i == 0 || $fig_class == 'pxp-is-half') {
                            $thmb_photo = $p_photo_full;
                        } else {
                            $thmb_photo = $p_photo_gallery;
                        }

                        if ($has_four === true && ($i == 2 || $i == 3)) {
                            $fig_class = '';
                        }

                        $d_none = $i > 4 ? 'd-none' : ''; ?>

                        <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" class="<?php if ($i == 0) echo esc_attr($first_fig_class);
                                                                                                                        else echo esc_attr($fig_class); ?> <?php echo esc_attr($d_none); ?>">
                            <a href="<?php echo "https://img.miogest.com/" . $photos[$i]; ?>" itemprop="contentUrl" data-size="<?php echo "1280x700"; /*echo esc_attr($p_photo_full[1]); */ ?>x<?php echo esc_attr($p_photo_full[2]); ?>" class="pxp-cover" style="background-image: url(<?php echo "https://img.miogest.com/" . $photos[$i]; ?>);"></a>
                            <figcaption itemprop="caption description"><?php echo esc_html($p_photo_info['caption']); ?></figcaption>
                        </figure>
                    <?php } ?>
                </div>
                <a href="javascript:void(0);" class="pxp-sp-gallery-btn"><?php esc_html_e('View Photos', 'resideo'); ?></a>
                <div class="clearfix"></div>
            </div>
        <?php } ?>

        <?php if ($top_element == 'gallery') { ?>
            <div class="mt-100">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <h2 class="pxp-sp-top-title"><?php the_title(); ?></h2>
                            <p class="pxp-sp-top-address pxp-text-light"><?php echo esc_html($address); ?></p>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="pxp-sp-top-btns mt-2 mt-md-0">
                                <?php $wishlist = get_user_meta($user->ID, 'property_wishlist', true);
                                if (!empty($wishlist)) {
                                    if (is_user_logged_in()) {
                                        print '<input type="hidden" id="pxp-sp-top-uid" value="' . esc_attr($user->ID) . '">';

                                        if (in_array($prop_id, $wishlist) === false) {
                                            print '<a href="javascript:void(0);" class="pxp-sp-top-btn" id="pxp-sp-top-btn-save"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                        } else {
                                            print '<a href="javascript:void(0);" class="pxp-sp-top-btn pxp-is-saved" id="pxp-sp-top-btn-save"><span class="fa fa-star"></span> ' . esc_html__('Saved', 'resideo') . '</a>';
                                        }
                                    } else {
                                        print '<a href="javascript:void(0);" data-toggle="modal" data-target="#pxp-signin-modal" class="pxp-sp-top-btn"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    }
                                } else {
                                    if (is_user_logged_in()) {
                                        print '<input type="hidden" id="pxp-sp-top-uid" value="' . esc_attr($user->ID) . '">';
                                        print '<a href="javascript:void(0);" class="pxp-sp-top-btn" id="pxp-sp-top-btn-save"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    } else {
                                        print '<a href="javascript:void(0);" data-toggle="modal" data-target="#pxp-signin-modal" class="pxp-sp-top-btn"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    }
                                }
                                wp_nonce_field('wishlist_ajax_nonce', 'pxp-single-property-save-security', true);

                                if (function_exists('resideo_get_share_menu')) {
                                    resideo_get_share_menu($prop_id);
                                } ?>
                            </div>
                            <div class="clearfix d-block d-xl-none"></div>
                            <div class="pxp-sp-top-feat mt-3 mt-md-0">
                                <?php if ($beds != '') { ?>
                                    <div><?php echo esc_html($beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                                <?php }
                                if ($baths != '') { ?>
                                    <div><?php echo esc_html($baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                                <?php }
                                if ($size != '') { ?>
                                    <div><?php echo esc_html($size); ?> <span><?php echo esc_html($unit); ?></span></div>
                                <?php } ?>
                            </div>
                            <div class="pxp-sp-top-price mt-3 mt-md-0">
                            <?php if ($currency_pos == 'before') {
                                    $price_str = esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                                } else {
                                    $price_str = esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
                                } 
                                
                                if ($price_reserved) {
                                    $price_str = 'Prezzo riservato' . ' <span>' . esc_html($price_label) . '</span>';
                                }
                                
                                echo $price_str; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="container mt-100">
            <div class="row">
                <div class="col-lg-8">
                    <div class="pxp-single-property-section">
                        <h3><?php esc_html_e('Key Details', 'resideo'); ?></h3>
                        <div class="row mt-3 mt-md-4">
                            <?php if ($status) { ?>
                                <div class="col-sm-6">
                                    <div class="pxp-sp-key-details-item">
                                        <div class="pxp-sp-kd-item-label text-uppercase"><?php esc_html_e('Status', 'resideo'); ?></div>
                                        <div class="pxp-sp-kd-item-value"><?php echo esc_html($status[0]->name); ?></div>
                                    </div>
                                </div>
                                <?php }

                            //REMOVE

                            if (is_array($custom_fields_settings)) {
                                uasort($custom_fields_settings, "resideo_compare_position");

                                foreach ($custom_fields_settings as $key => $value) {
                                    $cf_label = $value['label'];
                                    if (function_exists('icl_translate')) {
                                        $cf_label = icl_translate('resideo', 'resideo_property_field_' . $value['label'], $value['label']);
                                    }

                                    $field_value = get_post_meta($prop_id, $key, true);


                                    if ($field_value != '') { ?>
                                        <div class="col-sm-6">
                                            <div class="pxp-sp-key-details-item">
                                                <?php if ($value['type'] == 'list_field') {
                                                    $list = explode(',', $value['list']); ?>
                                                    <div class="pxp-sp-kd-item-label text-uppercase"><?php echo esc_html($cf_label); ?></div>
                                                    <div class="pxp-sp-kd-item-value"><?php echo esc_html($list[$field_value]); ?></div>
                                                <?php } else { ?>
                                                    <div class="pxp-sp-kd-item-label text-uppercase"><?php echo esc_html($cf_label); ?></div>
                                                    <div class="pxp-sp-kd-item-value"><?php echo esc_html($field_value); ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                            <?php }
                                }
                            } ?>
                            <div class="col-sm-12">
                                <?php if ($type) { ?>

                                    <div class="pxp-sp-key-details-item" style="margin-top:20px; padding-top:20px; border-top: 1px solid #E2E2E2;">
                                        <div class="pxp-sp-kd-item-label text-uppercase"><?php esc_html_e('Type', 'resideo'); ?></div>
                                        <div class="pxp-sp-kd-item-value">
                                            <!-- <div class="pxp-sp-kd-item-value"><?php echo esc_html($type[0]->name); ?></div> -->
                                            <?php foreach ($type as $t) { ?>
                                                <?php echo esc_html($t->name);
                                                if (next($type)) {
                                                    echo " | ";
                                                } ?>
                                            <?php } ?>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($overview != '') { ?>
                        <div class="pxp-single-property-section mt-4 mt-md-5">
                            <h3><?php esc_html_e('Overview', 'resideo'); ?></h3>
                            <div class="mt-3 mt-md-4">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php }

                    if ($amenities_count > 0) { ?>
                        <div class="pxp-single-property-section mt-4 mt-md-5">
                            <h3><?php esc_html_e('Amenities', 'resideo'); ?></h3>
                            <div class="row mt-3 mt-md-4">
                                <?php if (is_array($amenities_settings) && count($amenities_settings) > 0) {
                                    uasort($amenities_settings, "resideo_compare_position");

                                    foreach ($amenities_settings as $key => $value) {
                                        $am_label = $value['label'];
                                        if (function_exists('icl_translate')) {
                                            $am_label = icl_translate('resideo', 'resideo_property_amenity_' . $value['label'], $value['label']);
                                        }

                                        if (get_post_meta($prop_id, $key, true) == 1) { ?>
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="pxp-sp-amenities-item"><span class="<?php echo esc_attr($value['icon']); ?>"></span> <?php echo esc_html($am_label); ?></div>
                                            </div>
                                <?php }
                                    }
                                } ?>
                            </div>
                        </div>
                        <?php }

                    if ($video != '') {
                        if (function_exists('resideo_get_property_video')) { ?>
                            <div class="pxp-single-property-section mt-4 mt-md-5">
                                <h3><?php esc_html_e('Video', 'resideo'); ?></h3>
                                <div class="mt-3 mt-md-4">
                                    <?php resideo_get_property_video($video); ?>
                                </div>
                            </div>
                        <?php }
                    }

                    if ($virtual_tour != '') {
                        if (function_exists('resideo_get_property_virtual_tour')) { ?>
                            <div class="pxp-single-property-section mt-4 mt-md-5">
                                <h3><?php esc_html_e('Virtual Tour', 'resideo'); ?></h3>
                                <div class="mt-3 mt-md-4">
                                    <?php resideo_get_property_virtual_tour($virtual_tour); ?>
                                </div>
                            </div>
                        <?php }
                    }

                    $floor_plans_list = array();

                    if ($floor_plans != '') {
                        $floor_plans_data = json_decode(urldecode($floor_plans));

                        if (isset($floor_plans_data)) {
                            $floor_plans_list = $floor_plans_data->plans;
                        }
                    }

                    if (count($floor_plans_list) > 0) { ?>
                        <div class="pxp-single-property-section mt-4 mt-md-5">
                            <h3><?php esc_html_e('Floor Plans', 'resideo'); ?></h3>
                            <div class="accordion" id="pxpFloorPlans">
                                <?php foreach ($floor_plans_list as $floor_plan) {
                                    $floor_plan_image = wp_get_attachment_image_src($floor_plan->image, 'pxp-full'); ?>

                                    <div class="pxp-sp-floor-plans-item">
                                        <div class="pxp-sp-floor-plans-item-header" id="pxpSPFloorPlansItemHeader<?php echo esc_attr($floor_plan->image); ?>">
                                            <div class="pxp-sp-floor-plans-item-trigger collapsed" data-toggle="collapse" data-target="#pxpSPFloorPlansCollapse<?php echo esc_attr($floor_plan->image); ?>" aria-expanded="true" aria-controls="pxpSPFloorPlansCollapse<?php echo esc_attr($floor_plan->image); ?>">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="pxp-sp-floor-plans-item-title"><span class="fa fa-angle-down pxp-is-plus mr-3"></span><span class="fa fa-angle-up pxp-is-minus mr-3"></span><?php echo esc_html($floor_plan->title); ?></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="pxp-sp-floor-plans-item-info">
                                                            <?php if ($floor_plan->beds != '') { ?>
                                                                <div class="d-inline-block mr-2"><?php echo esc_html($floor_plan->beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                                                            <?php } ?>
                                                            <?php if ($floor_plan->baths != '') { ?>
                                                                <div class="d-inline-block mr-2"><?php echo esc_html($floor_plan->baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                                                            <?php } ?>
                                                            <?php if ($floor_plan->size != '') { ?>
                                                                <div class="d-inline-block"><?php echo esc_html($floor_plan->size); ?> <span><?php echo esc_html($unit); ?></span></div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="pxpSPFloorPlansCollapse<?php echo esc_attr($floor_plan->image); ?>" class="collapse" aria-labelledby="pxpSPFloorPlansItemHeader<?php echo esc_attr($floor_plan->image); ?>" data-parent="#pxpFloorPlans">
                                            <?php if ($floor_plan_image != '') { ?>
                                                <a href="<?php echo esc_url($floor_plan_image[0]); ?>" target="_blank">
                                                    <img class="pxp-sp-floor-plans-item-image" src="<?php echo esc_url($floor_plan_image[0]); ?>" alt="<?php echo esc_attr($floor_plan->title); ?>">
                                                </a>
                                            <?php } ?>
                                            <p class="mt-3"><?php echo esc_html($floor_plan->description); ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php }

                    if (wp_script_is('gmaps', 'enqueued')) { ?>
                        <div class="pxp-single-property-section mt-4 mt-md-5">
                            <h3><?php esc_html_e('Explore the Area', 'resideo'); ?></h3>
                            <div class="pxp-sp-pois-nav mt-3 mt-md-4">
                                <div class="pxp-sp-pois-nav-transportation text-uppercase"><?php esc_html_e('Transportation', 'resideo'); ?></div>
                                <div class="pxp-sp-pois-nav-restaurants text-uppercase"><?php esc_html_e('Restaurants', 'resideo'); ?></div>
                                <div class="pxp-sp-pois-nav-shopping text-uppercase"><?php esc_html_e('Shopping', 'resideo'); ?></div>
                                <div class="pxp-sp-pois-nav-cafes text-uppercase"><?php esc_html_e('Cafes & Bars', 'resideo'); ?></div>
                                <div class="pxp-sp-pois-nav-arts text-uppercase"><?php esc_html_e('Arts & Entertainment', 'resideo'); ?></div>
                                <div class="pxp-sp-pois-nav-fitness text-uppercase"><?php esc_html_e('Fitness', 'resideo'); ?></div>
                            </div>
                            <div id="pxp-sp-map" class="mt-3"></div>
                        </div>
                    <?php }

                    if ($calculator == '1') { ?>
                        <div class="pxp-single-property-section mt-4 mt-md-5">
                            <h3><?php esc_html_e('Payment Calculator', 'resideo'); ?></h3>
                            <div class="pxp-calculator-view mt-3 mt-md-4">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-4 align-self-center">
                                        <div class="pxp-calculator-chart-container">
                                            <canvas id="pxp-calculator-chart"></canvas>
                                            <div class="pxp-calculator-chart-result">
                                                <div class="pxp-calculator-chart-result-sum"></div>
                                                <div class="pxp-calculator-chart-result-label"><?php esc_html_e('per month', 'resideo'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-8 align-self-center mt-3 mt-lg-0">
                                        <div class="pxp-calculator-data">
                                            <div class="row justify-content-between">
                                                <div class="col-8">
                                                    <div class="pxp-calculator-data-label"><span class="fa fa-minus"></span><?php esc_html_e('Principal and Interest', 'resideo'); ?></div>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <div class="pxp-calculator-data-sum" id="pxp-calculator-data-pi"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pxp-calculator-data">
                                            <div class="row justify-content-between">
                                                <div class="col-8">
                                                    <div class="pxp-calculator-data-label"><span class="fa fa-minus"></span><?php esc_html_e('Property Taxes', 'resideo'); ?></div>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <div class="pxp-calculator-data-sum" id="pxp-calculator-data-pt"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pxp-calculator-data">
                                            <div class="row justify-content-between">
                                                <div class="col-8">
                                                    <div class="pxp-calculator-data-label"><span class="fa fa-minus"></span><?php esc_html_e('HOA Dues', 'resideo'); ?></div>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <div class="pxp-calculator-data-sum" id="pxp-calculator-data-hd"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pxp-calculator-form mt-3 mt-md-4">
                                <?php if ($currency_pos == 'before') {
                                    $taxes_value = $currency . money_format('%!.0i', $taxes);
                                    $hoa_dues_value = $currency . money_format('%!.0i', $hoa_dues);
                                    $price_value = $currency . $price;
                                } else {
                                    $taxes_value = money_format('%!.0i', $taxes) . $currency;
                                    $hoa_dues_value = money_format('%!.0i', $hoa_dues) . $currency;
                                    $price_value = $price . $currency;
                                } ?>

                                <input type="hidden" id="pxp-calculator-form-property-taxes" value="<?php echo esc_attr($taxes_value); ?>">
                                <input type="hidden" id="pxp-calculator-form-hoa-dues" value="<?php echo esc_attr($hoa_dues_value); ?>">

                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="pxp-calculator-form-term"><?php esc_html_e('Term', 'resideo'); ?></label>
                                            <select class="custom-select" id="pxp-calculator-form-term">
                                                <option value="30">30 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                <option value="25">25 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                <option value="20">20 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                <option value="15">15 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                <option value="10">10 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="pxp-calculator-form-interest"><?php esc_html_e('Interest', 'resideo'); ?></label>
                                            <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-interest" data-type="percent" value="3.51%">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="pxp-calculator-form-price"><?php esc_html_e('Home Price', 'resideo'); ?></label>
                                            <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-price" data-type="currency" value="<?php echo esc_attr($price_value); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="row">
                                            <div class="col-7 col-sm-7 col-md-8">
                                                <div class="form-group">
                                                    <label for="pxp-calculator-form-down-price"><?php esc_html_e('Down Payment', 'resideo'); ?></label>
                                                    <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-down-price" data-type="currency" value="">
                                                </div>
                                            </div>
                                            <div class="col-5 col-sm-5 col-md-4">
                                                <div class="form-group">
                                                    <label for="pxp-calculator-form-down-percentage">&nbsp;</label>
                                                    <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-down-percentage" data-type="percent" value="10%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>



                <div class="col-lg-4">
                    <?php if ($agent_id != '') {
                        $agent_avatar       = get_post_meta($agent_id, 'agent_avatar', true);
                        $agent_avatar_photo = wp_get_attachment_image_src($agent_avatar, 'pxp-thmb');

                        if ($agent_avatar_photo != '') {
                            $a_photo = $agent_avatar_photo[0];
                        } else {
                            $a_photo = RESIDEO_LOCATION . '/images/avatar-default.png';
                        }

                        $show_rating = isset($general_settings['resideo_agents_rating_field']) ? $general_settings['resideo_agents_rating_field'] : '';
                        $hide_phone = isset($appearance_settings['resideo_hide_agents_phone_field']) ? $appearance_settings['resideo_hide_agents_phone_field'] : '';

                        $agent_email = get_post_meta($agent_id, 'agent_email', true);
                        $agent_phone = get_post_meta($agent_id, 'agent_phone', true); ?>

                        <div class="pxp-single-property-section pxp-sp-agent-section mt-4 mt-md-5 mt-lg-0">
                            <h3><?php esc_html_e('Listed By', 'resideo'); ?></h3>
                            <div class="pxp-sp-agent mt-3 mt-md-4">
                                <a href="<?php echo esc_url(get_permalink($agent_id)); ?>" class="pxp-sp-agent-fig pxp-cover rounded-lg" style="background-image: url(<?php echo esc_attr($a_photo); ?>);"></a>
                                <div class="pxp-sp-agent-info">
                                    <div class="pxp-sp-agent-info-name"><a href="<?php echo esc_url(get_permalink($agent_id)); ?>"><?php echo esc_attr($agent->post_title); ?></a></div>
                                    <?php if ($show_rating != '') {
                                        print resideo_display_agent_rating(resideo_get_agent_ratings($agent_id), false, 'pxp-sp-agent-info-rating');
                                    }

                                    if ($agent_email != '') { ?>
                                        <div class="pxp-sp-agent-info-email"><a href="mailto:<?php echo esc_attr($agent_email); ?>"><?php echo esc_html($agent_email); ?></a></div>
                                        <?php }

                                    if ($agent_phone != '') {
                                        if ($hide_phone != '') { ?>
                                            <div class="pxp-sp-agent-info-show-phone" data-phone="<?php echo esc_attr($agent_phone); ?>"><span class="fa fa-phone"></span> <span class="pxp-is-number"><?php esc_html_e('Show phone number', 'resideo'); ?></span></div>
                                        <?php } else { ?>
                                            <div class="pxp-sp-agent-info-phone"><span class="fa fa-phone"></span> <?php echo esc_html($agent_phone); ?></div>
                                    <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                                <?php if (function_exists('resideo_get_contact_agent_modal')) {
                                    $modal_info                   = array();
                                    $modal_info['link']           = get_permalink($prop_id);
                                    $modal_info['title']          = get_the_title();
                                    $modal_info['agent_email']    = $agent_email;
                                    $modal_info['agent_id']       = $agent_id;
                                    $modal_info['agent']          = $agent->post_title;
                                    $modal_info['user_id']        = '';
                                    $modal_info['user_email']     = '';
                                    $modal_info['user_firstname'] = '';
                                    $modal_info['user_lastname']  = '';

                                    if (is_user_logged_in()) {
                                        $user_meta                    = get_user_meta($user->ID);
                                        $modal_info['user_id']        = $user->ID;
                                        $modal_info['user_email']     = $user->user_email;
                                        $user_firstname               = $user_meta['first_name'];
                                        $user_lastname                = $user_meta['last_name'];
                                        $modal_info['user_firstname'] = $user_firstname[0];
                                        $modal_info['user_lastname']  = $user_lastname[0];
                                    }

                                    $cta_is_sticky = isset($appearance_settings['resideo_sticky_agent_cta_field']) ? $appearance_settings['resideo_sticky_agent_cta_field'] : false;
                                    $cta_sticky_class = $cta_is_sticky == '1' ? 'pxp-is-sticky' : ''; ?>

                                    <div class="pxp-sp-agent-btns mt-3 mt-md-4">
                                        <a href="#pxp-contact-agent" class="pxp-sp-agent-btn-main <?php echo esc_attr($cta_sticky_class); ?>" data-toggle="modal" data-target="#pxp-contact-agent"><span class="fa fa-envelope-o"></span><?php esc_html_e('Contact Agent', 'resideo'); ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>


        <?php $show_similar = isset($appearance_settings['resideo_similar_field']) ? $appearance_settings['resideo_similar_field'] : false;

        if ($show_similar) {
            if (function_exists('resideo_get_similar_properties')) {
                resideo_get_similar_properties();
            }
        } ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-1"></div>
                <div class="col-sm-12 col-lg-10">
                    <?php if (comments_open() || get_comments_number()) {
                        comments_template();
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>

<?php if (isset($modal_info)) {
        resideo_get_contact_agent_modal($modal_info);
    }

    if (isset($report_modal_info)) {
        resideo_get_report_property_modal($report_modal_info);
    }

endwhile; ?>

<?php get_footer(); ?>