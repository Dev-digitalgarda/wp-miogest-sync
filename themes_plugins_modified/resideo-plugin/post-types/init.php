<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

require_once 'property.php';
require_once 'agent.php';
require_once 'lead.php';
require_once 'testimonial.php';
require_once 'membership.php';
require_once 'invoice.php';
require_once 'subscriber.php';
require_once 'page-meta.php';
require_once 'post-meta.php';

/**
 * Enqueue scripts for custom post types
 */
if (!function_exists('resideo_enqueue_custom_scripts')): 
    function resideo_enqueue_custom_scripts() {
        wp_register_style('datepicker', RESIDEO_PLUGIN_PATH . 'css/datepicker.css', array(), '1.0', 'all');

        if (is_rtl()) {
            wp_register_style('pxp-post-types-style', RESIDEO_PLUGIN_PATH . 'post-types/css/style-rtl.css', array(), '1.0', 'all');
        } else {
            wp_register_style('pxp-post-types-style', RESIDEO_PLUGIN_PATH . 'post-types/css/style.css', array(), '1.0', 'all');
        }

        $resideo_gmaps_settings = get_option('resideo_gmaps_settings', '');
        $gmaps_key = isset($resideo_gmaps_settings['resideo_gmaps_key_field']) ? $resideo_gmaps_settings['resideo_gmaps_key_field'] : '';

        if ($gmaps_key != '') {
            wp_register_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . $gmaps_key . '&amp;libraries=geometry&amp;libraries=places', array('jquery'), '1.0', true);
        }

        wp_register_script('datepicker', RESIDEO_PLUGIN_PATH . 'js/bootstrap-datepicker.js', array(), '1.0', true);
        wp_register_script('tooltip', RESIDEO_PLUGIN_PATH . 'post-types/js/tooltip.js', array(), '3.3.7', true);
        wp_register_script('pxp-post-types-js', RESIDEO_PLUGIN_PATH . 'post-types/js/post-types.js', array(), '1.0', 'all');

        wp_enqueue_style('datepicker');
        wp_enqueue_style('pxp-post-types-style');

        if ($gmaps_key != '') {
            wp_enqueue_script('gmaps');
            wp_enqueue_script('pxp-post-types-map', RESIDEO_PLUGIN_PATH . 'post-types/js/map.js', array(), '1.0', 'all');
        }

        wp_enqueue_script('datepicker');
        wp_enqueue_script('tooltip');
        wp_enqueue_script('pxp-post-types-js');

        $default_lat  = isset($resideo_gmaps_settings['resideo_gmaps_lat_field']) ? $resideo_gmaps_settings['resideo_gmaps_lat_field'] : '';
        $default_lng  = isset($resideo_gmaps_settings['resideo_gmaps_lng_field']) ? $resideo_gmaps_settings['resideo_gmaps_lng_field'] : '';

        $resideo_general_settings = get_option('resideo_general_settings', '');
        $auto_country = isset($resideo_general_settings['resideo_auto_country_field']) ? $resideo_general_settings['resideo_auto_country_field'] : '';
        $beds_label = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
        $baths_label = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
        $unit  = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : '';

        wp_localize_script('pxp-post-types-js', 'pt_vars', 
            array(
                'admin_url'                               => get_admin_url(),
                'ajaxurl'                                 => admin_url('admin-ajax.php'),
                'plugin_url'                              => RESIDEO_PLUGIN_PATH,
                'default_lat'                             => $default_lat,
                'default_lng'                             => $default_lng,
                'auto_country'                            => $auto_country,
                'beds_label'                              => $beds_label,
                'baths_label'                             => $baths_label,
                'unit'                                    => $unit,
                'geocode_error'                           => __('Geocode was not successful for the following reason', 'resideo'),
                'gallery_title'                           => __('Property Gallery', 'resideo'),
                'gallery_btn'                             => __('Insert Photos', 'resideo'),
                'floor_plan_title'                        => __('Floor Plan', 'resideo'),
                'floor_plan_btn'                          => __('Insert Plan', 'resideo'),
                'avatar_title'                            => __('Agent Avatar', 'resideo'),
                'icon_title'                              => __('Icon', 'resideo'),
                'icon_btn'                                => __('Insert Image', 'resideo'),
                'avatar_btn'                              => __('Insert Photo', 'resideo'),
                'slideshow_title'                         => __('Slideshow Gallery', 'resideo'),
                'slideshow_btn'                           => __('Insert Photos', 'resideo'),
                'slider_title'                            => __('Slider Gallery', 'resideo'),
                'slider_btn'                              => __('Insert Photo', 'resideo'),
                'slider_error'                            => __('Please choose a photo for the slide', 'resideo'),
                'img_placeholder'                         => RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png',
                'slider_caption_title'                    => __('Enter the caption title', 'resideo'),
                'slider_caption_subtitle'                 => __('Enter the caption subtitle', 'resideo'),
                'slider_cta_text'                         => __('Enter the CTA button text', 'resideo'),
                'slider_cta_link'                         => __('Enter the CTA button link', 'resideo'),
                'slider_edit_ok'                          => __('OK', 'resideo'),
                'slider_empty'                            => __('Sliders list is empty.', 'resideo'),
                'modal_add'                               => __('Add', 'resideo'),
                'modal_cancel'                            => __('Cancel', 'resideo'),
                'modal_properties'                        => __('Properties', 'resideo'),
                'load_more_properties'                    => __('Load 20 more properties', 'resideo'),
                'modal_no_properties'                     => __('No properties found.', 'resideo'),
                'search_properties'                       => __('Search properties', 'resideo'),
                'modal_properties_results'                => __('Properties', 'resideo'),
                'p_slider_empty'                          => __('Properties list is empty.', 'resideo'),
                'marker_title'                            => __('Map Marker', 'resideo'),
                'marker_btn'                              => __('Insert Image', 'resideo'),
                'header_image_title'                      => __('Header Image', 'resideo'),
                'header_image_btn'                        => __('Insert Image', 'resideo'),
                'testimonial_avatar_title'                => __('Testimonial Avatar', 'resideo'),
                'cp_header_image_title'                   => __('Contact Page Header Image', 'resideo'),
                'cp_header_image_btn'                     => __('Insert Image', 'resideo'),
                'header_video_cover_title'                => __('Header Video Cover', 'resideo'),
                'header_video_cover_btn'                  => __('Insert Cover', 'resideo'),
                'offices_empty'                           => __('Offices list is empty.', 'resideo'),
                'offices_error'                           => __('Please choose a title for the office', 'resideo'),
                'contact_form_header_image_title'         => __('Background Image', 'resideo'),
                'contact_form_header_image_btn'           => __('Insert Image', 'resideo'),
                'edit_floor_plan'                         => __('Edit Floor Plan', 'resideo'),
                'edit_floor_plan_title_label'             => __('Title', 'resideo'),
                'edit_floor_plan_title_placeholder'       => __('Enter plan title', 'resideo'),
                'edit_floor_plan_beds_label'              => __('Beds', 'resideo'),
                'edit_floor_plan_beds_placeholder'        => __('Enter plan number of beds', 'resideo'),
                'edit_floor_plan_baths_label'             => __('Baths', 'resideo'),
                'edit_floor_plan_baths_placeholder'       => __('Enter plan number of baths', 'resideo'),
                'edit_floor_plan_size_label'              => __('Size', 'resideo'),
                'edit_floor_plan_baths_placeholder'       => __('Enter plan size', 'resideo'),
                'edit_floor_plan_description_label'       => __('Description', 'resideo'),
                'edit_floor_plan_description_placeholder' => __('Enter plan description here...', 'resideo'),
                'edit_floor_plan_image_label'             => __('Image', 'resideo'),
                'edit_floor_plan_ok_btn'                  => __('Ok', 'resideo'),
                'edit_floor_plan_cancel_btn'              => __('Cancel', 'resideo'),
            )
        );

        wp_localize_script('pxp-post-types-map', 'ptm_vars', 
            array(
                'plugin_url'               => RESIDEO_PLUGIN_PATH,
                'default_lat'              => $default_lat,
                'default_lng'              => $default_lng,
                'auto_country'             => $auto_country,
                'geocode_error'            => __('Geocode was not successful for the following reason', 'resideo'),
            )
        );
    }
endif;
add_action('admin_enqueue_scripts', 'resideo_enqueue_custom_scripts');
?>