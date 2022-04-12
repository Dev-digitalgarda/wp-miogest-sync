<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_contact_fields')): 
    function resideo_admin_contact_fields() {
        add_settings_section('resideo_contact_fields_section', __('Contact Form Fields', 'resideo'), 'resideo_contact_fields_section_callback', 'resideo_contact_fields_settings');
    }
endif;

if (!function_exists('resideo_contact_fields_section_callback')): 
    function resideo_contact_fields_section_callback() { 
        wp_nonce_field('add_contact_fields_ajax_nonce', 'securityAddContactFields', true);

        print '<h4>' . __('Add New Contact Field', 'resideo') . '</h4>';
        print '<table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">' . __('Field Name', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="contact_field_name" id="contact_field_name">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Field Label', 'resideo') . '</th>
                        <td>
                            <input type="text" size="40" name="contact_field_label" id="contact_field_label">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Field Type', 'resideo') . '</th>
                        <td>
                            <select name="contact_field_type" id="contact_field_type">
                                <option value="text_input_field">' . __('Text Input', 'resideo') . '</option>
                                <option value="textarea_field">' . __('Textarea', 'resideo') . '</option>
                                <option value="select_field">' . __('Select', 'resideo') . '</option>
                                <option value="checkbox_field">' . __('Checkbox', 'resideo') . '</option>
                                <option value="date_field">' . __('Date', 'resideo') . '</option>
                            </select>
                            <input type="text" size="40" name="contact_list_field_items" id="contact_list_field_items" style="display: none;">
                            <p class="help" style="display: none; margin-left: 106px;">' . __('Enter the list values separated by comma.', 'resideo') . '</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Mandatory', 'resideo') . '</th>
                        <td>
                            <select name="contact_field_mandatory" id="contact_field_mandatory">
                                <option value="no">' . __('No', 'resideo') . '</option>
                                <option value="yes">' . __('Yes', 'resideo') . '</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">' . __('Position', 'resideo') . '</th>
                        <td>
                            <input type="text" size="4" name="contact_field_position" id="contact_field_position" value="0">
                        </td>
                    </tr>
                </tbody>
            </table>';
        print '<p class="submit"><input type="button" name="add_contact_fields_btn" id="add_contact_fields_btn" class="button button-secondary" value="' . __('Add Field', 'resideo') . '">&nbsp;&nbsp;&nbsp;<span class="fa fa-spin fa-spinner preloader"></span></p>';

        print '<h4>' . __('Contact Fields List', 'resideo') . '</h4>';
        print '<table class="table table-hover" id="contactFieldsTable">
                <thead>
                    <tr>
                        <th>' . __('Field Name', 'resideo') . '</th>
                        <th>' . __('Field Label', 'resideo') . '</th>
                        <th>' . __('Field Type', 'resideo') . '</th>
                        <th>' . __('Mandatory', 'resideo') . '</th>
                        <th>' . __('Position', 'resideo') . '</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>';

        $options = get_option('resideo_contact_fields_settings');

        if (is_array($options)) {
            uasort($options, 'resideo_compare_position');

            foreach ($options as $key => $value) {

                // Field name and label
                print '<tr>
                    <td><input type="text" name="resideo_contact_fields_settings[' . $key . '][name]" value="' . $value['name'] . '"></td>
                    <td><input type="text" name="resideo_contact_fields_settings[' . $key . '][label]" value="' . $value['label'] . '"></td>
                    <td>
                        <select class="table-contact-field-type" name="resideo_contact_fields_settings[' . $key . '][type]">';

                // Field type
                print '<option value="text_input_field"';
                if (isset($value['type']) && $value['type'] == 'text_input_field') {
                    print ' selected ';
                }
                print '>' . __('Text Input', 'resideo') . '</option>';

                print '<option value="textarea_field"';
                if (isset($value['type']) && $value['type'] == 'textarea_field') {
                    print ' selected ';
                }
                print '>' . __('Textarea', 'resideo') . '</option>';

                print '<option value="select_field"';
                if (isset($value['type']) && $value['type'] == 'select_field') {
                    print ' selected ';
                }
                print '>' . __('Select', 'resideo') . '</option>';

                print '<option value="checkbox_field"';
                if (isset($value['type']) && $value['type'] == 'checkbox_field') {
                    print ' selected ';
                }
                print '>' . __('Checkbox', 'resideo') . '</option>';

                print '<option value="date_field"';
                if (isset($value['type']) && $value['type'] == 'date_field') {
                    print ' selected ';
                }
                print '>' . __('Date', 'resideo') . '</option>';

                print '</select>';

                print '<input type="text" name="resideo_contact_fields_settings[' . $key . '][list]" value="' . $value['list'] . '" style="display:none;" placeholder="' . __('Comma separated values', 'resideo') . '">';

                print '</td>';

                // Field mandatory
                print '<td>
                        <select name="resideo_contact_fields_settings[' . $key . '][mandatory]">';

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

                 // Field position
                print '<td><input type="text" size="4" name="resideo_contact_fields_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>';

                // Field delete
                print '<td align="right"><a href="javascript:void(0);" data-row="' . $key . '" class="delete-contact-field admin-list-del"><span class="fa fa-trash-o"></span></a></td>';
                print '</tr>';
            }
        }

        print '</tbody></table>';
    }
endif;

if (!function_exists('resideo_add_contact_fields')): 
    function resideo_add_contact_fields() {
        check_ajax_referer('add_contact_fields_ajax_nonce', 'security');

        $name      = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $label     = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
        $type      = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $list      = isset($_POST['list']) ? sanitize_text_field($_POST['list']) : '';
        $mandatory = isset($_POST['mandatory']) ? sanitize_text_field($_POST['mandatory']) : '';
        $position  = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : '';

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
        if ($type != '' && $type == 'select_field' && $list == '') {
            echo json_encode(array('add'=>false, 'message'=>__('The list requires at least one element.', 'resideo')));
            exit();
        }
        if ($position == '') {
            echo json_encode(array('add'=>false, 'message'=>__('Position is mandatory.', 'resideo')));
            exit();
        }

        $var_name = str_replace(' ', '_', trim($name));
        $var_name = sanitize_key($var_name);

        $resideo_contact_fields_settings = get_option('resideo_contact_fields_settings');

        if (!is_array($resideo_contact_fields_settings)) {
            $resideo_contact_fields_settings = array();
        }

        $resideo_contact_fields_settings[$var_name]['name']      = $name;
        $resideo_contact_fields_settings[$var_name]['label']     = $label;
        $resideo_contact_fields_settings[$var_name]['type']      = $type;
        $resideo_contact_fields_settings[$var_name]['list']      = $list;
        $resideo_contact_fields_settings[$var_name]['mandatory'] = $mandatory;
        $resideo_contact_fields_settings[$var_name]['position']  = $position;

        update_option('resideo_contact_fields_settings', $resideo_contact_fields_settings);

        echo json_encode(array('add'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_add_contact_fields', 'resideo_add_contact_fields');
add_action('wp_ajax_resideo_add_contact_fields', 'resideo_add_contact_fields');

if (!function_exists('resideo_delete_contact_fields')): 
    function resideo_delete_contact_fields() {
        check_ajax_referer('add_contact_fields_ajax_nonce', 'security');
        $field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';

        $resideo_contact_fields_settings = get_option('resideo_contact_fields_settings');
        unset($resideo_contact_fields_settings[$field_name]);
        update_option('resideo_contact_fields_settings', $resideo_contact_fields_settings);

        echo json_encode(array('delete'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_contact_fields', 'resideo_delete_contact_fields');
add_action('wp_ajax_resideo_delete_contact_fields', 'resideo_delete_contact_fields');
?>