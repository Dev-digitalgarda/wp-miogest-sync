<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Gallery carousel shortcode
 */
if (!function_exists('resideo_gallery_carousel_shortcode')): 
    function resideo_gallery_carousel_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $return_string = '
            <div class="pxp-gallery-carousel mt-100">
                <div class="owl-carousel pxp-gallery-carousel-stage">';
        foreach ($s_array['photos'] as $photo) {
            $image_src = wp_get_attachment_image_src($photo['value'], 'pxp-full');
            if ($image_src != false) {
                $return_string .= '
                    <div>
                        <div class="pxp-gallery-carousel-item pxp-cover rounded-lg" style="background-image: url(' . esc_url($image_src[0]) . ');"></div>
                    </div>';
            }
        }
        $return_string .= '
                </div>
            </div>';

        return $return_string;
    }
endif;
?>