<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Testimonials shortcode
 */
if (!function_exists('resideo_testimonials_shortcode')): 
    function resideo_testimonials_shortcode($attrs, $content = null) {
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
        $layout = isset($s_array['layout']) ? $s_array['layout'] : '1';

        $text_color = isset($s_array['text_color']) ? 'color: ' . $s_array['text_color'] : '';
        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $cta_id = uniqid();

        $args = array(
            'numberposts'      => -1,
            'post_type'        => 'testimonial',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args);

        switch ($layout) {
            case '1':
                $return_string = 
                    '<div class="pxp-testim-1 pt-100 pb-100 ' . esc_attr($margin_class) . ' pxp-cover" style="background-image: url(' . esc_url($photo_src) . ');">
                        <div class="pxp-testim-1-intro">
                            <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                            <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>';
                if ($s_array['cta_text'] != '') {
                    $return_string .=
                            '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . esc_html($s_array['cta_text']) . '</a>';
                    if ($cta_color != '') {
                        $return_string .= 
                            '<style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                    }
                }
                $return_string .=
                        '</div>
                        <div class="pxp-testim-1-container mt-4 mt-md-5 mt-lg-0">
                            <div class="owl-carousel pxp-testim-1-stage">';
                foreach ($posts as $post) {
                    $text = get_post_meta($post['ID'], 'testimonial_text', true);
                    $location = get_post_meta($post['ID'], 'testimonial_location', true);
        
                    $avatar = get_post_meta($post['ID'], 'testimonial_avatar', true);
                    if ($avatar != '') {
                        $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');
                        $avatar_photo_src = $avatar_photo[0];
                    } else {
                        $avatar_photo_src = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                    }
                    $return_string .=
                                '<div>
                                    <div class="pxp-testim-1-item">
                                        <div class="pxp-testim-1-item-avatar pxp-cover" style="background-image: url(' . esc_url($avatar_photo_src) . ')"></div>
                                        <div class="pxp-testim-1-item-name">' . esc_html($post['post_title']) . '</div>
                                        <div class="pxp-testim-1-item-location">' . esc_html($location) . '</div>
                                        <div class="pxp-testim-1-item-message">' . esc_html($text) . '</div>
                                    </div>
                                </div>';
                }

                $return_string .= 
                            '</div>
                        </div>
                    </div>';
            break;
            case '2':
                $return_string = 
                    '<div class="pxp-testim-2 ' . esc_attr($margin_class) . '">
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-6">
                                <div class="pxp-testim-2-caption pt-100 pb-100">
                                    <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                                    <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                                    <div id="pxp-testim-2-caption-carousel" class="carousel slide pxp-testim-2-caption-carousel mt-4 mt-md-5" data-ride="carousel" data-pause="false" data-interval="7000">
                                        <div class="carousel-inner">';
                $counter_1 = 0;
                foreach ($posts as $post) {
                    $text = get_post_meta($post['ID'], 'testimonial_text', true);
                    $location = get_post_meta($post['ID'], 'testimonial_location', true);
                    $slide_active_1 = '';
                    if ($counter_1 == 0) {
                        $slide_active_1 = 'active';
                    }

                    $return_string .= 
                                            '<div class="carousel-item ' . esc_attr($slide_active_1) . '" data-slide="' . esc_attr($counter_1) . '">
                                                <div class="pxp-testim-2-item-message" style="' . esc_attr($text_color) . '">' . esc_html($text) . '</div>
                                                <div class="pxp-testim-2-item-name" style="' . esc_attr($text_color) . '">' . esc_html($post['post_title']) . '</div>
                                                <div class="pxp-testim-2-item-location" style="' . esc_attr($text_color) . '">' . esc_html($location) . '</div>
                                            </div>';
                    $counter_1++;
                }

                $return_string .= 
                                        '</div>
                                        <div class="pxp-carousel-controls mt-4 mt-md-5">
                                            <a class="pxp-carousel-control-prev" role="button" data-slide="prev">';
                if (is_rtl()) {
                    $return_string .=
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Symbol_1_1" data-name="Symbol 1 - 1" transform="translate(-1847.5 -1589.086)">
                                                        <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>';
                } else {
                    $return_string .=
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                        <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>';
                }
                $return_string .=
                                            '</a>
                                            <a class="pxp-carousel-control-next" role="button" data-slide="next">';
                if (is_rtl()) {
                    $return_string .=
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                        <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>';
                } else {
                    $return_string .=
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Symbol_1_1" data-name="Symbol 1 - 1" transform="translate(-1847.5 -1589.086)">
                                                        <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>';
                }
                $return_string .=
                                            '</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="pxp-testim-2-photos">
                                    <div id="pxp-testim-2-photos-carousel" class="carousel slide pxp-testim-2-photos-carousel" data-ride="carousel" data-pause="false" data-interval="false">
                                        <div class="carousel-inner">';
                $counter_2 = 0;
                foreach ($posts as $post) {
                    $avatar = get_post_meta($post['ID'], 'testimonial_avatar', true);
                    $avatar_photo_src = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                    if ($avatar != '') {
                        $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-full');
                        $avatar_photo_src = $avatar_photo[0];
                    }

                    $slide_active_2 = '';
                    if ($counter_2 == 0) {
                        $slide_active_2 = 'active';
                    }

                    $return_string .= 
                                            '<div class="carousel-item ' . esc_attr($slide_active_2) . '" data-slide="' . esc_attr($counter_2) . '">
                                                <div class="pxp-hero-bg pxp-cover" style="background-image: url(' . esc_url($avatar_photo_src) . ');"></div>
                                            </div>';
                    $counter_2++;
                }
                $return_string .= 
                                        '</div>
                                    </div>
                                </div>
                        </div>
                    </div>';
            break;
            default: 
                $return_string = 
                    '<div class="pxp-testim-1 pt-100 pb-100 ' . esc_attr($margin_class) . ' pxp-cover" style="background-image: url(' . esc_url($photo_src) . ');">
                        <div class="pxp-testim-1-intro">
                            <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                            <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>';
                if ($s_array['cta_text'] != '') {
                    $return_string .=
                            '<a href="' . esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate">' . esc_html($s_array['cta_text']) . '</a>';
                }
                $return_string .=
                        '</div>
                        <div class="pxp-testim-1-container mt-4 mt-md-5 mt-lg-0">
                            <div class="owl-carousel pxp-testim-1-stage">';
                foreach ($posts as $post) {
                    $text = get_post_meta($post['ID'], 'testimonial_text', true);
                    $location = get_post_meta($post['ID'], 'testimonial_location', true);

                    $avatar = get_post_meta($post['ID'], 'testimonial_avatar', true);
                    if ($avatar != '') {
                        $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');
                        $avatar_photo_src = $avatar_photo[0];
                    } else {
                        $avatar_photo_src = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                    }
                    $return_string .=
                                '<div>
                                    <div class="pxp-testim-1-item">
                                        <div class="pxp-testim-1-item-avatar pxp-cover" style="background-image: url(' . esc_url($avatar_photo_src) . ')"></div>
                                        <div class="pxp-testim-1-item-name">' . esc_html($post['post_title']) . '</div>
                                        <div class="pxp-testim-1-item-location">' . esc_html($location) . '</div>
                                        <div class="pxp-testim-1-item-message">' . esc_html($text) . '</div>
                                    </div>
                                </div>';
                }

                $return_string .= 
                            '</div>
                        </div>
                    </div>';
            break;
        }

        wp_reset_postdata();
        wp_reset_query();

        return $return_string;
    }
endif;
?>