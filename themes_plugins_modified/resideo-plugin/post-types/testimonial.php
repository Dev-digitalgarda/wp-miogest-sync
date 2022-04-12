<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register testimonial custom post type
 */
if (!function_exists('resideo_register_testimonial_type')): 
    function resideo_register_testimonial_type() {
        register_post_type('testimonial', array(
            'labels' => array(
                'name'               => __('Testimonials', 'resideo'),
                'singular_name'      => __('Testimonial', 'resideo'),
                'add_new'            => __('Add New Testimonial', 'resideo'),
                'add_new_item'       => __('Add Testimonial', 'resideo'),
                'edit'               => __('Edit', 'resideo'),
                'edit_item'          => __('Edit Testimonial', 'resideo'),
                'new_item'           => __('New Testimonial', 'resideo'),
                'view'               => __('View', 'resideo'),
                'view_item'          => __('View Testimonial', 'resideo'),
                'search_items'       => __('Search Testimonials', 'resideo'),
                'not_found'          => __('No Testimonials found', 'resideo'),
                'not_found_in_trash' => __('No Testimonials found in Trash', 'resideo'),
                'parent'             => __('Parent Testimonial', 'resideo'),
            ),
            'public'               => true,
            'exclude_from_search ' => true,
            'has_archive'          => true,
            'rewrite'              => array('slug' => _x('testimonials', 'URL SLUG', 'resideo')),
            'supports'             => array('title'),
            'can_export'           => true,
            'register_meta_box_cb' => 'resideo_add_testimonial_metaboxes',
            'menu_icon'            => 'dashicons-format-quote',
        ));
    }
endif;
add_action('init', 'resideo_register_testimonial_type');

if (!function_exists('resideo_add_testimonial_metaboxes')): 
    function resideo_add_testimonial_metaboxes() {
        add_meta_box('testimonial-text-section', __('What the customer says', 'resideo'), 'resideo_testimonial_text_render', 'testimonial', 'normal', 'default');
        add_meta_box('testimonial-location-section', __('Customer Location', 'resideo'), 'resideo_testimonial_location_render', 'testimonial', 'normal', 'default');
        add_meta_box('testimonial-avatar-section', __('Avatar', 'resideo'), 'resideo_testimonial_avatar_render', 'testimonial', 'normal', 'default');
    }
endif;

if (!function_exists('resideo_testimonial_text_render')): 
    function resideo_testimonial_text_render($post) {
        wp_nonce_field('resideo_testimonial', 'testimonial_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <p class="meta-options">
                            <textarea id="testimonial_text" name="testimonial_text" placeholder="' . __('Enter what the customer says here', 'resideo') . '" style="width: 100%; height: 160px;">' . esc_html(get_post_meta($post->ID, 'testimonial_text', true)) . '</textarea>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_testimonial_location_render')): 
    function resideo_testimonial_location_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <div class="adminField">
                            <label for="testimonial_location">' . __('Location', 'resideo') . '</label><br>
                            <input type="text" class="formInput" id="testimonial_location" name="testimonial_location" placeholder="' . __('Enter customer location', 'resideo') . '" value="' . esc_html(get_post_meta($post->ID, 'testimonial_location', true)) . '">
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_testimonial_avatar_render')): 
    function resideo_testimonial_avatar_render($post) {
        $avatar_src = wp_get_attachment_image_src(get_post_meta($post->ID, 'testimonial_avatar', true), 'pxp-agent');

        print '
            <div class="adminField">
                <input type="hidden" id="testimonial_avatar" name="testimonial_avatar" value="' . esc_attr(get_post_meta($post->ID, 'testimonial_avatar', true)) . '">
                <div class="testimonial-avatar-placeholder-container';
                    if ($avatar_src !== false) { 
                        echo ' has-image'; 
                    }
                    print '">
                    <div id="testimonial-avatar-image-placeholder" style="background-image: url(';
                        if ($avatar_src !== false) { 
                            echo esc_url($avatar_src[0]);
                        } else { 
                            echo esc_url(RESIDEO_PLUGIN_PATH . 'post-types/images/avatar-placeholder.png');
                        }
                        print ');">
                    </div>
                    <div id="delete-testimonial-avatar-image"><span class="fa fa-trash-o"></span></div>
                </div>
            </div>
        ';
    }
endif;

if (!function_exists('resideo_testimonial_meta_save')): 
    function resideo_testimonial_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['testimonial_noncename']) && wp_verify_nonce($_POST['testimonial_noncename'], 'resideo_testimonial')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['testimonial_text'])) {
            update_post_meta($post_id, 'testimonial_text', sanitize_text_field($_POST['testimonial_text']));
        }
        if (isset($_POST['testimonial_location'])) {
            update_post_meta($post_id, 'testimonial_location', sanitize_text_field($_POST['testimonial_location']));
        }
        if (isset($_POST['testimonial_avatar'])) {
            update_post_meta($post_id, 'testimonial_avatar', sanitize_text_field($_POST['testimonial_avatar']));
        }
    }
endif;
add_action('save_post', 'resideo_testimonial_meta_save');

if (!function_exists('resideo_change_testimonial_default_title')): 
    function resideo_change_testimonial_default_title($title) {
        $screen = get_current_screen();

        if ('testimonial' == $screen->post_type) {
            $title = __('Enter customer name here', 'resideo');
        }
        return $title;
    }
endif;
add_filter('enter_title_here', 'resideo_change_testimonial_default_title');
?>