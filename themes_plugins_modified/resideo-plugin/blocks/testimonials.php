<?php
/**
 * Testimonials block
 */
if(!function_exists('resideo_testimonials_block')): 
    function resideo_testimonials_block() {
        wp_register_script(
            'resideo-testimonials-block',
            plugins_url('js/testimonials.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-testimonials-block-editor',
            plugins_url('css/testimonials.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/testimonials', array(
            'editor_script' => 'resideo-testimonials-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_testimonials_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_testimonials_block');
?>