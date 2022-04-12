<?php
/*
Template Name: Agents List
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$resideo_general_settings    = get_option('resideo_general_settings');
$show_rating                 = isset($resideo_general_settings['resideo_agents_rating_field']) ? $resideo_general_settings['resideo_agents_rating_field'] : '';

$resideo_appearance_settings = get_option('resideo_appearance_settings', '');
$agents_per_page             = isset($resideo_appearance_settings['resideo_agents_per_page_field']) ? $resideo_appearance_settings['resideo_agents_per_page_field'] : 20;
$hide_phone                  = isset($resideo_appearance_settings['resideo_hide_agents_phone_field']) ? $resideo_appearance_settings['resideo_hide_agents_phone_field'] : '';

$keywords = isset($_GET['keywords']) ? sanitize_text_field($_GET['keywords']) : ''; ?>

<div class="pxp-content">
    <div class="pxp-content-wrapper mt-100">
        <?php while (have_posts()) : the_post();
            $post_id = get_the_ID();
            $subtitle = get_post_meta($post_id, 'agents_page_subtitle', true);
            $post_hero = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'pxp-full');
            $search_form = get_post_meta($post_id, 'agents_page_search_form', true); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-7">
                            <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>
                            <p class="pxp-text-light"><?php echo esc_html($subtitle); ?></p>
                        </div>
                    </div>
                </div>

                <div class="pxp-agents-hero mt-4 mt-md-5">
                    <?php if ($post_hero !== false) { ?>
                        <div class="pxp-agents-hero-fig pxp-cover" style="background-image: url(<?php echo esc_url($post_hero[0]); ?>);"></div>
                    <?php } ?>

                    <?php if ($search_form == '1') { ?>
                        <div class="pxp-agents-hero-search-container">
                            <div class="container">
                                <div class="pxp-agents-hero-search">
                                    <h2 class="pxp-section-h2"><?php esc_html_e('Find an Agent', 'resideo'); ?></h2>
                                    <div class="pxp-agents-hero-search-form mt-3 mt-md-4">
                                        <form role="search" method="get" id="pxp-search-agent-form" class="pxp-search-agent-form">
                                            <div class="form-group">
                                                <input type="text" class="form-control pxp-is-address" name="keywords" id="keywords" value="<?php echo esc_attr($keywords); ?>" placeholder="<?php echo esc_attr('Search for...', 'resideo'); ?>">
                                                <button type="submit" aria-label="Search">
                                                    <span aria-hidden="true" class="fa fa-search"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="container mt-200">
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="container">
            <div class="row">
                <?php global $paged;
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;

                $args = array(
                    'posts_per_page' => $agents_per_page,
                    'paged'          => $paged,
                    'post_type'      => 'agent',
                    'orderby'        => 'post_date',
                    'order'          => 'DESC',
                    's'              => $keywords,
                    'post_status'    => array('publish'),
                    'meta_query'     => array(
                        array(
                            'key' => 'agent_type',
                            'value' => 'agent',
                        )
                    )
                );

                $agents = new WP_Query($args);

                if ($agents->have_posts()) {
                    while ($agents->have_posts()): $agents->the_post();
                        $agent_id = get_the_ID();
                        $link     = get_the_permalink();
                        $name     = get_the_title();
                        $avatar   = get_post_meta($agent_id, 'agent_avatar', true);
                        $phone    = get_post_meta($agent_id, 'agent_phone', true); 
                        $email    = get_post_meta($agent_id, 'agent_email', true); ?>

                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <?php $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');

                            if ($avatar_photo != '') {
                                $a_photo = $avatar_photo[0];
                            } else {
                                $a_photo = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
                            } ?>

                            <a href="<?php echo esc_url($link); ?>" class="pxp-agents-1-item">
                                <div class="pxp-agents-1-item-fig-container rounded-lg">
                                    <div class="pxp-agents-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($a_photo); ?>); background-position: top center"></div>
                                </div>
                                <div class="pxp-agents-1-item-details rounded-lg">
                                    <div class="pxp-agents-1-item-details-name"><?php echo esc_html($name); ?></div>
                                    <?php if ($hide_phone != '') { ?>
                                        <div class="pxp-agents-1-item-details-email"><?php echo esc_html($email); ?></div>
                                    <?php } else { ?>
                                        <div class="pxp-agents-1-item-details-phone"><span class="fa fa-phone"></span> <?php echo esc_html($phone); ?></div>
                                    <?php } ?>
                                    <div class="pxp-agents-1-item-details-spacer"></div>
                                    <div class="pxp-agents-1-item-cta text-uppercase"><?php esc_html_e('More Details', 'resideo'); ?></div>
                                </div>
                                <?php if ($show_rating != '') {
                                    print resideo_display_agent_rating(resideo_get_agent_ratings($agent_id), false, 'pxp-agents-1-item-rating');
                                } ?>
                            </a>
                        </div>
                    <?php endwhile;

                    wp_reset_query();
                    wp_reset_postdata(); 
                } else { ?>
                    <div class="col"><?php esc_html_e('No agents found', 'resideo'); ?></div>
                <?php } ?>
            </div>

            <?php if ($agents->max_num_pages > 1) { ?>
                <ul class="pagination pxp-paginantion mt-2 mt-md-3">
                    <li class="page-item"><?php next_posts_link(__('Next Page', 'resideo') . '&nbsp;&nbsp;<span class="fa fa-angle-right"></span>', esc_html($agents->max_num_pages)); ?></li>
                    <li class="page-item"><?php previous_posts_link('<span class="fa fa-angle-left"></span>&nbsp;&nbsp;' . __('Previous Page', 'resideo')); ?></li>
                </ul>
            <?php } ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>