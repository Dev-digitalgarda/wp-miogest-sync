<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_property_video')):
    function resideo_get_property_video($video) { ?>
        <div class="pxp-embed-wrapper">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr($video) ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    <?php }
endif;
?>