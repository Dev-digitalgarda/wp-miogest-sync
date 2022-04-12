<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_membership')): 
    function resideo_admin_membership() {
        add_settings_section('resideo_membership_section', __('Membership and Payment', 'resideo'), 'resideo_membership_section_callback', 'resideo_membership_settings');
        add_settings_field('resideo_paid_field', __('PayPal Paid Submission', 'resideo'), 'resideo_paid_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_payment_currency_field', __('Payment Currency', 'resideo'), 'resideo_payment_currency_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_submission_price_field', __('Property Submission Price', 'resideo'), 'resideo_submission_price_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_featured_price_field', __('Featured Property Submission Price', 'resideo'), 'resideo_featured_price_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_free_submissions_no_field', __('Number of Free Submissions', 'resideo'), 'resideo_free_submissions_no_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_free_submissions_unlim_field', __('Unlimited Free Submissions', 'resideo'), 'resideo_free_submissions_unlim_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_free_featured_submissions_no_field', __('Number of Free Featured Submissions', 'resideo'), 'resideo_free_featured_submissions_no_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_api_version_field', __('PayPal API Version', 'resideo'), 'resideo_paypal_api_version_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_client_id_field', __('PayPal Client ID', 'resideo'), 'resideo_paypal_client_id_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_client_key_field', __('PayPal Client Secret Key', 'resideo'), 'resideo_paypal_client_key_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_api_username_field', __('PayPal API Username', 'resideo'), 'resideo_paypal_api_username_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_api_password_field', __('PayPal API Password', 'resideo'), 'resideo_paypal_api_password_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_api_signature_field', __('PayPal API Signature', 'resideo'), 'resideo_paypal_api_signature_field_render', 'resideo_membership_settings', 'resideo_membership_section');
        add_settings_field('resideo_paypal_email_field', __('PayPal Receiving E-mail', 'resideo'), 'resideo_paypal_email_field_render', 'resideo_membership_settings', 'resideo_membership_section');
    }
endif;

if (!function_exists('resideo_membership_section_callback')): 
    function resideo_membership_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('resideo_paid_field_render')): 
    function resideo_paid_field_render() { 
        $options = get_option('resideo_membership_settings');

        $value_select = '<select id="resideo_membership_settings[resideo_paid_field]" name="resideo_membership_settings[resideo_paid_field]">';
        $value_select .= '<option value="disabled"';
        if (isset($options['resideo_paid_field']) && $options['resideo_paid_field'] == 'disabled') {
            $value_select .= 'selected="selected"';
        }
        $value_select .= '>' . __('disabled', 'resideo') . '</option>';
        $value_select .= '<option value="listing"';
        if (isset($options['resideo_paid_field']) && $options['resideo_paid_field'] == 'listing') {
            $value_select .= 'selected="selected"';
        }
        $value_select .= '>' . __('per listing', 'resideo') . '</option>';
        $value_select .= '<option value="membership"';
        if (isset($options['resideo_paid_field']) && $options['resideo_paid_field'] == 'membership') {
            $value_select .= 'selected="selected"';
        }
        $value_select .= '>' . __('membership', 'resideo') . '</option>';
        $value_select .= '</select>';

        print $value_select;
    }
endif;

if (!function_exists('resideo_payment_currency_field_render')): 
    function resideo_payment_currency_field_render() { 
        $options = get_option('resideo_membership_settings');

        $currencies = array('USD','EUR','AUD','BRL','CAD','CZK','DKK','HKD','HUF','ILS','INR','JPY','MYR','MXN','NOK','NZD','PHP','PLN','GBP','SGD','SEK','CHF','TWD','THB','TRY');
        $currency_select = '<select id="resideo_membership_settings[resideo_payment_currency_field]" name="resideo_membership_settings[resideo_payment_currency_field]">';

        foreach ($currencies as $currency) {
            $currency_select .= '<option value="' . esc_attr($currency) . '"';

            if (isset($options['resideo_payment_currency_field']) && $options['resideo_payment_currency_field'] == $currency) {
                $currency_select .= 'selected="selected"';
            }

            $currency_select .= '>' . esc_html($currency) . '</option>';
        }

        $currency_select .= '</select>';

        print $currency_select;
    }
endif;

