<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_property_layout')): 
    function resideo_admin_property_layout() {
        add_settings_section('resideo_property_layout_section', __('Property Page Layout', 'resideo'), 'resideo_property_layout_section_callback', 'resideo_property_layout_settings');
        add_settings_field('resideo_property_layout_field', __('Layout Design', 'resideo'), 'resideo_property_layout_field_render', 'resideo_property_layout_settings', 'resideo_property_layout_section', array('class' => 'pxp-property-page-layout'));
        add_settings_field('resideo_property_layout_top_field', __('Top Element', 'resideo'), 'resideo_property_layout_top_field_render', 'resideo_property_layout_settings', 'resideo_property_layout_section', array('class' => 'pxp-top-element-settings'));
        add_settings_field('resideo_property_layout_map_position_field', __('Map Position', 'resideo'), 'resideo_property_layout_map_position_field_render', 'resideo_property_layout_settings', 'resideo_property_layout_section', array('class' => 'pxp-map-position-settings'));
        add_settings_field('resideo_property_layout_preview_field', '', 'resideo_property_layout_preview_field_render', 'resideo_property_layout_settings', 'resideo_property_layout_section');
        add_settings_field('resideo_property_layout_order_field', __('Sections Order', 'resideo'), 'resideo_property_layout_order_field_render', 'resideo_property_layout_settings', 'resideo_property_layout_section');
    }
endif;

if (!function_exists('resideo_property_layout_section_callback')): 
    function resideo_property_layout_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('resideo_property_layout_field_render')): 
    function resideo_property_layout_field_render() { 
        $options = get_option('resideo_property_layout_settings');
        $modes = array(
            'd1' => __('1 - Grid Gallery', 'resideo'),
            'd2' => __('2 - Side Gallery Thumbnails', 'resideo'),
            'd3' => __('3 - Full Width Carousel', 'resideo'),
            'd4' => __('4 - Half Map', 'resideo'),
            'd5' => __('5 - Contact Agent Hero', 'resideo'),
            'd6' => __('6 - Boxed Gallery Thumbnails', 'resideo'),
            'd7' => __('7 - Full Width Slider', 'resideo')
        );

        $modes_select = '<select id="resideo_property_layout_settings[resideo_property_layout_field]" name="resideo_property_layout_settings[resideo_property_layout_field]">';

        foreach ($modes as $key => $value) {
            $modes_select .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_property_layout_field']) && $options['resideo_property_layout_field'] == $key) {
                $modes_select .= 'selected="selected"';
            }

            $modes_select .= '>' . esc_html($value) . '</option>';
        }

        $modes_select .= '</select>';

        print $modes_select;
    }
endif;

if (!function_exists('resideo_property_layout_top_field_render')): 
    function resideo_property_layout_top_field_render() { 
        $options = get_option('resideo_property_layout_settings');
        $elements = array(
            'title'  => __('Title', 'resideo'),
            'gallery' => __('Photo gallery', 'resideo'),
        );

        $element_select = '<select id="resideo_property_layout_settings[resideo_property_layout_top_field]" name="resideo_property_layout_settings[resideo_property_layout_top_field]">';

        foreach ($elements as $key => $value) {
            $element_select .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_property_layout_top_field']) && $options['resideo_property_layout_top_field'] == $key) {
                $element_select .= 'selected="selected"';
            }

            $element_select .= '>' . esc_html($value) . '</option>';
        }

        $element_select .= '</select>';

        print $element_select;
    }
endif;

if (!function_exists('resideo_property_layout_map_position_field_render')): 
    function resideo_property_layout_map_position_field_render() { 
        $options = get_option('resideo_property_layout_settings');
        $elements = array(
            'left'  => __('Left', 'resideo'),
            'right' => __('Right', 'resideo'),
        );

        $element_select = '<select id="resideo_property_layout_settings[resideo_property_layout_map_position_field]" name="resideo_property_layout_settings[resideo_property_layout_map_position_field]">';

        foreach ($elements as $key => $value) {
            $element_select .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_property_layout_map_position_field']) && $options['resideo_property_layout_map_position_field'] == $key) {
                $element_select .= 'selected="selected"';
            }

            $element_select .= '>' . esc_html($value) . '</option>';
        }

        $element_select .= '</select>';

        print $element_select;
    }
