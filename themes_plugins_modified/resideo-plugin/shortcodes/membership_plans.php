<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Membership plans shortcode
 */
if (!function_exists('resideo_membership_plans_shortcode')): 
    function resideo_membership_plans_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $title                = isset($s_array['title']) ? $s_array['title'] : '';
        $subtitle             = isset($s_array['subtitle']) ? $s_array['subtitle'] : '';
        $default_title_color  = isset($s_array['title_color']) ? $s_array['title_color'] : '';
        $default_price_color  = isset($s_array['price_color']) ? $s_array['price_color'] : '';
        $default_cta_color    = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $featured_title_color = isset($s_array['featured_title_color']) ? $s_array['featured_title_color'] : '';
        $featured_price_color = isset($s_array['featured_price_color']) ? $s_array['featured_price_color'] : '';
        $featured_cta_color   = isset($s_array['featured_cta_color']) ? $s_array['featured_cta_color'] : '';
        $featured_label_color = isset($s_array['featured_label_color']) ? $s_array['featured_label_color'] : '';

        $membership_settings = get_option('resideo_membership_settings');
        $currency = isset($membership_settings['resideo_payment_currency_field']) ? $membership_settings['resideo_payment_currency_field'] : '';

        $auth_settings = get_option('resideo_authentication_settings');
        $user_nav = isset($auth_settings['resideo_user_registration_field']) ? $auth_settings['resideo_user_registration_field'] : '';

        $show_login_modal = '';
        if ($user_nav == '1') {
            if (!is_user_logged_in()) {
                $show_login_modal = 'pxp-show-login-modal';
            }
        }

        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'membership',
            'order'            => 'ASC',
            'suppress_filters' => false,
            'post_status'      => 'publish,',
            'meta_key'         => 'membership_plan_price',
            'orderby'          => 'meta_value_num'
        );

        $posts = get_posts($args);

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $return_string = '
            <div class="container ' . esc_attr($margin_class) . '">
                <h2 class="pxp-section-h2 text-center">' . esc_html($title) . '</h2>
                <p class="pxp-text-light text-center">' . esc_html($subtitle) . '</p>
                <div class="row justify-content-center mt-5">';

        $plans_total = count($posts);
        $column_class = 'col-sm-12 col-md-6 col-lg-4';
        if ($plans_total == 4 || $plans_total >= 7) {
            $column_class = 'col-sm-12 col-md-6 col-xl-3';
        }

        foreach($posts as $post) : 
            $membership_billing_time_unit       = get_post_meta($post->ID, 'membership_billing_time_unit', true);
            $membership_period                  = get_post_meta($post->ID, 'membership_period', true);
            $membership_submissions_no          = get_post_meta($post->ID, 'membership_submissions_no', true);
            $membership_unlim_submissions       = get_post_meta($post->ID, 'membership_unlim_submissions', true);
            $membership_featured_submissions_no = get_post_meta($post->ID, 'membership_featured_submissions_no', true);
            $membership_plan_price              = get_post_meta($post->ID, 'membership_plan_price', true);
            $membership_free_plan               = get_post_meta($post->ID, 'membership_free_plan', true);
            $membership_plan_popular            = get_post_meta($post->ID, 'membership_plan_popular', true);
            $membership_plan_popular_label      = get_post_meta($post->ID, 'membership_plan_popular_label', true);

            if ($membership_billing_time_unit == 'day') {
                if ($membership_period == '1') {
                    $time_unit = __('day', 'resideo');
                } else {
                    $time_unit = __('days', 'resideo');
                }
            } else if ($membership_billing_time_unit == 'week') {
                if ($membership_period == '1') {
                    $time_unit = __('week', 'resideo');
                } else {
                    $time_unit = __('weeks', 'resideo');
                }
            } else if ($membership_billing_time_unit == 'month') {
                if ($membership_period == '1') {
                    $time_unit = __('month', 'resideo');
                } else {
                    $time_unit = __('months', 'resideo');
                }
            } else {
                if ($membership_period == '1') {
                    $time_unit = __('year', 'resideo');
                } else {
                    $time_unit = __('years', 'resideo');
                }
            }

            $icon = get_post_meta($post->ID, 'membership_icon', true);
            $icon_src = wp_get_attachment_image_src($icon, 'pxp-icon');

            if ($icon_src != '') {
                $m_icon = $icon_src[0];
            }

            $popular_class = '';
            $title_color = $default_title_color;
            $price_color = $default_price_color;
            $cta_color = $default_cta_color;

            if ($membership_plan_popular == '1' && $membership_plan_popular_label != '') {
                $popular_class = 'pxp-is-popular';
                $title_color = $featured_title_color;
                $price_color = $featured_price_color;
                $cta_color = $featured_cta_color;
            }

            $return_string .= '
                <div class="' . esc_attr($column_class) . '">
                    <a href="' . esc_url(resideo_get_account_url()) . '" class="pxp-plans-1-item ' . esc_attr($show_login_modal) . ' ' . esc_attr($popular_class) . '">';
            if ($membership_plan_popular == '1' && $membership_plan_popular_label != '') {
                $return_string .= 
                        '<div class="pxp-plans-1-item-label" style="background-color: ' . esc_attr($featured_label_color) . '">' . esc_html($membership_plan_popular_label) . '</div>';
            }
            $return_string .= 
                        '<div class="pxp-plans-1-item-fig">';
            if ($icon_src != '') {
                $return_string .= 
                            '<img src="' . esc_url($m_icon) . '" alt="' . esc_attr($post->post_title) . '">';
            }
            $return_string .= 
                        '</div>
                        <div class="pxp-plans-1-item-title" style="color: ' . esc_attr($title_color) . '">' . esc_html($post->post_title) . '</div>
                        <ul class="pxp-plans-1-item-features list-unstyled">';
            if ($membership_unlim_submissions == 1) {
                $return_string .= 
                            '<li>' . __('Unlimited Listings', 'resideo') . '</li>';
            } else {
                $return_string .= 
                            '<li>' . esc_html($membership_submissions_no) . ' ' . __('Listings', 'resideo') . '</li>';
            }
            $return_string .= 
                            '<li>' . esc_html($membership_featured_submissions_no) . ' ' . __('Featured Listings', 'resideo') . '</li>
                        </ul>
                        <div class="pxp-plans-1-item-price" style="color: ' . esc_attr($price_color) . '">';
            if ($membership_free_plan == '1') {
                $return_string .= 
                            '<span class="pxp-plans-1-item-price-sum">' . __('Free', 'resideo') . '</span>';
            } else {
                $return_string .= 
                            '<span class="pxp-plans-1-item-price-currency">' . esc_html($currency) . '</span>
                            <span class="pxp-plans-1-item-price-sum">' . esc_html($membership_plan_price) . '</span>';
            }
            $return_string .= 
                            '<span class="pxp-plans-1-item-price-period"> / ' . esc_html($membership_period) . ' ' . esc_html($time_unit) . '</span>
                        </div>
                        <div class="pxp-plans-1-item-cta text-uppercase" style="color: ' . esc_attr($cta_color) . '">' . __('Choose Plan', 'resideo') . '</div>
                    </a>
                </div>';
        endforeach;

        $return_string .=  '</div></div>';

        wp_reset_postdata();
        wp_reset_query();

        return $return_string;
    }
endif;
?>