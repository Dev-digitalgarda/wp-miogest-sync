<?php
/**
 * Contact block
 */
if(!function_exists('resideo_contact_block')): 
    function resideo_contact_block() {
        wp_register_script(
            'resideo-contact-block',
            plugins_url('js/contact.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-contact-block-editor',
            plugins_url('css/contact.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/contact', array(
            'editor_script' => 'resideo-contact-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_contact_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_contact_block');
?>