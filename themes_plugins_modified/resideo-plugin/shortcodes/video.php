<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Video shortcode
 */
if (!function_exists('resideo_video_shortcode')): 
    function resideo_video_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $image  = isset($s_array['image']) ? $s_array['image'] : '';
        if ($image != '') {
            $photo = wp_get_attachment_image_src($image, 'pxp-full');
            $photo_src = $photo[0];
        } else {
            $photo_src = '';
        }

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $text_color = isset($s_array['text_color']) ? 'color: ' . $s_array['text_color'] : '';
        $video_id = isset($s_array['video']) ? $s_array['video'] : '';
        $modal_id = uniqid();

        $return_string =  
            '<div class="pxp-video-section pxp-cover pt-100 pb-100 ' . esc_attr($margin_class) . '" style="background-image: url(' . esc_url($photo_src) . ');">
                <div class="container">
                    <h2 class="pxp-section-h2 text-center" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                    <p class="pxp-text-light text-center" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                    <div class="pt-100 pb-100 text-center">
                        <a href="javascript:void(0)" class="pxp-video-section-trigger" data-toggle="modal" data-target="#pxp-video-section-modal-' . esc_attr($modal_id) . '">
                            <span class="fa fa-play"></span>
                        </a>
                    </div>
                </div>

                <div class="pxp-video-section-modal modal" id="pxp-video-section-modal-' . esc_attr($modal_id) . '" data-id="' . esc_attr($video_id) . '" tabindex="-1" aria-labelledby="pxp-video-section-modal-' . esc_attr($modal_id) . '" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="pxp-video-section-modal-container" id="pxp-video-section-modal-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        return $return_string;
    }
endif;
?>