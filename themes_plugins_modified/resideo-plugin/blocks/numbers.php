<?php
/**
 * Numbers block
 */
if (!function_exists('resideo_numbers_block')): 
    function resideo_numbers_block() {
        wp_register_script(
            'resideo-numbers-block',
            plugins_url('js/numbers.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-numbers-block-editor',
            plugins_url('css/numbers.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/numbers', array(
            'editor_script' => 'resideo-numbers-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_numbers_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_numbers_block');
?>