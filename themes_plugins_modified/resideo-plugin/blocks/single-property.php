<?php
/**
 * Single property block
 */
if (!function_exists('resideo_single_property_block')): 
    function resideo_single_property_block() {
        wp_register_script(
            'resideo-single-property-block',
            plugins_url('js/single-property.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-single-property-block-editor',
            plugins_url('css/single-property.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/single-property', array(
            'editor_script' => 'resideo-single-property-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_single_property_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_single_property_block');
?>