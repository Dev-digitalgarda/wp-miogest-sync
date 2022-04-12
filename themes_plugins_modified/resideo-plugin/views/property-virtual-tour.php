<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_property_virtual_tour')):
    function resideo_get_property_virtual_tour($virtual_tour) { 
        $virtual_tour_allowed_html = array(
            'iframe' => array(
                'align' => true,
                'width' => true,
                'height' => true,
                'frameborder' => true,
                'name' => true,
                'src' => true,
                'id' => true,
                'class' => true,
                'style' => true,
                'scrolling' => true,
                'marginwidth' => true,
                'marginheight' => true,
                'allowfullscreen' => true,
                'allow' => true
            )
        ); ?>
        <div class="pxp-embed-wrapper">
            <?php echo wp_kses($virtual_tour, $virtual_tour_allowed_html); ?>
        </div>
    <?php }
endif;
?>