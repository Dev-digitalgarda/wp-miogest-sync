<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_authentication')): 
    function resideo_admin_authentication() {
        add_settings_section('resideo_authentication_section', __( 'Authentication', 'resideo' ), 'resideo_authentication_section_callback', 'resideo_authentication_settings');
        add_settings_field('resideo_user_registration_field', __( 'Enable User Registration', 'resideo' ), 'resideo_user_registration_field_render', 'resideo_authentication_settings', 'resideo_authentication_section');
        add_settings_field('resideo_agent_registration_field', __( 'Enable User Registration as Agent/Owner', 'resideo' ), 'resideo_agent_registration_field_render', 'resideo_authentication_settings', 'resideo_authentication_section');
        add_settings_field('resideo_terms_field', __( 'Terms and Conditions Page URL', 'resideo' ), 'resideo_terms_field_render', 'resideo_authentication_settings', 'resideo_authentication_section');
    }
endif;

if (!function_exists('resideo_authentication_section_callback')): 
    function resideo_authentication_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('resideo_user_registration_field_render')): 
    function resideo_user_registration_field_render() { 
        $options = get_option('resideo_authentication_settings'); ?>

        <input type="checkbox" name="resideo_authentication_settings[resideo_user_registration_field]" <?php if (isset($options['resideo_user_registration_field'])) { checked($options['resideo_user_registration_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_agent_registration_field_render')): 
    function resideo_agent_registration_field_render() { 
        $options = get_option('resideo_authentication_settings'); ?>

        <input type="checkbox" name="resideo_authentication_settings[resideo_agent_registration_field]" <?php if (isset($options['resideo_agent_registration_field'])) { checked($options['resideo_agent_registration_field'], 1); } ?> value="1">
        <?php $values = array(
            'optional'   => __('Optional', 'resideo'),
            'all_agents' => __('All Users Agents', 'resideo'),
            'all_owners' => __('All Users Owners', 'resideo'),
        );

        $fields = '&nbsp;&nbsp;&nbsp;<select id="resideo_authentication_settings[resideo_agent_default_field]" name="resideo_authentication_settings[resideo_agent_default_field]">';

        foreach ($values as $key => $value) {
            $fields .= '<option value="' . esc_attr($key) . '"';

            if (isset($options['resideo_agent_default_field']) && $options['resideo_agent_default_field'] == $key) {
                $fields .= 'selected="selected"';
            }

            $fields .= '>' . esc_html($value) . '</option>';
        }

        $fields .= '</select>';

        print $fields;
    }
endif;

if (!function_exists('resideo_terms_field_render')): 
    function resideo_terms_field_render() {
        $options = get_option('resideo_authentication_settings'); ?>

        <input type="text" size="40" name="resideo_authentication_settings[resideo_terms_field]" value="<?php if (isset($options['resideo_terms_field'])) { echo esc_url($options['resideo_terms_field']); } ?>" />
    <?php }
endif;
?>