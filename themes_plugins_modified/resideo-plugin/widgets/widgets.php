<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register custom widgets
 */
if (!function_exists('resideo_register_widgets')): 
    function resideo_register_widgets() {
        require 'recent_posts.php';
        require 'recent_properties.php';
        require 'featured_properties.php';
        require 'search_properties.php';
        require 'featured_agents.php';
        require 'contact.php';
        require 'contact_form.php';
        require 'social.php';
        require 'custom_search.php';

        register_widget('Resideo_Recent_Posts_Widget');
        register_widget('Resideo_Recent_Properties_Widget');
        register_widget('Resideo_Featured_Properties_Widget');
        register_widget('Resideo_Search_Properties_Widget');
        register_widget('Resideo_Featured_Agents_Widget');
        register_widget('Resideo_Contact_Widget');
        register_widget('Resideo_Contact_Form_Widget');
        register_widget('Resideo_Social_Widget');
    }
endif;
add_action('widgets_init', 'resideo_register_widgets');

add_filter('use_widgets_block_editor', '__return_false');
?>