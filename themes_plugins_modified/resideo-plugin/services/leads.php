<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Get leads number per period
 */
if (!function_exists('resideo_get_leads_number')): 
    function resideo_get_leads_number() {
        global $wpdb;

        check_ajax_referer('leads_ajax_nonce', 'security');

        $period   = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '-7 days';
        $agent_id = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';

        switch ($period) {
            case '-7 days':
                $period_prev = '-14 days';
                break;
            case '-30 days':
                $period_prev = '-60 days';
                break;
            case '-60 days':
                $period_prev = '-120 days';
                break;
            case '-90 days':
                $period_prev = '-180 days';
                break;
            case '-12 months':
                $period_prev = '-24 months';
                break;
            default:
                $period_prev = '-14 days';
                break;
        }

        // Select leads from current period
        $start_date  = date('Y-m-d', strtotime($period));
        $today       = date('Y-m-d');

        $qry =  "SELECT count(*) as count, DATE_FORMAT(post_date, '%b %d') as date 
                FROM wp_posts INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1=1  AND ( 
                ( wp_posts.post_date > '$start_date' AND wp_posts.post_date < '$today' ) 
                ) AND ( 
                ( wp_postmeta.meta_key = 'lead_agent' AND wp_postmeta.meta_value = '$agent_id' ) 
                ) AND wp_posts.post_type = 'lead' AND ((wp_posts.post_status = 'publish')) 
                GROUP BY date ORDER BY wp_posts.post_date ASC";

        $leads = $wpdb->get_results($qry);

        // Select total leads from previous period
        $start_date_prev  = date('Y-m-d', strtotime($period_prev));

        $qry_prev =  "SELECT count(*) as count 
                    FROM wp_posts INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1=1  AND ( 
                    ( wp_posts.post_date > '$start_date_prev' AND wp_posts.post_date < '$start_date' ) 
                    ) AND ( 
                    ( wp_postmeta.meta_key = 'lead_agent' AND wp_postmeta.meta_value = '$agent_id' ) 
                    ) AND wp_posts.post_type = 'lead' AND ((wp_posts.post_status = 'publish'))";

        $leads_prev = $wpdb->get_results($qry_prev);

        $leads_arr = array();
        $total_leads = 0;

        foreach ($leads as $lead) {
            $leads_arr[$lead->date] = $lead->count;

            $total_leads += intval($lead->count);
        }

        $interval = new DatePeriod(
            new DateTime($period),
            new DateInterval('P1D'),
            new DateTime()
        );
        $dates = array();

        foreach ($interval as $date) {
            $dates[$date->format('M d')] = '0';
        }

        $leads_result = array_merge($dates, $leads_arr);

        if (count($leads_result) > 0) {
            echo json_encode(array('getleads'=>true, 'leads'=>$leads_result, 'total_leads'=>$total_leads, 'total_leads_prev'=>$leads_prev[0]->count));
            exit();
        } else {
            echo json_encode(array('getleads'=>false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_leads_number', 'resideo_get_leads_number');
add_action('wp_ajax_resideo_get_leads_number', 'resideo_get_leads_number');

/**
 * Get contacted/not contacted leads per period
 */
if (!function_exists('resideo_get_contacted_leads')): 
    function resideo_get_contacted_leads() {
        global $wpdb;

        check_ajax_referer('leads_ajax_nonce', 'security');

        $period   = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '-7 days';
        $agent_id = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';

        switch ($period) {
            case '-7 days':
                $period_prev = '-14 days';
                break;
            case '-30 days':
                $period_prev = '-60 days';
                break;
            case '-60 days':
                $period_prev = '-120 days';
                break;
            case '-90 days':
                $period_prev = '-180 days';
                break;
            case '-12 months':
                $period_prev = '-24 months';
                break;
            default:
                $period_prev = '-14 days';
                break;
        }

        // Select contacted/not contacted leads from current period
        $start_date  = date('Y-m-d', strtotime($period));
        $today       = date('Y-m-d');

        $qry = "SELECT 
                COALESCE(SUM( if(mt1.meta_value = 'yes', 1, 0) ), 0) as yes, 
                COALESCE(SUM( if(mt1.meta_value = 'no', 1, 0) ), 0) as no 
                FROM wp_posts 
                INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) 
                INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id ) 
                WHERE 1=1  
                AND ( ( wp_posts.post_date > '$start_date' AND wp_posts.post_date < '$today' ) ) 
                AND ( ( wp_postmeta.meta_key = 'lead_agent' AND wp_postmeta.meta_value = '$agent_id' ) AND ( mt1.meta_key = 'lead_contacted' ) ) 
                AND wp_posts.post_type = 'lead' AND ((wp_posts.post_status = 'publish'))";

        $leads = $wpdb->get_results($qry);

        // Select contacted/not contacted leads from previous period
        $start_date_prev  = date('Y-m-d', strtotime($period_prev));

        $qry_prev = "SELECT 
                    COALESCE(SUM( if(mt1.meta_value = 'yes', 1, 0) ), 0) as yes, 
                    COALESCE(SUM( if(mt1.meta_value = 'no', 1, 0) ), 0) as no 
                    FROM wp_posts 
                    INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) 
                    INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id ) 
                    WHERE 1=1  
                    AND ( ( wp_posts.post_date > '$start_date_prev' AND wp_posts.post_date < '$start_date' ) ) 
                    AND ( ( wp_postmeta.meta_key = 'lead_agent' AND wp_postmeta.meta_value = '$agent_id' ) AND ( mt1.meta_key = 'lead_contacted' ) ) 
                    AND wp_posts.post_type = 'lead' AND ((wp_posts.post_status = 'publish'))";

        $leads_prev = $wpdb->get_results($qry_prev);

        echo json_encode(array('getleads'=>true, 'leads'=>$leads[0], 'leads_prev'=>$leads_prev[0]));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_contacted_leads', 'resideo_get_contacted_leads');
add_action('wp_ajax_resideo_get_contacted_leads', 'resideo_get_contacted_leads');

/**
 * Get leads score per period
 */
if (!function_exists('resideo_get_leads_score')): 
    function resideo_get_leads_score() {
        global $wpdb;

        check_ajax_referer('leads_ajax_nonce', 'security');

        $period   = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '-7 days';
        $agent_id = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';

        switch ($period) {
            case '-7 days':
                $period_prev = '-14 days';
                break;
            case '-30 days':
                $period_prev = '-60 days';
                break;
            case '-60 days':
                $period_prev = '-120 days';
                break;
            case '-90 days':
                $period_prev = '-180 days';
                break;
            case '-12 months':
                $period_prev = '-24 months';
                break;
            default:
                $period_prev = '-14 days';
                break;
        }

        // Select contacted/not contacted leads from current period
        $start_date  = date('Y-m-d', strtotime($period));
        $today       = date('Y-m-d');

        $qry = "SELECT 
                COALESCE(SUM( if(mt1.meta_value = '0', 1, 0) ), 0) as none, 
                COALESCE(SUM( if(mt1.meta_value = '1', 1, 0) ), 0) as fit, 
                COALESCE(SUM( if(mt1.meta_value = '2', 1, 0) ), 0) as ready, 
                COALESCE(SUM( if(mt1.meta_value = '3', 1, 0) ), 0) as engaged 
                FROM wp_posts 
                INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) 
                INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id ) 
                WHERE 1=1  
                AND ( ( wp_posts.post_date > '$start_date' AND wp_posts.post_date < '$today' ) ) 
                AND ( ( wp_postmeta.meta_key = 'lead_agent' AND wp_postmeta.meta_value = '$agent_id' ) AND ( mt1.meta_key = 'lead_score' ) ) 
                AND wp_posts.post_type = 'lead' AND ((wp_posts.post_status = 'publish'))";

        $leads = $wpdb->get_results($qry);

        // Select contacted/not contacted leads from previous period
        $start_date_prev  = date('Y-m-d', strtotime($period_prev));

        $qry_prev = "SELECT 
                    COALESCE(SUM( if(mt1.meta_value = '0', 1, 0) ), 0) as none, 
                    COALESCE(SUM( if(mt1.meta_value = '1', 1, 0) ), 0) as fit, 
                    COALESCE(SUM( if(mt1.meta_value = '2', 1, 0) ), 0) as ready, 
                    COALESCE(SUM( if(mt1.meta_value = '3', 1, 0) ), 0) as engaged 
                    FROM wp_posts 
                    INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) 
                    INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id ) 
                    WHERE 1=1  
                    AND ( ( wp_posts.post_date > '$start_date_prev' AND wp_posts.post_date < '$start_date' ) ) 
                    AND ( ( wp_postmeta.meta_key = 'lead_agent' AND wp_postmeta.meta_value = '$agent_id' ) AND ( mt1.meta_key = 'lead_score' ) ) 
                    AND wp_posts.post_type = 'lead' AND ((wp_posts.post_status = 'publish'))";

        $leads_prev = $wpdb->get_results($qry_prev);

        echo json_encode(array('getleads'=>true, 'leads'=>$leads[0], 'leads_prev'=>$leads_prev[0]));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_leads_score', 'resideo_get_leads_score');
