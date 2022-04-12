<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register agent custom post type
 */
if (!function_exists('resideo_register_agent_type')): 
    function resideo_register_agent_type() {
        register_post_type('agent', array(
            'labels' => array(
                'name'               => __('Agents/Owners', 'resideo'),
                'singular_name'      => __('Agent/Owner', 'resideo'),
                'add_new'            => __('Add New Agent/Owner', 'resideo'),
                'add_new_item'       => __('Add Agent/Owner', 'resideo'),
                'edit'               => __('Edit', 'resideo'),
                'edit_item'          => __('Edit Agent/Owner', 'resideo'),
                'new_item'           => __('New Agent/Owner', 'resideo'),
                'view'               => __('View', 'resideo'),
                'view_item'          => __('View Agent/Owner', 'resideo'),
                'search_items'       => __('Search Agents/Owners', 'resideo'),
                'not_found'          => __('No Agents/Owners found', 'resideo'),
                'not_found_in_trash' => __('No Agents/Owners found in Trash', 'resideo'),
                'parent'             => __('Parent Agent/Owner', 'resideo'),
            ),
            'public'                => true,
            'exclude_from_search '  => true,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('agents', 'URL SLUG', 'resideo')),
            'supports'              => array('title', 'editor', 'comments', 'resideo'),
            'can_export'            => true,
            'register_meta_box_cb'  => 'resideo_add_agent_metaboxes',
            'menu_icon'             => 'dashicons-businessman',
        ));
    }
endif;
add_action('init', 'resideo_register_agent_type');

if (!function_exists('resideo_add_agent_metaboxes')): 
    function resideo_add_agent_metaboxes() {
        add_meta_box('agent-details-section', __('Details', 'resideo'), 'resideo_agent_details_render', 'agent', 'normal', 'default');
        add_meta_box('agent-payment-section', __('Membership and Payment', 'resideo'), 'resideo_agent_payment_render', 'agent', 'normal', 'default');
        add_meta_box('agent-avatar-section', __('Avatar', 'resideo'), 'resideo_agent_avatar_render', 'agent', 'normal', 'default');
        add_meta_box('agent-user-section', __('User', 'resideo'), 'resideo_agent_user_render', 'agent', 'normal', 'default');
        add_meta_box('agent-type-section', __('Type', 'resideo'), 'resideo_agent_type_render', 'agent', 'side', 'default');
        add_meta_box('agent-featured-section', __('Featured', 'resideo'), 'resideo_agent_featured_render', 'agent', 'side', 'default');
    }
endif;

