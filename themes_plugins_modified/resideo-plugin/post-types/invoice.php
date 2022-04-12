<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Register invoice custom post type
 */
if (!function_exists('resideo_register_invoice_type')): 
    function resideo_register_invoice_type() {
        register_post_type('invoice', array(
            'labels' => array(
                'name'                  => __('Invoices','resideo'),
                'singular_name'         => __('Invoice','resideo'),
                'add_new'               => __('Add New Invoice','resideo'),
                'add_new_item'          => __('Add Invoice','resideo'),
                'edit'                  => __('Edit','resideo'),
                'edit_item'             => __('Edit Invoice','resideo'),
                'new_item'              => __('New Invoice','resideo'),
                'view'                  => __('View','resideo'),
                'view_item'             => __('View Invoice','resideo'),
                'search_items'          => __('Search Invoices','resideo'),
                'not_found'             => __('No Invoices found','resideo'),
                'not_found_in_trash'    => __('No Invoices found in Trash','resideo'),
                'parent'                => __('Parent Invoice', 'resideo'),
            ),
            'public'                => true,
            'exclude_from_search '  => true,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('invoices', 'URL SLUG', 'resideo')),
            'supports'              => array('title'),
            'can_export'            => true,
            'register_meta_box_cb'  => 'resideo_add_invoice_metaboxes',
            'menu_icon'             => 'dashicons-media-spreadsheet'
        ));
    }
endif;
add_action('init', 'resideo_register_invoice_type');

if (!function_exists('resideo_add_invoice_metaboxes')): 
    function resideo_add_invoice_metaboxes() {
        add_meta_box('invoice-details-section', __('Details', 'resideo'), 'resideo_invoice_details_render', 'invoice', 'normal', 'default');
    }
endif;

