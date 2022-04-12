<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Recent_Properties_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'recent_properties';
    }

    public function get_title() {
        return __('Recent Properties', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-home';
    }

    public function get_categories() {
        return ['resideo'];
    }

    private function resideo_get_types_statuses() {
        $type_taxonomies = array( 
            'property_type'
        );
        $type_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $type_terms = get_terms($type_taxonomies, $type_args);

        $status_taxonomies = array( 
            'property_status'
        );
        $status_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $status_terms = get_terms($status_taxonomies, $status_args);

        $types = array('0' => __('All', 'resideo'));
        for ($ti = 0; $ti < count($type_terms); $ti++) {
            $types[$type_terms[$ti]->term_id] = $type_terms[$ti]->name;
        }

        $statuses = array('0' => __('All', 'resideo'));
        for ($si = 0; $si < count($status_terms); $si++) {
            $statuses[$status_terms[$si]->term_id] = $status_terms[$si]->name;
        }

        return array(
            'types' => $types,
            'statuses' => $statuses
        );
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
            'content_section',
            [
                'label' => __('Content', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $property_taxo = $this->resideo_get_types_statuses();

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => $property_taxo['types'],
            ]
        );

        $this->add_control(
            'status',
            [
                'label' => __('Status', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => $property_taxo['statuses'],
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => __('Number of Properties', 'resideo'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 30,
                'step' => 1,
                'default' => 3,
                'placeholder' => __('Enter number of properties', 'resideo'),
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
                        'icon' => 'fa fa-indent',
                    ],
                    '3' => [
                        'title' => __('Layout 3', 'resideo'),
                        'icon' => 'fa fa-th',
                    ],
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

        $type = (isset($settings['type']) && is_numeric($settings['type'])) ? $settings['type'] : '0';
        $status = (isset($settings['status']) && is_numeric($settings['status'])) ? $settings['status'] : '0';
        $number = (isset($settings['number']) && is_numeric($settings['number'])) ? $settings['number'] : '3';

        $args = array(
            'numberposts'      => $number,
            'post_type'        => 'property',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        if ($type != '0' && $status != '0') {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'property_type',
                    'field'    => 'term_id',
                    'terms'    => $type,
                ),
                array(
                    'taxonomy' => 'property_status',
                    'field'    => 'term_id',
                    'terms'    => $status,
                ),
            );
        } else if ($type != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'property_type',
                    'field'    => 'term_id',
                    'terms'    => $type,
                ),
            );
        } else if ($status != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'property_status',
                    'field'    => 'term_id',
                    'terms'    => $status,
                ),
            );
        }

        $posts = wp_get_recent_posts($args);

        $resideo_general_settings = get_option('resideo_general_settings');
        $beds_label               = isset($resideo_general_settings['resideo_beds_label_field']) ? $resideo_general_settings['resideo_beds_label_field'] : 'BD';
        $baths_label              = isset($resideo_general_settings['resideo_baths_label_field']) ? $resideo_general_settings['resideo_baths_label_field'] : 'BA';
        $unit                     = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : '';
        $currency                 = isset($resideo_general_settings['resideo_currency_symbol_field']) ? $resideo_general_settings['resideo_currency_symbol_field'] : '';
        $currency_pos             = isset($resideo_general_settings['resideo_currency_symbol_pos_field']) ? $resideo_general_settings['resideo_currency_symbol_pos_field'] : '';
        $locale                   = isset($resideo_general_settings['resideo_locale_field']) ? $resideo_general_settings['resideo_locale_field'] : '';
        $decimals                 = isset($resideo_general_settings['resideo_decimals_field']) ? $resideo_general_settings['resideo_decimals_field'] : '';
        setlocale(LC_MONETARY, $locale);

        $widget_html = '';
        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';
        $widget_after = '';
        $column_class = '';
        $card_margin_class = '';

        $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : '';
        $cta_id = uniqid();

        switch($settings['layout']) {
            case '1':
                $widget_html .= '
                    <div class="container-fluid pxp-props-carousel-right ' . esc_attr($margin_class) . '">';
                if ($settings['title'] != '') {
                    $widget_html .= '
                        <h2 class="pxp-section-h2">' . esc_html($settings['title']) . '</h2>';
                }
                if ($settings['subtitle'] != '') {
                    $widget_html .= '
                        <div class="pxp-text-light">' . $settings['subtitle'] . '</div>';
                }

                $widget_html .= '
                        <div class="pxp-props-carousel-right-container mt-4 mt-md-5">
                            <div class="owl-carousel pxp-props-carousel-right-stage">';

                if ($settings['cta_link']['url'] != '') {
                    $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : '';
                    $widget_after = '</div></div><a href="' . esc_url($settings['cta_link']['url']) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '"' . $target . $nofollow . '>' . esc_html($settings['cta_label']) . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style></div>';
                } else {
                    $widget_after = '</div></div></div>';
                }
            break;
            case '2':
                $widget_html .= '
                    <div class="container-fluid pxp-props-carousel-right pxp-has-intro ' . esc_attr($margin_class) . '">
                        <div class="pxp-props-carousel-right-intro">';
                if ($settings['title'] != '') {
                    $widget_html .= '
                            <h2 class="pxp-section-h2">' . esc_html($settings['title']) . '</h2>';
                }
                if ($settings['subtitle'] != '') {
                    $widget_html .= '
                            <div class="pxp-text-light">' . $settings['subtitle'] . '</div>';
                }
                if ($settings['cta_link']['url'] != '') {
                    $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : '';
                    $widget_html .= '
                            <a href="' . esc_url($settings['cta_link']['url']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '"' . $target . $nofollow . '>' . esc_html($settings['cta_label']) . '</a>
                            <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $widget_html .= '
                        </div>
                        <div class="pxp-props-carousel-right-container mt-4 mt-md-5 mt-lg-0">
                            <div class="owl-carousel pxp-props-carousel-right-stage-1">';
                $widget_after = '</div></div></div>';
            break;
            case '3':
                $widget_html .= '
                    <div class="container ' . esc_attr($margin_class) . '">';
                if ($settings['title'] != '') {
                    $widget_html .= '
                        <h2 class="pxp-section-h2">' . esc_html($settings['title']) . '</h2>';
                }
                if ($settings['subtitle'] != '') {
                    $widget_html .= '
                        <div class="pxp-text-light">' . $settings['subtitle'] . '</div>';
                }
                $widget_html .= '
                        <div class="row mt-4 mt-md-5">';
                $widget_after = '</div>';
                if ($settings['cta_link']['url'] != '') {
                    $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : '';
                    $widget_after .= '
                            <a href="' . esc_url($settings['cta_link']['url']) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-3 mt-lg-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '"' . $target . $nofollow . '>' . esc_html($settings['cta_label']) . '</a>
                            <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>';
                }
                $widget_after .= '</div>';

                $column_class = 'col-sm-12 col-md-6 col-lg-4';
                $card_margin_class = 'mb-4';
            break;
            default:
                $widget_html .= '
                    <div class="container-fluid pxp-props-carousel-right ' . esc_attr($margin_class) . '">';
                if ($settings['title'] != '') {
                    $widget_html .= '
                        <h2 class="pxp-section-h2">' . esc_html($settings['title']) . '</h2>';
                }
                if ($settings['subtitle'] != '') {
                    $widget_html .= '
                        <div class="pxp-text-light">' . settings['subtitle'] . '</div>';
                }

                $widget_html .= '
                        <div class="pxp-props-carousel-right-container mt-4 mt-md-5">
                            <div class="owl-carousel pxp-props-carousel-right-stage">';

                if ($settings['cta_link']['url'] != '') {
                    $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : '';
                    $widget_after = '</div></div><a href="' . esc_url($settings['cta_link']['url']) . '" class="pxp-primary-cta text-uppercase mt-4 mt-md-5 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '"' . $target . $nofollow . '>' . esc_html($settings['cta_label']) . '</a>
                                    <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style></div>';
                } else {
                    $widget_after = '</div></div></div>';
                }
            break;
        }

        foreach($posts as $post) : 
            $p_title = $post['post_title'];
            $p_link  = get_permalink($post['ID']);

            $gallery     = get_post_meta($post['ID'], 'property_gallery', true);
            $photos      = explode(',', $gallery);
            $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-gallery');

            if ($first_photo != '') {
                $p_photo = $first_photo[0];
            } else {
                $p_photo = RESIDEO_PLUGIN_PATH . 'images/property-tile.png';
            }

            $p_price       = get_post_meta($post['ID'], 'property_price', true);
            $p_price_label = get_post_meta($post['ID'], 'property_price_label', true);

            $currency_str = $currency;

            if (is_numeric($p_price)) {
                if ($decimals == '1') {
                    $p_price = money_format('%!i', $p_price);
                } else {
                    $p_price = money_format('%!.0i', $p_price);
                }
            } else {
                $p_price_label = '';
                $currency_str = '';
            }

            $p_beds  = get_post_meta($post['ID'], 'property_beds', true);
            $p_baths = get_post_meta($post['ID'], 'property_baths', true);
            $p_size  = get_post_meta($post['ID'], 'property_size', true);

            $widget_html .= '
                <div class="' . esc_attr($column_class) . '">
                    <a href="' . esc_url($p_link) . '" class="pxp-prop-card-1 rounded-lg ' . esc_attr($card_margin_class) . '">
                        <div class="pxp-prop-card-1-fig pxp-cover" style="background-image: url(' . esc_url($p_photo) . ');"></div>
                        <div class="pxp-prop-card-1-gradient pxp-animate"></div>
                        <div class="pxp-prop-card-1-details">
                            <div class="pxp-prop-card-1-details-title">' . esc_html($p_title) . '</div>
                            <div class="pxp-prop-card-1-details-price">';
            if ($currency_pos == 'before') {
                $widget_html .= esc_html($currency_str) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
            } else {
                $widget_html .= esc_html($p_price) . esc_html($currency_str) . ' <span>' . esc_html($p_price_label) . '</span>';
            }
            $widget_html .= '
                            </div>
                            <div class="pxp-prop-card-1-details-features text-uppercase">';
            if ($p_beds != '') {
                $widget_html .= esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
            }
            if ($p_baths != '') {
                $widget_html .= esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
            }
            if ($p_size != '') {
                $widget_html .= esc_html($p_size) . ' ' . esc_html($unit);
            }
            $widget_html .= '
                            </div>
                        </div>
                        <div class="pxp-prop-card-1-details-cta text-uppercase">' . __('View Details', 'resideo') . '</div>
                    </a>
                </div>';
        endforeach;

        $widget_html .= $widget_after;

        wp_reset_postdata();
        wp_reset_query();

        echo $widget_html;
    }
}
?>