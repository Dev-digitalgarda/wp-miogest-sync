<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Featured agents shortcode
 */
if (!function_exists('resideo_featured_agents_shortcode')): 
    function resideo_featured_agents_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        if (isset($s_array['number']) && is_numeric($s_array['number'])) {
            $number = $s_array['number'];
        } else {
            $number = '4';
        }

        $cta_id = uniqid();
        $cta_color = isset($s_array['cta_color']) ? $s_array['cta_color'] : '';

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

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $return_string = 
            '<div class="container ' . esc_attr($margin_class) . '">
                <h2 class="pxp-section-h2">' . esc_html($s_array['title']) . '</h2>
                <p class="pxp-text-light">' . esc_html($s_array['subtitle']) . '</p>

                <div class="row mt-4 mt-md-5">';
        foreach ($posts as $post) : 
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
            }

            $card_cta_color = isset($s_array['card_cta_color']) ? $s_array['card_cta_color'] : '';

            $return_string .= 
                    '<div class="col-sm-12 col-md-6 col-lg-3">
                        <a href="' . esc_url($a_link) . '" class="pxp-agents-1-item">
                            <div class="pxp-agents-1-item-fig-container rounded-lg">
                                <div class="pxp-agents-1-item-fig pxp-cover" style="background-image: url(' . esc_url($a_photo) . '); background-position: top center"></div>
                            </div>
                            <div class="pxp-agents-1-item-details rounded-lg">
                                <div class="pxp-agents-1-item-details-name">' . esc_html($a_title) . '</div>';
            if ($hide_phone != '') {
                $return_string .= '
                                    <div class="pxp-agents-1-item-details-email">' . esc_html($a_email) . '</div>';
            } else {
                $return_string .= '
                                    <div class="pxp-agents-1-item-details-phone"><span class="fa fa-phone"></span> ' . esc_html($a_phone) . '</div>';
            }
            $return_string .= '
                                <div class="pxp-agents-1-item-details-spacer"></div>
                                <div class="pxp-agents-1-item-cta text-uppercase" style="color: ' . esc_attr($card_cta_color) . '">' . esc_html($s_array['card_cta']) . '</div>
                            </div>';
            if ($show_rating != '') {
                $return_string .= resideo_display_agent_rating(resideo_get_agent_ratings($post['ID']), false, 'pxp-agents-1-item-rating');
            }
            $return_string .= 
                        '</a>
                    </div>';
        endforeach;
        $return_string .= 
                '</div>

                <a href="' .  esc_url($s_array['cta_link']) . '" class="pxp-primary-cta text-uppercase mt-1 mt-md-4 pxp-animate" id="cta-' . esc_attr($cta_id) . '" style="color: ' . esc_attr($cta_color) . '">' .  esc_html($s_array['cta_label']) . '</a>
                <style>.pxp-primary-cta#cta-' . esc_attr($cta_id) . ':after { border-top: 2px solid ' . esc_html($cta_color) . '; }</style>
            </div>';

        wp_reset_postdata();
        wp_reset_query();

        return $return_string;
    }
endif;
?>