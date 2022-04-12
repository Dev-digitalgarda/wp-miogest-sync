<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Properties shortcode
 */
if (!function_exists('resideo_properties_shortcode')): 
    function resideo_properties_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $title      = isset($s_array['title']) ? $s_array['title'] : '';
        $width      = isset($s_array['width']) ? $s_array['width'] : 'wide';
        $opacity    = isset($s_array['opacity']) ? $s_array['opacity'] : '0';
        $auto       = isset($s_array['autoslide']) ? $s_array['autoslide'] : 'no';
        $interval   = isset($s_array['interval']) ? $s_array['interval'] : '';
        $transition = isset($s_array['transition']) ? $s_array['transition'] : 'slide';
        $margin     = isset($s_array['margin']) ? $s_array['margin'] : 'no';
        $properties = isset($s_array['properties']) ? $s_array['properties'] : '';

        $resideo_general_settings = get_option('resideo_general_settings');

        if ($interval == '') {
            $data_interval = 6000;
        } else {
            $data_interval = intval($interval) * 1000;
        }

        if ($auto == 'no') {
            $data_interval = 0;
        }

        $wide_class = 'is-boxed';
        if ($width == 'wide') {
            $wide_class = 'extendFull';
        }

        $margin_class = '';
        if ($margin == 'yes') {
            $margin_class = 'mb-60';
        }

        $p_ids = array();
        $carousel_hash = '';

        if ($properties != '' && is_array($properties)) {
            foreach ($properties as $property) {
                array_push($p_ids, intval($property['id']));
                $carousel_hash .= $property['id'];
            }
        }

        if (is_array($p_ids) && count($p_ids) > 0) {
            $slider_obj = resideo_get_page_header_slider_properties($p_ids);
        } else {
            $slider_obj = array();
        }

        $transition_class = '';
        if ($transition == 'fade') {
            $transition_class = 'carousel-fade';
        }

        $return_string = '
            <div id="properties-carousel--' . $carousel_hash . '" class="carousel properties-carousel ' . esc_attr($transition_class) . ' ' . esc_attr($wide_class) . ' ' . $margin_class . ' slide" data-ride="carousel" data-interval="' . esc_attr($data_interval) . '">
                <ol class="carousel-indicators">';
        $count = 0;
        foreach ($slider_obj as $obj) {
            if ($count == 0) { 
                $slide_active = 'active';
            } else {
                $slide_active = '';
            }
            $return_string .= '
                    <li data-target="#properties-carousel--' . $carousel_hash . '" data-slide-to="' . esc_attr($count) . '" class="' . esc_attr($slide_active) . '"></li>' . PHP_EOL;
            $count++;
        }
        $return_string .= '
                </ol>
                <div class="carousel-inner">
                    <h2 class="carousel-title centered playfair">' . esc_html($title) . '</h2>';
        $unit = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : '';

        $currency = isset($resideo_general_settings['resideo_currency_symbol_field']) ? $resideo_general_settings['resideo_currency_symbol_field'] : '';
        $currency_pos = isset($resideo_general_settings['resideo_currency_symbol_pos_field']) ? $resideo_general_settings['resideo_currency_symbol_pos_field'] : '';

        $locale = isset($resideo_general_settings['resideo_locale_field']) ? $resideo_general_settings['resideo_locale_field'] : '';
        $decimals = isset($resideo_general_settings['resideo_decimals_field']) ? $resideo_general_settings['resideo_decimals_field'] : '';
        setlocale(LC_MONETARY, $locale);

        $count = 0;

        foreach ($slider_obj as $obj) {
            if ($count == 0) { 
                $slide_active = ' active';
            } else {
                $slide_active = '';
            }

            $return_string .= '
                    <div class="item' . $slide_active . '" style="background-image: url(' . esc_url($obj->photo[0]) . ')">
                        <div class="container">
                            <div class="hero-shadow" style="background: rgba(0,0,0,' . esc_attr($opacity) . ');"></div>
                            <div class="carousel-caption">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-rtl">';
            if ($obj->type != '') {
                $return_string .= '
                                        <div class="carousel-caption-propertyType visible-xs">' . esc_html($obj->type) . '</div>';
            }
            $return_string .= '
                                        <div class="carousel-caption-propertyTitle playfair">' . esc_html($obj->title) . '</div>
                                        <div class="carousel-caption-propertyAddress">' . esc_html($obj->address) . '</div>';
            if ($obj->beds != '' || $obj->baths != '' || $obj->size != '') {
                $return_string .= '
                                        <ul class="carousel-caption-propertyFeatures">';
            if ($obj->beds != '') {
                $return_string .= '
                                            <li><span class="fa fa-moon-o"></span> ' . esc_html($obj->beds) . '</li>';
            }
            if ($obj->baths != '') {
                $return_string .= '
                                            <li><span class="icon-drop"></span> ' . esc_html($obj->baths) . '</li>';
            }
            if ($obj->size != '') {
                $return_string .= '
                                            <li><span class="icon-frame"></span> ' . esc_html($obj->size) . ' ' . esc_html($unit) . '</li>';
            }
                $return_string .= '
                                        </ul>';
            }
            if ($obj->status != '') {
                $return_string .= '
                                        <div class="carousel-caption-propertyStatus label visible-xs" style="background-color: ' . esc_attr($obj->status_color) . ';">' . esc_html($obj->status) . '</div>';
            }

            $price = $obj->price;
            $price_label = $obj->price_label;

            if (is_numeric($price)) {
                if ($decimals == 1) {
                    $price = money_format('%!i', $price);
                } else {
                    $price = money_format('%!.0i', $price);
                }
            } else {
                $price_label = '';
                $currency = '';
            }

            $return_string .= '
                                        <div class="carousel-caption-propertyPrice visible-xs">';
            if ($currency_pos == 'before') {
                $return_string .= esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
            } else {
                $return_string .= esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
            }
            $return_string .= '
                                        </div>
                                        <div><a href="' . esc_url($obj->link) . '" class="btn btn-lg carousel-caption-propertyBtn">' . __('View Details', 'resideo') . '</a></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-rtl align-right hidden-xs">';
            if ($obj->status != '') {
                $return_string .= '
                                        <div class="carousel-caption-propertyStatus label" style="background-color: ' . esc_attr($obj->status_color) . ';">' . esc_html($obj->status) . '</div>';
            } else {
                $return_string .= '
                                        <div style="height: 26px;"></div>';
            }
            if ($obj->type != '') {
                $return_string .= '
                                        <div class="carousel-caption-propertyType">' . esc_html($obj->type) . '</div>';
            } else {
                $return_string .= '
                                        <div style="height: 101px;"></div>';
            }
            $return_string .= '
                                        <div class="carousel-caption-propertyPrice">';
            if ($currency_pos == 'before') {
                $return_string .= esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
            } else {
                $return_string .= esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
            }
            $return_string .= '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

            $count++;
        }

        $return_string .= '
                </div>
                <a class="left carousel-control" href="#properties-carousel--' . $carousel_hash . '" role="button" data-slide="prev"><span class="fa fa-angle-left"></span></a>
                <a class="right carousel-control" href="#properties-carousel--' . $carousel_hash . '" role="button" data-slide="next"><span class="fa fa-angle-right"></span></a>
            </div>';

        return $return_string;
    }
endif;
?>