<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Promo shortcode
 */
if (!function_exists('resideo_promo_shortcode')): 
    function resideo_promo_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';
        $section_class = 'pb-300';
        $caption_class = '';

        $image  = isset($s_array['image']) ? $s_array['image'] : '';
        $image_position  = isset($s_array['image_position']) ? $s_array['image_position'] : 'left';

        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $cta_id = uniqid();

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

        $layout = isset($s_array['layout']) ? $s_array['layout'] : '1';
        $caption_image_isicon = isset($s_array['caption_image_isicon']) ? $s_array['caption_image_isicon'] : '';
        $caption_image = isset($s_array['caption_image']) ? $s_array['caption_image'] : '';
        $caption_image_color = isset($s_array['caption_image_color']) ? $s_array['caption_image_color'] : '';
        $caption_title = isset($s_array['caption_title']) ? $s_array['caption_title'] : '';
        $caption_text = isset($s_array['caption_text']) ? $s_array['caption_text'] : '';

        switch ($layout) {
            case '1':
                if ($image != '') {
                    $photo = wp_get_attachment_image_src($image, 'pxp-full');
                    $photo_src = $photo[0];
                } else {
                    $photo_src = '';
                }
                $return_string = 
                    '<div class="pxp-cta-1 pxp-cover ' . esc_attr($margin_class) . ' ' . esc_attr($section_class) . '" style="background-image: url(' . esc_url($photo_src) . ')">
                        <div class="container">
                            <div class="row ' . esc_attr($caption_class) . '">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="pxp-cta-1-caption pxp-animate-in">
                                        <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                                        <p class="pxp-text-light">' . esc_html($s_array['text']) . '</p>';
                if ($s_array['cta_text'] != '') {
                    $return_string .= 
                                        '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>
                                        <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $return_string .= 
                                    '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
            break;
            case '2':
                if ($image != '') {
                    $photo = wp_get_attachment_image_src($image, 'pxp-gallery');
                    $photo_src = $photo[0];
                } else {
                    $photo_src = '';
                }
                $return_string = 
                    '<div class="pxp-cta-2 ' . esc_attr($margin_class) . '">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <h2 class="pxp-section-h2 d-block d-lg-none">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light mt-3 mt-lg-4 d-block d-lg-none">' . esc_html($s_array['text']) . '</p>
                                    <div class="pxp-cta-2-left mt-4 mt-md-5 mt-lg-0">
                                        <div class="pxp-cta-2-left-image pxp-cover" style="background-image: url(' . esc_url($photo_src) . ');"></div>
                                        <div class="pxp-cta-2-left-content">
                                            <div class="pxp-cta-2-left-content-item">
                                                <div class="pxp-cta-2-left-content-item-fig">';
                if ($caption_image_isicon == '1') {
                    $return_string .= 
                                                    '<span class="' . esc_attr($caption_image) . '" style="color: ' . esc_attr($caption_image_color) . '"></span>';
                } else {
                    $caption_image_src = wp_get_attachment_image_src($caption_image, 'pxp-icon');
                    if ($caption_image_src != false) {
                        $return_string .= 
                                                    '<img src="' . esc_url($caption_image_src[0]) . '" alt="' . esc_attr($caption_title) . '" />';
                    }
                }
                $return_string .= 
                                                '</div>
                                                <div class="pxp-cta-2-left-content-item-title">' . esc_html($caption_title) . '</div>
                                                <div class="pxp-cta-2-left-content-item-text">' . esc_html($caption_text) . '</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-5">
                                    <h2 class="pxp-section-h2 d-none d-lg-block">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light mt-3 mt-lg-4 d-none d-lg-block">' . esc_html($s_array['text']) . '</p>';
                if ($s_array['cta_text'] != '') {
                    $return_string .= 
                                    '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $return_string .= 
                                '</div>
                            </div>
                        </div>
                    </div>';
            break;
            case '3':
                if ($image != '') {
                    $photo = wp_get_attachment_image_src($image, 'pxp-gallery');
                    $photo_src = $photo[0];
                } else {
                    $photo_src = '';
                }
                $return_string = 
                    '<div class="pxp-cta-3 ' . esc_attr($margin_class) . '">
                        <div class="container">
                            <div class="row align-items-center">';
                if ($image_position == 'left') {
                    $return_string .= 
                                '<div class="col-lg-5">
                                    <div class="pxp-cta-3-image pxp-cover rounded-lg" style="background-image: url(' . esc_url($photo_src) . ');"></div>
                                </div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4">
                                    <h2 class="pxp-section-h2 mt-3 mt-md-5 mt-lg-0">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light mt-3 mt-lg-4">' . esc_html($s_array['text']) . '</p>';
                    if ($s_array['cta_text'] != '') {
                        $return_string .= 
                                    '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                    }
                    $return_string .= 
                                '</div>
                                <div class="col-lg-1"></div>';
                } else {
                    $return_string .= 
                                '<div class="col-lg-1"></div>
                                <div class="col-lg-4">
                                    <h2 class="pxp-section-h2 mt-3 mt-md-5 mt-lg-0">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light mt-3 mt-lg-4">' . esc_html($s_array['text']) . '</p>';
                    if ($s_array['cta_text'] != '') {
                        $return_string .= 
                                    '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                    }
                    $return_string .= 
                                '</div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-5 order-first order-lg-last">
                                    <div class="pxp-cta-3-image pxp-cover rounded-lg" style="background-image: url(' . esc_url($photo_src) . ');"></div>
                                </div>';
                }
                $return_string .= 
                            '</div>
                        </div>
                    </div>';
            break;
            case '4':
                $return_string = 
                    '<div class="pxp-cta-4 mt-200 mb-200">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-lg-6">
                                    <div class="text-center">
                                        <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                                        <p class="pxp-text-light mt-3 mt-lg-4">' . esc_html($s_array['text']) . '</p>';
                if ($s_array['cta_text'] != '') {
                    $return_string .= 
                                        '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>
                                        <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $return_string .= 
                                    '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
            break;
            default:
                if ($image != '') {
                    $photo = wp_get_attachment_image_src($image, 'pxp-full');
                    $photo_src = $photo[0];
                } else {
                    $photo_src = '';
                }
                $return_string = 
                    '<div class="pxp-cta-1 pxp-cover ' . esc_attr($margin_class) . ' ' . esc_attr($section_class) . '" style="background-image: url(' . esc_url($photo_src) . ')">
                        <div class="container">
                            <div class="row ' . esc_attr($caption_class) . '">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="pxp-cta-1-caption pxp-animate-in">
                                        <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                                        <p class="pxp-text-light">' . esc_html($s_array['text']) . '</p>';
                if ($s_array['cta_text'] != '') {
                    $return_string .= 
                                        '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>
                                        <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $return_string .= 
                                    '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
            break;
        }

        return $return_string;
    }
endif;
?>