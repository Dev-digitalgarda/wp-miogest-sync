<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_amenities')): 
    function resideo_admin_amenities() {
        add_settings_section('resideo_amenities_section', __('Amenities', 'resideo'), 'resideo_amenities_section_callback', 'resideo_amenities_settings');
    }
endif;

if (!function_exists('resideo_amenities_section_callback')): 
    function resideo_amenities_section_callback() { 
        wp_nonce_field('add_amenities_ajax_nonce', 'securityAddAmenities', true);

        print '<h4>' . __('Add New Amenity', 'resideo') . '</h4>';
        print '<table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">' . __('Amenity ID', 'resideo') . '</th>
                            <td>
                                <input type="text" size="40" name="amenity_name" id="amenity_name">
                                <p class="help">' . __('Give the amenity an unique ID (start with a letter)', 'resideo') . '</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">' . __('Amenity Label', 'resideo') . '</th>
                            <td>
                                <input type="text" size="40" name="amenity_label" id="amenity_label"><br>
                                <p class="help">' . __('This value will be displayed in the interface', 'resideo') . '</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">' . __('Amenity Icon', 'resideo') . '</th>
                            <td>
                                <input type="hidden" name="amenity_icon" id="amenity_icon" class="icons-field">
                                <a class="button button-secondary choose-icon">' . __('Browse Icons...', 'resideo') . '</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">' . __('Position', 'resideo') . '</th>
                            <td>
                                <input type="text" size="4" name="amenity_position" id="amenity_position" value="0">
                            </td>
                        </tr>
                    </tbody>
                </table>';
        print '<p class="submit"><input type="button" name="add_amenity_btn" id="add_amenity_btn" class="button button-secondary" value="' . __('Add Amenity', 'resideo') . '">&nbsp;&nbsp;&nbsp;<span class="fa fa-spin fa-spinner preloader"></span></p>';

        print '<h4>' . __('Amenities List', 'resideo') . '</h4>';
        print '<table class="table table-hover" id="amenitiesTable">
                <thead>
                    <tr>
                        <th>' . __('Field Name', 'resideo') . '</th>
                        <th>' . __('Amenity Label', 'resideo') . '</th>
                        <th>' . __('Amenity Icon', 'resideo') . '</th>
                        <th>' . __('Position', 'resideo') . '</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>';

        $options = get_option('resideo_amenities_settings');
        if (is_array($options)) {
            uasort($options, "resideo_compare_position");

            foreach ($options as $key => $value) {
                print '<tr>
                    <td><input type="text" name="resideo_amenities_settings[' . $key . '][name]" value="' . $value['name'] . '"></td>
                    <td><input type="text" name="resideo_amenities_settings[' . $key . '][label]" value="' . $value['label'] . '"></td>
                    <td>
                        <input type="hidden" name="resideo_amenities_settings[' . $key . '][icon]" class="icons-field" value="' . $value['icon'] . '">
                        <a class="button button-secondary choose-icon">' . __('Select an icon', 'resideo') . '</a>
                    </td>
                    <td><input type="text" size="4" name="resideo_amenities_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>
                    <td align="right"><a href="javascript:void(0);" data-row="' . $key . '" class="delete-amenity admin-list-del"><span class="fa fa-trash-o"></span></a></td>
                </tr>';
            }
        }

        print '</tbody></table>';
    }
endif;

if (!function_exists('resideo_add_amenities')): 
    function resideo_add_amenities() {
        check_ajax_referer('add_amenities_ajax_nonce', 'security');
        $name     = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $label    = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
        $icon     = isset($_POST['icon']) ? sanitize_text_field($_POST['icon']) : '';
        $position = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : '';

        if ($name == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field ID is mandatory.', 'resideo')));
            exit();
        }
        if ($label == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Amenity label is mandatory.', 'resideo')));
            exit();
        }
        if ($icon == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Amenity icon is mandatory.', 'resideo')));
            exit();
        }
        if ($position == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Position is mandatory.', 'resideo')));
            exit();
        }

        $var_name = str_replace(' ', '_', trim($name));
        $var_name = sanitize_key($var_name);

        $resideo_amenities_settings = get_option('resideo_amenities_settings');
        if (!is_array($resideo_amenities_settings)) {
            $resideo_amenities_settings = array();
        }
        $resideo_amenities_settings[$var_name]['name']     = $name;
        $resideo_amenities_settings[$var_name]['label']    = $label;
        $resideo_amenities_settings[$var_name]['icon']     = $icon;
        $resideo_amenities_settings[$var_name]['position'] = $position;

        update_option('resideo_amenities_settings', $resideo_amenities_settings);

        echo json_encode(array('add'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_add_amenities', 'resideo_add_amenities');
add_action('wp_ajax_resideo_add_amenities', 'resideo_add_amenities');

if (!function_exists('resideo_delete_amenities')): 
    function resideo_delete_amenities() {
        check_ajax_referer('add_amenities_ajax_nonce', 'security');
        $amenity_name = isset($_POST['amenity_name']) ? sanitize_text_field($_POST['amenity_name']) : '';

        $resideo_amenities_settings = get_option('resideo_amenities_settings');
        unset($resideo_amenities_settings[$amenity_name]);
        update_option('resideo_amenities_settings', $resideo_amenities_settings);

        echo json_encode(array('delete'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_amenities', 'resideo_delete_amenities');
add_action('wp_ajax_resideo_delete_amenities', 'resideo_delete_amenities');
?>