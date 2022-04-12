<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_share_menu')):
    function resideo_get_share_menu($post_id, $position = 'right') { 
        $position_class = 'dropdown-menu-right';
        if ($position == 'left') {
            $position_class = 'dropdown-menu-left';
        } ?>

        <div class="dropdown">
            <a class="pxp-sp-top-btn" href="javascript:void(0);" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-share-alt"></span> <?php esc_html_e('Share', 'resideo'); ?>
            </a>
            <div class="dropdown-menu <?php echo esc_attr($position_class); ?>" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink($post_id)); ?>"
                    onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                    target="_blank">
                    <span class="fa fa-facebook"></span> Facebook
                </a>
                <a class="dropdown-item" href="https://twitter.com/share?url=<?php echo esc_url(get_permalink($post_id)); ?>&amp;text=<?php echo urlencode(get_the_title()); ?>"
                    onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                    target="_blank">
                    <span class="fa fa-twitter"></span> Twitter
                </a>
                <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
                <a class="dropdown-item" href="https://www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" data-pin-custom="true">
                    <span class="fa fa-pinterest"></span> Pinterest
                </a>
                <a class="dropdown-item" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink($post_id)); ?>&title=<?php echo urlencode(get_the_title()); ?>" 
                    target="_blank">
                    <span class="fa fa-linkedin"></span> LinkedIn
                </a>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('resideo_get_post_share_menu')):
    function resideo_get_post_share_menu($post_id) { ?>
        <div class="col-sm-12 col-lg-1">
            <div class="pxp-blog-post-share">
                <div class="pxp-blog-post-share-label"><?php esc_html_e('Share', 'resideo'); ?></div>
                <ul class="list-unstyled mt-3">
                    <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink($post_id)); ?>"
                            onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                            target="_blank" title="<?php esc_html_e('Share on Facebook', 'resideo'); ?>">
                            <span class="fa fa-facebook"></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/share?url=<?php echo esc_url(get_permalink($post_id)); ?>&amp;text=<?php echo urlencode(get_the_title()); ?>"
                            onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                            target="_blank" title="<?php esc_html_e('Share on Twitter', 'resideo'); ?>">
                            <span class="fa fa-twitter"></span>
                        </a>
                    </li>
                    <li>
                        <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
                        <a href="https://www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" data-pin-custom="true">
                            <span class="fa fa-pinterest"></span>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink($post_id)); ?>&title=<?php echo urlencode(get_the_title()); ?>" 
                            title="<?php esc_html_e('Share on LinkedIn', 'resideo'); ?>" target="_blank">
                            <span class="fa fa-linkedin"></span>
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php }
endif;
?>