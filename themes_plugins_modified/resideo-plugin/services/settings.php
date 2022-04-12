<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

// Get custom fields list from theme settings
if (!function_exists('resideo_get_custom_fields_settings')): 
    function resideo_get_custom_fields_settings() {
        $options = get_option('resideo_fields_settings');

        if (is_array($options)) {
            uasort($options, 'resideo_compare_position');

            echo json_encode(array('getfields' => true, 'fields' => $options));
            exit();
        } else {
            echo json_encode(array('getfields' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_custom_fields_settings', 'resideo_get_custom_fields_settings');
add_action('wp_ajax_resideo_get_custom_fields_settings', 'resideo_get_custom_fields_settings');
?>