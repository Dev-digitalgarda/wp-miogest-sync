<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_notifications')): 
    function resideo_admin_notifications() {
        add_settings_section( 'resideo_notifications_section', __( 'Notifications', 'resideo' ), 'resideo_notifications_section_callback', 'resideo_notifications_settings' );
        add_settings_field( 'resideo_notify_agent_publish_field', __( 'Notify Agent when Properties are Published', 'resideo' ), 'resideo_notify_agent_publish_field_render', 'resideo_notifications_settings', 'resideo_notifications_section' );
        add_settings_field( 'resideo_notify_admin_publish_field', __( 'Notify Admin when Properties are Published', 'resideo' ), 'resideo_notify_admin_publish_field_render', 'resideo_notifications_settings', 'resideo_notifications_section' );
    }
endif;

if (!function_exists('resideo_notifications_section_callback')): 
    function resideo_notifications_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('resideo_notify_agent_publish_field_render')): 
    function resideo_notify_agent_publish_field_render() {
        $options = get_option('resideo_notifications_settings'); ?>

        <input type="checkbox" name="resideo_notifications_settings[resideo_notify_agent_publish_field]" <?php if (isset($options['resideo_notify_agent_publish_field'])) { checked( $options['resideo_notify_agent_publish_field'], 1 ); } ?> value="1">
    <?php }
endif;

if(!function_exists('resideo_notify_admin_publish_field_render')): 
    function resideo_notify_admin_publish_field_render() {
        $options = get_option('resideo_notifications_settings'); ?>

        <input type="checkbox" name="resideo_notifications_settings[resideo_notify_admin_publish_field]" <?php if (isset($options['resideo_notify_admin_publish_field'])) { checked( $options['resideo_notify_admin_publish_field'], 1 ); } ?> value="1">
    <?php }
endif;
?>