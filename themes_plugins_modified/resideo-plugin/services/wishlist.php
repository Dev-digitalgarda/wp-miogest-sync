<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Save property to wish list
 */
if (!function_exists('resideo_add_to_wishlist')): 
    function resideo_add_to_wishlist() {
        check_ajax_referer('wishlist_ajax_nonce', 'security');

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';

        $wishlist_key = 'property_wishlist';
        $wishlist = get_user_meta($user_id, $wishlist_key, true);

        if (empty($wishlist)) {
            $wishlist = array();

            delete_user_meta($user_id, $wishlist_key);
            add_user_meta($user_id, $wishlist_key, $wishlist);
        }

        if (in_array($post_id, $wishlist) === false) {
            array_push($wishlist, $post_id);
            update_user_meta($user_id, $wishlist_key, $wishlist);
        }

        echo json_encode(array('saved'=>true, 'wishlist'=>$wishlist));

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_add_to_wishlist', 'resideo_add_to_wishlist');
add_action('wp_ajax_resideo_add_to_wishlist', 'resideo_add_to_wishlist');

/**
 * Remove property from wish list
 */
if (!function_exists('resideo_remove_from_wishlist')): 
    function resideo_remove_from_wishlist() {
        check_ajax_referer('wishlist_ajax_nonce', 'security');

        $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';

        $wishlist_key = 'property_wishlist';
        $wishlist = get_user_meta($user_id, $wishlist_key, true);

        if (in_array($post_id, $wishlist)) {
            $wishlist = array_diff($wishlist, array($post_id));
            update_user_meta($user_id, $wishlist_key, $wishlist);
        }

        echo json_encode(array('removed' => true, 'wishlist' => $wishlist));

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_remove_from_wishlist', 'resideo_remove_from_wishlist');
add_action('wp_ajax_resideo_remove_from_wishlist', 'resideo_remove_from_wishlist');

/**
 * Get user properties from whish list 
 */
if (!function_exists('resideo_get_wishlist')): 
    function resideo_get_wishlist($user_id, $all = false) {
        $appearance_settings    = get_option('resideo_appearance_settings');
        $posts_per_page_setting = isset($appearance_settings['resideo_properties_per_page_field']) ? $appearance_settings['resideo_properties_per_page_field'] : '';

        if ($all === false) {
            $posts_per_page = $posts_per_page_setting != '' ? $posts_per_page_setting : 10;
        } else {
            $posts_per_page = -1;
        }

        $wishlist = get_user_meta($user_id, 'property_wishlist', true);

        if ($wishlist && $wishlist != '') {
            global $paged;

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $args = array(
                'post__in'            => $wishlist,
                'posts_per_page'      => $posts_per_page,
                'paged'               => $paged,
                'post_type'           => 'property',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true
            );

            $query = new WP_Query($args);

            wp_reset_query();
            wp_reset_postdata();

            return $query;
        } else {
            return false;
        }
    }
endif;

/**
 * Get number of wishes per property
 */
if (!function_exists('resideo_get_wishlist_count')): 
    function resideo_get_favourites_count($prop_id) {
        $users = get_users();
        $favs  = 0;

        foreach ($users as $user) {
            $user_fav = get_user_meta($user->data->ID, 'property_wishlist', true);

            if (is_array($user_fav) && in_array($prop_id, $user_fav)) {
                $favs = $favs + 1;
            }
        }

        return $favs;
    }
endif;

/**
 * Get wish list page URL
 */
if (!function_exists('resideo_get_wishlist_url')):
    function resideo_get_wishlist_url() {
        $pages = get_pages(array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'wish-list.php'
        ));

        if ($pages) {
            $wishlist_url = get_permalink($pages[0]->ID);
        } else {
            $wishlist_url = home_url();
        }

        return $wishlist_url;
    }
endif;
?>