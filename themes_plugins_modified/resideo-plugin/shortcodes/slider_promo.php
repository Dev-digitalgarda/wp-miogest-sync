<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Promo slider shortcode
 */
if (!function_exists('resideo_slider_promo_shortcode')): 
    function resideo_slider_promo_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';
        $section_class = 'pb-300';
        $caption_class = '';

        $ctas_color = isset($s_array['ctas_color']) ? $s_array['ctas_color'] : '';
        $uniq_id = uniqid();

        $interval   = isset($s_array['interval']) ? $s_array['interval'] : '';

        $data_interval = 'false';
        if ($interval != '' && $interval != '0') {
            $data_interval = intval($interval) * 1000;
        }

        switch ($s_array['position']) {
            case 'topLeft':
                $section_class = 'pb-300';
                $caption_class = '';
            break;
            case 'topRight':
                $section_class = 'pb-300';
                $caption_class = 'justify-content-end';
            break;
            case 'centerLeft':
                $section_class = 'pt-200 pb-200';
                $caption_class = '';
            break;
            case 'center':
                $section_class = 'pt-200 pb-200';
                $caption_class = 'justify-content-center';
            break;
            case 'centerRight':
                $section_class = 'pt-200 pb-200';
                $caption_class = 'justify-content-end';
            break;
            case 'bottomLeft':
                $section_class = 'pt-300';
                $caption_class = '';
            break;
            case 'bottomRight':
                $section_class = 'pt-300';
                $caption_class = 'justify-content-end';
            break;
            default:
                $section_class = 'pb-300';
                $caption_class = '';
            break;
        }

        $return_string = 
            '<div class="pxp-promo-slider ' . esc_attr($margin_class) . ' ' . esc_attr($section_class) .'">
                <div id="pxp-promo-slider-carousel-' . esc_attr($uniq_id) . '" class="carousel slide pxp-promo-slider-carousel" data-ride="carousel" data-pause="false" data-interval="' . esc_attr($data_interval) . '">
                    <div class="carousel-inner">';
        $count = 0;
        foreach ($s_array['slides'] as $slide) {
            $active_slide = '';
            if ($count == 0) {
                $active_slide = 'active';
            }
            $image_src = wp_get_attachment_image_src($slide['value'], 'pxp-full');
            $return_string .= 
                        '<div class="carousel-item ' . esc_attr($active_slide) . ' pxp-cover" style="background-image: url(' . esc_url($image_src[0]) . ');"></div>';
            $count++;
        }
        $return_string .= 
                    '</div>
                </div>

                <div class="container">
                    <div class="row ' . esc_attr($caption_class) . '">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="pxp-promo-slider-caption pxp-animate-in">';
        $count_captions = 0;
        foreach ($s_array['slides'] as $caption) {
            $active_caption = '';
            if ($count_captions == 0) {
                $active_caption = 'pxp-active';
            }
            $return_string .= 
                                '<div class="pxp-promo-slider-caption-item ' . esc_attr($active_caption) . '" data-index="' . esc_attr($count_captions) . '">
                                    <h2 class="pxp-section-h2">' . esc_html($caption['title']) . '</h2>
                                    <p class="pxp-text-light">' . esc_html($caption['text']) . '</p>';
            if ($caption['cta_text'] != '') {
                $return_string .= 
                                    '<a href="' . esc_url($caption['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-4 pxp-animate" id="cta-' . esc_attr($uniq_id) . esc_attr($count_captions) .  '" style="color: ' . esc_attr($ctas_color) . '">' . esc_html($caption['cta_text']) . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($uniq_id) . esc_attr($count_captions) . ':after { border-top: 2px solid ' . esc_html($ctas_color) . '; }</style>';
            }
            $return_string .= 
                                '</div>';
            $count_captions++;
        }
        $return_string .= 
                                '<ol class="pxp-promo-slider-caption-dots mt-3 mt-md-4">';
        $count_dots = 0;
        foreach ($s_array['slides'] as $dot) {
            $active_dot = '';
            if ($count_dots == 0) {
                $active_dot = 'active';
            }
            $return_string .= 
                                    '<li data-target="#pxp-promo-slider-carousel-' . esc_attr($uniq_id) . '" data-slide-to="' . esc_attr($count_dots) . '" class="' . esc_attr($active_dot) . '"><div></div></li>';
            $count_dots++;
        }
        $return_string .= 
                                '</ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        return $return_string;
    }
endif;
?>