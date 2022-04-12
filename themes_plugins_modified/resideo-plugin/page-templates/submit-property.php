<?php
/*
Template Name: Submit Property
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

$current_user = wp_get_current_user();

if (!is_user_logged_in() || resideo_check_user_agent($current_user->ID) === false) {
    wp_redirect(home_url());
}

// Check if property belongs to the logged in agent/owner
$agent_id = resideo_get_agent_by_userid($current_user->ID);

if (isset($_GET['edit_id'])) {
    $pr_id   = sanitize_text_field($_GET['edit_id']);
    $pr_agent_id = get_post_meta($pr_id, 'property_agent', true);

    if ($agent_id != $pr_agent_id) {
        wp_redirect(home_url());
    }
}

global $post;
get_header();

$general_settings = get_option('resideo_general_settings');
$fields_settings  = get_option('resideo_prop_fields_settings');

$p_overview      = isset($fields_settings['resideo_p_overview_field']) ? $fields_settings['resideo_p_overview_field'] : '';
$p_address       = isset($fields_settings['resideo_p_address_field']) ? $fields_settings['resideo_p_address_field'] : '';
$p_coordinates   = isset($fields_settings['resideo_p_coordinates_field']) ? $fields_settings['resideo_p_coordinates_field'] : '';
$p_streetno      = isset($fields_settings['resideo_p_streetno_field']) ? $fields_settings['resideo_p_streetno_field'] : '';
$p_street        = isset($fields_settings['resideo_p_street_field']) ? $fields_settings['resideo_p_street_field'] : '';
$p_neighborhood  = isset($fields_settings['resideo_p_neighborhood_field']) ? $fields_settings['resideo_p_neighborhood_field'] : '';
$p_city          = isset($fields_settings['resideo_p_city_field']) ? $fields_settings['resideo_p_city_field'] : '';
$p_state         = isset($fields_settings['resideo_p_state_field']) ? $fields_settings['resideo_p_state_field'] : '';
$p_zip           = isset($fields_settings['resideo_p_zip_field']) ? $fields_settings['resideo_p_zip_field'] : '';
$p_price         = isset($fields_settings['resideo_p_price_field']) ? $fields_settings['resideo_p_price_field'] : '';
$p_size          = isset($fields_settings['resideo_p_size_field']) ? $fields_settings['resideo_p_size_field'] : '';
$p_beds          = isset($fields_settings['resideo_p_beds_field']) ? $fields_settings['resideo_p_beds_field'] : '';
$p_baths         = isset($fields_settings['resideo_p_baths_field']) ? $fields_settings['resideo_p_baths_field'] : '';
$p_type          = isset($fields_settings['resideo_p_type_field']) ? $fields_settings['resideo_p_type_field'] : '';
$p_status        = isset($fields_settings['resideo_p_status_field']) ? $fields_settings['resideo_p_status_field'] : '';
$p_calculator    = isset($fields_settings['resideo_p_calculator_field']) ? $fields_settings['resideo_p_calculator_field'] : '';
$p_taxes         = isset($fields_settings['resideo_p_taxes_field']) ? $fields_settings['resideo_p_taxes_field'] : '';
$p_hoa           = isset($fields_settings['resideo_p_hoa_field']) ? $fields_settings['resideo_p_hoa_field'] : '';
$max_files       = (isset($general_settings['resideo_max_files_field']) && $general_settings['resideo_max_files_field'] != '') ? $general_settings['resideo_max_files_field'] : 10;
$featured_option = isset($general_settings['resideo_featured_property_field']) ? $general_settings['resideo_featured_property_field'] : '';

$edit_id      = isset($_GET['edit_id']) ? sanitize_text_field($_GET['edit_id']) : '';
$edit_link    = ($edit_id != '') ? get_permalink($edit_id) : '';
$display_form = true;

$currency = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
?>

<div class="pxp-content pxp-submit-property">
    <?php while (have_posts()) : the_post(); ?>
        <div class="pxp-content-wrapper mt-100">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <h1 class="pxp-page-header">
                            <?php if ($edit_id != '') {
                                esc_html_e('Edit Property', 'resideo');
                            } else {
                                echo get_the_title();
                            } ?>
                        </h1>
                    </div>
                </div>

                <!-- Membership settings -->
                <?php $membership_settings = get_option('resideo_membership_settings');
                $pay_type                  = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';
                $pay_currency              = isset($membership_settings['resideo_payment_currency_field']) ? $membership_settings['resideo_payment_currency_field'] : '';
                $standard_price            = isset($membership_settings['resideo_submission_price_field']) ? $membership_settings['resideo_submission_price_field'] : __('Free', 'resideo');
                $featured_price            = isset($membership_settings['resideo_featured_price_field']) ? $membership_settings['resideo_featured_price_field'] : __('Free', 'resideo');
                $standard_unlim            = isset($membership_settings['resideo_free_submissions_unlim_field']) ? $membership_settings['resideo_free_submissions_unlim_field'] : '';
                $agent_payment             = get_post_meta($agent_id, 'agent_payment', true);

                if ($pay_type == 'listing' && $agent_payment != '1') { ?>
                    <div class="row mt-4 mt-md-5">
                        <div class="col-sm-12 col-md-6">
                            <div class="pxp-submit-property-price-card rounded-lg">
                                <img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/standard-property-icon.svg'); ?>" alt="<?php esc_attr_e('Standard Property', 'resideo'); ?>">
                                <div class="pxp-submit-property-price-card-details">
                                    <h3><?php esc_html_e('Standard Property', 'resideo'); ?></h3>
                                    <?php if ($standard_unlim != '' && $standard_unlim == 1) { ?>
                                        <div class="pxp-submit-property-price-card-free"><?php esc_html_e('Free included', 'resideo'); ?>: <b><?php esc_html_e('unlimited', 'resideo'); ?></b></div>
                                    <?php } else { 
                                        $standard_free_left = get_post_meta($agent_id, 'agent_free_listings', true); ?>

                                        <div class="pxp-submit-property-price-card-free"><?php esc_html_e('Free submissions left', 'resideo'); ?>: <b><?php if ($standard_free_left == '' || $standard_free_left <= 0){ echo '0'; } else { echo esc_html($standard_free_left); } ?></b></div>
                                        <input type="hidden" name="standard_free_left" id="standard_free_left" value="<?php echo esc_html($standard_free_left); ?>">
                                    <?php } ?>
                                </div>
                                <div class="pxp-submit-property-price-card-price">
                                    <?php if ($standard_unlim != '' && $standard_unlim == 1) {
                                        esc_html_e('Free', 'resideo');
                                    } else { 
                                        echo esc_html($standard_price); ?> <span><?php echo esc_html($pay_currency); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="pxp-submit-property-price-card rounded-lg">
                                <img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/featured-property-icon.svg'); ?>" alt="<?php esc_attr_e('Featured Property', 'resideo'); ?>">
                                <div class="pxp-submit-property-price-card-details">
                                    <h3><?php esc_html_e('Featured Property', 'resideo'); ?></h3>
                                    <?php $featured_free_left = get_post_meta($agent_id, 'agent_free_featured_listings', true); ?>
                                    <div class="pxp-submit-property-price-card-free"><?php esc_html_e('Free submissions left', 'resideo'); ?>: <b><?php if ($featured_free_left == '' || $featured_free_left <= 0){ echo '0'; } else { echo esc_html($featured_free_left); } ?></b></div>
                                    <input type="hidden" name="featured_free_left" id="featured_free_left" value="<?php echo esc_html($featured_free_left); ?>">
                                </div>
                                <div class="pxp-submit-property-price-card-price">
                                    + <?php echo esc_html($featured_price); ?> <span><?php echo esc_html($pay_currency); ?></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                <?php } 

                if ($pay_type == 'membership' && $agent_payment != '1') {
                    $plan_id         = get_post_meta($agent_id, 'agent_plan', true);
                    $plan_listings   = get_post_meta($agent_id, 'agent_plan_listings', true);
                    $plan_unlimited  = get_post_meta($agent_id, 'agent_plan_unlimited', true);
                    $plan_activation = strtotime(get_post_meta($agent_id, 'agent_plan_activation', true));
                    $plan_time_unit  = get_post_meta($plan_id, 'membership_billing_time_unit', true);
                    $plan_period     = get_post_meta($plan_id, 'membership_period', true);
    
                    $seconds = 0;
    
                    switch ($plan_time_unit) {
                        case 'day':
                            $seconds = 60 * 60 * 24;
                        break;
                        case 'week':
                            $seconds = 60 * 60 * 24 * 7;
                        break;
                        case 'month':
                            $seconds = 60 * 60 * 24 * 30;
                        break;
                        case 'year':
                            $seconds = 60 * 60 * 24 * 365;
                        break;
                    }
    
                    $time_frame      = $seconds * $plan_period;
                    $expiration_date = $plan_activation + $time_frame;
                    $expiration_date = date('Y-m-d', $expiration_date);
                    $today           = getdate();
    
                    $no_listings = false;
                    $expired     = false;
    
                    if (intval($plan_listings) <= 0) {
                        $display_form = false;
                        $no_listings  = true;
                    }
                    if ($plan_unlimited == '1') {
                        $display_form = true;
                        $no_listings  = false;
                    }
                    if ($today[0] > strtotime($expiration_date)) {
                        $display_form = false;
                        $expired      = true;
                    }
                    if ($edit_id != '') {
                        $display_form = true;
                        $expired      = false;
                        $no_listings  = false;
                    }
                }

                if ($display_form == true) { ?>
                    <form id="pxp-submit-property-form" name="pxp-submit-property-form" method="post" action="" enctype="multipart/form-data">
                        <?php wp_nonce_field('submitproperty_ajax_nonce', 'security-submit-property', true); ?>
                        <input type="hidden" id="current_user" name="current_user" value="<?php echo esc_attr($current_user->ID); ?>">
                        <input type="hidden" id="new_id" name="new_id" value="<?php echo esc_attr($edit_id); ?>">

                        <?php $edit_lat = ($edit_id != '') ? get_post_meta($edit_id, 'property_lat', true) : '';
                        $edit_lng = ($edit_id != '') ? get_post_meta($edit_id, 'property_lng', true) : ''; ?>

                        <input type="hidden" id="new_lat_h" name="new_lat_h" value="<?php echo esc_attr($edit_lat); ?>">
                        <input type="hidden" id="new_lng_h" name="new_lng_h" value="<?php echo esc_attr($edit_lng); ?>">

                        <div class="row mt-4 mt-md-5">
                            <div class="col-sm-12 col-lg-8">
                                <h3><?php esc_html_e('Basic Information', 'resideo'); ?></h3>

                                <div class="mt-3 mt-md-4">

                                    <!-- TITLE FIELD -->

                                    <?php $edit_title = ($edit_id != '') ? get_the_title($edit_id) : ''; ?>
                                    <div class="form-group">
                                        <label for="new_title"><?php esc_html_e('Title', 'resideo'); ?> <span class="text-red">*</span></label>
                                        <input type="text" class="form-control" id="new_title" name="new_title" placeholder="<?php esc_html_e('Enter property title', 'resideo'); ?>" value="<?php echo esc_attr($edit_title); ?>">
                                    </div>

                                    <!-- OVERVIEW FIELD -->

                                    <?php if ($p_overview == 'enabled') {
                                        $p_overview_req = isset($fields_settings['resideo_p_overview_r_field']) ? $fields_settings['resideo_p_overview_r_field'] : '';

                                        if ($edit_id != '') {
                                            $prop = get_post($edit_id);
                                            $edit_overview = apply_filters('the_content', $prop->post_content);
                                        } else {
                                            $edit_overview = '';
                                        } ?>
                                        <div class="form-group pxp-is-tinymce">
                                            <label>
                                                <?php esc_html_e('Overview', 'resideo');
                                                if ($p_overview_req == 'required') { ?>
                                                    <span class="text-red">*</span>
                                                <?php } ?>
                                            </label>
                                            <?php $overview_settings = array(
                                                'teeny'         => true,
                                                'media_buttons' => false,
                                                'editor_height' => 240,
                                                'editor_css'    => '
                                                    <style>
                                                        .wp-editor-tabs {
                                                            float: right;
                                                        }
                                                        .wp-switch-editor {
                                                            background: transparent;
                                                            color: rgba(51, 51, 51, 0.7);
                                                            border: 0 none;
                                                            border-bottom: 2px solid transparent;
                                                            padding: 5px 0;
                                                            margin: 0 0 0 20px;
                                                            font-weight: 700;
                                                            font-size: .8rem;
                                                            text-transform: uppercase;
                                                            -webkit-transition: all .2s ease-in-out;
                                                            -o-transition: all .2s ease-in-out;
                                                            transition: all .2s ease-in-out;
                                                        }
                                                        .wp-switch-editor:active {
                                                            background-color: transparent;
                                                        }
                                                        .html-active .switch-html, 
                                                        .tmce-active .switch-tmce {
                                                            background: transparent;
                                                            color: #333;
                                                            border-bottom: 2px solid #333;
                                                            border-radius: 0;
                                                            margin: 0 0 0 20px;
                                                        }
                                                        div.mce-panel {
                                                            background: #fff;
                                                        }
                                                        .pxp-dark-mode div.mce-panel {
                                                            background: #F7F7F7;
                                                        }
                                                        div.mce-edit-area {
                                                            box-shadow: none;
                                                            overflow: hidden;
                                                            border: 1px solid #E2E2E2 !important;
                                                            border-radius: .25rem;
                                                        }
                                                        div.mce-fullscreen div.mce-edit-area {
                                                            box-shadow: none;
                                                            border-radius: 0;
                                                        }
                                                        div.mce-fullscreen div.mce-panel {
                                                            background: #fff;
                                                        }
                                                        div.mce-toolbar-grp {
                                                            background: transparent;
                                                            border-bottom: 0 none;
                                                        }
                                                        div.mce-fullscreen div.mce-toolbar-grp {
                                                            background: #fff;
                                                            border-bottom: 1px solid #ddd;
                                                        }
                                                        .wp-editor-container {
                                                            border: 0 none;
                                                        }
                                                        div.mce-toolbar-grp > div {
                                                            padding: 5px 0;
                                                        }
                                                        div.mce-fullscreen div.mce-toolbar-grp > div {
                                                            padding: 3px;
                                                        }
                                                        div.mce-statusbar {
                                                            border-top: 0 none;
                                                        }
                                                        .quicktags-toolbar {
                                                            padding: 5px 0;
                                                            border-bottom: 0 none;
                                                            background: transparent;
                                                        }
                                                        .wp-editor-container textarea.wp-editor-area {
                                                            box-shadow: none;
                                                            border: 1px solid #E2E2E2;
                                                            border-radius: .25rem;
                                                            background: #fff;
                                                            
                                                        }
                                                        .mce-top-part::before {
                                                            content: none;
                                                        }
                                                        .wp-core-ui #wp-new_overview-editor-container .button {
                                                            color: #555d66;
                                                            border-color: transparent;
                                                            background: transparent;
                                                            font-weight: 700;
                                                        }
                                                        .wp-core-ui #wp-new_overview-editor-container .button#qt_new_overview_strong {
                                                            font-weight: 900;
                                                        }
                                                        .wp-core-ui #wp-new_overview-editor-container .button:hover,
                                                        .wp-core-ui #wp-new_overview-editor-container .button:focus {
                                                            background: #fafafa;
                                                            border-color: #555d66;
                                                            color: #333;
                                                            box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0,0,0,.08);
                                                            outline: 0;
                                                        }
                                                    </style>
                                                ',
                                            );
                                            wp_editor($edit_overview, 'new_overview', $overview_settings); ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <?php $custom_fields_settings = get_option('resideo_fields_settings');

                                if ($p_type == 'enabled' || $p_status == 'enabled' || (is_array($custom_fields_settings) && count($custom_fields_settings) > 0)) { ?>
                                    <h3 class="mt-4 mt-md-5"><?php esc_html_e('Key Details', 'resideo'); ?></h3>
                                <?php } ?>

                                <div class="row mt-3 mt-md-4">

                                    <!-- TYPE FIELD -->

                                    <?php if ($p_type == 'enabled') {
                                        $type_tax = array( 
                                            'property_type'
                                        );
                                        $type_args = array(
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false
                                        ); 
                                        $type_terms = get_terms($type_tax, $type_args);

                                        $p_type_req   = isset($fields_settings['resideo_p_type_r_field']) ? $fields_settings['resideo_p_type_r_field'] : '';
                                        $edit_type    = ($edit_id != '') ? wp_get_post_terms($edit_id, 'property_type', true) : '';
                                        $edit_type_id = ($edit_type != '' && $edit_type) ? $edit_type[0]->term_id : ''; ?>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="new_type">
                                                    <?php esc_html_e('Type', 'resideo');
                                                    if ($p_type_req == 'required') { ?>
                                                        <span class="text-red">*</span>
                                                    <?php } ?>
                                                </label>
                                                <select class="custom-select" id="new_type" name="new_type">
                                                    <option value="0"><?php esc_html_e('Select type', 'resideo'); ?></option>
                                                    <?php foreach ($type_terms as $type_term) { ?>
                                                        <option value="<?php echo esc_attr($type_term->term_id); ?>" <?php selected($edit_type_id, $type_term->term_id); ?>><?php echo esc_html($type_term->name); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- STATUS FIELD -->

                                    <?php if ($p_status == 'enabled') { 
                                        $status_tax = array( 
                                            'property_status'
                                        );
                                        $status_args = array(
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false
                                        ); 
                                        $status_terms = get_terms($status_tax, $status_args);

                                        $p_status_req   = isset($fields_settings['resideo_p_status_r_field']) ? $fields_settings['resideo_p_status_r_field'] : '';
                                        $edit_status    = ($edit_id != '') ? wp_get_post_terms($edit_id, 'property_status', true) : '';
                                        $edit_status_id = ($edit_status != '' && $edit_status) ? $edit_status[0]->term_id : ''; ?>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="new_type">
                                                    <?php esc_html_e('Status', 'resideo');
                                                    if ($p_status_req == 'required') { ?>
                                                        <span class="text-red">*</span>
                                                    <?php } ?>
                                                </label>
                                                <select class="custom-select" id="new_status" name="new_status">
                                                    <option value="0"><?php esc_html_e('Select status', 'resideo'); ?></option>
                                                    <?php foreach ($status_terms as $status_term) { ?>
                                                        <option value="<?php echo esc_attr($status_term->term_id); ?>" <?php selected($edit_status_id, $status_term->term_id); ?>><?php echo esc_html($status_term->name); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- CUSTOM FIELDS -->

                                    <?php if (is_array($custom_fields_settings) && count($custom_fields_settings) > 0) { 
                                        uasort($custom_fields_settings, "resideo_compare_position");
                                        
                                        foreach ($custom_fields_settings as $key => $value) { 
                                            $edit_value  = ($edit_id != '') ? get_post_meta($edit_id, $key, true) : ''; ?>

                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value['label']); ?>
                                                        <?php if ($value['mandatory'] == 'yes') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <?php if ($value['type'] == 'date_field') { ?>
                                                        <input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control date-picker pxp-js-custom-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" value="<?php echo esc_attr($edit_value); ?>" placeholder="<?php esc_html_e('Enter', 'resideo'); echo ' ' . esc_attr($value['label']); ?>" />
                                                    <?php } else if ($value['type'] == 'list_field') { 
                                                        $list = explode(',', $value['list']); ?>
                                                        <select name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="custom-select pxp-js-custom-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>">
                                                            <option value=""><?php esc_html_e('Select', 'resideo'); ?></option>
                                                            <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                                <option value="<?php echo esc_attr($i); ?>" <?php selected($edit_value, $i); ?>><?php echo esc_html($list[$i]); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-custom-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" value="<?php echo esc_attr($edit_value); ?>" placeholder="<?php esc_html_e('Enter', 'resideo'); echo ' ' . esc_attr($value['label']); ?>" />
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>

                                <!-- AMENITIES FIELDS -->

                                <?php $amenities_settings = get_option('resideo_amenities_settings');

                                if (is_array($amenities_settings) && count($amenities_settings) > 0) { 
                                    uasort($amenities_settings, "resideo_compare_position"); ?>

                                    <h3 class="mt-4 mt-md-5"><?php esc_html_e('Amenities', 'resideo'); ?></h3>

                                    <div class="row mt-3 mt-md-4" id="new_amenities">
                                        <?php foreach ($amenities_settings as $key => $value) {
                                            $am_label = $value['label'];
                                            $edit_am_value = ($edit_id != '') ? get_post_meta($edit_id, $key, true) : '';

                                            if (function_exists('icl_translate')) {
                                                $am_label = icl_translate('resideo', 'resideo_property_amenity_' . $value['label'], $value['label']);
                                            } ?>

                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group form-check">
                                                    <input type="checkbox" class="form-check-input" id="<?php echo esc_attr($key); ?>" value="1" <?php checked($edit_am_value, '1'); ?>>
                                                    <label class="form-check-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_html($am_label);?></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <!-- VIDEO SECTION -->

                                <?php $edit_video = ($edit_id != '') ? get_post_meta($edit_id, 'property_video', true) : ''; ?>

                                <h3 class="mt-4 mt-md-5"><?php esc_html_e('Video', 'resideo'); ?></h3>

                                <div class="row mt-3 mt-md-4">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group mb-0">
                                            <label for="new_video"><?php esc_html_e('YouTube video ID', 'resideo'); ?></label>
                                            <input type="text" name="new_video" id="new_video" class="form-control" placeholder="<?php esc_attr_e('Enter the YouTube video ID', 'resideo'); ?>" value="<?php echo esc_attr($edit_video); ?>">
                                            <small class="form-text text-muted">E.g. https://www.youtube.com/watch?<b>v=Ur1Nrz23sSI</b></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- VIRTUAL TOUR SECTION -->

                                <?php $edit_virtual_tour = ($edit_id != '') ? get_post_meta($edit_id, 'property_virtual_tour', true) : ''; 
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
                                ); ?>

                                <h3 class="mt-4 mt-md-5"><?php esc_html_e('Virtual Tour', 'resideo'); ?></h3>

                                <div class="mt-3 mt-md-4">
                                    <div class="form-group mb-0">
                                        <label for="new_video"><?php esc_html_e('Virtual Tour Embed Code', 'resideo'); ?></label>
                                        <textarea type="text" name="new_virtual_tour" id="new_virtual_tour" class="form-control" placeholder="<?php esc_attr_e('Paste your virtual tour embed code here...', 'resideo'); ?>"><?php echo wp_kses($edit_virtual_tour, $virtual_tour_allowed_html); ?></textarea>
                                    </div>
                                </div>

                                <!-- PHOTO GALLERY SECTION -->

                                <?php $edit_gallery = ($edit_id != '') ? get_post_meta($edit_id, 'property_gallery', true) : ''; ?>

                                <h3 class="mt-4 mt-md-5"><?php esc_html_e('Photo Gallery', 'resideo'); ?></h3>

                                <div class="mt-3 mt-md-4">
                                    <div class="position-relative">
                                        <div id="aaiu-upload-container-gallery">
                                            <div class="pxp-submit-property-gallery">
                                                <?php $gallery_ids = explode(',', $edit_gallery);

                                                foreach ($gallery_ids as $photo_id) {
                                                    if ($photo_id != '') {
                                                        $photo_src = wp_get_attachment_image_src($photo_id, 'pxp-agent');
                                                        $photo_info = resideo_get_attachment($photo_id); ?>

                                                        <div class="pxp-submit-property-gallery-photo has-animation" style="background-image: url(<?php echo esc_url($photo_src[0]); ?>);" data-id="<?php echo esc_attr($photo_id); ?>">
                                                            <button class="pxp-submit-property-gallery-delete-photo"><span class="fa fa-trash-o"></span></button>
                                                        </div>
                                                    <?php } 
                                                } ?>
                                            </div>
                                            <div class="pxp-submit-property-upload-gallery-status"></div>
                                            <div class="clearfix"></div>
                                            <a role="button" id="aaiu-uploader-gallery" class="pxp-browser-photos-btn"><span class="fa fa-camera"></span>&nbsp;&nbsp;&nbsp;<?php esc_html_e('Upload Photo', 'resideo'); ?></a>
                                            <input type="hidden" name="new_gallery" id="new_gallery" value="<?php echo esc_attr($edit_gallery); ?>">
                                        </div>
                                    </div>
                                    <p class="pxp-help-block"><?php esc_html_e('Maximum number of files:', 'resideo'); ?> <strong><?php echo esc_html($max_files); ?></strong></p>
                                </div>

                                <!-- FLOOR PLANS SECTION -->

                                <?php $edit_floor_plans = ($edit_id != '') ? get_post_meta($edit_id, 'property_floor_plans', true) : ''; 

                                $floor_plans_list = array();

                                if ($edit_floor_plans != '') {
                                    $floor_plans_data = json_decode(urldecode($edit_floor_plans));
                        
                                    if (isset($floor_plans_data)) {
                                        $floor_plans_list = $floor_plans_data->plans;
                                    }
                                }

                                $beds_label = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
                                $baths_label = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
                                $unit  = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : ''; ?>

                                <h3 class="mt-4 mt-md-5"><?php esc_html_e('Floor Plans', 'resideo'); ?></h3>

                                <div class="mt-3 mt-md-4">
                                    <input type="hidden" id="new_floor_plans" name="new_floor_plans" value="<?php echo esc_attr($edit_floor_plans); ?>" />

                                    <ul class="pxp-sortable-list" id="pxp-submit-property-floor-plans-list">
                                        <?php if (count($floor_plans_list) > 0) { ?>
                                            <?php foreach ($floor_plans_list as $floor_plan) {
                                                $image = wp_get_attachment_image_src($floor_plan->image, 'pxp-thmb');
                                                $image_src = RESIDEO_PLUGIN_PATH . 'images/ph-thmb.jpg';

                                                if ($image !== false) { 
                                                    $image_src = $image[0];
                                                } ?>

                                                <li class="pxp-sortable-list-item rounded-lg" 
                                                        data-id="<?php echo esc_attr($floor_plan->image); ?>" 
                                                        data-src="<?php echo esc_attr($image_src); ?>" 
                                                        data-title="<?php echo esc_attr($floor_plan->title); ?>" 
                                                        data-beds="<?php echo esc_attr($floor_plan->beds); ?>" 
                                                        data-baths="<?php echo esc_attr($floor_plan->baths); ?>" 
                                                        data-size="<?php echo esc_attr($floor_plan->size); ?>" 
                                                        data-description="<?php echo esc_attr($floor_plan->description); ?>">
                                                    <div class="row align-items-center pxp-submit-property-floor-plan-item">
                                                        <div class="col-3 col-sm-2">
                                                            <div class="pxp-sortable-list-item-photo pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($image_src); ?>);"></div>
                                                        </div>
                                                        <div class="col-9 col-sm-10">
                                                            <div class="row align-items-center">
                                                                <div class="col-9 col-sm-8 col-lg-10">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-lg-7 pxp-sortable-list-item-title"><?php echo esc_html($floor_plan->title); ?></div>
                                                                        <div class="col-lg-5">
                                                                            <div class="pxp-sortable-list-item-features">
                                                                                <?php if ($floor_plan->beds != '') { ?>
                                                                                    <?php echo esc_html($floor_plan->beds); ?> <?php echo esc_html($beds_label); ?> <span>|</span>
                                                                                <?php } ?>
                                                                                <?php if ($floor_plan->baths != '') { ?>
                                                                                    <?php echo esc_html($floor_plan->baths); ?> <?php echo esc_html($baths_label); ?> <span>|</span>
                                                                                <?php } ?>
                                                                                <?php if ($floor_plan->size != '') { ?>
                                                                                    <?php echo esc_html($floor_plan->size); ?> <?php echo esc_html($unit); ?>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3 col-sm-4 col-lg-2 text-right">
                                                                    <a href="javascript:void(0);" class="pxp-sortable-list-item-edit pxp-submit-property-floor-plans-item-edit"><span class="fa fa-pencil"></span></a>
                                                                    <a href="javascript:void(0);" class="pxp-sortable-list-item-delete pxp-submit-property-floor-plans-item-delete"><span class="fa fa-trash-o"></span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>

                                <div class="mt-3 mt-md-4">
                                    <a href="javascript:void(0);" class="pxp-add-floor-plan-btn"><span class="fa fa-clone"></span>&nbsp;&nbsp;&nbsp;<?php esc_attr_e('Add Floor Plan', 'resideo'); ?></a>
                                    <div class="pxp-new-floor-plan rounded-lg">
                                        <h4><?php esc_attr_e('New Floor Plan', 'resideo'); ?></h4>
                                        <div class="mt-3 mt-md-4">
                                            <div class="form-group">
                                                <label for="pxp-new-floor-plan-title"><?php esc_html_e('Title', 'resideo'); ?></label>
                                                <input type="text" name="pxp-new-floor-plan-title" id="pxp-new-floor-plan-title" class="form-control" placeholder="<?php esc_attr_e('Enter plan title', 'resideo'); ?>">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="pxp-new-floor-plan-beds"><?php esc_html_e('Beds', 'resideo'); ?></label>
                                                        <input type="text" name="pxp-new-floor-plan-beds" id="pxp-new-floor-plan-beds" class="form-control" placeholder="<?php esc_attr_e('Enter number of beds', 'resideo'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="pxp-new-floor-plan-baths"><?php esc_html_e('Baths', 'resideo'); ?></label>
                                                        <input type="text" name="pxp-new-floor-plan-baths" id="pxp-new-floor-plan-baths" class="form-control" placeholder="<?php esc_attr_e('Enter number of baths', 'resideo'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="pxp-new-floor-plan-size"><?php esc_html_e('Size', 'resideo'); ?> (<?php echo esc_html($unit); ?>)</label>
                                                        <input type="text" name="pxp-new-floor-plan-size" id="pxp-new-floor-plan-size" class="form-control" placeholder="<?php esc_attr_e('Enter size', 'resideo'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label for="pxp-new-floor-plan-description"><?php esc_html_e('Description', 'resideo'); ?></label>
                                                        <textarea id="pxp-new-floor-plan-description" name="pxp-new-floor-plan-description" class="form-control" placeholder="<?php esc_attr_e('Enter description here...', 'resideo'); ?>"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <div class="pxp-new-floor-plan-image-label"><?php esc_html_e('Image', 'resideo'); ?></div>
                                                        <div class="position-relative">
                                                            <div id="aaiu-upload-container-floor-plan">
                                                                <div class="pxp-submit-property-floor-plan">
                                                                    <div class="pxp-submit-property-floor-plan-image" style="background-image: url(<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png'); ?>);" data-id=""></div>
                                                                </div>
                                                                <div class="pxp-submit-property-upload-floor-plan-status"></div>
                                                                <div class="clearfix"></div>
                                                                <a role="button" id="aaiu-uploader-floor-plan" class="pxp-browser-photos-btn"><span class="fa fa-picture-o"></span>&nbsp;&nbsp;&nbsp;<?php esc_html_e('Upload', 'resideo'); ?></a>
                                                                <input type="hidden" name="pxp-new-floor-plan-image" id="pxp-new-floor-plan-image" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="btn pxp-new-floor-plan-ok-btn mr-1"><?php esc_html_e('Add Plan', 'resideo'); ?></a>
                                            <a href="javascript:void(0);" class="btn pxp-new-floor-plan-cancel-btn"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-4">
                                <div class="pxp-submit-property-side rounded-lg mt-4 mt-md-5 mt-lg-0">
                                    <h3><?php esc_html_e('Publish', 'resideo'); ?></h3>

                                    <div class="mt-3 mt-md-4 form-group">
                                        <div class="property-submit-property-sub-status">
                                            <?php esc_html_e('Submission Status', 'resideo'); ?>: 
                                            <b>
                                                <?php if (isset($edit_id) && $edit_id != '') {
                                                    if (get_post_status($edit_id) == 'publish') {
                                                        esc_html_e('Published', 'resideo');
                                                    } else {
                                                        esc_html_e('Pending for Approval', 'resideo');
                                                    }
                                                } else {
                                                    esc_html_e('New', 'resideo');
                                                } ?>
                                            </b>
                                        </div>
                                    </div>

                                    <?php if ($featured_option == '1') { 
                                        $edit_featured  = ($edit_id != '') ? get_post_meta($edit_id, 'property_featured', true) : ''; ?>

                                        <div class="form-group">
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="new_featured" name="new_featured" value="1" <?php checked($edit_featured, '1'); ?>>
                                                <label class="form-check-label" for="new_featured"><?php esc_html_e('Set as Featured', 'resideo'); ?></label>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div>
                                        <a href="javascript:void(0);" class="btn pxp-submit-property-btn">
                                            <span class="pxp-submit-property-btn-text">
                                                <?php if (isset($edit_id) && $edit_id != '') {
                                                    esc_html_e('Update', 'resideo');
                                                } else {
                                                    esc_html_e('Publish', 'resideo');
                                                } ?>
                                            </span>
                                            <span class="pxp-submit-property-btn-sending">
                                                <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                                <?php if (isset($edit_id) && $edit_id != '') {
                                                    esc_html_e('Updating...', 'resideo');
                                                } else {
                                                    esc_html_e('Publishing...', 'resideo');
                                                } ?>
                                            </span>
                                        </a>

                                        <?php if ($edit_id != '') { ?>
                                            <div class="form-inline float-right">
                                                <?php if (get_post_status($edit_id) == 'publish') { ?>
                                                    <div class="form-group d-flex">
                                                        <a href="<?php echo esc_url($edit_link); ?>" class="pxp-submit-property-btn-view" target="_blank"><span class="fa fa-eye"></span></a>
                                                    </div>
                                                <?php } ?>

                                                <div class="form-group d-flex">
                                                    <a role="button" class="pxp-submit-property-btn-delete" data-toggle="modal" data-target="#pxp-submit-property-delete-modal"><span class="fa fa-trash-o"></span></a>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                
                                <?php if ($p_price == 'enabled' || $p_beds == 'enabled' || $p_baths == 'enabled' || $p_size == 'enabled') { ?>
                                    <div class="pxp-submit-property-side rounded-lg mt-4 mt-md-5">
                                        <h3><?php esc_html_e('Main Details', 'resideo'); ?></h3>

                                        <!-- PRICE FIELD -->

                                        <?php if ($p_price == 'enabled') { 
                                            $p_price_req  = isset($fields_settings['resideo_p_price_r_field']) ? $fields_settings['resideo_p_price_r_field'] : ''; 
                                            $edit_price   = ($edit_id != '') ? get_post_meta($edit_id, 'property_price', true) : '';
                                            $edit_price_label = ($edit_id != '') ? get_post_meta($edit_id, 'property_price_label', true) : ''; ?>

                                            <div class="row mt-3 mt-md-4">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="new_price">
                                                            <?php esc_html_e('Price', 'resideo'); ?> (<?php echo esc_html($currency); ?>)
                                                            <?php if ($p_price_req == 'required') { ?>
                                                                <span class="text-red">*</span>
                                                            <?php } ?>
                                                        </label>
                                                        <input type="text" class="form-control" id="new_price" placeholder="<?php esc_html_e('Enter price', 'resideo'); ?>" value="<?php echo esc_attr($edit_price); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="new_price_label"><?php esc_html_e('Price Label', 'resideo'); ?></label>
                                                        <input type="text" class="form-control" id="new_price_label" name="new_price_label" placeholder="<?php esc_html_e('Enter price label', 'resideo'); ?>" value="<?php echo esc_attr($edit_price_label); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="row<?php if ($p_price != 'enabled') { echo esc_attr(' mt-3 mt-md-4'); } ?>">

                                            <!-- BEDS FIELD -->

                                            <?php if ($p_beds == 'enabled') {
                                                $p_beds_req = isset($fields_settings['resideo_p_beds_r_field']) ? $fields_settings['resideo_p_beds_r_field'] : '';
                                                $edit_beds  = ($edit_id != '') ? get_post_meta($edit_id, 'property_beds', true) : ''; ?>

                                                <div class="col-sm-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="new_beds">
                                                            <?php esc_html_e('Beds', 'resideo');
                                                            if ($p_beds_req == 'required') { ?>
                                                                <span class="text-red">*</span>
                                                            <?php } ?>
                                                        </label>
                                                        <input type="text" class="form-control" id="new_beds" placeholder="0" value="<?php echo esc_attr($edit_beds); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!-- BATHS FIELD -->

                                            <?php if ($p_baths == 'enabled') {
                                                $p_baths_req = isset($fields_settings['resideo_p_baths_r_field']) ? $fields_settings['resideo_p_baths_r_field'] : '';
                                                $edit_baths  = ($edit_id != '') ? get_post_meta($edit_id, 'property_baths', true) : ''; ?>

                                                <div class="col-sm-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="new_baths">
                                                            <?php esc_html_e('Baths', 'resideo');
                                                            if ($p_baths_req == 'required') { ?>
                                                                <span class="text-red">*</span>
                                                            <?php } ?>
                                                        </label>
                                                        <input type="text" class="form-control" id="new_baths" placeholder="0" value="<?php echo esc_attr($edit_baths); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!-- SIZE FIELD -->

                                            <?php if ($p_size == 'enabled') {
                                                $p_size_req = isset($fields_settings['resideo_p_size_r_field']) ? $fields_settings['resideo_p_size_r_field'] : '';
                                                $unit       = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
                                                $edit_size  = ($edit_id != '') ? get_post_meta($edit_id, 'property_size', true) : ''; ?>

                                                <div class="col-sm-12 col-md-4">
                                                    <div class="form-group">
                                                        <label for="new_size">
                                                            <?php esc_html_e('Size', 'resideo'); ?> (<?php echo esc_html($unit); ?>)
                                                            <?php if ($p_size_req == 'required') { ?>
                                                                <span class="text-red">*</span>
                                                            <?php } ?>
                                                        </label>
                                                        <input type="text" class="form-control" id="new_size" placeholder="0" value="<?php echo esc_attr($edit_size); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="pxp-submit-property-side rounded-lg mt-4 mt-md-5">
                                    <h3><?php esc_html_e('Location', 'resideo'); ?></h3>

                                    <div class="mt-3 mt-md-4">

                                        <!-- ADDRESS FIELD -->

                                        <?php if ($p_address == 'enabled') { 
                                            $p_address_req   = isset($fields_settings['resideo_p_address_r_field']) ? $fields_settings['resideo_p_address_r_field'] : '';
                                            $p_address_type  = isset($fields_settings['resideo_p_address_t_field']) ? $fields_settings['resideo_p_address_t_field'] : '';
                                            $p_address_class = ($p_address_type == 'auto') ? 'new-address-auto' : '';
                                            $edit_address    = ($edit_id != '') ? get_post_meta($edit_id, 'property_address', true) : ''; ?>
                                            <div class="form-group">
                                                <label for="new_address">
                                                    <?php esc_html_e('Address', 'resideo'); ?>
                                                    <?php if ($p_address_req == 'required') { ?>
                                                        <span class="text-red">*</span>
                                                    <?php } ?>
                                                </label>
                                                <input class="form-control <?php echo esc_attr($p_address_class); ?>" type="text" id="new_address" name="new_address" placeholder="<?php esc_html_e('Enter address', 'resideo'); ?>" value="<?php echo esc_attr($edit_address); ?>">
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">

                                        <!-- COORDINATES FIELD -->

                                        <?php if ($p_coordinates == 'enabled') { 
                                            $p_coordinates_req = isset($fields_settings['resideo_p_coordinates_r_field']) ? $fields_settings['resideo_p_coordinates_r_field'] : '';
                                            $edit_lat          = ($edit_id != '') ? get_post_meta($edit_id, 'property_lat', true) : '';
                                            $edit_lng          = ($edit_id != '') ? get_post_meta($edit_id, 'property_lng', true) : ''; ?>

                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="new_lat">
                                                        <?php esc_html_e('Latitude', 'resideo'); ?>
                                                        <?php if ($p_coordinates_req == 'required') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <input class="form-control" type="text" id="new_lat" name="new_lat" placeholder="<?php esc_html_e('Enter latitude', 'resideo'); ?>" value="<?php echo esc_attr($edit_lat); ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="new_lng">
                                                        <?php esc_html_e('Longitude', 'resideo'); ?>
                                                        <?php if ($p_coordinates_req == 'required') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <input class="form-control" type="text" id="new_lng" name="new_lng" placeholder="<?php esc_html_e('Enter longitude', 'resideo'); ?>" value="<?php echo esc_attr($edit_lng); ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- MAP -->

                                    <?php if (wp_script_is('gmaps', 'enqueued')) { ?>
                                        <div class="form-group">
                                            <div id="pxp-submit-property-map"></div>
                                        </div>
                                    <?php } ?>

                                    <div class="row">

                                        <!-- STREET NO FIELD -->

                                        <?php if ($p_streetno == 'enabled') { 
                                            $p_streetno_req = isset($fields_settings['resideo_p_streetno_r_field']) ? $fields_settings['resideo_p_streetno_r_field'] : '';
                                            $edit_streetno  = ($edit_id != '') ? get_post_meta($edit_id, 'street_number', true) : ''; ?>

                                            <div class="col-sm-12 col-md-5">
                                                <div class="form-group">
                                                    <label sfor="new_street_no">
                                                        <?php esc_html_e('Street No', 'resideo'); ?>
                                                        <?php if ($p_streetno_req == 'required') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <input class="form-control" type="text" id="new_street_no" name="new_street_no" placeholder="<?php esc_html_e('Enter no', 'resideo'); ?>" value="<?php echo esc_attr($edit_streetno); ?>">
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <!-- STREET NAME FIELD -->

                                        <?php if ($p_street == 'enabled') { 
                                            $p_street_req = isset($fields_settings['resideo_p_street_r_field']) ? $fields_settings['resideo_p_street_r_field'] : '';
                                            $edit_street  = ($edit_id != '') ? get_post_meta($edit_id, 'route', true) : ''; ?>

                                            <div class="col-sm-12 col-md-7">
                                                <div class="form-group">
                                                    <label for="new_street">
                                                        <?php esc_html_e('Street Name', 'resideo'); ?>
                                                        <?php if ($p_street_req == 'required') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <input class="form-control" type="text" id="new_street" name="new_street" placeholder="<?php esc_html_e('Enter street name', 'resideo'); ?>" value="<?php echo esc_attr($edit_street); ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <?php if ($p_neighborhood == 'enabled') { 
                                        $p_neighborhood_req  = isset($fields_settings['resideo_p_neighborhood_r_field']) ? $fields_settings['resideo_p_neighborhood_r_field'] : '';
                                        $p_neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : ''; 
                                        $edit_neighborhood   = ($edit_id != '') ? get_post_meta($edit_id, 'neighborhood', true) : ''; ?>

                                        <div class="form-group">
                                            <label for="new_neighborhood">
                                                <?php esc_html_e('Neighborhood', 'resideo'); ?>
                                                <?php if ($p_neighborhood_req == 'required') { ?>
                                                    <span class="text-red">*</span>
                                                <?php } ?>
                                            </label>

                                            <?php if ($p_neighborhood_type == 'list') {
                                                $neighborhoods_settings = get_option('resideo_neighborhoods_settings'); ?>

                                                <select name="new_neighborhood" id="new_neighborhood" class="custom-select">
                                                    <option value=""><?php esc_html_e('Select neighborhood', 'resideo'); ?></option>
                                                    <?php if (is_array($neighborhoods_settings) && count($neighborhoods_settings) > 0) {
                                                        uasort($neighborhoods_settings, "resideo_compare_position");

                                                        foreach ($neighborhoods_settings as $key => $value) { ?>
                                                            <option value="<?php echo esc_attr($key); ?>" <?php selected($key, $edit_neighborhood); ?>><?php echo esc_html($value['name']); ?></option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            <?php } else { ?>
                                                <input class="form-control" type="text" name="new_neighborhood" id="new_neighborhood" placeholder="<?php esc_html_e('Enter neighborhood', 'resideo'); ?>" value="<?php echo esc_attr($edit_neighborhood); ?>">
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <!-- CITY FIELD -->

                                    <?php if ($p_city == 'enabled') { 
                                        $p_city_req  = isset($fields_settings['resideo_p_city_r_field']) ? $fields_settings['resideo_p_city_r_field'] : '';
                                        $p_city_type = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : ''; 
                                        $edit_city   = ($edit_id != '') ? get_post_meta($edit_id, 'locality', true) : ''; ?>

                                        <div class="form-group">
                                            <label for="new_city">
                                                <?php esc_html_e('City', 'resideo'); ?>
                                                <?php if ($p_city_req == 'required') { ?>
                                                    <span class="text-red">*</span>
                                                <?php } ?>
                                            </label>

                                            <?php if ($p_city_type == 'list') {
                                                $city_settings = get_option('resideo_cities_settings'); ?>

                                                <select name="new_city" id="new_city" class="custom-select">
                                                    <option value=""><?php esc_html_e('Select city', 'resideo'); ?></option>
                                                    <?php if (is_array($city_settings) && count($city_settings) > 0) {
                                                        uasort($city_settings, "resideo_compare_position");

                                                        foreach ($city_settings as $key => $value) { ?>
                                                            <option value="<?php echo esc_attr($key); ?>" <?php selected($key, $edit_city); ?>><?php echo esc_html($value['name']); ?></option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            <?php } else { ?>
                                                <input class="form-control" type="text" name="new_city" id="new_city" placeholder="<?php esc_html_e('Enter city', 'resideo'); ?>" value="<?php echo esc_attr($edit_city); ?>">
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <div class="row">

                                        <!-- COUNTY/STATE FIELD -->

                                        <?php if ($p_state == 'enabled') { 
                                            $p_state_req = isset($fields_settings['resideo_p_state_r_field']) ? $fields_settings['resideo_p_state_r_field'] : '';
                                            $edit_state  = ($edit_id != '') ? get_post_meta($edit_id, 'administrative_area_level_1', true) : ''; ?>

                                            <div class="col-sm-12 col-md-7">
                                                <div class="form-group">
                                                    <label for="new_state">
                                                        <?php esc_html_e('County/State', 'resideo'); ?>
                                                        <?php if ($p_state_req == 'required') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <input class="form-control" type="text" name="new_state" id="new_state" placeholder="<?php esc_html_e('Enter county/state', 'resideo'); ?>" value="<?php echo esc_attr($edit_state); ?>">
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <!-- ZIP CODE FIELD -->

                                        <?php if ($p_zip == 'enabled') { 
                                            $p_zip_req = isset($fields_settings['resideo_p_zip_r_field']) ? $fields_settings['resideo_p_zip_r_field'] : '';
                                            $edit_zip  = ($edit_id != '') ? get_post_meta($edit_id, 'postal_code', true) : ''; ?>

                                            <div class="col-sm-12 col-md-5">
                                                <div class="form-group">
                                                    <label for="new_zip">
                                                        <?php esc_html_e('Zip Code', 'resideo'); ?>
                                                        <?php if ($p_zip_req == 'required') { ?>
                                                            <span class="text-red">*</span>
                                                        <?php } ?>
                                                    </label>
                                                    <input class="form-control" type="text" name="new_zip" id="new_zip" placeholder="<?php esc_html_e('Enter zip', 'resideo'); ?>" value="<?php echo esc_attr($edit_zip); ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- MORTGAGE CALCULATOR FIELDS -->

                                <?php if ($p_calculator == 'enabled') { 
                                    $edit_calculator  = ($edit_id != '') ? get_post_meta($edit_id, 'property_calc', true) : ''; ?>

                                    <div class="pxp-submit-property-side rounded-lg mt-4 mt-md-5">
                                        <h3><?php esc_html_e('Mortgage Calculator', 'resideo'); ?></h3>

                                        <div class="mt-3 mt-md-4">
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="new_calculator" name="new_calculator" value="1" <?php checked($edit_calculator, '1'); ?>>
                                                <label class="form-check-label" for="new_calculator"><?php esc_html_e('Enable calculator', 'resideo'); ?></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <?php if ($p_taxes == 'enabled') { 
                                                $edit_taxes  = ($edit_id != '') ? get_post_meta($edit_id, 'property_taxes', true) : '0'; ?>

                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="new_taxes"><?php esc_html_e('Property Taxes', 'resideo'); ?> (<?php echo esc_html($currency); ?>)</label>
                                                        <input class="form-control" type="text" name="new_taxes" id="new_taxes" placeholder="<?php esc_html_e('Enter taxes', 'resideo'); ?>" value="<?php echo esc_attr($edit_taxes); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if ($p_hoa == 'enabled') { 
                                                $edit_hoa  = ($edit_id != '') ? get_post_meta($edit_id, 'property_hoa_dues', true) : '0'; ?>

                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="new_hoa"><?php esc_html_e('HOA Dues', 'resideo'); ?> (<?php echo esc_html($currency); ?>)</label>
                                                        <input class="form-control" type="text" name="new_hoa" id="new_hoa" placeholder="<?php esc_html_e('Enter HOA dues', 'resideo'); ?>" value="<?php echo esc_attr($edit_hoa); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                <?php } else if ($expired == true) { ?>
                    <div class="alert alert-secondary mt-4 mt-md-5" role="alert">
                        <h4 class="alert-heading"><?php esc_html_e('Your membership plan expired.', 'resideo'); ?></h4>
                        <p><?php esc_html_e('Please renew your membership plan if you want to submit new listings.', 'resideo'); ?></p>
                        <a href="<?php echo esc_url(resideo_get_account_url()); ?>" class="alert-link">
                            <?php esc_html_e('Go to your account page', 'resideo'); ?> <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-secondary mt-4 mt-md-5" role="alert">
                        <h4 class="alert-heading"><?php esc_html_e('You ran out of available submissions.', 'resideo'); ?></h4>
                        <p><?php esc_html_e('Please upgrade your membership plan if you want to submit new listings.', 'resideo'); ?></p>
                        <a href="<?php echo esc_url(resideo_get_account_url()); ?>" class="alert-link">
                            <?php esc_html_e('Go to your account page', 'resideo'); ?> <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div class="modal fade pxp-alert-modal" id="pxp-submit-property-alert-modal" role="dialog" aria-labelledby="pxpSubmitPropertyAlertModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pxp-submit-property-response"></div>
        </div>
    </div>
</div>

<div class="modal fade pxp-property-modal" id="pxp-submit-property-delete-modal" tabindex="-1" role="dialog" aria-labelledby="pxpSubmitPropertyDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="pxpSubmitPropertyDeleteModalLabel"><?php _e('Delete Property', 'resideo'); ?></h5>
                <p class="mt-4"><?php esc_html_e('Are you sure?', 'resideo'); ?></p>
                <div class="mt-4">
                    <a href="javascript:void(0);" class="pxp-submit-property-btn-delete-confirm">
                        <span class="pxp-submit-property-btn-delete-confirm-text"><?php esc_html_e('Delete', 'resideo'); ?></span>
                        <span class="pxp-submit-property-btn-delete-confirm-sending">
                            <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                            <?php esc_html_e('Deleting...', 'resideo'); ?>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="pxp-submit-property-btn-delete-cancel" data-dismiss="modal"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>