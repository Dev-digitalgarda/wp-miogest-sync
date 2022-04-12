<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_cities')): 
    function resideo_admin_cities() {
        add_settings_section('resideo_cities_section', __('Cities', 'resideo'), 'resideo_cities_section_callback', 'resideo_cities_settings');
    }
endif;

if (!function_exists('resideo_cities_section_callback')): 
    function resideo_cities_section_callback() { 
        wp_nonce_field('add_cities_ajax_nonce', 'securityAddCities', true);

        $options = get_option('resideo_cities_settings');

        print '<h4>' . __('Add New City', 'resideo') . '</h4>';
        print '<table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">' . __('City ID', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="city_id" id="city_id">
                            <p class="help">' . __('Give the city an unique ID (start with a letter)', 'resideo') . '</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('City Name', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="city_name" id="city_name">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Position', 'resideo') . '</th>
                        <td>
                            <input type="text" size="4" name="city_position" id="city_position" value="0">
                        </td>
                    </tr>
                </tbody>
            </table>';
        print '<p class="submit"><input type="button" name="add_city_btn" id="add_city_btn" class="button button-secondary" value="' . __('Add City', 'resideo') . '">&nbsp;&nbsp;&nbsp;<span class="fa fa-spin fa-spinner preloader"></span></p>';

        print '<h4>' . __('Cities List', 'resideo') . '</h4>';
        print '<table class="table table-hover" id="citiesTable">
                <thead>
                    <tr>
                        <th>' . __('City ID', 'resideo') . '</th>
                        <th>' . __('City Name', 'resideo') . '</th>
                        <th>' . __('Position', 'resideo') . '</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>';

        if (is_array($options)) {
            uasort($options, "resideo_compare_position");

            foreach ($options as $key => $value) {
                print '<tr>
                        <td><input type="text" name="resideo_cities_settings[' . $key . '][id]" value="' . $value['id'] . '"></td>
                        <td><input type="text" name="resideo_cities_settings[' . $key . '][name]" value="' . $value['name'] . '"></td>
                        <td><input type="text" size="4" name="resideo_cities_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>
                        <td align="right"><a href="javascript:void(0);" data-row="' . $key . '" class="delete-city admin-list-del"><span class="fa fa-trash-o"></span></a></td>
                    </tr>';
            }
        }

        print '</tbody></table>';
    }
endif;

if (!function_exists('resideo_add_cities')): 
    function resideo_add_cities() {
        check_ajax_referer('add_cities_ajax_nonce', 'security');

        $id       = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
        $name     = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $position = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : '';

        if ($id == '') {
            echo json_encode(array('add'=>false, 'message'=>__('City ID is mandatory.', 'resideo')));
            exit();
        }
        if ($name == '') {
            echo json_encode(array('add'=>false, 'message'=>__('City name is mandatory.', 'resideo')));
            exit();
        }
        if ($position == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Position is mandatory.', 'resideo')));
            exit();
        }

        $var_name = str_replace(' ', '_', trim($id));
        $var_name = sanitize_key($var_name);

        $resideo_cities_settings = get_option('resideo_cities_settings');
        if (!is_array($resideo_cities_settings)) {
            $resideo_cities_settings = array();
        }
        $resideo_cities_settings[$var_name]['id']       = $id;
        $resideo_cities_settings[$var_name]['name']     = $name;
        $resideo_cities_settings[$var_name]['position'] = $position;

        update_option('resideo_cities_settings', $resideo_cities_settings);

        echo json_encode(array('add'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_add_cities', 'resideo_add_cities');
add_action('wp_ajax_resideo_add_cities', 'resideo_add_cities');

if (!function_exists('resideo_delete_cities')): 
    function resideo_delete_cities() {
        check_ajax_referer('add_cities_ajax_nonce', 'security');
        $city_id = isset($_POST['city_id']) ? sanitize_text_field($_POST['city_id']) : '';

        $resideo_cities_settings = get_option('resideo_cities_settings');
        unset($resideo_cities_settings[$city_id]);
        update_option('resideo_cities_settings', $resideo_cities_settings);

        echo json_encode(array('delete'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_cities', 'resideo_delete_cities');
add_action('wp_ajax_resideo_delete_cities', 'resideo_delete_cities');
?>