add_action('wp_ajax_resideo_get_leads_score', 'resideo_get_leads_score');

if (!function_exists('resideo_get_agent_leads')): 
    function resideo_get_agent_leads($agent_id) {
        $name      = isset($_GET['lead_name']) ? stripslashes(sanitize_text_field($_GET['lead_name'])) : '';
        $contacted = isset($_GET['lead_contacted']) ? sanitize_text_field($_GET['lead_contacted']) : '';
        $score     = isset($_GET['lead_score']) ? sanitize_text_field($_GET['lead_score']) : '';

        $appearance_settings = get_option('resideo_appearance_settings');
        $posts_per_page      = isset($appearance_settings['resideo_leads_per_page_field']) ? $appearance_settings['resideo_leads_per_page_field'] : 10;
        $sort                = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date';

        global $paged;

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
            's'              => $name,
            'post_type'      => 'lead',
            'post_status'    => 'publish'
        );

        if ($sort == 'date') {
            $args['orderby'] = array('date' => 'DESC', 'ID' => 'DESC');
        } else if ($sort == 'name') {
            $args['orderby'] = array('title' => 'ASC', 'date' => 'DESC', 'ID' => 'DESC');
        } else if ($sort == 'score') {
            $args['meta_key'] = 'lead_score';
            $args['orderby'] = array('meta_value_num' => 'DESC', 'date' => 'DESC', 'ID' => 'DESC');
        }

        $args['meta_query'] = array('relation' => 'AND');

        if ($contacted != '') {
            array_push($args['meta_query'], array(
                'key'   => 'lead_contacted',
                'value' => $contacted,
            ));
        }

        if ($score != '') {
            array_push($args['meta_query'], array(
                'key'   => 'lead_score',
                'value' => $score,
            ));
        }

        array_push($args['meta_query'], array(
            'key'   => 'lead_agent',
            'value' => $agent_id,
        ));

        $query = new WP_Query($args);
        wp_reset_postdata();

        return $query;
    }
