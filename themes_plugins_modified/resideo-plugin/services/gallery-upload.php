<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_upload_gallery')): 
    function resideo_upload_gallery() {
        $file = array(
            'name'     => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['name']),
            'type'     => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['type']),
            'tmp_name' => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['tmp_name']),
            'error'    => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['error']),
            'size'     => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['size'])
        );

        $file = resideo_fileupload_process_gallery($file);
    }
endif;
add_action('wp_ajax_resideo_upload_gallery', 'resideo_upload_gallery');
add_action('wp_ajax_nopriv_resideo_upload_gallery', 'resideo_upload_gallery');

if (!function_exists('resideo_delete_file_gallery')): 
    function resideo_delete_file_gallery() {
        $attach_id = intval(sanitize_text_field($_POST['attach_id']));

        wp_delete_attachment($attach_id, true);
        exit;
    }
endif;
add_action('wp_ajax_resideo_delete_file_gallery', 'resideo_delete_file_gallery');
add_action('wp_ajax_nopriv_resideo_delete_file_gallery', 'resideo_delete_file_gallery');

if (!function_exists('resideo_fileupload_process_gallery')): 
    function resideo_fileupload_process_gallery($file) {
        $attachment = resideo_handle_file_gallery($file);

        if (is_array($attachment)) {
            $html = resideo_get_html_gallery($attachment);
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

if (!function_exists('resideo_handle_file_gallery')): 
    function resideo_handle_file_gallery($upload_data) {
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

if (!function_exists('resideo_get_html_gallery')): 
    function resideo_get_html_gallery($attachment) {
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