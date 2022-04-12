<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Services_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'services';
    }

    public function get_title() {
        return __('Services', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-briefcase';
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
                    'layout!' => ['4', '6', '7']
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
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'cta_section',
            [
                'label' => __('CTA', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout!' => ['1', '4', '6', '7']
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
            'services_section',
            [
                'label' => __('Services', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $services = new \Elementor\Repeater();

        $services->add_control(
            'service_icon',
            [
                'label' => __('Icon', 'resideo'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-camera',
                    'library' => 'solid',
                ],
            ]
        );

        $services->add_control(
            'service_icon_png',
            [
                'label' => __('Icon PNG (optional)', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $services->add_control(
            'service_icon_color',
            [
                'label' => __('Icon Color', 'resideo'),
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

        $services->add_control(
            'service_bg_image',
            [
                'label' => __('Background Image', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $services->add_control(
            'service_title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter serice title', 'resideo'),
            ]
        );

        $services->add_control(
            'service_text',
            [
                'label' => __('Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => __('Enter service text', 'resideo'),
            ]
        );

        $services->add_control(
            'service_link',
            [
                'label' => __('Service Link', 'resideo'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Enter service link', 'resideo'),
                'show_external' => true,
            ]
        );

        $services->add_control(
            'service_cta_label',
            [
                'label' => __('CTA Label', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter service CTA lablel', 'resideo'),
            ]
        );

        $services->add_control(
            'service_cta_color',
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

        $this->add_control(
            'services_list',
            [
                'label' => __('Services List', 'resideo'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $services->get_controls(),
                'title_field' => '{{{ service_title }}}',
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
                        'icon' => 'fa fa-columns',
                    ],
                    '2' => [
                        'title' => __('Layout 2', 'resideo'),
                        'icon' => 'fa fa-list',
                    ],
                    '3' => [
                        'title' => __('Layout 3', 'resideo'),
                        'icon' => 'fa fa-indent',
                    ],
                    '4' => [
                        'title' => __('Layout 4', 'resideo'),
                        'icon' => 'fa fa-th-list',
                    ],
                    '5' => [
                        'title' => __('Layout 5', 'resideo'),
                        'icon' => 'fa fa-plus-square-o',
                    ],
                    '6' => [
                        'title' => __('Layout 6', 'resideo'),
                        'icon' => 'fa fa-clone',
                    ],
                    '7' => [
                        'title' => __('Layout 7', 'resideo'),
                        'icon' => 'fa fa-align-center',
                    ]
                ],
                'default' => '1',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'display',
            [
                'label' => __('Display', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'columns',
                'options' => array(
                    'columns' => __('Columns', 'resideo'),
                    'grid' => __('Grid', 'resideo')
                ),
                'condition' => [
                    'layout' => '4'
                ]
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

        switch ($settings['layout']) {
            case '1': ?>
                <div class="pxp-services pxp-cover pt-100 mb-200 <?php echo esc_attr($margin_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
                    <h2 class="text-center pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                    <div class="pxp-text-light text-center" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                    <div class="container">
                        <div class="pxp-services-container rounded-lg mt-4 mt-md-5">
                            <?php foreach ($settings['services_list'] as $service) {
                                $target = $service['service_link']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $service['service_link']['nofollow'] ? ' rel="nofollow"' : '';

                                $icon_png_src = '';
                                $icon_png = false;
                                if (isset($service['service_icon_png'])) {
                                    $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');

                                    if ($icon_png != false) {
                                        $icon_png_src = $icon_png[0];
                                    }
                                }

                                if ($service['service_link']['url'] != '') { ?>
                                    <a href="<?php echo esc_url($service['service_link']['url']); ?>" class="pxp-services-item" <?php echo $target; ?> <?php echo $nofollow; ?>>
                                <?php } else { ?>
                                    <div class="pxp-services-item">
                                <?php } ?>

                                        <div class="pxp-services-item-fig">
                                            <?php if ($icon_png_src != '') { ?>
                                                <img src="<?php echo esc_url($icon_png_src); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                            <?php } else {
                                                if (is_array($service['service_icon']['value'])) { ?>
                                                    <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                <?php } else { ?>
                                                    <span class="<?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                <?php }
                                            } ?>
                                        </div>

                                        <div class="pxp-services-item-text text-center">
                                            <div class="pxp-services-item-text-title"><?php echo esc_html($service['service_title']); ?></div>
                                            <div class="pxp-services-item-text-sub"><?php echo $service['service_text']; ?></div>
                                        </div>

                                        <div class="pxp-services-item-cta text-uppercase text-center" style="color: <?php echo esc_attr($service['service_cta_color']); ?>"><?php echo esc_html($service['service_cta_label']); ?></div>

                                <?php if ($service['service_link']['url'] != '') { ?>
                                    </a>
                                <?php } else { ?>
                                    </div>
                                <?php }
                            } ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '2': 
                $item_margin = ''; ?>
                <div class="pxp-services-h pt-100 pb-100 <?php echo esc_attr($margin_class); ?>">
                    <div class="container">
                        <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                        <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                        <div class="pxp-services-h-container mt-4 mt-md-5">
                            <div class="pxp-services-h-fig pxp-cover rounded-lg pxp-animate-in" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);"></div>
                            <div class="pxp-services-h-items pxp-animate-in ml-0 ml-lg-5 mt-4 mt-md-5 mt-lg-0">
                                <?php $service_i = 0;
                                foreach ($settings['services_list'] as $service) {
                                    if ($service_i > 0) {
                                        $item_margin = 'mt-3 mt-md-4';
                                    }

                                    $icon_png_src = '';
                                    $icon_png = false;
                                    if (isset($service['service_icon_png'])) {
                                        $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');

                                        if ($icon_png != false) {
                                            $icon_png_src = $icon_png[0];
                                        }
                                    } ?>
                                    <div class="pxp-services-h-item <?php echo esc_attr($item_margin); ?>">
                                        <div class="media">
                                            <?php if ($icon_png_src != '') { ?>
                                                <img src="<?php echo esc_url($icon_png_src); ?>" class="mr-4" alt="<?php echo esc_html($service['service_title']); ?>">
                                            <?php } else {
                                                if (is_array($service['service_icon']['value'])) { ?>
                                                    <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" class="mr-4" alt="<?php echo esc_html($service['service_title']); ?>">
                                                <?php } else { ?>
                                                    <span class="mr-4 <?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                <?php }
                                            } ?>
                                            <div class="media-body">
                                                <h5 class="mt-0"><?php echo esc_html($service['service_title']); ?></h5>
                                                <?php echo $service['service_text']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $service_i++;
                                }
                                if ($settings['cta_link']['url'] != '') {
                                    $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                    <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate pxp-animate-in" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                    <?php if ($cta_color != '') { ?>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?> }</style>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '3': ?>
                <div class="pt-100 pb-100 position-relative <?php echo esc_attr($margin_class); ?>">
                    <div class="pxp-services-c pxp-cover" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);"></div>
                    <div class="pxp-services-c-content">
                        <div class="pxp-services-c-intro">
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
                        <div class="pxp-services-c-container mt-4 mt-md-5 mt-lg-0">
                            <div class="owl-carousel pxp-services-c-stage">
                                <?php foreach ($settings['services_list'] as $service) { 
                                    $icon_png_src = '';
                                    $icon_png = false;
                                    if (isset($service['service_icon_png'])) {
                                        $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');

                                        if ($icon_png != false) {
                                            $icon_png_src = $icon_png[0];
                                        }
                                    } ?>
                                    <div>
                                        <?php $target = $service['service_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $service['service_link']['nofollow'] ? ' rel="nofollow"' : '';

                                        if ($service['service_link']['url'] != '') { ?>
                                            <a href="<?php echo esc_url($service['service_link']['url']); ?>" class="pxp-services-c-item" <?php echo $target; ?> <?php echo $nofollow; ?>>
                                        <?php } else { ?>
                                            <div class="pxp-services-c-item">
                                        <?php } ?>
                                                <div class="pxp-services-c-item-fig">
                                                    <?php if ($icon_png_src != '') { ?>
                                                        <img src="<?php echo esc_url($icon_png_src); ?>" class="mr-4" alt="<?php echo esc_html($service['service_title']); ?>">
                                                    <?php } else {
                                                        if (is_array($service['service_icon']['value'])) { ?>
                                                            <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" class="mr-4" alt="<?php echo esc_html($service['service_title']); ?>">
                                                        <?php } else { ?>
                                                            <span class="mr-4 <?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                        <?php }
                                                    } ?>
                                                </div>
                                                <div class="pxp-services-c-item-text text-center">
                                                    <div class="pxp-services-c-item-text-title"><?php echo esc_html($service['service_title']); ?></div>
                                                    <div class="pxp-services-c-item-text-sub"><?php echo $service['service_text']; ?></div>
                                                </div>
                                                <div class="pxp-services-c-item-cta text-uppercase text-center" style="color: <?php echo esc_attr($service['service_cta_color']); ?>"><?php echo esc_html($service['service_cta_label']); ?></div>
                                        <?php if ($service['service_link']['url'] != '') { ?>
                                            </a>
                                        <?php } else { ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '4': 
                $display = isset($settings['display']) ? $settings['display'] : 'columns';
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

                    $services_count = count($settings['services_list']);
                    if ($services_count == 2) {
                        $item_class = 'col-md-6';
                    } elseif ($services_count % 3 == 0) {
                        $item_class = 'col-md-4';
                    } else {
                        $item_class = 'col-md-6 col-lg-3';
                    }
                } ?>
                <div class="pxp-services-columns <?php echo esc_attr($margin_class); ?>">
                    <div class="container">
                        <div class="row">
                            <div class="<?php echo esc_attr($title_column_class); ?>">
                                <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                                <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>
                            </div>
                            <div class="<?php echo esc_attr($space_class); ?>"></div>
                            <div class="<?php echo esc_attr($items_class); ?>">
                                <div class="row <?php echo esc_attr($items_container_class); ?>">
                                    <?php foreach ($settings['services_list'] as $service) {
                                        $icon_png_src = '';
                                        $icon_png = false;
                                        if (isset($service['service_icon_png'])) {
                                            $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');
    
                                            if ($icon_png != false) {
                                                $icon_png_src = $icon_png[0];
                                            }
                                        } ?>
                                        <div class="<?php echo esc_attr($item_class); ?>">
                                            <div class="pxp-services-columns-item mb-3 mb-md-4">
                                                <div class="pxp-services-columns-item-fig">
                                                    <?php if ($icon_png_src != '') { ?>
                                                        <img src="<?php echo esc_url($icon_png_src); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                    <?php } else {
                                                        if (is_array($service['service_icon']['value'])) { ?>
                                                            <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                        <?php } else { ?>
                                                            <span class="<?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                        <?php }
                                                    } ?>
                                                </div>
                                                <h3 class="mt-3"><?php echo esc_html($service['service_title']); ?></h3>
                                                <div class="pxp-text-light"><?php echo $service['service_text']; ?></div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '5': 
                $acc_id = uniqid();
                if ($bg_image_src == '') { ?>
                    <div class="pxp-services-accordion <?php echo esc_attr($margin_class); ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                                    <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <div class="accordion" id="pxpServicesAccordion<?php echo esc_attr($acc_id); ?>">
                                        <?php $count = 0;
                                        foreach ($settings['services_list'] as $service) {
                                            $item_class = '';
                                            $collapsed = '';
                                            $show = 'show';
                                            if ($count > 0) {
                                                $item_class = 'mt-2 mt-md-3';
                                                $collapsed = 'collapsed';
                                                $show = '';
                                            } ?>
                                            <div class="pxp-services-accordion-item <?php echo esc_attr($item_class); ?>">
                                                <div class="pxp-services-accordion-item-header" id="pxpServicesAccordionHeading<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>">
                                                    <h4 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left <?php echo esc_attr($collapsed); ?>" type="button" data-toggle="collapse" data-target="#pxpServicesAccordionCollapse<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>" aria-expanded="true" aria-controls="pxpServicesAccordionCollapse<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>">
                                                            <span class="pxp-services-accordion-item-icon"></span> <?php echo esc_html($service['service_title']); ?>
                                                        </button>
                                                    </h4>
                                                </div>
                                                <div id="pxpServicesAccordionCollapse<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>" class="collapse <?php echo esc_attr($show); ?>" aria-labelledby="pxpServicesAccordionHeading<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>" data-parent="#pxpServicesAccordion<?php echo esc_attr($acc_id); ?>">
                                                    <div class="pxp-services-accordion-item-body pxp-text-light"><?php echo $service['service_text']; ?></div>
                                                </div>
                                            </div>
                                            <?php $count++;
                                        } ?>
                                    </div>
                                    <?php if ($settings['cta_link']['url'] != '') { 
                                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                        <?php if ($cta_color != '') { ?>
                                            <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?> }</style>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="pxp-services-accordion pxp-services-accordion-has-image <?php echo esc_attr($margin_class); ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5">
                                    <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-6">
                                <div class="pxp-services-accordion-fig pxp-cover" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);"></div>
                            </div>
                            <div class="col-md-6 pxp-services-accordion-right">
                                <div class="pxp-services-accordion-right-container">
                                    <div class="row">
                                        <div class="col-xl-10 col-xxl-6">
                                            <h3 style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></h3>
                                            <div class="accordion mt-4 mt-md-5" id="pxpServicesAccordionFig<?php echo esc_attr($acc_id); ?>">
                                                <?php $count = 0;
                                                foreach ($settings['services_list'] as $service) {
                                                    $item_class = '';
                                                    $collapsed = '';
                                                    $show = 'show';
                                                    if ($count > 0) {
                                                        $item_class = 'mt-2 mt-md-3';
                                                        $collapsed = 'collapsed';
                                                        $show = '';
                                                    } ?>
                                                    <div class="pxp-services-accordion-item <?php echo esc_attr($item_class); ?>">
                                                        <div class="pxp-services-accordion-item-header" id="pxpServicesAccordionFigHeading<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>">
                                                            <h4 class="mb-0">
                                                                <button class="btn btn-link btn-block text-left <?php echo esc_attr($collapsed); ?>" type="button" data-toggle="collapse" data-target="#pxpServicesAccordionFigCollapse<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>" aria-expanded="true" aria-controls="pxpServicesAccordionFigCollapse<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>">
                                                                    <span class="pxp-services-accordion-item-icon"></span> <?php echo esc_html($service['service_title']); ?>
                                                                </button>
                                                            </h4>
                                                        </div>
                                                        <div id="pxpServicesAccordionFigCollapse<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>" class="collapse <?php echo esc_attr($show); ?>" aria-labelledby="pxpServicesAccordionFigHeading<?php echo esc_attr($acc_id); ?>-<?php echo esc_attr($count); ?>" data-parent="#pxpServicesAccordionFig<?php echo esc_attr($acc_id); ?>">
                                                            <div class="pxp-services-accordion-item-body pxp-text-light"><?php echo $service['service_text']; ?></div>
                                                        </div>
                                                    </div>
                                                    <?php $count++;
                                                } ?>
                                            </div>
                                            <?php if ($settings['cta_link']['url'] != '') { 
                                                $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                                $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                                <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                                <?php if ($cta_color != '') { ?>
                                                    <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?> }</style>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            break;
            case '6': 
                $section_id = uniqid(); ?>

                <div class="pxp-services-tabs <?php echo esc_attr($margin_class); ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="pxp-section-h2 d-block d-lg-none" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                                <div class="pxp-text-light mt-3 mt-lg-4 d-block d-lg-none" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                                <div class="pxp-services-tabs-items mt-4 mt-md-5 mt-lg-0">
                                    <div id="pxp-services-tabs-carousel-<?php echo esc_attr($section_id); ?>" class="carousel slide carousel-fade pxp-services-tabs-carousel" data-ride="carousel" data-interval="false">
                                        <div class="carousel-inner">
                                            <?php $count_services = 0;
                                            foreach ($settings['services_list'] as $service) {
                                                $service_bg_src = '';
                                                $service_bg = false;
                                                if (isset($service['service_bg_image'])) {
                                                    $service_bg = wp_get_attachment_image_src($service['service_bg_image']['id'], 'pxp-gallery');
                                                    if ($service_bg != false) {
                                                        $service_bg_src = $service_bg[0];
                                                    }
                                                }
                                                $active_class = $count_services == 0 ? 'active' : ''; ?>
                                                <div class="carousel-item pxp-cover <?php echo esc_attr($active_class); ?>" style="background-image: url(<?php echo esc_url($service_bg_src); ?>);"></div>
                                                <?php $count_services++;
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="pxp-services-tabs-items-content">
                                        <div id="pxp-services-tabs-content-carousel-<?php echo esc_attr($section_id); ?>" class="carousel slide carousel-fade" data-ride="carousel" data-interval="false">
                                            <div class="carousel-inner">
                                                <?php $count_services = 0;
                                                foreach ($settings['services_list'] as $service) {
                                                    $target = $service['service_link']['is_external'] ? ' target="_blank"' : '';
                                                    $nofollow = $service['service_link']['nofollow'] ? ' rel="nofollow"' : '';

                                                    $icon_png_src = '';
                                                    $icon_png = false;
                                                    if (isset($service['service_icon_png'])) {
                                                        $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');
                                                        if ($icon_png != false) {
                                                            $icon_png_src = $icon_png[0];
                                                        }
                                                    }

                                                    $active_class = $count_services == 0 ? 'active' : ''; ?>
                                                    <div class="carousel-item <?php echo esc_attr($active_class); ?>">
                                                        <?php if ($service['service_link']['url'] != '') { ?>
                                                            <a href="<?php echo esc_url($service['service_link']['url']); ?>" class="pxp-services-tabs-content-item" <?php echo $target; ?> <?php echo $nofollow; ?>>
                                                        <?php } else { ?>
                                                            <div class="pxp-services-tabs-content-item">
                                                        <?php } ?>

                                                            <div class="pxp-services-tabs-content-item-fig">
                                                                <?php if ($icon_png_src != '') { ?>
                                                                    <img src="<?php echo esc_url($icon_png_src); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                                <?php } else {
                                                                    if (is_array($service['service_icon']['value'])) { ?>
                                                                        <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                                    <?php } else { ?>
                                                                        <span class="<?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                                    <?php }
                                                                } ?>
                                                            </div>

                                                            <div class="pxp-services-tabs-content-item-text"><?php echo $service['service_text']; ?></div>
                                                            <?php if ($service['service_link']['url'] != '') {
                                                                $service_cta_color = isset($service['service_cta_color']) ? $service['service_cta_color'] : ''; ?>
                                                                <div class="pxp-services-tabs-content-item-cta-container">
                                                                    <div class="pxp-services-tabs-content-item-cta text-uppercase" style="color: <?php echo esc_attr($service_cta_color); ?>">
                                                                        <span><?php echo esc_html($service['service_cta_label']); ?></span>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                                                            <?php if (is_rtl()) { ?>
                                                                                <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                                                    <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: <?php echo esc_attr($service_cta_color); ?>"/>
                                                                                    <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: <?php echo esc_attr($service_cta_color); ?>"/>
                                                                                    <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: <?php echo esc_attr($service_cta_color); ?>"/>
                                                                                </g>
                                                                            <?php } else { ?>
                                                                                <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                                                                    <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: <?php echo esc_attr($service_cta_color); ?>" />
                                                                                    <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: <?php echo esc_attr($service_cta_color); ?>" />
                                                                                    <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2" style="stroke: <?php echo esc_attr($service_cta_color); ?>" />
                                                                                </g>
                                                                            <?php } ?>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            <?php }

                                                        if ($service['service_link']['url'] != '') { ?>
                                                            </a>
                                                        <?php } else { ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php $count_services++;
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-5">
                                <h2 class="pxp-section-h2 d-none d-lg-block" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                                <div class="pxp-text-light mt-3 mt-lg-4 d-none d-lg-block" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                                <ul class="carousel-indicators" data-id="<?php echo esc_attr($section_id); ?>">
                                    <?php $count_services = 0;
                                    foreach ($settings['services_list'] as $service) {
                                        $active_class = $count_services == 0 ? 'active' : ''; ?>
                                        <li data-target="#pxp-services-tabs-carousel-<?php echo esc_attr($section_id); ?>" data-slide-to="<?php echo esc_attr($count_services); ?>" class="<?php echo esc_attr($active_class); ?>"><?php echo esc_attr($service['service_title']); ?></li>
                                        <?php $count_services++;
                                    } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '7': ?>
                <div class="pxp-services-tilt <?php echo esc_attr($margin_class); ?>">
                    <h2 class="text-center pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                    <div class="pxp-text-light text-center" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                    <div class="container mt-4 mt-md-5">
                        <div class="row justify-content-center">
                            <?php foreach ($settings['services_list'] as $service) {
                                $service_bg_src = '';
                                $service_bg = false;
                                if (isset($service['service_bg_image'])) {
                                    $service_bg = wp_get_attachment_image_src($service['service_bg_image']['id'], 'pxp-gallery');
                                    if ($service_bg != false) {
                                        $service_bg_src = $service_bg[0];
                                    }
                                }
                                
                                $target = $service['service_link']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $service['service_link']['nofollow'] ? ' rel="nofollow"' : '';

                                $icon_png_src = '';
                                $icon_png = false;
                                if (isset($service['service_icon_png'])) {
                                    $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');
                                    if ($icon_png != false) {
                                        $icon_png_src = $icon_png[0];
                                    }
                                } ?>

                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <?php if ($service['service_link']['url'] != '') { ?>
                                        <a href="<?php echo esc_url($service['service_link']['url']); ?>" class="pxp-services-tilt-item" <?php echo $target; ?> <?php echo $nofollow; ?>>
                                    <?php } else { ?>
                                        <div class="pxp-services-tilt-item">
                                    <?php } ?>

                                    <figure class="pxp-services-tilt-item-fig pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($service_bg_src); ?>);">
                                        <figcaption class="pxp-services-tilt-item-caption">
                                            <div class="pxp-services-tilt-item-caption-icon">
                                                <?php if ($icon_png_src != '') { ?>
                                                    <img src="<?php echo esc_url($icon_png_src); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                <?php } else {
                                                    if (is_array($service['service_icon']['value'])) { ?>
                                                        <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                    <?php } else { ?>
                                                        <span class="<?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                    <?php }
                                                } ?>
                                            </div>
                                            <div class="pxp-services-tilt-item-caption-title"><?php echo esc_html($service['service_title']); ?></div>
                                            <div class="pxp-services-tilt-item-caption-text"><?php echo $service['service_text']; ?></div>
                                        </figcaption>
                                    </figure>

                                    <?php if ($service['service_link']['url'] != '') { ?>
                                        </a>
                                    <?php } else { ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php break;
            default: ?>
                <div class="pxp-services pxp-cover pt-100 mb-200 <?php echo esc_attr($margin_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
                    <h2 class="text-center pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                    <div class="pxp-text-light text-center" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>

                    <div class="container">
                        <div class="pxp-services-container rounded-lg mt-4 mt-md-5">
                            <?php foreach ($settings['services_list'] as $service) {
                                $target = $service['service_link']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $service['service_link']['nofollow'] ? ' rel="nofollow"' : '';

                                $icon_png_src = '';
                                $icon_png = false;
                                if (isset($service['service_icon_png'])) {
                                    $icon_png = wp_get_attachment_image_src($service['service_icon_png']['id'], 'pxp-full');

                                    if ($icon_png != false) {
                                        $icon_png_src = $icon_png[0];
                                    }
                                }

                                if ($service['service_link']['url'] != '') { ?>
                                    <a href="<?php echo esc_url($service['service_link']['url']); ?>" class="pxp-services-item" <?php echo $target; ?> <?php echo $nofollow; ?>>
                                <?php } else { ?>
                                    <div class="pxp-services-item">
                                <?php } ?>

                                        <div class="pxp-services-item-fig">
                                            <?php if ($icon_png_src != '') { ?>
                                                <img src="<?php echo esc_url($icon_png_src); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                            <?php } else {
                                                if (is_array($service['service_icon']['value'])) { ?>
                                                    <img src="<?php echo esc_url($service['service_icon']['value']['url']); ?>" alt="<?php echo esc_html($service['service_title']); ?>">
                                                <?php } else { ?>
                                                    <span class="<?php echo esc_attr($service['service_icon']['value']); ?>" style="color: <?php echo esc_attr($service['service_icon_color']); ?>"></span>
                                                <?php }
                                            } ?>
                                        </div>

                                        <div class="pxp-services-item-text text-center">
                                            <div class="pxp-services-item-text-title"><?php echo esc_html($service['service_title']); ?></div>
                                            <div class="pxp-services-item-text-sub"><?php echo $service['service_text']; ?></div>
                                        </div>

                                        <div class="pxp-services-item-cta text-uppercase text-center" style="color: <?php echo esc_attr($service['service_cta_color']); ?>"><?php echo esc_html($service['service_cta_label']); ?></div>

                                <?php if ($service['service_link']['url'] != '') { ?>
                                    </a>
                                <?php } else { ?>
                                    </div>
                                <?php }
                            } ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            <?php break;
        } 
    }
}
?>