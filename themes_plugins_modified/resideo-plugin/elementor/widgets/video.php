<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Video_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'video';
    }

    public function get_title() {
        return __('Video', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-youtube-play';
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
            'video_section',
            [
                'label' => __('Video', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video',
            [
                'label' => __('Youtube video ID', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => 'd1EaFyBqH5o',
                'description' => 'E.g. <span style="color: #999;">https://www.youtube.com/watch?v=</span><strong style="color: green; font-style: normal;">d1EaFyBqH5o</strong>'
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
        $modal_id = uniqid(); ?>

        <div class="pxp-video-section pxp-cover pt-100 pb-100 <?php echo esc_attr($margin_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
            <div class="container">
                <h2 class="pxp-section-h2 text-center" style="color: <?php echo esc_attr($settings['text_color']); ?>"><?php echo esc_html($settings['title']); ?></h2>
                <div class="pxp-text-light text-center" style="color: <?php echo esc_attr($settings['text_color']); ?>"><?php echo $settings['subtitle']; ?></div>
                <div class="pt-100 pb-100 text-center">
                    <a href="javascript:void(0)" class="pxp-video-section-trigger" data-toggle="modal" data-target="#pxp-video-section-modal-<?php echo esc_attr($modal_id); ?>">
                        <span class="fa fa-play"></span>
                    </a>
                </div>
            </div>

            <div class="pxp-video-section-modal modal" id="pxp-video-section-modal-<?php echo esc_attr($modal_id); ?>" data-id="<?php echo esc_attr($settings['video']); ?>" tabindex="-1" aria-labelledby="pxp-video-section-modal-<?php echo esc_attr($modal_id); ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="pxp-video-section-modal-container" id="pxp-video-section-modal-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}
?>