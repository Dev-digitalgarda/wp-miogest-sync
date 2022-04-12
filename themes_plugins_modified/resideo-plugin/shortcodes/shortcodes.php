<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

require_once 'services.php';
require_once 'recent_properties.php';
require_once 'featured_properties.php';
require_once 'single_property.php';
require_once 'search_properties.php';
require_once 'areas.php';
require_once 'featured_agents.php';
require_once 'membership_plans.php';
require_once 'recent_posts.php';
require_once 'featured_posts.php';
require_once 'testimonials.php';
require_once 'promo.php';
require_once 'slider_promo.php';
require_once 'subscribe.php';
require_once 'gallery_carousel.php';
require_once 'numbers.php';
require_once 'awards.php';
require_once 'contact.php';
require_once 'video.php';

if (!function_exists('resideo_register_shortcodes_buttons')): 
    function resideo_register_shortcodes_buttons($buttons) {
        global $post;

        $buttons = array();

        if (isset($post)) {
            if ($post->post_type == 'page') {
                array_push($buttons, "", "res_services");
                array_push($buttons, "", "res_recent_properties");
                array_push($buttons, "", "res_featured_properties");
                array_push($buttons, "", "res_single_property");
                array_push($buttons, "", "res_search_properties");
                array_push($buttons, "", "res_areas");
                array_push($buttons, "", "res_featured_agents");
                array_push($buttons, "", "res_membership_plans");
                array_push($buttons, "", "res_recent_posts");
                array_push($buttons, "", "res_featured_posts");
                array_push($buttons, "", "res_testimonials");
                array_push($buttons, "", "res_promo");
                array_push($buttons, "", "res_slider_promo");
                array_push($buttons, "", "res_subscribe");
                array_push($buttons, "", "res_gallery_carousel");
                array_push($buttons, "", "res_numbers");
                array_push($buttons, "", "res_awards");
                array_push($buttons, "", "res_contact");
                array_push($buttons, "", "res_video");
            }
        }

        return $buttons;
    }
endif;

if (!function_exists('resideo_add_plugins')): 
    function resideo_add_plugins($plugin_array) {
        $plugin_array['res_services']            = RESIDEO_PLUGIN_PATH . 'shortcodes/js/services.js';
        $plugin_array['res_recent_properties']   = RESIDEO_PLUGIN_PATH . 'shortcodes/js/recent-properties.js';
        $plugin_array['res_featured_properties'] = RESIDEO_PLUGIN_PATH . 'shortcodes/js/featured-properties.js';
        $plugin_array['res_single_property']     = RESIDEO_PLUGIN_PATH . 'shortcodes/js/single-property.js';
        $plugin_array['res_search_properties']   = RESIDEO_PLUGIN_PATH . 'shortcodes/js/search-properties.js';
        $plugin_array['res_areas']               = RESIDEO_PLUGIN_PATH . 'shortcodes/js/areas.js';
        $plugin_array['res_featured_agents']     = RESIDEO_PLUGIN_PATH . 'shortcodes/js/featured-agents.js';
        $plugin_array['res_membership_plans']    = RESIDEO_PLUGIN_PATH . 'shortcodes/js/membership-plans.js';
        $plugin_array['res_recent_posts']        = RESIDEO_PLUGIN_PATH . 'shortcodes/js/recent-posts.js';
        $plugin_array['res_featured_posts']      = RESIDEO_PLUGIN_PATH . 'shortcodes/js/featured-posts.js';
        $plugin_array['res_testimonials']        = RESIDEO_PLUGIN_PATH . 'shortcodes/js/testimonials.js';
        $plugin_array['res_promo']               = RESIDEO_PLUGIN_PATH . 'shortcodes/js/promo.js';
        $plugin_array['res_slider_promo']        = RESIDEO_PLUGIN_PATH . 'shortcodes/js/slider-promo.js';
        $plugin_array['res_subscribe']           = RESIDEO_PLUGIN_PATH . 'shortcodes/js/subscribe.js';
        $plugin_array['res_gallery_carousel']    = RESIDEO_PLUGIN_PATH . 'shortcodes/js/gallery-carousel.js';
        $plugin_array['res_numbers']             = RESIDEO_PLUGIN_PATH . 'shortcodes/js/numbers.js';
        $plugin_array['res_awards']              = RESIDEO_PLUGIN_PATH . 'shortcodes/js/awards.js';
        $plugin_array['res_contact']             = RESIDEO_PLUGIN_PATH . 'shortcodes/js/contact.js';
        $plugin_array['res_video']               = RESIDEO_PLUGIN_PATH . 'shortcodes/js/video.js';

        if(is_rtl()) {
            wp_enqueue_style('res_custom_rtl');
        } else {
            wp_enqueue_style('res_custom');
        }

        wp_enqueue_script('res_modal');
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('simple-line-icons');

        return $plugin_array;
    }
endif;

if (!function_exists('resideo_register_plugin_buttons')): 
    function resideo_register_plugin_buttons() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        add_editor_style(RESIDEO_PLUGIN_PATH . 'shortcodes/css/editor.css');

        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', 'resideo_add_plugins');
            add_filter('mce_buttons_3', 'resideo_register_shortcodes_buttons');
        }
    }
endif;

