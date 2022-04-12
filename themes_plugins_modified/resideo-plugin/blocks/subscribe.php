<?php
/**
 * Subscribe block
 */
if(!function_exists('resideo_subscribe_block')): 
    function resideo_subscribe_block() {
        wp_register_script(
            'resideo-subscribe-block',
            plugins_url('js/subscribe.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-subscribe-block-editor',
            plugins_url('css/subscribe.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/subscribe', array(
            'editor_script' => 'resideo-subscribe-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_subscribe_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_subscribe_block');
?>