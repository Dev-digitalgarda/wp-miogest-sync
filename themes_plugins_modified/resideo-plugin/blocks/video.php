<?php
/**
 * Video block
 */
if(!function_exists('resideo_video_block')): 
    function resideo_video_block() {
        wp_register_script(
            'resideo-video-block',
            plugins_url('js/video.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n')
        );

        wp_enqueue_style(
            'resideo-video-block-editor',
            plugins_url('css/video.css', __FILE__),
            array('wp-edit-blocks')
        );

        register_block_type('resideo-plugin/video', array(
            'editor_script' => 'resideo-video-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'resideo_video_shortcode'
        ));
    }
endif;
add_action('init', 'resideo_video_block');
?>