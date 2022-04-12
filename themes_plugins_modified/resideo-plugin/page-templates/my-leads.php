<?php
/*
Template Name: My Leads
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
get_header();

$agent_id    = resideo_get_agent_by_userid($current_user->ID);
$agent_leads = resideo_get_agent_leads($agent_id);
$total_leads = ($agent_leads) ? $agent_leads->found_posts : 0;

$search_name      = isset($_GET['lead_name']) ? stripslashes(sanitize_text_field($_GET['lead_name'])) : '';
$search_contacted = isset($_GET['lead_contacted']) ? sanitize_text_field($_GET['lead_contacted']) : '';
$search_score     = isset($_GET['lead_score']) ? sanitize_text_field($_GET['lead_score']) : '';

$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date'; ?>

<div class="pxp-content pxp-my-leads">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <h1 class="pxp-page-header"><?php esc_html_e('My Leads', 'resideo'); ?></h1>
                </div>
            </div>

            <?php wp_nonce_field('leads_ajax_nonce', 'leadsSecurity', true); ?>
            <input type="hidden" name="agent_id" id="agent_id"  value="<?php echo esc_attr($agent_id); ?>">
            <input type="hidden" name="user_id" id="user_id"  value="<?php echo esc_attr($current_user->ID); ?>">

            <div class="mt-4 mt-md-5 pxp-my-leads-charts">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="pxp-chart-container rounded-lg">
                            <h3><?php esc_html_e('Leads', 'resideo'); ?></h3>
                            <div class="mt-3 mt-md-4 pxp-chart-legend-number">
                                <span class="pxp-chart-legend-number-total">0</span>
                                <span class="pxp-chart-legend-number-percent"></span>
                                <span class="pxp-chart-legend-number-vs"></span>
                            </div>
                            <div class="mt-3 mt-md-4">
                                <canvas id="pxp-leads-chart"></canvas>
                            </div>
                            <div class="mt-3 mt-md-4">
                                <select id="pxp-leads-chart-period" class="custom-select pxp-leads-chart-period-margin">
                                    <option value="-7 days"><?php esc_html_e('Last 7 days', 'resideo'); ?></option>
                                    <option value="-30 days"><?php esc_html_e('Last 30 days', 'resideo'); ?></option>
                                    <option value="-60 days"><?php esc_html_e('Last 60 days', 'resideo'); ?></option>
                                    <option value="-90 days"><?php esc_html_e('Last 90 days', 'resideo'); ?></option>
                                    <option value="-12 months"><?php esc_html_e('Last 12 months', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="pxp-chart-container rounded-lg mt-4 mt-md-5 mt-xl-0">
                            <h3><?php esc_html_e('Contacts', 'resideo'); ?></h3>
                            <div class="mt-3 mt-md-4">
                                <canvas id="pxp-contacts-chart"></canvas>
                            </div>
                            <div class="mt-3 mt-md-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="pxp-contacts-legend-text"><span class="pxp-contacts-legend-color-yes"></span><?php esc_html_e('Contacted', 'resideo'); ?></div>
                                        <div class="pxp-contacts-legend-percent">
                                            <span class="pxp-contacts-legend-percent-yes-total"></span>
                                            <div class="pxp-contacts-legend-percent-yes-diff"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="pxp-contacts-legend-text"><span class="pxp-contacts-legend-color-no"></span><?php esc_html_e('Not contacted', 'resideo'); ?></div>
                                        <div class="pxp-contacts-legend-percent">
                                            <span class="pxp-contacts-legend-percent-no-total"></span>
                                            <div class="pxp-contacts-legend-percent-no-diff"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 mt-md-4">
                                <select id="pxp-contacts-chart-period" class="custom-select pxp-contacts-chart-period-margin">
                                    <option value="-7 days"><?php esc_html_e('Last 7 days', 'resideo'); ?></option>
                                    <option value="-30 days"><?php esc_html_e('Last 30 days', 'resideo'); ?></option>
                                    <option value="-60 days"><?php esc_html_e('Last 60 days', 'resideo'); ?></option>
                                    <option value="-90 days"><?php esc_html_e('Last 90 days', 'resideo'); ?></option>
                                    <option value="-12 months"><?php esc_html_e('Last 12 months', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="pxp-chart-container rounded-lg mt-4 mt-md-5 mt-xl-0">
                            <h3><?php esc_html_e('Score', 'resideo'); ?></h3>
                            <div class="mt-3 mt-md-4">
                                <canvas id="pxp-score-chart"></canvas>
                            </div>
                            <div class="mt-3 mt-md-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="pxp-score-legend-text"><span class="pxp-score-legend-color-engaged"></span><?php esc_html_e('Engaged', 'resideo'); ?></div>
                                        <div class="pxp-score-legend-percent">
                                            <span class="pxp-score-legend-percent-engaged-total"></span>
                                            <div class="pxp-score-legend-percent-engaged-diff"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="pxp-score-legend-text"><span class="pxp-score-legend-color-ready"></span><?php esc_html_e('Ready', 'resideo'); ?></div>
                                        <div class="pxp-score-legend-percent">
                                            <span class="pxp-score-legend-percent-ready-total"></span>
                                            <div class="pxp-score-legend-percent-ready-diff"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="pxp-score-legend-text"><span class="pxp-score-legend-color-fit"></span><?php esc_html_e('Fit', 'resideo'); ?></div>
                                        <div class="pxp-score-legend-percent">
                                            <span class="pxp-score-legend-percent-fit-total"></span>
                                            <div class="pxp-score-legend-percent-fit-diff"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="pxp-score-legend-text"><span class="pxp-score-legend-color-none"></span><?php esc_html_e('None', 'resideo'); ?></div>
                                        <div class="pxp-score-legend-percent">
                                            <span class="pxp-score-legend-percent-none-total"></span>
                                            <div class="pxp-score-legend-percent-none-diff"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 mt-md-4">
                                <select id="pxp-score-chart-period" class="custom-select">
                                    <option value="-7 days"><?php esc_html_e('Last 7 days', 'resideo'); ?></option>
                                    <option value="-30 days"><?php esc_html_e('Last 30 days', 'resideo'); ?></option>
                                    <option value="-60 days"><?php esc_html_e('Last 60 days', 'resideo'); ?></option>
                                    <option value="-90 days"><?php esc_html_e('Last 90 days', 'resideo'); ?></option>
                                    <option value="-12 months"><?php esc_html_e('Last 12 months', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mt-md-5 pxp-my-leads-header">
                <div class="row">
                    <div class="col-6">
                        <h3><?php echo esc_html($total_leads) . ' ' . __('Leads', 'resideo'); ?></h3>
                    </div>
                    <div class="col-6 align-right">
                        <a href="javascript:void(0);" class="btn pxp-my-leads-new-lead-btn"><?php esc_html_e('Add New Lead', 'resideo'); ?></a>
                    </div>
                </div>
            </div>

            <div class="mt-3 mt-md-4 pxp-my-leads-search">
                <form id="pxp-my-leads-search-form" role="search" method="get" action="<?php echo esc_url(resideo_get_myleads_url()); ?>">
                    <div class="row pxp-my-leads-search-for-row">
                        <div class="col-md-3 pxp-my-leads-search-for-col">
                            <div class="form-group">
                                <input type="text" class="form-control" id="lead_name" name="lead_name" placeholder="<?php esc_attr_e('Search by name', 'resideo'); ?>" value="<?php echo esc_attr($search_name); ?>">
                            </div>
                        </div>
                        <div class="col-md-2 pxp-my-leads-search-for-col">
                            <div class="form-group">
                                <select name="lead_contacted" id="lead_contacted" class="custom-select">
                                    <option value=""><?php esc_html_e('Contacted', 'resideo'); ?></option>
                                    <option value="no" <?php selected($search_contacted, 'no') ?>><?php esc_html_e('No', 'resideo'); ?></option>
                                    <option value="yes" <?php selected($search_contacted, 'yes'); ?>><?php esc_html_e('Yes', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-10 col-md-2 pxp-my-leads-search-for-col">
                            <div class="form-group">
                                <select name="lead_score" id="lead_score" class="custom-select">
                                    <option value=""><?php esc_html_e('Score', 'resideo'); ?></option>
                                    <option value="0" <?php selected($search_score, '0') ?>><?php esc_html_e('None', 'resideo'); ?></option>
                                    <option value="1" <?php selected($search_score, '1') ?>><?php esc_html_e('Fit', 'resideo'); ?></option>
                                    <option value="2" <?php selected($search_score, '2') ?>><?php esc_html_e('Ready', 'resideo'); ?></option>
                                    <option value="3" <?php selected($search_score, '3') ?>><?php esc_html_e('Engaged', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2 col-md-2 pxp-my-leads-search-for-col">
                            <div class="form-group">
                                <button type="submit" class="btn pxp-my-leads-search-btn"><span class="fa fa-search"></span></button>
                            </div>
                        </div>
                        <div class="col-md-3 align-right pxp-my-leads-search-for-col">
                            <div class="form-group">
                                <select name="sort" id="sort" class="custom-select">
                                    <option value="date" <?php selected($sort, 'date' || ''); ?>><?php esc_html_e('Date', 'resideo'); ?></option>
                                    <option value="name" <?php selected($sort, 'name'); ?>><?php esc_html_e('Name', 'resideo'); ?></option>
                                    <option value="score" <?php selected($sort, 'score'); ?>><?php esc_html_e('Score', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($total_leads != 0) { ?>
                <div class="mt-3 mt-md-4 pxp-my-leads-list">
                    <?php while ($agent_leads->have_posts()) {
                        $agent_leads->the_post();

                        $lead_id = get_the_ID();

                        $email     = get_post_meta($lead_id, 'lead_email', true);
                        $phone     = get_post_meta($lead_id, 'lead_phone', true);
                        $contacted = get_post_meta($lead_id, 'lead_contacted', true);
                        $score     = get_post_meta($lead_id, 'lead_score', true);
                        $notes     = get_post_meta($lead_id, 'lead_notes', true);
                        $user_id   = get_post_meta($lead_id, 'lead_user', true);

                        $created = get_the_date('Y-m-d'); ?>

                        <div class="pxp-my-leads-item rounded-lg" data-id="<?php echo esc_attr($lead_id); ?>" data-notes="<?php echo esc_attr($notes); ?>" data-uid="<?php echo esc_attr($user_id); ?>">
                            <div class="row align-items-center">
                                <div class="col-9 col-sm-8 col-lg-10">
                                    <div class="row align-items-center">
                                        <div class="col-lg-5">
                                            <div class="pxp-my-leads-item-name" data-name="<?php the_title(); ?>"><?php the_title(); ?></div>
                                            <div class="pxp-my-leads-item-email" data-email="<?php echo esc_attr($email); ?>">
                                                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                            </div>
                                            <div class="pxp-my-leads-item-phone" data-phone="<?php echo esc_attr($phone); ?>"><?php echo esc_attr($phone); ?></div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="pxp-my-leads-item-contacted mt-1 mt-lg-0" data-contacted="<?php echo esc_attr($contacted); ?>">
                                                        <?php switch ($contacted) {
                                                            case 'no':
                                                                esc_html_e('Not contacted', 'resideo');
                                                                break;
                                                            case 'yes':
                                                                esc_html_e('Contacted', 'resideo');
                                                                break;
                                                            default:
                                                                esc_html_e('Not contacted', 'resideo');
                                                                break;
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="pxp-my-leads-item-score mb-1 mb-lg-0" data-score="<?php echo esc_attr($score); ?>">
                                                        <?php switch ($score) {
                                                            case '0':
                                                                esc_html_e('None', 'resideo');
                                                                break;
                                                            case '1':
                                                                esc_html_e('Fit', 'resideo');
                                                                break;
                                                            case '2':
                                                                esc_html_e('Ready', 'resideo');
                                                                break;
                                                            case '3':
                                                                esc_html_e('Engaged', 'resideo');
                                                                break;
                                                            default:
                                                                esc_html_e('None', 'resideo');
                                                                break;
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="pxp-my-leads-item-date"><?php echo esc_html($created); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 col-sm-4 col-lg-2">
                                    <div class="pxp-my-leads-item-actions">
                                        <a href="javascript:void(0);" class="pxp-my-leads-item-edit"><span class="fa fa-pencil"></span></a>
                                        <a href="javascript:void(0);" class="pxp-my-leads-item-delete" data-toggle="modal" data-target="#pxp-my-leads-delete-lead-modal"><span class="fa fa-trash-o"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }

                    resideo_pagination($agent_leads->max_num_pages); ?>
                </div>
            <?php } ?>

            <div class="mt-4 mt-md-5 pxp-my-leads-new-lead-form">
                <h3><?php esc_html_e('Lead Details', 'resideo'); ?></h3>

                <input type="hidden" id="pxp-lead-field-id" name="pxp-lead-field-id">
                <input type="hidden" id="pxp-lead-field-uid" name="pxp-lead-field-uid">

                <div class="mt-3 mt-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pxp-lead-field-name"><?php esc_html_e('Name', 'resideo'); ?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="pxp-lead-field-name" name="pxp-lead-field-name" placeholder="<?php esc_html_e('Enter lead name', 'resideo'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="pxp-lead-field-email"><?php esc_html_e('Email', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-lead-field-email" name="pxp-lead-field-email" placeholder="<?php esc_html_e('Enter lead email', 'resideo'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="pxp-lead-field-phone"><?php esc_html_e('Phone', 'resideo'); ?></label>
                                <input type="text" class="form-control" id="pxp-lead-field-phone" name="pxp-lead-field-phone" placeholder="<?php esc_html_e('Enter lead phone', 'resideo'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="pxp-lead-field-contacted"><?php esc_html_e('Contacted', 'resideo'); ?></label>
                                <select name="pxp-lead-field-contacted" id="pxp-lead-field-contacted" class="custom-select">
                                    <option value="no"><?php esc_html_e('No', 'resideo'); ?></option>
                                    <option value="yes"><?php esc_html_e('Yes', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="form-group">
                                <label for="pxp-lead-field-score"><?php esc_html_e('Score', 'resideo'); ?></label>
                                <select name="pxp-lead-field-score" id="pxp-lead-field-score" class="custom-select">
                                    <option value="0"><?php esc_html_e('None', 'resideo'); ?></option>
                                    <option value="1"><?php esc_html_e('Fit', 'resideo'); ?></option>
                                    <option value="2"><?php esc_html_e('Ready', 'resideo'); ?></option>
                                    <option value="3"><?php esc_html_e('Engaged', 'resideo'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 mt-md-4">
                    <ul class="nav nav-tabs pxp-lead-field-tabs" id="pxp-lead-field-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pxp-lead-messages-tab" data-toggle="tab" href="#pxp-lead-messages-tab-panel" role="tab" aria-controls="messages" aria-selected="true"><?php esc_html_e('Messages', 'resideo'); ?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pxp-lead-notes-tab" data-toggle="tab" href="#pxp-lead-notes-tab-panel" role="tab" aria-controls="notes" aria-selected="false"><?php esc_html_e('Notes', 'resideo'); ?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pxp-lead-wishlist-tab" data-toggle="tab" href="#pxp-lead-wishlist-tab-panel" role="tab" aria-controls="wishlist" aria-selected="false"><?php esc_html_e('Wish List', 'resideo'); ?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pxp-lead-searches-tab" data-toggle="tab" href="#pxp-lead-searches-tab-panel" role="tab" aria-controls="searches" aria-selected="false"><?php esc_html_e('Saved Searches', 'resideo'); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="pxpLeadFieldTabsContent">
                        <div class="tab-pane fade show active" id="pxp-lead-messages-tab-panel" role="tabpanel" aria-labelledby="pxp-lead-messages-tab">
                            <img src="<?php print esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader" alt="...">
                            <span class="pxp-loader-text"><?php esc_html_e('Loading messages...', 'resideo'); ?></span>
                        </div>
                        <div class="tab-pane fade" id="pxp-lead-notes-tab-panel" role="tabpanel" aria-labelledby="pxp-lead-notes-tab">
                            <textarea class="form-control" id="pxp-lead-field-notes" name="pxp-lead-field-notes" rows="6" placeholder="<?php esc_html_e('Write your notes here...', 'resideo'); ?>"></textarea>
                        </div>
                        <div class="tab-pane fade" id="pxp-lead-wishlist-tab-panel" role="tabpanel" aria-labelledby="pxp-lead-wishlist-tab">
                            <img src="<?php print esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader" alt="...">
                            <span class="pxp-loader-text"><?php esc_html_e('Loading wish list...', 'resideo'); ?></span>
                        </div>
                        <div class="tab-pane fade" id="pxp-lead-searches-tab-panel" role="tabpanel" aria-labelledby="pxp-lead-searches-tab">
                            <img src="<?php print esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader" alt="...">
                            <span class="pxp-loader-text"><?php esc_html_e('Loading searches...', 'resideo'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mt-md-5">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" class="btn pxp-my-leads-submit-lead-btn">
                                <span class="pxp-my-leads-submit-lead-btn-text">
                                    <?php esc_html_e('Add Lead', 'resideo'); ?>
                                </span>
                                <span class="pxp-my-leads-submit-lead-btn-sending">
                                    <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                    <?php esc_html_e('Adding...', 'resideo'); ?>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="btn pxp-my-leads-update-lead-btn">
                                <span class="pxp-my-leads-update-lead-btn-text">
                                    <?php esc_html_e('Update Lead', 'resideo'); ?>
                                </span>
                                <span class="pxp-my-leads-update-lead-btn-sending">
                                    <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                                    <?php esc_html_e('Updating...', 'resideo'); ?>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="btn pxp-my-leads-cancel-lead-btn"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                        </div>
                        <div class="col-6 align-right">
                            <a href="javascript:void(0);" class="btn pxp-my-leads-delete-lead-btn" data-toggle="modal" data-target="#pxp-my-leads-delete-lead-modal"><span class="fa fa-trash-o"></span> <?php esc_html_e('Delete Lead', 'resideo'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade pxp-alert-modal" id="pxp-my-leads-alert-modal" role="dialog" aria-labelledby="pxpMyLeadsAlertModallabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pxp-my-leads-response"></div>
        </div>
    </div>
</div>

<div class="modal fade pxp-property-modal" id="pxp-my-leads-delete-lead-modal" tabindex="-1" role="dialog" aria-labelledby="pxpMyLeadsDeleteLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title" id="pxpMyLeadsDeleteLeadModalLabel"><?php _e('Delete Lead', 'resideo'); ?></h5>
                <p class="mt-4"><?php esc_html_e('Are you sure?', 'resideo'); ?></p>
                <div class="mt-4">
                    <a href="javascript:void(0);" class="pxp-my-leads-delete-lead-btn-confirm">
                        <span class="pxp-my-leads-delete-lead-btn-confirm-text"><?php esc_html_e('Delete', 'resideo'); ?></span>
                        <span class="pxp-my-leads-delete-lead-btn-confirm-sending">
                            <img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="...">
                            <?php esc_html_e('Deleting...', 'resideo'); ?>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="pxp-my-leads-delete-lead-btn-cancel" data-dismiss="modal"><?php esc_html_e('Cancel', 'resideo'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>