<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_user_modal')):
    function resideo_get_user_modal() {
        $auth_settings      = get_option('resideo_authentication_settings');
        $agent_registration = isset($auth_settings['resideo_agent_registration_field']) ? $auth_settings['resideo_agent_registration_field'] : '';
        $agent_default      = isset($auth_settings['resideo_agent_default_field']) ? $auth_settings['resideo_agent_default_field'] : '';
        $terms              = isset($auth_settings['resideo_terms_field']) ? $auth_settings['resideo_terms_field'] : ''; ?>

        <div class="modal fade pxp-user-modal" id="pxp-signin-modal" tabindex="-1" role="dialog" aria-labelledby="pxpSigninModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpSigninModal"><?php _e('Welcome back!', 'resideo'); ?></h5>
                        <form id="pxp-signin-modal-form" class="mt-4" method="post">
                            <div class="pxp-modal-message pxp-signin-modal-message"></div>
                            <div class="form-group">
                                <label for="pxp-signin-modal-email"><?php _e('Email', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-signin-modal-email">
                            </div>
                            <div class="form-group">
                                <label for="pxp-signin-modal-pass"><?php _e('Password', 'resideo'); ?></label>
                                <input type="password" class="form-control" id="pxp-signin-modal-pass">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="pxp-signin-modal-redirect" id="pxp-signin-modal-redirect" value="">
                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-signin-modal-security', true); ?>

                                <a href="javascript:void(0);" class="btn pxp-user-modal-btn pxp-signin-modal-btn">
                                    <span class="pxp-signin-modal-btn-text"><?php _e('Sign In', 'resideo'); ?></span>
                                    <span class="pxp-signin-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Signing in...', 'resideo'); ?></span>
                                </a>
                            </div>
                            <div class="form-group mt-4 text-center pxp-modal-small">
                                <a href="javascript:void(0);" class="pxp-modal-link pxp-forgot-trigger"><?php _e('Forgot password', 'resideo'); ?></a>
                            </div>
                            <div class="text-center pxp-modal-small">
                                <?php _e('New to', 'resideo'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a href="javascript:void(0);" class="pxp-modal-link pxp-signup-trigger"><?php _e('Create an account', 'resideo'); ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade pxp-user-modal" id="pxp-signup-modal" tabindex="-1" role="dialog" aria-labelledby="pxpSignupModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpSignupModal"><?php _e('Create an account', 'resideo'); ?></h5>
                        <form id="pxp-signup-modal-form" class="mt-4" method="post">
                            <div class="pxp-modal-message pxp-signup-modal-message"></div>

                            <?php if ($agent_registration == '1') {
                                if ($agent_default == 'all_agents') { ?>
                                    <input type="hidden" id="pxp-signup-modal-user-type" value="agent">
                                <?php } else if ($agent_default == 'all_owners') { ?>
                                    <input type="hidden" id="pxp-signup-modal-user-type" value="owner">
                                <?php } else { ?>
                                    <div class="form-group">
                                        <select id="pxp-signup-modal-user-type" class="custom-select">
                                            <option value=""><?php _e('I am searching for properties', 'resideo'); ?></option>
                                            <option value="agent"><?php _e('I am an agent', 'resideo'); ?></option>
                                            <option value="owner"><?php _e('I am an owner', 'resideo'); ?></option>
                                        </select>
                                    </div>
                                <?php }
                            } ?>

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pxp-signup-modal-firstname"><?php _e('First Name', 'resideo'); ?></label>
                                        <input type="text" class="form-control" id="pxp-signup-modal-firstname">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pxp-signup-modal-lastname"><?php _e('Last Name', 'resideo'); ?></label>
                                        <input type="text" class="form-control" id="pxp-signup-modal-lastname">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pxp-signup-modal-email"><?php _e('Email', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-signup-modal-email">
                            </div>
                            <div class="form-group">
                                <label for="pxp-signup-modal-pass"><?php _e('Password', 'resideo'); ?></label>
                                <input type="password" class="form-control" id="pxp-signup-modal-pass">
                            </div>

                            <?php if ($terms != '') { ?>
                                <div class="form-group">
                                    <div class="checkbox custom-checkbox">
                                        <label class="pxp-signup-modal-terms-label">
                                            <input type="checkbox" id="pxp-signup-modal-terms" value="1">
                                            <span class="fa fa-check"></span> <?php printf(__('I agree with <a href="%s" class="pxp-modal-link" target="_blank">Terms and Conditions</a>', 'resideo'), esc_url($terms)); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-signup-modal-security', true); ?>

                                <a href="javascript:void(0);" class="btn pxp-user-modal-btn pxp-signup-modal-btn">
                                    <span class="pxp-signup-modal-btn-text"><?php _e('Sign Up', 'resideo'); ?></span>
                                    <span class="pxp-signup-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Signing up...', 'resideo'); ?></span>
                                </a>
                            </div>
                            <div class="text-center mt-4 pxp-modal-small">
                                <?php _e('Already have an account?', 'resideo'); ?> <a href="javascript:void(0);" class="pxp-modal-link pxp-signin-trigger"><?php _e('Sign in', 'resideo'); ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade pxp-user-modal" id="pxp-forgot-modal" tabindex="-1" role="dialog" aria-labelledby="pxpForgotModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpForgotModal"><?php _e('Forgot Password', 'resideo'); ?></h5>
                        <form id="pxp-forgot-modal-form" class="mt-4" method="post">
                            <div class="pxp-modal-message pxp-forgot-modal-message"></div>
                            <div class="form-group">
                                <label for="pxp-forgot-modal-email"><?php _e('Email', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-forgot-modal-email">
                            </div>
                            <div class="form-group">
                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-forgot-modal-security', true); ?>

                                <a href="javascript:void(0);" class="btn pxp-user-modal-btn pxp-forgot-modal-btn">
                                    <span class="pxp-forgot-modal-btn-text"><?php _e('Get New Password', 'resideo'); ?></span>
                                    <span class="pxp-forgot-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Sending...', 'resideo'); ?></span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade pxp-user-modal" id="pxp-reset-modal" tabindex="-1" role="dialog" aria-labelledby="pxpResetModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpResetModal"><?php _e('Reset Password', 'resideo'); ?></h5>
                        <form id="pxp-reset-modal-form" class="mt-4" method="post">
                            <div class="pxp-modal-message pxp-reset-modal-message"></div>
                            <div class="form-group">
                                <label for="pxp-reset-modal-pass"><?php _e('New Password', 'resideo'); ?></label>
                                <input type="password" class="form-control" id="pxp-reset-modal-pass">
                                <small class="form-text text-muted"><?php _e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).', 'resideo') ?></small>
                            </div>
                            <div class="form-group">
                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-reset-modal-security', true); ?>

                                <a href="javascript:void(0);" class="btn pxp-user-modal-btn pxp-reset-modal-btn">
                                    <span class="pxp-reset-modal-btn-text"><?php _e('Reset Password', 'resideo'); ?></span>
                                    <span class="pxp-reset-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Setting new password...', 'resideo'); ?></span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php }
endif;
?>