<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_address')): 
    function resideo_admin_address() {
        add_settings_section('resideo_address_section', __('Property Address Format', 'resideo'), 'resideo_address_section_callback', 'resideo_address_settings');
    }
endif;

if (!function_exists('resideo_address_section_callback')):
    function resideo_address_section_callback() {
        $options = get_option('resideo_address_settings');

        $default_options = array(
            'street_number' => array(
                'name' => __('Street No', 'resideo'),
                'position' => 0
            ),
            'street' => array(
                'name' => __('Street Name', 'resideo'),
                'position' => 1
            ),
            'neighborhood' => array(
                'name' => __('Neighborhood', 'resideo'),
                'position' => 2
            ),
            'city' => array(
                'name' => __('City', 'resideo'),
                'position' => 3
            ),
            'state' => array(
                'name' => __('State', 'resideo'),
                'position' => 4
            ),
            'zip' => array(
                'name' => __('Zip Code', 'resideo'),
                'position' => 5
            )
        );

        print '
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>' . __('Address Info', 'resideo') . '</th>
                        <th align="right">' . __('Position', 'resideo') . '</th>
                    </tr>
                </thead>
                <tbody>';

        if (is_array($options)) {
            uasort($options, "resideo_compare_position");

            foreach ($options as $key => $value) {
                print '
                    <tr>
                        <td style="vertical-align: middle;"><input type="hidden" name="resideo_address_settings[' . $key . '][name]" value="' . $value['name'] . '">' . $value['name'] . '</td>
                        <td><input type="text" size="4" name="resideo_address_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>
                    </tr>';
            }
        } else {
            foreach ($default_options as $key => $value) {
                print '
                    <tr>
                        <td style="vertical-align: middle;"><input type="hidden" name="resideo_address_settings[' . $key . '][name]" value="' . $value['name'] . '">' . $value['name'] . '</td>
                        <td><input type="text" size="4" name="resideo_address_settings[' . $key . '][position]" value="' . $value['position'] . '"></td>
                    </tr>';
            }
        }

        print '
                </tbody>
            </table>';
    }
endif;
?>