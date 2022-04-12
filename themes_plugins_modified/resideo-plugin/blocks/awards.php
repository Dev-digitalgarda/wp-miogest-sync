<?php
/**
 * Awards block
 */
if (!function_exists('resideo_awards_block')): 
    function resideo_awards_block() {
        wp_register_script(
            'resideo-awards-block',
            plugins_url('js/awards.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-awards-block-editor',
            plugins_url('css/awards.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/awards', array(
            'editor_script' => 'resideo-awards-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_awards_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_awards_block');
?>