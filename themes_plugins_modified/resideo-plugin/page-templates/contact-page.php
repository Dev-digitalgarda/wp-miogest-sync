<?php
/*
Template Name: Contact Page
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$form_column_class = 'col-12';
$field_column_class = 'col-sm-12 col-md-6 col-lg-3';
$is_map = false;
if (wp_script_is('gmaps', 'enqueued')) {
    $form_column_class = 'col-sm-12 col-lg-6';
    $field_column_class = 'col-sm-12 col-md-6';
    $is_map = true;
}
?>

<div class="pxp-content">
    <?php while(have_posts()) : the_post();
        $post_id = get_the_ID();
        $subtitle = get_post_meta($post_id, 'contact_page_subtitle', true);
        $email = get_post_meta($post_id, 'contact_page_email', true);

        $post_hero = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'pxp-full');
        $page_hero = RESIDEO_PLUGIN_PATH . 'images/ph-full.jpg';
        if ($post_hero !== false) {
            $page_hero = $post_hero[0];
        }

        $offices = get_post_meta($post_id, 'contact_page_offices', true);
        $offices_list = json_decode($offices, true); ?>

        <div class="pxp-content-wrapper mt-100">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>
                        <p class="pxp-text-light"><?php echo esc_html($subtitle); ?></p>
                    </div>
                </div>
            </div>

            <div class="pxp-contact-hero mt-4 mt-md-5">
                <div class="pxp-contact-hero-fig pxp-cover" style="background-image: url(<?php echo esc_url($page_hero); ?>);"></div>

                <div class="pxp-contact-hero-offices-container">
                    <div class="container">
                        <div class="pxp-contact-hero-offices">
                            <h2 class="pxp-section-h2"><?php esc_html_e('Our Offices', 'resideo'); ?></h2>
                            <div class="row">
                                <?php if ($offices_list && is_array($offices_list)) {
                                    foreach ($offices_list as $office) { ?>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="pxp-contact-hero-offices-title mt-3 mt-md-4"><?php echo esc_html($office['title']); ?></div>
                                            <div class="pxp-contact-hero-offices-info mt-2 mt-md-3">
                                                <p class="pxp-is-address"><?php echo esc_html($office['address1']); ?><br><?php echo esc_html($office['address2']); ?></p>
                                                <p>
                                                    <?php echo esc_html($office['phone']); ?><br>
                                                    <a href="mailto:<?php echo esc_attr($office['email']); ?>"><?php echo esc_html($office['email']); ?></a>
                                                </p>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="container mt-200">
                <div class="row">
                    <div class="<?php echo esc_attr($form_column_class); ?>">
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
                                        <div class="<?php echo esc_attr($field_column_class); ?>">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-form-email" placeholder="<?php esc_attr_e('Email', 'resideo'); ?>">
                                            </div>
                                        </div>

                                        <?php uasort($contact_fields_settings, "resideo_compare_position");

                                        foreach ($contact_fields_settings as $key => $value) {
                                            $is_optional = $value['mandatory'] == 'no' ? '(' . __('optional', 'resideo') . ')' : '';

                                            switch ($value['type']) {
                                                case 'text_input_field': ?>
                                                    <div class="<?php echo esc_attr($field_column_class); ?>">
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
                                                    <div class="<?php echo esc_attr($field_column_class); ?>">
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
                                                    <div class="<?php echo esc_attr($field_column_class); ?>">
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
                                    <div class="<?php echo esc_attr($field_column_class); ?>">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pxp-contact-form-name" placeholder="<?php esc_attr_e('Name', 'resideo'); ?>">
                                        </div>
                                    </div>
                                    <div class="<?php echo esc_attr($field_column_class); ?>">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pxp-contact-form-email" placeholder="<?php esc_attr_e('Email', 'resideo'); ?>">
                                        </div>
                                    </div>
                                    <div class="<?php echo esc_attr($field_column_class); ?>">
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
                                    <div class="<?php echo esc_attr($field_column_class); ?>">
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
                    <?php if ($offices_list && $is_map === true) { ?>
                        <div class="col-sm-12 col-lg-6">
                            <div class="row mt-4 mt-md-5 mt-lg-0">
                                <div class="col-6">
                                    <h2 class="pxp-section-h2"><?php esc_html_e('Our Locations', 'resideo'); ?></h2>
                                </div>
                                <div class="col-6 text-right pxp-contact-locations-select-container">
                                    <select class="custom-select pxp-contact-locations-select">
                                        <?php if (is_array($offices_list)) {
                                            foreach ($offices_list as $office) { ?>
                                                <option data-lat="<?php echo esc_attr($office['lat']); ?>" data-lng="<?php echo esc_attr($office['lng']); ?>"><?php echo esc_html($office['title']); ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="pxp-contact-map" class="mt-3"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>