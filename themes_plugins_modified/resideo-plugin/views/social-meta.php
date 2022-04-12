<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_social_meta')):
    function resideo_get_social_meta() {
        if (is_single() && !is_singular('property') && have_posts()) { 
            $fb_post_id      = get_the_ID();
            $fb_post_image   = wp_get_attachment_image_src(get_post_thumbnail_id($fb_post_id), 'single-post-thumbnail');
            $fb_post_excerpt = resideo_get_excerpt_by_id($fb_post_id);
            $fb_post_title   = get_the_title(); ?>

            <meta property="og:url" content="<?php the_permalink(); ?>" />
            <meta property="og:title" content="<?php echo esc_attr($fb_post_title); ?>" />
            <meta property="og:description" content="<?php echo esc_attr($fb_post_excerpt); ?>" />
            <meta property="og:image" content="<?php echo esc_url($fb_post_image[0]); ?>" />
        <?php } else if (is_singular('property') && have_posts()) {
            $fb_post_id       = get_the_ID();
            $fb_post_title    = get_the_title();
            $fb_gallery       = get_post_meta($fb_post_id, 'property_gallery', true);
            $fb_photos        = explode(',', $fb_gallery);
            $fb_first_photo   = wp_get_attachment_image_src($fb_photos[0], 'tile');

            if ($fb_first_photo != '') {
                $fb_p_photo = $fb_first_photo[0];
            } else {
                $fb_p_photo = RESIDEO_PLUGIN_PATH . 'images/property-tile.png';
            } ?>

            <meta property="og:url" content="<?php the_permalink(); ?>" />
            <meta property="og:title" content="<?php echo esc_attr($fb_post_title); ?>" />
            <meta property="og:description" content="<?php echo esc_attr($fb_post_title); ?>" />
            <meta property="og:image" content="<?php echo esc_url($fb_p_photo); ?>" />
        <?php } else { 
            $bloginfo = get_bloginfo('description'); ?>

            <meta property="og:description" content="<?php echo esc_attr($bloginfo); ?>" />
        <?php }
    }
endif;
?>