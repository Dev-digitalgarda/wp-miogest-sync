<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Areas shortcode
 */
if (!function_exists('resideo_areas_shortcode')): 
    function resideo_areas_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $results_page = resideo_get_search_properties_link();

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $cta_id = uniqid();

        $layout = isset($s_array['layout']) ? $s_array['layout'] : '1';

        switch ($layout) {
            case '1':
                $return_string = 
                    '<div class="container ' . esc_attr($margin_class) . '">
                        <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>

                        <div class="row mt-4 mt-md-5">';

                if (isset($s_array['areas']) && is_array($s_array['areas'])) {
                    foreach ($s_array['areas'] as $area) {

                        $image_src = wp_get_attachment_image_src($area['id'], 'pxp-gallery');

                        $area_link = add_query_arg(
                            array(
                                'search_neighborhood' => $area['neighborhood_id'] != '' ? $area['neighborhood_id'] : $area['neighborhood'],
                                'search_city'         => $area['city_id'] != '' ? $area['city_id'] : $area['city'],
                            ), $results_page
                        );

                        $properties_count = resideo_get_area_properties_no($area);
                        $area_cta_color = isset($area['cta_color']) ? $area['cta_color'] : '';

                        $city = $area['city'];
                        if ($area['city_id'] != '') {
                            if (function_exists('icl_translate')) {
                                $city = icl_translate('admin_texts_resideo_cities_settings', '[resideo_cities_settings][' . $area['city_id'] . ']name');
                            }
                        }

                        $neighborhood = $area['neighborhood'];
                        if ($area['neighborhood_id'] != '') {
                            if (function_exists('icl_translate')) {
                                $neighborhood = icl_translate('admin_texts_resideo_neighborhoods_settings', '[resideo_neighborhoods_settings][' . $area['neighborhood_id'] . ']name');
                            }
                        }

                        $return_string .= 
                            '<div class="col-sm-12 col-md-6 col-lg-4">
                                <a href="' . esc_url($area_link) . '" class="pxp-areas-1-item rounded-lg">
                                    <div class="pxp-areas-1-item-fig pxp-cover" style="background-image: url(' . esc_url($image_src[0]) . ');"></div>
                                    <div class="pxp-areas-1-item-details">
                                        <div class="pxp-areas-1-item-details-area">' . esc_html($neighborhood) . '</div>
                                        <div class="pxp-areas-1-item-details-city">' . esc_html($city) . '</div>
                                    </div>
                                    <div class="pxp-areas-1-item-counter"><span>' . esc_html($properties_count) . ' ' . __('Properties', 'resideo') . ' </span></div>
                                    <div class="pxp-areas-1-item-cta text-uppercase" style="color: ' . esc_attr($area_cta_color) . '">' . __('Explore', 'resideo') . '</div>
                                </a>
                            </div>';
                    }
                }

                $return_string .= 
                        '</div>';
                if ($s_array['cta_link'] != '') {
                    $return_string .= 
                        '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-4 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>
                        <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $return_string .= 
                    '</div>';
            break;
            case '2':
                $return_string = 
                    '<div class="' . esc_attr($margin_class) . '">
                        <h2 class="pxp-section-h2 text-center">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light text-center">' . esc_html($s_array['subtitle']) . '</p>
                        <div class="pxp-areas-carousel mt-4 mt-md-5">
                            <div class="pxp-areas-carousel-stage owl-carousel">';
                if (isset($s_array['areas']) && is_array($s_array['areas'])) {
                    foreach ($s_array['areas'] as $area) {

                        $image_src = wp_get_attachment_image_src($area['id'], 'pxp-gallery');

                        $area_link = add_query_arg(
                            array(
                                'search_neighborhood' => $area['neighborhood_id'] != '' ? $area['neighborhood_id'] : $area['neighborhood'],
                                'search_city'         => $area['city_id'] != '' ? $area['city_id'] : $area['city'],
                            ), $results_page
                        );

                        $properties_count = resideo_get_area_properties_no($area);
                        $area_cta_color = isset($area['cta_color']) ? $area['cta_color'] : '';

                        $city = $area['city'];
                        if ($area['city_id'] != '') {
                            if (function_exists('icl_translate')) {
                                $city = icl_translate('admin_texts_resideo_cities_settings', '[resideo_cities_settings][' . $area['city_id'] . ']name');
                            }
                        }

                        $neighborhood = $area['neighborhood'];
                        if ($area['neighborhood_id'] != '') {
                            if (function_exists('icl_translate')) {
                                $neighborhood = icl_translate('admin_texts_resideo_neighborhoods_settings', '[resideo_neighborhoods_settings][' . $area['neighborhood_id'] . ']name');
                            }
                        }

                        $return_string .= 
                                '<a href="' . esc_url($area_link) . '" class="pxp-areas-carousel-item">
                                    <div class="pxp-areas-carousel-item-fig-container">
                                        <div class="pxp-areas-carousel-item-fig pxp-cover" style="background-image: url(' . esc_url($image_src[0]) . ');"></div>
                                    </div>
                                    <div class="pxp-areas-carousel-item-details mt-1">
                                        <div class="pxp-areas-carousel-item-details-area">' . esc_html($neighborhood) . '</div>
                                        <div class="pxp-areas-carousel-item-details-city">' . esc_html($city) . '</div>
                                    </div>
                                    <div class="pxp-areas-carousel-item-counter"><span>' . esc_html($properties_count) . ' ' . __('Properties', 'resideo') . ' </span></div>
                                </a>';
                    }
                }
                $return_string .= 
                            '</div>
                        </div>';
                if ($s_array['cta_link'] != '') {
                    $return_string .= 
                        '<div class="mt-4 mt-md-5 text-center">
                            <a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>
                            <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>
                        </div>';
                }
                $return_string .= 
                    '</div>';
            break;
            default:
                $return_string = 
                    '<div class="container ' . esc_attr($margin_class) . '">
                        <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>

                        <div class="row mt-4 mt-md-5">';

                if (isset($s_array['areas']) && is_array($s_array['areas'])) {
                    foreach ($s_array['areas'] as $area) {

                        $image_src = wp_get_attachment_image_src($area['id'], 'pxp-gallery');

                        $area_link = add_query_arg(
                            array(
                                'search_neighborhood' => $area['neighborhood_id'] != '' ? $area['neighborhood_id'] : $area['neighborhood'],
                                'search_city'         => $area['city_id'] != '' ? $area['city_id'] : $area['city'],
                            ), $results_page
                        );

                        $properties_count = resideo_get_area_properties_no($area);
                        $area_cta_color = isset($area['cta_color']) ? $area['cta_color'] : '';

                        $city = $area['city'];
                        if ($area['city_id'] != '') {
                            if (function_exists('icl_translate')) {
                                $city = icl_translate('admin_texts_resideo_cities_settings', '[resideo_cities_settings][' . $area['city_id'] . ']name');
                            }
                        }

                        $neighborhood = $area['neighborhood'];
                        if ($area['neighborhood_id'] != '') {
                            if (function_exists('icl_translate')) {
                                $neighborhood = icl_translate('admin_texts_resideo_neighborhoods_settings', '[resideo_neighborhoods_settings][' . $area['neighborhood_id'] . ']name');
                            }
                        }

                        $return_string .= 
                            '<div class="col-sm-12 col-md-6 col-lg-4">
                                <a href="' . esc_url($area_link) . '" class="pxp-areas-1-item rounded-lg">
                                    <div class="pxp-areas-1-item-fig pxp-cover" style="background-image: url(' . esc_url($image_src[0]) . ');"></div>
                                    <div class="pxp-areas-1-item-details">
                                        <div class="pxp-areas-1-item-details-area">' . esc_html($neighborhood) . '</div>
                                        <div class="pxp-areas-1-item-details-city">' . esc_html($city) . '</div>
                                    </div>
                                    <div class="pxp-areas-1-item-counter"><span>' . esc_html($properties_count) . ' ' . __('Properties', 'resideo') . ' </span></div>
                                    <div class="pxp-areas-1-item-cta text-uppercase" style="color: ' . esc_attr($area_cta_color) . '">' . __('Explore', 'resideo') . '</div>
                                </a>
                            </div>';
                    }
                }

                $return_string .= 
                        '</div>';
                if ($s_array['cta_link'] != '') {
                    $return_string .= 
                        '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-4 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>
                        <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $return_string .= 
                    '</div>';
            break;
        }

        

        return $return_string;
    }
endif;

/**
 * Get properties number per area
 */
if (!function_exists('resideo_get_area_properties_no')): 
    function resideo_get_area_properties_no($area) {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'property',
            'post_status'      => 'publish',
            'suppress_filters' => false,
        );

        $args['meta_query'] = array('relation' => 'AND');

        if ($area['city_id'] == '') {
            if ($area['city'] != '') {
                array_push($args['meta_query'], array(
                    'key'   => 'locality',
                    'value' => $area['city'],
                ));
            }
        } else {
            array_push($args['meta_query'], array(
                'key'   => 'locality',
                'value' => $area['city_id'],
            ));
        }

        if ($area['neighborhood_id'] == '') {
            if ($area['neighborhood'] != '') {
                array_push($args['meta_query'], array(
                    'key'   => 'neighborhood',
                    'value' => $area['neighborhood'],
                ));
            }
        } else {
            array_push($args['meta_query'], array(
                'key'   => 'neighborhood',
                'value' => $area['neighborhood_id'],
            ));
        }

        $query = new WP_Query($args);

        wp_reset_postdata();
        wp_reset_query();

        return $query->found_posts;
    }
endif;
?>