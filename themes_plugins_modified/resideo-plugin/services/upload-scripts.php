<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_load_upload_scripts')): 
    function resideo_load_upload_scripts() {
        $general_settings = get_option('resideo_general_settings');

        $max_file_size = 100 * 1000 * 1000;
        $max_files = (isset($general_settings['resideo_max_files_field']) && $general_settings['resideo_max_files_field'] != '') ? $general_settings['resideo_max_files_field'] : 10;

        wp_register_script('gallery-ajax-upload', RESIDEO_PLUGIN_PATH . 'js/gallery-upload.js', array('jquery', 'jquery-ui', 'plupload-handlers'), '1.0', true);
        wp_enqueue_script('gallery-ajax-upload');
        wp_localize_script('gallery-ajax-upload', 'gallery_upload_vars', 
            array('ajaxurl'         => admin_url('admin-ajax.php'),
                'nonce'             => wp_create_nonce('resideo_upload_gallery'),
                'remove'            => wp_create_nonce('resideo_remove_gallery'),
                'number'            => 1,
                'upload_enabled'    => true,
                'confirmMsg'        => __('Are you sure you want to delete this?', 'resideo'),
                'plupload'          => array(
                                        'runtimes'         => 'html5,flash,html4',
                                        'browse_button'    => 'aaiu-uploader-gallery',
                                        'container'        => 'aaiu-upload-container-gallery',
                                        'file_data_name'   => 'aaiu_upload_file_gallery',
                                        'max_file_size'    => $max_file_size . 'b',
                                        'max_files'        => $max_files,
                                        'url'              => admin_url('admin-ajax.php') . '?action=resideo_upload_gallery&nonce=' . wp_create_nonce('resideo_allow'),
                                        'flash_swf_url'    => includes_url('js/plupload/plupload.flash.swf'),
                                        'filters'          => array(array('title' => __('Allowed Files', 'resideo'), 'extensions' => "jpg,jpeg,gif,png")),
                                        'multipart'        => true,
                                        'urlstream_upload' => true
                                    ),
                'add_photo_title'   => __('Add Photo Title', 'resideo'),
                'add_photo_caption' => __('Add Photo Caption', 'resideo'),
                'dic_text'          => __('Are you sure?', 'resideo'),
                'dic_yes'           => __('Delete', 'resideo'),
                'dic_no'            => __('Cancel', 'resideo'),
            )
        );

        $beds_label = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
        $baths_label = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
        $unit  = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';

        wp_register_script('floor-plan-ajax-upload', RESIDEO_PLUGIN_PATH . 'js/floor-plans.js', array('jquery', 'jquery-ui', 'plupload-handlers'), '1.0', true);
        wp_enqueue_script('floor-plan-ajax-upload');
        wp_localize_script('floor-plan-ajax-upload', 'floor_plan_upload_vars', 
            array(
                'ajaxurl'        => admin_url('admin-ajax.php'),
                'nonce'          => wp_create_nonce('resideo_upload_floor_plan'),
                'remove'         => wp_create_nonce('resideo_remove_floor_plan'),
                'number'         => 1,
                'upload_enabled' => true,
                'confirmMsg'     => __('Are you sure you want to delete this?', 'resideo'),
                'plupload'       => array(
                                        'runtimes'         => 'html5,flash,html4',
                                        'browse_button'    => 'aaiu-uploader-floor-plan',
                                        'container'        => 'aaiu-upload-container-floor-plan',
                                        'file_data_name'   => 'aaiu_upload_file_floor_plan',
                                        'max_file_size'    => $max_file_size . 'b',
                                        'max_files'        => 1,
                                        'url'              => admin_url('admin-ajax.php') . '?action=resideo_upload_floor_plan&nonce=' . wp_create_nonce('resideo_allow'),
                                        'flash_swf_url'    => includes_url('js/plupload/plupload.flash.swf'),
                                        'filters'          => array(array('title' => __('Allowed Files', 'resideo'), 'extensions' => "jpg,jpeg,gif,png")),
                                        'multipart'        => true,
                                        'urlstream_upload' => true
                                    ),
                'plupload_edit' => array(
                                        'runtimes'         => 'html5,flash,html4',
                                        'browse_button'    => 'aaiu-uploader-floor-plan-edit',
                                        'container'        => 'aaiu-upload-container-floor-plan-edit',
                                        'file_data_name'   => 'aaiu_upload_file_floor_plan_edit',
                                        'max_file_size'    => $max_file_size . 'b',
                                        'max_files'        => 1,
                                        'url'              => admin_url('admin-ajax.php') . '?action=resideo_upload_floor_plan_edit&nonce=' . wp_create_nonce('resideo_allow'),
                                        'flash_swf_url'    => includes_url('js/plupload/plupload.flash.swf'),
                                        'filters'          => array(array('title' => __('Allowed Files', 'resideo'), 'extensions' => "jpg,jpeg,gif,png")),
                                        'multipart'        => true,
                                        'urlstream_upload' => true
                                    ),
                'dic_text'                                => __('Are you sure?', 'resideo'),
                'dic_yes'                                 => __('Delete', 'resideo'),
                'dic_no'                                  => __('Cancel', 'resideo'),
                'plugin_url'                              => RESIDEO_PLUGIN_PATH,
                'beds_label'                              => $beds_label,
                'baths_label'                             => $baths_label,
                'unit'                                    => $unit,
                'edit_floor_plan'                         => __('Edit Floor Plan', 'resideo'),
                'edit_floor_plan_title_label'             => __('Title', 'resideo'),
                'edit_floor_plan_title_placeholder'       => __('Enter plan title', 'resideo'),
                'edit_floor_plan_beds_label'              => __('Beds', 'resideo'),
                'edit_floor_plan_beds_placeholder'        => __('Enter number of beds', 'resideo'),
                'edit_floor_plan_baths_label'             => __('Baths', 'resideo'),
                'edit_floor_plan_baths_placeholder'       => __('Enter number of baths', 'resideo'),
                'edit_floor_plan_size_label'              => __('Size', 'resideo'),
                'edit_floor_plan_baths_placeholder'       => __('Enter size', 'resideo'),
                'edit_floor_plan_description_label'       => __('Description', 'resideo'),
                'edit_floor_plan_description_placeholder' => __('Enter description here...', 'resideo'),
                'edit_floor_plan_image_label'             => __('Image', 'resideo'),
                'edit_floor_plan_ok_btn'                  => __('Ok', 'resideo'),
                'edit_floor_plan_cancel_btn'              => __('Cancel', 'resideo'),
                'edit_floor_plan_upload_btn'              => __('Upload', 'resideo'),
            )
        );

        wp_register_script('avatar-ajax-upload', RESIDEO_PLUGIN_PATH . 'js/avatar-upload.js', array('jquery', 'jquery-ui', 'plupload-handlers'), '1.0', true);
        wp_enqueue_script('avatar-ajax-upload');
        wp_localize_script('avatar-ajax-upload', 'avatar_upload_vars', 
            array('ajaxurl'      => admin_url('admin-ajax.php'),
                'nonce'          => wp_create_nonce('resideo_upload_avatar'),
                'remove'         => wp_create_nonce('resideo_remove_avatar'),
                'number'         => 1,
                'upload_enabled' => true,
                'confirmMsg'     => __('Are you sure you want to delete this?', 'resideo'),
                'plupload'       => array(
                                        'runtimes'         => 'html5,flash,html4',
                                        'browse_button'    => 'aaiu-uploader-avatar',
                                        'container'        => 'aaiu-upload-container-avatar',
                                        'file_data_name'   => 'aaiu_upload_file_avatar',
                                        'max_file_size'    => $max_file_size . 'b',
                                        'max_files'        => 1,
                                        'url'              => admin_url('admin-ajax.php') . '?action=resideo_upload_avatar&nonce=' . wp_create_nonce('resideo_allow'),
                                        'flash_swf_url'    => includes_url('js/plupload/plupload.flash.swf'),
                                        'filters'          => array(array('title' => __('Allowed Files', 'resideo'), 'extensions' => "jpg,jpeg,gif,png")),
                                        'multipart'        => true,
                                        'urlstream_upload' => true
                                    ),
                'dic_text'       => __('Are you sure?', 'resideo'),
                'dic_yes'        => __('Delete', 'resideo'),
                'dic_no'         => __('Cancel', 'resideo'),
                'plugin_url'     => RESIDEO_PLUGIN_PATH,
            )
        );
    }
endif;
add_action( 'wp_enqueue_scripts', 'resideo_load_upload_scripts' );
?>