if (!function_exists('resideo_admin_enqueue_shortcodes_scripts')): 
    function resideo_admin_enqueue_shortcodes_scripts() {
        wp_enqueue_style('wp-color-picker', false, true);
        wp_enqueue_script('wp-color-picker', false, true);

        if (is_rtl()) {
            wp_register_style('res_custom_rtl', RESIDEO_PLUGIN_PATH . 'shortcodes/css/custom-rtl.css');
        } else {
            wp_register_style('res_custom', RESIDEO_PLUGIN_PATH . 'shortcodes/css/custom.css');
        }

        wp_register_style('font-awesome', RESIDEO_PLUGIN_PATH . 'css/font-awesome.min.css', array(), '4.7.0', 'all');
        wp_register_style('simple-line-icons', RESIDEO_PLUGIN_PATH . 'css/simple-line-icons.css', array(), '2.3.2', 'all');
    }
endif;

if (!function_exists('resideo_register_shortcodes')): 
    function resideo_register_shortcodes() {
        add_shortcode('res_services', 'resideo_services_shortcode');
        add_shortcode('res_recent_properties', 'resideo_recent_properties_shortcode');
        add_shortcode('res_featured_properties', 'resideo_featured_properties_shortcode');
        add_shortcode('res_single_property', 'resideo_single_property_shortcode');
        add_shortcode('res_search_properties', 'resideo_search_properties_shortcode');
        add_shortcode('res_areas', 'resideo_areas_shortcode');
        add_shortcode('res_featured_agents', 'resideo_featured_agents_shortcode');
        add_shortcode('res_membership_plans', 'resideo_membership_plans_shortcode');
        add_shortcode('res_recent_posts', 'resideo_recent_posts_shortcode');
        add_shortcode('res_featured_posts', 'resideo_featured_posts_shortcode');
        add_shortcode('res_testimonials', 'resideo_testimonials_shortcode');
        add_shortcode('res_promo', 'resideo_promo_shortcode');
        add_shortcode('res_slider_promo', 'resideo_slider_promo_shortcode');
        add_shortcode('res_subscribe', 'resideo_subscribe_shortcode');
        add_shortcode('res_gallery_carousel', 'resideo_gallery_carousel_shortcode');
        add_shortcode('res_numbers', 'resideo_numbers_shortcode');
        add_shortcode('res_awards', 'resideo_awards_shortcode');
        add_shortcode('res_contact', 'resideo_contact_shortcode');
        add_shortcode('res_video', 'resideo_video_shortcode');
        add_action('admin_enqueue_scripts', 'resideo_admin_enqueue_shortcodes_scripts');
    }
endif;

foreach (array('post.php', 'post-new.php') as $hook) {
    add_action("admin_head-$hook", 'resideo_admin_head');
    add_action("admin_head-$hook", 'resideo_register_plugin_buttons');
}

if (!function_exists('resideo_get_sh_cities')): 
    function resideo_get_sh_cities() {
        $resideo_cities_settings = get_option('resideo_cities_settings');

        if (is_array($resideo_cities_settings) && count($resideo_cities_settings) > 0) {
            uasort($resideo_cities_settings, "resideo_compare_position");

            $cities = array();

            foreach ($resideo_cities_settings as $key => $value) {
                $city = new stdClass();

                $city->id = $key;
                $city->name = $value['name'];

                array_push($cities, $city);
            }

            return urlencode(json_encode($cities, true));
        } else {
            return '';
        }
    }
endif;

if (!function_exists('resideo_get_sh_neighborhoods')): 
    function resideo_get_sh_neighborhoods() {
        $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings');

        if (is_array($resideo_neighborhoods_settings) && count($resideo_neighborhoods_settings) > 0) {
            uasort($resideo_neighborhoods_settings, "resideo_compare_position");

            $neighborhoods = array();

            foreach ($resideo_neighborhoods_settings as $key => $value) {
                $neighborhood = new stdClass();

                $neighborhood->id = $key;
                $neighborhood->name = $value['name'];

                array_push($neighborhoods, $neighborhood);
            }

            return urlencode(json_encode($neighborhoods, true));
        } else {
            return '';
        }
    }
endif;

if (!function_exists('resideo_get_sh_properties')): 
    function resideo_get_sh_properties() {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'property',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        );
        $props = array();

        $posts = get_posts($args);

        foreach ($posts as $post) : setup_postdata($post);
            $prop = new stdClass();

            $prop->id = $post->ID;
            $prop->title = $post->post_title;

            array_push($props, $prop);
        endforeach;

        wp_reset_postdata();
        wp_reset_query();

        if (count($props) > 0) {
            return urlencode(json_encode($props, true));
        } else {
            return '';
        }
    }
endif;

if (!function_exists('resideo_get_sh_property_custom_fields')): 
    function resideo_get_sh_property_custom_fields() {
        $custom_fields_settings = get_option('resideo_fields_settings');

        if (is_array($custom_fields_settings)) {
            uasort($custom_fields_settings, "resideo_compare_position");

            return urlencode(json_encode($custom_fields_settings, true));
        } else {
            return '';
        }
    }
endif;

