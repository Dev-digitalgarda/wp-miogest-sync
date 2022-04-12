<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Resideo_Search_Properties_Widget extends WP_Widget {
    function __construct() {
        $widget_ops  = array('classname' => 'resideo_search_properties_sidebar', 'description' => __('Properties quick search form', 'resideo'));
        $control_ops = array('id_base' => 'resideo_search_properties_widget');

        parent::__construct('resideo_search_properties_widget', __('Resideo Search Properties', 'resideo'), $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array(
            'title'        => '',
            'status'       => '0',
            'address'      => '0',
            'city'         => '0',
            'neighborhood' => '0',
            'state'        => '0',
            'type'         => '0',  
            'price'        => '0',
            'beds'         => '0',
            'baths'        => '0',
            'size'         => '0',
            'keywords'     => '0',
            'id'           => '0',
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $display = '
            <p>
                <label for="' . esc_attr($this->get_field_id('title')) . '">' . __('Title', 'resideo') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($instance['title']) . '" />
            </p>';

        /**
         * Status field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('status')) . '">' . __('Status', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('status')) . '" name="' . esc_attr($this->get_field_name('status')) . '">
                    <option value="0" ';
        if (esc_attr($instance['status']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['status']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Address field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('address')) . '">' . __('Address', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('address')) . '" name="' . esc_attr($this->get_field_name('address')) . '">
                    <option value="0" ';
        if (esc_attr($instance['address']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['address']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * City field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('city')) . '">' . __('City', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('city')) . '" name="' . esc_attr($this->get_field_name('city')) . '">
                    <option value="0" ';
        if (esc_attr($instance['city']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['city']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Neighborhood field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('neighborhood')) . '">' . __('Neighborhood', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('neighborhood')) . '" name="' . esc_attr($this->get_field_name('neighborhood')) . '">
                    <option value="0" ';
        if (esc_attr($instance['neighborhood']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['neighborhood']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * County/State field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('state')) . '">' . __('County/State', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('state')) . '" name="' . esc_attr($this->get_field_name('state')) . '">
                    <option value="0" ';
        if (esc_attr($instance['state']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['state']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Type field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('type')) . '">' . __('Type', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('type')) . '" name="' . esc_attr($this->get_field_name('type')) . '">
                    <option value="0" ';
        if (esc_attr($instance['type']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['type']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Price field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('price')) . '">' . __('Price', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('price')) . '" name="' . esc_attr($this->get_field_name('price')) . '">
                    <option value="0" ';
        if (esc_attr($instance['price']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['price']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Beds field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('beds')) . '">' . __('Beds', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('beds')) . '" name="' . esc_attr($this->get_field_name('beds')) . '">
                    <option value="0" ';
        if (esc_attr($instance['beds']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['beds']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Baths field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('baths')) . '">' . __('Baths', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('baths')) . '" name="' . esc_attr($this->get_field_name('baths')) . '">
                    <option value="0" ';
        if (esc_attr($instance['baths']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['baths']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Size field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('size')) . '">' . __('Size', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('size')) . '" name="' . esc_attr($this->get_field_name('size')) . '">
                    <option value="0" ';
        if (esc_attr($instance['size']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['size']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Keywords field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('keywords')) . '">' . __('Keywords', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('keywords')) . '" name="' . esc_attr($this->get_field_name('keywords')) . '">
                    <option value="0" ';
        if (esc_attr($instance['keywords']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['keywords']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        /**
         * Id field
         */
        $display .= '
            <p>
                <label for="' . esc_attr($this->get_field_id('id')) . '">' . __('ID', 'resideo') . ':</label>
                <select id="' . esc_attr($this->get_field_id('id')) . '" name="' . esc_attr($this->get_field_name('id')) . '">
                    <option value="0" ';
        if (esc_attr($instance['id']) == '0') {
            $display .= 'selected';
        }
        $display .= '>' . __('Disabled', 'resideo') . '</option>
                    <option value="1" ';
        if (esc_attr($instance['id']) == '1') {
            $display .= 'selected';
        }
        $display .= '>' . __('Enabled', 'resideo') . '</option>
                </select>
            </p>
        ';

        print $display;
    }


    function update($new_instance, $old_instance) {
        $instance                 = $old_instance;
        $instance['title']        = sanitize_text_field($new_instance['title']);
        $instance['status']       = sanitize_text_field($new_instance['status']);
        $instance['address']      = sanitize_text_field($new_instance['address']);
        $instance['city']         = sanitize_text_field($new_instance['city']);
        $instance['neighborhood'] = sanitize_text_field($new_instance['neighborhood']);
        $instance['state']        = sanitize_text_field($new_instance['state']);
        $instance['type']         = sanitize_text_field($new_instance['type']);
        $instance['price']        = sanitize_text_field($new_instance['price']);
        $instance['beds']         = sanitize_text_field($new_instance['beds']);
        $instance['baths']        = sanitize_text_field($new_instance['baths']);
        $instance['size']         = sanitize_text_field($new_instance['size']);
        $instance['keywords']     = sanitize_text_field($new_instance['keywords']);
        $instance['id']           = sanitize_text_field($new_instance['id']);

        if (function_exists('icl_register_string')) {
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_title', sanitize_text_field($new_instance['title']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_status', sanitize_text_field($new_instance['status']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_address', sanitize_text_field($new_instance['address']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_city', sanitize_text_field($new_instance['city']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_neighborhood', sanitize_text_field($new_instance['neighborhood']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_state', sanitize_text_field($new_instance['state']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_type', sanitize_text_field($new_instance['type']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_price', sanitize_text_field($new_instance['price']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_beds', sanitize_text_field($new_instance['beds']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_baths', sanitize_text_field($new_instance['baths']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_size', sanitize_text_field($new_instance['size']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_keywords', sanitize_text_field($new_instance['keywords']));
            icl_register_string('resideo_featured_properties_widget', 'resideo_featured_properties_widget_id', sanitize_text_field($new_instance['id']));
        }

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $display = '';
        $title = apply_filters('widget_title', $instance['title']);

        print $before_widget;

        if($title) {
            print $before_title . esc_html($title) . $after_title;
        }

        $search_submit = resideo_get_search_properties_link();

        $fields_settings  = get_option('resideo_prop_fields_settings');
        $address_type     = isset($fields_settings['resideo_p_address_t_field']) ? $fields_settings['resideo_p_address_t_field'] : '';
        $general_settings = get_option('resideo_general_settings');

        $search_fields = '';

        /**
         * Status field
         */
        if (isset($instance['status']) &&  $instance['status'] == '1') {
            $status_tax = array( 
                'property_status'
            );
            $status_args = array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            ); 
            $status_terms = get_terms($status_tax, $status_args);

            $search_fields .= '
                <div class="form-group">
                    <label for="search_status">' . __('Status', 'resideo') . '</label>
                    <select id="search_status" name="search_status" class="custom-select">
                        <option value="0">' . __('All', 'resideo') . '</option>';
            foreach ($status_terms as $status_term) {
                $search_fields .= '
                        <option value="' . esc_attr($status_term->term_id) . '">' . esc_html($status_term->name) . '</option>';
            }
            $search_fields .= '
                    </select>
                </div>';
        }

        /**
         * Address field
         */
        if (isset($instance['address']) &&  $instance['address'] == '1') {
            $search_fields .= '
                <div class="form-group">';
            if ($address_type == 'auto') {
                $search_fields .= '
                    <label for="widget-search-address-auto">' . __('City/Neighborhood/Address', 'resideo') . '</label>
                    <input type="text" class="form-control" id="widget-search-address-auto" name="search_address" autocomplete="off">
                    <input type="hidden" id="search_street_no" name="search_street_no" autocomplete="off">
                    <input type="hidden" id="search_street" name="search_street" autocomplete="off">
                    <input type="hidden" id="search_neighborhood" name="search_neighborhood" autocomplete="off">
                    <input type="hidden" id="search_city" name="search_city" autocomplete="off">
                    <input type="hidden" id="search_state" name="search_state" autocomplete="off">
                    <input type="hidden" id="search_zip" name="search_zip" autocomplete="off">';
            } else {
                $search_fields .= '
                    <label for="widget-search-address-auto">' . __('Address', 'resideo') . '</label>
                    <input type="text" class="form-control" id="search_address" name="search_address">';
            }
            $search_fields .= '
                </div>';
        }

        /**
         * City field
         */
        if (isset($instance['city']) &&  $instance['city'] == '1') {
            $city_type = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';

            if (($address_type == 'input' && isset($instance['address']) && $instance['address'] == '1') || !isset($instance['address']) ||  $instance['address'] == '0' || $address_type == '') {
                $search_fields .= '
                    <div class="form-group">
                        <label for="search_city">' . __('City', 'resido') . '</label>';
                if ($city_type == 'list') {
                    $resideo_cities_settings = get_option('resideo_cities_settings');

                    $search_fields .= '
                        <select id="search_city" name="search_city" class="custom-select">
                            <option value="">' . __('All', 'resideo') . '</opion>';
                    if (is_array($resideo_cities_settings) && count($resideo_cities_settings) > 0) {
                        uasort($resideo_cities_settings, "resideo_compare_position");

                        foreach ($resideo_cities_settings as $key => $value) {
                            $search_fields .= '
                            <option value="' . esc_attr($key) . '">' . esc_html($value['name']) . '</option>';
                        }
                    }
                    $search_fields .= '
                        </select>';
                } else {
                    $search_fields .= '
                        <input class="form-control" type="text" name="search_city" id="search_city">';
                }
                $search_fields .= '
                    </div>';
            }
        }

        /**
         * Neighborhood field
         */
        if (isset($instance['neighborhood']) &&  $instance['neighborhood'] == '1') {
            $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';

            if (($address_type == 'input' && isset($instance['address']) && $instance['address'] == '1') || !isset($instance['address']) ||  $instance['address'] == '0' || $address_type == '') {
                $search_fields .= '
                    <div class="form-group">
                        <label for="search_city">' . __('Neighborhood', 'resido') . '</label>';
                if ($neighborhood_type == 'list') {
                    $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings');

                    $search_fields .= '
                        <select id="search_neighborhood" name="search_neighborhood" class="custom-select">
                            <option value="">' . __('All', 'resideo') . '</opion>';
                    if (is_array($resideo_neighborhoods_settings) && count($resideo_neighborhoods_settings) > 0) {
                        uasort($resideo_neighborhoods_settings, "resideo_compare_position");

                        foreach ($resideo_neighborhoods_settings as $key => $value) {
                            $search_fields .= '
                            <option value="' . esc_attr($key) . '">' . esc_html($value['name']) . '</option>';
                        }
                    }
                    $search_fields .= '
                        </select>';
                } else {
                    $search_fields .= '
                        <input class="form-control" type="text" name="search_neighborhood" id="search_neighborhood">';
                }
                $search_fields .= '
                    </div>';
            }
        }

        /**
         * County/State field
         */
        if (isset($instance['state']) &&  $instance['state'] == '1') {
            if (($address_type == 'input' && isset($instance['address']) && $instance['address'] == '1') || !isset($instance['address']) ||  $instance['address'] == '0' || $address_type == '') {
                $search_fields .=  '
                    <div class="form-group">
                        <label for="search_state">' . __('County/State', 'resideo') . '</label>
                        <input class="form-control" type="text" name="search_state" id="search_state">
                    </div>';
            }
        }

        /**
         * Type field
         */
        if (isset($instance['type']) &&  $instance['type'] == '1') {
            $type_tax = array( 
                'property_type'
            );
            $type_args = array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            ); 
            $type_terms = get_terms($type_tax, $type_args);

            $search_fields .= '
                <div class="form-group">
                    <label for="search_type">' . __('Type', 'resideo') . '</label>
                    <select id="search_type" name="search_type" class="custom-select">
                        <option value="0">' . __('All', 'resideo') . '</option>';
            foreach ($type_terms as $type_term) {
                $search_fields .= '
                        <option value="' . esc_attr($type_term->term_id) . '">' . esc_html($type_term->name) . '</option>';
            }
            $search_fields .= '
                    </select>
                </div>';
        }

        /**
         * Price field
         */
        if (isset($instance['price']) &&  $instance['price'] == '1') {
            $locale       = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
            $currency     = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
            $currency_pos = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
            $max_price    = isset($general_settings['resideo_max_price_field']) ? intval($general_settings['resideo_max_price_field']) : '';

            $i = $max_price;
            $price_list = '';
            $price_array = array($max_price);

            if ($max_price != '') {
                while($i >= 200) {
                    $i = round($i / 2, -2);
                    array_unshift($price_array, $i);
                }
            }

            setlocale(LC_MONETARY, $locale);

            foreach ($price_array as $price) {
                if ($currency_pos == 'after') {
                    $price_list .= '<option value="' . esc_attr($price) . '">' . esc_html(money_format('%!.0i', $price)) . esc_html($currency) . '</option>';
                } else {
                    $price_list .= '<option value="' . esc_attr($price) . '">' . esc_html($currency) . esc_html(money_format('%!.0i', $price)) . '</option>';
                }
            }
            
            $search_fields .= '
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label for="search_price_min">' . __('Min Price', 'resideo') . '</label>
                            <select id="search_price_min" name="search_price_min" class="custom-select">
                                <option value="">' . __('No Min', 'resideo') . '</option>
                                ' . $price_list . '
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="search_price_max">' . __('Max Price', 'resideo') . '</label>
                            <select id="search_price_max" name="search_price_max" class="custom-select">
                                <option value="">' . __('No Max', 'resideo') . '</option>
                                ' . $price_list . '
                            </select>
                        </div>
                    </div>
                </div>';
        }

        /**
         * Beds and Baths fields
         */
        $search_fields .= '
            <div class="form-row">';
        if (isset($instance['beds']) &&  $instance['beds'] == '1') {
            $search_fields .= '
                <div class="col">
                    <div class="form-group">
                        <label for="search_beds">' . __('Beds', 'resideo') . '</label>
                        <select id="search_beds" name="search_beds" class="custom-select">
                            <option value="0">0+</option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                        </select>
                    </div>
                </div>';
        }
        if (isset($instance['baths']) &&  $instance['baths'] == '1') {
            $search_fields .= '
                <div class="col">
                    <div class="form-group">
                        <label for="search_baths">' . __('Baths', 'resideo') . '</label>
                        <select id="search_baths" name="search_baths" class="custom-select">
                            <option value="0">0+</option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                        </select>
                    </div>
                </div>';
        }
        $search_fields .= '
            </div>';

        /**
         * Size field
         */
        if (isset($instance['size']) &&  $instance['size'] == '1') {
            $unit = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';

            $search_fields .= '
                <div class="form-row">
                    <div class="col">
                        <label for="search_size_min">' . __('Min Size', 'resideo') . '</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control" id="search_size_min" name="search_size_min">
                            <div class="input-group-append">
                                <span class="input-group-text">' . esc_html($unit) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <label for="search_size_max">' . __('Max Size', 'resideo') . '</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control" id="search_size_max" name="search_size_max">
                            <div class="input-group-append">
                                <span class="input-group-text">' . esc_html($unit) . '</span>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        /**
         * Keywords field
         */
        if (isset($instance['keywords']) &&  $instance['keywords'] == '1') {
            $search_fields .= '
                <div class="form-group">
                    <label for="search_keywords">' . __('Keywords', 'resideo') . '</label>
                    <input class="form-control" type="text" name="search_keywords" id="search_keywords">
                </div>';
        }

        /**
         * Property ID field
         */
        if (isset($instance['id']) &&  $instance['id'] == '1') {
            $search_fields .= '
                <div class="form-group">
                    <label for="search_id">' . __('Property ID', 'resideo') . '</label>
                    <input class="form-control" type="text" name="search_id" id="search_id">
                </div>';
        }

        $display .= '
            <form id="search-property-form-widget" role="search" method="get" action="' . esc_url($search_submit) . '">
                <input type="hidden" name="sort" id="sort" value="newest" />
                ' . $search_fields . '
                <input type="submit" id="search-property-widget-submit" value="' . __('Search', 'resideo') . '">
            </form>';

        print $display;
        print $after_widget;
    }
}
?>