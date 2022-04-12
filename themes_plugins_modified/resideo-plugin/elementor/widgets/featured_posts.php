<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Featured_Posts_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'featured_posts';
    }

    public function get_title() {
        return __('Featured Blog Posts', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-list-alt';
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
                'label' => __('CTAs', 'resideo'),
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

        $this->add_control(
            'post_cta_color',
            [
                'label' => __('Post Card CTA Color', 'resideo'),
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

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';

        $args = array(
            'numberposts'      => '3',
            'post_type'        => 'post',
            'order'            => 'DESC',
            'meta_key'         => 'post_featured',
            'meta_value'       => '1',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args);
        $blog_url = get_permalink(get_option('page_for_posts'));

        $cta_id = uniqid();
        $cta_color = isset($settings['cta_color']) ? $settings['cta_color'] : '';
        $post_cta_color = isset($settings['post_cta_color']) ? $settings['post_cta_color'] : ''; ?>

        <div class="container <?php echo esc_attr($margin_class); ?>">
            <h2 class="pxp-section-h2"><?php echo esc_html($settings['title']); ?></h2>
            <div class="pxp-text-light"><?php echo $settings['subtitle']; ?></div>
            <div class="row mt-4 mt-md-5">
                <?php foreach($posts as $post) : 
                    $p_title = $post['post_title'];
                    $p_link = get_permalink($post['ID']);
                    $p_date = get_the_date('F j, Y', $post['ID']);

                    $post_image = wp_get_attachment_image_src(get_post_thumbnail_id($post['ID']), 'pxp-gallery');

                    if ($post_image != '') {
                        $p_photo = $post_image[0];
                    } else {
                        $p_photo = false;
                    }

                    $categories = get_the_category($post['ID']);
                    $separator = ', ';
                    $output = '';
                    $categories_str = '';

                    if ($categories) {
                        foreach ($categories as $category) {
                            $output .=  $category->cat_name . $separator;
                        }
                        $categories_str = trim($output, $separator);
                    }

                    $item_class = $p_photo === false ? 'pxp-no-image' : ''; ?>

                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <a href="<?php echo esc_url($p_link); ?>" class="pxp-posts-1-item <?php echo esc_attr($item_class); ?>">
                            <div class="pxp-posts-1-item-fig-container">
                                <?php if ($p_photo !== false) { ?>
                                    <div class="pxp-posts-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($p_photo); ?>);"></div>
                                <?php } ?>
                            </div>
                            <div class="pxp-posts-1-item-details">
                                <div class="pxp-posts-1-item-details-category"><?php echo esc_html($categories_str); ?></div>
                                <div class="pxp-posts-1-item-details-title"><?php echo esc_html($p_title); ?></div>
                                <div class="pxp-posts-1-item-details-date mt-2"><?php echo esc_html($p_date); ?></div>
                                <div class="pxp-posts-1-item-cta text-uppercase" style="color: <?php echo esc_attr($post_cta_color); ?>;"><?php esc_html_e('Read Article', 'resideo'); ?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo esc_url($blog_url); ?>" class="pxp-primary-cta text-uppercase mt-2 mt-md-4 pxp-animate" id="cta-<?php echo esc_attr($cta_id); ?>" style="color: <?php echo esc_attr($cta_color); ?>"><?php esc_html_e('Read More', 'resideo'); ?></a>
            <style>.pxp-primary-cta#cta-<?php echo esc_attr($cta_id); ?>:after { border-top: 2px solid <?php echo esc_html($cta_color); ?>; }</style>
        </div>

        <?php wp_reset_postdata();
        wp_reset_query();
    }
}
?>