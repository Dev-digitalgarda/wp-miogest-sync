<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Contact shortcode
 */
if (!function_exists('resideo_contact_shortcode')): 
    function resideo_contact_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $s_array = json_decode(urldecode($attrs['data_content']), true);

        $margin_class = $s_array['margin'] == 'yes' ? 'mt-100' : '';

        $image  = isset($s_array['image']) ? $s_array['image'] : '';
        if ($image != '') {
            $photo = wp_get_attachment_image_src($image, 'pxp-full');
            $photo_src = $photo[0];
        } else {
            $photo_src = '';
        }

        $text_color    = isset($s_array['text_color']) ? 'color: ' . $s_array['text_color'] : '';
        $form_title    = isset($s_array['form_title']) ? $s_array['form_title']: '';
        $form_subtitle = isset($s_array['form_subtitle']) ? $s_array['form_subtitle']: '';
        $form_email    = isset($s_array['form_email']) ? $s_array['form_email']: '';
        $form_position = isset($s_array['position']) ? $s_array['position']: 'right';

        $intro_column_class = 'order-1';
        $form_column_class = 'order-3';
        if ($form_position == 'left') {
            $intro_column_class = 'order-3';
            $form_column_class = 'order-1';
        }

        $nonce_field = wp_nonce_field('contact_section_form_ajax_nonce', 'contact_section_security', true, false);

        $return_string = 
            '<div class="pxp-contact-section pxp-cover pt-100 pb-100 ' . esc_attr($margin_class) . '" style="background-image: url(' . esc_url($photo_src) . ')">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xl-4 align-left ' . esc_attr($intro_column_class) . '">
                            <h2 class="pxp-section-h2" style="' . esc_attr($text_color) . '">' . esc_html($s_array['title']) . '</h2>
                            <p class="pxp-text-light" style="' . esc_attr($text_color) . '">' . esc_html($s_array['subtitle']) . '</p>
                        </div>
                        <div class="col-lg-1 col-xl-3 order-2">
                        </div>
                        <div class="col-lg-5 align-left ' . esc_attr($form_column_class) . '">
                            <div class="pxp-contact-section-form mt-5 mt-lg-0">
                                <h2 class="pxp-section-h2">' . esc_html($form_title) . '</h2>
                                <p>' . esc_html($form_subtitle) . '</p>
                                <div class="pxp-contact-section-form-response mt-4"></div>
                                <div class="mt-4">';
        $contact_fields_settings = get_option('resideo_contact_fields_settings');
        $has_fields = false;
        if (is_array($contact_fields_settings)) {
            if (count($contact_fields_settings)) {
                $has_fields = true;

                $return_string .= 
                                    '<div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-section-form-email" placeholder="' . __('Your email...', 'resideo') . '">
                                            </div>
                                        </div>';
                uasort($contact_fields_settings, "resideo_compare_position");
                foreach ($contact_fields_settings as $key => $value) {
                    $is_optional = $value['mandatory'] == 'no' ? '(' . __('optional', 'resideo') . ')' : '';

                    switch ($value['type']) {
                        case 'text_input_field':
                            $return_string .= 
                                        '<div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" data-type="text_input_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-contact-section-field" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '" placeholder="' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '" />
                                            </div>
                                        </div>';
                        break;
                        case 'textarea_field':
                            $return_string .= 
                                        '<div class="col-12">
                                            <div class="form-group">
                                                <textarea data-type="textarea_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-contact-section-field" rows="4" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '" placeholder="' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '"></textarea>
                                            </div>
                                        </div>';
                        break;
                        case 'select_field':
                            $list = explode(',', $value['list']);
                            $return_string .= 
                                        '<div class="col-sm-6">
                                            <div class="form-group">
                                                <select data-type="select_field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="custom-select pxp-js-contact-section-field" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '">
                                                    <option value="' . __('None', 'resideo') . '">' . esc_html($value['label']) . ' ' . esc_attr($is_optional) . '</option>';
                            for ($i = 0; $i < count($list); $i++) {
                                $return_string .= 
                                                        '<option value="' . esc_html($list[$i]) . '">' . esc_html($list[$i]) . '</option>';
                            }
                            $return_string .= 
                                               '</select>
                                            </div>
                                        </div>';
                        break;
                        case 'checkbox_field': 
                            $return_string .= 
                                        '<div class="col-12">
                                            <div class="form-group form-check">
                                                <input data-type="checkbox_field" type="checkbox" class="form-check-input pxp-js-contact-section-field" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '"> <label class="form-check-label" for="' . esc_attr($key) . '">' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '</label>
                                            </div>
                                        </div>';
                        break;
                        case 'date_field':
                            $return_string .= 
                                        '<div class="col-sm-6">
                                            <div class="form-group">
                                                <input data-type="date_field" type="text" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-contact-section-field date-picker" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '" placeholder="' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '" />
                                            </div>
                                        </div>';
                        break;
                        default:
                            $return_string .= 
                                        '<div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" data-type="text_input_field" name=" ' . esc_attr($key) . '" id="' . esc_attr($key) . '" class="form-control pxp-js-contact-section-field" data-mandatory="' . esc_attr($value['mandatory']) . '" data-label="' . esc_attr($value['label']) . '" placeholder="' . esc_attr($value['label']) . ' ' . esc_attr($is_optional) . '" />
                                            </div>
                                        </div>';
                        break;
                    }
                }
                $return_string .= 
                                    '</div>';
            }
        }
        if ($has_fields === false) {
            $return_string .= 
                                    '<div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-section-form-name" placeholder="' . __('Your name', 'resideo') . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-section-form-phone" placeholder="' . __('Your number', 'resideo') . '">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="pxp-contact-section-form-email" placeholder="' . __('Your email', 'resideo') . '">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" id="pxp-contact-section-form-message" rows="6" placeholder="' . __('Type your message...', 'resideo') . '"></textarea>
                                    </div>';
        }
        $return_string .= 
                                    '<input type="hidden" id="pxp-contact-section-form-company-email" value="' . esc_attr($form_email) . '">
                                    <a href="javascript:void(0);" class="btn pxp-contact-section-form-btn" data-custom="' . esc_attr($has_fields) . '">
                                        <span class="pxp-contact-section-form-btn-text">' . __('Send Message', 'resideo') . '</span>
                                        <span class="pxp-contact-section-form-btn-sending"><img src="' . esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg') . '" class="pxp-loader pxp-is-btn" alt="..."> ' . __('Sending...', 'resideo') . '</span>
                                    </a>' . $nonce_field;
        $return_string .= 
                                '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        return $return_string;
    }
endif;
?>