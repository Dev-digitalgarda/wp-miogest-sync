<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Services shortcode
 */
if (!function_exists('resideo_services_shortcode')): 
    function resideo_services_shortcode($attrs, $content = null) {
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

        $text_color = isset($s_array['text_color']) ? 'color: ' . $s_array['text_color'] : '';
        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $cta_id = uniqid();

        switch ($s_array['layout']) {
            case '1':
                $return_string = '
                    <div class="pxp-services pxp-cover pt-100 mb-200 ' . esc_attr($margin_class) . '" style="background-image: url(' . esc_url($bg_image_src) . ');">
                        <h2 class="text-center pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light text-center" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>

                        <div class="container">
                            <div class="pxp-services-container rounded-lg mt-4 mt-md-5">';
                foreach ($s_array['services'] as $service) {
                    if ($service['link'] != '') {
                        $return_string .= 
                                '<a href="' . esc_url($service['link']) . '" class="pxp-services-item">';
                    } else {
                        $return_string .= 
                                '<div class="pxp-services-item">';
                    }
                    $return_string .= 
                                    '<div class="pxp-services-item-fig">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                        '<span class="' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                        '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_html($service['title']) . '" />';
                        }
                    }
                    $service_cta_color = isset($service['ctacolor']) ? $service['ctacolor'] : '';
                    $return_string .= 
                                    '</div>
                                    <div class="pxp-services-item-text text-center">
                                        <div class="pxp-services-item-text-title">' . esc_html($service['title']) . '</div>
                                        <div class="pxp-services-item-text-sub">' . esc_html($service['text']) . '</div>
                                    </div>
                                    <div class="pxp-services-item-cta text-uppercase text-center" style="color: ' . esc_attr($service_cta_color) . '">' . esc_html($service['cta']) . '</div>';
                    if ($service['link'] != '') {
                        $return_string .= 
                                '</a>';
                    } else {
                        $return_string .= 
                                '</div>';
                    }
                }
                $return_string .= 
                                '<div class="clearfix"></div>
                            </div>
                        </div>
                    </div>';
            break;
            case '2':
                $item_margin = '';
                $return_string = 
                    '<div class="pxp-services-h pt-100 pb-100 ' . esc_attr($margin_class) . '">
                        <div class="container">
                            <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                            <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>

                            <div class="pxp-services-h-container mt-4 mt-md-5">
                                <div class="pxp-services-h-fig pxp-cover pxp-animate-in rounded-lg" style="background-image: url(' . esc_url($bg_image_src) . ');"></div>
                                <div class="pxp-services-h-items pxp-animate-in ml-0 ml-lg-5 mt-4 mt-md-5 mt-lg-0">';
                $service_i = 0;
                foreach ($s_array['services'] as $service) {
                    if ($service_i > 0) {
                        $item_margin = 'mt-3 mt-md-4';
                    }
                    $return_string .= 
                                    '<div class="pxp-services-h-item ' . esc_attr($item_margin) . '">
                                        <div class="media">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                            '<span class="mr-4 ' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                            '<img src="' . esc_url($image_src[0]) . '" class="mr-4" alt="' . esc_attr($service['title']) . '" />';
                        }
                    }
                    $return_string .= 
                                            '<div class="media-body">
                                                <h5 class="mt-0">' . esc_attr($service['title']) . '</h5>
                                                ' . esc_html($service['text']) . '
                                            </div>
                                        </div>
                                    </div>';
                    $service_i++;
                }
                if ($s_array['cta_link'] != '') {
                    $return_string .= 
                                    '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate pxp-animate-in" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>';
                    if ($cta_color != '') {
                        $return_string .= 
                                    '<style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                    }
                }
                $return_string .= 
                                '</div>
                            </div>
                        </div>
                    </div>';
            break;
            case '3':
                $return_string = 
                    '<div class="pt-100 pb-100 position-relative ' . esc_attr($margin_class) . '">
                        <div class="pxp-services-c pxp-cover" style="background-image: url(' . esc_url($bg_image_src) . ');"></div>
                        <div class="pxp-services-c-content">
                            <div class="pxp-services-c-intro">
                                <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>';
                if ($s_array['cta_link'] != '') {
                    $return_string .= 
                                '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>';
                    if ($cta_color != '') {
                        $return_string .= 
                                '<style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                    }
                }
                $return_string .=
                            '</div>
                            <div class="pxp-services-c-container mt-4 mt-md-5 mt-lg-0">
                                <div class="owl-carousel pxp-services-c-stage">';
                foreach ($s_array['services'] as $service) {
                    $return_string .=
                                    '<div>';
                    if ($service['link'] != '') {
                        $return_string .=
                                        '<a href="' . esc_url($service['link']) . '" class="pxp-services-c-item">';
                    } else {
                        $return_string .=
                                        '<div class="pxp-services-c-item">';
                    }
                    $return_string .=
                                            '<div class="pxp-services-c-item-fig">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                                '<span class="' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                                '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_attr($service['title']) . '" />';
                        }
                    }
                    $service_cta_color = isset($service['ctacolor']) ? $service['ctacolor'] : '';
                    $return_string .=
                                            '</div>
                                            <div class="pxp-services-c-item-text text-center">
                                                <div class="pxp-services-c-item-text-title">' . esc_html($service['title']) . '</div>
                                                <div class="pxp-services-c-item-text-sub">' . esc_html($service['text']) . '</div>
                                            </div>
                                            <div class="pxp-services-c-item-cta text-uppercase text-center" style="color: ' . esc_attr($service_cta_color) . '">' . esc_html($service['cta']) . '</div>
                                        </a>
                                    </div>';
                }
                $return_string .=
                                '</div>
                            </div>
                        </div>
                    </div>';
            break;
            case '4':
                $display = isset($s_array['display']) ? $s_array['display'] : 'columns';
                $title_column_class = 'col-md-4';
                $space_class = 'col-md-2';
                $items_container_class = '';
                $items_class = 'col-md-6';
                $item_class = 'col-sm-6';

                if ($display == 'grid') {
                    $title_column_class = 'col-12';
                    $space_class = 'd-none';
                    $items_container_class = 'mt-4 mt-md-5';
                    $items_class = 'col-12';

                    $services_count = count($s_array['services']);
                    if ($services_count == 2) {
                        $item_class = 'col-md-6';
                    } elseif ($services_count % 3 == 0) {
                        $item_class = 'col-md-4';
                    } else {
                        $item_class = 'col-md-6 col-lg-3';
                    }
                }

                $return_string = 
                    '<div class="pxp-services-columns ' . esc_attr($margin_class) . '">
                        <div class="container">
                            <div class="row">
                                <div class="' . esc_attr($title_column_class) . '">
                                    <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                                </div>
                                <div class="' . esc_attr($space_class) . '"></div>
                                <div class="' . esc_attr($items_class) . '">
                                    <div class="row ' . esc_attr($items_container_class) . '">';
                foreach ($s_array['services'] as $service) {
                    $return_string .=
                                        '<div class="' . esc_attr($item_class) . '">
                                            <div class="pxp-services-columns-item mb-3 mb-md-4">
                                                <div class="pxp-services-columns-item-fig">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                                    '<span class="' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                                    '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_attr($service['title']) . '" />';
                        }
                    }
                    $return_string .=
                                                '</div>
                                                <h3 class="mt-3">' . esc_html($service['title']) . '</h3>
                                                <p class="pxp-text-light">' . esc_html($service['text']) . '</p>
                                            </div>
                                        </div>';
                }
                $return_string .=
                                    '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
            break;
            case '5':
                $acc_id = uniqid();
                if ($bg_image_src == '') {
                    $return_string = 
                        '<div class="pxp-services-accordion ' . esc_attr($margin_class) . '">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                        <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-6">
                                        <div class="accordion" id="pxpServicesAccordion' . esc_attr($acc_id) . '">';
                    $count = 0;
                    foreach ($s_array['services'] as $service) {
                        $item_class = '';
                        $collapsed = '';
                        $show = 'show';
                        if ($count > 0) {
                            $item_class = 'mt-2 mt-md-3';
                            $collapsed = 'collapsed';
                            $show = '';
                        }
                        $return_string .= 
                                            '<div class="pxp-services-accordion-item ' . esc_attr($item_class) . '">
                                                <div class="pxp-services-accordion-item-header" id="pxpServicesAccordionHeading' . esc_attr($acc_id) . '-' . esc_attr($count) . '">
                                                    <h4 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left ' . esc_attr($collapsed) . '" type="button" data-toggle="collapse" data-target="#pxpServicesAccordionCollapse' . esc_attr($acc_id) . '-' . esc_attr($count) . '" aria-expanded="true" aria-controls="pxpServicesAccordionCollapse' . esc_attr($acc_id) . '-' . esc_attr($count) . '">
                                                            <span class="pxp-services-accordion-item-icon"></span> ' . esc_html($service['title']) . '
                                                        </button>
                                                    </h4>
                                                </div>
                                                <div id="pxpServicesAccordionCollapse' . esc_attr($acc_id) . '-' . esc_attr($count) . '" class="collapse ' . esc_attr($show) . '" aria-labelledby="pxpServicesAccordionHeading' . esc_attr($acc_id) . '-' . esc_attr($count) . '" data-parent="#pxpServicesAccordion' . esc_attr($acc_id) . '">
                                                    <div class="pxp-services-accordion-item-body pxp-text-light">' . esc_html($service['text']) . '</div>
                                                </div>
                                            </div>';
                        $count++;
                    }
                    $return_string .= 
                                        '</div>';
                    if ($s_array['cta_link'] != '') {
                        $return_string .= 
                                        '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>';
                        if ($cta_color != '') {
                            $return_string .= 
                                        '<style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                        }
                    }
                    $return_string .= 
                                    '</div>
                                </div>
                            </div>
                        </div>';
                } else {
                    $return_string = 
                        '<div class="pxp-services-accordion pxp-services-accordion-has-image ' . esc_attr($margin_class) . '">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-5">
                                        <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-md-6">
                                    <div class="pxp-services-accordion-fig pxp-cover" style="background-image: url(' . esc_url($bg_image_src) . ');"></div>
                                </div>
                                <div class="col-md-6 pxp-services-accordion-right">
                                    <div class="pxp-services-accordion-right-container">
                                        <div class="row">
                                            <div class="col-xl-10 col-xxl-6">
                                                <h3 style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</h3>
                                                <div class="accordion mt-4 mt-md-5" id="pxpServicesAccordionFig' . esc_attr($acc_id) . '">';
                    $count = 0;
                    foreach ($s_array['services'] as $service) {
                        $item_class = '';
                        $collapsed = '';
                        $show = 'show';
                        if ($count > 0) {
                            $item_class = 'mt-2 mt-md-3';
                            $collapsed = 'collapsed';
                            $show = '';
                        }
                        $return_string .= 
                                                    '<div class="pxp-services-accordion-item ' . esc_attr($item_class) . '">
                                                        <div class="pxp-services-accordion-item-header" id="pxpServicesAccordionFigHeading' . esc_attr($acc_id) . '-' . esc_attr($count) . '">
                                                            <h4 class="mb-0">
                                                                <button class="btn btn-link btn-block text-left ' . esc_attr($collapsed) . '" type="button" data-toggle="collapse" data-target="#pxpServicesAccordionFigCollapse' . esc_attr($acc_id) . '-' . esc_attr($count) . '" aria-expanded="true" aria-controls="pxpServicesAccordionFigCollapse' . esc_attr($acc_id) . '-' . esc_attr($count) . '">
                                                                    <span class="pxp-services-accordion-item-icon"></span> ' . esc_html($service['title']) . '
                                                                </button>
                                                            </h4>
                                                        </div>
                                                        <div id="pxpServicesAccordionFigCollapse' . esc_attr($acc_id) . '-' . esc_attr($count) . '" class="collapse ' . esc_attr($show) . '" aria-labelledby="pxpServicesAccordionFigHeading' . esc_attr($acc_id) . '-' . esc_attr($count) . '" data-parent="#pxpServicesAccordionFig' . esc_attr($acc_id) . '">
                                                            <div class="pxp-services-accordion-item-body pxp-text-light">' . esc_html($service['text']) . '</div>
                                                        </div>
                                                    </div>';
                        $count++;
                    }
                    $return_string .= 
                                                '</div>';
                    if ($s_array['cta_link'] != '') {
                        $return_string .= 
                                                '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_label']) . '</a>';
                        if ($cta_color != '') {
                            $return_string .= 
                                                '<style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                        }
                    }
                    $return_string .=
                                            '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            break;
            case '6':
                $section_id = uniqid();
                $return_string = 
                    '<div class="pxp-services-tabs ' . esc_attr($margin_class) . '">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h2 class="pxp-section-h2 d-block d-lg-none" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light mt-3 mt-lg-4 d-block d-lg-none" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                                    <div class="pxp-services-tabs-items mt-4 mt-md-5 mt-lg-0">
                                        <div id="pxp-services-tabs-carousel-' . esc_attr($section_id) . '" class="carousel slide carousel-fade pxp-services-tabs-carousel" data-ride="carousel" data-interval="false">
                                            <div class="carousel-inner">';
                $count_services = 0;
                foreach ($s_array['services'] as $service) {
                    $service_bg = wp_get_attachment_image_src($service['bgvalue'], 'pxp-gallery');
                    $service_bg_src = '';
                    if ($service_bg != false) {
                        $service_bg_src = $service_bg[0];
                    }
                    $active_class = $count_services == 0 ? 'active' : '';
                    $return_string .=
                                                '<div class="carousel-item pxp-cover ' . esc_attr($active_class) . '" style="background-image: url(' . esc_url($service_bg_src) . ');"></div>';
                    $count_services++;
                }
                $return_string .= 
                                            '</div>
                                        </div>
                                        <div class="pxp-services-tabs-items-content">
                                            <div id="pxp-services-tabs-content-carousel-' . esc_attr($section_id) . '" class="carousel slide carousel-fade" data-ride="carousel" data-interval="false">
                                                <div class="carousel-inner">';
                $count_services = 0;
                foreach ($s_array['services'] as $service) {
                    $active_class = $count_services == 0 ? 'active' : '';
                    $return_string .=
                                                    '<div class="carousel-item ' . esc_attr($active_class) . '">';
                    if ($service['link'] != '') {
                        $return_string .= 
                                                        '<a href="' . esc_url($service['link']) . '" class="pxp-services-tabs-content-item">';
                    } else {
                        $return_string .= 
                                                        '<div class="pxp-services-tabs-content-item">';
                    }
                    $return_string .= 
                                                            '<div class="pxp-services-tabs-content-item-fig">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                                                '<span class="' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                                                '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_attr($service['title']) . '" />';
                        }
                    }
                    $return_string .= 
                                                            '</div>
                                                            <div class="pxp-services-tabs-content-item-text">' . esc_html($service['text']) . '</div>';
                    if ($service['link'] != '') {
                        $service_cta_color = isset($service['ctacolor']) ? $service['ctacolor'] : '';
                        $return_string .= 
                                                            '<div class="pxp-services-tabs-content-item-cta-container">
                                                                <div class="pxp-services-tabs-content-item-cta text-uppercase" style="color: ' . esc_attr($service_cta_color) . '">
                                                                    <span>' . esc_html($service['cta']) . '</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">';
                    if (is_rtl()) {
                        $return_string .= 
                                                                        '<g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                                            <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: ' . esc_attr($service_cta_color) . '"/>
                                                                            <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: ' . esc_attr($service_cta_color) . '"/>
                                                                            <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: ' . esc_attr($service_cta_color) . '"/>
                                                                        </g>';
                    } else {
                        $return_string .= 
                                                                        '<g id="Symbol_1_1" data-name="Symbol 1 - 1" transform="translate(-1847.5 -1589.086)">
                                                                            <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: ' . esc_attr($service_cta_color) . '" />
                                                                            <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: ' . esc_attr($service_cta_color) . '" />
                                                                            <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: ' . esc_attr($service_cta_color) . '" />
                                                                        </g>';
                    }
                    $return_string .= 
                                                                    '</svg>
                                                                </div>
                                                            </div>';
                    }
                    if ($service['link'] != '') {
                        $return_string .= 
                                                        '</a>';
                    } else {
                        $return_string .= 
                                                        '</div>';
                    }
                    $return_string .= 
                                                    '</div>';
                    $count_services++;
                }
                $return_string .= 
                                                '</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-5">
                                    <h2 class="pxp-section-h2 d-none d-lg-block" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light mt-3 mt-lg-4 d-none d-lg-block" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                                    <ul class="carousel-indicators" data-id="' . esc_attr($section_id) . '">';
                $count_services = 0;
                foreach ($s_array['services'] as $service) {
                    $active_class = $count_services == 0 ? 'active' : '';
                    $return_string .=
                                        '<li data-target="#pxp-services-tabs-carousel-' . esc_attr($section_id) . '" data-slide-to="' . esc_attr($count_services) . '" class="' . esc_attr($active_class) . '">' . esc_attr($service['title']) . '</li>';
                    $count_services++;
                }
                $return_string .= 
                                    '</ul>
                                </div>
                            </div>
                        </div>
                    </div>';
            break;
            case '7':
                $return_string = 
                    '<div class="pxp-services-tilt ' . esc_attr($margin_class) . '">
                        <h2 class="text-center pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light text-center" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>

                        <div class="container mt-4 mt-md-5">
                            <div class="row justify-content-center">';
                foreach ($s_array['services'] as $service) {
                    $service_bg = wp_get_attachment_image_src($service['bgvalue'], 'pxp-gallery');
                    $service_bg_src = '';
                    if ($service_bg != false) {
                        $service_bg_src = $service_bg[0];
                    }

                    $return_string .=
                                '<div class="col-sm-12 col-md-6 col-lg-4">';
                    if ($service['link'] != '') {
                        $return_string .= 
                                    '<a href="' . esc_url($service['link']) . '" class="pxp-services-tilt-item">';
                    } else {
                        $return_string .= 
                                    '<div class="pxp-services-tilt-item">';
                    }
                    $return_string .= 
                                        '<figure class="pxp-services-tilt-item-fig pxp-cover rounded-lg" style="background-image: url(' . esc_url($service_bg_src) . ');">
                                            <figcaption class="pxp-services-tilt-item-caption">
                                                <div class="pxp-services-tilt-item-caption-icon">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                                    '<span class="' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                                    '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_attr($service['title']) . '" />';
                        }
                    }
                    $return_string .= 
                                                '</div>
                                                <div class="pxp-services-tilt-item-caption-title">' . esc_html($service['title']) . '</div>
                                                <div class="pxp-services-tilt-item-caption-text">' . esc_html($service['text']) . '</div>
                                            </figcaption>
                                        </figure>';
                    if ($service['link'] != '') {
                        $return_string .= 
                                    '</a>';
                    } else {
                        $return_string .= 
                                    '</div>';
                    }
                    $return_string .= 
                                '</div>';
                }
                $return_string .=
                            '</div>
                        </div>
                    </div';
            break;
            default:
            case '1':
                $return_string = '
                    <div class="pxp-services pxp-cover pt-100 mb-200 ' . esc_attr($margin_class) . '" style="background-image: url(' . esc_url($bg_image_src) . ');">
                        <h2 class="text-center pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                        <p class="pxp-text-light text-center" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>

                        <div class="container">
                            <div class="pxp-services-container rounded-lg mt-4 mt-md-5">';
                foreach ($s_array['services'] as $service) {
                    if ($service['link'] != '') {
                        $return_string .= 
                                '<a href="' . esc_url($service['link']) . '" class="pxp-services-item">';
                    } else {
                        $return_string .= 
                                '<div class="pxp-services-item">';
                    }
                    $return_string .= 
                                    '<div class="pxp-services-item-fig">';
                    if ($service['isicon'] == '1') {
                        $return_string .= 
                                        '<span class="' . esc_attr($service['value']) . '" style="color: ' . esc_attr($service['color']) . '"></span>';
                    } else {
                        $image_src = wp_get_attachment_image_src($service['value'], 'pxp-icon');
                        if ($image_src != false) {
                            $return_string .= 
                                        '<img src="' . esc_url($image_src[0]) . '" alt="' . esc_html($service['title']) . '" />';
                        }
                    }
                    $service_cta_color = isset($service['ctacolor']) ? $service['ctacolor'] : '';
                    $return_string .= 
                                    '</div>
                                    <div class="pxp-services-item-text text-center">
                                        <div class="pxp-services-item-text-title">' . esc_html($service['title']) . '</div>
                                        <div class="pxp-services-item-text-sub">' . esc_html($service['text']) . '</div>
                                    </div>
                                    <div class="pxp-services-item-cta text-uppercase text-center" style="color: ' . esc_attr($service_cta_color) . '">' . esc_html($service['cta']) . '</div>';
                    if ($service['link'] != '') {
                        $return_string .= 
                                '</a>';
                    } else {
                        $return_string .= 
                                '</div>';
                    }
                }
                $return_string .= 
                                '<div class="clearfix"></div>
                            </div>
                        </div>
                    </div>';
            break;
        }

        return $return_string;
    }
endif;
?>