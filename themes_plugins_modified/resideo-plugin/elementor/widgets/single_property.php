<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Single_Property_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'single_property';
    }

    public function get_title() {
        return __('Single Property', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-home';
    }

    public function get_categories() {
        return ['resideo'];
    }

    private function resideo_get_properties() {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'property',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        );

        $props = array('0' => __('Select a property', 'resideo'));

        $posts = get_posts($args);

        foreach ($posts as $post) : setup_postdata($post);
            $prop_id = $post->ID;
            $prop_title = $post->post_title;
            $prop_id_str = strval($prop_id);
            $props[$prop_id_str] = $prop_title;
        endforeach;

        wp_reset_postdata();
        wp_reset_query();

        return $props;
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'property_section',
            [
                'label' => __('Property', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'property',
            [
                'label' => __('Property', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => $this->resideo_get_properties(),
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
            'position',
            [
                'label' => __('Image Position', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Left', 'resideo'),
                    'right' => __('Right', 'resideo'),
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

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : ''; 
        
        if (isset($settings['property']) && $settings['property'] != '' && $settings['property'] != '0') {
            $prop_id = $settings['property'];

            $general_settings = get_option('resideo_general_settings');
            $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
            $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
            $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA'; 

            $currency     = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
            $currency_pos = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
            $locale       = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
            $decimals     = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
            setlocale(LC_MONETARY, $locale);

            $title       = get_the_title($prop_id);
            $price       = get_post_meta($prop_id, 'property_price', true);
            $price_label = get_post_meta($prop_id, 'property_price_label', true);

            if (is_numeric($price)) {
                if ($decimals == '1') {
                    $price = money_format('%!i', $price);
                } else {
                    $price = money_format('%!.0i', $price);
                }
            } else {
                $price_label = '';
                $currency = '';
            }

            $beds  = get_post_meta($prop_id, 'property_beds', true);
            $baths = get_post_meta($prop_id, 'property_baths', true);
            $size  = get_post_meta($prop_id, 'property_size', true);

            $gallery = get_post_meta($prop_id, 'property_gallery', true);
            $photos  = explode(',', $gallery);
            $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-full');

            $link = get_permalink($prop_id); 

            $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : '';
            $cta_id = uniqid(); ?>

            <div class="pxp-single-property <?php echo esc_attr($margin_class); ?>">
                <div class="row no-gutters align-items-center">
                    <?php if ($settings['position'] == 'right') { ?>
                        <div class="col-6">
                            <div class="pxp-single-property-caption">
                                <h2 class="pxp-section-h2"><?php echo esc_html($title); ?></h2>
                                <div class="pxp-single-property-caption-features mt-4">
                                    <?php if ($beds != '') {
                                        echo esc_html($beds) . ' ' . esc_html($beds_label); ?><span>|</span>
                                    <?php }
                                    if ($baths != '') {
                                        echo esc_html($baths) . ' ' . esc_html($baths_label); ?><span>|</span>
                                    <?php }
                                    if ($size != '') {
                                        echo esc_html($size) . ' ' . esc_html($unit);
                                    } ?>
                                </div>
                                <div class="pxp-single-property-caption-price mt-5">
                                    <?php if ($currency_pos == 'before') {
                                        echo esc_html($currency) . esc_html($price); ?> <span><?php echo esc_html($price_label); ?></span>
                                    <?php } else {
                                        echo esc_html($price) . esc_html($currency); ?> <span><?php echo esc_html($price_label); ?></span>
                                    <?php } ?>
                                </div>
                                <a href="<?php echo esc_url($link); ?>" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>"><?php esc_html_e('View Details', 'resideo'); ?></a>
                                <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                            </div>
                        </div>
                        <div class="col-6">
                            <?php if ($first_photo != '') { ?>
                                <div class="pxp-single-property-fig pxp-cover" style="background-image: url(<?php echo esc_url($first_photo[0]); ?>);"></div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="col-6">
                            <?php if ($first_photo != '') { ?>
                                <div class="pxp-single-property-fig pxp-cover" style="background-image: url(<?php echo esc_url($first_photo[0]); ?>);"></div>
                            <?php } ?>
                        </div>
                        <div class="col-6">
                            <div class="pxp-single-property-caption pxp-is-right">
                                <h2 class="pxp-section-h2"><?php echo esc_html($title); ?></h2>
                                <div class="pxp-single-property-caption-features mt-4">
                                    <?php if ($beds != '') {
                                        echo esc_html($beds) . ' ' . esc_html($beds_label); ?><span>|</span>
                                    <?php }
                                    if ($baths != '') {
                                        echo esc_html($baths) . ' ' . esc_html($baths_label); ?><span>|</span>
                                    <?php }
                                    if ($size != '') {
                                        echo esc_html($size) . ' ' . esc_html($unit);
                                    } ?>
                                </div>
                                <div class="pxp-single-property-caption-price mt-5">
                                    <?php if ($currency_pos == 'before') {
                                        echo esc_html($currency) . esc_html($price); ?> <span><?php echo esc_html($price_label); ?></span>
                                    <?php } else {
                                        echo esc_html($price) . esc_html($currency); ?> <span><?php echo esc_html($price_label); ?></span>
                                    <?php } ?>
                                </div>
                                <a href="<?php echo esc_url($link); ?>" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>"><?php esc_html_e('View Details', 'resideo'); ?></a>
                                <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php }
    }
}
?>