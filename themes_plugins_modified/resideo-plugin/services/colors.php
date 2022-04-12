<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

$colors_settings = get_option('resideo_colors_settings');

$header_bg_color = isset($colors_settings['resideo_header_bg_color_field']) ? $colors_settings['resideo_header_bg_color_field'] : '';
$header_logo_color = isset($colors_settings['resideo_header_logo_color_field']) ? $colors_settings['resideo_header_logo_color_field'] : '';
$header_nav_color = isset($colors_settings['resideo_header_nav_color_field']) ? $colors_settings['resideo_header_nav_color_field'] : '';
$header_user_icon_color = isset($colors_settings['resideo_header_user_icon_color_field']) ? $colors_settings['resideo_header_user_icon_color_field'] : '';
$button_bg_color = isset($colors_settings['resideo_button_bg_color_field']) ? $colors_settings['resideo_button_bg_color_field'] : '';
$button_text_color = isset($colors_settings['resideo_button_text_color_field']) ? $colors_settings['resideo_button_text_color_field'] : '';
$feat_prop_bg_color = isset($colors_settings['resideo_feat_prop_bg_color_field']) ? $colors_settings['resideo_feat_prop_bg_color_field'] : '';
$feat_prop_text_color = isset($colors_settings['resideo_feat_prop_text_color_field']) ? $colors_settings['resideo_feat_prop_text_color_field'] : '';
$feat_post_bg_color = isset($colors_settings['resideo_feat_post_bg_color_field']) ? $colors_settings['resideo_feat_post_bg_color_field'] : '';
$feat_post_text_color = isset($colors_settings['resideo_feat_post_text_color_field']) ? $colors_settings['resideo_feat_post_text_color_field'] : '';
$map_marker_bg_color = isset($colors_settings['resideo_map_marker_bg_color_field']) ? $colors_settings['resideo_map_marker_bg_color_field'] : '';
$map_marker_border_color = isset($colors_settings['resideo_map_marker_border_color_field']) ? $colors_settings['resideo_map_marker_border_color_field'] : '';
$map_marker_text_color = isset($colors_settings['resideo_map_marker_text_color_field']) ? $colors_settings['resideo_map_marker_text_color_field'] : '';
$agent_card_cta_color = isset($colors_settings['resideo_agent_card_cta_color_field']) ? $colors_settings['resideo_agent_card_cta_color_field'] : '';
$post_card_cta_color = isset($colors_settings['resideo_post_card_cta_color_field']) ? $colors_settings['resideo_post_card_cta_color_field'] : '';
$footer_bg_color = isset($colors_settings['resideo_footer_bg_color_field']) ? $colors_settings['resideo_footer_bg_color_field'] : '';
$footer_text_color = isset($colors_settings['resideo_footer_text_color_field']) ? $colors_settings['resideo_footer_text_color_field'] : '';

