<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_contact_agent_hero_form')):
    function resideo_get_contact_agent_hero_form($form_info) { ?>
        <form class="mt-4">
            <input type="hidden" id="pxp-hero-contact-agent-agent_email" value="<?php echo esc_attr($form_info['agent_email']); ?>">
            <input type="hidden" id="pxp-hero-contact-agent-title" value="<?php echo esc_attr($form_info['title']); ?>">
            <input type="hidden" id="pxp-hero-contact-agent-link" value="<?php echo esc_attr($form_info['link']); ?>">
            <input type="hidden" id="pxp-hero-contact-agent-agent_id" value="<?php echo esc_attr($form_info['agent_id']); ?>">
            <input type="hidden" id="pxp-hero-contact-agent-user_id" value="<?php echo esc_attr($form_info['user_id']); ?>">
            <div class="pxp-modal-message pxp-hero-contact-form-response"></div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="pxp-hero-contact-agent-firstname" value="<?php echo esc_attr($form_info['user_firstname']); ?>" placeholder="<?php esc_attr_e('First Name', 'resideo'); ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="pxp-hero-contact-agent-lastname" value="<?php echo esc_attr($form_info['user_lastname']); ?>" placeholder="<?php esc_attr_e('Last Name', 'resideo'); ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="pxp-hero-contact-agent-email" value="<?php echo esc_attr($form_info['user_email']); ?>" placeholder="<?php esc_attr_e('Email', 'resideo'); ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="pxp-hero-contact-agent-phone" placeholder="<?php esc_attr_e('Phone (optional)', 'resideo'); ?>">
            </div>
            <div class="form-group">
                <span id="pxp-hero-contact-agent-hidden-message" class="d-none"><?php echo sprintf(__('Hi, %s%sI would like more information about %s.', 'resideo'), PHP_EOL, PHP_EOL, esc_html($form_info['title'])); ?></span>
                <textarea id="pxp-hero-contact-agent-message" rows="4" class="form-control" placeholder="<?php esc_attr_e('Message', 'resideo'); ?>"><?php echo sprintf(__('Hi, %s%sI would like more information about %s.', 'resideo'), PHP_EOL, PHP_EOL, esc_html($form_info['title'])); ?></textarea>
            </div>
            <div class="form-group mt-4">
                <?php wp_nonce_field('contactagent_ajax_nonce', 'pxp-hero-contact-agent-security', true); ?>
                <a href="javascript:void(0);" class="btn pxp-agent-contact-hero-btn pxp-contact-agent-hero-btn">
                    <span class="pxp-contact-agent-hero-btn-text"><?php _e('Send Message', 'resideo'); ?></span>
                    <span class="pxp-contact-agent-hero-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Sending message...', 'resideo'); ?></span>
                </a>
            </div>
        </form>
    <?php }
endif;
?>