if (!function_exists('resideo_agent_details_render')): 
    function resideo_agent_details_render($post) {
        wp_nonce_field('resideo_agent', 'agent_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_title">' . __('Title', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_title" name="agent_title" placeholder="' . __('Enter title', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_title', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_specs">' . __('Specialities (separate by comma)', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_specs" name="agent_specs" placeholder="' . __('Enter specialities', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_specs', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="agent_email">' . __('Email', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_email" name="agent_email" placeholder="' . __('Enter email', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_email', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="agent_phone">' . __('Phone', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_phone" name="agent_phone" placeholder="' . __('Enter phone', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_phone', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="agent_skype">' . __('Skype', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_skype" name="agent_skype" placeholder="' . __('Enter Skype ID', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_skype', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_facebook">' . __('Facebook', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_facebook" name="agent_facebook" placeholder="' . __('Enter Facebook profile URL', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_facebook', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_twitter">' . __('Twitter', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_twitter" name="agent_twitter" placeholder="' . __('Enter Twitter profile URL', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_twitter', true)) . '" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_pinterest">' . __('Pinterest', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_pinterest" name="agent_pinterest" placeholder="' . __('Enter Pinterest profile URL', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_pinterest', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_linkedin">' . __('LinkedIn', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_linkedin" name="agent_linkedin" placeholder="' . __('Enter LinkedIn profile URL', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_linkedin', true)) . '" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <div class="adminField">
                            <label for="agent_instagram">' . __('Instagram', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agent_instagram" name="agent_instagram" placeholder="' . __('Enter Instagram profile URL', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'agent_instagram', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_agent_payment_render')): 
    function resideo_agent_payment_render($post) {
        wp_nonce_field('resideo_agent', 'agent_noncename');

        $membership_settings = get_option('resideo_membership_settings');
        $pay_type            = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';

        if($pay_type == 'listing' || $pay_type == 'membership') {

            print '<input type="hidden" name="agent_payment" value="">
                   <input type="checkbox" name="agent_payment" value="1" ';
            if (esc_html(get_post_meta($post->ID, 'agent_payment', true)) == 1) {
                print ' checked ';
            }
            print ' /> <label for="agent_payment">' . __('Allow the agent to post properties regardless of payment method', 'resideo') . '</label>';
        } else {
            print '<i>' . __('Payment type is disabled.', 'resideo') . '</i>';
        }
    }
endif;

if (!function_exists('resideo_agent_user_render')): 
    function resideo_agent_user_render($post) {
        wp_nonce_field('resideo_agent', 'agent_noncename');

        $mypost        = $post->ID;
        $originalpost  = $post;
        $selected_user = esc_html(get_post_meta($mypost, 'agent_user', true));
        $users_list    = '';
        $args          = array('role' => '');

        $user_query = new WP_User_Query($args);

        foreach($user_query->results as $user) {
            $users_list .= '<option value="' . $user->ID . '"';
            if ($user->ID == $selected_user) {
                $users_list .= ' selected';
            }
            $users_list .= '>' . $user->user_login . ' - ' . $user->first_name . ' ' . $user->last_name . '</option>';
        }

        wp_reset_query();

        $post = $originalpost;

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%" valign="top">
                        <div class="adminField">
                            <label for="agent_user">' . __('Assign a User', 'resideo') . '</label><br />
                            <select id="agent_user" name="agent_user">
                                <option value="">' . __('None', 'resideo') . '</option>
                                ' . $users_list . '
                            </select>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_agent_avatar_render')): 
    function resideo_agent_avatar_render($post) {
        wp_nonce_field('resideo_agent', 'agent_noncename');

        $avatar_src = wp_get_attachment_image_src(get_post_meta($post->ID, 'agent_avatar', true), 'pxp-agent');

        print '
            <div class="adminField">
                <input type="hidden" id="agent_avatar" name="agent_avatar" value="' . esc_attr(get_post_meta($post->ID, 'agent_avatar', true)) . '">
                <div class="avatar-placeholder-container';

        if($avatar_src !== false) { 
            echo ' has-image'; 
        }

        print '">';
        print '<div id="avatar-image-placeholder" style="background-image: url(';

        if($avatar_src !== false) { 
            echo esc_url($avatar_src[0]); 
        } else { 
            echo esc_url(RESIDEO_PLUGIN_PATH . 'post-types/images/avatar-placeholder.png');
        }

        print ');"></div>
                <div id="delete-avatar-image"><span class="fa fa-trash-o"></span></div>
            </div></div>
        ';
    }
endif;

if (!function_exists('resideo_agent_type_render')): 
    function resideo_agent_type_render($post) {
        wp_nonce_field('resideo_agent', 'agent_noncename');

        $selected_type = esc_html(get_post_meta($post->ID, 'agent_type', true));

        if($selected_type == 'owner') {
            $type_list = '<option value="agent">' . __('Agent', 'resideo') . '</option>
                            <option value="owner" selected>' . __('Owner', 'resideo') . '</option>';
        } else {
            $type_list = '<option value="agent" selected>' . __('Agent', 'resideo') . '</option>
                            <option value="owner">' . __('Owner', 'resideo') . '</option>';
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <select id="agent_type" name="agent_type">
                                ' . $type_list . '
                            </select>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_agent_featured_render')): 
    function resideo_agent_featured_render($post) {
        wp_nonce_field('resideo_agent', 'agent_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="agent_featured" value="">
                            <input type="checkbox" name="agent_featured" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'agent_featured', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="agent_featured">' . __('Set as Featured', 'resideo') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_agent_meta_save')): 
    function resideo_agent_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['agent_noncename']) && wp_verify_nonce($_POST['agent_noncename'], 'resideo_agent')) ? 'true' : 'false';

        if($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if(isset($_POST['agent_title'])) {
            update_post_meta($post_id, 'agent_title', sanitize_text_field($_POST['agent_title']));
        }
        if(isset($_POST['agent_specs'])) {
            update_post_meta($post_id, 'agent_specs', sanitize_text_field($_POST['agent_specs']));
        }
        if(isset($_POST['agent_email'])) {
            update_post_meta($post_id, 'agent_email', sanitize_text_field($_POST['agent_email']));
        }
        if(isset($_POST['agent_phone'])) {
            update_post_meta($post_id, 'agent_phone', sanitize_text_field($_POST['agent_phone']));
        }
        if(isset($_POST['agent_skype'])) {
            update_post_meta($post_id, 'agent_skype', sanitize_text_field($_POST['agent_skype']));
        }
        if(isset($_POST['agent_facebook'])) {
            update_post_meta($post_id, 'agent_facebook', sanitize_text_field($_POST['agent_facebook']));
        }
        if(isset($_POST['agent_twitter'])) {
            update_post_meta($post_id, 'agent_twitter', sanitize_text_field($_POST['agent_twitter']));
        }
        if(isset($_POST['agent_pinterest'])) {
            update_post_meta($post_id, 'agent_pinterest', sanitize_text_field($_POST['agent_pinterest']));
        }
        if(isset($_POST['agent_linkedin'])) {
            update_post_meta($post_id, 'agent_linkedin', sanitize_text_field($_POST['agent_linkedin']));
        }
        if(isset($_POST['agent_instagram'])) {
            update_post_meta($post_id, 'agent_instagram', sanitize_text_field($_POST['agent_instagram']));
        }
        if(isset($_POST['agent_user'])) {
            update_post_meta($post_id, 'agent_user', sanitize_text_field($_POST['agent_user']));
        }
        if(isset($_POST['agent_avatar'])) {
            update_post_meta($post_id, 'agent_avatar', sanitize_text_field($_POST['agent_avatar']));
        }
        if(isset($_POST['agent_type'])) {
            update_post_meta($post_id, 'agent_type', sanitize_text_field($_POST['agent_type']));
        }
        if(isset($_POST['agent_featured'])) {
            update_post_meta($post_id, 'agent_featured', sanitize_text_field($_POST['agent_featured']));
        }
        if(isset($_POST['agent_payment'])) {
            update_post_meta($post_id, 'agent_payment', sanitize_text_field($_POST['agent_payment']));
        }
    }
endif;
add_action('save_post', 'resideo_agent_meta_save');

if (!function_exists('resideo_change_agent_default_title')): 
    function resideo_change_agent_default_title($title) {
        $screen = get_current_screen();

        if('agent' == $screen->post_type) {
            $title = __('Enter agent/owner name here', 'resideo');
        }

        return $title;
    }
endif;
add_filter('enter_title_here', 'resideo_change_agent_default_title');

/**
 * Add custom columns in agents list
 */
if (!function_exists('resideo_agents_columns')): 
    function resideo_agents_columns($columns) {
        $date  = $columns['date'];

        unset($columns['comments']);
        unset($columns['date']);

        $columns['photo']          = __('Photo', 'resideo');
        $columns['agent_type']     = __('Type', 'resideo');
        $columns['agent_email']    = __('Email', 'resideo');
        $columns['agent_phone']    = __('Phone', 'resideo');
        $columns['agent_plan']     = __('Membership Plan', 'resideo');
        $columns['featured_agent'] = __('Featured', 'resideo');
        $columns['date']           = $date;

        return $columns;
    }
endif;
add_filter('manage_agent_posts_columns', 'resideo_agents_columns');

if (!function_exists('resideo_agents_custom_column')): 
    function resideo_agents_custom_column($column, $post_id) {
        switch ($column) {
            case 'photo':
                $avatar       = get_post_meta($post_id, 'agent_avatar', true);
                $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');

                if ($avatar_photo != '') {
                    $photo = $avatar_photo[0];
                } else {
                    $photo = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                }

                echo '<img src="' . esc_attr($photo) . '" style="width: 60px; height: 60px">';

                break;
            case 'agent_type':
                $type = get_post_meta($post_id, 'agent_type', true);

                if ($type == 'agent') {
                    echo __('Agent', 'resideo');
                } 

                if ($type == 'owner') {
                    echo __('Owner', 'resideo');
                }

                break;
            case 'agent_email':
                $email = get_post_meta($post_id, 'agent_email', true);

                echo esc_html($email);

                break;
            case 'agent_phone':
                $phone = get_post_meta($post_id, 'agent_phone', true);

                echo esc_html($phone);

                break;
            case 'agent_plan':
                $plan_id = get_post_meta($post_id, 'agent_plan', true);

                if ($plan_id && $plan_id != '') {
                    $plan = get_the_title($plan_id);

                    echo esc_html($plan);
                } else {
                    echo '&mdash;';
                }

                break;
            case 'featured_agent':
                $featured = get_post_meta($post_id, 'agent_featured', true);

                if ($featured == '1') {
                    echo __('Yes', 'resideo');
                } else {
                    echo __('No', 'resideo');
                }
                break;
        }
    }
endif;
add_action('manage_agent_posts_custom_column', 'resideo_agents_custom_column', 10, 2);

if (!function_exists('resideo_agents_sortable_columns')): 
    function resideo_agents_sortable_columns($columns) {
        $columns['agent_type']     = 'agent_type';
        $columns['featured_agent'] = 'featured_agent';

        return $columns;
    }
endif;
add_filter('manage_edit-agent_sortable_columns', 'resideo_agents_sortable_columns');

if (!function_exists('resideo_agents_custom_orderby')): 
    function resideo_agents_custom_orderby($query) {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('featured_agent' == $orderby) {
            $query->set('meta_key', 'agent_featured');
            $query->set('orderby', 'meta_value');
        }

        if ('agent_type' == $orderby) {
            $query->set('meta_key', 'agent_type');
            $query->set('orderby', 'meta_value');
        }
    }
endif;
add_action('pre_get_posts', 'resideo_agents_custom_orderby');
?>