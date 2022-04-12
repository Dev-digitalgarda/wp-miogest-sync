<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Promo_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'promo';
    }

    public function get_title() {
        return __('Promo', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-info-circle';
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

        $this->end_controls_section();

        $this->start_controls_section(
            'background_section',
            [
                'label' => __('Background Image', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout' => ['1', '2', '3']
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

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => __('Enter promo text', 'resideo'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'caption_section',
            [
                'label' => __('Caption', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout' => '2'
                ]
            ]
        );

        $this->add_control(
            'caption_icon',
            [
                'label' => __('Icon', 'resideo'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-camera',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'caption_icon_png',
            [
                'label' => __('Icon PNG (optional)', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'caption_icon_color',
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

        $this->add_control(
            'caption_title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter caption title', 'resideo'),
            ]
        );

        $this->add_control(
            'caption_text',
            [
                'label' => __('Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => __('Enter caption text', 'resideo'),
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
                        'icon' => 'fa fa-window-maximize',
                    ],
                    '2' => [
                        'title' => __('Layout 2', 'resideo'),
                        'icon' => 'fa fa-clone',
                    ],
                    '3' => [
                        'title' => __('Layout 3', 'resideo'),
                        'icon' => 'fa fa-indent',
                    ],
                    '4' => [
                        'title' => __('Layout 4', 'resideo'),
                        'icon' => 'fa fa-align-center',
                    ]
                ],
                'default' => '1',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'caption_position',
            [
                'label' => __('Caption Position', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'topLeft',
                'options' => array(
                    'topLeft' => __('Top Left', 'resideo'),
                    'topRight' => __('Top Right', 'resideo'),
                    'centerLeft' => __('Center Left', 'resideo'),
                    'center' => __('Center', 'resideo'),
                    'centerRight' => __('Center Right', 'resideo'),
                    'bottomLeft' => __('Bottom Left', 'resideo'),
                    'bottomRight' => __('Bottom Right', 'resideo'),
                ),
                'condition' => [
                    'layout' => '1'
                ]
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => __('Image Position', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => __('Left', 'resideo'),
                    'right' => __('Right', 'resideo')
                ),
                'condition' => [
                    'layout' => '3'
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
                'return_value' => 'yes',
                'condition' => [
                    'layout' => ['1', '2', '3']
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';

        $section_class = 'pb-300';
        $caption_class = '';

        switch ($settings['caption_position']) {
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

        $cta_id = uniqid();
        $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : ''; 

        $layout = isset($settings['layout']) ? $settings['layout'] : '1';
        $image_position = isset($settings['image_position']) ? $settings['image_position'] : 'left';

        switch ($layout) {
            case '1': 
                $bg_image_src = '';
                $bg_image = false;
                if (isset($settings['background_image'])) {
                    $bg_image = wp_get_attachment_image_src($settings['background_image']['id'], 'pxp-full');

                    if ($bg_image != false) {
                        $bg_image_src = $bg_image[0];
                    }
                } ?>
                <div class="pxp-cta-1 pxp-cover <?php echo esc_attr($margin_class); ?> <?php echo esc_attr($section_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>)">
                    <div class="container">
                        <div class="row <?php echo esc_attr($caption_class); ?>">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="pxp-cta-1-caption pxp-animate-in">
                                    <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                                    <div class="pxp-text-light"><?php echo $settings['text']; ?></div>

                                    <?php if ($settings['cta_link']['url'] != '') { 
                                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '2': 
                $bg_image_src = '';
                $bg_image = false;
                if (isset($settings['background_image'])) {
                    $bg_image = wp_get_attachment_image_src($settings['background_image']['id'], 'pxp-gallery');

                    if ($bg_image != false) {
                        $bg_image_src = $bg_image[0];
                    }
                }

                $icon_png_src = '';
                $icon_png = false;
                if (isset($settings['caption_icon_png'])) {
                    $icon_png = wp_get_attachment_image_src($settings['caption_icon_png']['id'], 'pxp-full');

                    if ($icon_png != false) {
                        $icon_png_src = $icon_png[0];
                    }
                } ?>
                <div class="pxp-cta-2 <?php echo esc_attr($margin_class); ?>">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="pxp-section-h2 d-block d-lg-none"><?php echo esc_html($settings['title']); ?></h2>
                                <div class="pxp-text-light mt-3 mt-lg-4 d-block d-lg-none"><?php echo $settings['text']; ?></div>
                                <div class="pxp-cta-2-left mt-4 mt-md-5 mt-lg-0">
                                    <div class="pxp-cta-2-left-image pxp-cover" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);"></div>
                                    <div class="pxp-cta-2-left-content">
                                        <div class="pxp-cta-2-left-content-item">
                                            <div class="pxp-cta-2-left-content-item-fig">
                                                <?php if ($icon_png_src != '') { ?>
                                                    <img src="<?php echo esc_url($icon_png_src); ?>" alt="<?php echo esc_attr($settings['caption_title']); ?>" />
                                                <?php } else {
                                                    if (is_array($settings['caption_icon']['value'])) { ?>
                                                        <img src="<?php echo esc_url($settings['caption_icon']['value']['url']); ?>" alt="<?php echo esc_attr($settings['caption_title']); ?>">
                                                    <?php } else { ?>
                                                        <span class="<?php echo esc_attr($settings['caption_icon']['value']); ?>" style="color: <?php echo esc_attr($settings['caption_icon_color']); ?>"></span>
                                                    <?php }
                                                } ?>
                                            </div>
                                            <div class="pxp-cta-2-left-content-item-title"><?php echo esc_html($settings['caption_title']); ?></div>
                                            <div class="pxp-cta-2-left-content-item-text"><?php echo $settings['caption_text']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-5">
                                <h2 class="pxp-section-h2 d-none d-lg-block"><?php echo esc_html($settings['title']); ?></h2>
                                <div class="pxp-text-light mt-3 mt-lg-4 d-none d-lg-block"><?php echo $settings['text']; ?></div>

                                <?php if ($settings['cta_link']['url'] != '') { 
                                    $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                    <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                    <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            case '3': 
                $bg_image_src = '';
                $bg_image = false;
                if (isset($settings['background_image'])) {
                    $bg_image = wp_get_attachment_image_src($settings['background_image']['id'], 'pxp-gallery');

                    if ($bg_image != false) {
                        $bg_image_src = $bg_image[0];
                    }
                } ?>
                <div class="pxp-cta-3 <?php echo esc_attr($margin_class); ?>">
                    <div class="container">
                        <div class="row align-items-center">
                            <?php  if ($image_position == 'left') { ?>
                                <div class="col-lg-5">
                                    <div class="pxp-cta-3-image pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);"></div>
                                </div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4">
                                    <h2 class="pxp-section-h2 mt-3 mt-md-5 mt-lg-0"><?php echo esc_html($settings['title']); ?></h2>
                                    <div class="pxp-text-light mt-3 mt-lg-4"><?php echo $settings['text']; ?></div>

                                    <?php if ($settings['cta_link']['url'] != '') { 
                                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                                    <?php } ?>
                                </div>
                                <div class="col-lg-1"></div>
                            <?php } else { ?>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-4">
                                    <h2 class="pxp-section-h2 mt-3 mt-md-5 mt-lg-0"><?php echo esc_html($settings['title']); ?></h2>
                                    <div class="pxp-text-light mt-3 mt-lg-4"><?php echo $settings['text']; ?></div>

                                    <?php if ($settings['cta_link']['url'] != '') { 
                                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                                    <?php } ?>
                                </div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-5 order-first order-lg-last">
                                    <div class="pxp-cta-3-image pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);"></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php break;
            case '4': ?>
                <div class="pxp-cta-4 mt-200 mb-200">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                                <div class="text-center">
                                    <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                                    <div class="pxp-text-light mt-3 mt-lg-4"><?php echo $settings['text']; ?></div>

                                    <?php if ($settings['cta_link']['url'] != '') { 
                                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
            default: 
                $bg_image_src = '';
                $bg_image = false;
                if (isset($settings['background_image'])) {
                    $bg_image = wp_get_attachment_image_src($settings['background_image']['id'], 'pxp-full');

                    if ($bg_image != false) {
                        $bg_image_src = $bg_image[0];
                    }
                } ?>
                <div class="pxp-cta-1 pxp-cover <?php echo esc_attr($margin_class); ?> <?php echo esc_attr($section_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>)">
                    <div class="container">
                        <div class="row <?php echo esc_attr($caption_class); ?>">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="pxp-cta-1-caption pxp-animate-in">
                                    <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                                    <div class="pxp-text-light"><?php echo $settings['text']; ?></div>

                                    <?php if ($settings['cta_link']['url'] != '') { 
                                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break;
        }
    }
}
?>