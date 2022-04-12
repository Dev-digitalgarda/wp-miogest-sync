<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Resideo_Featured_Properties_Widget extends WP_Widget {
    function __construct() {
        $widget_ops  = array('classname' => 'resideo_featured_properties_sidebar', 'description' => __('Featured properties', 'resideo'));
        $control_ops = array('id_base' => 'resideo_featured_properties_widget');

        parent::__construct('resideo_featured_properties_widget', __('Resideo Featured Properties', 'resideo'), $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array(
            'title'  => '',
            'limit'  => '',
            'type'   => 0,
            'status' => 0
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $display = '
            <p>
                <label for="' . esc_attr($this->get_field_id('title')) . '">' . __('Title', 'resideo') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($instance['title']) . '" />
            </p>
            <p>
                <label for="' . esc_attr($this->get_field_id('limit')) . '">' . __('Number of properties to show', 'resideo') . ':</label>
                <input type="text" size="3" id="' . esc_attr($this->get_field_id('limit')) . '" name="' . esc_attr($this->get_field_name('limit')) . '" value="' . esc_attr($instance['limit']) . '" />
            </p>
        ';

        $type_taxonomies = array(
            'property_type'
        );
        $type_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $type_terms = get_terms($type_taxonomies, $type_args);

        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('type')) . '">' . __('Type', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('type')) . '" name="' . esc_attr($this->get_field_name('type')) . '">
                    <option ' . ($instance['type'] == 0 ? 'selected="selected"' : '') . 'value="0">' . esc_html('All', 'resideo') . '</option>';
        foreach ($type_terms as $type_term) {
            $display .= '
                    <option ' . ($instance['type'] == $type_term->term_id ? 'selected="selected"' : '') . 'value="' . esc_attr($type_term->term_id) . '">' . esc_html($type_term->name) . '</option>';
        }
        $display .= '
                </select>
            </p>
        ';

        $status_taxonomies = array( 
            'property_status'
        );
        $status_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $status_terms = get_terms($status_taxonomies, $status_args);
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('status')) . '">' . __('Status', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('status')) . '" name="' . esc_attr($this->get_field_name('status')) . '">
                    <option ' . ($instance['status'] == 0 ? 'selected="selected"' : '') . 'value="0">' . esc_html('All', 'resideo') . '</option>';
        foreach ($status_terms as $status_term) {
            $display .= '
                    <option ' . ($instance['status'] == $status_term->term_id ? 'selected="selected"' : '') . 'value="' . esc_attr($status_term->term_id) . '">' . esc_html($status_term->name) . '</option>';
        }
        $display .= '
                </select>
            </p>
        ';

        print $display;
    }


    function update($new_instance, $old_instance) {
        $instance           = $old_instance;
        $instance['title']  = sanitize_text_field($new_instance['title']);
        $instance['limit']  = sanitize_text_field($new_instance['limit']);
        $instance['type']   = sanitize_text_field($new_instance['type']);
        $instance['status'] = sanitize_text_field($new_instance['status']);

        if (function_exists('icl_register_string')) {
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_title', sanitize_text_field($new_instance['title']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_limit', sanitize_text_field($new_instance['limit']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_type', sanitize_text_field($new_instance['type']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_status', sanitize_text_field($new_instance['status']));
        }

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $display = '';
        $title = apply_filters('widget_title', $instance['title']);

        print $before_widget;

        if ($title) {
            print $before_title . esc_html($title) . $after_title;
        }

        if (isset($instance['limit']) && $instance['limit'] != '') {
            $limit = $instance['limit'];
        } else {
            $limit = 4;
        }

        if (isset($instance['type']) && is_numeric($instance['type'])) {
            $type = $instance['type'];
        } else {
            $type = 0;
        }

        if (isset($instance['status']) && is_numeric($instance['status'])) {
            $status = $instance['status'];
        } else {
            $status = 0;
        }

        $suppress_filters = false;
        if (function_exists('dsidxpress_InitWidgets')) {
            $suppress_filters = true;
        }

        $r = array(
            'posts_per_page'   => $instance['limit'],
            'post_type'        => 'property',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'meta_key'         => 'property_featured',
            'meta_value'       => '1',
            'suppress_filters' => $suppress_filters,
            'post_status'      => 'publish'
        );

        if ($type != 0 && $status != 0) {
            $r['tax_query'] = array(
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
        } else if ($type != 0) {
            $r['tax_query'] = array(
                array(
                    'taxonomy' => 'property_type',
                    'field'    => 'term_id',
                    'terms'    => $type,
                ),
            );
        } else if ($status != 0) {
            $r['tax_query'] = array(
                array(
                    'taxonomy' => 'property_status',
                    'field'    => 'term_id',
                    'terms'    => $status,
                ),
            );
        }

        $resideo_general_settings = get_option('resideo_general_settings');
        $beds_label               = isset($resideo_general_settings['resideo_beds_label_field']) ? $resideo_general_settings['resideo_beds_label_field'] : 'BD';
        $baths_label              = isset($resideo_general_settings['resideo_baths_label_field']) ? $resideo_general_settings['resideo_baths_label_field'] : 'BA';
        $unit                     = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : 'SF';
        $currency                 = isset($resideo_general_settings['resideo_currency_symbol_field']) ? $resideo_general_settings['resideo_currency_symbol_field'] : '';
        $currency_pos             = isset($resideo_general_settings['resideo_currency_symbol_pos_field']) ? $resideo_general_settings['resideo_currency_symbol_pos_field'] : '';
        $locale                   = isset($resideo_general_settings['resideo_locale_field']) ? $resideo_general_settings['resideo_locale_field'] : '';
        $decimals                 = isset($resideo_general_settings['resideo_decimals_field']) ? $resideo_general_settings['resideo_decimals_field'] : '';

        setlocale(LC_MONETARY, $locale);

        $posts = get_posts($r);

        $display = '';

        foreach ($posts as $post) : setup_postdata($post);
            $p_title = $post->post_title;
            $p_link  = get_permalink($post->ID);

            $gallery     = get_post_meta($post->ID, 'property_gallery', true);
            $photos      = explode(',', $gallery);
            $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-thmb');

            if ($first_photo !== false) {
                $p_photo = $first_photo[0];
            } else {
                $p_photo = RESIDEO_PLUGIN_PATH . 'images/ph-thmb.jpg';
            }

            $p_price       = get_post_meta($post->ID, 'property_price', true);
            $p_price_label = get_post_meta($post->ID, 'property_price_label', true);

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

            $p_beds  = get_post_meta($post->ID, 'property_beds', true);
            $p_baths = get_post_meta($post->ID, 'property_baths', true);
            $p_size  = get_post_meta($post->ID, 'property_size', true);

            $display .= '
                <a href="' . esc_url($p_link) . '" class="media mt-2 mt-md-3">
                    <img src="' . esc_url($p_photo) . '" class="mr-3 rounded-lg" alt="' . esc_html($p_title) . '">
                    <div class="media-body">
                        <h5>' . esc_html($p_title) . '</h5>';
            if ($currency_pos == 'before') {
                $display .= '
                        <div class="pxp-property-side-price">' . esc_html($currency_str) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span></div>';
            } else {
                $display .= '
                        <div class="pxp-property-side-price">' . esc_html($p_price) . esc_html($currency_str) . ' <span>' . esc_html($p_price_label) . '</span></div>';
            }
            $display .= '
                        <div class="pxp-property-side-features">
                            <span>';
            if ($p_beds != '') {
                $display .= esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
            }
            if ($p_baths != '') {
                $display .= esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
            }
            if ($p_size != '') {
                $display .= esc_html($p_size) . ' ' . esc_html($unit);
            }
            $display .= '
                            </span>
                        </div>
                        <div class="pxp-property-side-cta text-uppercase">' . __('More Details', 'resideo') . '</div>
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