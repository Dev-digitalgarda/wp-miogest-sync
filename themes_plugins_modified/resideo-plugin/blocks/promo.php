<?php
/**
 * Promo block
 */
if (!function_exists('resideo_promo_block')): 
    function resideo_promo_block() {
        wp_register_script(
            'resideo-promo-block',
            plugins_url('js/promo.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-promo-block-editor',
            plugins_url('css/promo.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/promo', array(
            'editor_script' => 'resideo-promo-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_promo_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_promo_block');
?>