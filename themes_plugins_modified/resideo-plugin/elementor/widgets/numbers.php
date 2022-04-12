<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Numbers_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'numbers';
    }

    public function get_title() {
        return __('Numbers', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-list-ol';
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
            'numbers_section',
            [
                'label' => __('Numbers', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $numbers = new \Elementor\Repeater();

        $numbers->add_control(
            'number_sum',
            [
                'label' => __('Number', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => '1',
                'default' => '1'
            ]
        );

        $numbers->add_control(
            'number_sign',
            [
                'label' => __('Sign', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter number sign', 'resideo'),
            ]
        );

        $numbers->add_control(
            'number_delay',
            [
                'label' => __('Delay', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => '1',
                'default' => '1'
            ]
        );

        $numbers->add_control(
            'number_increment',
            [
                'label' => __('Increment', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => '1',
                'default' => '1'
            ]
        );

        $numbers->add_control(
            'number_title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter number title', 'resideo'),
            ]
        );

        $numbers->add_control(
            'number_text',
            [
                'label' => __('Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => __('Enter number text', 'resideo'),
            ]
        );

        $this->add_control(
            'numbers_list',
            [
                'label' => __('Numbers List', 'resideo'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $numbers->get_controls(),
                'title_field' => '{{{ number_title }}}',
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

        if ($bg_image_src == '') { ?>
            <div class="pxp-numbers pt-100 pb-100 <?php echo esc_attr($margin_class); ?>">
                <div class="container">
                    <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <div class="pxp-text-light"><?php echo $settings['subtitle']; ?></div>
                    <div class="row">
                        <?php foreach ($settings['numbers_list'] as $number) { ?>
                            <div class="col-sm-4">
                                <div class="pxp-numbers-item mt-4 mt-md-5">
                                    <div class="pxp-numbers-item-number"><span class="numscroller" data-min="0" data-max="<?php echo esc_attr($number['number_sum']); ?>" data-delay="<?php echo esc_attr($number['number_delay']); ?>" data-increment="<?php echo esc_attr($number['number_increment']); ?>"></span><?php echo esc_html($number['number_sign']); ?></div>
                                    <div class="pxp-numbers-item-title"><?php echo esc_html($number['number_title']); ?></div>
                                    <div class="pxp-numbers-item-text pxp-text-light"><?php echo $number['number_text']; ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="pxp-numbers-fig pxp-cover pt-400 <?php echo esc_attr($margin_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>);">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="pxp-numbers-fig-caption">
                                <div class="row">
                                    <?php foreach ($settings['numbers_list'] as $number) { ?>
                                        <div class="col-4">
                                            <div class="pxp-counters-fig-item">
                                                <div class="pxp-numbers-item-number"><span class="numscroller" data-min="0" data-max="<?php echo esc_attr($number['number_sum']); ?>" data-delay="<?php echo esc_attr($number['number_delay']); ?>" data-increment="<?php echo esc_attr($number['number_increment']); ?>"></span><?php echo esc_html($number['number_sign']); ?></div>
                                                <div class="pxp-numbers-item-title"><?php echo esc_html($number['number_title']); ?></div>
                                                <div class="pxp-numbers-item-text pxp-text-light"><?php echo $number['number_text']; ?></div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }
}
?>