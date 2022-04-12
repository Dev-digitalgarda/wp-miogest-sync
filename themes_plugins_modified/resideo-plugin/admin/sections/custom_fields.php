<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_fields')): 
    function resideo_admin_fields() {
        add_settings_section('resideo_fields_section', __('Property Custom Fields', 'resideo'), 'resideo_fields_section_callback', 'resideo_fields_settings');
    }
endif;

if (!function_exists('resideo_fields_section_callback')): 
    function resideo_fields_section_callback() { 
        wp_nonce_field('add_custom_fields_ajax_nonce', 'securityAddCustomFields', true);

        print '<p class="help">' . __('These fields will be displayed under Key Details section on the property page', 'resideo') . '</p>';
        print '<h4>' . __('Add New Custom Fields', 'resideo') . '</h4>';
        print '<table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">' . __('Field Name', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="custom_field_name" id="custom_field_name">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Field Label', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="custom_field_label" id="custom_field_label">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Field Type', 'resideo') . '</th>
                        <td>
                            <select name="custom_field_type" id="custom_field_type">
                                <option value="text_field">' . __('Text', 'resideo') . '</option>
                                <option value="numeric_field">' . __('Numeric', 'resideo') . '</option>
                                <option value="date_field">' . __('Date', 'resideo') . '</option>
                                <option value="list_field">' . __('List', 'resideo') . '</option>
                                <option value="interval_field">' . __('Interval', 'resideo') . '</option>
                            </select>
                            <input type="text" size="40" name="custom_list_field_items" id="custom_list_field_items" style="display: none;">
                            <p class="help" style="display: none; margin-left: 96px;">' . __('Enter the list values separated by comma.', 'resideo') . '</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Mandatory', 'resideo') . '</th>
                        <td>
                            <select name="custom_field_mandatory" id="custom_field_mandatory">
                                <option value="no">' . __('No', 'resideo') . '</option>
                                <option value="yes">' . __('Yes', 'resideo') . '</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Show in Search', 'resideo') . '</th>
                        <td>
                            <select name="custom_field_search" id="custom_field_search">
                                <option value="no">' . __('No', 'resideo') . '</option>
                                <option value="yes">' . __('Yes', 'resideo') . '</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Show in Filter', 'resideo') . '</th>
                        <td>
                            <select name="custom_field_filter" id="custom_field_filter">
                                <option value="no">' . __('No', 'resideo') . '</option>
                                <option value="yes">' . __('Yes', 'resideo') . '</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Search Comparison', 'resideo') . '</th>
                        <td>
                            <select name="custom_field_comparison" id="custom_field_comparison">
                                <option value="equal">' . __('Equal', 'resideo') . '</option>
                                <option value="greater">' . __('Greater', 'resideo') . '</option>
                                <option value="smaller">' . __('Smaller', 'resideo') . '</option>
                                <option value="like">' . __('Like', 'resideo') . '</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Position', 'resideo') . '</th>
                        <td>
                            <input type="text" size="4" name="custom_field_position" id="custom_field_position" value="0">
                        </td>
                    </tr>
                </tbody>
            </table>';
        print '<p class="submit"><input type="button" name="add_fields_btn" id="add_fields_btn" class="button button-secondary" value="' . __('Add Field', 'resideo') . '">&nbsp;&nbsp;&nbsp;<span class="fa fa-spin fa-spinner preloader"></span></p>';

        print '<h4>' . __('Custom Fields List', 'resideo') . '</h4>';
        print '<table class="table table-hover" id="customFieldsTable">
                <thead>
                    <tr>
                        <th>' . __('Field Name', 'resideo') . '</th>
                        <th>' . __('Field Label', 'resideo') . '</th>
                        <th>' . __('Field Type', 'resideo') . '</th>
                        <th>' . __('Mandatory', 'resideo') . '</th>
                        <th>' . __('Show in Search', 'resideo') . '</th>
                        <th>' . __('Show in Filter', 'resideo') . '</th>
                        <th>' . __('Search Comparison', 'resideo') . '</th>
                        <th>' . __('Position', 'resideo') . '</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>';

        $options = get_option('resideo_fields_settings');

        if (is_array($options)) {
            uasort($options, 'resideo_compare_position');

            foreach ($options as $key => $value) {

                // Field name and label
                print '<tr>
                    <td><input type="text" name="resideo_fields_settings[' . $key . '][name]" value="' . $value['name'] . '"></td>
                    <td><input type="text" name="resideo_fields_settings[' . $key . '][label]" value="' . $value['label'] . '"></td>
                    <td>
                        <select class="table-field-type" name="resideo_fields_settings[' . $key . '][type]">';

                // Field type
                print '<option value="text_field"';
                if (isset($value['type']) && $value['type'] == 'text_field') {
                    print ' selected ';
                }
                print '>' . __('Text', 'resideo') . '</option>';

                print '<option value="numeric_field"';
                if (isset($value['type']) && $value['type'] == 'numeric_field') {
                    print ' selected ';
                }
                print '>' . __('Numeric', 'resideo') . '</option>';

                print '<option value="date_field"';
                if (isset($value['type']) && $value['type'] == 'date_field') {
                    print ' selected ';
                }
                print '>' . __('Date', 'resideo') . '</option>';

                print '<option value="list_field"';
                if (isset($value['type']) && $value['type'] == 'list_field') {
                    print ' selected ';
                }
                print '>' . __('List', 'resideo') . '</option>';

                print '<option value="interval_field"';
                if (isset($value['type']) && $value['type'] == 'interval_field') {
                    print ' selected ';
                }
                print '>' . __('Interval', 'resideo') . '</option>';

                print '</select>';

                print '<input type="text" name="resideo_fields_settings[' . $key . '][list]" value="' . $value['list'] . '" style="display:none;" placeholder="' . __('Comma separated values', 'resideo') . '">';

                print '</td>';

                // Field mandatory
                print '<td>
                        <select name="resideo_fields_settings[' . $key . '][mandatory]">';

                print '<option value="no"';
                if (isset($value['mandatory']) && $value['mandatory'] == 'no') {
                    print ' selected ';
                }
                print '>' . __('No', 'resideo') . '</option>';

                print '<option value="yes"';
                if (isset($value['mandatory']) && $value['mandatory'] == 'yes') {
                    print ' selected ';
                }
                print '>' . __('Yes', 'resideo') . '</option>';

                print '</select></td>';

                // Field show in search
                print '<td>
                        <select name="resideo_fields_settings[' . $key . '][search]">';

                print '<option value="no"';
                if (isset($value['search']) && $value['search'] == 'no') {
                    print ' selected ';
                }
                print '>' . __('No', 'resideo') . '</option>';

                print '<option value="yes"';
                if (isset($value['search']) && $value['search'] == 'yes') {
                    print ' selected ';
                }
                print '>' . __('Yes', 'resideo') . '</option>';

                print '</select></td>';

                // Field show in filter
                print '<td>
                        <select name="resideo_fields_settings[' . $key . '][filter]">';

                print '<option value="no"';
                if (isset($value['filter']) && $value['filter'] == 'no') {
                    print ' selected ';
                }
                print '>' . __('No', 'resideo') . '</option>';

                print '<option value="yes"';
                if (isset($value['filter']) && $value['filter'] == 'yes') {
                    print ' selected ';
                }
                print '>' . __('Yes', 'resideo') . '</option>';

                print '</select></td>';

                // Field search comparison
                print '<td>
                        <select name="resideo_fields_settings[' . $key . '][comparison]">';

                print '<option value="equal"';
                if (isset($value['comparison']) && $value['comparison'] == 'equal') {
                    print ' selected ';
                }
                print '>' . __('Equal', 'resideo') . '</option>';

                print '<option value="greater"';
                if (isset($value['comparison']) && $value['comparison'] == 'greater') {
                    print ' selected ';
                }
                print '>' . __('Greater', 'resideo') . '</option>';

                print '<option value="smaller"';
                if (isset($value['comparison']) && $value['comparison'] == 'smaller') {
                    print ' selected ';
                }
                print '>' . __('Smaller', 'resideo') . '</option>';

                print '<option value="like"';
                if (isset($value['comparison']) && $value['comparison'] == 'like') {
                    print ' selected ';
                }
                print '>' . __('Like', 'resideo') . '</option>';

                print '</select></td>';

                 // Field position
                print '<td><input type="text" size="4" name="resideo_fields_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>';

                // Field delete
                print '<td align="right"><a href="javascript:void(0);" data-row="' . $key . '" class="delete-field admin-list-del"><span class="fa fa-trash-o"></span></a></td>';
                print '</tr>';
            }
        }

        print '</tbody></table>';
    }
