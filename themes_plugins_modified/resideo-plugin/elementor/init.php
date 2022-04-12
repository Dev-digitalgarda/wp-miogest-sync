<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Resideo_Extension {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
    }

    public function init_widgets() {
        // Include Widget files
        require_once(__DIR__ . '/widgets/services.php');
        require_once(__DIR__ . '/widgets/recent_properties.php');
        require_once(__DIR__ . '/widgets/featured_properties.php');
        require_once(__DIR__ . '/widgets/single_property.php');
        require_once(__DIR__ . '/widgets/search_properties.php');
        require_once(__DIR__ . '/widgets/areas.php');
        require_once(__DIR__ . '/widgets/featured_agents.php');
        require_once(__DIR__ . '/widgets/membership_plans.php');
        require_once(__DIR__ . '/widgets/recent_posts.php');
        require_once(__DIR__ . '/widgets/featured_posts.php');
        require_once(__DIR__ . '/widgets/testimonials.php');
        require_once(__DIR__ . '/widgets/promo.php');
        require_once(__DIR__ . '/widgets/promo_slider.php');
        require_once(__DIR__ . '/widgets/subscribe.php');
        require_once(__DIR__ . '/widgets/gallery_carousel.php');
        require_once(__DIR__ . '/widgets/numbers.php');
        require_once(__DIR__ . '/widgets/awards.php');
        require_once(__DIR__ . '/widgets/contact.php');
        require_once(__DIR__ . '/widgets/video.php');

        // Register widgets
        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Services_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Recent_Properties_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Featured_Properties_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Single_Property_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Search_Properties_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Areas_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Featured_Agents_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Membership_Plans_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Recent_Posts_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Featured_Posts_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Testimonials_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Promo_Slider_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Promo_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Subscribe_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Gallery_Carousel_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Numbers_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Awards_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Contact_Widget());
        $widgets_manager->register_widget_type(new \Elementor_Resideo_Video_Widget());
    }
}

Elementor_Resideo_Extension::instance();

function resideo_add_elementor_widget_category($elements_manager) {
    $elements_manager->add_category(
        'resideo',
        [
            'title' => __('Resideo', 'resideo'),
            'icon' => 'fa fa-home',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'resideo_add_elementor_widget_category');
?>