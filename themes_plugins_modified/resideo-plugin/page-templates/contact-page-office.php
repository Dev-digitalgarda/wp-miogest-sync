<?php
/*
Template Name: Contact Page Office
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$is_map = false;
if (wp_script_is('gmaps', 'enqueued')) {
    $is_map = true;
} ?>

<div class="pxp-content">
    <?php while(have_posts()) : the_post();
        $post_id = get_the_ID();

        $subtitle = get_post_meta($post_id, 'contact_page_office_subtitle', true);
        $email    = get_post_meta($post_id, 'contact_page_office_email', true);

        $office_title     = get_post_meta($post_id, 'contact_page_single_office_title', true);
        $office_address_1 = get_post_meta($post_id, 'contact_page_single_office_address_line_1', true);
        $office_address_2 = get_post_meta($post_id, 'contact_page_single_office_address_line_2', true);
        $office_phone     = get_post_meta($post_id, 'contact_page_single_office_phone', true);
        $office_email     = get_post_meta($post_id, 'contact_page_single_office_email', true);
        $office_lat       = get_post_meta($post_id, 'contact_page_single_office_lat', true);
        $office_lng       = get_post_meta($post_id, 'contact_page_single_office_lng', true); 

        $page_margin_bottom = get_post_meta($post_id, 'contact_page_margin_bottom', true); ?>

        <div class="pxp-content-wrapper mt-100">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>
                        <p class="pxp-text-light"><?php echo esc_html($subtitle); ?></p>
                    </div>
                </div>
            </div>

            <?php if ($is_map === true) { ?>
                <div id="pxp-contact-office-map" class="mt-4 mt-md-5" data-lat="<?php echo esc_attr($office_lat); ?>" data-lng="<?php echo esc_attr($office_lng); ?>"></div>
            <?php } ?>

            <div class="container mt-100">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <h2 class="pxp-section-h2"><?php esc_html_e('Contact Details', 'resideo'); ?></h2>
                        <div class="pxp-contact-hero-offices-title pxp-is-office mt-3 mt-md-4"><?php echo esc_html($office_title); ?></div>
                        <div class="pxp-contact-hero-offices-info pxp-is-office mt-2 mt-md-3">
                            <p class="pxp-is-address"><?php echo esc_html($office_address_1); ?><br><?php echo esc_html($office_address_2); ?></p>
                            <p>
                                <?php echo esc_html($office_phone); ?><br>
                                <a href="mailto:<?php echo esc_attr($office_email); ?>"><?php echo esc_html($office_email); ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-8">
                        <h2 class="pxp-section-h2"><?php esc_html_e('Send Us A Message', 'resideo'); ?></h2>
                        <div class="pxp-contact-form mt-3 mt-md-4">
                            <div class="pxp-contact-form-response"></div>
                            <input type="hidden" id="pxp-contact-form-company-email" value="<?php echo esc_attr($email); ?>">

                            <?php $contact_fields_settings = get_option('resideo_contact_fields_settings');

                            $has_fields = false;
                            if (is_array($contact_fields_settings)) {
                                if (count($contact_fields_settings)) {
                                    $has_fields = true; ?>

                                    <div class="row">
                                        <div class="<?php echo esc_attr($contact); ?>">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-form-email" placeholder="<?php esc_attr_e('Email', 'resideo'); ?>">
                                            </div>
                                        </div>

                                        <?php uasort($contact_fields_settings, "resideo_compare_position");

                                        foreach ($contact_fields_settings as $key => $value) {
                                            $is_optional = $value['mandatory'] == 'no' ? '(' . __('optional', 'resideo') . ')' : '';

                                            switch ($value['type']) {
                                                case 'text_input_field': ?>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" data-type="text_input_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                        </div>
                                                    </div>
                                                <?php break;
                                                case 'textarea_field': ?>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <textarea data-type="textarea_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-field" rows="6" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>"></textarea>
                                                        </div>
                                                    </div>
                                                <?php break;
                                                case 'select_field': 
                                                    $list = explode(',', $value['list']); ?>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <select data-type="select_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="custom-select pxp-js-contact-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>">
                                                                <option value="<?php esc_attr_e('None', 'resideo'); ?>"><?php echo esc_html($value['label']); ?> <?php echo esc_attr($is_optional); ?></option>
                                                                <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                                    <option value="<?php echo esc_html($list[$i]); ?>"><?php echo esc_html($list[$i]); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php break;
                                                case 'checkbox_field': ?>
                                                    <div class="col-12">
                                                        <div class="form-group form-check">
                                                            <input data-type="checkbox_field" type="checkbox" class="form-check-input pxp-js-contact-field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>"> <label class="form-check-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?></label>
                                                        </div>
                                                    </div>
                                                <?php break;
                                                case 'date_field': ?>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <input data-type="date_field" type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-field date-picker" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                        </div>
                                                    </div>
                                                <?php break;
                                            }
                                        } ?>
                                    </div>
                                <?php }
                            } ?>

                            <?php if ($has_fields === false) { ?>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pxp-contact-form-name" placeholder="<?php esc_attr_e('Name', 'resideo'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pxp-contact-form-email" placeholder="<?php esc_attr_e('Email', 'resideo'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <select class="custom-select" id="pxp-contact-form-reg">
                                                <option value=""><?php esc_html_e('What is this regarding?', 'resideo'); ?></option>
                                                <option value="<?php esc_attr_e('Customer support / feedback', 'resideo'); ?>"><?php esc_html_e('Customer support / feedback', 'resideo'); ?></option>
                                                <option value="<?php esc_attr_e('Applying', 'resideo'); ?>"><?php esc_html_e('Applying', 'resideo'); ?></option>
                                                <option value="<?php esc_attr_e('Press', 'resideo'); ?>"><?php esc_html_e('Press', 'resideo'); ?></option>
                                                <option value="<?php esc_attr_e('Listings', 'resideo'); ?>"><?php esc_html_e('Listings', 'resideo'); ?></option>
                                                <option value="<?php esc_attr_e('Partnerships', 'resideo'); ?>"><?php esc_html_e('Partnerships', 'resideo'); ?></option>
                                                <option value="<?php esc_attr_e('General questions', 'resideo'); ?>"><?php esc_html_e('General questions', 'resideo'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pxp-contact-form-phone" placeholder="<?php esc_attr_e('Phone (optional)', 'resideo'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="pxp-contact-form-message" rows="6" placeholder="<?php esc_attr_e('Message', 'resideo'); ?>"></textarea>
                                </div>
                            <?php } ?>

                            <a href="javascript:void(0);" class="btn pxp-contact-form-btn" data-custom="<?php echo esc_attr($has_fields); ?>">
                                <span class="pxp-contact-form-btn-text"><?php esc_html_e('Send Message', 'resideo'); ?></span>
                                <span class="pxp-contact-form-btn-sending"><img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Sending...', 'resideo'); ?></span>
                            </a>
                            <?php wp_nonce_field('contact_form_page_ajax_nonce', 'contact_page_security', true, true); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php the_content(); ?>

        </div>
    <?php endwhile; ?>
</div>

<?php if ($page_margin_bottom == '1') {
    get_footer();
} else {
    get_footer('nospace');
} ?>