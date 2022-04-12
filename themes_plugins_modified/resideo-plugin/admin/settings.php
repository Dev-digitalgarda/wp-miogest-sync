<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!defined('RESIDEO_VERSION')) {
    $resideo_theme = wp_get_theme('resideo');

    if ($resideo_theme->Version) {
        define('RESIDEO_VERSION', $resideo_theme->Version);
    }
}

add_action('admin_menu', 'resideo_add_admin_menu');
add_action('admin_init', 'resideo_settings_init');

if (!function_exists('resideo_add_admin_menu')): 
    function resideo_add_admin_menu() {
        add_menu_page('Resideo', 'Resideo', 'administrator', 'admin/settings.php', 'resideo_settings_page' , 'data:image/svg+xml;base64,' . 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMjggMTI4Ij48cGF0aCBkPSJNNzYuMTIsNDUuODRsLTguNzEtLjYzcS0xMi40NiwwLTE2LDcuODRWMTA2LjhIMjVWMjEuOTFINDkuNzZsLjg2LDEwLjlxNi42OC0xMi40NywxOC42LTEyLjQ3YTI1LjY4LDI1LjY4LDAsMCwxLDcuMzcuOTRaIiBzdHlsZT0iZmlsbDojZmZmIi8+PHBhdGggZD0iTTg4LjQ0LDgxLjE0YTE1LjA3LDE1LjA3LDAsMCwxLDEwLjQzLDMuNzMsMTMsMTMsMCwwLDEsMCwxOS4wNywxNS4wNiwxNS4wNiwwLDAsMS0xMC40MywzLjcyQTE0LjksMTQuOSwwLDAsMSw3OCwxMDMuOWExMywxMywwLDAsMSwwLTE5QTE0LjksMTQuOSwwLDAsMSw4OC40NCw4MS4xNFoiIHN0eWxlPSJmaWxsOiNmZmYiLz48L3N2Zz4=');
    }
endif;

if (!function_exists('resideo_settings_init')): 
    function resideo_settings_init() {
        wp_enqueue_style('font-awesome', RESIDEO_PLUGIN_PATH . 'css/font-awesome.min.css', array(), '4.7.0', 'all');
        wp_enqueue_style('simple-line-icons', RESIDEO_PLUGIN_PATH . 'css/simple-line-icons.css', array(), '2.3.2', 'all');
        if (is_rtl()) {
            wp_enqueue_style('resideo-settings-style-rtl', RESIDEO_PLUGIN_PATH . 'admin/css/style-rtl.css', false, '1.0', 'all');
        } else {
            wp_enqueue_style('resideo-settings-style', RESIDEO_PLUGIN_PATH . 'admin/css/style.css', false, '1.0', 'all');
        }
        wp_enqueue_script('media-upload');
        wp_enqueue_style('thickbox');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('my-upload');
        wp_enqueue_script('resideo-settings', RESIDEO_PLUGIN_PATH . 'admin/js/admin.js', array('wp-color-picker'), '1.0', true);

        wp_localize_script('resideo-settings', 'settings_vars',
            array(
                'plugin_url'          => RESIDEO_PLUGIN_PATH,
                'admin_url'           => get_admin_url(),
                'ajaxurl'             => admin_url('admin-ajax.php'),
                'logo_image_title'    => __('Logo Image', 'resideo'),
                'logo_image_btn'      => __('Insert Image', 'resideo'),
                'favicon_image_title' => __('Favicon Image', 'resideo'),
                'favicon_image_btn'   => __('Insert Image', 'resideo'),
                'avatar_title'        => __('User Profile Picture', 'resideo'),
                'avatar_btn'          => __('Insert Picture', 'resideo'),
            )
        );

        register_setting('resideo_welcome_settings', 'resideo_welcome_settings');
        register_setting('resideo_setup_settings', 'resideo_setup_settings');
        register_setting('resideo_general_settings', 'resideo_general_settings');
        register_setting('resideo_appearance_settings', 'resideo_appearance_settings');
        register_setting('resideo_colors_settings', 'resideo_colors_settings');
        register_setting('resideo_gmaps_settings', 'resideo_gmaps_settings');
        register_setting('resideo_property_layout_settings', 'resideo_property_layout_settings');
        register_setting('resideo_cities_settings', 'resideo_cities_settings');
        register_setting('resideo_neighborhoods_settings', 'resideo_neighborhoods_settings');
        register_setting('resideo_address_settings', 'resideo_address_settings');
        register_setting('resideo_prop_fields_settings', 'resideo_prop_fields_settings');
        register_setting('resideo_fields_settings', 'resideo_fields_settings');
        register_setting('resideo_amenities_settings', 'resideo_amenities_settings');
        register_setting('resideo_contact_fields_settings', 'resideo_contact_fields_settings');
        register_setting('resideo_authentication_settings', 'resideo_authentication_settings');
        register_setting('resideo_membership_settings', 'resideo_membership_settings');
        register_setting('resideo_notifications_settings', 'resideo_notifications_settings');
    }
endif;

/**
 * Load media files needed for Uploader
 */
if (!function_exists('resideo_load_wp_media_files')): 
    function resideo_load_wp_media_files() {
        wp_enqueue_media();
    }
endif;
add_action('admin_enqueue_scripts', 'resideo_load_wp_media_files');

require_once 'sections/welcome.php';
require_once 'sections/setup.php';
require_once 'sections/general.php';
require_once 'sections/appearance.php';
require_once 'sections/colors.php';
require_once 'sections/gmaps.php';
require_once 'sections/property_layout.php';
require_once 'sections/cities.php';
require_once 'sections/neighborhoods.php';
require_once 'sections/address.php';
require_once 'sections/property_fields.php';
require_once 'sections/custom_fields.php';
require_once 'sections/amenities.php';
require_once 'sections/contact_fields.php';
require_once 'sections/authentication.php';
require_once 'sections/membership.php';
require_once 'sections/notifications.php';

if (!function_exists('resideo_settings_page')): 
    function resideo_settings_page() { 
        $allowed_html = array();
        $active_tab = isset($_GET['tab']) ? wp_kses($_GET['tab'], $allowed_html) : 'welcome';
        $tab = 'resideo_welcome_settings';

        switch ($active_tab) {
            case "welcome":
                resideo_admin_welcome();
                $tab = 'resideo_welcome_settings';
                break;
            case "setup":
                resideo_admin_setup();
                $tab = 'resideo_setup_settings';
                break;
            case "general_settings":
                resideo_admin_general_settings();
                $tab = 'resideo_general_settings';
                break;
            case "appearance":
                resideo_admin_appearance();
                $tab = 'resideo_appearance_settings';
                break;
            case "colors":
                resideo_admin_colors();
                $tab = 'resideo_colors_settings';
                break;
            case "gmaps":
                resideo_admin_gmaps();
                $tab = 'resideo_gmaps_settings';
                break;
            case "property_layout":
                resideo_admin_property_layout();
                $tab = 'resideo_property_layout_settings';
                break;
            case "cities":
                resideo_admin_cities();
                $tab = 'resideo_cities_settings';
                break;
            case "neighborhoods":
                resideo_admin_neighborhoods();
                $tab = 'resideo_neighborhoods_settings';
                break;
            case "address":
                resideo_admin_address();
                $tab = 'resideo_address_settings';
                break;
            case "property_fields":
                resideo_admin_prop_fields();
                $tab = 'resideo_prop_fields_settings';
                break;
            case "fields":
                resideo_admin_fields();
                $tab = 'resideo_fields_settings';
                break;
            case "amenities":
                resideo_admin_amenities();
                $tab = 'resideo_amenities_settings';
                break;
            case "contact_fields":
                resideo_admin_contact_fields();
                $tab = 'resideo_contact_fields_settings';
                break;
            case "auth":
                resideo_admin_authentication();
                $tab = 'resideo_authentication_settings';
                break;
            case "membership":
                resideo_admin_membership();
                $tab = 'resideo_membership_settings';
                break;
            case "notifications":
                resideo_admin_notifications();
                $tab = 'resideo_notifications_settings';
                break;
        } ?>

        <div class="resideo-wrapper">
            <div class="resideo-leftSide">
                <div class="resideo-logo"><img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'admin/images/logo.png'); ?>"></div>
                <ul class="resideo-tabs">
                    <li class="<?php echo ($active_tab == 'welcome') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=welcome">
                            <span class="icon-info resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Welcome', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'setup') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=setup">
                            <span class="icon-magic-wand resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Theme Setup', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'general_settings') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=general_settings">
                            <span class="icon-settings resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('General Settings', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'appearance') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=appearance">
                            <span class="icon-screen-desktop resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Appearance', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'colors') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=colors">
                            <span class="icon-drop resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Colors', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'gmaps') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=gmaps">
                            <span class="icon-map resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Google Maps', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'property_layout') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=property_layout">
                            <span class="icon-doc resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Property Page Layout', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'cities') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=cities">
                            <span class="icon-directions resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Cities', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'neighborhoods') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=neighborhoods">
                            <span class="icon-frame resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Neighborhoods', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'address') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=address">
                            <span class="icon-location-pin resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Property Address Format', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'property_fields') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=property_fields">
                            <span class="icon-list resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Property Fields', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'fields') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=fields">
                            <span class="icon-plus resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Property Custom Fields', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'amenities') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=amenities">
                            <span class="icon-grid resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Amenities', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'contact_fields') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=contact_fields">
                            <span class="icon-envelope resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Contact Form Fields', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'auth') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=auth">
                            <span class="icon-user resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Authentication', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'membership') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=membership">
                            <span class="icon-credit-card resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Membership and Payment', 'resideo'); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'notifications') ? 'resideo-tab-active' : '' ?>">
                        <a href="admin.php?page=admin/settings.php&tab=notifications">
                            <span class="icon-bubbles resideo-tab-icon"></span><span class="resideo-tab-link"><?php esc_html_e('Notifications', 'resideo'); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="resideo-content">
                <div class="resideo-content-body">
                    <form action='options.php' method='post'>
                        <?php wp_nonce_field('update-options');
                        settings_fields($tab);
                        do_settings_sections($tab);

                        if ($active_tab != 'welcome' && $active_tab != 'setup') {
                            submit_button();
                        } ?>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php }
endif;
?>