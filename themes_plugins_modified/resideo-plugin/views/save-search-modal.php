<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_save_search_modal')):
    function resideo_get_save_search_modal() { ?>
        <div class="modal fade pxp-properties-modal pxp-sm" id="pxp-save-search-modal" tabindex="-1" role="dialog" aria-labelledby="pxpSaveSearchModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-title" id="pxpSaveSearchModal"><?php esc_html_e('Save Search', 'resideo'); ?></h5>
                        <form class="mt-4">
                            <div class="pxp-modal-message pxp-save-search-modal-response"></div>
                            <div class="form-group">
                                <label for="pxp-save-search-name"><?php esc_html_e('Search Name', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-save-search-name">
                            </div>
                            <div class="form-group mt-4">
                                <?php wp_nonce_field('savesearch_ajax_nonce', 'pxp-save-search-security', true); ?>
                                <a href="javascript:void(0);" class="btn pxp-properties-modal-btn pxp-save-search-modal-btn">
                                    <span class="pxp-save-search-modal-btn-text"><?php _e('Save', 'resideo'); ?></span>
                                    <span class="pxp-save-search-modal-btn-loading"><img src="<?php echo esc_url(RESIDEO_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php _e('Saving...', 'resideo'); ?></span>
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