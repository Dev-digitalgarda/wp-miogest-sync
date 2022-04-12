<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Featured_Agents_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'featured_agents';
    }

    public function get_title() {
        return __('Featured Agents', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-user';
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

        $this->add_control(
            'number',
            [
                'label' => __('Number of Agents', 'resideo'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 30,
                'step' => 1,
                'default' => 4,
                'placeholder' => __('Enter number of agents', 'resideo'),
            ]
        );

        $this->add_control(
            'agent_cta_text',
            [
                'label' => __('Agent Cards CTA Text', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter the CTA text', 'resideo'),
            ]
        );

        $this->add_control(
            'agent_cta_color',
            [
                'label' => __('Agent Cards CTA Color', 'resideo'),
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

        $number = (isset($settings['number']) && is_numeric($settings['number'])) ? $settings['number'] : '4';

        $args = array(
            'numberposts' => $number,
            'post_type' => 'agent',
            'meta_query' => array(
                array(
                    'relation' => 'AND'
                ),
                array(
                    'key' => 'agent_featured',
                    'value' => '1',
                ),
                array(
                    'key' => 'agent_type',
                    'value' => 'agent',
                ),
            ),
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args);

        $resideo_general_settings = get_option('resideo_general_settings','');
        $show_rating = isset($resideo_general_settings['resideo_agents_rating_field']) ? $resideo_general_settings['resideo_agents_rating_field'] : '';

        $appearance_settings = get_option('resideo_appearance_settings');
        $hide_phone = isset($appearance_settings['resideo_hide_agents_phone_field']) ? $appearance_settings['resideo_hide_agents_phone_field'] : '';

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : ''; 

        $cta_id = uniqid();
        $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : '';
        $agent_cta_color = isset($settings['agent_cta_color']) ? $settings['agent_cta_color'] : ''; ?>

        <div class="container <?php echo esc_attr($margin_class); ?>">
            <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
            <div class="pxp-text-light"><?php echo $settings['subtitle']; ?></div>

            <div class="row mt-4 mt-md-5">
                <?php foreach ($posts as $post) : 
                    $a_title = $post['post_title'];
                    $a_link = get_permalink($post['ID']);
                    $a_phone = get_post_meta($post['ID'], 'agent_phone', true);
                    $a_email = get_post_meta($post['ID'], 'agent_email', true);

                    $avatar = get_post_meta($post['ID'], 'agent_avatar', true);
                    $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');

                    if ($avatar_photo != '') {
                        $a_photo = $avatar_photo[0];
                    } else {
                        $a_photo = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                    } ?>

                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <a href="<?php echo esc_url($a_link); ?>" class="pxp-agents-1-item">
                            <div class="pxp-agents-1-item-fig-container rounded-lg">
                                <div class="pxp-agents-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($a_photo); ?>); background-position: top center"></div>
                            </div>
                            <div class="pxp-agents-1-item-details rounded-lg">
                                <div class="pxp-agents-1-item-details-name"><?php echo esc_html($a_title); ?></div>
                                <?php if ($hide_phone != '') { ?>
                                    <div class="pxp-agents-1-item-details-email"><?php echo esc_html($a_email); ?></div>
                                <?php } else { ?>
                                    <div class="pxp-agents-1-item-details-email"><span class="fa fa-phone"></span> <?php echo esc_html($a_phone); ?></div>
                                <?php } ?>
                                <div class="pxp-agents-1-item-details-spacer"></div>
                                <div class="pxp-agents-1-item-cta text-uppercase" style="color: <?php echo esc_attr($agent_cta_color); ?>"><?php  echo esc_html($settings['agent_cta_text']); ?></div>
                            </div>
                            <?php if ($show_rating != '') {
                                echo resideo_display_agent_rating(resideo_get_agent_ratings($post['ID']), false, 'pxp-agents-1-item-rating');
                            } ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($settings['cta_link']['url'] != '') { 
                $target = $settings['cta_link']['is_external'] ? ' target="_blank"' : '';
                $nofollow = $settings['cta_link']['nofollow'] ? ' rel="nofollow"' : ''; ?>
                <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="pxp-primary-cta text-uppercase mt-1 mt-md-4 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html($settings['cta_label']); ?></a>
                <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
            <?php } ?>
        </div>

        <?php wp_reset_postdata();
        wp_reset_query();
    }
}
?>