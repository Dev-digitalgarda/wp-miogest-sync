<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Awards_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'awards';
    }

    public function get_title() {
        return __('Awards', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-trophy';
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
            'text',
            [
                'label' => __('Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'placeholder' => __('Enter text', 'resideo'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'awards_section',
            [
                'label' => __('Awards', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $awards = new \Elementor\Repeater();

        $awards->add_control(
            'award_image',
            [
                'label' => __('Image', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $awards->add_control(
            'award_title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter award title', 'resideo'),
            ]
        );

        $this->add_control(
            'awards_list',
            [
                'label' => __('Awards List', 'resideo'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $awards->get_controls(),
                'title_field' => '{{{ award_title }}}',
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

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : ''; ?>

        <div class="pxp-awards <?php echo esc_attr($margin_class); ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                        <div class="pxp-text-light"><?php echo $settings['subtitle']; ?></div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <?php if ($settings['text'] != '') { ?>
                            <div class="pxp-text-light"><?php echo $settings['text']; ?></div>
                        <?php } ?>
                        <div class="row">
                            <?php foreach ($settings['awards_list'] as $award) { ?>
                                <div class="col-sm-4">
                                    <div class="pxp-awards-item">
                                        <?php $image = false;
                                        if (isset($award['award_image'])) {
                                            $image = wp_get_attachment_image_src($award['award_image']['id'], 'pxp-gallery');

                                            if ($image != false) { ?>
                                                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($award['award_title']); ?>">
                                            <?php }
                                        } ?>
                                        <p class="pxp-awards-item-title"><?php echo esc_html($award['award_title']); ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}
?>