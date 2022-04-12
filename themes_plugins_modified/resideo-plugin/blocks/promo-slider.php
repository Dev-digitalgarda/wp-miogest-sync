<?php
/**
 * Promo slider block
 */
if (!function_exists('resideo_promo_slider_block')): 
    function resideo_promo_slider_block() {
        wp_register_script(
            'resideo-promo-slider-block',
            plugins_url('js/promo-slider.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-promo-slider-block-editor',
            plugins_url('css/promo-slider.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/promo-slider', array(
            'editor_script' => 'resideo-promo-slider-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_slider_promo_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_promo_slider_block');
?>