endif;

if (!function_exists('resideo_property_layout_preview_field_render')): 
    function resideo_property_layout_preview_field_render() { 
        $options = get_option('resideo_property_layout_settings');

        $preview_d1_title   = '';
        $preview_d1_gallery = 'display: none;';
        $preview_d2         = 'display: none;';
        $preview_d3_title   = 'display: none;';
        $preview_d3_gallery = 'display: none;';
        $preview_d4_left    = 'display: none;';
        $preview_d4_right   = 'display: none;';
        $preview_d5         = 'display: none;';
        $preview_d6_title   = 'display: none;';
        $preview_d6_gallery = 'display: none;';
        $preview_d7_title   = 'display: none;';
        $preview_d7_gallery = 'display: none;';

        $layout_design = isset($options['resideo_property_layout_field']) ? $options['resideo_property_layout_field'] : 'd1';
        $top_element = isset($options['resideo_property_layout_top_field']) ? $options['resideo_property_layout_top_field'] : 'title';
        $map_position = isset($options['resideo_property_layout_map_position_field']) ? $options['resideo_property_layout_map_position_field'] : 'left';

        switch ($layout_design) {
            case 'd1':
                if ($top_element == 'title') {
                    $preview_d1_title   = '';
                    $preview_d1_gallery = 'display: none;';
                } else {
                    $preview_d1_title   = 'display: none;';
                    $preview_d1_gallery = '';
                }
                $preview_d2         = 'display: none;';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
            case 'd2':
                $preview_d1_title   = 'display: none;';
                $preview_d1_gallery = 'display: none;';
                $preview_d2         = '';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
            case 'd3':
                if ($top_element == 'title') {
                    $preview_d3_title   = '';
                    $preview_d3_gallery = 'display: none;';
                } else {
                    $preview_d3_title   = 'display: none;';
                    $preview_d3_gallery = '';
                }
                $preview_d1_title   = 'display: none;';
                $preview_d1_gallery = 'display: none;';
                $preview_d2         = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
            case 'd4':
                if ($map_position == 'left') {
                    $preview_d4_left  = '';
                    $preview_d4_right = 'display: none;';
                } else {
                    $preview_d4_left  = 'display: none;';
                    $preview_d4_right = '';
                }
                $preview_d1_title   = 'display: none;';
                $preview_d1_gallery = 'display: none;';
                $preview_d2         = 'display: none;';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
            case 'd5':
                $preview_d1_title   = 'display: none;';
                $preview_d1_gallery = 'display: none;';
                $preview_d2         = 'display: none;';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = '';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
            case 'd6':
                if ($top_element == 'title') {
                    $preview_d6_title   = '';
                    $preview_d6_gallery = 'display: none;';
                } else {
                    $preview_d6_title   = 'display: none;';
                    $preview_d6_gallery = '';
                }
                $preview_d1_title   = 'display: none;';
                $preview_d1_gallery = 'display: none;';
                $preview_d2         = 'display: none;';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
            case 'd7':
                if ($top_element == 'title') {
                    $preview_d7_title   = '';
                    $preview_d7_gallery = 'display: none;';
                } else {
                    $preview_d7_title   = 'display: none;';
                    $preview_d7_gallery = '';
                }
                $preview_d1_title   = 'display: none;';
                $preview_d1_gallery = 'display: none;';
                $preview_d2         = 'display: none;';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
            break;
            default:
                if ($top_element == 'title') {
                    $preview_d1_title   = '';
                    $preview_d1_gallery = 'display: none;';
                } else {
                    $preview_d1_title   = 'display: none;';
                    $preview_d1_gallery = '';
                }
                $preview_d2         = 'display: none;';
                $preview_d3_title   = 'display: none;';
                $preview_d3_gallery = 'display: none;';
                $preview_d4_left    = 'display: none;';
                $preview_d4_right   = 'display: none;';
                $preview_d5         = 'display: none;';
                $preview_d6_title   = 'display: none;';
                $preview_d6_gallery = 'display: none;';
                $preview_d7_title   = 'display: none;';
                $preview_d7_gallery = 'display: none;';
            break;
        }

        $preview = '<img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d1-title-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d1-title" style="'. esc_attr($preview_d1_title) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d1-gallery-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d1-gallery" style="'. esc_attr($preview_d1_gallery) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d2.png') . '" class="pxp-property-page-layout-preview pxp-is-d2" style="'. esc_attr($preview_d2) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d3-title-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d3-title" style="'. esc_attr($preview_d3_title) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d3-gallery-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d3-gallery" style="'. esc_attr($preview_d3_gallery) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d4-map-left.png') . '" class="pxp-property-page-layout-preview pxp-is-d4-left" style="'. esc_attr($preview_d4_left) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d4-map-right.png') . '" class="pxp-property-page-layout-preview pxp-is-d4-right" style="'. esc_attr($preview_d4_right) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d5.png') . '" class="pxp-property-page-layout-preview pxp-is-d5" style="'. esc_attr($preview_d5) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d6-title-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d6-title" style="'. esc_attr($preview_d6_title) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d6-gallery-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d6-gallery" style="'. esc_attr($preview_d6_gallery) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d7-title-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d7-title" style="'. esc_attr($preview_d7_title) . '">
                    <img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/single-property-layout-d7-gallery-top.png') . '" class="pxp-property-page-layout-preview pxp-is-d7-gallery" style="'. esc_attr($preview_d7_gallery) . '">';

        print $preview;
    }