if ($header_bg_color != '') {
    print '
        .pxp-header.pxp-is-sticky,
        .pxp-header.pxp-is-opaque,
        .pxp-header.pxp-full,
        .pxp-header.pxp-no-bg,
        .pxp-header.pxp-mobile:after,
        .pxp-dark-mode .pxp-header.pxp-is-sticky,
        .pxp-dark-mode .pxp-header.pxp-is-opaque,
        .pxp-dark-mode .pxp-header.pxp-full,
        .pxp-dark-mode .pxp-header.pxp-no-bg,
        .pxp-dark-mode .pxp-header.pxp-mobile:after {
            background-color: ' . esc_html($header_bg_color) . ';
        }
        .pxp-header.pxp-no-bg,
        .pxp-dark-mode .pxp-header.pxp-no-bg {
            border-bottom: 1px solid ' . esc_html($header_bg_color) . ';
        }
        @media screen and (max-width: 991px) {
            .pxp-nav,
            .pxp-dark-mode .pxp-nav {
                background-color: ' . esc_html($header_bg_color) . ';
            }
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul,
        .pxp-no-bg .pxp-nav > div > ul > li > ul,
        .pxp-full .pxp-nav > div > ul > li > ul, 
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul {
            background-color: ' . esc_html($header_bg_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul li > ul,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul li > ul,
        .pxp-no-bg .pxp-nav > div > ul > li > ul li > ul,
        .pxp-full .pxp-nav > div > ul > li > ul li > ul,
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul li > ul,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul li > ul,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul li > ul,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul li > ul {
            background-color: ' . esc_html($header_bg_color) . ';
        }
        .pxp-is-sticky .pxp-user-menu,
        .pxp-is-opaque .pxp-user-menu,
        .pxp-no-bg .pxp-user-menu,
        .pxp-full .pxp-user-menu, 
        .pxp-dark-mode .pxp-is-sticky .pxp-user-menu,
        .pxp-dark-mode .pxp-is-opaque .pxp-user-menu,
        .pxp-dark-mode .pxp-no-bg .pxp-user-menu,
        .pxp-dark-mode .pxp-full .pxp-user-menu {
            background-color: ' . esc_html($header_bg_color) . ';
        }
    ';
}
if ($header_logo_color != '') {
    print '
        .pxp-is-sticky .pxp-logo,
        .pxp-is-opaque .pxp-logo,
        .pxp-mobile .pxp-logo {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-dark-mode .pxp-is-sticky .pxp-logo,
        .pxp-dark-mode .pxp-is-opaque .pxp-logo,
        .pxp-dark-mode .pxp-mobile .pxp-logo {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-header.pxp-full .pxp-logo {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-dark-mode .pxp-header.pxp-full .pxp-logo {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-header.pxp-no-bg .pxp-logo {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-dark-mode .pxp-header.pxp-no-bg .pxp-logo {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-is-sticky .pxp-logo:hover,
        .pxp-is-opaque .pxp-logo:hover,
        .pxp-mobile .pxp-logo:hover {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-header.pxp-full .pxp-logo:hover {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-header.pxp-no-bg .pxp-logo:hover {
            color: ' . esc_html($header_logo_color) . ';
        }
        .pxp-dark-mode .pxp-is-sticky .pxp-logo:hover,
        .pxp-dark-mode .pxp-is-opaque .pxp-logo:hover,
        .pxp-dark-mode .pxp-mobile .pxp-logo:hover,
        .pxp-dark-mode .pxp-no-bg .pxp-logo:hover,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-logo:hover {
            color: ' . esc_html($header_logo_color) . ';
        }
    ';
}
if ($header_nav_color != '') {
    print '
        .pxp-is-sticky .pxp-nav > div > ul > li > a,
        .pxp-is-sticky .pxp-nav > div > ul > li > a:hover,
        .pxp-is-opaque .pxp-nav > div > ul > li > a,
        .pxp-is-opaque .pxp-nav > div > ul > li > a:hover,
        .pxp-mobile .pxp-nav > div > ul > li > a,
        .pxp-mobile .pxp-nav > div > ul > li> a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-no-bg .pxp-nav > div > ul > li > a,
        .pxp-no-bg .pxp-nav > div > ul > li > a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-header.pxp-full .pxp-nav > div > ul > li > a,
        .pxp-header.pxp-full .pxp-nav > div > ul > li > a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > a:after, 
        .pxp-is-opaque .pxp-nav > div > ul > li > a:after, 
        .pxp-mobile .pxp-nav > div > ul > li > a:after, 
        .pxp-is-sticky .pxp-nav > div > ul > li:hover > a:after, 
        .pxp-is-opaque .pxp-nav > div > ul > li:hover > a:after, 
        .pxp-mobile .pxp-nav > div > ul > li:hover > a:after {
            background: ' . esc_html($header_nav_color) . ';
        }
        .pxp-no-bg .pxp-nav > div > ul > li > a:after, 
        .pxp-no-bg .pxp-nav > div > ul > li:hover > a:after {
            background: ' . esc_html($header_nav_color) . ';
        }
        .pxp-header.pxp-full .pxp-nav > div > ul > li > a:after, 
        .pxp-header.pxp-full .pxp-nav > div > ul > li:hover > a:after {
            background: ' . esc_html($header_nav_color) . ';
        }
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > a,
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > a:hover,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > a,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > a:hover,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > a,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > a:hover,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > a,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > a:hover,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-nav > div > ul > li > a,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-nav > div > ul > li > a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > a:after,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > a:after,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > a:after,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > a:after,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-nav > div > ul > li > a:after, 
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li:hover > a:after,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li:hover > a:after,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li:hover > a:after,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li:hover > a:after,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-nav > div > ul > li:hover > a:after {
            background: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul > li > a,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul > li > a,
        .pxp-mobile .pxp-nav > div > ul > li > ul > li > a,
        .pxp-no-bg .pxp-nav > div > ul > li > ul > li > a,
        .pxp-full .pxp-nav > div > ul > li > ul > li > a,
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul > li > a,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul > li > a,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > ul > li > a,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul > li > a,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul > li > a {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-mobile .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-no-bg .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-full .pxp-nav > div > ul > li > ul > li > a:hover, 
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul > li > a:hover,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul > li > a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul li > span,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul li > span,
        .pxp-mobile .pxp-nav > div > ul > li > ul li > span,
        .pxp-no-bg .pxp-nav > div > ul > li > ul li > span,
        .pxp-full .pxp-nav > div > ul > li > ul li > span,
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul li > span,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul li > span,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > ul li > span,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul li > span,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul li > span {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-mobile .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-no-bg .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-full .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul li > ul > li > a,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul li > ul > li > a {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-is-opaque .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-mobile .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-no-bg .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-full .pxp-nav > div > ul > li > ul li > ul > li > a:hover, 
        .pxp-dark-mode .pxp-is-sticky .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-dark-mode .pxp-is-opaque .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-dark-mode .pxp-mobile .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-dark-mode .pxp-no-bg .pxp-nav > div > ul > li > ul li > ul > li > a:hover,
        .pxp-dark-mode .pxp-full .pxp-nav > div > ul > li > ul li > ul > li > a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-user-menu > li > a,
        .pxp-is-opaque .pxp-user-menu > li > a,
        .pxp-mobile .pxp-user-menu > li > a,
        .pxp-no-bg .pxp-user-menu > li > a,
        .pxp-full .pxp-user-menu > li > a,
        .pxp-dark-mode .pxp-is-sticky .pxp-user-menu > li > a,
        .pxp-dark-mode .pxp-is-opaque .pxp-user-menu > li > a,
        .pxp-dark-mode .pxp-mobile .pxp-user-menu > li > a,
        .pxp-dark-mode .pxp-no-bg .pxp-user-menu > li > a,
        .pxp-dark-mode .pxp-full .pxp-user-menu > li > a {
            color: ' . esc_html($header_nav_color) . ';
        }
        .pxp-is-sticky .pxp-user-menu > li > a:hover,
        .pxp-is-opaque .pxp-user-menu > li > a:hover,
        .pxp-mobile .pxp-user-menu > li > a:hover,
        .pxp-no-bg .pxp-user-menu > li > a:hover,
        .pxp-full .pxp-user-menu > li > a:hover,
        .pxp-dark-mode .pxp-is-sticky .pxp-user-menu > li > a:hover,
        .pxp-dark-mode .pxp-is-opaque .pxp-user-menu > li > a:hover,
        .pxp-dark-mode .pxp-mobile .pxp-user-menu > li > a:hover,
        .pxp-dark-mode .pxp-no-bg .pxp-user-menu > li > a:hover,
        .pxp-dark-mode .pxp-full .pxp-user-menu > li > a:hover {
            color: ' . esc_html($header_nav_color) . ';
        }
    ';
}
if ($header_user_icon_color != '') {
    print '
        .pxp-is-sticky .pxp-header-user, 
        .pxp-is-sticky .pxp-header-nav-trigger,
        .pxp-is-opaque .pxp-header-user, 
        .pxp-is-opaque .pxp-header-nav-trigger,
        .pxp-mobile .pxp-header-nav-trigger {
            border: 1px solid ' . esc_html($header_user_icon_color) . ';
            color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-no-bg .pxp-header-user, 
        .pxp-no-bg .pxp-header-nav-trigger {
            border: 1px solid ' . esc_html($header_user_icon_color) . ';
            color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-header.pxp-full .pxp-header-user, 
        .pxp-header.pxp-full .pxp-header-nav-trigger {
            border: 1px solid ' . esc_html($header_user_icon_color) . ';
            color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-is-sticky .pxp-header-user:hover, 
        .pxp-is-sticky .pxp-header-nav-trigger:hover,
        .pxp-is-opaque .pxp-header-user:hover, 
        .pxp-is-opaque .pxp-header-nav-trigger:hover,
        .pxp-mobile .pxp-header-nav-trigger:hover {
            color: #fff;
            background-color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-no-bg .pxp-header-user:hover, 
        .pxp-no-bg .pxp-header-nav-trigger:hover {
            color: #fff;
            background-color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-header.pxp-full .pxp-header-user:hover, 
        .pxp-header.pxp-full .pxp-header-nav-trigger:hover {
            color: #fff;
            background-color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-dark-mode .pxp-is-sticky .pxp-header-user, 
        .pxp-dark-mode .pxp-is-sticky .pxp-header-nav-trigger,
        .pxp-dark-mode .pxp-is-opaque .pxp-header-user, 
        .pxp-dark-mode .pxp-is-opaque .pxp-header-nav-trigger,
        .pxp-dark-mode .pxp-mobile .pxp-header-user, 
        .pxp-dark-mode .pxp-mobile .pxp-header-nav-trigger,
        .pxp-dark-mode .pxp-no-bg .pxp-header-user, 
        .pxp-dark-mode .pxp-no-bg .pxp-header-nav-trigger,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-header-user, 
        .pxp-dark-mode .pxp-header.pxp-full .pxp-header-nav-trigger {
            border: 1px solid ' . esc_html($header_user_icon_color) . ';
            color: ' . esc_html($header_user_icon_color) . ';
        }
        .pxp-dark-mode .pxp-is-sticky .pxp-header-user:hover, 
        .pxp-dark-mode .pxp-is-sticky .pxp-header-nav-trigger:hover,
        .pxp-dark-mode .pxp-is-opaque .pxp-header-user:hover, 
        .pxp-dark-mode .pxp-is-opaque .pxp-header-nav-trigger:hover,
        .pxp-dark-mode .pxp-mobile .pxp-header-user:hover, 
        .pxp-dark-mode .pxp-mobile .pxp-header-nav-trigger:hover,
        .pxp-dark-mode .pxp-no-bg .pxp-header-user:hover, 
        .pxp-dark-mode .pxp-no-bg .pxp-header-nav-trigger:hover,
        .pxp-dark-mode .pxp-header.pxp-full .pxp-header-user:hover, 
        .pxp-dark-mode .pxp-header.pxp-full .pxp-header-nav-trigger:hover {
            color: #fff;
            background-color: ' . esc_html($header_user_icon_color) . ';
        }
    ';
}
if ($button_bg_color != '') {
    print '
        .pxp-hero-search .pxp-hero-search-btn, .pxp-dark-mode .pxp-hero-search .pxp-hero-search-btn,
        .pxp-hero-contact-form-btn, .pxp-dark-mode .pxp-hero-contact-form-btn,
        .pxp-contact-section-form-btn, .pxp-dark-mode .pxp-contact-section-form-btn,
        .pxp-agent-contact-hero-btn, .pxp-dark-mode .pxp-agent-contact-hero-btn,
        .pxp-save-search-btn, .pxp-dark-mode .pxp-save-search-btn,
        .pxp-filter-btn, .pxp-dark-mode .pxp-filter-btn,
        .pxp-sp-agent-btn-main, .pxp-dark-mode .pxp-sp-agent-btn-main,
        .pxp-blog-post-comments-form-btn, .pxp-dark-mode .pxp-blog-post-comments-form-btn, 
        .pxp-agent-comments-form-btn, .pxp-dark-mode .pxp-agent-comments-form-btn,
        .pxp-agent-contact-btn, .pxp-dark-mode .pxp-agent-contact-btn,
        .pxp-agent-contact-modal-btn, .pxp-dark-mode .pxp-agent-contact-modal-btn,
        .pxp-report-property-modal-btn, .pxp-dark-mode .pxp-report-property-modal-btn,
        .pxp-user-modal-btn, .pxp-dark-mode .pxp-user-modal-btn,
        .pxp-properties-modal-btn, .pxp-dark-mode .pxp-properties-modal-btn,
        .contact-widget-form-send, .pxp-dark-mode .contact-widget-form-send,
        .pxp-side-section input[type="submit"], .pxp-dark-mode .pxp-side-section input[type="submit"],
        .pxp-contact-form-btn, .pxp-dark-mode .pxp-contact-form-btn,
        #dsidx.dsidx-details .dsidx-contact-form table input[type="button"], .pxp-dark-mode #dsidx.dsidx-details .dsidx-contact-form table input[type="button"],
        .pxp-submit-property-btn, .pxp-dark-mode .pxp-submit-property-btn,
        .pxp-submit-property-btn-delete-confirm, .pxp-dark-mode .pxp-submit-property-btn-delete-confirm,
        .pxp-new-floor-plan-ok-btn, .pxp-dark-mode .pxp-new-floor-plan-ok-btn,
        .pxp-edit-floor-plan-ok-btn, .pxp-dark-mode .pxp-edit-floor-plan-ok-btn,
        .pxp-saved-searches-btn-delete-confirm, .pxp-dark-mode .pxp-saved-searches-btn-delete-confirm,
        .pxp-wishlist-btn-delete-confirm, .pxp-dark-mode .pxp-wishlist-btn-delete-confirm,
        .pxp-my-properties-btn-delete-confirm, .pxp-dark-mode .pxp-my-properties-btn-delete-confirm,
        .pxp-my-leads-new-lead-btn, .pxp-dark-mode .pxp-my-leads-new-lead-btn,
        .pxp-my-leads-delete-lead-btn-confirm, .pxp-dark-mode .pxp-my-leads-delete-lead-btn-confirm,
        .pxp-my-leads-submit-lead-btn, .pxp-dark-mode .pxp-my-leads-delete-lead-btn-confirm,
        .pxp-my-leads-update-lead-btn, .pxp-dark-mode .pxp-my-leads-update-lead-btn,
        .pxp-account-settings-update-btn, .pxp-dark-mode .pxp-account-settings-update-btn,
        .pxp-my-properties-item-payment-paypal-btn, .pxp-dark-mode .pxp-my-properties-item-payment-paypal-btn,
        .pxp-my-properties-item-payment-upgrade-btn, .pxp-dark-mode .pxp-my-properties-item-payment-upgrade-btn,
        .pxp-my-properties-item-payment-featured-btn, .pxp-dark-mode .pxp-my-properties-item-payment-featured-btn,
        .pxp-activate-plan-btn, .pxp-dark-mode .pxp-activate-plan-btn,
        .pxp-pay-plan-btn, .pxp-dark-mode .pxp-pay-plan-btn,
        .pxp-dark-mode .pxp-footer .pxp-side-section input[type="submit"],
        .pxp-dark-mode .pxp-footer .contact-widget-form-send,
        .pxp-search-properties-form input[type="submit"], .pxp-dark-mode .pxp-search-properties-form input[type="submit"],
        .wpcf7-form .wpcf7-form-control.wpcf7-submit, .pxp-dark-mode .wpcf7-form .wpcf7-form-control.wpcf7-submit, .pxp-dark-mode .pxp-footer .pxp-side-section .wpcf7-form .wpcf7-form-control.wpcf7-submit {
            background-color: ' . esc_html($button_bg_color) . ';
            border: 1px solid ' . esc_html($button_bg_color) . ';
        }
        .pxp-blog-posts-carousel-1 .pxp-primary-cta {
            color: ' . esc_html($button_bg_color) . ';
        }
        .pxp-blog-posts-carousel-1 .pxp-primary-cta:after {
            border-top: 2px solid ' . esc_html($button_bg_color) . ';
        }
    ';
}
if ($button_text_color != '') {
    print '
        .pxp-hero-search .pxp-hero-search-btn, .pxp-dark-mode .pxp-hero-search .pxp-hero-search-btn, .pxp-hero-search .pxp-hero-search-btn:hover, .pxp-dark-mode .pxp-hero-search .pxp-hero-search-btn:hover,
        .pxp-hero-contact-form-btn, .pxp-dark-mode .pxp-hero-contact-form-btn, .pxp-hero-contact-form-btn:hover, .pxp-dark-mode .pxp-hero-contact-form-btn:hover,
        .pxp-contact-section-form-btn, .pxp-dark-mode .pxp-contact-section-form-btn, .pxp-contact-section-form-btn:hover, .pxp-dark-mode .pxp-contact-section-form-btn:hover,
        .pxp-agent-contact-hero-btn, .pxp-dark-mode .pxp-agent-contact-hero-btn, .pxp-agent-contact-hero-btn:hover, .pxp-dark-mode .pxp-agent-contact-hero-btn:hover,
        .pxp-save-search-btn, .pxp-dark-mode .pxp-save-search-btn, .pxp-save-search-btn:hover, .pxp-dark-mode .pxp-save-search-btn:hover,
        .pxp-filter-btn, .pxp-dark-mode .pxp-filter-btn, .pxp-filter-btn:hover, .pxp-dark-mode .pxp-filter-btn:hover,
        .pxp-sp-agent-btn-main, .pxp-dark-mode .pxp-sp-agent-btn-main, .pxp-sp-agent-btn-main:hover, .pxp-dark-mode .pxp-sp-agent-btn-main:hover,
        .pxp-blog-post-comments-form-btn, .pxp-dark-mode .pxp-blog-post-comments-form-btn, .pxp-blog-post-comments-form-btn:hover, .pxp-dark-mode .pxp-blog-post-comments-form-btn:hover, 
        .pxp-agent-comments-form-btn, .pxp-dark-mode .pxp-agent-comments-form-btn, .pxp-agent-comments-form-btn:hover, .pxp-dark-mode .pxp-agent-comments-form-btn:hover,
        .pxp-agent-contact-btn, .pxp-dark-mode .pxp-agent-contact-btn, .pxp-agent-contact-btn:hover, .pxp-dark-mode .pxp-agent-contact-btn:hover,
        .pxp-agent-contact-modal-btn, .pxp-dark-mode .pxp-agent-contact-modal-btn, .pxp-agent-contact-modal-btn:hover, .pxp-dark-mode .pxp-agent-contact-modal-btn:hover,
        .pxp-report-property-modal-btn, .pxp-dark-mode .pxp-report-property-modal-btn, .pxp-report-property-modal-btn:hover, .pxp-dark-mode .pxp-report-property-modal-btn:hover,
        .pxp-user-modal-btn, .pxp-dark-mode .pxp-user-modal-btn, .pxp-user-modal-btn:hover, .pxp-dark-mode .pxp-user-modal-btn:hover,
        .pxp-properties-modal-btn, .pxp-dark-mode .pxp-properties-modal-btn, .pxp-properties-modal-btn:hover, .pxp-dark-mode .pxp-properties-modal-btn:hover,
        .contact-widget-form-send, .pxp-dark-mode .contact-widget-form-send, .contact-widget-form-send:hover, .pxp-dark-mode .contact-widget-form-send:hover,
        .pxp-side-section input[type="submit"], .pxp-dark-mode .pxp-side-section input[type="submit"], .pxp-side-section input[type="submit"]:hover, .pxp-dark-mode .pxp-side-section input[type="submit"]:hover,
        .pxp-dark-mode .pxp-footer .pxp-side-section input[type="submit"], .pxp-dark-mode .pxp-footer .pxp-side-section input[type="submit"]:hover, 
        .pxp-contact-form-btn, .pxp-dark-mode .pxp-contact-form-btn, .pxp-contact-form-btn:hover, .pxp-dark-mode .pxp-contact-form-btn:hover, 
        #dsidx.dsidx-details .dsidx-contact-form table input[type="button"], .pxp-dark-mode #dsidx.dsidx-details .dsidx-contact-form table input[type="button"], #dsidx.dsidx-details .dsidx-contact-form table input[type="button"]:hover, .pxp-dark-mode #dsidx.dsidx-details .dsidx-contact-form table input[type="button"]:hover,
        .pxp-submit-property-btn, .pxp-dark-mode .pxp-submit-property-btn, .pxp-submit-property-btn:hover, .pxp-dark-mode .pxp-submit-property-btn:hover,
        .pxp-submit-property-btn-delete-confirm, .pxp-dark-mode .pxp-submit-property-btn-delete-confirm, .pxp-submit-property-btn-delete-confirm:hover, .pxp-dark-mode .pxp-submit-property-btn-delete-confirm:hover,
        .pxp-new-floor-plan-ok-btn, .pxp-dark-mode .pxp-new-floor-plan-ok-btn, .pxp-new-floor-plan-ok-btn:hover, .pxp-dark-mode .pxp-new-floor-plan-ok-btn:hover,
        .pxp-edit-floor-plan-ok-btn, .pxp-dark-mode .pxp-edit-floor-plan-ok-btn, .pxp-edit-floor-plan-ok-btn:hover, .pxp-dark-mode .pxp-edit-floor-plan-ok-btn:hover,
        .pxp-saved-searches-btn-delete-confirm, .pxp-dark-mode .pxp-saved-searches-btn-delete-confirm, .pxp-saved-searches-btn-delete-confirm:hover, .pxp-dark-mode .pxp-saved-searches-btn-delete-confirm:hover,
        .pxp-wishlist-btn-delete-confirm, .pxp-dark-mode .pxp-wishlist-btn-delete-confirm, .pxp-wishlist-btn-delete-confirm:hover, .pxp-dark-mode .pxp-wishlist-btn-delete-confirm:hover,
        .pxp-my-properties-btn-delete-confirm, .pxp-dark-mode .pxp-my-properties-btn-delete-confirm, .pxp-my-properties-btn-delete-confirm:hover, .pxp-dark-mode .pxp-my-properties-btn-delete-confirm:hover,
        .pxp-my-leads-new-lead-btn, .pxp-dark-mode .pxp-my-leads-new-lead-btn, .pxp-my-leads-new-lead-btn:hover, .pxp-dark-mode .pxp-my-leads-new-lead-btn:hover,
        .pxp-my-leads-delete-lead-btn-confirm, .pxp-dark-mode .pxp-my-leads-delete-lead-btn-confirm, .pxp-my-leads-delete-lead-btn-confirm:hover, .pxp-dark-mode .pxp-my-leads-delete-lead-btn-confirm:hover,
        .pxp-my-leads-submit-lead-btn, .pxp-dark-mode .pxp-my-leads-delete-lead-btn-confirm, .pxp-my-leads-submit-lead-btn:hover, .pxp-dark-mode .pxp-my-leads-delete-lead-btn-confirm:hover,
        .pxp-my-leads-update-lead-btn, .pxp-dark-mode .pxp-my-leads-update-lead-btn, .pxp-my-leads-update-lead-btn:hover, .pxp-dark-mode .pxp-my-leads-update-lead-btn:hover,
        .pxp-account-settings-update-btn, .pxp-dark-mode .pxp-account-settings-update-btn, .pxp-account-settings-update-btn:hover, .pxp-dark-mode .pxp-account-settings-update-btn:hover,
        .pxp-my-properties-item-payment-paypal-btn, .pxp-dark-mode .pxp-my-properties-item-payment-paypal-btn, .pxp-my-properties-item-payment-paypal-btn:hover, .pxp-dark-mode .pxp-my-properties-item-payment-paypal-btn:hover,
        .pxp-my-properties-item-payment-upgrade-btn, .pxp-dark-mode .pxp-my-properties-item-payment-upgrade-btn, .pxp-my-properties-item-payment-upgrade-btn:hover, .pxp-dark-mode .pxp-my-properties-item-payment-upgrade-btn:hover,
        .pxp-my-properties-item-payment-featured-btn, .pxp-dark-mode .pxp-my-properties-item-payment-featured-btn, .pxp-my-properties-item-payment-featured-btn:hover, .pxp-dark-mode .pxp-my-properties-item-payment-featured-btn:hover,
        .pxp-activate-plan-btn, .pxp-dark-mode .pxp-activate-plan-btn, .pxp-activate-plan-btn:hover, .pxp-dark-mode .pxp-activate-plan-btn:hover,
        .pxp-pay-plan-btn, .pxp-dark-mode .pxp-pay-plan-btn, .pxp-pay-plan-btn:hover, .pxp-dark-mode .pxp-pay-plan-btn:hover,
        .pxp-dark-mode .pxp-footer .contact-widget-form-send, .pxp-dark-mode .pxp-footer .contact-widget-form-send:hover,
        .pxp-search-properties-form input[type="submit"], .pxp-dark-mode .pxp-search-properties-form input[type="submit"],
        .wpcf7-form .wpcf7-form-control.wpcf7-submit, .pxp-dark-mode .wpcf7-form .wpcf7-form-control.wpcf7-submit, .pxp-dark-mode .pxp-footer .pxp-side-section .wpcf7-form .wpcf7-form-control.wpcf7-submit {
            color: ' . esc_html($button_text_color) . ';
        }
    ';
}
if ($feat_prop_bg_color != '') {
    print '
        .pxp-results-card-1-featured-label,
        .pxp-dark-mode .pxp-results-card-1-featured-label,
        .pxp-results-card-2-featured-label,
        .pxp-dark-mode .pxp-results-card-2-featured-label,
        .pxp-results-card-3-featured-label,
        .pxp-dark-mode .pxp-results-card-3-featured-label,
        .pxp-results-list-item-1-featured-label,
        .pxp-dark-mode .pxp-results-list-item-1-featured-label {
            background: ' . esc_html($feat_prop_bg_color) . ';
        }
        .pxp-results-card-1.pxp-is-featured,
        .pxp-dark-mode .pxp-results-card-1.pxp-is-featured,
        .pxp-results-card-2.pxp-is-featured .carousel-inner,
        .pxp-dark-mode .pxp-results-card-2.pxp-is-featured .carousel-inner,
        .pxp-results-card-3.pxp-is-featured .carousel-inner,
        .pxp-dark-mode .pxp-results-card-3.pxp-is-featured .carousel-inner,
        .pxp-results-list-item-1.pxp-is-featured .carousel-inner, 
        .pxp-dark-mode .pxp-results-list-item-1.pxp-is-featured .carousel-inner {
            border: 2px solid ' . esc_html($feat_prop_bg_color) . ';
        }
    ';
}
if ($feat_prop_text_color != '') {
    print '
        .pxp-results-card-1-featured-label, 
        .pxp-dark-mode .pxp-results-card-1-featured-label,
        .pxp-results-card-2-featured-label, 
        .pxp-dark-mode .pxp-results-card-2-featured-label,
        .pxp-results-card-3-featured-label, 
        .pxp-dark-mode .pxp-results-card-3-featured-label,
        .pxp-results-list-item-1-featured-label,
        .pxp-dark-mode .pxp-results-list-item-1-featured-label {
            color: ' . esc_html($feat_prop_text_color) . ';
        }
    ';
}
if ($feat_post_bg_color != '') {
    print '
        .pxp-posts-1-item-featured-label,
        .pxp-dark-mode .pxp-posts-1-item-featured-label {
            background: ' . esc_html($feat_post_bg_color) . ';
        }
    ';
}
if ($feat_post_text_color != '') {
    print '
        .pxp-posts-1-item-featured-label,
        .pxp-dark-mode .pxp-posts-1-item-featured-label {
            color: ' . esc_html($feat_prop_text_color) . ';
        }
    ';
}
if ($map_marker_bg_color != '') {
    print '
        .pxp-price-marker,
        .pxp-dark-mode .pxp-price-marker,
        .pxp-price-marker:after,
        .pxp-dark-mode .pxp-price-marker:after {
            background-color: ' . esc_html($map_marker_bg_color) . ';
        }
        .pxp-price-marker:hover, 
        .pxp-price-marker.active {
            background-color: #fff;
        }
        .pxp-dark-mode .pxp-price-marker:hover, 
        .pxp-dark-mode .pxp-price-marker.active {
            background-color: #000;
        }
        .pxp-price-marker:hover:after, 
        .pxp-price-marker.active:after {
            background-color: #fff;
        }
        .pxp-dark-mode .pxp-price-marker:hover:after, 
        .pxp-dark-mode .pxp-price-marker.active:after {
            background-color: #000;
        }
    ';
}
if ($map_marker_border_color != '') {
    print '
        .pxp-price-marker,
        .pxp-dark-mode .pxp-price-marker {
            border: 2px solid ' . esc_html($map_marker_border_color) . ';
        }
        .pxp-price-marker:after,
        .pxp-dark-mode .pxp-price-marker:after {
            border-right: 2px solid ' . esc_html($map_marker_border_color) . ';
            border-bottom: 2px solid ' . esc_html($map_marker_border_color) . ';
        }
    ';
}
if ($map_marker_text_color != '') {
    print '
        .pxp-price-marker,
        .pxp-dark-mode .pxp-price-marker {
            color: ' . esc_html($map_marker_text_color) . ';
        }
    ';
}
if ($agent_card_cta_color != '') {
    print '
        .pxp-agents-1-item-cta,
        .pxp-dark-mode .pxp-agents-1-item-cta {
            color: ' . esc_html($agent_card_cta_color) . ';
        }
    ';
}
if ($post_card_cta_color != '') {
    print '
        .pxp-posts-1-item-cta {
            color: ' . esc_html($post_card_cta_color) . ';
        }
    ';
}
if ($footer_bg_color != '') {
    print '
        .pxp-footer,
        .pxp-dark-mode .pxp-footer {
            background-color: ' . esc_html($footer_bg_color) . ';
        }
    ';
}
if ($footer_text_color != '') {
    print '
        .pxp-footer, .pxp-dark-mode .pxp-footer,
        .pxp-footer-bottom, .pxp-dark-mode .pxp-footer-bottom,
        .pxp-footer .pxp-side-section ul > li > a, .pxp-dark-mode .pxp-footer .pxp-side-section ul > li > a,
        .pxp-footer #wp-calendar caption, .pxp-dark-mode .pxp-footer #wp-calendar caption,
        .pxp-footer .wp-calendar-nav a, .pxp-dark-mode .pxp-footer .wp-calendar-nav a,
        .pxp-footer #wp-calendar tbody td, .pxp-dark-mode .pxp-footer #wp-calendar tbody td,
        .pxp-footer #wp-calendar tbody td a, .pxp-dark-mode .pxp-footer #wp-calendar tbody td a,
        .pxp-footer .resideo_featured_agents_sidebar .media > .media-body > h5, .pxp-dark-mode .pxp-footer .resideo_featured_agents_sidebar .media > .media-body > h5,
        .pxp-footer .resideo_featured_agents_sidebar .media > .media-body > .pxp-agent-side-phone, .pxp-dark-mode .pxp-footer .resideo_featured_agents_sidebar .media > .media-body > .pxp-agent-side-phone,
        .pxp-footer .resideo_featured_agents_sidebar .media > .media-body > .pxp-agent-side-cta, .pxp-dark-mode .pxp-footer .resideo_featured_agents_sidebar .media > .media-body > .pxp-agent-side-cta,
        .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > h5, .pxp-dark-mode .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > h5, 
        .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > h5, .pxp-dark-mode .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > h5,
        .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > .pxp-property-side-price, .pxp-dark-mode .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > .pxp-property-side-price, 
        .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > .pxp-property-side-price, .pxp-dark-mode .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > .pxp-property-side-price,
        .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > .pxp-property-side-features, .pxp-dark-mode .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > .pxp-property-side-features, 
        .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > .pxp-property-side-features, .pxp-dark-mode .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > .pxp-property-side-features,
        .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > .pxp-property-side-cta, .pxp-dark-mode .pxp-footer .resideo_featured_properties_sidebar .media > .media-body > .pxp-property-side-cta, 
        .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > .pxp-property-side-cta, .pxp-dark-mode .pxp-footer .resideo_recent_properties_sidebar .media > .media-body > .pxp-property-side-cta,
        .pxp-footer .resideo_recent_posts_sidebar .media > .media-body > h5, .pxp-dark-mode .pxp-footer .resideo_recent_posts_sidebar .media > .media-body > h5,
        .pxp-footer .resideo_recent_posts_sidebar .media > .media-body > .pxp-post-side-date, .pxp-dark-mode .pxp-footer .resideo_recent_posts_sidebar .media > .media-body > .pxp-post-side-date,
        .pxp-footer .resideo_social_sidebar a, .pxp-dark-mode .pxp-footer .resideo_social_sidebar a,
        .pxp-footer .widget_text a, .pxp-dark-mode .pxp-footer .widget_text a,
        .pxp-footer .widget_text a:hover, .pxp-dark-mode .pxp-footer .widget_text a:hover,
        .pxp-dark-mode .pxp-footer .pxp-side-section h3,
        .pxp-dark-mode .pxp-footer label,
        .pxp-dark-mode .pxp-footer #wp-calendar thead th,
        .pxp-dark-mode .pxp-footer .pxp-side-section ul > li,
        .pxp-dark-mode .pxp-footer .textwidget,
        .pxp-dark-mode .pxp-footer .textwidget a,
        .pxp-dark-mode .pxp-footer .widget_media_gallery .gallery .gallery-item .gallery-caption,
        .pxp-dark-mode .pxp-footer .widget_media_image .wp-caption-text,
        .pxp-dark-mode .pxp-footer .pxp-side-address,
        .pxp-dark-mode .pxp-footer-header,
        .pxp-dark-mode .pxp-footer-links a,
        .pxp-dark-mode .pxp-footer-bottom a {
            color: ' . esc_html($footer_text_color) . ';
        }
        .pxp-footer .widget_tag_cloud .tagcloud > a, .pxp-dark-mode .pxp-footer .widget_tag_cloud .tagcloud > a,
        .pxp-footer .widget_tag_cloud .tagcloud > a:hover, .pxp-dark-mode .pxp-footer .widget_tag_cloud .tagcloud > a:hover {
            color: ' . esc_html($footer_text_color) . ';
            border: 1px solid ' . esc_html($footer_text_color) . ';
        }
    ';
}
?>