endif;

if (!function_exists('resideo_save_lead')): 
    function resideo_save_lead() {
        check_ajax_referer('leads_ajax_nonce', 'security');

        $lead_id   = isset($_POST['lead_id']) ? sanitize_text_field($_POST['lead_id']) : '';
        $user_id   = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $agent_id  = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $name      = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email     = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $phone     = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $contacted = isset($_POST['contacted']) ? sanitize_text_field($_POST['contacted']) : '';
        $score     = isset($_POST['score']) ? sanitize_text_field($_POST['score']) : '';
        $notes     = isset($_POST['notes']) ? sanitize_text_field($_POST['notes']) : '';

        $lead = array(
            'post_title'  => $name,
            'post_type'   => 'lead',
            'post_status' => 'publish',
            'post_author' => $user_id
        );

        if ($lead_id != '') {
            $lead['ID'] = $lead_id;
        }

        if ($name == '') {
            echo json_encode(array('save' => false, 'message' => __('Name field is mandatory.', 'resideo')));
            exit();
        }

        $lead_id = wp_insert_post($lead);

        update_post_meta($lead_id, 'lead_agent', $agent_id);
        update_post_meta($lead_id, 'lead_email', $email);
        update_post_meta($lead_id, 'lead_phone', $phone);
        update_post_meta($lead_id, 'lead_contacted', $contacted);
        update_post_meta($lead_id, 'lead_score', $score);
        update_post_meta($lead_id, 'lead_notes', $notes);

        if ($lead_id != 0) {
            echo json_encode(array('save' => true, 'leadID' => $lead_id, 'message' => __('The lead was successfully saved. Redirecting...', 'resideo')));
            exit();
        } else {
            echo json_encode(array('save' => false, 'message' => __('Something went wrong. The lead was not saved.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_save_lead', 'resideo_save_lead');
add_action('wp_ajax_resideo_save_lead', 'resideo_save_lead');

if (!function_exists('resideo_get_lead_messages')): 
    function resideo_get_lead_messages() {
        check_ajax_referer('leads_ajax_nonce', 'security');

        $lead_id = isset($_POST['lead_id']) ? sanitize_text_field($_POST['lead_id']) : '';

        $messages = get_post_meta($lead_id, 'lead_messages', true);

        if (is_array($messages) && count($messages) > 0) {
            echo json_encode(array('getmessages'=>true, 'messages'=>$messages));
            exit();
        } else {
            echo json_encode(array('getmessages'=>false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_lead_messages', 'resideo_get_lead_messages');
add_action('wp_ajax_resideo_get_lead_messages', 'resideo_get_lead_messages');

if (!function_exists('resideo_get_lead_wishlist')): 
    function resideo_get_lead_wishlist() {
        check_ajax_referer('leads_ajax_nonce', 'security');

        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';

        $props = array();

        if ($user_id != '') {
            $wl_posts = resideo_get_wishlist($user_id, true);

            if ($wl_posts) {
                $total_p = $wl_posts->found_posts;
            } else {
                $total_p = 0;
            }

            if ($total_p != 0) {
                $general_settings = get_option('resideo_general_settings');
                $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
                $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
                $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
                $currency         = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
                $currency_pos     = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
                $locale           = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
                $decimals         = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
                setlocale(LC_MONETARY, $locale);

                while ($wl_posts->have_posts()) {
                    $wl_posts->the_post();

                    $prop = new stdClass();

                    $prop_id     = get_the_ID();
                    $prop->link  = get_permalink($prop_id);
                    $prop->title = get_the_title($prop_id);

                    $gallery     = get_post_meta($prop_id, 'property_gallery', true);
                    $photos      = explode(',', $gallery);
                    $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-thmb');

                    if ($first_photo != '') {
                        $prop->photo = $first_photo[0];
                    } else {
                        $prop->photo = RESIDEO_PLUGIN_PATH . 'images/ph-thmb.jpg';
                    }

                    $price       = get_post_meta($prop_id, 'property_price', true);
                    $price_label = get_post_meta($prop_id, 'property_price_label', true);

                    if (is_numeric($price)) {
                        if ($decimals == 1) {
                            $price = money_format('%!i', $price);
                        } else {
                            $price = money_format('%!.0i', $price);
                        }
                        $currency_val = $currency;
                    } else {
                        $price_label = '';
                        $currency_val = '';
                    }

                    if ($currency_pos == 'before') {
                        $prop->price = esc_html($currency_val) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                    } else {
                        $prop->price = esc_html($price) . esc_html($currency_val) . ' <span>' . esc_html($price_label) . '</span>';
                    }

                    $prop->beds  = get_post_meta($prop_id, 'property_beds', true);
                    $prop->baths = get_post_meta($prop_id, 'property_baths', true);
                    $prop->size  = get_post_meta($prop_id, 'property_size', true);

                    $prop->beds_label = $beds_label;
                    $prop->baths_label = $baths_label;
                    $prop->unit  = $unit;

                    array_push($props, $prop);
                }

                echo json_encode(array('getwl' => true, 'props' => $props));
                exit();
            } else {
                echo json_encode(array('getwl' => false));
                exit();
            }
        } else {
            echo json_encode(array('getwl' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_lead_wishlist', 'resideo_get_lead_wishlist');
add_action('wp_ajax_resideo_get_lead_wishlist', 'resideo_get_lead_wishlist');

if (!function_exists('resideo_get_lead_searches')): 
    function resideo_get_lead_searches() {
        check_ajax_referer('leads_ajax_nonce', 'security');

        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';

        if ($user_id != '') {
            $searches = get_user_meta($user_id, 'user_search', true);

            if (is_array($searches) && count($searches) > 0) {
                echo json_encode(array('getsearches'=>true, 'searches'=>$searches));
                exit();
            } else {
                echo json_encode(array('getsearches'=>false));
                exit();
            }
        } else {
            echo json_encode(array('getsearches'=>false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_get_lead_searches', 'resideo_get_lead_searches');
add_action('wp_ajax_resideo_get_lead_searches', 'resideo_get_lead_searches');

if (!function_exists('resideo_delete_lead')): 
    function resideo_delete_lead() {
        check_ajax_referer('leads_ajax_nonce', 'security');

        $del_id = isset($_POST['lead_id']) ? sanitize_text_field($_POST['lead_id']) : '';

        $del_lead = wp_delete_post($del_id);

        if ($del_lead) {
            echo json_encode(array('delete'=>true, 'message'=>__('The lead was successfully deleted. Redirecting...', 'resideo')));
            exit();
        } else {
            echo json_encode(array('delete'=>false, 'message'=>__('Something went wrong. The lead was not deleted.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_lead', 'resideo_delete_lead');
add_action('wp_ajax_resideo_delete_lead', 'resideo_delete_lead');
?>