function resideo_admin_head() {
    $plugin_url = plugins_url('/', __FILE__);

    $fields_settings   = get_option('resideo_prop_fields_settings');
    $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
    $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';

    $resideo_gmaps_settings = get_option('resideo_gmaps_settings', '');
    $default_lat            = isset($resideo_gmaps_settings['resideo_gmaps_lat_field']) ? $resideo_gmaps_settings['resideo_gmaps_lat_field'] : '';
    $default_lng            = isset($resideo_gmaps_settings['resideo_gmaps_lng_field']) ? $resideo_gmaps_settings['resideo_gmaps_lng_field'] : '';

    $resideo_general_settings = get_option('resideo_general_settings', '');
    $auto_country             = isset($resideo_general_settings['resideo_auto_country_field']) ? $resideo_general_settings['resideo_auto_country_field'] : '';

    $appearance_settings = get_option('resideo_appearance_settings');
    $hide_agents_phone = isset($appearance_settings['resideo_hide_agents_phone_field']) ? $appearance_settings['resideo_hide_agents_phone_field'] : ''; ?>

    <!-- TinyMCE Shortcode Plugin -->
    <script type='text/javascript'>
    var sh_vars = {
        'admin_url'                           : '<?php echo get_admin_url(); ?>',
        'ajaxurl'                             : '<?php echo admin_url('admin-ajax.php'); ?>',
        'plugin_url'                          : '<?php echo RESIDEO_PLUGIN_PATH; ?>',
        'services_title'                      : '<?php esc_html_e('Services', 'resideo'); ?>',
        'cancel_btn'                          : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'insert_btn'                          : '<?php esc_html_e('Insert', 'resideo'); ?>',
        'title_label'                         : '<?php esc_html_e('Title', 'resideo'); ?>',
        'title_placeholder'                   : '<?php esc_html_e('Enter title', 'resideo'); ?>',
        'subtitle_label'                      : '<?php esc_html_e('Subtitle', 'resideo'); ?>',
        'subtitle_placeholder'                : '<?php esc_html_e('Enter subtitle', 'resideo'); ?>',
        'services_list'                       : '<?php esc_html_e('Services List', 'resideo'); ?>',
        'add_service_btn'                     : '<?php esc_html_e('Add New Service', 'resideo'); ?>',
        'empty_list'                          : '<?php esc_html_e('The list is empty.', 'resideo'); ?>',
        'service_title_label'                 : '<?php esc_html_e('Service Title', 'resideo'); ?>',
        'service_title_placeholder'           : '<?php esc_html_e('Enter service title', 'resideo'); ?>',
        'service_text_label'                  : '<?php esc_html_e('Service Text', 'resideo'); ?>',
        'service_text_placeholder'            : '<?php esc_html_e('Enter service text', 'resideo'); ?>',
        'new_service_header'                  : '<?php esc_html_e('New Service', 'resideo'); ?>',
        'service_link_label'                  : '<?php esc_html_e('Service Link', 'resideo'); ?>',
        'service_link_placeholder'            : '<?php esc_html_e('Enter service link', 'resideo'); ?>',
        'service_cta_label'                   : '<?php esc_html_e('Service CTA Label', 'resideo'); ?>',
        'service_cta_label_placeholder'       : '<?php esc_html_e('Enter service CTA lablel', 'resideo'); ?>',
        'ok_service_btn'                      : '<?php esc_html_e('Add', 'resideo'); ?>',
        'cancel_service_btn'                  : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'service_add_img'                     : '<?php esc_html_e('Add Image', 'resideo'); ?>',
        'service_add_icon'                    : '<?php esc_html_e('Add Icon', 'resideo'); ?>',
        'service_image'                       : '<?php esc_html_e('Service Image', 'resideo'); ?>',
        'service_insert_image'                : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'edit_service_header'                 : '<?php esc_html_e('Edit Service', 'resideo'); ?>',
        'ok_edit_service_btn'                 : '<?php esc_html_e('OK', 'resideo'); ?>',
        'service_icon_color'                  : '<?php esc_html_e('Icon Color', 'resideo'); ?>',
        'recent_properties_title'             : '<?php esc_html_e('Recent Properties', 'resideo'); ?>',
        'featured_properties_title'           : '<?php esc_html_e('Featured Properties', 'resideo'); ?>',
        'remove_btn'                          : '<?php esc_html_e('Remove', 'resideo'); ?>',
        'edit_btn'                            : '<?php esc_html_e('Edit', 'resideo'); ?>',
        'all_label'                           : '<?php esc_html_e('All', 'resideo'); ?>',
        'type_label'                          : '<?php esc_html_e('Type', 'resideo'); ?>',
        'status_label'                        : '<?php esc_html_e('Status', 'resideo'); ?>',
        'prop_number_label'                   : '<?php esc_html_e('Number of Properties', 'resideo'); ?>',
        'prop_number_placeholder'             : '<?php esc_html_e('Enter number of properties', 'resideo'); ?>',
        'cards_design_label'                  : '<?php esc_html_e('Cards Design', 'resideo'); ?>',
        'cards_display_label'                 : '<?php esc_html_e('Display', 'resideo'); ?>',
        'columns_type_label'                  : '<?php esc_html_e('Column Type', 'resideo'); ?>',
        'sh_delete_confirmation'              : '<?php esc_html_e('Are you sure you want to delete the shortcode?', 'resideo'); ?>',
        'info_title'                          : '<?php esc_html_e('Info', 'resideo'); ?>',
        'cta_text_label'                      : '<?php esc_html_e('CTA Button Text', 'resideo'); ?>',
        'cta_text_placeholder'                : '<?php esc_html_e('Enter the CTA button text', 'resideo'); ?>',
        'cta_link_label'                      : '<?php esc_html_e('CTA Button Link', 'resideo'); ?>',
        'cta_link_placeholder'                : '<?php esc_html_e('Enter the CTA button link', 'resideo'); ?>',
        'card_cta_text_label'                 : '<?php esc_html_e('Agent Card CTA text', 'resideo'); ?>',
        'card_cta_text_placeholder'           : '<?php esc_html_e('Enter the CTA text', 'resideo'); ?>',
        'img_label'                           : '<?php esc_html_e('Background Image', 'resideo'); ?>',
        'media_info_image_title'              : '<?php esc_html_e('Info Background Image', 'resideo'); ?>',
        'media_info_image_btn'                : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'media_services_image_title'          : '<?php esc_html_e('Services Background Image', 'resideo'); ?>',
        'media_services_image_btn'            : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'color_label'                         : '<?php esc_html_e('Background Color', 'resideo'); ?>',
        'color_opacity_label'                 : '<?php esc_html_e('Background Color Opacity', 'resideo'); ?>',
        'width_label'                         : '<?php esc_html_e('Width', 'resideo'); ?>',
        'wide_label'                          : '<?php esc_html_e('Wide', 'resideo'); ?>',
        'boxed_label'                         : '<?php esc_html_e('Boxed', 'resideo'); ?>',
        'height_label'                        : '<?php esc_html_e('Height', 'resideo'); ?>',
        'height_placeholder'                  : '<?php esc_html_e('Enter the height', 'resideo'); ?>',
        'search_properties_title'             : '<?php esc_html_e('Search Properties', 'resideo'); ?>',
        'fields_list_label'                   : '<?php esc_html_e('Fields List', 'resideo'); ?>',
        'sp_id_label'                         : '<?php esc_html_e('Property ID', 'resideo'); ?>',
        'sp_address_label'                    : '<?php esc_html_e('Address', 'resideo'); ?>',
        'sp_city_label'                       : '<?php esc_html_e('City', 'resideo'); ?>',
        'sp_neighborhood_label'               : '<?php esc_html_e('Neighborhood', 'resideo'); ?>',
        'sp_state_label'                      : '<?php esc_html_e('County/State', 'resideo'); ?>',
        'sp_price_label'                      : '<?php esc_html_e('Price', 'resideo'); ?>',
        'sp_size_label'                       : '<?php esc_html_e('Size', 'resideo'); ?>',
        'sp_beds_label'                       : '<?php esc_html_e('Beds', 'resideo'); ?>',
        'sp_baths_label'                      : '<?php esc_html_e('Baths', 'resideo'); ?>',
        'sp_type_label'                       : '<?php esc_html_e('Type', 'resideo'); ?>',
        'sp_status_label'                     : '<?php esc_html_e('Status', 'resideo'); ?>',
        'sp_keywords_label'                   : '<?php esc_html_e('Keywords', 'resideo'); ?>',
        'sp_amenities_label'                  : '<?php esc_html_e('Amenities', 'resideo'); ?>',
        'custom_fields_list_label'            : '<?php esc_html_e('Custom Fields List', 'resideo'); ?>',
        'limit_main_fields_label'             : '<?php esc_html_e('Limit Main Fields', 'resideo'); ?>',
        'fields_display_label'                : '<?php esc_html_e('Display', 'resideo'); ?>',
        'fields_main_area_label'              : '<?php esc_html_e('fields in main area', 'resideo'); ?>',
        'areas_title'                         : '<?php esc_html_e('Areas', 'resideo'); ?>',
        'areas_list'                          : '<?php esc_html_e('Areas List', 'resideo'); ?>',
        'add_area_btn'                        : '<?php esc_html_e('Add New Area', 'resideo'); ?>',
        'new_area_header'                     : '<?php esc_html_e('New Area', 'resideo'); ?>',
        'area_add_img'                        : '<?php esc_html_e('Add Image', 'resideo'); ?>',
        'area_neighborhood_label'             : '<?php esc_html_e('Neighborhood', 'resideo'); ?>',
        'area_neighborhood_placeholder'       : '<?php esc_html_e('Enter neighborhood', 'resideo'); ?>',
        'area_city_label'                     : '<?php esc_html_e('City', 'resideo'); ?>',
        'area_city_placeholder'               : '<?php esc_html_e('Enter city', 'resideo'); ?>',
        'ok_area_btn'                         : '<?php esc_html_e('Add', 'resideo'); ?>',
        'cancel_area_btn'                     : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'edit_area_header'                    : '<?php esc_html_e('Edit Area', 'resideo'); ?>',
        'ok_edit_area_btn'                    : '<?php esc_html_e('OK', 'resideo'); ?>',
        'cancel_edit_area_btn'                : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'area_image'                          : '<?php esc_html_e('Area Image', 'resideo'); ?>',
        'area_insert_image'                   : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'areas_cities_list'                   : '<?php echo resideo_get_sh_cities(); ?>',
        'areas_neighborhoods_list'            : '<?php echo resideo_get_sh_neighborhoods(); ?>',
        'areas_city_type'                     : '<?php echo esc_html($city_type); ?>',
        'areas_neighborhood_type'             : '<?php echo esc_html($neighborhood_type); ?>',
        'areas_select_neighborhood'           : '<?php esc_html_e('Select a neighborhood', 'resideo'); ?>',
        'areas_select_city'                   : '<?php esc_html_e('Select a city', 'resideo'); ?>',
        'properties_title'                    : '<?php esc_html_e('Properties Slider', 'resideo'); ?>',
        'properties_list'                     : '<?php esc_html_e('Properties List', 'resideo'); ?>',
        'add_property_btn'                    : '<?php esc_html_e('Add Property', 'resideo'); ?>',
        'modal_properties'                    : '<?php esc_html_e('Properties', 'resideo'); ?>',
        'search_properties'                   : '<?php esc_html_e('Search properties', 'resideo'); ?>',
        'modal_properties_results'            : '<?php esc_html_e('Properties', 'resideo'); ?>',
        'load_more_properties'                : '<?php esc_html_e('Load 20 more properties', 'resideo'); ?>',
        'modal_no_properties'                 : '<?php esc_html_e('No properties found.', 'resideo'); ?>',
        'autoslide_label'                     : '<?php esc_html_e('Autoslide', 'resideo'); ?>',
        'autoslide_no'                        : '<?php esc_html_e('No', 'resideo'); ?>',
        'autoslide_yes'                       : '<?php esc_html_e('Yes', 'resideo'); ?>',
        'interval_label'                      : '<?php esc_html_e('Autoslide Interval', 'resideo'); ?>',
        'seconds_label'                       : '<?php esc_html_e('seconds', 'resideo'); ?>',
        'transition_label'                    : '<?php esc_html_e('Transition', 'resideo'); ?>',
        'margin_label'                        : '<?php esc_html_e('Margin', 'resideo'); ?>',
        'margin_no'                           : '<?php esc_html_e('No', 'resideo'); ?>',
        'margin_yes'                          : '<?php esc_html_e('Yes', 'resideo'); ?>',
        'transition_slide'                    : '<?php esc_html_e('Slide', 'resideo'); ?>',
        'transition_fade'                     : '<?php esc_html_e('Fade', 'resideo'); ?>',
        'opacity_label'                       : '<?php esc_html_e('Caption Background Opacity', 'resideo'); ?>',
        'width_label'                         : '<?php esc_html_e('Width', 'resideo'); ?>',
        'width_wide'                          : '<?php esc_html_e('Wide', 'resideo'); ?>',
        'width_boxed'                         : '<?php esc_html_e('Boxed', 'resideo'); ?>',
        'recent_posts_title'                  : '<?php esc_html_e('Recent Blog Posts', 'resideo'); ?>',
        'posts_number_label'                  : '<?php esc_html_e('Number of Posts', 'resideo'); ?>',
        'posts_number_placeholder'            : '<?php esc_html_e('Enter number of posts', 'resideo'); ?>',
        'featured_posts_title'                : '<?php esc_html_e('Featured Blog Posts', 'resideo'); ?>',
        'featured_agents_title'               : '<?php esc_html_e('Featured Agents', 'resideo'); ?>',
        'agents_number_label'                 : '<?php esc_html_e('Number of Agents', 'resideo'); ?>',
        'agents_number_placeholder'           : '<?php esc_html_e('Enter number of agents', 'resideo'); ?>',
        'testimonials_title'                  : '<?php esc_html_e('Testimonials', 'resideo'); ?>',
        'media_testimonials_image_title'      : '<?php esc_html_e('Testimonials Background Image', 'resideo'); ?>',
        'media_testimonials_image_btn'        : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'membership_plans_title'              : '<?php esc_html_e('Membership Plans', 'resideo'); ?>',
        'columns_title'                       : '<?php esc_html_e('Columns', 'resideo'); ?>',
        'contact_title'                       : '<?php esc_html_e('Contact', 'resideo'); ?>',
        'business_name_label'                 : '<?php esc_html_e('Business Name', 'resideo'); ?>',
        'business_name_placeholder'           : '<?php esc_html_e('Enter company/business name', 'resideo'); ?>',
        'phone_label'                         : '<?php esc_html_e('Phone Number', 'resideo'); ?>',
        'phone_placeholder'                   : '<?php esc_html_e('Enter phone number', 'resideo'); ?>',
        'address_label'                       : '<?php esc_html_e('Address', 'resideo'); ?>',
        'address_placeholder'                 : '<?php esc_html_e('Enter address', 'resideo'); ?>',
        'position_btn'                        : '<?php esc_html_e('Position pin by address', 'resideo'); ?>',
        'lat_label'                           : '<?php esc_html_e('Latitude', 'resideo'); ?>',
        'lat_placeholder'                     : '<?php esc_html_e('Enter latitude', 'resideo'); ?>',
        'lng_label'                           : '<?php esc_html_e('Longitude', 'resideo'); ?>',
        'lng_placeholder'                     : '<?php esc_html_e('Enter longitude', 'resideo'); ?>',
        'email_label'                         : '<?php esc_html_e('Email address', 'resideo'); ?>',
        'email_placeholder'                   : '<?php esc_html_e('Enter email address', 'resideo'); ?>',
        'form_label'                          : '<?php esc_html_e('Display Contact Form', 'resideo'); ?>',
        'form_no'                             : '<?php esc_html_e('No', 'resideo'); ?>',
        'form_yes'                            : '<?php esc_html_e('Yes', 'resideo'); ?>',
        'default_lat'                         : '<?php echo esc_html($default_lat); ?>',
        'default_lng'                         : '<?php echo esc_html($default_lng); ?>',
        'auto_country'                        : '<?php echo esc_html($auto_country); ?>',
        'geocode_error'                       : '<?php esc_html_e('Geocode was not successful for the following reason', 'resideo'); ?>',
        'marker_label'                        : '<?php esc_html_e('Marker', 'resideo'); ?>',
        'marker_title'                        : '<?php esc_html_e('Map Marker', 'resideo'); ?>',
        'marker_btn'                          : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'map_position_label'                  : '<?php esc_html_e('Map Position', 'resideo'); ?>',
        'map_position_right'                  : '<?php esc_html_e('Right', 'resideo'); ?>',
        'map_position_left'                   : '<?php esc_html_e('Left', 'resideo'); ?>',
        'align_label'                         : '<?php esc_html_e('Align', 'resideo'); ?>',
        'align_left'                          : '<?php esc_html_e('Left', 'resideo'); ?>',
        'align_center'                        : '<?php esc_html_e('Center', 'resideo'); ?>',
        'align_right'                         : '<?php esc_html_e('Right', 'resideo'); ?>',
        'promo_title'                         : '<?php esc_html_e('Promo', 'resideo'); ?>',
        'text_label'                          : '<?php esc_html_e('Text', 'resideo'); ?>',
        'text_placeholder'                    : '<?php esc_html_e('Enter text', 'resideo'); ?>',
        'caption_position_label'              : '<?php esc_html_e('Caption Position', 'resideo'); ?>',
        'image_position_label'                : '<?php esc_html_e('Image Position', 'resideo'); ?>',
        'right_label'                         : '<?php esc_html_e('Right', 'resideo'); ?>',
        'left_label'                          : '<?php esc_html_e('Left', 'resideo'); ?>',
        'media_promo_image_title'             : '<?php esc_html_e('Image', 'resideo'); ?>',
        'media_promo_image_btn'               : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'promo_img_label'                     : '<?php esc_html_e('Image', 'resideo'); ?>',
        'text_align_label'                    : '<?php esc_html_e('Text Align', 'resideo'); ?>',
        'layout_label'                        : '<?php esc_html_e('Layout', 'resideo'); ?>',
        'top_left_label'                      : '<?php esc_html_e('Top Left', 'resideo'); ?>',
        'top_right_label'                     : '<?php esc_html_e('Top Right', 'resideo'); ?>',
        'center_left_label'                   : '<?php esc_html_e('Center Left', 'resideo'); ?>',
        'center_label'                        : '<?php esc_html_e('Center', 'resideo'); ?>',
        'center_right_label'                  : '<?php esc_html_e('Center Right', 'resideo'); ?>',
        'bottom_left_label'                   : '<?php esc_html_e('Bottom Left', 'resideo'); ?>',
        'bottom_right_label'                  : '<?php esc_html_e('Bottom Right', 'resideo'); ?>',
        'subscribe_title'                     : '<?php esc_html_e('Subscribe', 'resideo'); ?>',
        'media_subscribe_image_title'         : '<?php esc_html_e('Subscribe Background Image', 'resideo'); ?>',
        'media_subscribe_image_btn'           : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'service_cta_color'                   : '<?php esc_html_e('Service CTA Color', 'resideo'); ?>',
        'cta_button_color'                    : '<?php esc_html_e('CTA Button Color', 'resideo'); ?>',
        'area_cta_color'                      : '<?php esc_html_e('Area CTA Color', 'resideo'); ?>',
        'agent_card_cta_color'                : '<?php esc_html_e('Agent Card CTA Color', 'resideo'); ?>',
        'plans_title_color'                   : '<?php esc_html_e('Plans Title Color', 'resideo'); ?>',
        'plans_price_color'                   : '<?php esc_html_e('Plans Price Color', 'resideo'); ?>',
        'plans_cta_color'                     : '<?php esc_html_e('Plans CTA Color', 'resideo'); ?>',
        'featured_plan_title_color'           : '<?php esc_html_e('Featured Plan Title Color', 'resideo'); ?>',
        'featured_plan_price_color'           : '<?php esc_html_e('Featured Plan Price Color', 'resideo'); ?>',
        'featured_plan_cta_color'             : '<?php esc_html_e('Featured Plan CTA Color', 'resideo'); ?>',
        'featured_plan_label_color'           : '<?php esc_html_e('Featured Plan Label Color', 'resideo'); ?>',
        'blog_post_card_cta_color'            : '<?php esc_html_e('Post Card CTA Color', 'resideo'); ?>',
        'gallery_carousel_title'              : '<?php esc_html_e('Gallery Carousel', 'resideo'); ?>',
        'gallery_carousel_photos'             : '<?php esc_html_e('Gallery Carousel Photos', 'resideo'); ?>',
        'add_gallery_carousel_photo_btn'      : '<?php esc_html_e('Add New Photo', 'resideo'); ?>',
        'new_gallery_carousel_photo_header'   : '<?php esc_html_e('New Photo', 'resideo'); ?>',
        'gallery_carousel_photo_add_img'      : '<?php esc_html_e('Add Photo', 'resideo'); ?>',
        'ok_gallery_carousel_photo_btn'       : '<?php esc_html_e('Add', 'resideo'); ?>',
        'cancel_gallery_carousel_photo_btn'   : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'edit_gallery_carousel_photo_header'  : '<?php esc_html_e('Edit Photo', 'resideo'); ?>',
        'ok_edit_gallery_carousel_photo_btn'  : '<?php esc_html_e('OK', 'resideo'); ?>',
        'gallery_carousel_photo'              : '<?php esc_html_e('Photo', 'resideo'); ?>',
        'gallery_carousel_insert_photo'       : '<?php esc_html_e('Insert Photo', 'resideo'); ?>',
        'numbers_title'                       : '<?php esc_html_e('Numbers', 'resideo'); ?>',
        'numbers_list'                        : '<?php esc_html_e('Numbers List', 'resideo'); ?>',
        'add_number_btn'                      : '<?php esc_html_e('Add New Number', 'resideo'); ?>',
        'new_number_header'                   : '<?php esc_html_e('New Number', 'resideo'); ?>',
        'number_sum_label'                    : '<?php esc_html_e('Number', 'resideo'); ?>',
        'number_sum_placeholder'              : '<?php esc_html_e('Enter number', 'resideo'); ?>',
        'number_sign_label'                   : '<?php esc_html_e('Number sign', 'resideo'); ?>',
        'number_sign_placeholder'             : '<?php esc_html_e('Enter number sign', 'resideo'); ?>',
        'number_delay_label'                  : '<?php esc_html_e('Number animation delay', 'resideo'); ?>',
        'number_delay_placeholder'            : '<?php esc_html_e('Enter number delay', 'resideo'); ?>',
        'number_increment_label'              : '<?php esc_html_e('Number increment', 'resideo'); ?>',
        'number_increment_placeholder'        : '<?php esc_html_e('Enter number increment', 'resideo'); ?>',
        'number_title_label'                  : '<?php esc_html_e('Number Title', 'resideo'); ?>',
        'number_title_placeholder'            : '<?php esc_html_e('Enter number title', 'resideo'); ?>',
        'number_text_label'                   : '<?php esc_html_e('Number Text', 'resideo'); ?>',
        'number_text_placeholder'             : '<?php esc_html_e('Enter number text', 'resideo'); ?>',
        'ok_number_btn'                       : '<?php esc_html_e('Add', 'resideo'); ?>',
        'cancel_number_btn'                   : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'media_numbers_image_title'           : '<?php esc_html_e('Numbers Background Image', 'resideo'); ?>',
        'media_numbers_image_btn'             : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'edit_number_header'                  : '<?php esc_html_e('Edit Number', 'resideo'); ?>',
        'ok_edit_number_btn'                  : '<?php esc_html_e('OK', 'resideo'); ?>',
        'cancel_number_btn'                   : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'awards_title'                        : '<?php esc_html_e('Awards', 'resideo'); ?>',
        'awards_list'                         : '<?php esc_html_e('Awards List', 'resideo'); ?>',
        'add_award_btn'                       : '<?php esc_html_e('Add New Award', 'resideo'); ?>',
        'new_award_header'                    : '<?php esc_html_e('New Award', 'resideo'); ?>',
        'award_add_img'                       : '<?php esc_html_e('Add Image', 'resideo'); ?>',
        'award_title_label'                   : '<?php esc_html_e('Award Title', 'resideo'); ?>',
        'award_title_placeholder'             : '<?php esc_html_e('Enter award title', 'resideo'); ?>',
        'ok_award_btn'                        : '<?php esc_html_e('Add', 'resideo'); ?>',
        'cancel_award_btn'                    : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'edit_award_header'                   : '<?php esc_html_e('Edit Award', 'resideo'); ?>',
        'ok_edit_award_btn'                   : '<?php esc_html_e('OK', 'resideo'); ?>',
        'cancel_award_btn'                    : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'award_image'                         : '<?php esc_html_e('Award Image', 'resideo'); ?>',
        'award_insert_image'                  : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'single_property_title'               : '<?php esc_html_e('Single Property', 'resideo'); ?>',
        'single_property_name_label'          : '<?php esc_html_e('Property Name', 'resideo'); ?>',
        'single_property_name_placeholder'    : '<?php esc_html_e('Search for a property...', 'resideo'); ?>',
        'single_property_image_position_label': '<?php esc_html_e('Image Position', 'resideo'); ?>',
        'hide_agents_phone'                   : '<?php echo esc_html($hide_agents_phone); ?>',
        'search_properties_title'             : '<?php esc_html_e('Search Properties', 'resideo'); ?>',
        'property_custom_fields'              : '<?php echo resideo_get_sh_property_custom_fields(); ?>',
        'slider_promo_title'                  : '<?php esc_html_e('Promo Slider', 'resideo'); ?>',
        'cta_buttons_color'                   : '<?php esc_html_e('CTA Buttons Color', 'resideo'); ?>',
        'promo_slides'                        : '<?php esc_html_e('Slides', 'resideo'); ?>',
        'add_promo_slide_btn'                 : '<?php esc_html_e('Add New Slide', 'resideo'); ?>',
        'new_promo_slide_header'              : '<?php esc_html_e('New Slide', 'resideo'); ?>',
        'promo_slide_add_img'                 : '<?php esc_html_e('Add Image', 'resideo'); ?>',
        'promo_slide_title_label'             : '<?php esc_html_e('Slide Title', 'resideo'); ?>',
        'promo_slide_title_placeholder'       : '<?php esc_html_e('Enter slide title', 'resideo'); ?>',
        'promo_slide_text_label'              : '<?php esc_html_e('Slide Text', 'resideo'); ?>',
        'promo_slide_text_placeholder'        : '<?php esc_html_e('Enter slide text', 'resideo'); ?>',
        'ok_promo_slide_btn'                  : '<?php esc_html_e('Add', 'resideo'); ?>',
        'cancel_promo_slide_btn'              : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'edit_slide_header'                   : '<?php esc_html_e('Edit Slide', 'resideo'); ?>',
        'ok_edit_promo_slide_btn'             : '<?php esc_html_e('OK', 'resideo'); ?>',
        'cancel_edit_promo_slide_btn'         : '<?php esc_html_e('Cancel', 'resideo'); ?>',
        'slide_image'                         : '<?php esc_html_e('Slide Image', 'resideo'); ?>',
        'slide_insert_image'                  : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'text_color_label'                    : '<?php esc_html_e('Text Color', 'resideo'); ?>',
        'cta_color_label'                     : '<?php esc_html_e('CTA Color', 'resideo'); ?>',
        'form_button_color'                   : '<?php esc_html_e('Form Button Color', 'resideo'); ?>',
        'form_position_label'                 : '<?php esc_html_e('Form Position', 'resideo'); ?>',
        'contact_img_label'                   : '<?php esc_html_e('Image', 'resideo'); ?>',
        'media_contact_image_title'           : '<?php esc_html_e('Image', 'resideo'); ?>',
        'media_contact_image_btn'             : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'form_title_label'                    : '<?php esc_html_e('Form Title', 'resideo'); ?>',
        'form_subtitle_label'                 : '<?php esc_html_e('Form Subtitle', 'resideo'); ?>',
        'form_email_label'                    : '<?php esc_html_e('Form Email', 'resideo'); ?>',
        'form_title_placeholder'              : '<?php esc_html_e('Enter form title', 'resideo'); ?>',
        'form_subtitle_placeholder'           : '<?php esc_html_e('Enter form subtitle', 'resideo'); ?>',
        'form_email_placeholder'              : '<?php esc_html_e('Enter form email', 'resideo'); ?>',
        'video_title'                         : '<?php esc_html_e('Video', 'resideo'); ?>',
        'media_video_image_title'             : '<?php esc_html_e('Video Background Image', 'resideo'); ?>',
        'media_video_image_btn'               : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'yt_video_id_label'                   : '<?php esc_html_e('Youtube video ID', 'resideo'); ?>',
        'caption_icon_label'                  : '<?php esc_html_e('Caption Icon', 'resideo'); ?>',
        'promo_caption_add_img'               : '<?php esc_html_e('Add Image', 'resideo'); ?>',
        'promo_caption_add_icon'              : '<?php esc_html_e('Add Icon', 'resideo'); ?>',
        'promo_caption_image'                 : '<?php esc_html_e('Caption Icon', 'resideo'); ?>',
        'promo_caption_insert_image'          : '<?php esc_html_e('Insert Image', 'resideo'); ?>',
        'promo_caption_icon_color'            : '<?php esc_html_e('Caption Icon Color', 'resideo'); ?>',
        'caption_title_label'                 : '<?php esc_html_e('Caption Title', 'resideo'); ?>',
        'caption_title_placeholder'           : '<?php esc_html_e('Enter caption title', 'resideo'); ?>',
        'caption_text_label'                  : '<?php esc_html_e('Caption Text', 'resideo'); ?>',
        'caption_text_placeholder'            : '<?php esc_html_e('Enter caption text', 'resideo'); ?>',
        'align_label'                         : '<?php esc_html_e('Align', 'resideo'); ?>',
        'display_label'                       : '<?php esc_html_e('Display', 'resideo'); ?>',
        'display_columns'                     : '<?php esc_html_e('Columns', 'resideo'); ?>',
        'display_grid'                        : '<?php esc_html_e('Grid', 'resideo'); ?>',
    };
    </script>
    <!-- TinyMCE Shortcode Plugin -->
<?php }

add_action('init', 'resideo_register_shortcodes');

if (!function_exists('resideo_get_types_statuses')): 
    function resideo_get_types_statuses() {
        $type_taxonomies = array( 
            'property_type'
        );
        $type_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $type_terms = get_terms($type_taxonomies, $type_args);

        $status_taxonomies = array( 
            'property_status'
        );
        $status_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $status_terms = get_terms($status_taxonomies, $status_args);

        echo json_encode(array('getts' => true, 'types' => $type_terms, 'statuses' => $status_terms));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_types_statuses', 'resideo_get_types_statuses');
add_action('wp_ajax_resideo_get_types_statuses', 'resideo_get_types_statuses');
?>