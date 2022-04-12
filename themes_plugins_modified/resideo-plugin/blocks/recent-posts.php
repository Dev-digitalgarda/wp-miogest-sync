<?php
/**
 * Recent posts block
 */
if (!function_exists('resideo_recent_posts_block')): 
    function resideo_recent_posts_block() {
        wp_register_script(
            'resideo-recent-posts-block',
            plugins_url('js/recent-posts.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-recent-posts-block-editor',
            plugins_url('css/recent-posts.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/recent-posts', array(
            'editor_script' => 'resideo-recent-posts-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_recent_posts_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_recent_posts_block');
?>