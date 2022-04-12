<?php
/**
 * Gallery carousel block
 */
if (!function_exists('resideo_gallery_carousel_block')): 
    function resideo_gallery_carousel_block() {
        wp_register_script(
            'resideo-gallery-carousel-block',
            plugins_url('js/gallery-carousel.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-gallery-carousel-block-editor',
            plugins_url('css/gallery-carousel.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/gallery-carousel', array(
            'editor_script' => 'resideo-gallery-carousel-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_gallery_carousel_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_gallery_carousel_block');
?>