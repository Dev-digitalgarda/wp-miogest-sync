<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Resideo_Featured_Agents_Widget extends WP_Widget {
    function __construct() {
        $widget_ops  = array('classname' => 'resideo_featured_agents_sidebar', 'description' => __('Featured agents', 'resideo'));
        $control_ops = array('id_base' => 'resideo_featured_agents_widget');

        parent::__construct('resideo_featured_agents_widget', __('Resideo Featured Agents', 'resideo'), $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array(
            'title' => '',
            'limit' => ''
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $display = '
            <p>
                <label for="' . esc_attr($this->get_field_id('title')) . '">' . __('Title', 'resideo') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($instance['title']) . '" />
            </p>
            <p>
                <label for="' . esc_attr($this->get_field_id('limit')) . '">' . __('Number of agents to show', 'resideo') . ':</label>
                <input type="text" size="3" id="' . esc_attr($this->get_field_id('limit')) . '" name="' . esc_attr($this->get_field_name('limit')) . '" value="' . esc_attr($instance['limit']) . '" />
            </p>
        ';

        print $display;
    }


    function update($new_instance, $old_instance) {
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['limit'] = sanitize_text_field($new_instance['limit']);

        if (function_exists('icl_register_string')) {
            icl_register_string('resideo_featured_agents_widget', 'resideo_featured_agents_widget_title', sanitize_text_field($new_instance['title']));
            icl_register_string('resideo_featured_agents_widget', 'resideo_featured_agents_widget_limit', sanitize_text_field($new_instance['limit']));
        }

        return $instance;
    }

    function widget($args, $instance) {
        $general_settings = get_option('resideo_general_settings');
        $show_rating = isset($general_settings['resideo_agents_rating_field']) ? $general_settings['resideo_agents_rating_field'] : '';

        extract($args);

        $display = '';
        $title = apply_filters('widget_title', $instance['title']);

        print $before_widget;

        if ($title) {
            print $before_title . esc_html($title) . $after_title;
        }

        $limit = 4;
        if ($instance['limit'] && $instance['limit'] != '') {
            $limit = $instance['limit'];
        }

        $suppress_filters = false;
        if (function_exists('dsidxpress_InitWidgets')) {
            $suppress_filters = true;
        }

        $appearance_settings = get_option('resideo_appearance_settings');
        $hide_phone = isset($appearance_settings['resideo_hide_agents_phone_field']) ? $appearance_settings['resideo_hide_agents_phone_field'] : '';

        $r = array(
            'posts_per_page'   => $limit,
            'post_type'        => 'agent',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'meta_key'         => 'agent_featured',
            'meta_value'       => '1',
            'suppress_filters' => $suppress_filters,
            'post_status'      => 'publish'
        );

        $posts = get_posts($r);

        foreach ($posts as $post) : setup_postdata($post);
            $a_name  = $post->post_title;
            $a_link  = get_permalink($post->ID);
            $a_phone = get_post_meta($post->ID, 'agent_phone', true);
            $a_email = get_post_meta($post->ID, 'agent_email', true);

            $a_avatar       = get_post_meta($post->ID, 'agent_avatar', true);
            $a_avatar_photo = wp_get_attachment_image_src($a_avatar, 'pxp-thmb');

            if ($a_avatar_photo != '') {
                $a_photo = $a_avatar_photo[0];
            } else {
                $a_photo = RESIDEO_PLUGIN_PATH . 'images/ph-thmb.jpg';
            }

            $display .=  '
                <a href="' . esc_url($a_link) . '" class="media mt-2 mt-md-3">
                    <img src="' . esc_url($a_photo) . '" class="mr-3 rounded-lg" alt="' . esc_html($a_name) . '">';
            if ($show_rating != '') {
                $display .= resideo_display_agent_rating(resideo_get_agent_ratings($post->ID), false, 'pxp-agent-side-rating');
            }
            $display .= '
                    <div class="media-body">
                        <h5>' . esc_html($a_name) . '</h5>';
            if ($hide_phone != '') {
                $display .= '
                        <div class="pxp-agent-side-phone">' . esc_html($a_email) . '</div>';
            } else {
                $display .= '
                        <div class="pxp-agent-side-phone"><span class="fa fa-phone"></span> ' . esc_html($a_phone) . '</div>';
            }
            $display .= '
                        <div class="pxp-agent-side-cta text-uppercase">' . __('More Details', 'resideo') . '</div>
                    </div>
                </a>';
        endforeach;

        wp_reset_postdata();
        wp_reset_query();

        print $display;
        print $after_widget;
    }
}
?>