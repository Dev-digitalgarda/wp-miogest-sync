<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Gallery_Carousel_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'gallery_carousel';
    }

    public function get_title() {
        return __('Gallery Carousel', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-image';
    }

    public function get_categories() {
        return ['resideo'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'photos_section',
            [
                'label' => __('Photos', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $photos = new \Elementor\Repeater();

        $photos->add_control(
            'photo',
            [
                'label' => __('Photo', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'photos_list',
            [
                'label' => __('Photos List', 'resideo'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $photos->get_controls()
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display(); ?>

        <div class="pxp-gallery-carousel mt-100">
            <div class="owl-carousel pxp-gallery-carousel-stage">
                <?php foreach ($settings['photos_list'] as $photo) {
                    $image_src = '';
                    $image = false;
                    if (isset($photo['photo'])) {
                        $image = wp_get_attachment_image_src($photo['photo']['id'], 'pxp-full');

                        if ($image != false) {
                            $image_src = $image[0];
                        }
                    } ?>

                    <div>
                        <div class="pxp-gallery-carousel-item pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($image_src); ?>);"></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }
}
?>