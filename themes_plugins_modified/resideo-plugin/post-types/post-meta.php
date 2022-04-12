<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Custom featured metabox in posts
 */
if (!function_exists('resideo_add_post_metaboxes')): 
    function resideo_add_post_metaboxes() {
        add_meta_box('post-featured-section', __('Featured', 'resideo'), 'resideo_post_featured_render', 'post', 'side', 'default');
    }
endif;
add_action('add_meta_boxes', 'resideo_add_post_metaboxes');

if (!function_exists('resideo_post_featured_render')): 
    function resideo_post_featured_render($post) {
        wp_nonce_field(plugin_basename(__FILE__), 'post_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if(isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <p class="meta-options">
                            <input type="hidden" name="post_featured" value="">
                            <input type="checkbox" name="post_featured" value="1" ';
                                if (isset($post_id) && esc_html(get_post_meta($post_id, 'post_featured', true)) == 1) {
                                    print ' checked ';
                                }
                            print ' />
                            <label for="post_featured">' . __('Set as Featured', 'resideo') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_post_meta_save')): 
    function resideo_post_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['post_noncename']) && wp_verify_nonce($_POST['post_noncename'], basename(__FILE__))) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['post_featured'])) {
            update_post_meta($post_id, 'post_featured', sanitize_text_field($_POST['post_featured']));
        }
    }
endif;
add_action('save_post', 'resideo_post_meta_save');
?>