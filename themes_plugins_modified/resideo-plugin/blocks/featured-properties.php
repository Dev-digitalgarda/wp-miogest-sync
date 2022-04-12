<?php
/**
 * Featured properties block
 */
if (!function_exists('resideo_featured_properties_block')): 
    function resideo_featured_properties_block() {
        wp_register_script(
            'resideo-featured-properties-block',
            plugins_url('js/featured-properties.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-featured-properties-block-editor',
            plugins_url('css/featured-properties.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/featured-properties', array(
            'editor_script' => 'resideo-featured-properties-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_featured_properties_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_featured_properties_block');
?>