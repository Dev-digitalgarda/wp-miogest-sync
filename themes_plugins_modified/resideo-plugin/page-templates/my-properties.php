<?php
/*
Template Name: My Properties
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

$current_user = wp_get_current_user();

if (!is_user_logged_in() || resideo_check_user_agent($current_user->ID) === false) {
    wp_redirect(home_url());
}

global $post;
get_header('app');

$membership_settings = get_option('resideo_membership_settings', '');
$payment_type        = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';
$payment_currency    = isset($membership_settings['resideo_payment_currency_field']) ? $membership_settings['resideo_payment_currency_field'] : '';
$standard_price      = isset($membership_settings['resideo_submission_price_field']) ? $membership_settings['resideo_submission_price_field'] : 'FREE';
$featured_price      = isset($membership_settings['resideo_featured_price_field']) ? $membership_settings['resideo_featured_price_field'] : 'FREE';

$agent_id      = resideo_get_agent_by_userid($current_user->ID);
$agent_payment = get_post_meta($agent_id, 'agent_payment', true);
$my_posts      = resideo_get_my_properties($agent_id);
$edit_url      = resideo_get_submit_url();
$total_p       = $my_posts ? $my_posts->found_posts : 0;
?>

<div class="pxp-content pxp-my-properties">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <h1 class="pxp-page-header"><?php esc_html_e('My Properties', 'resideo'); ?></h1>
                </div>
            </div>

            <div class="mt-4 mt-md-5">
                <h3><?php echo esc_html($total_p) . ' ' . __('Properties', 'resideo'); ?></h3>
            </div>

            <input type="hidden" id="user_id" name="user_id" value="<?php echo esc_attr($current_user->ID); ?>">
            <input type="hidden" id="myproperties_url" name="myproperties_url" value="<?php echo esc_url(resideo_get_myproperties_url()); ?>">
            <input type="hidden" id="del_id" name="del_id" value="">

            <?php if ($payment_type == 'listing' && $agent_payment != '1') { ?>
                <input type="hidden" id="standard_price" name="standard_price" value="<?php echo esc_attr($standard_price); ?>">
                <input type="hidden" id="featured_price" name="featured_price" value="<?php echo esc_attr($featured_price); ?>">
            <?php } ?>

            <?php wp_nonce_field('myproperties_ajax_nonce', 'security-myproperties', true); ?>

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
                    <?php while ($my_posts->have_posts()) {
                        $my_posts->the_post();

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
                        $size  = get_post_meta($prop_id, 'property_size', true);

                        $featured = get_post_meta($prop_id, 'property_featured', true);
                        $payment_status = get_post_meta($prop_id, 'payment_status', true); ?>

                        <div class="pxp-my-properties-item rounded-lg">
                            <div class="row align-items-center">
                                <div class="col-3 col-sm-2 col-lg-1">
                                    <div class="pxp-my-properties-item-photo pxp-cover rounded-lg" style="background-image: url(<?php echo esc_url($first_photo[0]); ?>);"></div>
                                </div>
                                <div class="col-9 col-sm-10 col-lg-11">
                                    <div class="row align-items-center">
                                        <div class="col-9 col-sm-8 col-lg-10">
                                            <div class="row align-items-center">
                                                <div class="col-lg-4">
                                                    <div class="pxp-my-properties-item-title"><?php the_title(); ?></div>
                                                    <div class="pxp-my-properties-item-features"><?php 
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
                                                    <div class="pxp-my-properties-item-price">
                                                        <?php if ($currency_pos == 'before') {
                                                            echo esc_html($currency_str); ?><?php echo esc_html($price); ?> <span><?php echo esc_html($price_label); ?></span>
                                                        <?php } else {
                                                            echo esc_html($price); ?><?php echo esc_html($currency_str); ?> <span><?php echo esc_html($price_label); ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="pxp-my-properties-item-featured mt-1 mb-1 mt-lg-0 mb-lg-0">
                                                        <?php if ($featured == 1) { ?>
                                                            <span><?php esc_html_e('Featured', 'resideo'); ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="pxp-my-properties-item-status">
                                                        <?php if (get_post_status($prop_id) == 'publish') { ?>
                                                            <div><?php esc_html_e('Status', 'resideo') ?>: <b><?php esc_html_e('Published', 'resideo'); ?></b></div>
                                                        <?php } else { ?>
                                                            <div><?php esc_html_e('Status', 'resideo') ?>: <b><?php esc_html_e('Pending for Approval', 'resideo'); ?></b></div>
                                                        <?php } ?>

                                                        <?php if ($payment_type == 'listing') {
                                                            if ($payment_status == 'paid') { ?>
                                                                <div><?php esc_html_e('Payment', 'resideo') ?>: <b><?php esc_html_e('Paid', 'resideo'); ?></b></div>
                                                            <?php } else { ?>
                                                                <div><?php esc_html_e('Payment', 'resideo') ?>: <b><?php esc_html_e('Required', 'resideo'); ?></b></div>
                                                            <?php }
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="pxp-my-properties-item-payment mt-1 mt-lg-0">
                                                        <?php if ($payment_type == 'listing') {
                                                            $featured_free_left = get_post_meta($agent_id, 'agent_free_featured_listings', true);
                                                            $ffl_int            = intval($featured_free_left); 
                                                            $show_options       = true;

                                                            if ($payment_status == 'paid') {
                                                                if ($ffl_int > 0 || $agent_payment == '1') {
                                                                    if ($featured == 1) {
                                                                        $show_options = false;
                                                                    }
                                                                } else {
                                                                    if ($featured == 1) {
                                                                        $show_options = false;
                                                                    }
                                                                }
                                                            }

                                                            if ($show_options == true) { ?>
                                                                <div class="d-flex dropdown">
                                                                    <a role="button" data-toggle="dropdown" class="pxp-my-properties-item-payment-toggle"><span class="fa fa-ellipsis-h"></span></a>
                                                                    <div class="dropdown-menu pxp-my-properties-item-payment-dropdown">
                                                                        <?php if ($payment_status == 'paid') {
                                                                            if ($ffl_int > 0 || $agent_payment == '1') {
                                                                                if ($featured != 1) { ?>
                                                                                    <div class="form-group form-check">
                                                                                        <input type="checkbox" class="form-check-input pxp-my-featured-free" id="pxp-my-featured-free" value="1">
                                                                                        <label class="form-check-label" for="pxp-my-featured-free">
                                                                                            <?php esc_html_e('Set as Featured', 'resideo'); ?> <?php if($agent_payment != '1') { ?>(<strong><?php echo esc_html($ffl_int) . ' ' . __('free left', 'resideo'); ?></strong>) <?php } ?>
                                                                                        </label>
                                                                                    </div>
                                                                                <?php }
                                                                                wp_nonce_field('upgradeproperty_ajax_nonce', 'securityUpgradeProperty', true); ?>
                                                                                <a href="javascript:void(0);" class="btn pxp-my-properties-item-payment-upgrade-btn pxp-upgrade-btn" style="display: none;" data-id="<?php echo esc_html($prop_id); ?>" data-agent-id="<?php echo esc_html($agent_id); ?>">
                                                                                    <span class="pxp-my-properties-item-payment-upgrade-btn-text">
                                                                                        <?php esc_html_e('Upgrade to Featured', 'resideo'); ?>
                                                                                    </span>
                                                                                    <span class="pxp-my-properties-item-payment-upgrade-btn-sending">
                                                                                        <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                                                                        <?php esc_html_e('Upgrading...', 'resideo'); ?>
                                                                                    </span>
                                                                                </a>
                                                                            <?php } else {
                                                                                if ($featured != 1) { ?>
                                                                                    <div class="form-group form-check">
                                                                                        <input type="checkbox" class="form-check-input pxp-my-featured" id="pxp-my-featured" value="1">
                                                                                        <label class="form-check-label" for="pxp-my-featured">
                                                                                            <?php esc_html_e('Set as Featured', 'resideo'); ?> (<strong>+ <?php echo esc_html($featured_price) . ' ' . esc_html($payment_currency); ?></strong>)
                                                                                        </label>
                                                                                    </div>
                                                                                <?php } ?>
                                                                                <input type="hidden" class="pxp-pay-featured" value="1">
                                                                                <a href="javascript:void(0);" class="btn pxp-my-properties-item-payment-paypal-btn pxp-pay-btn" style="display: none;" data-id="<?php echo esc_attr($prop_id); ?>" data-featured="" data-upgrade="1">
                                                                                    <span class="pxp-my-properties-item-payment-paypal-btn-text">
                                                                                        <span class="fa fa-paypal"></span> <?php esc_html_e('Pay with PayPal', 'resideo'); ?> <span class="pxp-pay-total"><?php echo esc_html($featured_price); ?></span> <?php echo esc_html($payment_currency); ?>
                                                                                    </span>
                                                                                    <span class="pxp-my-properties-item-payment-paypal-btn-sending">
                                                                                        <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                                                                        <?php esc_html_e('Processing Payment...', 'resideo'); ?>
                                                                                    </span>
                                                                                </a>
                                                                            <?php }
                                                                        } else { ?>
                                                                            <div class="pxp-my-properties-item-payment-price"><?php esc_html_e('Submission Price', 'resideo'); ?>: <b><?php echo esc_html($standard_price) . ' ' . esc_html($payment_currency); ?></b></div>
                                                                            <?php if ($featured != 1) {
                                                                                if ($ffl_int > 0) { ?>
                                                                                    <div class="form-group form-check">
                                                                                        <input type="checkbox" class="form-check-input pxp-my-featured-free" id="pxp-my-featured-free" value="1">
                                                                                        <label class="form-check-label" for="pxp-my-featured-free">
                                                                                            <?php esc_html_e('Set as Featured', 'resideo'); ?> (<strong><?php echo esc_html($ffl_int) . ' ' . __('free left', 'resideo'); ?></strong>)
                                                                                        </label>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="form-group form-check">
                                                                                        <input type="checkbox" class="form-check-input pxp-my-featured" id="pxp-my-featured" value="1">
                                                                                        <label class="form-check-label" for="pxp-my-featured">
                                                                                            <?php esc_html_e('Set as Featured', 'resideo'); ?> (<strong>+ <?php echo esc_html($featured_price) . ' ' . esc_html($payment_currency); ?></strong>)
                                                                                        </label>
                                                                                    </div>
                                                                                <?php }
                                                                            } ?>
                                                                            <a href="javascript:void(0);" class="btn pxp-my-properties-item-payment-paypal-btn pxp-pay-btn" data-id="<?php echo esc_attr($prop_id); ?>" data-featured="" data-upgrade="">
                                                                                <span class="pxp-my-properties-item-payment-paypal-btn-text">
                                                                                    <span class="fa fa-paypal"></span> <?php esc_html_e('Pay with PayPal', 'resideo'); ?> <span class="pxp-pay-total"><?php echo esc_html($standard_price); ?></span> <?php echo esc_html($payment_currency); ?>
                                                                                </span>
                                                                                <span class="pxp-my-properties-item-payment-paypal-btn-sending">
                                                                                    <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                                                                    <?php esc_html_e('Processing Payment...', 'resideo'); ?>
                                                                                </span>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            <?php }
                                                        } else if ($payment_type == 'membership') {
                                                            $featured_plan_left = get_post_meta($agent_id, 'agent_plan_featured', true);
                                                            $fpl_int = ($agent_payment == '1') ? 1 : intval($featured_plan_left);

                                                            if ($featured != 1 && $fpl_int > 0) { ?>
                                                                <?php wp_nonce_field('featuredproperty_ajax_nonce', 'securityFeaturedProperty', true); ?>
                                                                <a href="javascript:void(0);" class="btn pxp-my-properties-item-payment-featured-btn pxp-featured-btn" data-id="<?php echo esc_attr($prop_id); ?>" data-agent-id="<?php echo esc_attr($agent_id); ?>">
                                                                    <span class="pxp-my-properties-item-payment-featured-btn-text">
                                                                        <?php esc_html_e('Set as Featured', 'resideo'); ?>
                                                                    </span>
                                                                    <span class="pxp-my-properties-item-payment-featured-btn-sending">
                                                                        <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                                                        <?php esc_html_e('Upgrading...', 'resideo'); ?>
                                                                    </span>
                                                                </a>
                                                            <?php } 
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 col-sm-4 col-lg-2">
                                            <div class="pxp-my-properties-item-actions">
                                                <a href="<?php echo esc_url($edit_url) . '?edit_id=' . esc_attr($prop_id); ?>" target="_blank"><span class="fa fa-pencil"></span></a>
                                                <a href="<?php echo esc_url($link); ?>" target="_blank"><span class="fa fa-eye"></span></a>
                                                <a href="javascript:void(0);" class="pxp-my-properties-items-delete" data-toggle="modal" data-target="#pxp-my-properties-delete-modal" data-id="<?php echo esc_attr($prop_id); ?>"><span class="fa fa-trash-o"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php resideo_pagination($my_posts->max_num_pages); ?>
            <?php } else { ?>
                <div class="mt-3 mt-md-4">
                    <?php esc_html_e('You have no properties submitted.', 'resideo'); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="modal fade pxp-alert-modal" id="pxp-my-properties-alert-modal" role="dialog" aria-labelledby="pxpMyPropertiesAlertModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pxp-my-properties-response"></div>
        </div>
    </div>
</div>

<div class="modal fade pxp-property-modal" id="pxp-my-properties-delete-modal" tabindex="-1" role="dialog" aria-labelledby="pxpMyPropertiesDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="pxpMyPropertiesDeleteModalLabel"><?php _e('Delete Property', 'resideo'); ?></h5>
                <p class="mt-4"><?php esc_html_e('Are you sure?', 'resideo'); ?></p>
                <div class="mt-4">
                    <?php wp_nonce_field('submitproperty_ajax_nonce', 'security-submit-property', true); ?>
                    <a href="javascript:void(0);" class="pxp-my-properties-btn-delete-confirm">
                        <span class="pxp-my-properties-btn-delete-confirm-text"><?php esc_html_e('Delete', 'resideo'); ?></span>
                        <span class="pxp-my-properties-btn-delete-confirm-sending">
                            <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                            <?php esc_html_e('Deleting...', 'resideo'); ?>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="pxp-my-properties-btn-delete-cancel" data-dismiss="modal"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>