if (!function_exists('resideo_submission_price_field_render')): 
    function resideo_submission_price_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_submission_price_field]" type="text" size="20" name="resideo_membership_settings[resideo_submission_price_field]" value="<?php if (isset($options['resideo_submission_price_field'])) { echo esc_attr($options['resideo_submission_price_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_featured_price_field_render')): 
    function resideo_featured_price_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_featured_price_field]" type="text" size="20" name="resideo_membership_settings[resideo_featured_price_field]" value="<?php if (isset($options['resideo_featured_price_field'])) { echo esc_attr($options['resideo_featured_price_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_free_submissions_no_field_render')): 
    function resideo_free_submissions_no_field_render() {
        $options = get_option('resideo_membership_settings');

        // set free submissions number for all agents
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            $args = array(
                'post_type'   => 'agent',
                'post_status' => 'publish'
            );

            $posts = get_posts($args);

            foreach ($posts as $post) : setup_postdata($post);
                update_post_meta($post->ID, 'agent_free_listings', $options['resideo_free_submissions_no_field']);
            endforeach;

            wp_reset_postdata();
            wp_reset_query();
        } ?>

        <input id="resideo_membership_settings[resideo_free_submissions_no_field]" type="text" size="5" name="resideo_membership_settings[resideo_free_submissions_no_field]" value="<?php if (isset($options['resideo_free_submissions_no_field'])) { echo esc_attr($options['resideo_free_submissions_no_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_free_submissions_unlim_field_render')): 
    function resideo_free_submissions_unlim_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input type="checkbox" name="resideo_membership_settings[resideo_free_submissions_unlim_field]" <?php if (isset($options['resideo_free_submissions_unlim_field'])) { checked($options['resideo_free_submissions_unlim_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_free_featured_submissions_no_field_render')): 
    function resideo_free_featured_submissions_no_field_render() {
        $options = get_option('resideo_membership_settings');

        // set free featured submissions number for all agents
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            $args = array(
                'post_type'   => 'agent',
                'post_status' => 'publish'
            );

            $posts = get_posts($args);

            foreach ($posts as $post) : setup_postdata($post);
                update_post_meta($post->ID, 'agent_free_featured_listings', $options['resideo_free_featured_submissions_no_field']);
            endforeach;

            wp_reset_postdata();
            wp_reset_query();
        } ?>

        <input id="resideo_membership_settings[resideo_free_featured_submissions_no_field]" type="text" size="5" name="resideo_membership_settings[resideo_free_featured_submissions_no_field]" value="<?php if (isset($options['resideo_free_featured_submissions_no_field'])) { echo esc_attr($options['resideo_free_featured_submissions_no_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_paypal_api_version_field_render')): 
    function resideo_paypal_api_version_field_render() { 
        $options = get_option('resideo_membership_settings');

        $value_select = '<select id="resideo_membership_settings[resideo_paypal_api_version_field]" name="resideo_membership_settings[resideo_paypal_api_version_field]">';
        $value_select .= '<option value="test"';

        if (isset($options['resideo_paypal_api_version_field']) && $options['resideo_paypal_api_version_field'] == 'test') {
            $value_select .= 'selected="selected"';
        }

        $value_select .= '>' . __('test', 'resideo') . '</option>';
        $value_select .= '<option value="live"';

        if (isset($options['resideo_paypal_api_version_field']) && $options['resideo_paypal_api_version_field'] == 'live') {
            $value_select .= 'selected="selected"';
        }

        $value_select .= '>' . __('live', 'resideo') . '</option>';
        $value_select .= '</select>';

        print $value_select;
    }
endif;

if (!function_exists('resideo_paypal_client_id_field_render')): 
    function resideo_paypal_client_id_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_paypal_client_id_field]" type="text" size="40" name="resideo_membership_settings[resideo_paypal_client_id_field]" value="<?php if (isset($options['resideo_paypal_client_id_field'])) { echo esc_attr($options['resideo_paypal_client_id_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_paypal_client_key_field_render')): 
    function resideo_paypal_client_key_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_paypal_client_key_field]" type="text" size="40" name="resideo_membership_settings[resideo_paypal_client_key_field]" value="<?php if (isset($options['resideo_paypal_client_key_field'])) { echo esc_attr($options['resideo_paypal_client_key_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_paypal_api_username_field_render')): 
    function resideo_paypal_api_username_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_paypal_api_username_field]" type="text" size="40" name="resideo_membership_settings[resideo_paypal_api_username_field]" value="<?php if (isset($options['resideo_paypal_api_username_field'])) { echo esc_attr($options['resideo_paypal_api_username_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_paypal_api_password_field_render')): 
    function resideo_paypal_api_password_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_paypal_api_password_field]" type="text" size="40" name="resideo_membership_settings[resideo_paypal_api_password_field]" value="<?php if (isset($options['resideo_paypal_api_password_field'])) { echo esc_attr($options['resideo_paypal_api_password_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_paypal_api_signature_field_render')): 
    function resideo_paypal_api_signature_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_paypal_api_signature_field]" type="text" size="40" name="resideo_membership_settings[resideo_paypal_api_signature_field]" value="<?php if (isset($options['resideo_paypal_api_signature_field'])) { echo esc_attr($options['resideo_paypal_api_signature_field']); } ?>" />
    <?php }
endif;

if(!function_exists('resideo_paypal_email_field_render')): 
    function resideo_paypal_email_field_render() {
        $options = get_option('resideo_membership_settings'); ?>

        <input id="resideo_membership_settings[resideo_paypal_email_field]" type="text" size="40" name="resideo_membership_settings[resideo_paypal_email_field]" value="<?php if (isset($options['resideo_paypal_email_field'])) { echo esc_attr($options['resideo_paypal_email_field']); } ?>" />
    <?php }
endif;
?>