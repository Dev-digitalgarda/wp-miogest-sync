<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Send contact shortcode form message
 */
if (!function_exists('resideo_send_contact_shortcode_message')): 
    function resideo_send_contact_shortcode_message() {
        check_ajax_referer('contact_section_form_ajax_nonce', 'security');

        $company_email = isset($_POST['company_email']) ? sanitize_email($_POST['company_email']) : '';
        $client_email = isset($_POST['client_email']) ? sanitize_email($_POST['client_email']) : '';

        if (empty($client_email)) {
            echo json_encode(array('sent' => false, 'message' => __('Email field is mandatory.', 'resideo')));
            exit();
        }

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'resideo_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        $body = '';

        if ($custom_fields != '') {
            $body .= __('The following details were sent via contact form:', 'resideo') . '<br /><br />';

            foreach ($custom_fields as $key => $value) {
                if (
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'text_input_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'textarea_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'None' && $value['field_type'] == 'select_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'No' && $value['field_type'] == 'checkbox_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'date_field')
                ) {
                    echo json_encode(array('save' => false, 'message' => sprintf (__('%s field is mandatory.', 'resideo'), $value['field_label'])));
                    exit();
                }

                $body .= $value['field_label'] . ': ' . $value['field_value'] . '<br />';
            }

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        } else {
            $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
            $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
            $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

            if (empty($name)) {
                echo json_encode(array('sent' => false, 'message' => __('Name field is mandatory.', 'resideo')));
                exit();
            }

            if (empty($message)) {
                echo json_encode(array('sent' => false, 'message' => __('Message field is mandatory.', 'resideo')));
                exit();
            }

            $body = __('You received the following message from ', 'resideo');
            $body .= $name . ' [' . __('Phone number', 'resideo') . ': ' . $phone . '] ' . __('regarding', 'resideo') . ' ' . $reg . '<br /><br />';
            $body .= $message;

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        }

        $company_email = explode(',', $company_email);

        $send = wp_mail(
            $company_email,
            sprintf(__('%s | Message from client', 'resideo'), get_option('blogname')),
            $body,
            $headers
        );

        if ($send) {
            echo json_encode(array('sent' => true, 'message' => __('Your message was successfully sent.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent' => false, 'message' => __('Your message failed to be sent.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_send_contact_shortcode_message', 'resideo_send_contact_shortcode_message');
add_action('wp_ajax_resideo_send_contact_shortcode_message', 'resideo_send_contact_shortcode_message');

/**
 * Send contact page form message
 */
if (!function_exists('resideo_contact_company')): 
    function resideo_contact_company() {
        check_ajax_referer('contact_form_page_ajax_nonce', 'security');

        $company_email = isset($_POST['company_email']) ? sanitize_email($_POST['company_email']) : '';
        $client_email = isset($_POST['client_email']) ? sanitize_email($_POST['client_email']) : '';

        if (empty($client_email)) {
            echo json_encode(array('sent' => false, 'message' => __('Email field is mandatory.', 'resideo')));
            exit();
        }

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'resideo_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        $body = '';

        if ($custom_fields != '') {
            $body .= __('The following details were sent via contact form:', 'resideo') . '<br /><br />';

            foreach ($custom_fields as $key => $value) {
                if (
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'text_input_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'textarea_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'None' && $value['field_type'] == 'select_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'No' && $value['field_type'] == 'checkbox_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'date_field')
                ) {
                    echo json_encode(array('save' => false, 'message' => sprintf (__('%s field is mandatory.', 'resideo'), $value['field_label'])));
                    exit();
                }

                $body .= $value['field_label'] . ': ' . $value['field_value'] . '<br />';
            }

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        } else {
            $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
            $reg     = isset($_POST['reg']) ? sanitize_text_field($_POST['reg']) : '';
            $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
            $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

            if (empty($name)) {
                echo json_encode(array('sent' => false, 'message' => __('Name field is mandatory.', 'resideo')));
                exit();
            }

            if (empty($reg)) {
                echo json_encode(array('sent' => false, 'message' => __('Please select a category.', 'resideo')));
                exit();
            }

            if (empty($message)) {
                echo json_encode(array('sent' => false, 'message' => __('Message field is mandatory.', 'resideo')));
                exit();
            }

            $body = __('You received the following message from ', 'resideo');
            $body .= $name . ' [' . __('Phone number', 'resideo') . ': ' . $phone . '] ' . __('regarding', 'resideo') . ' ' . $reg . '<br /><br />';
            $body .= $message;

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        }

        $company_email = explode(',', $company_email);

        $send = wp_mail(
            $company_email,
            sprintf(__('%s | Message from client', 'resideo'), get_option('blogname')),
            $body,
            $headers
        );

        if ($send) {
            echo json_encode(array('sent' => true, 'message' => __('Your message was successfully sent.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent' => false, 'message' => __('Your message failed to be sent.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_contact_company', 'resideo_contact_company');
add_action('wp_ajax_resideo_contact_company', 'resideo_contact_company');

/**
 * Send hero contact form message
 */
if (!function_exists('resideo_send_hero_contact_message')): 
    function resideo_send_hero_contact_message() {
        check_ajax_referer('hero_contact_form_page_ajax_nonce', 'security');

        $company_email = isset($_POST['company_email']) ? sanitize_email($_POST['company_email']) : '';
        $client_email = isset($_POST['client_email']) ? sanitize_email($_POST['client_email']) : '';

        if (empty($client_email)) {
            echo json_encode(array('sent' => false, 'message' => __('Email field is mandatory.', 'resideo')));
            exit();
        }

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'resideo_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        $body = '';

        if ($custom_fields != '') {
            $body .= __('The following details were sent via contact form:', 'resideo') . '<br /><br />';

            foreach ($custom_fields as $key => $value) {
                if (
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'text_input_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'textarea_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'None' && $value['field_type'] == 'select_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'No' && $value['field_type'] == 'checkbox_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'date_field')
                ) {
                    echo json_encode(array('save' => false, 'message' => sprintf (__('%s field is mandatory.', 'resideo'), $value['field_label'])));
                    exit();
                }

                $body .= $value['field_label'] . ': ' . $value['field_value'] . '<br />';
            }

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        } else {
            $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
            $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
            $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

            if (empty($name)) {
                echo json_encode(array('sent' => false, 'message' => __('Name field is mandatory.', 'resideo')));
                exit();
            }

            if (empty($message)) {
                echo json_encode(array('sent' => false, 'message' => __('Message field is mandatory.', 'resideo')));
                exit();
            }

            $body = __('You received the following message from ', 'resideo');
            $body .= $name . ' [' . __('Phone number', 'resideo') . ': ' . $phone . '] ' . __('regarding', 'resideo') . ' ' . $reg . '<br /><br />';
            $body .= $message;

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        }

        $company_email = explode(',', $company_email);

        $send = wp_mail(
            $company_email,
            sprintf(__('%s | Message from client', 'resideo'), get_option('blogname')),
            $body,
            $headers
        );

        if ($send) {
            echo json_encode(array('sent' => true, 'message' => __('Your message was successfully sent.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent' => false, 'message' => __('Your message failed to be sent.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_send_hero_contact_message', 'resideo_send_hero_contact_message');
add_action('wp_ajax_resideo_send_hero_contact_message', 'resideo_send_hero_contact_message');

/**
 * Send contact widget form message
 */
if (!function_exists('resideo_widget_contact_company')): 
    function resideo_widget_contact_company() {
        check_ajax_referer('contact_form_widget_ajax_nonce', 'security');

        $company_email = isset($_POST['company_email']) ? sanitize_email($_POST['company_email']) : '';
        $client_email = isset($_POST['client_email']) ? sanitize_email($_POST['client_email']) : '';

        if (empty($client_email)) {
            echo json_encode(array('sent' => false, 'message' => __('Email field is mandatory.', 'resideo')));
            exit();
        }

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'resideo_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        $body = '';

        if ($custom_fields != '') {
            $body .= __('The following details were sent via contact form:', 'resideo') . '<br /><br />';

            foreach ($custom_fields as $key => $value) {
                if (
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'text_input_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'textarea_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'None' && $value['field_type'] == 'select_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == 'No' && $value['field_type'] == 'checkbox_field') || 
                    ($value['field_mandatory'] == 'yes' && $value['field_value'] == '' && $value['field_type'] == 'date_field')
                ) {
                    echo json_encode(array('save' => false, 'message' => sprintf (__('%s field is mandatory.', 'resideo'), $value['field_label'])));
                    exit();
                }

                $body .= $value['field_label'] . ': ' . $value['field_value'] . '<br />';
            }

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        } else {
            $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
            $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
            $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

            if (empty($name)) {
                echo json_encode(array('sent' => false, 'message' => __('Name field is mandatory.', 'resideo')));
                exit();
            }

            if (empty($message)) {
                echo json_encode(array('sent' => false, 'message' => __('Message field is mandatory.', 'resideo')));
                exit();
            }

            $body = __('You received the following message from ', 'resideo');
            $body .= $name . ' [' . __('Phone number', 'resideo') . ': ' . $phone . ']' . '<br /><br />';
            $body .= $message;

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: '. $client_email,
                'Reply-To: ' . $client_email
            );
        }

        $company_email = explode(',', $company_email);

        $send = wp_mail(
            $company_email,
            sprintf(__('%s | Message from client', 'resideo'), get_option('blogname')),
            $body,
            $headers
        );

        if ($send) {
            echo json_encode(array('sent' => true, 'message' => __('Your message was successfully sent.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent' => false, 'message' => __('Your message failed to be sent.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_widget_contact_company', 'resideo_widget_contact_company');
add_action('wp_ajax_resideo_widget_contact_company', 'resideo_widget_contact_company');
?>