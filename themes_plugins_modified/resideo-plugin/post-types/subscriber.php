<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register subscriber custom post type
 */
if (!function_exists('resideo_register_subscriber_type')): 
    function resideo_register_subscriber_type() {
        register_post_type('subscriber', array(
            'labels' => array(
                'name'               => __('Subscribers', 'resideo'),
                'singular_name'      => __('Subscriber', 'resideo'),
                'add_new'            => __('Add New Subscriber', 'resideo'),
                'add_new_item'       => __('Add Subscriber', 'resideo'),
                'edit'               => __('Edit', 'resideo'),
                'edit_item'          => __('Edit Subscriber', 'resideo'),
                'new_item'           => __('New Subscriber', 'resideo'),
                'view'               => __('View', 'resideo'),
                'view_item'          => __('View Subscriber', 'resideo'),
                'search_items'       => __('Search Subscribers', 'resideo'),
                'not_found'          => __('No Subscribers found', 'resideo'),
                'not_found_in_trash' => __('No Subscribers found in Trash', 'resideo'),
                'parent'             => __('Parent Subscriber', 'resideo'),
            ),
            'public'               => true,
            'exclude_from_search ' => true,
            'has_archive'          => true,
            'rewrite'              => array('slug' => _x('subscribers', 'URL SLUG', 'resideo')),
            'supports'             => array('title'),
            'can_export'           => true,
            'register_meta_box_cb' => 'resideo_add_subscriber_metaboxes',
            'menu_icon'            => 'dashicons-groups',
        ));
    }
endif;
add_action('init', 'resideo_register_subscriber_type');

if (!function_exists('resideo_add_subscriber_metaboxes')): 
    function resideo_add_subscriber_metaboxes() {
        add_meta_box('subscriber-notes-section', __('Notes', 'resideo'), 'resideo_subscriber_notes_render', 'subscriber', 'normal', 'default');
    }
endif;

if (!function_exists('resideo_subscriber_notes_render')): 
    function resideo_subscriber_notes_render($post) {
        wp_nonce_field('resideo_subscriber', 'subscriber_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <p class="meta-options">
                            <textarea id="subscriber_notes" name="subscriber_notes" placeholder="' . __('Enter notes about subscriber', 'resideo') . '" style="width: 100%; height: 160px;">' . esc_html(get_post_meta($post->ID, 'subscriber_notes', true)) . '</textarea>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_subscriber_meta_save')): 
    function resideo_subscriber_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['subscriber_noncename']) && wp_verify_nonce($_POST['subscriber_noncename'], 'resideo_subscriber')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['subscriber_notes'])) {
            update_post_meta($post_id, 'subscriber_notes', sanitize_text_field($_POST['subscriber_notes']));
        }
    }
endif;
add_action('save_post', 'resideo_subscriber_meta_save');

if (!function_exists('resideo_change_subscriber_default_title')): 
    function resideo_change_subscriber_default_title($title) {
        $screen = get_current_screen();

        if ('subscriber' == $screen->post_type) {
            $title = __('Enter subscriber email address here', 'resideo');
        }
        return $title;
    }
endif;
add_filter('enter_title_here', 'resideo_change_subscriber_default_title');
?>