<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Featured blog posts shortcode
 */
if (!function_exists('resideo_featured_posts_shortcode')): 
    function resideo_featured_posts_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

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

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';
        $blog_url = get_permalink(get_option('page_for_posts'));

        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';
        $card_cta_color = isset($s_array['card_cta_color']) ? $s_array['card_cta_color'] : '';
        $cta_id = uniqid();

        $return_string = 
            '<div class="container ' . esc_attr($margin_class) . '">
                <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>
                <div class="row mt-4 mt-md-5">';

        foreach($posts as $post) : 
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

            $item_class = $p_photo === false ? 'pxp-no-image' : '';

            $return_string .= 
                '<div class="col-sm-12 col-md-6 col-lg-4">
                    <a href="' . esc_url($p_link) . '" class="pxp-posts-1-item ' . esc_attr($item_class) . '">
                        <div class="pxp-posts-1-item-fig-container">';
            if ($p_photo !== false) {
                $return_string .= '
                            <div class="pxp-posts-1-item-fig pxp-cover" style="background-image: url(' . esc_url($p_photo) . ');"></div>';
            }
            $return_string .= '
                        </div>
                        <div class="pxp-posts-1-item-details">
                            <div class="pxp-posts-1-item-details-category">' . esc_html($categories_str) . '</div>
                            <div class="pxp-posts-1-item-details-title">' . esc_html($p_title) . '</div>
                            <div class="pxp-posts-1-item-details-date mt-2">' . esc_html($p_date) . '</div>
                            <div class="pxp-posts-1-item-cta text-uppercase" style="color: ' . esc_attr($card_cta_color) . '">' . __('Read Article', 'resideo') . '</div>
                        </div>
                    </a>
                </div>';
        endforeach;

        $return_string .= '
                </div>
                <a href="' . esc_url($blog_url) . '" class="pxp-primary-cta text-uppercase mt-2 mt-md-4 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' . __('Read More', 'resideo') . '</a>
                <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>
            </div>';

        wp_reset_postdata();
        wp_reset_query();

        return $return_string;
    }
endif;
?>