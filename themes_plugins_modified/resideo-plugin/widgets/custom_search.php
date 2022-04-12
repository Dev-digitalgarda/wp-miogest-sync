<?php 
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_custom_search_form')): 
    function resideo_custom_search_form($form) {
        $form = '
            <form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
                <div>
                    <label class="screen-reader-text" for="s">' . __('Search for:', 'resideo') . '</label>
                    <input type="text" value="' . get_search_query() . '" name="s" id="s">
                    <button type="submit" aria-label="' . __('Search', 'resideo') . '">
                        <span aria-hidden="true" class="fa fa-search"></span>
                    </button>
                </div>
            </form>';

        return $form;
    }
endif;
add_filter('get_search_form', 'resideo_custom_search_form', 100);
?>