<?php
/*
Template Name: Account Settings
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $current_user;
global $agent_id;

$current_user = wp_get_current_user();

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$user_account_url = resideo_get_account_url();
$agent_id         = resideo_get_agent_by_userid($current_user->ID);


// Check PayPal Memberhip Plans Payment
if (isset($_GET['token'])) {
    $token = sanitize_text_field($_GET['token']);

    $save_data           = get_option('paypal_plan_transfer');
    $payment_execute_url = $save_data[$current_user->ID]['paypal_execute'];
    $token               = $save_data[$current_user->ID ]['paypal_token'];
    $plan_id             = $save_data[$current_user->ID ]['plan_id'];

    if (isset($_GET['PayerID'])) {
        $payerId = sanitize_text_field($_GET['PayerID']);

        $payment_execute = array(
            'payer_id' => $payerId
        );

        $json      = json_encode($payment_execute);
        $json_resp = resideo_make_post_call($payment_execute_url, $json, $token);

        $save_data[$current_user->ID ] = array();

        update_option('paypal_plan_transfer', $save_data);

        if ($json_resp['state'] == 'approved') {
            resideo_update_agent_membership($agent_id, $plan_id);
            wp_redirect($user_account_url);
        }
    }
}

global $post;
get_header();

$auth_settings     = get_option('resideo_authentication_settings');
$register_as_agent = isset($auth_settings['resideo_agent_registration_field']) ? $auth_settings['resideo_agent_registration_field'] : false;

$user_meta       = get_user_meta($current_user->ID);
$as_email        = $current_user->user_email;
$as_nickname     = $user_meta['nickname'];
$as_first_name   = $user_meta['first_name'];
$as_last_name    = $user_meta['last_name'];
$user_avatar     = get_the_author_meta('avatar', $current_user->ID);
$user_avatar_src = wp_get_attachment_image_src($user_avatar, 'pxp-agent');

if ($user_avatar_src !== false) {
    $avatar = $user_avatar_src[0];
} else {
    $avatar = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
}

if ($agent_id && $agent_id != '') {
    $agent           = get_post($agent_id);
    $agent_about     = $agent->post_content;
    $agent_title     = get_post_meta($agent_id, 'agent_title', true);
    $agent_specs     = get_post_meta($agent_id, 'agent_specs', true);
    $agent_phone     = get_post_meta($agent_id, 'agent_phone', true);
    $agent_skype     = get_post_meta($agent_id, 'agent_skype', true);
    $agent_facebook  = get_post_meta($agent_id, 'agent_facebook', true);
    $agent_twitter   = get_post_meta($agent_id, 'agent_twitter', true);
    $agent_pinterest = get_post_meta($agent_id, 'agent_pinterest', true);
    $agent_linkedin  = get_post_meta($agent_id, 'agent_linkedin', true);
    $agent_instagram = get_post_meta($agent_id, 'agent_instagram', true);
    $agent_payment   = get_post_meta($agent_id, 'agent_payment', true);
} ?>

<div class="pxp-content pxp-account-settings">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <h1 class="pxp-page-header"><?php esc_html_e('Account Settings', 'resideo'); ?></h1>
                </div>
            </div>

            <?php if ($agent_id && $agent_id != '') {
                $membership_settings = get_option('resideo_membership_settings');
                $payment_type = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';

                if ($payment_type == 'membership' && $agent_payment != '1') {
                    $agent_plan_id  = get_post_meta($agent_id, 'agent_plan', true);
                    $has_free = '';

                    if (!$agent_plan_id || $agent_plan_id == '') { ?>
                        <div class="alert alert-secondary mt-4 mt-md-5" role="alert">
                            <h4 class="alert-heading"><?php esc_html_e('You don\'t have an active membership plan.', 'resideo'); ?></h4>
                            <p class="mb-0"><?php esc_html_e('You have to choose one in order to submit listings.', 'resideo'); ?></p>
                        </div>
                    <?php } else {
                        $plan_name       = get_the_title($agent_plan_id);
                        $plan_listings   = get_post_meta($agent_id, 'agent_plan_listings', true);
                        $plan_featured   = get_post_meta($agent_id, 'agent_plan_featured', true);
                        $plan_unlimited  = get_post_meta($agent_id, 'agent_plan_unlimited', true);
                        $has_free        = get_post_meta($agent_id, 'agent_plan_free', true);
                        $plan_activation = strtotime(get_post_meta($agent_id, 'agent_plan_activation', true));
                        $plan_time_unit  = get_post_meta($agent_plan_id, 'membership_billing_time_unit', true);
                        $plan_period     = get_post_meta($agent_plan_id, 'membership_period', true);

                        $seconds = 0;
                        switch ($plan_time_unit) {
                            case 'day':
                                $seconds = 60 * 60 * 24;
                            break;
                            case 'week':
                                $seconds = 60 * 60 * 24 * 7;
                            break;
                            case 'month':
                                $seconds = 60 * 60 * 24 * 30;
                            break;
                            case 'year':
                                $seconds = 60 * 60 * 24 * 365;
                            break;
                        }

                        $time_frame      = $seconds * $plan_period;
                        $expiration_date = $plan_activation + $time_frame;
                        $expiration_date = date('Y-m-d', $expiration_date);
                        $today           = getdate();
                    } ?>

                    <!-- Get membership plans list -->
                    <?php 
                    $args = array(
                        'posts_per_page'   => -1,
                        'post_type'        => 'membership',
                        'order'            => 'ASC',
                        'post_status'      => 'publish,',
                        'meta_key'         => 'membership_plan_price',
                        'orderby'          => 'meta_value_num',
                        'suppress_filters' => false,
                    );

                    $posts = get_posts($args);

                    $membership_settings = get_option('resideo_membership_settings');
                    $currency = isset($membership_settings['resideo_payment_currency_field']) ? $membership_settings['resideo_payment_currency_field'] : '';

                    $return_string = '
                        <div class="mt-4 mt-md-5 pxp-account-settings-plans">
                            <div class="row">';

                    foreach ($posts as $post) : 
                        $membership_billing_time_unit       = get_post_meta($post->ID, 'membership_billing_time_unit', true);
                        $membership_period                  = get_post_meta($post->ID, 'membership_period', true);
                        $membership_submissions_no          = get_post_meta($post->ID, 'membership_submissions_no', true);
                        $membership_unlim_submissions       = get_post_meta($post->ID, 'membership_unlim_submissions', true);
                        $membership_featured_submissions_no = get_post_meta($post->ID, 'membership_featured_submissions_no', true);
                        $membership_plan_price              = get_post_meta($post->ID, 'membership_plan_price', true);
                        $membership_free_plan               = get_post_meta($post->ID, 'membership_free_plan', true);

                        switch ($membership_billing_time_unit) {
                            case 'day':
                                $time_unit = __('days', 'resideo');
                            break;
                            case 'week':
                                $time_unit = __('weeks', 'resideo');
                            break;
                            case 'month':
                                $time_unit = __('months', 'resideo');
                            break;
                            case 'year':
                                $time_unit = __('years', 'resideo');
                            break;
                        }

                        $icon = get_post_meta($post->ID, 'membership_icon', true);
                        $icon_src = wp_get_attachment_image_src($icon, 'pxp-icon');

                        if ($icon_src != '') {
                            $m_icon = $icon_src[0];
                        }

                        $return_string .= '
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="pxp-account-settings-plans-item rounded-lg">
                                        <h3>' . esc_html($post->post_title) . '</h3>';
                        if ($post->ID == $agent_plan_id) {
                            if ($today[0] > strtotime($expiration_date)) {
                                $return_string .= '
                                        <div class="pxp-account-settings-plans-item-status is-expired">' . __('Expired', 'resideo') . '</div>';
                            } else {
                                $return_string .= '
                                        <div class="pxp-account-settings-plans-item-status">' . __('Active', 'resideo') . '</div>';
                            }
                        }
                        $return_string .= '
                                        <div class="pxp-account-settings-plan-item-icon" style="background-image: url(' . esc_url($m_icon) . ');"></div>
                                        <div class="pxp-account-settings-plan-item-price mt-3">';
                        if ($membership_free_plan == 1) {
                            $return_string .= '
                                            <div class="pxp-account-settings-plan-item-price-sum">' . __('Free', 'resideo') . '<span class="pxp-account-settings-plan-item-price-period">&nbsp;/ ' . esc_html($membership_period) . ' ' . esc_html($time_unit) . '</span></div>';
                        } else {
                            $return_string .= '
                                            <div class="pxp-account-settings-plan-item-price-sum">' . esc_html($membership_plan_price) . ' <span class="pxp-account-settings-plan-item-price-currency"> ' . esc_html($currency) . '</span><span class="pxp-account-settings-plan-item-price-period">&nbsp;/ ' . esc_html($membership_period) . ' ' . esc_html($time_unit) . '</span></div>';
                        }
                        $return_string .= '
                                        </div>
                                        <div class="pxp-account-settings-plan-item-features mt-3">';
                        if ($post->ID == $agent_plan_id) {
                            if ($plan_unlimited == '1') {
                                $return_string .= '
                                            <div><span>' . __('Available Listings', 'resideo') . ': </span><strong>' . __('Unlimited', 'resideo') . '</strong></div>';
                            } else {
                                $return_string .= '
                                            <div><span>' . __('Available Listings', 'resideo') . ': </span><strong>' . esc_html($plan_listings) . '</strong></div>';
                            }
                            $return_string .= '
                                            <div><span>' . __('Available Featured Listings', 'resideo') . ': </span><strong>' . esc_html($plan_featured) . '</strong></div>';
                        } else {
                            if ($membership_unlim_submissions == 1) {
                                $return_string .= '
                                            <div><strong>' . __('Unlimited', 'resideo') . '</strong> <span>' . __('Listings', 'resideo') . '</span></div>';
                            } else {
                                $return_string .= '
                                            <div><strong>' . esc_html($membership_submissions_no) . '</strong> <span>' . __('Listings', 'resideo') . '</span></div>';
                            }
                            $return_string .= '
                                            <div><strong>' . esc_html($membership_featured_submissions_no) . '</strong> <span>' . __('Featured Listings', 'resideo') . '</span></div>';
                        }
                        $return_string .= '
                                        </div>
                                        <div class="row mt-3 mt-md-4">
                                            <div class="col-7">';
                        if ($membership_free_plan == 1) {
                            if ($has_free != 1) {
                                $return_string .= '
                                                <a href="javascript:void(0);" class="btn pxp-activate-plan-btn" data-agent-id="' . esc_attr($agent_id) . '" data-id="' . esc_attr($post->ID) . '">
                                                    <span class="pxp-activate-plan-btn-text">' . __('Activate Plan', 'resideo') . '</span>
                                                    <span class="pxp-activate-plan-btn-sending">
                                                        <img src="' . RESIDEO_PLUGIN_PATH . 'images/loader-light.svg" class="pxp-loader pxp-is-btn" alt="...">' . __('Activating...', 'resideo') . '
                                                    </span>
                                                </a>';
                            }
                        } else {
                            if (($post->ID != $agent_plan_id) || (($post->ID == $agent_plan_id) && $today[0] > strtotime($expiration_date))) {
                                $return_string .= '
                                                <a href="javascript:void(0);" class="btn pxp-pay-plan-btn" data-id="' . esc_attr($post->ID) . '">
                                                    <span class="pxp-pay-plan-btn-text"><span class="fa fa-paypal"></span> ' . __('Pay with PayPal', 'resideo') . '</span>
                                                    <span class="pxp-pay-plan-btn-sending">
                                                        <img src="' . RESIDEO_PLUGIN_PATH . 'images/loader-light.svg" class="pxp-loader pxp-is-btn" alt="...">' . __('Processing Payment...', 'resideo') . '
                                                    </span>
                                                </a>';
                            }
                        }
                        $return_string .= '
                                            </div>
                                            <div class="col-5">
                                                <div class="pxp-account-settings-plan-item-expiration text-right">';
                        if ($post->ID == $agent_plan_id) {
                            if ($today[0] > strtotime($expiration_date)) {
                                $return_string .= __('Expired on', 'resideo') . '<br><b>' . $expiration_date . '</b>';
                            } else {
                                $return_string .= __('Expires on', 'resideo') . '<br><b>' . $expiration_date . '</b>';
                            }
                        }
                        $return_string .= '
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                    endforeach;

                    $return_string .= '
                            </div>
                        </div>';

                    print $return_string;

                    wp_reset_postdata();
                    wp_reset_query(); 
                }
            } ?>

            <div class="mt-4 mt-md-5">
                <div class="pxp-account-settings-form">
                    <div class="row">
                        <div class="col-sm-12 col-lg-8">
                            <h3><?php esc_html_e('User Details', 'resideo'); ?></h3>

                            <div class="row mt-3 mt-md-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="as_first_name"><?php esc_html_e('First Name', 'resideo'); ?> <span class="text-red">*</span></label>
                                        <input type="text" id="as_first_name" name="as_first_name" class="form-control" placeholder="<?php esc_html_e('Enter first name', 'resideo'); ?>" value="<?php echo esc_attr($as_first_name[0]); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="as_last_name"><?php esc_html_e('Last Name', 'resideo'); ?> <span class="text-red">*</span></label>
                                        <input type="text" id="as_last_name" name="as_last_name" class="form-control" placeholder="<?php esc_html_e('Enter last name', 'resideo'); ?>" value="<?php echo esc_attr($as_last_name[0]); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="as_nickname"><?php esc_html_e('Nickname', 'resideo'); ?> <span class="text-red">*</span></label>
                                        <input type="text" id="as_nickname" name="as_nickname" class="form-control" placeholder="<?php esc_html_e('Enter nickname', 'resideo'); ?>" value="<?php echo esc_attr($as_nickname[0]); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="as_email"><?php esc_html_e('Email', 'resideo'); ?> <span class="text-red">*</span></label>
                                        <input type="text" id="as_email" name="as_email" class="form-control" placeholder="<?php esc_html_e('Enter email', 'resideo'); ?>" value="<?php echo esc_attr($as_email); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="as_password"><?php esc_html_e('New Password', 'resideo'); ?> <span class="text-red">*</span></label>
                                        <input type="password" id="as_password" name="as_password" class="form-control" placeholder="<?php esc_html_e('Enter new password', 'resideo'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="pxp-account-settings-side rounded-lg mt-4 mt-md-5 mt-lg-0">
                                <h3><?php esc_html_e('Profile Picture', 'resideo'); ?></h3>

                                <div class="mt-3 mt-md-4">
                                    <div class="position-relative">
                                        <div id="aaiu-upload-container-avatar">
                                            <div class="pxp-account-settings-avatar">
                                                <?php if ($user_avatar_src !== false) { ?>
                                                    <div class="pxp-account-settings-avatar-photo has-animation" style="background-image: url(<?php echo esc_url($avatar); ?>);" data-id="<?php echo esc_attr($user_avatar); ?>">
                                                        <button class="pxp-account-settings-avatar-delete-photo"><span class="fa fa-trash-o"></span></button>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="pxp-account-settings-avatar-photo has-animation" style="background-image: url(<?php echo esc_url($avatar); ?>);" data-id=""></div>
                                                <?php } ?>
                                            </div>
                                            <div class="pxp-account-settings-upload-avatar-status"></div>
                                            <div class="clearfix"></div>
                                            <a role="button" id="aaiu-uploader-avatar" class="pxp-browser-photos-btn"><span class="fa fa-camera"></span>&nbsp;&nbsp;&nbsp;<?php esc_html_e('Upload Photo', 'resideo');?></a>
                                            <input type="hidden" name="as_avatar" id="as_avatar" value="<?php echo esc_attr($user_avatar); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($agent_id && $agent_id != '') {
                        $agent_type = get_post_meta($agent_id, 'agent_type', true); ?>

                        <div class="mt-4 mt-md-5">
                            <?php if ($agent_type == 'agent') { ?>
                                <h3><?php esc_html_e('Agent Details', 'resideo'); ?></h3>
                            <?php } else { ?>
                                <h3><?php esc_html_e('Owner Details', 'resideo'); ?></h3>
                            <?php } 
            
                            $agent_about_html = apply_filters('the_content', $agent_about); ?>

                            <div class="mt-3 mt-md-4">
                                <div class="form-group pxp-is-tinymce">
                                    <label><?php esc_html_e('About', 'resideo'); ?></label>
                                    <?php $about_settings = array(
                                        'teeny'         => true,
                                        'media_buttons' => false,
                                        'editor_height' => 240,
                                        'editor_css'    => '
                                            <style>
                                                .wp-editor-tabs {
                                                    float: right;
                                                }
                                                .wp-switch-editor {
                                                    background: transparent;
                                                    color: rgba(51, 51, 51, 0.7);
                                                    border: 0 none;
                                                    border-bottom: 2px solid transparent;
                                                    padding: 5px 0;
                                                    margin: 0 0 0 20px;
                                                    font-weight: 700;
                                                    font-size: .8rem;
                                                    text-transform: uppercase;
                                                    -webkit-transition: all .2s ease-in-out;
                                                    -o-transition: all .2s ease-in-out;
                                                    transition: all .2s ease-in-out;
                                                }
                                                .wp-switch-editor:active {
                                                    background-color: transparent;
                                                }
                                                .html-active .switch-html, 
                                                .tmce-active .switch-tmce {
                                                    background: transparent;
                                                    color: #333;
                                                    border-bottom: 2px solid #333;
                                                    border-radius: 0;
                                                    margin: 0 0 0 20px;
                                                }
                                                div.mce-panel {
                                                    background: #fff;
                                                }
                                                .pxp-dark-mode div.mce-panel {
                                                    background: #F7F7F7;
                                                }
                                                div.mce-edit-area {
                                                    box-shadow: none;
                                                    overflow: hidden;
                                                    border: 1px solid #E2E2E2 !important;
                                                    border-radius: .25rem;
                                                }
                                                div.mce-fullscreen div.mce-edit-area {
                                                    box-shadow: none;
                                                    border-radius: 0;
                                                }
                                                div.mce-fullscreen div.mce-panel {
                                                    background: #fff;
                                                }
                                                div.mce-toolbar-grp {
                                                    background: transparent;
                                                    border-bottom: 0 none;
                                                }
                                                div.mce-fullscreen div.mce-toolbar-grp {
                                                    background: #fff;
                                                    border-bottom: 1px solid #ddd;
                                                }
                                                .wp-editor-container {
                                                    border: 0 none;
                                                }
                                                div.mce-toolbar-grp > div {
                                                    padding: 5px 0;
                                                }
                                                div.mce-fullscreen div.mce-toolbar-grp > div {
                                                    padding: 3px;
                                                }
                                                div.mce-statusbar {
                                                    border-top: 0 none;
                                                }
                                                .quicktags-toolbar {
                                                    padding: 5px 0;
                                                    border-bottom: 0 none;
                                                    background: transparent;
                                                }
                                                .wp-editor-container textarea.wp-editor-area {
                                                    box-shadow: none;
                                                    border: 1px solid #E2E2E2;
                                                    border-radius: .25rem;
                                                    background: #fff;
                                                    
                                                }
                                                .mce-top-part::before {
                                                    content: none;
                                                }
                                                .wp-core-ui #wp-as_agent_about-editor-container .button {
                                                    color: #555d66;
                                                    border-color: transparent;
                                                    background: transparent;
                                                    font-weight: 700;
                                                }
                                                .wp-core-ui #wp-as_agent_about-editor-container .button#qt_as_agent_about_strong {
                                                    font-weight: 900;
                                                }
                                                .wp-core-ui #wp-as_agent_about-editor-container .button:hover,
                                                .wp-core-ui #wp-as_agent_about-editor-container .button:focus {
                                                    background: #fafafa;
                                                    border-color: #555d66;
                                                    color: #333;
                                                    box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0,0,0,.08);
                                                    outline: 0;
                                                }
                                            </style>
                                        ',
                                    );
                                    wp_editor($agent_about_html, 'as_agent_about', $about_settings); ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_title"><?php esc_html_e('Title', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_title" name="as_agent_title" placeholder="<?php esc_html_e('Enter title', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_title); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_specs"><?php esc_html_e('Specialities', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_specs" name="as_agent_specs" placeholder="<?php esc_html_e('Repeat specialities', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_specs); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_phone"><?php esc_html_e('Phone', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_phone" name="as_agent_phone" placeholder="<?php esc_html_e('Enter phone', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_phone); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_skype"><?php esc_html_e('Skype', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_skype" name="as_agent_skype" placeholder="<?php esc_html_e('Enter Skype ID', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_skype); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_facebook"><?php esc_html_e('Facebook', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_facebook" name="as_agent_facebook" placeholder="<?php esc_html_e('Enter Facebook profile URL', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_facebook); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_twitter"><?php esc_html_e('Twitter', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_twitter" name="as_agent_twitter" placeholder="<?php esc_html_e('Enter Twitter profile URL', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_twitter); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_pinterest"><?php esc_html_e('Pinterest', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_pinterest" name="as_agent_pinterest" placeholder="<?php esc_html_e('Enter Pinterest profile URL', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_pinterest); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_linkedin"><?php esc_html_e('LinkedIn', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_linkedin" name="as_agent_linkedin" placeholder="<?php esc_html_e('Enter LinkedIn profile URL', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_linkedin); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="as_agent_instagram"><?php esc_html_e('Instagram', 'resideo'); ?></label>
                                            <input type="text" id="as_agent_instagram" name="as_agent_instagram" placeholder="<?php esc_html_e('Enter Instagram profile URL', 'resideo'); ?>" class="form-control" value="<?php echo esc_attr($agent_instagram); ?>">
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="as_agent_id" id="as_agent_id" value="<?php echo esc_attr($agent_id); ?>">
                    <?php } ?>

                    <input type="hidden" name="as_id" id="as_id" value="<?php echo esc_attr($current_user->ID); ?>">
                    <?php wp_nonce_field('account_settings_ajax_nonce', 'securityAccountSettings', true); ?>
                </div>

                <div class="mt-4 mt-md-5">
                    <a href="javascript:void(0);" class="btn pxp-account-settings-update-btn">
                        <span class="pxp-account-settings-update-btn-text"><?php esc_html_e('Update Account', 'resideo'); ?></span>
                        <span class="pxp-account-settings-update-btn-sending">
                            <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Updating...', 'resideo'); ?>
                        </span>
                    </a>
                    <?php if ($register_as_agent && $agent_id === false) { ?>
                        <div class="btn-group pxp-become-agent-btn-group">
                            <button type="button" class="btn pxp-become-agent-btn" data-type="agent">
                                <span class="pxp-become-agent-btn-text">
                                    <span class="pxp-is-agent"><?php esc_html_e('Become Agent', 'resideo'); ?></span>
                                    <span class="pxp-is-owner"><?php esc_html_e('Become Owner', 'resideo'); ?></span>
                                </span>
                                <span class="pxp-become-agent-btn-sending">
                                    <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Updating...', 'resideo'); ?></span>
                                </span>
                            </button>
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-angle-down"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="javascript:void(0);" data-value="agent"><?php esc_html_e('Agent', 'resideo'); ?></a></li>
                                <li><a href="javascript:void(0);" data-value="owner"><?php esc_html_e('Owner', 'resideo'); ?></a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade pxp-alert-modal" id="pxp-account-settings-alert-modal" role="dialog" aria-labelledby="pxpAccountSettingsAlertModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pxp-account-settings-response"></div>
        </div>
    </div>
</div>

<?php get_footer(); ?>