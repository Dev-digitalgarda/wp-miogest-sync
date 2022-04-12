<?php
/*
Template Name: dsIDXPress Map Left
*/

/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();
?>

<div class="pxp-content pxp-full-height pxp-idx-map-half pxp-idx-map-left pxp-idx-listings-page">
    <?php while(have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="pxp-map-side pxp-map-left pxp-half">
                <a href="javascript:void(0);" class="pxp-idx-list-toggle"><span class="fa fa-list"></span></a>
            </div>
            <div class="pxp-content-side pxp-content-right pxp-half">
                <div class="pxp-content-side-wrapper">
                    <?php if (is_active_sidebar('pxp-idx-search-widget-area')) {
                        dynamic_sidebar('pxp-idx-search-widget-area');
                    }
                    the_content(); ?>
                </div>

                <?php get_footer('split'); ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>