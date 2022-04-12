<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register lead custom post type
 */
if (!function_exists('resideo_register_lead_type')): 
    function resideo_register_lead_type() {
        register_post_type('lead', array(
            'labels' => array(
                'name'               => __('Leads', 'resideo'),
                'singular_name'      => __('Lead', 'resideo'),
                'add_new'            => __('Add New Lead', 'resideo'),
                'add_new_item'       => __('Add Lead', 'resideo'),
                'edit'               => __('Edit', 'resideo'),
                'edit_item'          => __('Edit Lead', 'resideo'),
                'new_item'           => __('New Lead', 'resideo'),
                'view'               => __('View', 'resideo'),
                'view_item'          => __('View lead', 'resideo'),
                'search_items'       => __('Search Leads', 'resideo'),
                'not_found'          => __('No Leads found', 'resideo'),
                'not_found_in_trash' => __('No Leads found in Trash', 'resideo'),
                'parent'             => __('Parent Leads', 'resideo'),
            ),
            'public'                => true,
            'exclude_from_search '  => true,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('leads', 'URL SLUG', 'resideo')),
            'supports'              => array('title'),
            'can_export'            => true,
            'register_meta_box_cb'  => 'resideo_add_lead_metaboxes',
            'menu_icon'             => 'dashicons-book',
        ));
    }
endif;
add_action('init', 'resideo_register_lead_type');

if (!function_exists('resideo_add_lead_metaboxes')): 
    function resideo_add_lead_metaboxes() {
        add_meta_box('lead-details-section', __('Details', 'resideo'), 'resideo_lead_details_render', 'lead', 'normal', 'default');
        add_meta_box('lead-messages-section', __('Messages', 'resideo'), 'resideo_lead_messages_render', 'lead', 'normal', 'default');
        add_meta_box('lead-wishlist-section', __('Wish List', 'resideo'), 'resideo_lead_wishlist_render', 'lead', 'normal', 'default');
        add_meta_box('lead-searches-section', __('Saved Searches', 'resideo'), 'resideo_lead_searches_render', 'lead', 'normal', 'default');
        add_meta_box('lead-notes-section', __('Notes', 'resideo'), 'resideo_lead_notes_render', 'lead', 'normal', 'default');
        add_meta_box('lead-agent-section', __('Lead Owner', 'resideo'), 'resideo_lead_agent_render', 'lead', 'side', 'default');
        add_meta_box('lead-user-section', __('Lead User', 'resideo'), 'resideo_lead_user_render', 'lead', 'side', 'default');
    }
endif;

if (!function_exists('resideo_lead_details_render')): 
    function resideo_lead_details_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        $selected_contacted = get_post_meta($post->ID, 'lead_contacted', true);
        $selected_score     = get_post_meta($post->ID, 'lead_score', true);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="adminField">
                            <label for="lead_email">' . __('Email', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="lead_email" name="lead_email" placeholder="' . __('Enter email', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'lead_email', true)) . '" />
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="adminField">
                            <label for="lead_phone">' . __('Phone', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="lead_phone" name="lead_phone" placeholder="' . __('Enter phone', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'lead_phone', true)) . '" />
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="adminField">
                            <label for="lead_email">' . __('Contacted', 'resideo') . '</label><br />
                            <select id="lead_contacted" name="lead_contacted" class="formInput">
                                <option value="no" '; selected($selected_contacted, 'no'); print '>' . __('No', 'resideo') . '</option>
                                <option value="yes" '; selected( $selected_contacted, 'yes' ); print '>' . __('Yes', 'resideo') . '</option>
                            </select>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="adminField">
                            <label for="lead_email">' . __('Score', 'resideo') . '</label><br />
                            <select id="lead_score" name="lead_score" class="formInput">
                                <option value="0" '; selected($selected_score, '0'); print '>' . __('None', 'resideo') . '</option>
                                <option value="1" '; selected($selected_score, '1'); print '>' . __('Fit', 'resideo') . '</option>
                                <option value="2" '; selected($selected_score, '2'); print '>' . __('Ready', 'resideo') . '</option>
                                <option value="3" '; selected($selected_score, '3'); print '>' . __('Engaged', 'resideo') . '</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_lead_messages_render')):
    function resideo_lead_messages_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        $messages = get_post_meta($post->ID, 'lead_messages', true);

        if (is_array($messages) && count($messages) > 0) {
            print '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="lead-message-table">';

            for ($i = 0; $i < count($messages); $i++) { 
                print   '<tr>
                            <td width="20%" valign="top"><i>' . esc_html($messages[$i]['date']) . '</i></td>
                            <td width="80%" valign="top">' . esc_html($messages[$i]['message']);
                                if(isset($messages[$i]['prop_link']) && $messages[$i]['prop_link'] != '' && isset($messages[$i]['prop_title']) && $messages[$i]['prop_title'] != '') {
                                    print '<br /><br />' . __('Related Property', 'resideo') . ': <a href="' . esc_url($messages[$i]['prop_link']) . '" target="_blank">' . esc_html($messages[$i]['prop_title']) . '</a>';
                                }
                            print '</td>
                        </tr>';
            }

            print '</table>';
        } else {
            esc_html_e('No messages found.', 'resideo');
        }
    }
