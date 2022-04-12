<?php
/**
 * Areas block
 */
if (!function_exists('resideo_areas_block')): 
    function resideo_areas_block() {
        wp_register_script(
            'resideo-areas-block',
            plugins_url('js/areas.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-areas-block-editor',
            plugins_url('css/areas.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/areas', array(
            'editor_script' => 'resideo-areas-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_areas_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_areas_block');
?>