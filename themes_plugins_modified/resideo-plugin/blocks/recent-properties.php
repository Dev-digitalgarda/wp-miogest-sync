<?php
/**
 * Recent properties block
 */
if (!function_exists('resideo_recent_properties_block')): 
    function resideo_recent_properties_block() {
        wp_register_script(
            'resideo-recent-properties-block',
            plugins_url('js/recent-properties.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-recent-properties-block-editor',
            plugins_url('css/recent-properties.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/recent-properties', array(
            'editor_script' => 'resideo-recent-properties-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_recent_properties_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_recent_properties_block');
?>