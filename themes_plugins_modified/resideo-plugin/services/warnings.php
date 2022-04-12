<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_pages_admin_notice')) :
    function resideo_pages_admin_notice() { 
        $pages = array(
            'Account Settings' => 'account-settings.php',
            'My Leads'         => 'my-leads.php',
            'My Properties'    => 'my-properties.php',
            'Property Search'  => 'property-search.php',
            'Saved Searches'   => 'saved-searches.php',
            'Submit Property'  => 'submit-property.php',
            'Wish List'        => 'wish-list.php',
        );
        $templates = array();
        $hide_warning = get_option('dismiss_pages_warning');

        foreach ($pages as $key => $value) {
            $page = get_pages(array(
                'meta_key'   => '_wp_page_template',
                'meta_value' => $value
            ));

            if (!$page) {
                array_push($templates, $key);
            }
        }

        if (count($templates) > 0 && $hide_warning != '1') { ?>
            <div class="notice notice-warning is-dismissible notice-pages">
            <h3><?php esc_html_e('Missing Pages', 'resideo'); ?></h3>
                <p><?php esc_html_e('In order for the theme to function properly, it requires a list of pages having the following templates', 'resideo'); ?>:</p>
                <p>
                    <?php $i = 0;
                    foreach ($templates as $template) {
                        echo '<strong style="color: #e80022;">' . esc_html($template) . '</strong>';

                        if ($i < count($templates) - 1) {
                            echo ', ';
                        }

                        $i++;
                    } ?>
                </p>
                <p style="color: #999;"><i><?php esc_html_e('All the pages from the list are included in the theme demos. If you choose to import a demo, ignore this warning.', 'resideo'); ?></i></p>
                <?php wp_nonce_field('dismisspageswarning_ajax_nonce', 'security-dismiss-pages-warning', true); ?>
            </div>
        <?php }
    }
endif;
add_action('admin_notices', 'resideo_pages_admin_notice');

if (!function_exists('resideo_dismiss_pages_warning')): 
    function resideo_dismiss_pages_warning() {
        check_ajax_referer('dismisspageswarning_ajax_nonce', 'security');

        update_option('dismiss_pages_warning', '1');

        exit();
        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_dismiss_pages_warning', 'resideo_dismiss_pages_warning');
add_action('wp_ajax_resideo_dismiss_pages_warning', 'resideo_dismiss_pages_warning');
?>