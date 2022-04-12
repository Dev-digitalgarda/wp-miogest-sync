<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register membership custom post type
 */
if (!function_exists('resideo_register_membership_type')): 
    function resideo_register_membership_type() {
        register_post_type('membership', array(
            'labels' => array(
                'name'               => __('Membership Plans', 'resideo'),
                'singular_name'      => __('Membership Plan', 'resideo'),
                'add_new'            => __('Add New Membership Plan', 'resideo'),
                'add_new_item'       => __('Add Membership Plan', 'resideo'),
                'edit'               => __('Edit', 'resideo'),
                'edit_item'          => __('Edit Membership Plan', 'resideo'),
                'new_item'           => __('New Membership Plan', 'resideo'),
                'view'               => __('View', 'resideo'),
                'view_item'          => __('View Membership Plan', 'resideo'),
                'search_items'       => __('Search Membership Plans', 'resideo'),
                'not_found'          => __('No Membership Plans found', 'resideo'),
                'not_found_in_trash' => __('No Membership Plans found in Trash', 'resideo'),
                'parent'             => __('Parent Membership Plan', 'resideo'),
            ),
            'public'               => true,
            'exclude_from_search ' => true,
            'has_archive'          => true,
            'rewrite'              => array('slug' => _x('membership_plans', 'URL SLUG', 'resideo')),
            'supports'             => array('title'),
            'can_export'           => true,
            'register_meta_box_cb' => 'resideo_add_membership_metaboxes',
            'menu_icon'            => 'dashicons-id'
        ));
    }
endif;
add_action('init', 'resideo_register_membership_type');

/**
 * Add membership post type metaboxes
 */
if (!function_exists('resideo_add_membership_metaboxes')): 
    function resideo_add_membership_metaboxes() {
        add_meta_box('membership-plan-features-section', __('Membership Plan Features', 'resideo'), 'resideo_membership_plan_features_render', 'membership', 'normal', 'default');
        add_meta_box('membership-plan-icon-section', __('Icon', 'resideo'), 'resideo_membership_plan_icon_render', 'membership', 'normal', 'default');
    }
endif;

