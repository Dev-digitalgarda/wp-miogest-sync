<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class ResideoPageTemplater {
    private static $instance;
    protected $templates;

    public static function get_instance() {
        if(null == self::$instance) {
            self::$instance = new ResideoPageTemplater();
        }

        return self::$instance;
    }

    private function __construct() {
        $this->templates = array();

        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {
            add_filter('page_attributes_dropdown_pages_args', array($this, 'register_project_templates'));
        } else {
            add_filter('theme_page_templates', array($this, 'add_new_template'));
        }

        add_filter('wp_insert_post_data', array($this, 'register_project_templates'));
        add_filter('template_include', array($this, 'view_project_template'));

        $this->templates = array(
            'contact-page.php'        => 'Contact Page',
            'contact-page-office.php' => 'Contact Page Office',
            'agents-list.php'         => 'Agents List',
            'property-search.php'     => 'Property Search',
            'idx.php'                 => 'dsIDXPress',
            'idx-map-right.php'       => 'dsIDXPress Map Right',
            'idx-map-left.php'        => 'dsIDXPress Map Left',
            'idx-single.php'          => 'dsIDXPress Details',
            'idx.php'                 => 'dsIDXPress',
            'account-settings.php'    => 'Account Settings',
            'my-leads.php'            => 'My Leads',
            'my-properties.php'       => 'My Properties',
            'paypal-processor.php'    => 'Paypal Processor',
            'saved-searches.php'      => 'Saved Searches',
            'wish-list.php'           => 'Whish List',
            'submit-property.php'     => 'Submit Property',
        );
    }

    public function add_new_template($posts_templates) {
        $posts_templates = array_merge($posts_templates, $this->templates);

        return $posts_templates;
    }

    public function register_project_templates($atts) {
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
        $templates = wp_get_theme()->get_page_templates();

        if (empty($templates)) {
            $templates = array();
        }

        wp_cache_delete($cache_key , 'themes');

        $templates = array_merge($templates, $this->templates);

        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }

    public function view_project_template($template) {
        global $post;

        if (!$post) {
            return $template;
        }

        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
            return $template;
        }

        $file = plugin_dir_path( __FILE__ ) . get_post_meta($post->ID, '_wp_page_template', true);

        if (file_exists($file)) {
            return $file;
        } else {
            echo $file;
        }

        return $template;
    }
}
add_action('plugins_loaded', array('ResideoPageTemplater', 'get_instance'));