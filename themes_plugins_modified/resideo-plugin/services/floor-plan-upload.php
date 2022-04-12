<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_upload_floor_plan')): 
    function resideo_upload_floor_plan() {
        $file = array(
            'name'     => sanitize_text_field($_FILES['aaiu_upload_file_floor_plan']['name']),
            'type'     => sanitize_text_field($_FILES['aaiu_upload_file_floor_plan']['type']),
            'tmp_name' => sanitize_text_field($_FILES['aaiu_upload_file_floor_plan']['tmp_name']),
            'error'    => sanitize_text_field($_FILES['aaiu_upload_file_floor_plan']['error']),
            'size'     => sanitize_text_field($_FILES['aaiu_upload_file_floor_plan']['size'])
        );

        $file = resideo_fileupload_process_floor_plan($file);
    }
endif;
add_action('wp_ajax_resideo_upload_floor_plan', 'resideo_upload_floor_plan');
add_action('wp_ajax_nopriv_resideo_upload_floor_plan', 'resideo_upload_floor_plan');

if (!function_exists('resideo_delete_file_floor_plan')): 
    function resideo_delete_file_floor_plan() {
        $attach_id = intval(sanitize_text_field($_POST['attach_id']));

        wp_delete_attachment($attach_id, true);
        exit;
    }
endif;
add_action('wp_ajax_resideo_delete_file_floor_plan', 'resideo_delete_file_floor_plan');
add_action('wp_ajax_nopriv_resideo_delete_file_floor_plan', 'resideo_delete_file_floor_plan');

if (!function_exists('resideo_fileupload_process_floor_plan')): 
    function resideo_fileupload_process_floor_plan($file) {
        $attachment = resideo_handle_file_floor_plan($file);

        if (is_array($attachment)) {
            $html = resideo_get_html_floor_plan($attachment);
            $response = array(
                'success' => true,
                'html'    => $html,
                'attach'  => $attachment['id']
            );

            echo json_encode($response);
            exit;
        }

        $response = array('success' => false);

        echo json_encode($response);
        exit;
    }
endif;

if (!function_exists('resideo_handle_file_floor_plan')): 
    function resideo_handle_file_floor_plan($upload_data) {
        $return        = false;
        $uploaded_file = wp_handle_upload($upload_data, array('test_form' => false));

        if (isset($uploaded_file['file'])) {
            $file_loc  = $uploaded_file['file'];
            $file_name = basename($upload_data['name']);
            $file_type = wp_check_filetype($file_name);

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id   = wp_insert_attachment($attachment, $file_loc);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);

            wp_update_attachment_metadata($attach_id, $attach_data);

            $return = array('data' => $attach_data, 'id' => $attach_id);

            return $return;
        }

        return $return;
    }
endif;

if (!function_exists('resideo_get_html_floor_plan')): 
    function resideo_get_html_floor_plan($attachment) {
        $attach_id = $attachment['id'];
        $post      = get_post($attach_id);
        $dir       = wp_upload_dir();
        $path      = $dir['baseurl'];
        $file      = $attachment['data']['file'];
        $html      = '';
        $html      .= $path . '/' . $file;

        return $html;
    }
endif;
?>