if (!function_exists('resideo_invoice_details_render')): 
    function resideo_invoice_details_render($post) {
        wp_nonce_field('resideo_invoice', 'invoice_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label style="font-weight: bold;">' . __('Invoice ID', 'resideo') . ': ' . $post->ID . '</label> 
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label for="invoice_item_type">' . __('Item Type', 'resideo') . '</label><br />';
                            print resideo_item_types(esc_html(get_post_meta($post->ID, 'invoice_item_type', true)));
                            print '
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label for="invoice_item_id">' . __('Item ID', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="invoice_item_id" name="invoice_item_id" placeholder="' . __('Enter Item ID', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'invoice_item_id', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label for="invoice_item_price">' . __('Item Price', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="invoice_item_price" name="invoice_item_price" placeholder="' . __('Enter Item Price', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'invoice_item_price', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label for="invoice_agent_id">' . __('Agent ID', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="invoice_agent_id" name="invoice_agent_id" placeholder="' . __('Enter Agent ID', 'resideo') . '" value="' . esc_attr(get_post_meta($post->ID, 'invoice_agent_id', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('resideo_invoice_meta_save')): 
    function resideo_invoice_meta_save($post_id) {
        $is_autosave    = wp_is_post_autosave($post_id);
        $is_revision    = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['invoice_noncename']) && wp_verify_nonce($_POST['invoice_noncename'], 'resideo_invoice')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['invoice_item_type'])) {
            update_post_meta($post_id, 'invoice_item_type', sanitize_text_field($_POST['invoice_item_type']));
        }
        if (isset($_POST['invoice_item_id'])) {
            update_post_meta($post_id, 'invoice_item_id', sanitize_text_field($_POST['invoice_item_id']));
        }
        if (isset($_POST['invoice_item_price'])) {
            update_post_meta($post_id, 'invoice_item_price', sanitize_text_field($_POST['invoice_item_price']));
        }
        if (isset($_POST['invoice_agent_id'])) {
            update_post_meta($post_id, 'invoice_agent_id', sanitize_text_field($_POST['invoice_agent_id']));
        }
    }
endif;
add_action('save_post', 'resideo_invoice_meta_save');

if (!function_exists('resideo_item_types')): 
    function resideo_item_types($selected) {
        $types = array('Standard Listing', 'Listing Upgraded to Featured', 'Featured Listing', 'Membership Plan');

        $type_select = '<select id="invoice_item_type" name="invoice_item_type">';

        foreach ($types as $type) {
            $type_select .= '<option value="' . esc_attr($type) . '"';

            if ($selected == $type) {
                $type_select .= 'selected="selected"';
            }

            $type_select .= '>' . esc_html($type) . '</option>';
        }

        $type_select.='</select>';

        return $type_select;
    }
endif;

if (!function_exists('resideo_insert_invoice')):
    function resideo_insert_invoice($item_type, $item_id, $agent_id, $is_featured, $is_upgrade) {
        $post = array(
            'post_type'   => 'invoice', 
            'post_status' => 'publish',
        );

        $post_id = wp_insert_post($post);

        $membership_settings       = get_option('resideo_membership_settings');
        $submission_price          = isset($membership_settings['resideo_submission_price_field']) ? floatval($membership_settings['resideo_submission_price_field']) : 0;
        $featured_submission_price = isset($membership_settings['resideo_featured_price_field']) ? floatval($membership_settings['resideo_featured_price_field']) : 0;

        if ($item_type == 'Membership Plan') {
            $price = get_post_meta($item_id, 'membership_plan_price', true);
        } else {
            if ($is_upgrade == 1) {
                $price = $featured_submission_price;
            } else {
                if ($is_featured == 1) {
                    $price = $submission_price + $featured_submission_price;
                } else {
                    $price = $submission_price;
                }
            }
        }

        update_post_meta($post_id, 'invoice_item_type', $item_type);
        update_post_meta($post_id, 'invoice_item_id', $item_id);
        update_post_meta($post_id, 'invoice_item_price', $price);
        update_post_meta($post_id, 'invoice_agent_id', $agent_id);

        $new_post = array(
           'ID'         => $post_id,
           'post_title' => 'Invoice ' . $post_id,
        );

        wp_update_post($new_post);
    }
endif;

/**
 * Add custom columns in invoices list
 */
if (!function_exists('resideo_invoices_columns')): 
    function resideo_invoices_columns($columns) {
        $date  = $columns['date'];

        unset($columns['date']);

        $columns['invoice_type']  = __('Item Type', 'resideo');
        $columns['invoice_price'] = __('Price', 'resideo');
        $columns['invoice_agent'] = __('Purchased By', 'resideo');
        $columns['date']          = $date;

        return $columns;
    }
endif;
add_filter('manage_invoice_posts_columns', 'resideo_invoices_columns');

if (!function_exists('resideo_invoices_custom_column')): 
    function resideo_invoices_custom_column($column, $post_id) {
        switch ($column) {
            case 'invoice_type':
                $type = get_post_meta($post_id, 'invoice_item_type', true);

                echo esc_html($type);

                break;
            case 'invoice_price':
                $price = get_post_meta($post_id, 'invoice_item_price', true);

                echo esc_html($price);

                break;
            case 'invoice_agent':
                $agent_id   = get_post_meta($post_id, 'invoice_agent_id', true);
                $agent_name = get_the_title($agent_id);

                echo esc_html($agent_name);

                break;
        }
    }
endif;
add_action('manage_invoice_posts_custom_column', 'resideo_invoices_custom_column', 10, 2);

if (!function_exists('resideo_invoices_sortable_columns')): 
    function resideo_invoices_sortable_columns($columns) {
        $columns['invoice_type']  = 'invoice_type';
        $columns['invoice_price'] = 'invoice_price';
        $columns['invoice_agent'] = 'invoice_agent';

        return $columns;
    }
endif;
add_filter('manage_edit-invoice_sortable_columns', 'resideo_invoices_sortable_columns');

if (!function_exists('resideo_invoices_custom_orderby')): 
    function resideo_invoices_custom_orderby($query) {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('invoice_type' == $orderby) {
            $query->set('meta_key', 'invoice_item_type');
            $query->set('orderby', 'meta_value');
        }

        if ('invoice_price' == $orderby) {
            $query->set('meta_key', 'invoice_item_price');
            $query->set('orderby', 'meta_value_no');
        }

        if ('invoice_agent' == $orderby) {
            $query->set('meta_key', 'invoice_agent_id');
            $query->set('orderby', 'meta_value_no');
        }
    }
endif;
add_action('pre_get_posts', 'resideo_invoices_custom_orderby');
?>