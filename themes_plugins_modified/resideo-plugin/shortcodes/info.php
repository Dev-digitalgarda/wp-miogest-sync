<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Info shortcode
 */
if (!function_exists('resideo_info_shortcode')): 
    function resideo_info_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $margin_class = '';
        if ($s_array['margin'] == 'yes') {
            $margin_class = 'mb-60';
        }

        $width_class = '';
        if ($s_array['width'] == 'wide') {
            $width_class = 'extendFull';
        }

        $align_class = '';
        if ($s_array['align'] == 'left') {
            $align_class = 'is-left-align';
        }
        if ($s_array['align'] == 'right') {
            $align_class = 'is-right-align';
        }

        $return_string = '
            <div class="spotlight-short custom-height ' . esc_attr($width_class) . ' ' . esc_attr($margin_class) . '" data-height="' . esc_attr($s_array['height']) . '">
                <div class="spotlight-short-bg"';
        if ($s_array['image'] != '') {
            $photo = wp_get_attachment_image_src($s_array['image'], 'pxp-full');
            $return_string .= ' style="background-image: url(' . esc_url($photo[0]) . ');"';
        }
        $return_string .= '>';
        if ($s_array['color'] != '') {
            $return_string .= '
                    <div class="spotlight-short-bg-shadow" style="background: ' . esc_attr($s_array['color']) . '; opacity: ' . esc_attr($s_array['opacity']) . ';"></div>';
        }
        $return_string .= '
                    <div class="spotlight-short-caption ' . esc_attr($align_class) . '">
                        <div class="row">';
        if ($s_array['align'] == 'left') {
            $return_string .= '
                            <div class="col-xs-12 col-sm-6 col-md-8">';
            if ($s_array['title'] != '') {
                $return_string .= '
                                <h2 class="centered playfair">' . esc_html($s_array['title']) . '</h2>';
            }
            if ($s_array['subtitle'] != '') {
                $return_string .= '
                                <p class="centered">' . esc_html($s_array['subtitle']) . '</p>';
            }
            if ($s_array['cta_text'] != '') {
                $return_string .= '
                                <div class="spotlight-short-caption-cta"><a href="' . esc_url($s_array['cta_link']) . '" class="btn btn-lg">' . esc_html($s_array['cta_text']) . '</a></div>';
            }
            $return_string .= '
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4"></div>';
        } else if ($s_array['align'] == 'right') {
            $return_string .= '
                            <div class="col-xs-12 col-sm-6 col-md-4"></div>
                            <div class="col-xs-12 col-sm-6 col-md-8">';
            if ($s_array['title'] != '') {
                $return_string .= '
                                <h2 class="centered playfair">' . esc_html($s_array['title']) . '</h2>';
            }
            if ($s_array['subtitle'] != '') {
                $return_string .= '
                                <p class="centered">' . esc_html($s_array['subtitle']) . '</p>';
            }
            if ($s_array['cta_text'] != '') {
                $return_string .= '
                                <div class="spotlight-short-caption-cta"><a href="' . esc_url($s_array['cta_link']) . '" class="btn btn-lg">' . esc_html($s_array['cta_text']) . '</a></div>';
            }
            $return_string .= '
                            </div>';
        } else {
            $return_string .= '
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-6">';
            if ($s_array['title'] != '') {
                $return_string .= '
                                <h2 class="centered playfair">' . esc_html($s_array['title']) . '</h2>';
            }
            if ($s_array['subtitle'] != '') {
                $return_string .= '
                                <p class="centered">' . esc_html($s_array['subtitle']) . '</p>';
            }
            if ($s_array['cta_text'] != '') {
                $return_string .= '
                                <div class="spotlight-short-caption-cta"><a href="' . esc_url($s_array['cta_link']) . '" class="btn btn-lg">' . esc_html($s_array['cta_text']) . '</a></div>';
            }
            $return_string .= '
                            </div>
                            <div class="col-xs-12 col-sm-3"></div>';
        }
        $return_string .=
                        '</div>
                    </div>
                </div>
            </div>';

        return $return_string;
    }
endif;
?>