endif;

if (!function_exists('resideo_lead_wishlist_render')): 
    function resideo_lead_wishlist_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        $user_id = get_post_meta($post->ID, 'lead_user', true);

        if ($user_id != '') {
            $wl_posts = resideo_get_wishlist($user_id, true);

            if ($wl_posts) {
                $total_p = $wl_posts->found_posts;
            } else {
                $total_p = 0;
            }

            if ($total_p != 0) {
                $fields_settings   = get_option('resideo_prop_fields_settings');
                $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
                $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
                $neighborhoods     = get_option('resideo_neighborhoods_settings');
                $cities            = get_option('resideo_cities_settings');

                $general_settings = get_option('resideo_general_settings');
                $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
                $currency         = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
                $currency_pos     = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
                $locale           = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
                $decimals         = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
                setlocale(LC_MONETARY, $locale);

                print '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="lead-wl-table">';

                while ($wl_posts->have_posts()) {
                    $wl_posts->the_post();

                    $prop_id = get_the_ID();
                    $link    = get_permalink($prop_id);
                    $title   = get_the_title($prop_id);

                    $gallery     = get_post_meta($prop_id, 'property_gallery', true);
                    $photos      = explode(',', $gallery);
                    $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-agent');

                    if ($first_photo != '') {
                        $photo = $first_photo[0];
                    } else {
                        $photo = RESIDEO_PLUGIN_PATH . 'images/property-tile.png';
                    }

                    $type   = wp_get_post_terms($prop_id, 'property_type');
                    $status = wp_get_post_terms($prop_id, 'property_status');

                    $price       = get_post_meta($prop_id, 'property_price', true);
                    $price_label = get_post_meta($prop_id, 'property_price_label', true);

                    $currency_str = $currency;

                    if (is_numeric($price)) {
                        if ($decimals == '1') {
                            $price = money_format('%!i', $price);
                        } else {
                            $price = money_format('%!.0i', $price);
                        }
                    } else {
                        $price_label = '';
                        $currency_str = '';
                    }

                    $address_arr  = array();
                    $address      = '';
                    $street_no    = get_post_meta($prop_id, 'street_number', true);
                    $street       = get_post_meta($prop_id, 'route', true);
                    $neighborhood = get_post_meta($prop_id, 'neighborhood', true);
                    $city         = get_post_meta($prop_id, 'locality', true);
                    $state        = get_post_meta($prop_id, 'administrative_area_level_1', true);
                    $zip          = get_post_meta($prop_id, 'postal_code', true);

                    $neighborhood_value = resideo_get_field_value($neighborhood_type, $neighborhood, $neighborhoods);
                    $city_value         = resideo_get_field_value($city_type, $city, $cities);

                    $address_settings = get_option('resideo_address_settings');

                    if (is_array($address_settings)) {
                        uasort($address_settings, "resideo_compare_position");

                        $address_default = array(
                            'street_number' => $street_no,
                            'street'        => $street,
                            'neighborhood'  => $neighborhood_value,
                            'city'          => $city_value,
                            'state'         => $state,
                            'zip'           => $zip
                        );

                        foreach ($address_settings as $key => $value) {
                            if ($address_default[$key] != '') {
                                array_push($address_arr, $address_default[$key]);
                            }
                        }
                    } else {
                        if ($street_no != '') array_push($address_arr, $street_no);
                        if ($street != '') array_push($address_arr, $street);
                        if ($neighborhood_value != '') array_push($address_arr, $neighborhood_value);
                        if ($city_value != '') array_push($address_arr, $city_value);
                        if ($state != '') array_push($address_arr, $state);
                        if ($zip != '') array_push($address_arr, $zip);
                    }

                    if (count($address_arr) > 0) $address = implode(', ', $address_arr);

                    $beds  = get_post_meta($prop_id, 'property_beds', true);
                    $baths = get_post_meta($prop_id, 'property_baths', true);
                    $size  = get_post_meta($prop_id, 'property_size', true);

                    $feat = '';

                    if ($beds != '') {
                        $feat .= $beds . ' ' . __('Beds', 'resideo') . ', ';
                    }
                    if ($baths != '') {
                        $feat .= $baths . ' ' . __('Baths', 'resideo') . ', ';
                    }
                    if ($size != '') {
                        $feat .= $size . ' ' . $unit;
                    }

                    print  '<tr>
                                <td width="45%" valign="top">
                                    <div class="lead-wl-thumb" style="background-image: url(' . esc_url($photo) . ')"></div>
                                    <div class="lead-wl-ta">
                                        <a href="' . esc_url($link) . '" target="_blank" class="lead-wl-title">' . esc_html($title) . '</a>
                                        <div class="lead-wl-address">' . esc_html($address) . '</div>
                                    </div>
                                </td>
                                <td width="10%" valign="top">';
                                    if ($type) {
                                        print esc_html($type[0]->name);
                                    }
                                print '</td>
                                <td width="20%" valign="top">' . esc_html($feat) . '</td>
                                <td width="10%" valign="top">';
                                    if ($status) {
                                        print esc_html($status[0]->name);
                                    }
                                print '</td>
                                <td width="15%" valign="top" align="right" class="rtl-align-left">';
                                    if ($currency_pos == 'before') {
                                        print '<strong>' . esc_html($currency_str) . esc_html($price) . '</strong> <span>' . esc_html($price_label) . '</span>';
                                    } else {
                                        print '<strong>' . esc_html($price) . esc_html($currency_str) . '</strong> <span>' . esc_html($price_label) . '</span>';
                                    }
                                print '</td>
                            </tr>';
                }

                print '</table>';
            } else {
                esc_html_e('Wish list empty.', 'resideo');
            }
        } else {
            esc_html_e('Wish list empty.', 'resideo');
        }
    }
