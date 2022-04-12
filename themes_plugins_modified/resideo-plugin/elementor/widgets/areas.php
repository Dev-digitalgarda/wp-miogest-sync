<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Areas_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'areas';
    }

    public function get_title() {
        return __('Areas', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-th';
    }

    public function get_categories() {
        return ['resideo'];
    }

    private function resideo_get_cities() {
        $resideo_cities_settings = get_option('resideo_cities_settings');

        $cities = array('' => __('Select a city', 'resideo'));

        if (is_array($resideo_cities_settings) && count($resideo_cities_settings) > 0) {
            uasort($resideo_cities_settings, "resideo_compare_position");

            foreach ($resideo_cities_settings as $key => $value) {
                $cities[$key] = $value['name'];
            }
        }

        return $cities;
    }

    private function resideo_get_neighborhoods() {
        $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings');

        $neighborhoods = array('' => __('Select a neighborhood', 'resideo'));

        if (is_array($resideo_neighborhoods_settings) && count($resideo_neighborhoods_settings) > 0) {
            uasort($resideo_neighborhoods_settings, "resideo_compare_position");

            foreach ($resideo_neighborhoods_settings as $key => $value) {
                $neighborhoods[$key] = $value['name'];
            }
        }

        return $neighborhoods;
    }

    private function resideo_count_properties($area) {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'property',
            'post_status'      => 'publish',
            'suppress_filters' => false,
        );

        $args['meta_query'] = array('relation' => 'AND');

        if ($area['area_city'] != '') {
            array_push($args['meta_query'], array(
                'key'   => 'locality',
                'value' => $area['area_city']
            ));
        }

        if ($area['area_neighborhood'] != '') {
            array_push($args['meta_query'], array(
                'key'   => 'neighborhood',
                'value' => $area['area_neighborhood']
            ));
        }

        $query = new WP_Query($args);

        wp_reset_postdata();
        wp_reset_query();

        return $query->found_posts;
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
            'areas_section',
            [
                'label' => __('Areas', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $areas = new \Elementor\Repeater();

        $areas->add_control(
            'area_image',
            [
                'label' => __('Image', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $fields_settings   = get_option('resideo_prop_fields_settings');
        $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
        $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';

        if ($neighborhood_type == 'list') {
            $areas->add_control(
                'area_neighborhood',
                [
                    'label' => __('Neighborhood', 'resideo'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $this->resideo_get_neighborhoods(),
                ]
            );
        } else {
            $areas->add_control(
                'area_neighborhood',
                [
                    'label' => __('Neighborhood', 'resideo'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'input_type' => 'string',
                    'placeholder' => __('Enter neighborhood name', 'resideo'),
                ]
            );
        }

        if ($city_type == 'list') {
            $areas->add_control(
                'area_city',
                [
                    'label' => __('City', 'resideo'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $this->resideo_get_cities(),
                ]
            );
        } else {
            $areas->add_control(
                'area_city',
                [
                    'label' => __('City', 'resideo'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'input_type' => 'string',
                    'placeholder' => __('Enter city name', 'resideo'),
                ]
            );
        }

        $areas->add_control(
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
            'areas_list',
            [
                'label' => __('Areas List', 'resideo'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $areas->get_controls()
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
                        'icon' => 'fa fa-th-large',
                    ],
                    '2' => [
                        'title' => __('Layout 2', 'resideo'),
                        'icon' => 'fa fa-arrows-h',
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

        $results_page = resideo_get_search_properties_link();
        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';

        $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : '';
        $cta_id = uniqid();
        
        $layout = isset($settings['layout']) ? $settings['layout'] : '1';

        $fields_settings   = get_option('resideo_prop_fields_settings');
        $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
        $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';

        $neighborhoods = array();
        if ($neighborhood_type == 'list') {
            $neighborhoods = $this->resideo_get_neighborhoods();
        }

        $cities = array();
        if ($city_type == 'list') {
            $cities = $this->resideo_get_cities();
        }

        switch ($layout) {
            case '1': ?>
                <div class="container <?php echo esc_attr($margin_class); ?>">
                    <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <div class="pxp-text-light"><?php echo $settings['subtitle']; ?></div>

                    <div class="row mt-4 mt-md-5">
                        <?php foreach ($settings['areas_list'] as $area) {
                            $image_src = '';
                            $image = false;
                            if (isset($area['area_image'])) {
                                $image = wp_get_attachment_image_src($area['area_image']['id'], 'pxp-gallery');

                                if ($image != false) {
                                    $image_src = $image[0];
                                }
                            }

                            $area_link = add_query_arg(
                                array(
                                    'search_neighborhood' => $area['area_neighborhood'],
                                    'search_city' => $area['area_city'],
                                ), $results_page
                            );

                            $properties_count = $this->resideo_count_properties($area); 

                            $area_cta_color = isset($area['cta_color']) ? $area['cta_color'] : ''; ?>

                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <a href="<?php echo esc_url($area_link); ?>" class="pxp-areas-1-item rounded-lg">
                                    <div class="pxp-areas-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($image_src); ?>);"></div>
                                    <div class="pxp-areas-1-item-details">
                                        <?php if (count($neighborhoods) > 0 && isset($neighborhoods[$area['area_neighborhood']])) { ?>
                                            <div class="pxp-areas-1-item-details-area"><?php echo esc_html($neighborhoods[$area['area_neighborhood']]); ?></div>
                                        <?php } else { ?>
                                            <div class="pxp-areas-1-item-details-area"><?php echo esc_html($area['area_neighborhood']); ?></div>
                                        <?php }

                                        if (count($cities) > 0 && isset($cities[$area['area_city']])) { ?>
                                            <div class="pxp-areas-1-item-details-city"><?php echo esc_html($cities[$area['area_city']]); ?></div>
                                        <?php } else { ?>
                                            <div class="pxp-areas-1-item-details-city"><?php echo esc_html($area['area_city']); ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="pxp-areas-1-item-counter"><span><?php echo esc_html($properties_count) . ' ' . __('Properties', 'resideo'); ?></span></div>
                                    <div class="pxp-areas-1-item-cta text-uppercase" style="color: <?php echo esc_attr($area_cta_color); ?>"><?php esc_html_e('Explore', 'resideo'); ?></div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($settings['cta_link']['url'] != '') { 
                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                    <?php } ?>
                </div>
            <?php break;
            case '2': ?>
                <div class="<?php echo esc_attr($margin_class); ?>">
                    <h2 class="pxp-section-h2 text-center"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="pxp-text-light text-center"><?php echo $settings['subtitle']; ?></p>

                    <div class="pxp-areas-carousel mt-4 mt-md-5">
                        <div class="pxp-areas-carousel-stage owl-carousel">
                            <?php foreach ($settings['areas_list'] as $area) {
                                $image_src = '';
                                $image = false;
                                if (isset($area['area_image'])) {
                                    $image = wp_get_attachment_image_src($area['area_image']['id'], 'pxp-gallery');

                                    if ($image != false) {
                                        $image_src = $image[0];
                                    }
                                }

                                $area_link = add_query_arg(
                                    array(
                                        'search_neighborhood' => $area['area_neighborhood'],
                                        'search_city' => $area['area_city'],
                                    ), $results_page
                                );

                                $properties_count = $this->resideo_count_properties($area); 

                                $area_cta_color = isset($area['cta_color']) ? $area['cta_color'] : ''; ?>

                                <a href="<?php echo esc_url($area_link); ?>" class="pxp-areas-carousel-item">
                                    <div class="pxp-areas-carousel-item-fig-container">
                                        <div class="pxp-areas-carousel-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($image_src); ?>);"></div>
                                    </div>
                                    <div class="pxp-areas-carousel-item-details mt-1">
                                        <?php if (count($neighborhoods) > 0 && isset($neighborhoods[$area['area_neighborhood']])) { ?>
                                            <div class="pxp-areas-carousel-item-details-area"><?php echo esc_html($neighborhoods[$area['area_neighborhood']]); ?></div>
                                        <?php } else { ?>
                                            <div class="pxp-areas-carousel-item-details-area"><?php echo esc_html($area['area_neighborhood']); ?></div>
                                        <?php }

                                        if (count($cities) > 0 && isset($cities[$area['area_city']])) { ?>
                                            <div class="pxp-areas-carousel-item-details-city"><?php echo esc_html($cities[$area['area_city']]); ?></div>
                                        <?php } else { ?>
                                            <div class="pxp-areas-carousel-item-details-city"><?php echo esc_html($area['area_city']); ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="pxp-areas-carousel-item-counter"><span><?php echo esc_html($properties_count) . ' ' . __('Properties', 'resideo'); ?></span></div>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if ($settings['cta_link']['url'] != '') { 
                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                        <div class="mt-4 mt-md-5 text-center">
                            <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                            <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                        </div>
                    <?php } ?>
                </div>
            <?php break;
            default: ?>
                <div class="container <?php echo esc_attr($margin_class); ?>">
                    <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="pxp-text-light"><?php echo $settings['subtitle']; ?></p>

                    <div class="row mt-4 mt-md-5">
                        <?php foreach ($settings['areas_list'] as $area) {
                            $image_src = '';
                            $image = false;
                            if (isset($area['area_image'])) {
                                $image = wp_get_attachment_image_src($area['area_image']['id'], 'pxp-gallery');

                                if ($image != false) {
                                    $image_src = $image[0];
                                }
                            }

                            $area_link = add_query_arg(
                                array(
                                    'search_neighborhood' => $area['area_neighborhood'],
                                    'search_city' => $area['area_city'],
                                ), $results_page
                            );

                            $properties_count = $this->resideo_count_properties($area); 

                            $area_cta_color = isset($area['cta_color']) ? $area['cta_color'] : ''; ?>

                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <a href="<?php echo esc_url($area_link); ?>" class="pxp-areas-1-item rounded-lg">
                                    <div class="pxp-areas-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($image_src); ?>);"></div>
                                    <div class="pxp-areas-1-item-details">
                                        <?php if (count($neighborhoods) > 0 && isset($neighborhoods[$area['area_neighborhood']])) { ?>
                                            <div class="pxp-areas-1-item-details-area"><?php echo esc_html($neighborhoods[$area['area_neighborhood']]); ?></div>
                                        <?php } else { ?>
                                            <div class="pxp-areas-1-item-details-area"><?php echo esc_html($area['area_neighborhood']); ?></div>
                                        <?php }

                                        if (count($cities) > 0 && isset($cities[$area['area_city']])) { ?>
                                            <div class="pxp-areas-1-item-details-city"><?php echo esc_html($cities[$area['area_city']]); ?></div>
                                        <?php } else { ?>
                                            <div class="pxp-areas-1-item-details-city"><?php echo esc_html($area['area_city']); ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="pxp-areas-1-item-counter"><span><?php echo esc_html($properties_count) . ' ' . __('Properties', 'resideo'); ?></span></div>
                                    <div class="pxp-areas-1-item-cta text-uppercase" style="color: <?php echo esc_attr($area_cta_color); ?>"><?php esc_html_e('Explore', 'resideo'); ?></div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($settings['cta_link']['url'] != '') { 
                        $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                        $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-5 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                        <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
                    <?php } ?>
                </div>
            <?php break;
        }
    }
}
?>