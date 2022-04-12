<?php
/*
Template Name: Saved Searches
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

global $post;
get_header('app');

$current_user = wp_get_current_user();
$searches = get_user_meta($current_user->ID, 'user_search', true);
?>

<div class="pxp-content pxp-saved-searches">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <h1 class="pxp-page-header"><?php esc_html_e('Saved Searches', 'resideo'); ?></h1>
                </div>
            </div>

            <input type="hidden" id="user_id" name="user_id" value="<?php echo esc_attr($current_user->ID); ?>">
            <input type="hidden" id="searches_url" name="searches_url" value="<?php echo esc_url(resideo_get_searches_url()); ?>">
            <input type="hidden" id="searches_del_name" name="searches_del_name" value="">

            <?php wp_nonce_field('deletesearch_ajax_nonce', 'security-deletesearch', true); ?>

            <?php if (is_array($searches) && count($searches) > 0) { ?>
                <div class="mt-4 mt-md-5">
                    <?php foreach ($searches as $search) { ?>
                        <div class="pxp-saved-searches-item rounded-lg">
                            <div class="row align-items-center">
                                <div class="col-9 col-sm-8 col-lg-10">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9">
                                            <div class="pxp-saved-searches-item-name"><?php echo esc_html($search['name']); ?></div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="pxp-saved-searches-item-date"><?php echo esc_html($search['date']); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 col-sm-4 col-lg-2">
                                    <div class="pxp-saved-searches-item-actions">
                                        <a href="<?php echo esc_url($search['url']); ?>" target="_blank"><span class="fa fa-eye"></span></a>
                                        <a href="javascript:void(0);" class="pxp-saved-searches-items-delete" data-toggle="modal" data-target="#pxp-saved-searches-delete-modal" data-name="<?php echo esc_html($search['name']); ?>"><span class="fa fa-trash-o"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="mt-3 mt-md-4">
                    <?php esc_html_e('You have no saved searches.', 'resideo'); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="modal fade pxp-property-modal" id="pxp-saved-searches-delete-modal" tabindex="-1" role="dialog" aria-labelledby="pxpSavedSearchesDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="pxpSavedSearchesDeleteModalLabel"><?php _e('Delete Search', 'resideo'); ?></h5>
                <p class="mt-4"><?php esc_html_e('Are you sure?', 'resideo'); ?></p>
                <div class="mt-4">
                    <a href="javascript:void(0);" class="pxp-saved-searches-btn-delete-confirm">
                        <span class="pxp-saved-searches-btn-delete-confirm-text"><?php esc_html_e('Delete', 'resideo'); ?></span>
                        <span class="pxp-saved-searches-btn-delete-confirm-sending">
                            <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                            <?php esc_html_e('Deleting...', 'resideo'); ?>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="pxp-saved-searches-btn-delete-cancel" data-dismiss="modal"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>