<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (function_exists('register_block_type')) {
    require_once 'services.php';
    require_once 'recent-properties.php';
    require_once 'featured-properties.php';
    require_once 'single-property.php';
    require_once 'search-properties.php';
    require_once 'areas.php';
    require_once 'featured-agents.php';
    require_once 'membership-plans.php';
    require_once 'recent-posts.php';
    require_once 'featured-posts.php';
    require_once 'testimonials.php';
    require_once 'promo.php';
    require_once 'promo-slider.php';
    require_once 'subscribe.php';
    require_once 'gallery-carousel.php';
    require_once 'numbers.php';
    require_once 'awards.php';
    require_once 'contact.php';
    require_once 'video.php';
}
?>