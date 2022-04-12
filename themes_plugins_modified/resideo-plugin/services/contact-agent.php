<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_contact_agent')): 
    function resideo_contact_agent() {
        check_ajax_referer('contactagent_ajax_nonce', 'security');

        $agent_email = isset($_POST['agent_email']) ? sanitize_email($_POST['agent_email']) : '';
        $agent_id    = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $user_id     = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $firstname   = isset($_POST['firstname']) ? sanitize_text_field($_POST['firstname']) : '';
        $lastname    = isset($_POST['lastname']) ? sanitize_text_field($_POST['lastname']) : '';
        $phone       = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $email       = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $message     = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
        $title       = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $link        = isset($_POST['link']) ? sanitize_text_field($_POST['link']) : '';

        if (empty($firstname) || empty($lastname) || empty($email) || empty($message)) {
            echo json_encode(array('sent'=>false, 'message'=>__('Your message failed to be sent. Please check your fields.', 'resideo')));
            exit();
        }

        $body = '';

        if ($title != '' && $link != '') {
            $body .= __('You received the following message from ', 'resideo') . $firstname . ' ' . $lastname .
                    ' [' . __('Phone number', 'resideo') . ': ' . $phone . ']' . 
                    ' ' . __('regarding a property you listed: ', 'resideo') . '<br />' . 
                    $title . ' [ ' . $link . ' ]' . '<br /><br />
                    <i>' . $message . '</i>';
        } else {
            $body .= __('You received the following message from ', 'resideo') . 
                    $firstname . ' ' . $lastname . ' [' . __('Phone number', 'resideo') . ': ' . $phone . ']' . '<br /><br />
                    <i>' . $message . '</i>';
        }

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: '. $email,
            'Reply-To: ' . $email
        );

        $send = wp_mail(
            $agent_email,
            sprintf( __('[%s] Message from client', 'resideo'), get_option('blogname') ),
            $body,
            $headers
        );

        if ($send) {
            $agent_user = get_post_meta($agent_id, 'agent_user', true);

            $time = time();
            $date = date('Y-m-d H:i:s', $time);

            if ($user_id != '') {
                $args = array(
                    'post_type'      => 'lead',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'   => 'lead_user',
                            'value' => $user_id,
                        ),
                    ),
                );
            } else {
                $args = array(
                    'post_type'      => 'lead',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'   => 'lead_email',
                            'value' => $email,
                        ),
                    ),
                );
            }

            $lead_selection     = new WP_Query($args);
            $lead_selection_arr = get_object_vars($lead_selection);

            if (is_array($lead_selection_arr['posts']) && count($lead_selection_arr['posts']) > 0) {
                $lead_found = $lead_selection_arr['posts'][0];

                $lead_found_messages = get_post_meta($lead_found->ID, 'lead_messages', true);

                array_push($lead_found_messages, array(
                    'date'       => $date,
                    'message'    => $message,
                    'prop_title' => $title,
                    'prop_link'  => $link,
                ));

                update_post_meta($lead_found->ID, 'lead_messages', $lead_found_messages);
            } else {
                $lead = array(
                    'post_title'  => $firstname . ' ' . $lastname,
                    'post_type'   => 'lead',
                    'post_status' => 'publish',
                    'post_author' => $agent_user,
                );

                $lead_id = wp_insert_post($lead);

                update_post_meta($lead_id, 'lead_agent', $agent_id);
                update_post_meta($lead_id, 'lead_user', $user_id);
                update_post_meta($lead_id, 'lead_phone', $phone);
                update_post_meta($lead_id, 'lead_email', $email);
                update_post_meta($lead_id, 'lead_contacted', 'no');
                update_post_meta($lead_id, 'lead_score', '0');

                $lead_messages = array(
                    array(
                        'date'       => $date,
                        'message'    => $message,
                        'prop_title' => $title,
                        'prop_link'  => $link,
                    ),
                );

                update_post_meta($lead_id, 'lead_messages', $lead_messages);
            }

            echo json_encode(array('sent'=>true, 'message'=>__('Your message was successfully sent.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent'=>false, 'message'=>__('Your message failed to be sent.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_contact_agent', 'resideo_contact_agent');
add_action('wp_ajax_resideo_contact_agent', 'resideo_contact_agent');

if (!function_exists('resideo_work_with_agent')): 
    function resideo_work_with_agent() {
        check_ajax_referer('contactagent_ajax_nonce', 'security');

        $agent_email = isset($_POST['agent_email']) ? sanitize_email($_POST['agent_email']) : '';
        $agent_id    = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $user_id     = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $firstname   = isset($_POST['firstname']) ? sanitize_text_field($_POST['firstname']) : '';
        $lastname    = isset($_POST['lastname']) ? sanitize_text_field($_POST['lastname']) : '';
        $phone       = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $email       = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $interest    = isset($_POST['interest']) ? sanitize_text_field($_POST['interest']) : '';
        $message     = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

        if (empty($firstname) || empty($lastname) || empty($email)) {
            echo json_encode(array('sent'=>false, 'message'=>__('Your message failed to be sent. Please check your fields.', 'resideo')));
            exit();
        }

        $message_body =  __('I am interested in: ', 'resideo')  . $interest . '<br /><br />' . $message;
        $body = __('You received the following message from ', 'resideo') . 
                $firstname . ' ' . $lastname . ' [' . __('Phone number', 'resideo') . ': ' . $phone . ']' . '<br /><br />
                <i>' . $message_body . '</i>';

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: '. $email,
            'Reply-To: ' . $email
        );

        $send = wp_mail(
            $agent_email,
            sprintf(__('[%s] Message from client', 'resideo'), get_option('blogname')),
            $body,
            $headers
        );

        if ($send) {
            $agent_user = get_post_meta($agent_id, 'agent_user', true);

            $time = time();
            $date = date('Y-m-d H:i:s', $time);
            $args = array(
                'post_type'      => 'lead',
                'post_status'    => 'publish',
                'posts_per_page' => -1
            );

            if ($user_id != '') {
                $args['meta_query'] = array(
                    array(
                        'key'   => 'lead_user',
                        'value' => $user_id
                    )
                );
            } else {
                $args['meta_query'] = array(
                    array(
                        'key'   => 'lead_email',
                        'value' => $email
                    )
                );
            }

            $lead_selection     = new WP_Query($args);
            $lead_selection_arr = get_object_vars($lead_selection);

            if (is_array($lead_selection_arr['posts']) && count($lead_selection_arr['posts']) > 0) {
                $lead_found = $lead_selection_arr['posts'][0];

                $lead_found_messages = get_post_meta($lead_found->ID, 'lead_messages', true);

                array_push($lead_found_messages, array(
                    'date'       => $date,
                    'message'    => $message,
                    'prop_title' => '',
                    'prop_link'  => '',
                ));

                update_post_meta($lead_found->ID, 'lead_messages', $lead_found_messages);
            } else {
                $lead = array(
                    'post_title'  => $firstname . ' ' . $lastname,
                    'post_type'   => 'lead',
                    'post_status' => 'publish',
                    'post_author' => $agent_user,
                );

                $lead_id = wp_insert_post($lead);

                update_post_meta($lead_id, 'lead_agent', $agent_id);
                update_post_meta($lead_id, 'lead_user', $user_id);
                update_post_meta($lead_id, 'lead_phone', $phone);
                update_post_meta($lead_id, 'lead_email', $email);
                update_post_meta($lead_id, 'lead_contacted', 'no');
                update_post_meta($lead_id, 'lead_score', '0');

                $lead_messages = array(
                    array(
                        'date'       => $date,
                        'message'    => $message,
                        'prop_title' => '',
                        'prop_link'  => '',
                    ),
                );

                update_post_meta($lead_id, 'lead_messages', $lead_messages);
            }

            echo json_encode(array('sent'=>true, 'message'=>__('Your message was successfully sent.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent'=>false, 'message'=>__('Your message failed to be sent.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_work_with_agent', 'resideo_work_with_agent');
add_action('wp_ajax_resideo_work_with_agent', 'resideo_work_with_agent');
?>