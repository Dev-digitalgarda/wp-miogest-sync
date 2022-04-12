<?php
/**
 * Services block
 */
if (!function_exists('resideo_services_block')): 
    function resideo_services_block() {
        wp_register_script(
            'resideo-services-block',
            plugins_url('js/services.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-services-block-editor',
            plugins_url('css/services.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/services', array(
            'editor_script' => 'resideo-services-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_services_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_services_block');
?>