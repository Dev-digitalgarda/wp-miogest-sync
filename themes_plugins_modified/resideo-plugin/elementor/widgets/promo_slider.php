<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Promo_Slider_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'promo_slider';
    }

    public function get_title() {
        return __('Promo Slider', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-object-group';
    }

    public function get_categories() {
        return ['resideo'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'ctas_section',
            [
                'label' => __('CTAs', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ctas_color',
            [
                'label' => __('CTAs Color', 'resideo'),
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
            'transition_section',
            [
                'label' => __('Transition', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'interval',
            [
                'label' => __('Autoslide Interval (seconds)', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => '0'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slides_section',
            [
                'label' => __('Slides', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $slides = new \Elementor\Repeater();

        $slides->add_control(
            'slide_image',
            [
                'label' => __('Image', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $slides->add_control(
            'slide_title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter slide title', 'resideo'),
            ]
        );

        $slides->add_control(
            'slide_text',
            [
                'label' => __('Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => __('Enter slide text', 'resideo'),
            ]
        );

        $slides->add_control(
            'slide_cta_link',
            [
                'label' => __('CTA Link', 'resideo'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Enter slide CTA link', 'resideo'),
                'show_external' => true,
            ]
        );

        $slides->add_control(
            'slide_cta_label',
            [
                'label' => __('CTA Label', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter slide CTA label', 'resideo'),
            ]
        );

        $this->add_control(
            'slides_list',
            [
                'label' => __('Slides List', 'resideo'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $slides->get_controls(),
                'title_field' => '{{{ slide_title }}}',
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
                )
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

        $section_class = 'pb-300';
        $caption_class = '';

        $ctas_color = isset($settings['ctas_color']) ? $settings['ctas_color'] : '';
        $uniq_id = uniqid();

        $interval   = isset($settings['interval']) ? $settings['interval'] : '';

        $data_interval = 'false';
        if ($interval != '' && $interval != '0') {
            $data_interval = intval($interval) * 1000;
        }

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
        } ?>

        <div class="pxp-promo-slider <?php echo esc_attr($margin_class); ?> <?php echo esc_attr($section_class); ?>">
            <div id="pxp-promo-slider-carousel-<?php echo esc_attr($uniq_id); ?>" class="carousel slide pxp-promo-slider-carousel" data-ride="carousel" data-pause="false" data-interval="<?php echo esc_attr($data_interval); ?>">
                <div class="carousel-inner">
                    <?php $count = 0;
                    foreach ($settings['slides_list'] as $slide) {
                        $active_slide = '';
                        if ($count == 0) {
                            $active_slide = 'active';
                        }
                        $image_src = '';
                        if (isset($slide['slide_image'])) {
                            $image = wp_get_attachment_image_src($slide['slide_image']['id'], 'pxp-full');

                            if ($image != false) {
                                $image_src = $image[0];
                            }
                        } ?>
                        <div class="carousel-item <?php echo esc_attr($active_slide); ?> pxp-cover" style="background-image: url(<?php echo esc_url($image_src); ?>);"></div>
                        <?php $count++;
                    } ?>
                </div>
            </div>
            <div class="container">
                <div class="row <?php echo esc_attr($caption_class); ?>">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="pxp-promo-slider-caption pxp-animate-in">
                            <?php $count_captions = 0;
                            foreach ($settings['slides_list'] as $caption) {
                                $active_caption = '';
                                if ($count_captions == 0) {
                                    $active_caption = 'pxp-active';
                                } ?>
                                <div class="pxp-promo-slider-caption-item <?php echo esc_attr($active_caption); ?>" data-index="<?php echo esc_attr($count_captions); ?>">
                                    <h2 class="pxp-section-h2"><?php echo esc_html($caption['slide_title']); ?></h2>
                                    <div class="pxp-text-light"><?php echo $caption['slide_text']; ?></div>
                                    <?php if ($caption['slide_cta_link']['url'] != '') {
                                        $target = $caption['slide_cta_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $caption['slide_cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url($caption['slide_cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-4 pxp-animate" id="cta-<?php echo esc_attr($uniq_id); ?><?php echo esc_attr($count_captions); ?>" style="color: <?php echo esc_attr($ctas_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($caption['slide_cta_label']); ?></a>
                                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($uniq_id); ?><?php echo esc_attr($count_captions); ?>:after { border-top: 2px solid <?php echo esc_html($ctas_color); ?>; }</style>
                                    <?php } ?>
                                </div>
                                <?php $count_captions++;
                            } ?>
                            <ol class="pxp-promo-slider-caption-dots mt-3 mt-md-4">
                                <?php $count_dots = 0;
                                foreach ($settings['slides_list'] as $dot) {
                                    $active_dot = '';
                                    if ($count_dots == 0) {
                                        $active_dot = 'active';
                                    } ?>
                                    <li data-target="#pxp-promo-slider-carousel-<?php echo esc_attr($uniq_id); ?>" data-slide-to="<?php echo esc_attr($count_dots); ?>" class="<?php echo esc_attr($active_dot); ?>"><div></div></li>
                                    <?php $count_dots++;
                                } ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}
?>