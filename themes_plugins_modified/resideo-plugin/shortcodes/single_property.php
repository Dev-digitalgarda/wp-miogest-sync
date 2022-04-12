<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Single property shortcode
 */
if (!function_exists('resideo_single_property_shortcode')): 
    function resideo_single_property_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $cta_id = uniqid();

        $return_string = '';

        if (isset($s_array['id']) && $s_array['id'] != '') {
            $general_settings = get_option('resideo_general_settings');
            $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
            $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
            $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA'; 

            $currency     = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
            $currency_pos = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
            $locale       = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
            $decimals     = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
            setlocale(LC_MONETARY, $locale);

            $title       = get_the_title($s_array['id']);
            $price       = get_post_meta($s_array['id'], 'property_price', true);
            $price_label = get_post_meta($s_array['id'], 'property_price_label', true);

            if (is_numeric($price)) {
                if ($decimals == '1') {
                    $price = money_format('%!i', $price);
                } else {
                    $price = money_format('%!.0i', $price);
                }
            } else {
                $price_label = '';
                $currency = '';
            }

            $beds  = get_post_meta($s_array['id'], 'property_beds', true);
            $baths = get_post_meta($s_array['id'], 'property_baths', true);
            $size  = get_post_meta($s_array['id'], 'property_size', true);

            $gallery = get_post_meta($s_array['id'], 'property_gallery', true);
            $photos  = explode(',', $gallery);
            $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-full');

            $link = get_permalink($s_array['id']);

            $return_string .= 
                    '<div class="pxp-single-property ' . esc_attr($margin_class) . '">
                        <div class="row no-gutters align-items-center">';
            if ($s_array['position'] == 'right') {
                $return_string .= 
                            '<div class="col-7 col-sm-6">
                                <div class="pxp-single-property-caption">
                                    <h2 class="pxp-section-h2">' . esc_html($title) . '</h2>
                                    <div class="pxp-single-property-caption-features mt-4">';
                if ($beds != '') {
                    $return_string .= esc_html($beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                }
                if ($baths != '') {
                    $return_string .= esc_html($baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                }
                if ($size != '') {
                    $return_string .= esc_html($size) . ' ' . esc_html($unit);
                }
                $return_string .= 
                                    '</div>
                                    <div class="pxp-single-property-caption-price mt-5">';
                if ($currency_pos == 'before') {
                    $return_string .= esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                } else {
                    $return_string .= esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
                }
                $return_string .=
                                    '</div>
                                    <a href="' . esc_url($link) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . __('View Details', 'resideo') . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>
                                </div>
                            </div>
                            <div class="col-5 col-sm-6">';
                if ($first_photo != '') {
                    $return_string .=
                                '<div class="pxp-single-property-fig pxp-cover" style="background-image: url(' . esc_url($first_photo[0]) . ');"></div>';
                }
                $return_string .=
                            '</div>';
            } else {
                $return_string .= 
                            '<div class="col-5 col-sm-6">';
                if ($first_photo != '') {
                    $return_string .=
                                '<div class="pxp-single-property-fig pxp-cover" style="background-image: url(' . esc_url($first_photo[0]) . ');"></div>';
                }
                $return_string .=
                            '</div>
                            <div class="col-7 col-sm-6">
                                <div class="pxp-single-property-caption pxp-is-right">
                                    <h2 class="pxp-section-h2">' . esc_html($title) . '</h2>
                                    <div class="pxp-single-property-caption-features mt-4">';
                if ($beds != '') {
                    $return_string .= esc_html($beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                }
                if ($baths != '') {
                    $return_string .= esc_html($baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                }
                if ($size != '') {
                    $return_string .= esc_html($size) . ' ' . esc_html($unit);
                }
                $return_string .=
                                    '</div>
                                    <div class="pxp-single-property-caption-price mt-5">';
                if ($currency_pos == 'before') {
                    $return_string .= esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                } else {
                    $return_string .= esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
                }
                $return_string .=
                                    '</div>
                                    <a href="' . esc_url($link) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . __('View Details', 'resideo') . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>
                                </div>
                            </div>';
            }
            $return_string .= 
                        '</div>
                    </div>';
        }

        return $return_string;
    }
endif;
?>