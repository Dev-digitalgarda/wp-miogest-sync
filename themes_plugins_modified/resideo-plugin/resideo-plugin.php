<?php
/*
* Plugin Name: Resideo Plugin
* Description: Core functionality for Resideo WP WordPress Theme.
* Text Domain: resideo
* Domain Path: /languages
* Version: 2.5.2
* Author: Pixel Prime
* Author URI: http://pixelprime.co
*/

define('RESIDEO_PLUGIN_PATH', plugin_dir_url( __FILE__ ));
define('RESIDEO_PLUGIN_BASENAME', plugin_basename(__FILE__));

add_action('plugins_loaded', 'resideo_load_textdomain');
function resideo_load_textdomain() {
    load_plugin_textdomain('resideo', false, dirname(plugin_basename( __FILE__ ) ) . '/languages');
}

/**
 * Custom post types
 */
require_once 'post-types/init.php';

/**
 * Shortcodes
 */
require_once 'shortcodes/shortcodes.php';

/**
 * Blocks
 */
require_once 'blocks/init.php';

/**
 * Widgets
 */
require_once 'widgets/widgets.php';

/**
 * Page templates
 */
require_once 'page-templates/init.php';

/**
 * Services
 */
require_once 'services/upload-scripts.php';
require_once 'services/settings.php';
require_once 'services/search-properties.php';
require_once 'services/properties.php';
require_once 'services/save-property.php';
require_once 'services/print-property.php';
require_once 'services/report-property.php';
require_once 'services/wishlist.php';
require_once 'services/my-properties.php';
require_once 'services/gallery-upload.php';
require_once 'services/floor-plan-upload.php';
require_once 'services/floor-plan-upload-edit.php';
require_once 'services/agents.php';
require_once 'services/users.php';
require_once 'services/leads.php';
require_once 'services/contact.php';
require_once 'services/contact-agent.php';
require_once 'services/avatar-upload.php';
require_once 'services/paypal.php';
require_once 'services/warnings.php';
require_once 'services/subscription.php';

/**
 * Views
 */
require_once 'views/social-meta.php';
require_once 'views/user-modal.php';
require_once 'views/dashboard-nav.php';
require_once 'views/filter-properties-form.php';
require_once 'views/filter-properties-form-sidebar.php';
require_once 'views/modal-calculator.php';
require_once 'views/similar-properties.php';
require_once 'views/contact-agent-modal.php';
require_once 'views/contact-agent-hero.php';
require_once 'views/work-with-agent-modal.php';
require_once 'views/report-property-modal.php';
require_once 'views/page-header.php';
require_once 'views/search-properties-form.php';
require_once 'views/save-search-modal.php';
require_once 'views/single-share.php';
require_once 'views/property-video.php';
require_once 'views/property-virtual-tour.php';

/**
 * Admin
 */
require_once 'admin/settings.php';

/**
 * Elementor
 */

require_once 'elementor/init.php';

/**
 * Custom colors
 */
if (!function_exists('resideo_add_custom_colors')): 
    function resideo_add_custom_colors() {
        echo '<style>';
        require_once 'services/colors.php';
        echo '</style>';
    }
endif;
add_action('wp_head', 'resideo_add_custom_colors');

/**
 * Custom 2nd logo
 */
if (!function_exists('resideo_add_second_logo_setting')): 
    function resideo_add_second_logo_setting($wp_customize) {
        $wp_customize->add_setting('resideo_second_logo');

        $wp_customize->add_control(
            new WP_Customize_Cropped_Image_Control(
                $wp_customize, 
                'resideo_second_logo', 
                array(
                    'label'      => __('Fixed Header Logo', 'resideo'),
                    'section'    => 'title_tagline',
                    'settings'   => 'resideo_second_logo',
                    'priority'   => 10,
                    'height'     => 300,
                    'width'      => 300,
                    'flex-width' => true,
                )
            )
        );
    }
endif;
add_action('customize_register', 'resideo_add_second_logo_setting');
?>