endif;

if (!function_exists('resideo_property_layout_order_field_render')): 
    function resideo_property_layout_order_field_render() { 
        $options = get_option('resideo_property_layout_settings');

        $default_options = array(
            'key_details' => array(
                'name' => __('Key Details', 'resideo'),
                'position' => 0
            ),
            'overview' => array(
                'name' => __('Overview', 'resideo'),
                'position' => 1
            ),
            'amenities' => array(
                'name' => __('Amenities', 'resideo'),
                'position' => 2
            ),
            'video' => array(
                'name' => __('Video', 'resideo'),
                'position' => 3
            ),
            'virtual_tour' => array(
                'name' => __('Virtual Tour', 'resideo'),
                'position' => 4
            ),
            'floor_plans' => array(
                'name' => __('Floor Plans', 'resideo'),
                'position' => 5
            ),
            'explore_area' => array(
                'name' => __('Explore the Area', 'resideo'),
                'position' => 6
            ),
            'payment_calculator' => array(
                'name' => __('Payment Calculator', 'resideo'),
                'position' => 7
            )
        );

        print '
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>' . __('Section', 'resideo') . '</th>
                        <th align="right">' . __('Position', 'resideo') . '</th>
                    </tr>
                </thead>
                <tbody>';

        if (is_array($options) && isset($options['resideo_property_layout_order_field'])) {
            uasort($options['resideo_property_layout_order_field'], "resideo_compare_position");

            foreach ($options['resideo_property_layout_order_field'] as $key => $value) {
                print '
                    <tr>
                        <td style="vertical-align: middle;"><input type="hidden" name="resideo_property_layout_settings[resideo_property_layout_order_field][' . $key . '][name]" value="' . $value['name'] . '">' . $value['name'] . '</td>
                        <td><input type="text" size="4" name="resideo_property_layout_settings[resideo_property_layout_order_field][' . $key . '][position]" value="' . $value['position'] . '"></td>
                    </tr>';
            }
        } else {
            foreach ($default_options as $key => $value) {
                print '
                    <tr>
                        <td style="vertical-align: middle;"><input type="hidden" name="resideo_property_layout_settings[resideo_property_layout_order_field][' . $key . '][name]" value="' . $value['name'] . '">' . $value['name'] . '</td>
                        <td><input type="text" size="4" name="resideo_property_layout_settings[resideo_property_layout_order_field][' . $key . '][position]" value="' . $value['position'] . '"></td>
                    </tr>';
            }
        }

        print '
                </tbody>
            </table>';
    }
endif;
?>
