<?php
/*
Template Name: dsIDXPress
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();
?>

<div class="pxp-content pxp-idx-listings-page">
     <?php while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="pxp-content-wrapper mt-100">
                <div class="container">
                    <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>

                    <?php if (is_active_sidebar('pxp-idx-search-widget-area')) { ?>
                        <div class="mt-4 mt-md-5">
                            <?php dynamic_sidebar('pxp-idx-search-widget-area'); ?>
                        </div>
                    <?php } ?>

                    <div class="mt-4 mt-md-5">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>