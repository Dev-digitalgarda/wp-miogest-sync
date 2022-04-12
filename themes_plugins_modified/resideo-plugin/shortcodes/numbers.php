<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Numbers shortcode
 */
if (!function_exists('resideo_numbers_shortcode')): 
    function resideo_numbers_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $return_string = '';

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $bg_image = wp_get_attachment_image_src($s_array['image'], 'pxp-full');
        $bg_image_src = '';
        if ($bg_image != false) {
            $bg_image_src = $bg_image[0];
        }

        if ($bg_image_src == '') {
            $return_string .= 
                '<div class="pxp-numbers pt-100 pb-100 ' . esc_attr($margin_class) . '">
                    <div class="container">
                        <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>
                        <div class="row">';
            foreach ($s_array['numbers'] as $number) {
                $return_string .= 
                            '<div class="col-sm-4">
                                <div class="pxp-numbers-item mt-4 mt-md-5">
                                    <div class="pxp-numbers-item-number"><span class="numscroller" data-min="0" data-max="' . esc_attr($number['sum']) . '" data-delay="' . esc_attr($number['delay']) . '" data-increment="' . esc_attr($number['increment']) . '"></span>' . esc_html($number['sign']) . '</div>
                                    <div class="pxp-numbers-item-title">' . esc_html($number['title']) . '</div>
                                    <div class="pxp-numbers-item-text pxp-text-light">' . esc_html($number['text']) . '</div>
                                </div>
                            </div>';
            }
            $return_string .=
                        '</div>
                    </div>
                </div>';
        } else {
            $return_string .= 
                '<div class="pxp-numbers-fig pxp-cover pt-400 ' . esc_attr($margin_class) . '" style="background-image: url(' . esc_url($bg_image_src) . ');">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="pxp-numbers-fig-caption">
                                    <div class="row">';
            foreach ($s_array['numbers'] as $number) {
                $return_string .= 
                                        '<div class="col-4">
                                            <div class="pxp-counters-fig-item">
                                                <div class="pxp-numbers-item-number"><span class="numscroller" data-min="0" data-max="' . esc_attr($number['sum']) . '" data-delay="' . esc_attr($number['delay']) . '" data-increment="' . esc_attr($number['increment']) . '"></span>' . esc_html($number['sign']) . '</div>
                                                <div class="pxp-numbers-item-title">' . esc_html($number['title']) . '</div>
                                                <div class="pxp-numbers-item-text pxp-text-light">' . esc_html($number['text']) . '</div>
                                            </div>
                                        </div>';
            }
            $return_string .=
                                    '</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        return $return_string;
    }
endif;
?>