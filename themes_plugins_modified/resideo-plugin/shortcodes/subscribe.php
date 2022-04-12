<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Subscribe shortcode
 */
if (!function_exists('resideo_subscribe_shortcode')): 
    function resideo_subscribe_shortcode($attrs, $content = null) {
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
        $cta_color = isset($s_array['text_color']) ? $s_array['text_color'] : '';

        $nonce_field = wp_nonce_field('subscribe_ajax_nonce', 'security-subscribe', true, false);

        $return_string =  
            '<div class="pxp-subscribe-section pxp-full pxp-cover pt-100 pb-100 ' . esc_attr($margin_class) . '" style="background-image: url(' . esc_url($photo_src) . ');">
                <div class="container">
                    <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                    <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                    <div class="row mt-4 mt-md-5">
                        <div class="col-sm-12 col-md-6">
                            <div class="pxp-subscribe-1-form" id="pxp-subscribe-form">'
                                . $nonce_field .
                                '<div class="pxp-subscribe-form-response"></div>
                                <input type="text" id="pxp-subscribe-email" name="pxp-subscribe-email" class="form-control" placeholder="' . __('Enter your email...', 'resideo') . '">
                                <a href="javascript:void(0);" id="pxp-subscribe-form-btn" class="pxp-primary-cta text-uppercase pxp-animate mt-3 mt-md-4" style="color: ' . esc_attr($cta_color) . '"><img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg') . '" class="pxp-loader pxp-is-btn" alt="..."> ' . __('Subscribe', 'resideo') . '</a>';
        if ($cta_color != '') {
            $return_string .= 
                                '<style>.pxp-primary-cta#pxp-subscribe-form-btn:after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
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