<?php
/*
Template Name: dsIDXPress Details
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();
?>

<div class="pxp-content">
     <?php while(have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="pxp-content-wrapper mt-100">
                <div class="container">
                    <h2 class="pxp-sp-top-title"><?php the_title(); ?></h2>

                    <div class="pxp-idx-details">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>