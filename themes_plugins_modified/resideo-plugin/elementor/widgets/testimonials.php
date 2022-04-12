<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Testimonials_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'testimonials';
    }

    public function get_title() {
        return __('Testimonials', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-quote-left';
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

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'resideo'),
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
            'background_section',
            [
                'label' => __('Background Image', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout' => '1'
                ]
            ]
        );

        $this->add_control(
            'background_image',
            [
                'label' => __('Choose image', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'cta_section',
            [
                'label' => __('CTA', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout' => '1'
                ]
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => __('CTA Link', 'resideo'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Enter CTA link', 'resideo'),
                'show_external' => true,
            ]
        );

        $this->add_control(
            'cta_label',
            [
                'label' => __('CTA Label', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter CTA label', 'resideo'),
            ]
        );

        $this->add_control(
            'cta_color',
            [
                'label' => __('CTA Color', 'resideo'),
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
            'layout',
            [
                'label' => __('Layout', 'resideo'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Layout 1', 'resideo'),
                        'icon' => 'fa fa-th-list',
                    ],
                    '2' => [
                        'title' => __('Layout 2', 'resideo'),
                        'icon' => 'fa fa-columns',
                    ]
                ],
                'default' => '1',
                'toggle' => false,
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

        $bg_image_src = '';
        $bg_image = false;
        if (isset($settings['background_image'])) {
            $bg_image = wp_get_attachment_image_src($settings['background_image']['id'], 'pxp-full');

            if ($bg_image != false) {
                $bg_image_src = $bg_image[0];
            }
        }
        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';

        $text_color = isset($settings['text_color']) ? 'color: ' . $settings['text_color'] : '';
        $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : '';
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

        switch ($settings['layout']) {
            case '1': ?>
                <div class="pxp-testim-1 pt-100 pb-100 <?php echo esc_attr($margin_class); ?> pxp-cover" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
                    <div class="pxp-testim-1-intro">
                        <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                        <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                        <?php if ($settings['cta_link']['url'] != '') { 
                            $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                            <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                            <?php if ($cta_color != '') { ?>
                                <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?> }</style>
                            <?php }
                        } ?>
                    </div>
                    <div class="pxp-testim-1-container mt-4 mt-md-5 mt-lg-0">
                        <div class="owl-carousel pxp-testim-1-stage">
                            <?php foreach ($posts as $post) {
                                $text = get_post_meta($post['ID'], 'testimonial_text', true);
                                $location = get_post_meta($post['ID'], 'testimonial_location', true);

                                $avatar = get_post_meta($post['ID'], 'testimonial_avatar', true);
                                if ($avatar != '') {
                                    $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');
                                    $avatar_photo_src = $avatar_photo[0];
                                } else {
                                    $avatar_photo_src = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                                } ?>

                                <div>
                                    <div class="pxp-testim-1-item">
                                        <div class="pxp-testim-1-item-avatar pxp-cover" style="background-image: url(<?php echo esc_url($avatar_photo_src); ?>)"></div>
                                        <div class="pxp-testim-1-item-name"><?php echo esc_html($post['post_title']); ?></div>
                                        <div class="pxp-testim-1-item-location"><?php echo esc_html($location); ?></div>
                                        <div class="pxp-testim-1-item-message"><?php echo $text; ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php break;
            case '2': ?>
                <div class="pxp-testim-2 <?php echo esc_attr($margin_class); ?>">
                    <div class="row no-gutters align-items-center">
                        <div class="col-md-6">
                            <div class="pxp-testim-2-caption pt-100 pb-100">
                                <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                                <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>
                                <div id="pxp-testim-2-caption-carousel" class="carousel slide pxp-testim-2-caption-carousel mt-4 mt-md-5" data-ride="carousel" data-pause="false" data-interval="7000">
                                    <div class="carousel-inner">
                                        <?php $counter_1 = 0;
                                        foreach ($posts as $post) {
                                            $text = get_post_meta($post['ID'], 'testimonial_text', true);
                                            $location = get_post_meta($post['ID'], 'testimonial_location', true);
                                            $slide_active_1 = '';
                                            if ($counter_1 == 0) {
                                                $slide_active_1 = 'active';
                                            } ?>
                                            <div class="carousel-item <?php echo esc_attr($slide_active_1); ?>" data-slide="<?php echo esc_attr($counter_1); ?>">
                                                <div class="pxp-testim-2-item-message" style="<?php echo esc_attr($text_color); ?>"><?php echo $text; ?></div>
                                                <div class="pxp-testim-2-item-name" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($post['post_title']); ?></div>
                                                <div class="pxp-testim-2-item-location" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($location); ?></div>
                                            </div>
                                            <?php $counter_1++;
                                        } ?>
                                    </div>
                                    <div class="pxp-carousel-controls mt-4 mt-md-5">
                                        <a class="pxp-carousel-control-prev" role="button" data-slide="prev">
                                            <?php if (is_rtl()) { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                                        <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>
                                            <?php } else { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                        <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>
                                            <?php } ?>
                                        </a>
                                        <a class="pxp-carousel-control-next" role="button" data-slide="next">
                                            <?php if (is_rtl()) { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                        <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>
                                            <?php } else { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                    <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                                        <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                    </g>
                                                </svg>
                                            <?php } ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pxp-testim-2-photos">
                                <div id="pxp-testim-2-photos-carousel" class="carousel slide pxp-testim-2-photos-carousel" data-ride="carousel" data-pause="false" data-interval="false">
                                    <div class="carousel-inner">
                                        <?php $counter_2 = 0;
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
                                            } ?>
                                            <div class="carousel-item <?php echo esc_attr($slide_active_2); ?>" data-slide="<?php echo esc_attr($counter_2); ?>">
                                                <div class="pxp-hero-bg pxp-cover" style="background-image: url(<?php echo esc_url($avatar_photo_src); ?>);"></div>
                                            </div>
                                            <?php $counter_2++;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            default: ?>
                <div class="pxp-testim-1 pt-100 pb-100 <?php echo esc_attr($margin_class); ?> pxp-cover" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
                    <div class="pxp-testim-1-intro">
                        <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                        <div class="pxp-text-light"><?php echo $settings['subtitle']; ?></div>

                        <?php if ($settings['cta_link']['url'] != '') { 
                            $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                            <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                        <?php } ?>
                    </div>
                    <div class="pxp-testim-1-container mt-4 mt-md-5 mt-lg-0">
                        <div class="owl-carousel pxp-testim-1-stage">
                            <?php foreach ($posts as $post) {
                                $text = get_post_meta($post['ID'], 'testimonial_text', true);
                                $location = get_post_meta($post['ID'], 'testimonial_location', true);

                                $avatar = get_post_meta($post['ID'], 'testimonial_avatar', true);
                                if ($avatar != '') {
                                    $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');
                                    $avatar_photo_src = $avatar_photo[0];
                                } else {
                                    $avatar_photo_src = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                                } ?>

                                <div>
                                    <div class="pxp-testim-1-item">
                                        <div class="pxp-testim-1-item-avatar pxp-cover" style="background-image: url(<?php echo esc_url($avatar_photo_src); ?>)"></div>
                                        <div class="pxp-testim-1-item-name"><?php echo esc_html($post['post_title']); ?></div>
                                        <div class="pxp-testim-1-item-location"><?php echo esc_html($location); ?></div>
                                        <div class="pxp-testim-1-item-message"><?php echo $text; ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php break;
        }

        wp_reset_postdata();
        wp_reset_query();
    }
}
?>