<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_report_property')): 
    function resideo_report_property() {
        check_ajax_referer('reportproperty_ajax_nonce', 'security');

        $title  = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $link   = isset($_POST['link']) ? sanitize_text_field($_POST['link']) : '';
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';

        if (empty($reason)) {
            echo json_encode(array('sent'=>false, 'message'=>__('Please describe a reason.', 'resideo')));
            exit();
        }

        $body = '';
        $body .= __('You received a report regarding this property listing:', 'resideo') . "\n\n";
        $body .=  $title . ' [ ' . $link . ' ]' . "\n\n";
        $body .=  __('Reason: ', 'resideo') . "\n\n";
        $body .= $reason;

        $admin_email = get_option('admin_email');

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: '. $admin_email,
            'Reply-To: ' . $admin_email
        );

        $send = wp_mail(
            $admin_email,
            sprintf( __('[%s] Property Listing Report', 'resideo'), get_option('blogname') ),
            $body,
            $headers
        );

        if ($send) {
            echo json_encode(array('sent'=>true, 'message'=>__('Your report was successfully submited.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent'=>false, 'message'=>__('Your report failed to be submited.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_report_property', 'resideo_report_property');
add_action('wp_ajax_resideo_report_property', 'resideo_report_property');
?>