<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Membership_Plans_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'membership_plans';
    }

    public function get_title() {
        return __('Membership Plans', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-credit-card-alt';
    }

    public function get_categories() {
        return ['resideo'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter title', 'resideo'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'input_type' => 'string',
                'placeholder' => __('Enter subtitle', 'resideo'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'plans_section',
            [
                'label' => __('Plans', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'plans_title_color',
            [
                'label' => __('Plans Title Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'plans_price_color',
            [
                'label' => __('Plans Price Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'plans_cta_color',
            [
                'label' => __('Plans CTA Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'featured_plan_title_color',
            [
                'label' => __('Featured Plan Title Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'featured_plan_price_color',
            [
                'label' => __('Featured Plan Price Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'featured_plan_cta_color',
            [
                'label' => __('Featured Plan CTA Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'featured_plan_label_color',
            [
                'label' => __('Featured Plan Label Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'resideo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'resideo'),
                'label_off' => __('No', 'resideo'),
                'return_value' => 'yes'
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';

        $membership_settings = get_option('resideo_membership_settings');
        $currency = isset($membership_settings['resideo_payment_currency_field']) ? $membership_settings['resideo_payment_currency_field'] : '';

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

        $default_plans_title_color = isset($settings['plans_title_color']) ? $settings['plans_title_color'] : '';
        $default_plans_price_color = isset($settings['plans_price_color']) ? $settings['plans_price_color'] : '';
        $default_plans_cta_color   = isset($settings['plans_cta_color']) ? $settings['plans_cta_color'] : '';
        $featured_plan_title_color = isset($settings['featured_plan_title_color']) ? $settings['featured_plan_title_color'] : '';
        $featured_plan_price_color = isset($settings['featured_plan_price_color']) ? $settings['featured_plan_price_color'] : '';
        $featured_plan_cta_color   = isset($settings['featured_plan_cta_color']) ? $settings['featured_plan_cta_color'] : '';
        $featured_plan_label_color = isset($settings['featured_plan_label_color']) ? $settings['featured_plan_label_color'] : ''; 

        $auth_settings = get_option('resideo_authentication_settings');
        $user_nav = isset($auth_settings['resideo_user_registration_field']) ? $auth_settings['resideo_user_registration_field'] : '';

        $show_login_modal = '';
        if ($user_nav == '1') {
            if (!is_user_logged_in()) {
                $show_login_modal = 'pxp-show-login-modal';
            }
        } ?>

        <div class="container <?php echo esc_attr($margin_class); ?>">
            <h2 class="pxp-section-h2 text-center"><?php echo esc_html($settings['title']); ?></h2>
            <div class="pxp-text-light text-center"><?php echo $settings['subtitle']; ?></div>
            <div class="row justify-content-center mt-5">
                <?php $plans_total = count($posts);
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

                    if ($membership_plan_popular == '1') {
                        $is_popular = ' is-popular';
                    } else {
                        $is_popular = '';
                    }

                    $icon = get_post_meta($post->ID, 'membership_icon', true);
                    $icon_src = wp_get_attachment_image_src($icon, 'pxp-icon');

                    if ($icon_src != '') {
                        $m_icon = $icon_src[0];
                    }

                    $popular_class = '';
                    $plan_title_color = $default_plans_title_color;
                    $plan_price_color = $default_plans_price_color;
                    $plan_cta_color = $default_plans_cta_color;

                    if ($membership_plan_popular == '1' && $membership_plan_popular_label != '') {
                        $popular_class = 'pxp-is-popular';
                        $plan_title_color = $featured_plan_title_color;
                        $plan_price_color = $featured_plan_price_color;
                        $plan_cta_color = $featured_plan_cta_color;
                    } ?>

                    <div class="<?php echo esc_attr($column_class); ?>">
                        <a href="<?php echo esc_url(resideo_get_account_url()); ?>" class="pxp-plans-1-item <?php echo esc_attr($show_login_modal); ?> <?php echo esc_attr($popular_class); ?>">
                            <?php if ($membership_plan_popular == '1' && $membership_plan_popular_label != '') { ?>
                                <div class="pxp-plans-1-item-label" style="background-color: <?php echo esc_attr($featured_plan_label_color); ?>;"><?php echo esc_html($membership_plan_popular_label); ?></div>
                            <?php } ?>
                            <div class="pxp-plans-1-item-fig">
                                <?php if ($icon_src != '') { ?>
                                    <img src="<?php echo esc_url($m_icon); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                <?php } ?>
                            </div>
                            <div class="pxp-plans-1-item-title" style="color: <?php echo esc_attr($plan_title_color); ?>"><?php echo esc_html($post->post_title); ?></div>
                            <ul class="pxp-plans-1-item-features list-unstyled">
                                <?php if ($membership_unlim_submissions == 1) { ?>
                                    <li><?php esc_html_e('Unlimited Listings', 'resideo'); ?></li>
                                <?php } else { ?>
                                    <li><?php echo esc_html($membership_submissions_no); ?> <?php esc_html_e('Listings', 'resideo'); ?></li>
                                <?php } ?>
                                <li><?php echo esc_html($membership_featured_submissions_no); ?> <?php esc_html_e('Featured Listings', 'resideo'); ?></li>
                            </ul>
                            <div class="pxp-plans-1-item-price" style="color: <?php echo esc_attr($plan_price_color); ?>;">
                                <?php if ($membership_free_plan == '1') { ?>
                                    <span class="pxp-plans-1-item-price-sum"><?php esc_html_e('Free', 'resideo'); ?></span>
                                <?php } else { ?>
                                    <span class="pxp-plans-1-item-price-currency"><?php echo esc_html($currency); ?></span>
                                    <span class="pxp-plans-1-item-price-sum"><?php echo esc_html($membership_plan_price); ?></span>
                                <?php } ?>
                                <span class="pxp-plans-1-item-price-period"> / <?php echo esc_html($membership_period); ?> <?php echo esc_html($time_unit); ?></span>
                            </div>
                            <div class="pxp-plans-1-item-cta text-uppercase" style="color: <?php echo esc_attr($plan_cta_color); ?>;"><?php esc_html_e('Choose Plan', 'resideo'); ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php wp_reset_postdata();
        wp_reset_query();
    }
}
?>