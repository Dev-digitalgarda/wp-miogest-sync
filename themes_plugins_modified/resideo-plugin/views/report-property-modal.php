<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_report_property_modal')):
    function resideo_get_report_property_modal($modal_info) { ?>
        <div class="modal fade pxp-agent-modal" id="pxp-report-property-modal" tabindex="-1" role="dialog" aria-labelledby="pxpReportPropertyModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpReportPropertyModal"><?php esc_html_e('Report Property', 'resideo'); ?></h5>
                        <form class="mt-4">
                            <input type="hidden" id="pxp-modal-report-property-title" value="<?php echo esc_attr($modal_info['title']); ?>">
                            <input type="hidden" id="pxp-modal-report-property-link" value="<?php echo esc_attr($modal_info['link']); ?>">
                            <div class="pxp-modal-message pxp-report-property-modal-response"></div>
                            <div class="form-group">
                                <label for="pxp-report-property-reason"><?php esc_html_e('Reason', 'resideo'); ?></label>
                                <textarea id="pxp-report-property-reason" placeholder="<?php esc_attr_e('Please describe a reason...', 'resideo'); ?>" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <?php wp_nonce_field('reportproperty_ajax_nonce', 'pxp-modal-report-property-security', true); ?>
        
                                <a href="javascript:void(0);" class="btn pxp-report-property-modal-btn">
                                    <span class="pxp-report-property-modal-btn-text"><?php esc_html_e('Submit', 'resideo'); ?></span>
                                    <span class="pxp-report-property-modal-btn-sending"><img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Sending report...', 'resideo'); ?></span>
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