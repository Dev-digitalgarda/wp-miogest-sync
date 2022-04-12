<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_appearance')): 
    function resideo_admin_appearance() {
        add_settings_section('resideo_appearance_section', __( 'Appearance', 'resideo' ), 'resideo_appearance_section_callback', 'resideo_appearance_settings');
        add_settings_field('resideo_theme_mode_field', __( 'Theme Mode', 'resideo' ), 'resideo_theme_mode_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_header_background_field', __( 'Header Background', 'resideo' ), 'resideo_header_background_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_sidebar_field', __( 'Sidebar Position', 'resideo' ), 'resideo_sidebar_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_properties_per_page_field', __( 'Properties per Page', 'resideo' ), 'resideo_properties_per_page_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_properties_per_page_agent_field', __( 'Properties per Page on Agent Profile', 'resideo' ), 'resideo_properties_per_page_agent_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_similar_field', __( 'Show Similar Properties on Property Page', 'resideo' ), 'resideo_similar_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_sticky_agent_cta_field', __( 'Make Contact Agent CTA Sticky on Property Page - Mobile View', 'resideo' ), 'resideo_sticky_agent_cta_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_agents_per_page_field', __( 'Agents per Page', 'resideo' ), 'resideo_agents_per_page_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_hide_agents_phone_field', __( 'Hide Agents Phone Number', 'resideo' ), 'resideo_hide_agents_phone_number_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_leads_per_page_field', __( 'Leads per Page', 'resideo' ), 'resideo_leads_per_page_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_related_posts_field', __( 'Show Related Articles on Blog Post', 'resideo' ), 'resideo_related_posts_field_render', 'resideo_appearance_settings', 'resideo_appearance_section');
        add_settings_field('resideo_blog_title_field', __('Blog Page Title', 'resideo'), 'resideo_blog_title_field_render', 'resideo_appearance_settings', 'resideo_appearance_section' );
        add_settings_field('resideo_blog_subtitle_field', __('Blog Page Subtitle', 'resideo'), 'resideo_blog_subtitle_field_render', 'resideo_appearance_settings', 'resideo_appearance_section' );
    }
endif;

if (!function_exists('resideo_appearance_section_callback')): 
    function resideo_appearance_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('resideo_theme_mode_field_render')): 
    function resideo_theme_mode_field_render() { 
        $options = get_option('resideo_appearance_settings');
        $modes = array(
            'light' => __('Light', 'resideo'),
            'dark' => __('Dark', 'resideo'),
        );

        $modes_select = '<select id="resideo_appearance_settings[resideo_theme_mode_field]" name="resideo_appearance_settings[resideo_theme_mode_field]">';

        foreach ($modes as $key => $value) {
            $modes_select .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_theme_mode_field']) && $options['resideo_theme_mode_field'] == $key) {
                $modes_select .= 'selected="selected"';
            }

            $modes_select .= '>' . esc_html($value) . '</option>';
        }

        $modes_select .= '</select>';

        print $modes_select;
    }
endif;

if (!function_exists('resideo_header_background_field_render')): 
    function resideo_header_background_field_render() { 
        $options = get_option('resideo_appearance_settings');
        $modes = array(
            'transparent' => __('Transparent', 'resideo'),
            'opaque' => __('Opaque', 'resideo'),
        );

        $modes_select = '<select id="resideo_appearance_settings[resideo_header_background_field]" name="resideo_appearance_settings[resideo_header_background_field]">';

        foreach ($modes as $key => $value) {
            $modes_select .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_header_background_field']) && $options['resideo_header_background_field'] == $key) {
                $modes_select .= 'selected="selected"';
            }

            $modes_select .= '>' . esc_html($value) . '</option>';
        }

        $modes_select .= '</select>';

        print $modes_select;
    }
endif;

if (!function_exists('resideo_sidebar_field_render')): 
    function resideo_sidebar_field_render() { 
        $options = get_option('resideo_appearance_settings');
        $sidebars = array(
            'left'  => __('Left', 'resideo'),
            'right' => __('Right', 'resideo'),
        );

        $sidebar_select = '<select id="resideo_appearance_settings[resideo_sidebar_field]" name="resideo_appearance_settings[resideo_sidebar_field]">';

        foreach ($sidebars as $key => $value) {
            $sidebar_select .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_sidebar_field']) && $options['resideo_sidebar_field'] == $key) {
                $sidebar_select .= 'selected="selected"';
            }

            $sidebar_select .= '>' . esc_html($value) . '</option>';
        }

        $sidebar_select .= '</select>';

        print $sidebar_select;
    }
