<?php
/*
Template Name: Whish List
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

global $post;
get_header();

$current_user = wp_get_current_user();
$wl_posts = resideo_get_wishlist($current_user->ID);
$total_p = $wl_posts ? $wl_posts->found_posts : 0;
?>

<div class="pxp-content pxp-wish-list">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <h1 class="pxp-page-header"><?php esc_html_e('Wish List', 'resideo'); ?></h1>
                </div>
            </div>

            <div class="mt-4 mt-md-5">
                <h3><?php echo esc_html($total_p) . ' ' . __('Properties', 'resideo'); ?></h3>
            </div>

            <input type="hidden" id="user_id" name="user_id" value="<?php echo esc_attr($current_user->ID); ?>">
            <input type="hidden" id="wishlist_url" name="wishlist_url" value="<?php echo esc_url(resideo_get_wishlist_url()); ?>">
            <input type="hidden" id="wishlist_del_id" name="wishlist_del_id" value="">

            <?php wp_nonce_field('wishlist_ajax_nonce', 'security-wishlist', true); ?>

            <?php if ($total_p != 0) { 
                $general_settings = get_option('resideo_general_settings');
                $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
                $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
                $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
                $currency         = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
                $currency_pos     = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
                $locale           = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
                $decimals         = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
                setlocale(LC_MONETARY, $locale); ?>

                <div class="mt-3 mt-md-4">
                    <?php while ($wl_posts->have_posts()) {
                        $wl_posts->the_post();

                        $prop_id = get_the_ID();
                        $link  = get_permalink($prop_id);

                        $gallery     = get_post_meta($prop_id, 'property_gallery', true);
                        $photos      = explode(',', $gallery);
                        $first_photo = wp_get_attachment_image_src($photos[0], 'pxp-thmb');

                        if ($first_photo != '') {
                            $photo = $first_photo[0];
                        } else {
                            $photo = RESIDEO_PLUGIN_PATH . 'images/ph-thmb.jpg';
                        }

                        $price       = get_post_meta($prop_id, 'property_price', true);
                        $price_label = get_post_meta($prop_id, 'property_price_label', true);

                        $currency_str = $currency;

                        if (is_numeric($price)) {
                            if ($decimals == '1') {
                                $price = money_format('%!i', $price);
                            } else {
                                $price = money_format('%!.0i', $price);
                            }
                        } else {
                            $price_label = '';
                            $currency_str = '';
                        }


                        $beds  = get_post_meta($prop_id, 'property_beds', true);
                        $baths = get_post_meta($prop_id, 'property_baths', true);
                        $size  = get_post_meta($prop_id, 'property_size', true); ?>

                        <div class="pxp-wishlist-item rounded-lg">
                            <div class="row align-items-center">
                                <div class="col-3 col-sm-2 col-lg-1">
                                    <div class="pxp-wishlist-item-photo pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($first_photo[0]); ?>);"></div>
                                </div>

                                <div class="col-9 col-sm-10 col-lg-11">
                                    <div class="row align-items-center">
                                        <div class="col-9 col-sm-8 col-lg-10">
                                            <div class="row align-items-center">
                                                <div class="col-lg-6">
                                                    <div class="pxp-wishlist-item-title"><?php the_title(); ?></div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="pxp-wishlist-item-features"><?php 
                                                        if ($beds != '') {
                                                            echo esc_html($beds); ?> <?php echo esc_html($beds_label); ?><span>|</span><?php 
                                                        }
                                                        if ($baths != '') { 
                                                            echo esc_html($baths); ?> <?php echo esc_html($baths_label); ?><span>|</span><?php 
                                                        }
                                                        if ($size != '') {
                                                            echo esc_html($size)?> <?php echo esc_html($unit);
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="pxp-wishlist-item-price">
                                                        <?php if ($currency_pos == 'before') {
                                                            echo esc_html($currency_str); ?><?php echo esc_html($price); ?> <span><?php echo esc_html($price_label); ?></span>
                                                        <?php } else {
                                                            echo esc_html($price); ?><?php echo esc_html($currency_str); ?> <span><?php echo esc_html($price_label); ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 col-sm-4 col-lg-2">
                                            <div class="pxp-wishlist-item-actions">
                                                <a href="<?php echo esc_url($link); ?>" target="_blank"><span class="fa fa-eye"></span></a>
                                                <a href="javascript:void(0);" class="pxp-wishlist-items-delete" data-toggle="modal" data-target="#pxp-wishlist-delete-modal" data-id="<?php echo esc_attr($prop_id); ?>"><span class="fa fa-trash-o"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php resideo_pagination($wl_posts->max_num_pages); ?>
            <?php } else { ?>
                <div class="mt-3 mt-md-4">
                    <?php esc_html_e('You have no properties saved to your wish list.', 'resideo'); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="modal fade pxp-property-modal" id="pxp-wishlist-delete-modal" tabindex="-1" role="dialog" aria-labelledby="pxpWishlistDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="pxpWishlistDeleteModalLabel"><?php _e('Remove from Wish List', 'resideo'); ?></h5>
                <p class="mt-4"><?php esc_html_e('Are you sure?', 'resideo'); ?></p>
                <div class="mt-4">
                    <a href="javascript:void(0);" class="pxp-wishlist-btn-delete-confirm">
                        <span class="pxp-wishlist-btn-delete-confirm-text"><?php esc_html_e('Remove', 'resideo'); ?></span>
                        <span class="pxp-wishlist-btn-delete-confirm-sending">
                            <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                            <?php esc_html_e('Removing...', 'resideo'); ?>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="pxp-wishlist-btn-delete-cancel" data-dismiss="modal"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
