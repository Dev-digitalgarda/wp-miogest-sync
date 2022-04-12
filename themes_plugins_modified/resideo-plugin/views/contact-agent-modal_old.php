<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_contact_agent_modal')):
    function resideo_get_contact_agent_modal($modal_info) { ?>
        <div class="modal fade pxp-agent-modal" id="pxp-contact-agent" tabindex="-1" role="dialog" aria-labelledby="pxpContactAgentModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpContactAgentModal"><?php esc_html_e('Request Info', 'resideo'); ?></h5>
                        <form class="mt-4">
                            <input type="hidden" id="pxp-modal-contact-agent-agent_email" value="<?php echo esc_attr($modal_info['agent_email']); ?>">
                            <input type="hidden" id="pxp-modal-contact-agent-title" value="<?php echo esc_attr($modal_info['title']); ?>">
                            <input type="hidden" id="pxp-modal-contact-agent-link" value="<?php echo esc_attr($modal_info['link']); ?>">
                            <input type="hidden" id="pxp-modal-contact-agent-agent_id" value="<?php echo esc_attr($modal_info['agent_id']); ?>">
                            <input type="hidden" id="pxp-modal-contact-agent-user_id" value="<?php echo esc_attr($modal_info['user_id']); ?>">
                            <div class="pxp-modal-message pxp-contact-agent-modal-response"></div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="pxp-contact-agent-firstname"><?php esc_html_e('First Name', 'resideo'); ?></label>
                                        <input type="text" class="form-control" id="pxp-contact-agent-firstname" value="<?php echo esc_attr($modal_info['user_firstname']); ?>">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="pxp-contact-agent-lastname"><?php esc_html_e('Last Name', 'resideo'); ?></label>
                                        <input type="text" class="form-control" id="pxp-contact-agent-lastname" value="<?php echo esc_attr($modal_info['user_lastname']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pxp-contact-agent-email"><?php esc_html_e('Email', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-contact-agent-email" value="<?php echo esc_attr($modal_info['user_email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="pxp-contact-agent-phone"><?php esc_html_e('Phone (optional)', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-contact-agent-phone">
                            </div>
                            <div class="form-group">
                                <label for="pxp-contact-agent-message"><?php esc_html_e('Message', 'resideo'); ?></label>
                                <span id="pxp-modal-contact-agent-hidden-message" class="d-none"><?php echo sprintf(__('Hi, %s%sI would like more information about %s.', 'resideo'), PHP_EOL, PHP_EOL, esc_html($modal_info['title'])); ?></span>
                                <textarea id="pxp-contact-agent-message" rows="4" class="form-control"><?php echo sprintf(__('Hi, %s%sI would like more information about %s.', 'resideo'), PHP_EOL, PHP_EOL, esc_html($modal_info['title'])); ?></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <?php wp_nonce_field('contactagent_ajax_nonce', 'pxp-modal-contact-agent-security', true); ?>
                                <a href="javascript:void(0);" class="btn pxp-agent-contact-modal-btn pxp-contact-agent-modal-btn">
                                    <span class="pxp-contact-agent-modal-btn-text"><?php _e('Send Message', 'resideo'); ?></span>
                                    <span class="pxp-contact-agent-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Sending message...', 'resideo'); ?></span>
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