endif;

if (!function_exists('resideo_lead_searches_render')): 
    function resideo_lead_searches_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        $user_id = get_post_meta($post->ID, 'lead_user', true);

        if ($user_id != '') {
            $searches = get_user_meta($user_id, 'user_search', true);

            if (is_array($searches) && count($searches) > 0) {
                print   '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="lead-searches-table">';

                foreach ($searches as $search) {
                    print   '<tr>
                                <td width="70%" valign="top">
                                    <a href="' . esc_url($search['url']) . '" target="_blank">' . esc_html($search['name']) . '</a>
                                </td>
                                <td width="30%" valign="top" align="right" class="rtl-align-left">'. esc_html($search['date']) .'</td>
                            </tr>';
                }

                print '</table>';
            } else {
                esc_html_e('Saved searches list empty.', 'resideo');
            }
        } else {
            esc_html_e('Saved searches list empty.', 'resideo');
        }
    }
endif;

if (!function_exists('resideo_lead_notes_render')): 
    function resideo_lead_notes_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <textarea id="lead_notes" name="lead_notes" placeholder="' . __('Enter your notes here...', 'resideo') . '" style="width: 100%; height: 160px;">' . esc_html(get_post_meta($post->ID, 'lead_notes', true)) . '</textarea>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_lead_agent_render')): 
    function resideo_lead_agent_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        $agent_list = '';
        $selected_agent = esc_html(get_post_meta($post->ID, 'lead_agent', true));

        $args = array(
            'post_type'      => 'agent',
            'post_status'    => 'publish',
            'posts_per_page' => -1
        );

        $agent_selection      = new WP_Query($args);
        $agent_selection_arr  = get_object_vars($agent_selection);

        if (is_array($agent_selection_arr['posts']) && count($agent_selection_arr['posts']) > 0) {
            foreach ($agent_selection_arr['posts'] as $agent) {
                $agent_list .= '<option value="' . esc_attr($agent->ID) . '"';

                if ($agent->ID == $selected_agent) {
                    $agent_list .= ' selected';
                }

                $agent_list .= '>' . $agent->post_title . '</option>';
            }
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div class="adminField">
                            <label for="lead_agent">' . __('Agent', 'resideo') . '</label><br />
                            <select id="lead_agent" name="lead_agent">
                                <option value="">' . __('None', 'resideo') . '</option>
                                ' . $agent_list . '
                            </select>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_lead_user_render')): 
    function resideo_lead_user_render($post) {
        wp_nonce_field('resideo_lead', 'lead_noncename');

        $mypost        = $post->ID;
        $originalpost  = $post;
        $selected_user = esc_html(get_post_meta($mypost, 'lead_user', true));
        $users_list    = '';
        $args          = array('role' => '');

        $user_query = new WP_User_Query($args);

        foreach ($user_query->results as $user) {
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
                    <td width="100%" valign="top">
                        <div class="adminField">
                            <label for="lead_user">' . __('User', 'resideo') . '</label><br />
                            <select id="lead_user" name="lead_user">
                                <option value="">' . __('None', 'resideo') . '</option>
                                ' . $users_list . '
                            </select>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_lead_meta_save')): 
    function resideo_lead_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['lead_noncename']) && wp_verify_nonce($_POST['lead_noncename'], 'resideo_lead')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['lead_email'])) {
            update_post_meta($post_id, 'lead_email', sanitize_text_field($_POST['lead_email']));
        }
        if (isset($_POST['lead_phone'])) {
            update_post_meta($post_id, 'lead_phone', sanitize_text_field($_POST['lead_phone']));
        }
        if (isset($_POST['lead_contacted'])) {
            update_post_meta($post_id, 'lead_contacted', sanitize_text_field($_POST['lead_contacted']));
        }
        if (isset($_POST['lead_score'])) {
            update_post_meta($post_id, 'lead_score', sanitize_text_field($_POST['lead_score']));
        }
        if (isset($_POST['lead_notes'])) {
            update_post_meta($post_id, 'lead_notes', sanitize_text_field($_POST['lead_notes']));
        }
        if (isset($_POST['lead_agent'])) {
            update_post_meta($post_id, 'lead_agent', sanitize_text_field($_POST['lead_agent']));
        }
        if (isset($_POST['lead_user'])) {
            update_post_meta($post_id, 'lead_user', sanitize_text_field($_POST['lead_user']));
        }
    }