endif;

if (!function_exists('resideo_properties_per_page_field_render')): 
    function resideo_properties_per_page_field_render() { 
        $options = get_option('resideo_appearance_settings'); ?>

        <input id="resideo_appearance_settings[resideo_properties_per_page_field]" type="number" step="1" min="1" style="width: 65px;" name="resideo_appearance_settings[resideo_properties_per_page_field]" value="<?php if (isset($options['resideo_properties_per_page_field'])) { echo esc_attr($options['resideo_properties_per_page_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_properties_per_page_agent_field_render')): 
    function resideo_properties_per_page_agent_field_render() { 
        $options = get_option('resideo_appearance_settings'); ?>

        <input id="resideo_appearance_settings[resideo_properties_per_page_agent_field]" type="number" step="1" min="1" style="width: 65px;" name="resideo_appearance_settings[resideo_properties_per_page_agent_field]" value="<?php if (isset($options['resideo_properties_per_page_agent_field'])) { echo esc_attr($options['resideo_properties_per_page_agent_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_similar_field_render')): 
    function resideo_similar_field_render() {
        $options = get_option('resideo_appearance_settings'); ?>

        <input type="checkbox" name="resideo_appearance_settings[resideo_similar_field]" <?php if(isset($options['resideo_similar_field'])) { checked($options['resideo_similar_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_sticky_agent_cta_field_render')): 
    function resideo_sticky_agent_cta_field_render() {
        $options = get_option('resideo_appearance_settings'); ?>

        <input type="checkbox" name="resideo_appearance_settings[resideo_sticky_agent_cta_field]" <?php if(isset($options['resideo_sticky_agent_cta_field'])) { checked($options['resideo_sticky_agent_cta_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_agents_per_page_field_render')): 
    function resideo_agents_per_page_field_render() { 
        $options = get_option('resideo_appearance_settings'); ?>

        <input id="resideo_appearance_settings[resideo_agents_per_page_field]" type="number" step="1" min="1" style="width: 65px;" name="resideo_appearance_settings[resideo_agents_per_page_field]" value="<?php if (isset($options['resideo_agents_per_page_field'])) { echo esc_attr($options['resideo_agents_per_page_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_hide_agents_phone_number_render')): 
    function resideo_hide_agents_phone_number_render() {
        $options = get_option('resideo_appearance_settings'); ?>

        <input type="checkbox" name="resideo_appearance_settings[resideo_hide_agents_phone_field]" <?php if (isset($options['resideo_hide_agents_phone_field'])) { checked($options['resideo_hide_agents_phone_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_leads_per_page_field_render')): 
    function resideo_leads_per_page_field_render() { 
        $options = get_option('resideo_appearance_settings'); ?>

        <input id="resideo_appearance_settings[resideo_leads_per_page_field]" type="number" step="1" min="1" style="width: 65px;" name="resideo_appearance_settings[resideo_leads_per_page_field]" value="<?php if (isset($options['resideo_leads_per_page_field'])) { echo esc_attr($options['resideo_leads_per_page_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_related_posts_field_render')): 
    function resideo_related_posts_field_render() {
        $options = get_option('resideo_appearance_settings'); ?>

        <input type="checkbox" name="resideo_appearance_settings[resideo_related_posts_field]" <?php if (isset($options['resideo_related_posts_field'])) { checked($options['resideo_related_posts_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_blog_title_field_render')): 
    function resideo_blog_title_field_render() { 
        $options = get_option('resideo_appearance_settings'); ?>

        <input id="resideo_appearance_settings[resideo_blog_title_field]" type="text" size="60" name="resideo_appearance_settings[resideo_blog_title_field]" value="<?php if (isset($options['resideo_blog_title_field'])) { echo esc_attr($options['resideo_blog_title_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_blog_subtitle_field_render')): 
    function resideo_blog_subtitle_field_render() { 
        $options = get_option('resideo_appearance_settings'); ?>

        <input id="resideo_appearance_settings[resideo_blog_subtitle_field]" type="text" size="60" name="resideo_appearance_settings[resideo_blog_subtitle_field]" value="<?php if (isset($options['resideo_blog_subtitle_field'])) { echo esc_attr($options['resideo_blog_subtitle_field']); } ?>" />
    <?php }
endif;
?>