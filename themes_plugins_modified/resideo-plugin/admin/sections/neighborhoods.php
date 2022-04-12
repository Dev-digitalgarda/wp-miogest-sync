<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_neighborhoods')): 
    function resideo_admin_neighborhoods() {
        add_settings_section('resideo_neighborhoods_section', __('Neighborhoods', 'resideo'), 'resideo_neighborhoods_section_callback', 'resideo_neighborhoods_settings');
    }
endif;

if (!function_exists('resideo_neighborhoods_section_callback')): 
    function resideo_neighborhoods_section_callback() { 
        wp_nonce_field('add_neighborhoods_ajax_nonce', 'securityAddNeighborhoods', true);

        $options = get_option('resideo_neighborhoods_settings');

        print '<h4>' . __('Add New Neighborhood', 'resideo') . '</h4>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">' . __('Neighborhood ID', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="neighborhood_id" id="neighborhood_id">
                            <p class="help">' . __('Give the neighborhood an unique ID (start with a letter)', 'resideo') . '</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Neighborhood Name', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="neighborhood_name" id="neighborhood_name">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Position', 'resideo') . '</th>
                        <td>
                            <input type="text" size="4" name="neighborhood_position" id="neighborhood_position" value="0">
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="button" name="add_neighborhood_btn" id="add_neighborhood_btn" class="button button-secondary" value="' . __('Add Neighborhood', 'resideo') . '">&nbsp;&nbsp;&nbsp;<span class="fa fa-spin fa-spinner preloader"></span></p>

            <h4>' . __('Neighborhoods List', 'resideo') . '</h4>
            <table class="table table-hover" id="neighborhoodsTable">
                <thead>
                    <tr>
                        <th>' . __('Neighborhood ID', 'resideo') . '</th>
                        <th>' . __('Neighborhood Name', 'resideo') . '</th>
                        <th>' . __('Position', 'resideo') . '</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>';

        if (is_array($options)) {
            uasort($options, "resideo_compare_position");

            foreach ($options as $key => $value) {
                print '<tr>
                            <td><input type="text" name="resideo_neighborhoods_settings[' . $key . '][id]" value="' . $value['id'] . '"></td>
                            <td><input type="text" name="resideo_neighborhoods_settings[' . $key . '][name]" value="' . $value['name'] . '"></td>
                            <td><input type="text" size="4" name="resideo_neighborhoods_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>
                            <td align="right"><a href="javascript:void(0);" data-row="' . $key . '" class="delete-neighborhood admin-list-del"><span class="fa fa-trash-o"></span></a></td>
                        </tr>';
            }
        }

        print '</tbody></table>';
    }
endif;

if (!function_exists('resideo_add_neighborhoods')): 
    function resideo_add_neighborhoods() {
        check_ajax_referer('add_neighborhoods_ajax_nonce', 'security');
        $id       = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
        $name     = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $position = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : '';

        if ($id == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Neighborhood ID is mandatory.', 'resideo')));
            exit();
        }
        if ($name == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Neighborhood name is mandatory.', 'resideo')));
            exit();
        }
        if ($position == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Position is mandatory.', 'resideo')));
            exit();
        }

        $var_name = str_replace(' ', '_', trim($id));
        $var_name = sanitize_key($var_name);

        $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings');
        if (!is_array($resideo_neighborhoods_settings)) {
            $resideo_neighborhoods_settings = array();
        }
        $resideo_neighborhoods_settings[$var_name]['id']       = $id;
        $resideo_neighborhoods_settings[$var_name]['name']     = $name;
        $resideo_neighborhoods_settings[$var_name]['position'] = $position;

        update_option('resideo_neighborhoods_settings', $resideo_neighborhoods_settings);

        echo json_encode(array('add'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_add_neighborhoods', 'resideo_add_neighborhoods');
add_action('wp_ajax_resideo_add_neighborhoods', 'resideo_add_neighborhoods');

if (!function_exists('resideo_delete_neighborhoods')): 
    function resideo_delete_neighborhoods() {
        check_ajax_referer('add_neighborhoods_ajax_nonce', 'security');
        $neighborhood_id = isset($_POST['neighborhood_id']) ? sanitize_text_field($_POST['neighborhood_id']) : '';

        $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings');
        unset($resideo_neighborhoods_settings[$neighborhood_id]);
        update_option('resideo_neighborhoods_settings', $resideo_neighborhoods_settings);

        echo json_encode(array('delete'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_neighborhoods', 'resideo_delete_neighborhoods');
add_action('wp_ajax_resideo_delete_neighborhoods', 'resideo_delete_neighborhoods');
?>