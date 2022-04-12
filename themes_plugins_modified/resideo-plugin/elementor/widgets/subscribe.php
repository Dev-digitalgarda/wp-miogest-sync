<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Subscribe_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'subscribe';
    }

    public function get_title() {
        return __('Subscribe', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-envelope';
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
        $cta_color = isset($settings['text_color']) ? $settings['text_color'] : ''; ?>

        <div class="pxp-subscribe-section pxp-full pxp-cover pt-100 pb-100 <?php echo esc_attr($margin_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
            <div class="container">
                <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>
                <div class="row mt-4 mt-md-5">
                    <div class="col-sm-12 col-md-6">
                        <div class="pxp-subscribe-1-form" id="pxp-subscribe-form">
                            <?php wp_nonce_field('subscribe_ajax_nonce', 'security-subscribe', true, true); ?>
                            <div class="pxp-subscribe-form-response"></div>
                            <input type="text" id="pxp-subscribe-email" name="pxp-subscribe-email" class="form-control" placeholder="<?php esc_html_e('Enter your email...', 'resideo'); ?>">
                            <a href="javascript:void(0);" id="pxp-subscribe-form-btn" class="pxp-primary-cta text-uppercase pxp-animate mt-3 mt-md-4" style="color: <?php echo esc_attr($cta_color); ?>"><img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Subscribe', 'resideo'); ?></a>
                            <?php if ($cta_color != '') { ?>
                                <style>.pxp-primary-cta#pxp-subscribe-form-btn:after { border-top: 2px solid <?php echo esc_html($cta_color); ?> }</style>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}
?>