if (!function_exists('resideo_membership_plan_features_render')): 
    function resideo_membership_plan_features_render($post) {
        wp_nonce_field('resideo_membership', 'membership_noncename');

        $selected_unit = get_post_meta($post->ID, 'membership_billing_time_unit', true);
        $resideo_membership_settings = get_option('resideo_membership_settings');
        $currency = isset($resideo_membership_settings['resideo_payment_currency_field']) ? $resideo_membership_settings['resideo_payment_currency_field'] : '';

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="adminField">
                            <label for="membership_period">' . __('Membership Plan Period', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="membership_period" name="membership_period" placeholder="' . __('Enter number of...', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_period', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <div class="adminField">
                            <label for="membership_billing_time_unit">&nbsp;</label><br />
                            <select id="membership_billing_time_unit" name="membership_billing_time_unit">
                                <option value="day" ';
                                selected( $selected_unit, 'day' );
                                print '>' . __('Days', 'resideo') . '</option>
                                <option value="week" ';
                                selected( $selected_unit, 'week' );
                                print '>' . __('Weeks', 'resideo') . '</option>
                                <option value="month" ';
                                selected( $selected_unit, 'month' );
                                print '>' . __('Months', 'resideo') . '</option>
                                <option value="year" ';
                                selected( $selected_unit, 'year' );
                                print '>' . __('Years', 'resideo') . '</option>
                            </select>
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="adminField">
                            <label for="membership_submissions_no">' . __('Number of Submissions', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="membership_submissions_no" name="membership_submissions_no" placeholder="' . __('Enter the number of submissions', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_submissions_no', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options" style="padding-top: 15px;"> 
                            <input type="hidden" name="membership_unlim_submissions" value="">
                            <input type="checkbox" name="membership_unlim_submissions" value="1" ';
                            if (get_post_meta($post->ID, 'membership_unlim_submissions', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_unlim_submissions">' . __('Unlimited Submissions', 'resideo') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="adminField">
                            <label for="membership_featured_submissions_no">' . __('Number of Featured Submissions', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="membership_featured_submissions_no" name="membership_featured_submissions_no" placeholder="' . __('Enter the number of featured submissions', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_featured_submissions_no', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="adminField">
                            <label for="membership_plan_price">' . __('Plan Price', 'resideo') . ' (' . esc_html($currency) . ')</label><br />
                            <input type="text" class="formInput" id="membership_plan_price" name="membership_plan_price" placeholder="' . __('Enter the plan price', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_plan_price', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options" style="padding-top: 15px;"> 
                            <input type="hidden" name="membership_free_plan" value="">
                            <input type="checkbox" name="membership_free_plan" value="1" ';
                            if (get_post_meta($post->ID, 'membership_free_plan', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_free_plan">' . __('Free Plan', 'resideo') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options" style="padding-top: 15px;"> 
                            <input type="hidden" name="membership_plan_popular" value="">
                            <input type="checkbox" name="membership_plan_popular" value="1" ';
                            if (get_post_meta($post->ID, 'membership_plan_popular', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_free_plan">' . __('Popular/Recommended Plan', 'resideo') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <div class="adminField">
                            <label for="membership_plan_popular_label">' . __('Popular/Recommended Plan Label', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="membership_plan_popular_label" name="membership_plan_popular_label" placeholder="' . __('Enter the label text', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_plan_popular_label', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_membership_plan_icon_render')): 
    function resideo_membership_plan_icon_render($post) {
        wp_nonce_field('resideo_membership', 'agent_noncename');

        $icon_src = wp_get_attachment_image_src(get_post_meta($post->ID, 'membership_icon', true), 'pxp-icon');

        print '
            <div class="adminField">
                <input type="hidden" id="membership_icon" name="membership_icon" value="' . esc_attr(get_post_meta($post->ID, 'membership_icon', true)) . '">
                <div class="icon-placeholder-container';

        if($icon_src !== false) { 
            echo ' has-image'; 
        }

        print '">';
        print '<div id="icon-image-placeholder" style="background-image: url(';

        if($icon_src !== false) { 
            echo esc_url($icon_src[0]); 
        } else { 
            echo esc_url(RESIDEO_PLUGIN_PATH . 'post-types/images/icon-placeholder.png');
        }

        print ');"></div>
                <div id="delete-icon-image"><span class="fa fa-trash-o"></span></div>
            </div></div>
        ';
    }
endif;

if (!function_exists('resideo_membership_meta_save')): 
    function resideo_membership_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['membership_noncename']) && wp_verify_nonce($_POST['membership_noncename'], 'resideo_membership')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['membership_billing_time_unit'])) {
            update_post_meta($post_id, 'membership_billing_time_unit', sanitize_text_field($_POST['membership_billing_time_unit']));
        }
        if (isset($_POST['membership_period'])) {
            update_post_meta($post_id, 'membership_period', sanitize_text_field($_POST['membership_period']));
        }
        if (isset($_POST['membership_submissions_no'])) {
            update_post_meta($post_id, 'membership_submissions_no', sanitize_text_field($_POST['membership_submissions_no']));
        }
        if (isset($_POST['membership_unlim_submissions'])) {
            update_post_meta($post_id, 'membership_unlim_submissions', sanitize_text_field($_POST['membership_unlim_submissions']));
        }
        if (isset($_POST['membership_featured_submissions_no'])) {
            update_post_meta($post_id, 'membership_featured_submissions_no', sanitize_text_field($_POST['membership_featured_submissions_no']));
        }
        if (isset($_POST['membership_plan_price'])) {
            update_post_meta($post_id, 'membership_plan_price', sanitize_text_field($_POST['membership_plan_price']));
        }
        if (isset($_POST['membership_free_plan'])) {
            update_post_meta($post_id, 'membership_free_plan', sanitize_text_field($_POST['membership_free_plan']));
        }
        if (isset($_POST['membership_plan_popular'])) {
            update_post_meta($post_id, 'membership_plan_popular', sanitize_text_field($_POST['membership_plan_popular']));
        }
        if (isset($_POST['membership_plan_popular_label'])) {
            update_post_meta($post_id, 'membership_plan_popular_label', sanitize_text_field($_POST['membership_plan_popular_label']));
        }
        if (isset($_POST['membership_icon'])) {
            update_post_meta($post_id, 'membership_icon', sanitize_text_field($_POST['membership_icon']));
        }
    }
endif;
add_action('save_post', 'resideo_membership_meta_save');

if (!function_exists('resideo_change_membership_default_title')): 
    function resideo_change_membership_default_title($title){
        $screen = get_current_screen();

        if ('membership' == $screen->post_type) {
            $title = __('Enter membership plan title here', 'resideo');
        }
        return $title;
    }
endif;
add_filter('enter_title_here', 'resideo_change_membership_default_title');
?>