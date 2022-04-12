<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_prop_fields')): 
    function resideo_admin_prop_fields() {
        add_settings_section('resideo_prop_fields_section', __('Property Fields', 'resideo'), 'resideo_prop_fields_section_callback', 'resideo_prop_fields_settings');
        add_settings_field('resideo_p_id_field', __('ID', 'resideo'), 'resideo_p_id_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_overview_field', __('Overview', 'resideo'), 'resideo_p_overview_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_address_field', __('Address', 'resideo'), 'resideo_p_address_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_coordinates_field', __('Coordinates', 'resideo'), 'resideo_p_coordinates_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_streetno_field', __('Street No', 'resideo'), 'resideo_p_streetno_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_street_field', __('Street Name', 'resideo'), 'resideo_p_street_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_neighborhood_field', __('Neighborhood', 'resideo'), 'resideo_p_neighborhood_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_city_field', __('City', 'resideo'), 'resideo_p_city_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_state_field', __('County/State', 'resideo'), 'resideo_p_state_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_zip_field', __('Zip Code', 'resideo'), 'resideo_p_zip_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_price_field', __('Price', 'resideo'), 'resideo_p_price_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_size_field', __('Size', 'resideo'), 'resideo_p_size_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_beds_field', __('Beds', 'resideo'), 'resideo_p_beds_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_baths_field', __('Baths', 'resideo'), 'resideo_p_baths_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_type_field', __('Type', 'resideo'), 'resideo_p_type_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_status_field', __('Status', 'resideo'), 'resideo_p_status_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_keywords_field', __('Keywords', 'resideo'), 'resideo_p_keywords_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_amenities_field', __('Amenities', 'resideo'), 'resideo_p_amenities_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_calculator_field', __('Mortgage Calculator', 'resideo'), 'resideo_p_calculator_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_taxes_field', __('Property Taxes', 'resideo'), 'resideo_p_taxes_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
        add_settings_field('resideo_p_hoa_field', __('HOA Dues', 'resideo'), 'resideo_p_hoa_field_render', 'resideo_prop_fields_settings', 'resideo_prop_fields_section');
    }
endif;

if (!function_exists('resideo_prop_fields_section_callback')): 
    function resideo_prop_fields_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('resideo_p_id_field_render')): 
    function resideo_p_id_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_id_field]" name="resideo_prop_fields_settings[resideo_p_id_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_id_field']) && $options['resideo_p_id_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="16%" style="padding: 0">&nbsp;</td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>';

                    // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_id_f_field]" name="resideo_prop_fields_settings[resideo_p_id_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_id_f_field']) && $options['resideo_p_id_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_overview_field_render')): 
    function resideo_p_overview_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_overview_field]" name="resideo_prop_fields_settings[resideo_p_overview_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_overview_field']) && $options['resideo_p_overview_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                       </select>
                    </td>';

        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_overview_r_field]" name="resideo_prop_fields_settings[resideo_p_overview_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_overview_r_field']) && $options['resideo_p_overview_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="33%" style="padding: 0">&nbsp;</td>
                    <td width="33%" style="padding: 0">&nbsp;</td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_address_field_render')): 
    function resideo_p_address_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_address_field]" name="resideo_prop_fields_settings[resideo_p_address_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_address_field']) && $options['resideo_p_address_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_address_r_field]" name="resideo_prop_fields_settings[resideo_p_address_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_address_r_field']) && $options['resideo_p_address_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Input/List
        $t_values = array(
            'auto'  => __('Autocomplete', 'resideo'),
            'input' => __('Input', 'resideo'),
        );

        $fields .= '
                    <td width="18%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_address_t_field]" name="resideo_prop_fields_settings[resideo_p_address_t_field]">';
        foreach ($t_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_address_t_field']) && $options['resideo_p_address_t_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_address_s_field]" name="resideo_prop_fields_settings[resideo_p_address_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_address_s_field']) && $options['resideo_p_address_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_address_f_field]" name="resideo_prop_fields_settings[resideo_p_address_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_address_f_field']) && $options['resideo_p_address_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_coordinates_field_render')): 
    function resideo_p_coordinates_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_coordinates_field]" name="resideo_prop_fields_settings[resideo_p_coordinates_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_coordinates_field']) && $options['resideo_p_coordinates_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_coordinates_r_field]" name="resideo_prop_fields_settings[resideo_p_coordinates_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_coordinates_r_field']) && $options['resideo_p_coordinates_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_streetno_field_render')): 
    function resideo_p_streetno_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_streetno_field]" name="resideo_prop_fields_settings[resideo_p_streetno_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_streetno_field']) && $options['resideo_p_streetno_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_streetno_r_field]" name="resideo_prop_fields_settings[resideo_p_streetno_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_streetno_r_field']) && $options['resideo_p_streetno_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_street_field_render')): 
    function resideo_p_street_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_street_field]" name="resideo_prop_fields_settings[resideo_p_street_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_street_field']) && $options['resideo_p_street_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_street_r_field]" name="resideo_prop_fields_settings[resideo_p_street_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_street_r_field']) && $options['resideo_p_street_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_neighborhood_field_render')): 
    function resideo_p_neighborhood_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_neighborhood_field]" name="resideo_prop_fields_settings[resideo_p_neighborhood_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_neighborhood_field']) && $options['resideo_p_neighborhood_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_neighborhood_r_field]" name="resideo_prop_fields_settings[resideo_p_neighborhood_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_neighborhood_r_field']) && $options['resideo_p_neighborhood_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Input/List
        $t_values = array(
            'input' => __('Input', 'resideo'),
            'list'  => __('List', 'resideo'),
        );

        $fields .= '
                    <td width="18%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_neighborhood_t_field]" name="resideo_prop_fields_settings[resideo_p_neighborhood_t_field]">';
        foreach ($t_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_neighborhood_t_field']) && $options['resideo_p_neighborhood_t_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_neighborhood_s_field]" name="resideo_prop_fields_settings[resideo_p_neighborhood_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_neighborhood_s_field']) && $options['resideo_p_neighborhood_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_neighborhood_f_field]" name="resideo_prop_fields_settings[resideo_p_neighborhood_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_neighborhood_f_field']) && $options['resideo_p_neighborhood_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_city_field_render')): 
    function resideo_p_city_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_city_field]" name="resideo_prop_fields_settings[resideo_p_city_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_city_field']) && $options['resideo_p_city_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_city_r_field]" name="resideo_prop_fields_settings[resideo_p_city_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_city_r_field']) && $options['resideo_p_city_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Input/List
        $t_values = array(
            'input' => __('Input', 'resideo'),
            'list'  => __('List', 'resideo'),
        );

        $fields .= '
                    <td width="18%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_city_t_field]" name="resideo_prop_fields_settings[resideo_p_city_t_field]">';
        foreach ($t_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_city_t_field']) && $options['resideo_p_city_t_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_city_s_field]" name="resideo_prop_fields_settings[resideo_p_city_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_city_s_field']) && $options['resideo_p_city_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_city_f_field]" name="resideo_prop_fields_settings[resideo_p_city_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_city_f_field']) && $options['resideo_p_city_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_state_field_render')): 
    function resideo_p_state_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_state_field]" name="resideo_prop_fields_settings[resideo_p_state_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_state_field']) && $options['resideo_p_state_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_state_r_field]" name="resideo_prop_fields_settings[resideo_p_state_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_state_r_field']) && $options['resideo_p_state_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_state_s_field]" name="resideo_prop_fields_settings[resideo_p_state_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_state_s_field']) && $options['resideo_p_state_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_state_f_field]" name="resideo_prop_fields_settings[resideo_p_state_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_state_f_field']) && $options['resideo_p_state_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_zip_field_render')): 
    function resideo_p_zip_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_zip_field]" name="resideo_prop_fields_settings[resideo_p_zip_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_zip_field']) && $options['resideo_p_zip_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_zip_r_field]" name="resideo_prop_fields_settings[resideo_p_zip_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_zip_r_field']) && $options['resideo_p_zip_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_price_field_render')): 
    function resideo_p_price_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_price_field]" name="resideo_prop_fields_settings[resideo_p_price_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_price_field']) && $options['resideo_p_price_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_price_r_field]" name="resideo_prop_fields_settings[resideo_p_price_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '<option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_price_r_field']) && $options['resideo_p_price_r_field'] == $key) {
                $fields .= 'selected="selected"';
            }
            $fields .= '>' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_price_s_field]" name="resideo_prop_fields_settings[resideo_p_price_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_price_s_field']) && $options['resideo_p_price_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_price_f_field]" name="resideo_prop_fields_settings[resideo_p_price_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_price_f_field']) && $options['resideo_p_price_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_size_field_render')): 
    function resideo_p_size_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_size_field]" name="resideo_prop_fields_settings[resideo_p_size_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_size_field']) && $options['resideo_p_size_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_size_r_field]" name="resideo_prop_fields_settings[resideo_p_size_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_size_r_field']) && $options['resideo_p_size_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_size_f_field]" name="resideo_prop_fields_settings[resideo_p_size_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_size_f_field']) && $options['resideo_p_size_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_beds_field_render')): 
    function resideo_p_beds_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_beds_field]" name="resideo_prop_fields_settings[resideo_p_beds_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_beds_field']) && $options['resideo_p_beds_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_beds_r_field]" name="resideo_prop_fields_settings[resideo_p_beds_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_beds_r_field']) && $options['resideo_p_beds_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_beds_s_field]" name="resideo_prop_fields_settings[resideo_p_beds_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_beds_s_field']) && $options['resideo_p_beds_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_beds_f_field]" name="resideo_prop_fields_settings[resideo_p_beds_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_beds_f_field']) && $options['resideo_p_beds_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_baths_field_render')): 
    function resideo_p_baths_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_baths_field]" name="resideo_prop_fields_settings[resideo_p_baths_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_baths_field']) && $options['resideo_p_baths_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                        >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_baths_r_field]" name="resideo_prop_fields_settings[resideo_p_baths_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_baths_r_field']) && $options['resideo_p_baths_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_baths_s_field]" name="resideo_prop_fields_settings[resideo_p_baths_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_baths_s_field']) && $options['resideo_p_baths_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_baths_f_field]" name="resideo_prop_fields_settings[resideo_p_baths_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_baths_f_field']) && $options['resideo_p_baths_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_type_field_render')): 
    function resideo_p_type_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_type_field]" name="resideo_prop_fields_settings[resideo_p_type_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_type_field']) && $options['resideo_p_type_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_type_r_field]" name="resideo_prop_fields_settings[resideo_p_type_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_type_r_field']) && $options['resideo_p_type_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_type_s_field]" name="resideo_prop_fields_settings[resideo_p_type_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_type_s_field']) && $options['resideo_p_type_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_type_f_field]" name="resideo_prop_fields_settings[resideo_p_type_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_type_f_field']) && $options['resideo_p_type_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_status_field_render')): 
    function resideo_p_status_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_status_field]" name="resideo_prop_fields_settings[resideo_p_status_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_status_field']) && $options['resideo_p_status_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Not required/Required
        $r_values = array(
            'not_required' => __('Not required', 'resideo'),
            'required'     => __('Required', 'resideo'),
        );

        $fields .= '
                    <td width="16%" style="padding: 0">
                        <select id="resideo_prop_fields_settings[resideo_p_status_r_field]" name="resideo_prop_fields_settings[resideo_p_status_r_field]">';
        foreach ($r_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_status_r_field']) && $options['resideo_p_status_r_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                    <td width="18%" style="padding: 0">&nbsp;</td>';

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Search', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_status_s_field]" name="resideo_prop_fields_settings[resideo_p_status_s_field]">';
        foreach ($s_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_status_s_field']) && $options['resideo_p_status_s_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_status_f_field]" name="resideo_prop_fields_settings[resideo_p_status_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_status_f_field']) && $options['resideo_p_status_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_keywords_field_render')): 
    function resideo_p_keywords_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">&nbsp;</td>
                    <td width="16%" style="padding: 0">&nbsp;</td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_keywords_f_field]" name="resideo_prop_fields_settings[resideo_p_keywords_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_keywords_f_field']) && $options['resideo_p_keywords_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_amenities_field_render')): 
    function resideo_p_amenities_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Show in Search No/Yes
        $s_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields = '
            <table cellpading="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="16%" style="padding: 0">&nbsp;</td>
                    <td width="16%" style="padding: 0">&nbsp;</td>
                    <td width="18%" style="padding: 0">&nbsp;</td>
                    <td width="25%" style="padding: 0">&nbsp;</td>';

        // Show in Filter No/Yes
        $f_values = array(
            'no'  => __('No', 'resideo'),
            'yes' => __('Yes', 'resideo'),
        );

        $fields .= '
                    <td width="25%" style="padding: 0">' . __('Show in Filter', 'resideo') . '&nbsp;
                        <select id="resideo_prop_fields_settings[resideo_p_amenities_f_field]" name="resideo_prop_fields_settings[resideo_p_amenities_f_field]">';
        foreach ($f_values as $key => $value) {
            $fields .= '
                            <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_amenities_f_field']) && $options['resideo_p_amenities_f_field'] == $key) {
                $fields .= '
                                selected="selected"';
            }
            $fields .= '
                            >' . esc_html($value) . '</option>';
        }
        $fields .= '
                        </select>
                    </td>
                </tr>
            </table>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_calculator_field_render')): 
    function resideo_p_calculator_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <select id="resideo_prop_fields_settings[resideo_p_calculator_field]" name="resideo_prop_fields_settings[resideo_p_calculator_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_calculator_field']) && $options['resideo_p_calculator_field'] == $key) {
                $fields .= '
                    selected="selected"';
            }
            $fields .= '
                >' . esc_html($value) . '</option>';
        }
        $fields .= '
            </select>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_texes_field_render')): 
    function resideo_p_taxes_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <select id="resideo_prop_fields_settings[resideo_p_taxes_field]" name="resideo_prop_fields_settings[resideo_p_taxes_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_taxes_field']) && $options['resideo_p_taxes_field'] == $key) {
                $fields .= '
                    selected="selected"';
            }
            $fields .= '
                >' . esc_html($value) . '</option>';
        }
        $fields .= '
            </select>';

        print $fields;
    }
endif;

if (!function_exists('resideo_p_hoa_field_render')): 
    function resideo_p_hoa_field_render() { 
        $options = get_option('resideo_prop_fields_settings');

        // Disabled/Enabled
        $values = array(
            'disabled' => __('Disabled', 'resideo'),
            'enabled'  => __('Enabled', 'resideo'),
        );

        $fields = '
            <select id="resideo_prop_fields_settings[resideo_p_hoa_field]" name="resideo_prop_fields_settings[resideo_p_hoa_field]">';
        foreach ($values as $key => $value) {
            $fields .= '
                <option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_p_hoa_field']) && $options['resideo_p_hoa_field'] == $key) {
                $fields .= '
                    selected="selected"';
            }
            $fields .= '
                >' . esc_html($value) . '</option>';
        }
        $fields .= '
            </select>';

        print $fields;
    }
endif;
?>