<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_search_properties_form')):
    function resideo_get_search_properties_form($status_type = 'dropdown') {
        $fields_no = 0;
        $main_fields = array();

        $search_submit = resideo_get_search_properties_link();

        $general_settings = get_option('resideo_general_settings');
        $fields_settings  = get_option('resideo_prop_fields_settings');

        $status_s        = isset($fields_settings['resideo_p_status_s_field']) ? $fields_settings['resideo_p_status_s_field'] : '';
        $address_s       = isset($fields_settings['resideo_p_address_s_field']) ? $fields_settings['resideo_p_address_s_field'] : '';
        $city_s          = isset($fields_settings['resideo_p_city_s_field']) ? $fields_settings['resideo_p_city_s_field'] : '';
        $neighborhood_s  = isset($fields_settings['resideo_p_neighborhood_s_field']) ? $fields_settings['resideo_p_neighborhood_s_field'] : '';
        $state_s         = isset($fields_settings['resideo_p_state_s_field']) ? $fields_settings['resideo_p_state_s_field'] : '';
        $type_s          = isset($fields_settings['resideo_p_type_s_field']) ? $fields_settings['resideo_p_type_s_field'] : '';
        $price_s         = isset($fields_settings['resideo_p_price_s_field']) ? $fields_settings['resideo_p_price_s_field'] : '';
        $beds_s          = isset($fields_settings['resideo_p_beds_s_field']) ? $fields_settings['resideo_p_beds_s_field'] : '';
        $baths_s         = isset($fields_settings['resideo_p_baths_s_field']) ? $fields_settings['resideo_p_baths_s_field'] : '';
        $address_type    = isset($fields_settings['resideo_p_address_t_field']) ? $fields_settings['resideo_p_address_t_field'] : '';

        $form_class = '';
        if ($status_type == 'tabs') {
            $form_class = 'pxp-has-tabs';
        }

        /**
         * Status field
         */
        if ($status_s == 'yes') {
            $main_fields[] = 'status';
            $fields_no++;
        }

        /**
         * Address field
         */
        if ($address_s == 'yes') {
            $main_fields[] = 'address';
            $fields_no++;
        }

        /**
         * City field
         */
        if ($city_s == 'yes') {
            $city_type = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';

            if (($address_type == 'input' && $address_s == 'yes') ||  $address_s == 'no' || $address_s == '' || $address_type == '') {
                $main_fields[] = 'city';
            }

            $fields_no++;
        }

        /**
         * Neighborhood field
         */
        if ($neighborhood_s == 'yes') {
            $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';

            if (($address_type == 'input' && $address_s == 'yes') ||  $address_s == 'no' || $address_s == '' || $address_type == '') {
                $main_fields[] = 'neighborhood';
            }

            $fields_no++;
        }

        /**
         * County/State field
         */
        if ($state_s == 'yes') {
            if (($address_type == 'input' && $address_s == 'yes') ||  $address_s == 'no' || $address_s == '' || $address_type == '') {
                $main_fields[] = 'state';
            }

            $fields_no++;
        }

        /**
         * Type field
         */
        if ($type_s == 'yes') {
            $main_fields[] = 'type';
            $fields_no++;
        }

        /**
         * Price field
         */
        if ($price_s == 'yes') {
            $main_fields[] = 'price';
            $fields_no++;
        }

        /**
         * Beds field
         */
        if ($beds_s == 'yes') {
            $main_fields[] = 'beds';
            $fields_no++;
        }

        /**
         * Baths field
         */
        if ($baths_s == 'yes') {
            $main_fields[] = 'baths';
            $fields_no++;
        }

        /**
         * Custom fields
         */
        $custom_fields_settings = get_option('resideo_fields_settings');

        if (is_array($custom_fields_settings)) {
            $custom_no = 0;

            foreach ($custom_fields_settings as $key => $value) {
                if ($value['search'] == 'yes') {
                    $custom_no++;
                    $fields_no++;
                }
            }

            if ($custom_no > 0) {
                $main_fields[] = 'custom';
            }
        }

        if (!function_exists('resideo_display_status_s')):
            function resideo_display_status_s() {
                $status_tax = array( 
                    'property_status'
                );
                $status_args = array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false
                ); 
                $status_terms = get_terms($status_tax, $status_args); ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <select class="custom-select" name="search_status">
                            <?php foreach ($status_terms as $status_term) { ?>
                                <option value="<?php echo esc_attr($status_term->term_id); ?>"><?php echo esc_html($status_term->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_status_tabs_s')):
            function resideo_display_status_tabs_s() {
                $status_tax = array( 
                    'property_status'
                );
                $status_args = array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false
                ); 
                $status_terms = get_terms($status_tax, $status_args); ?>

                <div class="pxp-hero-search-tabs">
                    <ul class="list-unstyled">
                        <?php $tabs_count = 0;
                        foreach ($status_terms as $status_term) { ?>
                            <li class="<?php if ($tabs_count == 0) { echo esc_attr('pxp-active'); } ?>">
                                <input type="radio" name="search_status" value="<?php echo esc_attr($status_term->term_id); ?>" <?php if ($tabs_count == 0) { echo esc_attr('checked="checked"'); } ?>><a href="javascript:void(0);"><?php echo esc_html($status_term->name); ?></a>
                            </li>
                            <?php $tabs_count++;
                        } ?>
                    </ul>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_address_s')):
            function resideo_display_address_s($address_type, $status_type = 'dropdown') { 
                $column_class = 'col-sm-10 col-md-7'; 
                if ($status_type == 'tabs') {
                    $column_class = 'col-sm-10 col-md-11';
                } ?>
                <div class="<?php echo esc_attr($column_class); ?>">
                    <div class="form-group">
                        <?php if($address_type == 'auto') { ?>
                            <input type="text" class="form-control" id="hero-search-address-auto" name="search_address" placeholder="<?php esc_attr_e('Enter address...', 'resideo'); ?>" autocomplete="off">
                            <input type="hidden" id="search_street_no_field" name="search_street_no" autocomplete="off">
                            <input type="hidden" id="search_street_field" name="search_street" autocomplete="off">
                            <input type="hidden" id="search_neighborhood_field" name="search_neighborhood" autocomplete="off">
                            <input type="hidden" id="search_city_field" name="search_city" autocomplete="off">
                            <input type="hidden" id="search_state_field" name="search_state" autocomplete="off">
                            <input type="hidden" id="search_zip_field" name="search_zip" autocomplete="off">
                        <?php } else { ?>
                            <input type="text" class="form-control" id="search_address" name="search_address" placeholder="<?php esc_attr_e('Enter address...', 'resideo'); ?>">
                        <?php } ?>
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_city_s')):
            function resideo_display_city_s($city_type) { ?>
                <div class="col-md-4">
                    <?php if ($city_type == 'list') {
                        $resideo_cities_settings = get_option('resideo_cities_settings'); ?>

                        <div class="form-group">
                            <select class="custom-select" name="search_city">
                                <option value=""><?php esc_html_e('City', 'resideo'); ?></option>
                                <?php if (is_array($resideo_cities_settings) && count($resideo_cities_settings) > 0) {
                                    uasort($resideo_cities_settings, "resideo_compare_position");

                                    foreach ($resideo_cities_settings as $key => $value) { ?>
                                        <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value['name']); ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    <?php } else { ?>
                        <div class="form-group">
                            <input class="form-control" type="text" name="search_city" id="search_city" placeholder="<?php esc_attr_e('City', 'resideo'); ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_neighborhood_s')):
            function resideo_display_neighborhood_s($neighborhood_type) { ?>
                <div class="col-md-4">
                    <?php if ($neighborhood_type == 'list') {
                        $resideo_neighborhoods_settings = get_option('resideo_neighborhoods_settings'); ?>

                        <div class="form-group">
                            <select class="custom-select" name="search_neighborhood">
                                <option value=""><?php esc_html_e('Neighborhood', 'resideo'); ?></option>
                                <?php if (is_array($resideo_neighborhoods_settings) && count($resideo_neighborhoods_settings) > 0) {
                                    uasort($resideo_neighborhoods_settings, "resideo_compare_position");

                                    foreach ($resideo_neighborhoods_settings as $key => $value) { ?>
                                        <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value['name']); ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    <?php } else { ?>
                        <div class="form-group">
                            <input class="form-control" type="text" name="search_neighborhood" id="search_neighborhood" placeholder="<?php esc_attr_e('Neighborhood', 'resideo'); ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_state_s')):
            function resideo_display_state_s() { ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-control" type="text" name="search_state" id="search_state" placeholder="<?php esc_attr_e('County/State', 'resideo'); ?>">
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_type_s')):
            function resideo_display_type_s() {
                $type_tax = array( 
                    'property_type'
                );
                $type_args = array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false
                ); 
                $type_terms = get_terms($type_tax, $type_args); ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <select class="custom-select" name="search_type">
                            <option value="0"><?php esc_html_e('Type', 'resideo'); ?></option>
                            <?php foreach ($type_terms as $type_term) { ?>
                                <option value="<?php echo esc_attr($type_term->term_id); ?>"><?php echo esc_html($type_term->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_price_s')):
            function resideo_display_price_s($general_settings) {
                $locale       = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
                $currency     = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
                $currency_pos = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
                $max_price    = isset($general_settings['resideo_max_price_field']) ? intval($general_settings['resideo_max_price_field']) : '';

                $i = $max_price;
                $price_array = array($max_price);

                if ($max_price != '') {
                    while ($i >= 200) {
                        $i = round($i / 2, -2);
                        array_unshift($price_array, $i);
                    }
                }

                setlocale(LC_MONETARY, $locale); ?>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <select class="custom-select" name="search_price_min" id="search_price_min">
                            <option value=""><?php esc_html_e('Min Price', 'resideo'); ?></option>
                            <?php foreach ($price_array as $price) {
                                if ($currency_pos == 'after') { ?>
                                    <option value="<?php echo esc_attr($price); ?>"><?php echo esc_html(money_format('%!.0i', $price)) . esc_html($currency); ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo esc_attr($price); ?>"><?php echo esc_html($currency) . esc_html(money_format('%!.0i', $price)); ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <select class="custom-select" name="search_price_max" id="search_price_max">
                            <option value=""><?php esc_html_e('Max Price', 'resideo'); ?></option>
                            <?php foreach ($price_array as $price) {
                                if ($currency_pos == 'after') { ?>
                                    <option value="<?php echo esc_attr($price); ?>"><?php echo esc_html(money_format('%!.0i', $price)) . esc_html($currency); ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo esc_attr($price); ?>"><?php echo esc_html($currency) . esc_html(money_format('%!.0i', $price)); ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_beds_s')):
            function resideo_display_beds_s() { ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="custom-select" name="search_beds" id="search_beds">
                            <option value="0"><?php esc_html_e('Beds', 'resideo'); ?></option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                        </select>
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_baths_s')):
            function resideo_display_baths_s() { ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="custom-select" name="search_baths" id="search_baths">
                            <option value="0"><?php esc_html_e('Baths', 'resideo'); ?></option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                        </select>
                    </div>
                </div>
            <?php }
        endif;

        if (!function_exists('resideo_display_custom_s')):
            function resideo_display_custom_s($custom_fields_settings) {
                uasort($custom_fields_settings, "resideo_compare_position");

                foreach ($custom_fields_settings as $key => $value) {
                    if ($value['search'] == 'yes') {
                        switch ($value['type']) {
                            case 'date_field': ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control date-picker" placeholder="<?php echo esc_attr($value['label']); ?>" />
                                    </div>
                                </div>
                                <?php break;
                            case 'list_field':
                                $list = explode(',', $value['list']); ?>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="<?php echo esc_attr($key); ?>" class="custom-select">
                                            <option value=""><?php echo esc_html($value['label']); ?></option>
                                            <?php for($i = 0; $i < count($list); $i++) { ?>
                                                <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($list[$i]); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php break;
                            case 'interval_field': ?>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <input type="text" name="<?php echo esc_attr($key); ?>_min" id="<?php echo esc_attr($key); ?>_min" class="form-control" placeholder="<?php echo esc_attr($value['label']) . ' ' . __('Min', 'resideo'); ?>" />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <input type="text" name="<?php echo esc_attr($key); ?>_max" id="<?php echo esc_attr($key); ?>_max" class="form-control" placeholder="<?php echo esc_attr($value['label']) . ' ' . __('Max', 'resideo'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php break;
                            default: ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control" placeholder="<?php echo esc_attr($value['label']); ?>" />
                                    </div>
                                </div>
                                <?php break;
                        } ?>
                        <input type="hidden" name="<?php echo esc_attr($key); ?>_comparison" id="<?php echo esc_attr($key); ?>_comparison" value="<?php echo esc_attr($value['comparison']); ?>" />
                    <?php }
                }
            }
        endif;

        if ($fields_no > 0) { ?>
            <form class="pxp-hero-search mt-4 <?php echo esc_attr($form_class); ?>" role="search" method="get" action="<?php echo esc_url($search_submit); ?>">
                <?php if (in_array('status', $main_fields)) {
                    if ($status_type == 'tabs') {
                        resideo_display_status_tabs_s();
                    }
                } ?>
                <div class="row">
                    <?php if (in_array('status', $main_fields)) {
                        if ($status_type != 'tabs') {
                            resideo_display_status_s();
                        }
                    }
                    if (in_array('address', $main_fields)) {
                        if ($status_type == 'tabs') {
                            resideo_display_address_s($address_type, 'tabs');
                        } else {
                            resideo_display_address_s($address_type, 'dropdown');
                        }
                    }
                    if (in_array('city', $main_fields)) {
                        resideo_display_city_s($city_type);
                    }
                    if (in_array('neighborhood', $main_fields)) {
                        resideo_display_neighborhood_s($neighborhood_type);
                    }
                    if (in_array('state', $main_fields)) {
                        resideo_display_state_s();
                    }
                    if (in_array('type', $main_fields)) {
                        resideo_display_type_s();
                    }
                    if (in_array('price', $main_fields)) {
                        resideo_display_price_s($general_settings);
                    }
                    if (in_array('beds', $main_fields)) {
                        resideo_display_beds_s();
                    }
                    if (in_array('baths', $main_fields)) {
                        resideo_display_baths_s();
                    }
                    if (in_array('custom', $main_fields)) {
                        resideo_display_custom_s($custom_fields_settings);
                    } ?>

                    <div class="col-sm-2 col-md-1">
                        <button class="pxp-hero-search-btn"><span class="fa fa-search"></span></button>
                    </div>
                </div>
            </form>
        <?php }
    }
endif;
?>