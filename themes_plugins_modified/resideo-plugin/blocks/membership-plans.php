<?php
/**
 * Membership plans block
 */
if (!function_exists('resideo_membership_plans_block')): 
    function resideo_membership_plans_block() {
        wp_register_script(
            'resideo-membership-plans-block',
            plugins_url('js/membership-plans.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-membership-plans-block-editor',
            plugins_url('css/membership-plans.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/membership-plans', array(
            'editor_script' => 'resideo-membership-plans-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_membership_plans_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_membership_plans_block');
?>