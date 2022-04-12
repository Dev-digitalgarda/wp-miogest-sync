<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_work_with_agent_modal')):
    function resideo_work_with_agent_modal($modal_info) { ?>
        <div class="modal fade pxp-agent-modal" id="pxp-work-with-modal" tabindex="-1" role="dialog" aria-labelledby="pxpWorkWithModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpWorkWithModal"><?php esc_html_e('Work with', 'resideo'); ?> <?php echo esc_html($modal_info['agent_name']); ?></h5>
                        <form class="mt-4">
                            <input type="hidden" id="pxp-single-agent-email" value="<?php echo esc_attr($modal_info['agent_email']); ?>">
                            <input type="hidden" id="pxp-single-agent-id" value="<?php echo esc_attr($modal_info['agent_id']); ?>">
                            <input type="hidden" id="pxp-single-agent-user-id" value="<?php echo esc_attr($modal_info['user_id']); ?>">
                            <div class="pxp-modal-message pxp-work-with-modal-response"></div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="pxp-work-with-firstname"><?php esc_html_e('First Name', 'resideo'); ?></label>
                                        <input type="text" class="form-control" id="pxp-work-with-firstname" value="<?php echo esc_attr($modal_info['user_firstname']); ?>">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="pxp-work-with-lastname"><?php esc_html_e('Last Name', 'resideo'); ?></label>
                                        <input type="text" class="form-control" id="pxp-work-with-lastname" value="<?php echo esc_attr($modal_info['user_lastname']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pxp-work-with-email"><?php esc_html_e('Email', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-work-with-email" value="<?php echo esc_attr($modal_info['user_email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="pxp-work-with-phone"><?php esc_html_e('Phone (optional)', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-work-with-phone">
                            </div>
                            <div class="form-group">
                                <label for="pxp-work-with-interest"><?php esc_html_e('I am interested in', 'resideo'); ?></label>
                                <select class="custom-select" id="pxp-work-with-interest">
                                    <option value="sell"><?php esc_html_e('Sell', 'resideo'); ?></option>
                                    <option value="buy"><?php esc_html_e('Buy', 'resideo'); ?></option>
                                    <option value="rent"><?php esc_html_e('Rent', 'resideo'); ?></option>
                                    <option value="other"><?php esc_html_e('Other', 'resideo'); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pxp-work-with-interest"><?php esc_html_e('Message', 'resideo'); ?></label>
                                <textarea id="pxp-work-with-message" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <?php wp_nonce_field('contactagent_ajax_nonce', 'pxp-single-agent-security', true); ?>
                                <a href="javascript:void(0);" class="btn pxp-agent-contact-modal-btn pxp-work-with-agent-modal-btn">
                                    <span class="pxp-work-with-agent-modal-btn-text"><?php _e('Send Message', 'resideo'); ?></span>
                                    <span class="pxp-work-with-agent-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Sending message...', 'resideo'); ?></span>
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