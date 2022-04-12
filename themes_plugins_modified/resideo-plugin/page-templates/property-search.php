<?php
/*
Template Name: Property Search
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$template = get_post_meta($post->ID, 'page_template_type', true);
$listing_type = get_post_meta($post->ID, 'page_listing_type', true);
$no_map = false;
$content_class = '';
$wrapper_class = '';
$column_class = 'col-sm-6 col-lg-12 col-xl-6 col-xxxl-4';
$has_sidebar = false;
$sidebar_class = '';
$container_class = 'container';

$list_col_image_class = 'col-12 col-sm-6 col-md-4 col-lg-12 col-xl-6 col-xxl-4';
$list_col_details_class = 'col-8 col-sm-6 col-lg-8 col-xl-6';
$list_col_price_class = 'col-4 col-sm-12 col-md-2 col-lg-4 col-xl-12 col-xxl-2';
$list_title_margin_class = 'mt-2 mt-md-3 mt-lg-2 mt-xl-3';
$list_features_margin_class = 'mt-3 mt-md-5 mt-lg-3 mt-xl-5';

switch ($template) {
    case 'half_map_left':
        $map_class = 'pxp-map-side pxp-map-left pxp-half';
        $list_class = 'pxp-content-side pxp-content-right pxp-half';
        $content_class = 'pxp-full-height';
        break;
    case 'half_map_right':
        $map_class = 'pxp-map-side pxp-map-right pxp-half';
        $list_class = 'pxp-content-side pxp-content-left pxp-half';
        $content_class = 'pxp-full-height';
        break;
    case 'no_map':
        $no_map = true;
        $list_class = 'pxp-no-map';
        $wrapper_class = 'mt-100';
        $column_class = 'col-sm-12 col-md-6 col-lg-4';
        $list_col_image_class = 'col-12 col-sm-6 col-md-4';
        $list_col_details_class = 'col-8 col-sm-6';
        $list_col_price_class = 'col-4 col-sm-12 col-md-2';
        $list_title_margin_class = 'mt-2 mt-md-3';
        $list_features_margin_class = 'mt-3 mt-md-5';
        break;
    case 'sidebar_left':
        $no_map = true;
        $has_sidebar = true;
        $list_class = 'pxp-no-map';
        $wrapper_class = 'mt-100';
        $column_class = 'col-sm-12 col-md-6 col-xl-4';
        $sidebar_class = 'order-first';
        $container_class = '';
        break;
    case 'sidebar_right':
        $no_map = true;
        $has_sidebar = true;
        $list_class = 'pxp-no-map';
        $wrapper_class = 'mt-100';
        $column_class = 'col-sm-12 col-md-6 col-xl-4';
        $container_class = '';
        $list_col_image_class = 'col-12 col-sm-4';
        $list_col_details_class = 'col-8 col-sm-6';
        $list_col_price_class = 'col-4 col-sm-12 col-md-2';
        $list_title_margin_class = 'mt-2 mt-md-3';
        $list_features_margin_class = 'mt-3 mt-md-5';
        break;
    default:
        $map_class = 'pxp-map-side pxp-map-right pxp-half';
        $list_class = 'pxp-content-side pxp-content-left pxp-half';
        $content_class = 'pxp-full-height';
        $list_col_image_class = 'col-12 col-sm-4';
        $list_col_details_class = 'col-8 col-sm-6';
        $list_col_price_class = 'col-4 col-sm-12 col-md-2';
        $list_title_margin_class = 'mt-2 mt-md-3';
        $list_features_margin_class = 'mt-3 mt-md-5';
        break;
}

$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';
$searched_posts = resideo_search_properties();
$total_p = $searched_posts->found_posts;

$fields_settings = get_option('resideo_prop_fields_settings');
$p_price         = isset($fields_settings['resideo_p_price_field']) ? $fields_settings['resideo_p_price_field'] : '';
$p_beds          = isset($fields_settings['resideo_p_beds_field']) ? $fields_settings['resideo_p_beds_field'] : '';
$p_baths         = isset($fields_settings['resideo_p_baths_field']) ? $fields_settings['resideo_p_baths_field'] : '';
$p_size          = isset($fields_settings['resideo_p_size_field']) ? $fields_settings['resideo_p_size_field'] : '';

$appearance_settings = get_option('resideo_appearance_settings');
$general_settings = get_option('resideo_general_settings');


$fields_settings   = get_option('resideo_prop_fields_settings');
$neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
$city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
$neighborhoods     = get_option('resideo_neighborhoods_settings');
$cities            = get_option('resideo_cities_settings');
$address_settings = get_option('resideo_address_settings'); ?>

<div class="pxp-content <?php echo esc_attr($content_class); ?>">
    <?php if ($no_map === false) { ?>
        <div class="<?php echo esc_attr($map_class); ?>">
            <div id="results-map">
                <div class="pxp-map-placeholder"><img src="<?php print esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader" alt="..."><br><?php esc_html_e('Loading properties', 'resideo'); ?></div>
            </div>
            <a href="javascript:void(0);" class="pxp-list-toggle"><span class="fa fa-list"></span> <?php esc_html_e('Show list', 'resideo'); ?></a>
            <?php wp_nonce_field('results_map_ajax_nonce', 'resultsMapSecurity', true); ?>
        </div>
    <?php } ?>

    <div class="<?php echo esc_attr($list_class); ?>">
        <div class="pxp-content-side-wrapper <?php echo esc_attr($wrapper_class); ?>">
            <?php if ($no_map === true) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-7">
                            <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>
                        </div>
                    </div>
                </div>
                <?php }

            if (function_exists('resideo_get_filter_properties_form') && $has_sidebar === false) {
                if ($no_map === true) { ?>
                    <div class="mt-4 mt-md-5">
                        <div class="container">
                        <?php }

                    resideo_get_filter_properties_form();

                    if ($no_map === true) { ?>
                        </div>
                    </div>
                <?php }
                }

                if ($has_sidebar === true) { ?>
                <div class="container mt-4 mt-md-5">
                    <div class="row">
                        <div class="col-sm-12 col-lg-9">
                        <?php }

                    if ($no_map === true) { ?>
                            <div class="<?php echo esc_attr($container_class); ?>">
                            <?php } ?>
                            <div class="row pb-4">
                                <div class="col-md-6 col-lg-12 col-xl-4">
                                    <h2 class="pxp-content-side-h2">
                                        <?php $per_p_field = isset($appearance_settings['resideo_properties_per_page_field']) ? $appearance_settings['resideo_properties_per_page_field'] : '';
                                        $per_p             = $per_p_field != '' ? intval($per_p_field) : 10;
                                        $page_no           = get_query_var('paged') ? get_query_var('paged') : 1;

                                        $from_p = ($page_no == 1) ? 1 : $per_p * ($page_no - 1) + 1;
                                        $to_p   = ($total_p - ($page_no - 1) * $per_p > $per_p) ? $per_p * $page_no : $total_p;

                                        echo esc_html($from_p) . ' - ' . esc_html($to_p) . ' ' . __('of', 'resideo') . ' ' . esc_html($total_p) . ' ' . __('Results', 'resideo'); ?>
                                    </h2>
                                </div>
                                <div class="col-md-6 col-lg-12 col-xl-8">
                                    <div class="pxp-sort-form form-inline float-right">
                                        <?php if (function_exists('resideo_get_save_search_modal')) { ?>
                                            <div class="form-group">
                                                <a href="javascript:void(0);" class="pxp-save-search-btn"><?php esc_html_e('Save Search', 'resideo'); ?></a>
                                            </div>
                                        <?php } ?>
                                        <div class="d-block d-sm-none w-100"></div>
                                        <div class="form-group pxp-sort-select">
                                            <select class="custom-select" id="pxp-sort-results">
                                                <option value="newest" <?php if (!$sort || $sort == '' || $sort == 'newest') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?php esc_html_e('Default Sort', 'resideo'); ?></option>
                                                <?php if ($p_price != '' && $p_price == 'enabled') { ?>
                                                    <option value="price_lo" <?php if ($sort && $sort != '' && $sort == 'price_lo') {
                                                                                    echo 'selected="selected"';
                                                                                } ?>><?php esc_html_e('Price (Lo-Hi)', 'resideo'); ?></option>
                                                    <option value="price_hi" <?php if ($sort && $sort != '' && $sort == 'price_hi') {
                                                                                    echo 'selected="selected"';
                                                                                } ?>><?php esc_html_e('Price (Hi-Lo)', 'resideo'); ?></option>
                                                <?php }
                                                if ($p_beds != '' && $p_beds == 'enabled') { ?>
                                                    <option value="beds" <?php if ($sort && $sort != '' && $sort == 'beds') {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php esc_html_e('Beds', 'resideo'); ?></option>
                                                <?php }
                                                if ($p_baths != '' && $p_baths == 'enabled') { ?>
                                                    <option value="baths" <?php if ($sort && $sort != '' && $sort == 'baths') {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php esc_html_e('Baths', 'resideo'); ?></option>
                                                <?php }
                                                if ($p_size != '' && $p_size == 'enabled') { ?>
                                                    <option value="size" <?php if ($sort && $sort != '' && $sort == 'size') {
                                                                                echo 'selected="selected"';
                                                                            } ?>><?php esc_html_e('Size', 'resideo'); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php if ($no_map === false) { ?>
                                            <div class="form-group d-flex">
                                                <a role="button" class="pxp-map-toggle"><span class="fa fa-map-o"></span></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($no_map === true) { ?>
                            </div>
                        <?php } ?>

                        <?php if ($no_map === true) { ?>
                            <div class="<?php echo esc_attr($container_class); ?>">
                            <?php } ?>
                            <div class="row pxp-results">
                                <?php $unit   = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
                                $currency     = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
                                $currency_pos = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
                                $locale       = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
                                $decimals     = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
                                $beds_label   = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
                                $baths_label  = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
                                setlocale(LC_MONETARY, $locale);

                                while ($searched_posts->have_posts()) {
                                    $searched_posts->the_post();

                                    $prop_id = get_the_ID();
                                    $p_link  = get_permalink($prop_id);

                                    $gallery = get_post_meta($prop_id, 'property_gallery', true);
                                    $photos  = array_slice(explode(',', $gallery), 1);

                                    $p_price       = get_post_meta($prop_id, 'property_price', true);
                                    $p_price_reserved       = get_post_meta($prop_id, 'property_price_reserved', true) == 'true';
                                    $p_price_label = get_post_meta($prop_id, 'property_price_label', true);

                                    if (is_numeric($p_price)) {
                                        if ($decimals == '1') {
                                            $p_price = money_format('%!i', $p_price);
                                        } else {
                                            $p_price = money_format('%!.0i', $p_price);
                                        }
                                    } else {
                                        $p_price_label = '';
                                        $currency = '';
                                    }

                                    $p_beds  = get_post_meta($prop_id, 'property_beds', true);
                                    $p_baths = get_post_meta($prop_id, 'property_baths', true);
                                    $p_size  = get_post_meta($prop_id, 'property_size', true);

                                    $status = wp_get_post_terms($prop_id, 'property_status');
                                    $type   = wp_get_post_terms($prop_id, 'property_type');

                                    $p_featured = get_post_meta($prop_id, 'property_featured', true);
                                    $featured_class = ($p_featured == '1') ? 'pxp-is-featured' : '';

                                    switch ($listing_type) {
                                        case 'grid_1': ?>
                                            <div class="<?php echo esc_attr($column_class); ?>">
                                                <a href="<?php echo esc_url($p_link); ?>" class="pxp-results-card pxp-results-card-1 rounded-lg <?php echo esc_attr($featured_class); ?>" data-prop="<?php echo esc_attr($prop_id); ?>">
                                                    <div id="card-carousel-<?php echo esc_attr($prop_id); ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                                                        <div class="carousel-inner">
                                                            <?php if ($photos[0] != '') {
                                                                for ($i = 0; $i < count($photos); $i++) {
                                                                    // $p_photo = wp_get_attachment_image_src($photos[$i], 'pxp-gallery'); 
                                                            ?>
                                                                    <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>" style="background-image: url( https://img.miogest.com/<?php echo $photos[$i]; ?>);"></div>
                                                                <?php }
                                                            } else {
                                                                $p_photo = 'https://gardahomeproject.it/wp-content/uploads/2021/07/placeholder500.jpg'; ?>
                                                                <div class="carousel-item active" style="background-image: url(<?php echo esc_url($p_photo); ?>);"></div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($photos[0] != '' && count($photos) > 1) { ?>
                                                            <span class="carousel-control-prev" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="prev">
                                                                <span class="fa fa-angle-left" aria-hidden="true"></span>
                                                            </span>
                                                            <span class="carousel-control-next" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="next">
                                                                <span class="fa fa-angle-right" aria-hidden="true"></span>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pxp-results-card-1-gradient"></div>
                                                    <div class="pxp-results-card-1-details">
                                                        <div class="pxp-results-card-1-details-title"><?php the_title(); ?></div>
                                                        <div class="pxp-results-card-1-details-price">
                                                            <?php
                                                            if ($currency_pos == 'before') {
                                                                $price_str = esc_html($currency) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            } else {
                                                                $price_str = esc_html($p_price) . esc_html($currency) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            }

                                                            if ($p_price_reserved) {
                                                                $price_str = 'Prezzo riservato' . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            }
                                                            echo $price_str; ?>
                                                        </div>
                                                    </div>
                                                    <div class="pxp-results-card-1-features">
                                                        <span>
                                                            <?php if ($p_beds != '') {
                                                                echo esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                            }
                                                            if ($p_baths != '') {
                                                                echo esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                            }
                                                            if ($p_size != '') {
                                                                echo esc_html($p_size) . ' ' . esc_html($unit);
                                                            } ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($p_featured == '1') { ?>
                                                        <div class="pxp-results-card-1-featured-label"><?php esc_html_e('Featured', 'resideo'); ?></div>
                                                    <?php } ?>
                                                </a>
                                            </div>
                                        <?php break;
                                        case 'grid_2': ?>
                                            <div class="<?php echo esc_attr($column_class); ?>">
                                                <a href="<?php echo esc_url($p_link); ?>" class="pxp-results-card pxp-results-card-2 <?php echo esc_attr($featured_class); ?>" data-prop="<?php echo esc_attr($prop_id); ?>">
                                                    <div id="card-carousel-<?php echo esc_attr($prop_id); ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                                                        <div class="carousel-inner rounded-lg">
                                                            <?php if ($photos[0] != '') {
                                                                for ($i = 0; $i < count($photos); $i++) {
                                                                    // $p_photo = wp_get_attachment_image_src($photos[$i], 'pxp-gallery'); 
                                                            ?>
                                                                    <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>" style="background-image: url( https://img.miogest.com/<?php echo $photos[$i]; ?>);"></div>
                                                                <?php }
                                                            } else {
                                                                $p_photo = 'https://gardahomeproject.it/wp-content/uploads/2021/07/placeholder500.jpg'; ?>
                                                                <div class="carousel-item active" style="background-image: url(<?php echo esc_url($p_photo); ?>);"></div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($photos[0] != '' && count($photos) > 1) { ?>
                                                            <span class="carousel-control-prev" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="prev">
                                                                <span class="fa fa-angle-left" aria-hidden="true"></span>
                                                            </span>
                                                            <span class="carousel-control-next" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="next">
                                                                <span class="fa fa-angle-right" aria-hidden="true"></span>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($p_featured == '1') { ?>
                                                        <div class="pxp-results-card-2-featured-label"><?php esc_html_e('Featured', 'resideo'); ?></div>
                                                    <?php } ?>
                                                    <div class="pxp-results-card-2-details">
                                                        <div class="pxp-results-card-2-details-title"><?php the_title(); ?></div>
                                                        <div class="pxp-results-card-2-features">
                                                            <span>
                                                                <?php if ($p_beds != '') {
                                                                    echo esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                                }
                                                                if ($p_baths != '') {
                                                                    echo esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                                }
                                                                if ($p_size != '') {
                                                                    echo esc_html($p_size) . ' ' . esc_html($unit);
                                                                } ?>
                                                            </span>
                                                        </div>
                                                        <div class="pxp-results-card-2-details-price">
                                                            <?php
                                                            if ($currency_pos == 'before') {
                                                                $price_str = esc_html($currency) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            } else {
                                                                $price_str = esc_html($p_price) . esc_html($currency) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            }

                                                            if ($p_price_reserved) {
                                                                $price_str = 'Prezzo riservato' . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            }
                                                            echo $price_str; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php break;
                                        case 'grid_3': ?>
                                            <div class="<?php echo esc_attr($column_class); ?>">
                                                <a href="<?php echo esc_url($p_link); ?>" class="pxp-results-card pxp-results-card-3 <?php echo esc_attr($featured_class); ?>" data-prop="<?php echo esc_attr($prop_id); ?>">
                                                    <div id="card-carousel-<?php echo esc_attr($prop_id); ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                                                        <div class="carousel-inner rounded-lg">
                                                            <?php if ($photos[0] != '') {
                                                                for ($i = 0; $i < count($photos); $i++) {
                                                                    // $p_photo = wp_get_attachment_image_src($photos[$i], 'pxp-gallery'); 
                                                            ?>
                                                                    <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>" style="background-image: url( https://img.miogest.com/<?php echo $photos[$i]; ?>);"></div>
                                                                <?php }
                                                            } else {
                                                                $p_photo = 'https://gardahomeproject.it/wp-content/uploads/2021/07/placeholder500.jpg'; ?>
                                                                <div class="carousel-item active" style="background-image: url(<?php echo esc_url($p_photo); ?>);"></div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($photos[0] != '' && count($photos) > 1) { ?>
                                                            <span class="carousel-control-prev" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="prev">
                                                                <span class="fa fa-angle-left" aria-hidden="true"></span>
                                                            </span>
                                                            <span class="carousel-control-next" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="next">
                                                                <span class="fa fa-angle-right" aria-hidden="true"></span>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($p_featured == '1') { ?>
                                                        <div class="pxp-results-card-3-featured-label"><?php esc_html_e('Featured', 'resideo'); ?></div>
                                                    <?php } ?>
                                                    <div class="pxp-results-card-3-details">
                                                        <div class="pxp-results-card-3-details-title"><?php the_title(); ?></div>
                                                        <div class="pxp-results-card-3-type-status">
                                                            <?php if ($type) {
                                                                echo esc_html($type[0]->name);
                                                            }
                                                            if ($status) {
                                                                echo '<span>|</span>' . esc_html($status[0]->name);
                                                            } ?>
                                                        </div>
                                                        <div class="pxp-results-card-3-features">
                                                            <span>
                                                                <?php if ($p_beds != '') {
                                                                    echo esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                                }
                                                                if ($p_baths != '') {
                                                                    echo esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                                }
                                                                if ($p_size != '') {
                                                                    echo esc_html($p_size) . ' ' . esc_html($unit);
                                                                } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="pxp-results-card-3-details-price">
                                                        <?php
                                                        if ($currency_pos == 'before') {
                                                            $price_str = esc_html($currency) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                        } else {
                                                            $price_str = esc_html($p_price) . esc_html($currency) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                        }

                                                        if ($p_price_reserved) {
                                                            $price_str = 'Prezzo riservato' . ' <span>' . esc_html($p_price_label) . '</span>';
                                                        }
                                                        echo $price_str; ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php break;
                                        case 'list':
                                            $address_arr  = array();
                                            $address      = '';
                                            $street_no    = get_post_meta($prop_id, 'street_number', true);
                                            $street       = get_post_meta($prop_id, 'route', true);
                                            $neighborhood = get_post_meta($prop_id, 'neighborhood', true);
                                            $city         = get_post_meta($prop_id, 'locality', true);
                                            $state        = get_post_meta($prop_id, 'administrative_area_level_1', true);
                                            $zip          = get_post_meta($prop_id, 'postal_code', true);

                                            $neighborhood_value = resideo_get_field_value($neighborhood_type, $neighborhood, $neighborhoods);
                                            $city_value         = resideo_get_field_value($city_type, $city, $cities);

                                            if (is_array($address_settings)) {
                                                uasort($address_settings, "resideo_compare_position");

                                                $address_default = array(
                                                    'street_number' => $street_no,
                                                    'street'        => $street,
                                                    'neighborhood'  => $neighborhood_value,
                                                    'city'          => $city_value,
                                                    'state'         => $state,
                                                    'zip'           => $zip
                                                );

                                                foreach ($address_settings as $key => $value) {
                                                    if ($address_default[$key] != '') {
                                                        array_push($address_arr, $address_default[$key]);
                                                    }
                                                }
                                            } else {
                                                if ($street_no != '') array_push($address_arr, $street_no);
                                                if ($street != '') array_push($address_arr, $street);
                                                if ($neighborhood_value != '') array_push($address_arr, $neighborhood_value);
                                                if ($city_value != '') array_push($address_arr, $city_value);
                                                if ($state != '') array_push($address_arr, $state);
                                                if ($zip != '') array_push($address_arr, $zip);
                                            }

                                            if (count($address_arr) > 0) $address = implode(', ', $address_arr); ?>

                                            <div class="col-12">
                                                <a href="<?php echo esc_url($p_link); ?>" class="pxp-results-card pxp-results-list-item-1 <?php echo esc_attr($featured_class); ?>" data-prop="<?php echo esc_attr($prop_id); ?>">
                                                    <div class="row">
                                                        <div class="<?php echo esc_attr($list_col_image_class); ?>">
                                                            <div id="card-carousel-<?php echo esc_attr($prop_id); ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                                                                <div class="carousel-inner rounded-lg">
                                                                    <?php if ($photos[0] != '') {
                                                                        for ($i = 0; $i < count($photos); $i++) {
                                                                            // $p_photo = wp_get_attachment_image_src($photos[$i], 'pxp-gallery'); 
                                                                    ?>
                                                                            <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>" style="background-image: url( https://img.miogest.com/<?php echo $photos[$i]; ?>);"></div>
                                                                        <?php }
                                                                    } else {
                                                                        $p_photo = 'https://gardahomeproject.it/wp-content/uploads/2021/07/placeholder500.jpg'; ?>
                                                                        <div class="carousel-item active" style="background-image: url(<?php echo esc_url($p_photo); ?>);"></div>
                                                                    <?php }
                                                                    if ($photos[0] != '' && count($photos) > 1) { ?>
                                                                        <span class="carousel-control-prev" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="prev">
                                                                            <span class="fa fa-angle-left" aria-hidden="true"></span>
                                                                        </span>
                                                                        <span class="carousel-control-next" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="next">
                                                                            <span class="fa fa-angle-right" aria-hidden="true"></span>
                                                                        </span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="<?php echo esc_attr($list_col_details_class); ?>">
                                                            <div class="pxp-results-list-item-1-details">
                                                                <div class="pxp-results-list-item-1-type-status">
                                                                    <?php if ($type) {
                                                                        echo esc_html($type[0]->name);
                                                                    }
                                                                    if ($status) {
                                                                        echo '<span>|</span>' . esc_html($status[0]->name);
                                                                    } ?>
                                                                </div>
                                                                <div class="pxp-results-list-item-1-details-title <?php echo esc_attr($list_title_margin_class); ?>"><?php the_title(); ?></div>
                                                                <div class="pxp-results-list-item-1-details-address"><?php echo esc_html($address); ?></div>
                                                                <div class="pxp-results-list-item-1-features <?php echo esc_attr($list_features_margin_class); ?>">
                                                                    <span>
                                                                        <?php if ($p_beds != '') {
                                                                            echo esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                                        }
                                                                        if ($p_baths != '') {
                                                                            echo esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                                        }
                                                                        if ($p_size != '') {
                                                                            echo esc_html($p_size) . ' ' . esc_html($unit);
                                                                        } ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="<?php echo esc_attr($list_col_price_class); ?>">
                                                            <div class="pxp-results-list-item-1-price">
                                                                <?php
                                                                if ($currency_pos == 'before') {
                                                                    $price_str = esc_html($currency) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                                } else {
                                                                    $price_str = esc_html($p_price) . esc_html($currency) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                                }

                                                                if ($p_price_reserved) {
                                                                    $price_str = 'Prezzo riservato' . ' <span>' . esc_html($p_price_label) . '</span>';
                                                                }
                                                                echo $price_str; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($p_featured == '1') { ?>
                                                        <div class="pxp-results-list-item-1-featured-label"><?php esc_html_e('Featured', 'resideo'); ?></div>
                                                    <?php } ?>
                                                </a>
                                            </div>
                                        <?php break;
                                        default: ?>
                                            <div class="<?php echo esc_attr($column_class); ?>">
                                                <a href="<?php echo esc_url($p_link); ?>" class="pxp-results-card pxp-results-card-1 rounded-lg <?php echo esc_attr($featured_class); ?>" data-prop="<?php echo esc_attr($prop_id); ?>">
                                                    <div id="card-carousel-<?php echo esc_attr($prop_id); ?>" class="carousel slide" data-ride="carousel" data-interval="false">
                                                        <div class="carousel-inner">
                                                            <?php if ($photos[0] != '') {
                                                                for ($i = 0; $i < count($photos); $i++) {
                                                                    // $p_photo = wp_get_attachment_image_src($photos[$i], 'pxp-gallery'); 
                                                            ?>
                                                                    <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>" style="background-image: url( https://img.miogest.com/<?php echo $photos[$i]; ?>);"></div>
                                                                <?php }
                                                            } else {
                                                                $p_photo = 'https://gardahomeproject.it/wp-content/uploads/2021/07/placeholder500.jpg'; ?>
                                                                <div class="carousel-item active" style="background-image: url(<?php echo esc_url($p_photo); ?>);"></div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($photos[0] != '' && count($photos) > 1) { ?>
                                                            <span class="carousel-control-prev" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="prev">
                                                                <span class="fa fa-angle-left" aria-hidden="true"></span>
                                                            </span>
                                                            <span class="carousel-control-next" data-href="#card-carousel-<?php echo esc_attr($prop_id); ?>" data-slide="next">
                                                                <span class="fa fa-angle-right" aria-hidden="true"></span>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pxp-results-card-1-gradient"></div>
                                                    <div class="pxp-results-card-1-details">
                                                        <div class="pxp-results-card-1-details-title"><?php the_title(); ?></div>
                                                        <div class="pxp-results-card-1-details-price">
                                                            <?php
                                                            if ($currency_pos == 'before') {
                                                                $price_str = esc_html($currency) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            } else {
                                                                $price_str = esc_html($p_price) . esc_html($currency) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            }

                                                            if ($p_price_reserved) {
                                                                $price_str = 'Prezzo riservato' . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            }
                                                            echo $price_str; ?>
                                                        </div>
                                                    </div>
                                                    <div class="pxp-results-card-1-features">
                                                        <span>
                                                            <?php if ($p_beds != '') {
                                                                echo esc_html($p_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                            }
                                                            if ($p_baths != '') {
                                                                echo esc_html($p_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                            }
                                                            if ($p_size != '') {
                                                                echo esc_html($p_size) . ' ' . esc_html($unit);
                                                            } ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($p_featured == '1') { ?>
                                                        <div class="pxp-results-card-1-featured-label"><?php esc_html_e('Featured', 'resideo'); ?></div>
                                                    <?php } ?>
                                                </a>
                                            </div>
                                <?php break;
                                    }
                                } ?>
                            </div>

                            <?php resideo_pagination($searched_posts->max_num_pages);
                            if ($no_map === true) { ?>
                            </div>
                        <?php }

                            if ($has_sidebar === true) { ?>
                        </div>
                        <div class="col-sm-12 col-lg-3 mt-4 mt-md-5 mt-lg-0 <?php echo esc_attr($sidebar_class); ?>">
                            <?php resideo_get_filter_properties_form_sidebar(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if (function_exists('resideo_get_save_search_modal')) {
            resideo_get_save_search_modal();
        }

        if ($no_map !== true) {
            get_footer('split'); ?>
        <?php } else { ?>
    </div>
</div>
<?php } ?>

<?php if ($no_map === true) {
    get_footer();
} ?>