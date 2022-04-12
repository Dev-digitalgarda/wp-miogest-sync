<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_dashboard_nav')):
    function resideo_get_dashboard_nav() {
        global $current_user;
        global $post;

        $is_agent = resideo_check_user_agent($current_user->ID);
        $template = get_page_template_slug($post->ID); ?>

        <div class="dashboard-nav">
            <ul>
                <?php if ($is_agent === true) { ?>
                    <li><a href="<?php echo esc_url(resideo_get_submit_url()); ?>" class="<?php echo ($template == 'submit-property.php') ? 'active' : ''; ?>"><span class="icon-plus"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('Submit New Property', 'resideo'); ?></span></a></li>
                <?php } ?>

                <li><a href="<?php echo esc_url(resideo_get_wishlist_url()); ?>" class="<?php echo ($template == 'wish-list.php') ? 'active' : ''; ?>"><span class="fa fa-heart-o"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('Wish List', 'resideo'); ?></span></a></li>
                <li><a href="<?php echo esc_url(resideo_get_searches_url()); ?>" class="<?php echo ($template == 'saved-searches.php') ? 'active' : ''; ?>"><span class="fa fa-bookmark-o"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('Saved Searches', 'resideo'); ?></span></a></li>

                <?php if ($is_agent === true) { ?>
                    <li><a href="<?php echo esc_url(resideo_get_myproperties_url()); ?>" class="<?php echo ($template == 'my-properties.php') ? 'active' : ''; ?>"><span class="icon-folder"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('My Properties', 'resideo'); ?></span></a></li>
                    <li><a href="<?php echo esc_url(resideo_get_myleads_url()); ?>" class="<?php echo ($template == 'my-leads.php') ? 'active' : ''; ?>"><span class="icon-notebook"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('My Leads', 'resideo'); ?></span></a></li>
                <?php } ?>

                <li><a href="<?php echo esc_url(resideo_get_account_url()); ?>" class="<?php echo ($template == 'account-settings.php') ? 'active' : ''; ?>"><span class="icon-user"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('Account Settings', 'resideo'); ?></span></a></li>
                <li><a href="<?php echo wp_logout_url(home_url()); ?>"><span class="icon-power"></span> <span class="hidden-xs hidden-sm"><?php esc_html_e('Sign Out', 'resideo'); ?></span></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    <?php }
endif;
?>