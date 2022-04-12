<?php
/**
 * Featured agents block
 */
if(!function_exists('resideo_featured_agents_block')): 
    function resideo_featured_agents_block() {
        wp_register_script(
            'resideo-featured-agents-block',
            plugins_url('js/featured-agents.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-featured-agents-block-editor',
            plugins_url('css/featured-agents.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/featured-agents', array(
            'editor_script' => 'resideo-featured-agents-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_featured_agents_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_featured_agents_block');
?>