endif;

if (!function_exists('resideo_add_custom_fields')): 
    function resideo_add_custom_fields() {
        check_ajax_referer('add_custom_fields_ajax_nonce', 'security');

        $name       = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $label      = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
        $type       = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $list       = isset($_POST['list']) ? sanitize_text_field($_POST['list']) : '';
        $mandatory  = isset($_POST['mandatory']) ? sanitize_text_field($_POST['mandatory']) : '';
        $position   = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : '';
        $search     = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $filter     = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : '';
        $comparison = isset($_POST['comparison']) ? sanitize_text_field($_POST['comparison']) : '';

        if ($name == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field name is mandatory.', 'resideo')));
            exit();
        }
        if ($label == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field label is mandatory.', 'resideo')));
            exit();
        }
        if ($type == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Field type is mandatory.', 'resideo')));
            exit();
        }
        if ($type != '' && $type == 'list_field' && $list == '') {
            echo json_encode(array('add'=>false, 'message'=>__('The list requires at least one element.', 'resideo')));
            exit();
        }
        if ($position == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Position is mandatory.', 'resideo')));
            exit();
        }

        $var_name = str_replace(' ', '_', trim($name));
        $var_name = sanitize_key($var_name);

        $resideo_fields_settings = get_option('resideo_fields_settings');
        if (!is_array($resideo_fields_settings)) {
            $resideo_fields_settings = array();
        }
        $resideo_fields_settings[$var_name]['name']       = $name;
        $resideo_fields_settings[$var_name]['label']      = $label;
        $resideo_fields_settings[$var_name]['type']       = $type;
        $resideo_fields_settings[$var_name]['list']       = $list;
        $resideo_fields_settings[$var_name]['mandatory']  = $mandatory;
        $resideo_fields_settings[$var_name]['position']   = $position;
        $resideo_fields_settings[$var_name]['search']     = $search;
        $resideo_fields_settings[$var_name]['filter']     = $filter;
        $resideo_fields_settings[$var_name]['comparison'] = $comparison;
        update_option('resideo_fields_settings', $resideo_fields_settings);

        echo json_encode(array('add'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_add_custom_fields', 'resideo_add_custom_fields');
add_action('wp_ajax_resideo_add_custom_fields', 'resideo_add_custom_fields');

if (!function_exists('resideo_delete_custom_fields')): 
    function resideo_delete_custom_fields() {
        check_ajax_referer('add_custom_fields_ajax_nonce', 'security');
        $field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';

        $resideo_fields_settings = get_option('resideo_fields_settings');
        unset($resideo_fields_settings[$field_name]);
        update_option('resideo_fields_settings', $resideo_fields_settings);

        echo json_encode(array('delete'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_custom_fields', 'resideo_delete_custom_fields');
add_action('wp_ajax_resideo_delete_custom_fields', 'resideo_delete_custom_fields');
?>