endif;
add_action('save_post', 'resideo_lead_meta_save');

if (!function_exists('resideo_change_lead_default_title')): 
    function resideo_change_lead_default_title($title) {
        $screen = get_current_screen();

        if ('lead' == $screen->post_type) {
            $title = __('Enter lead name here', 'resideo');
        }

        return $title;
    }
endif;
add_filter('enter_title_here', 'resideo_change_lead_default_title');

/**
 * Add custom columns in leads list
 */
if (!function_exists('resideo_leads_columns')): 
    function resideo_leads_columns($columns) {
        $date = $columns['date'];

        unset($columns['date']);

        $columns['email']     = __('Email', 'resideo');
        $columns['phone']     = __('Phone', 'resideo');
        $columns['contacted'] = __('Contacted', 'resideo');
        $columns['score']     = __('Score', 'resideo');
        $columns['agent']     = __('Agent', 'resideo');
        $columns['date']      = $date;

        return $columns;
    }
endif;
add_filter('manage_lead_posts_columns', 'resideo_leads_columns');

if (!function_exists('resideo_leads_custom_column')): 
    function resideo_leads_custom_column($column, $post_id) {
        switch ($column) {
            case 'email':
                $email = get_post_meta($post_id, 'lead_email', true);

                echo esc_html($email);
                break;
            case 'phone':
                $phone = get_post_meta($post_id, 'lead_phone', true);

                echo esc_html($phone);
                break;
            case 'contacted':
                $contacted = get_post_meta($post_id, 'lead_contacted', true);

                if ($contacted == 'yes') {
                    echo __('Yes', 'resideo');
                } else {
                    echo __('No', 'resideo');
                }
                break;
            case 'score':
                $score = get_post_meta($post_id, 'lead_score', true);

                if ($score == '1') {
                    echo __('Fit', 'resideo');
                } else if ($score == '2') {
                    echo __('Ready', 'resideo');
                } else if ($score == '3') {
                    echo __('Engaged', 'resideo');
                } else {
                    echo __('None', 'resideo');
                }
                break;
            case 'agent':
                $agent      = get_post_meta($post_id, 'lead_agent', true);
                $agent_name = get_the_title($agent);

                echo esc_html($agent_name);
                break;
        }
    }
endif;
add_action('manage_lead_posts_custom_column', 'resideo_leads_custom_column', 10, 2);

if (!function_exists('resideo_leads_sortable_columns')): 
    function resideo_leads_sortable_columns($columns) {
        $columns['contacted'] = 'contacted';
        $columns['score']     = 'score';
        $columns['agent']     = 'agent';

        return $columns;
    }
endif;
add_filter('manage_edit-lead_sortable_columns', 'resideo_leads_sortable_columns');

function resideo_leads_custom_orderby($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('contacted' == $orderby) {
        $query->set('meta_key', 'lead_contacted');
        $query->set('orderby', 'meta_value');
    }

    if ('score' == $orderby) {
        $query->set('meta_key', 'lead_score');
        $query->set('orderby', 'meta_value_num');
    }

    if ('agent' == $orderby) {
        $query->set('meta_key', 'lead_agent');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'resideo_leads_custom_orderby');
?>