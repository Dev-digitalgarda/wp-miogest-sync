<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Awards shortcode
 */
if (!function_exists('resideo_awards_shortcode')): 
    function resideo_awards_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $return_string = 
            '<div class="pxp-awards ' . esc_attr($margin_class) . '">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                            <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-6">';
        if ($s_array['text'] != '') {
            $return_string .= 
                            '<p class="pxp-text-light mb-4">' . esc_html($s_array['text']) . '</p>';
        }
        $return_string .= 
                            '<div class="row">';
        foreach ($s_array['awards'] as $award) {
            $image_src = wp_get_attachment_image_src($award['value'], 'pxp-gallery');
            $return_string .= 
                                '<div class="col-sm-4">
                                    <div class="pxp-awards-item">';
            if ($image_src != false) {
                $return_string .=
                                        '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_attr($award['title']) . '">';
            }
            $return_string .= 
                                        '<p class="pxp-awards-item-title">' . esc_html($award['title']) . '</p>
                                    </div>
                                </div>';
        }
        $return_string .=
                            '</div>
                        </div>
                    </div>
                </div>
            </div>';

        return $return_string;
    }
endif;
?>