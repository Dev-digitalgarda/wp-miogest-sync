<?php
/**
 * Search properties block
 */
if (!function_exists('resideo_search_properties_block')): 
    function resideo_search_properties_block() {
        wp_register_script(
            'resideo-search-properties-block',
            plugins_url('js/search-properties.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-search-properties-block-editor',
            plugins_url('css/search-properties.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/search-properties', array(
            'editor_script' => 'resideo-search-properties-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_search_properties_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_search_properties_block');
?>