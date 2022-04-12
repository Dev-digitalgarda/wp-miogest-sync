<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Resideo_Contact_Form_Widget extends WP_Widget {
    function __construct() {
        $widget_ops  = array('classname' => 'resideo_contact_form_sidebar', 'description' => __('Contact form', 'resideo'));
        $control_ops = array('id_base' => 'resideo_contact_form_widget');

        parent::__construct('resideo_contact_form_widget', __('Resideo Contact Form', 'resideo'), $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array(
            'title' => '',
            'email' => '',
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $display = '
            <p>
                <label for="' . esc_attr($this->get_field_id('title')) . '">' . __('Title', 'resideo') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($instance['title']) . '" />
            </p>
            <p>
                <label for="' . esc_attr($this->get_field_id('email')) . '">' . __('Email', 'resideo') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('email')) . '" name="' . esc_attr($this->get_field_name('email')) . '" value="' . esc_attr($instance['email']) . '" />
            </p>
        ';

        print $display;
    }


    function update($new_instance, $old_instance) {
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['email'] = sanitize_text_field($new_instance['email']);

        if (function_exists('icl_register_string')) {
            icl_register_string('resideo_contact_form_widget', 'resideo_contact_form_widget_title', sanitize_text_field($new_instance['title']));
            icl_register_string('resideo_contact_form_widget', 'resideo_contact_form_widget_email', sanitize_text_field($new_instance['email']));
        }

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $display = '';
        $title = apply_filters('widget_title', $instance['title']);

        print $before_widget;

        if ($title) {
            print $before_title . esc_html($title) . $after_title;
        }

        $contact_fields_settings = get_option('resideo_contact_fields_settings');

        $has_fields = false;
        if (is_array($contact_fields_settings)) {
            if (count($contact_fields_settings)) {
                $has_fields = true;

                $display .= '
                    <div class="contact-widget-form">
                        <div class="contact-widget-form-response"></div>
                        <input type="hidden" id="contact_widget_form_company_email" name="contact_widget_form_company_email" value="' . esc_attr($instance['email']) . '">
                        <div class="form-group">
                            <label for="contact_widget_form_email">' . __('Email', 'resideo') . '</label>
                            <input class="form-control" type="text" name="contact_widget_form_email" id="contact_widget_form_email">
                        </div>';

                uasort($contact_fields_settings, "resideo_compare_position");
                foreach ($contact_fields_settings as $key => $value) {
                    $is_optional = $value['mandatory'] == 'no' ? '(' . __('optional', 'resideo') . ')' : '';

                    switch ($value['type']) {
                        case 'text_input_field':
                            $display .= '
                                <div class="form-group">
                                    <label for="' . esc_attr($key) . '">' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '</label>
                                    <input type="text" data-type="text_input_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-widget-contact-field" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '" />
                                </div>';
                        break;
                        case 'textarea_field':
                            $display .= '
                                <div class="form-group">
                                    <label for="' . esc_attr($key) . '">' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '</label>
                                    <textarea data-type="textarea_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-widget-contact-field" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '"></textarea>
                                </div>';
                        break;
                        case 'select_field':
                            $list = explode(',', $value['list']);
                            $display .= '
                                <div class="form-group">
                                    <select data-type="select_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="custom-select pxp-js-widget-contact-field" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '">
                                        <option value="' . esc_attr('None', 'resideo') . '">' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '</option>';
                            for ($i = 0; $i < count($list); $i++) {
                                $display .= '
                                        <option value="' . esc_html($list[$i]) . '">' . esc_html($list[$i]) . '</option>';
                            }
                            $display .= '
                                    </select>
                                </div>';
                        break;
                        case 'checkbox_field':
                            $display .= '
                                <div class="form-group form-check">
                                    <input data-type="checkbox_field" type="checkbox" class="form-check-input pxp-js-widget-contact-field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '"> <label class="form-check-label" for="' . esc_attr($key) . '">' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '</label>
                                </div>';
                        break;
                        case 'date_field':
                            $display .= '
                                <div class="form-group">
                                    <label for="' . esc_attr($key) . '">' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '</label>
                                    <input type="text" data-type="date_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-widget-contact-field date-picker" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '" />
                                </div>';
                        break;
                    }
                }

                $display .= '
                        <a href="javascript:void(0);" class="btn contact-widget-form-send" data-custom="' . esc_attr($has_fields) . '">
                            <span class="contact-widget-form-send-text">' . __('Send Message', 'resideo') . '</span>
                            <span class="contact-widget-form-sending-text"><img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg') . '" class="pxp-loader pxp-is-btn" alt="..."> ' . __('Sending...', 'resideo') . '</span>
                        </a>
                    </div>
                ';
            }
        }

        if ($has_fields === false) {
            $display .= '
                <div class="contact-widget-form">
                    <div class="contact-widget-form-response"></div>
                    <input type="hidden" id="contact_widget_form_company_email" name="contact_widget_form_company_email" value="' . esc_attr($instance['email']) . '">
                    <div class="form-group">
                        <label for="contact_widget_form_name">' . __('Name', 'resideo') . '</label>
                        <input class="form-control" type="text" name="contact_widget_form_name" id="contact_widget_form_name">
                    </div>
                    <div class="form-group">
                        <label for="contact_widget_form_phone">' . __('Phone', 'resideo') . '</label>
                        <input class="form-control" type="text" name="contact_widget_form_phone" id="contact_widget_form_phone">
                    </div>
                    <div class="form-group">
                        <label for="contact_widget_form_email">' . __('Email', 'resideo') . '</label>
                        <input class="form-control" type="text" name="contact_widget_form_email" id="contact_widget_form_email">
                    </div>
                    <div class="form-group">
                        <label for="contact_widget_form_message">' . __('Message', 'resideo') . '</label>
                        <textarea class="form-control" type="text" name="contact_widget_form_message" id="contact_widget_form_message"></textarea>
                    </div>
                    <a href="javascript:void(0);" class="btn contact-widget-form-send" data-custom="' . esc_attr($has_fields) . '">
                        <span class="contact-widget-form-send-text">' . __('Send Message', 'resideo') . '</span>
                        <span class="contact-widget-form-sending-text"><img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg') . '" class="pxp-loader pxp-is-btn" alt="..."> ' . __('Sending...', 'resideo') . '</span>
                    </a>
                </div>';
        }

        wp_nonce_field('contact_form_widget_ajax_nonce', 'contact_widget_security', true, true);

        print $display;
        print $after_widget;
    }

}
?>