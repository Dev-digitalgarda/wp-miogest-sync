<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Columns shortcode
 */
if (!function_exists('resideo_columns_shortcode')): 
    function resideo_columns_shortcode($attrs, $content = null) {
        extract(shortcode_atts(array(
            'type' => '',
        ), $attrs));

        $return_string = '';

        switch ($type) {
            case 'one_half':
                $return_string .= '<div class="col-xs-12 col-sm-6 pb-40">' . $content . '</div>';
                break;
            case 'one_half_last':
                $return_string .= '<div class="col-xs-12 col-sm-6 pb-40">' . $content . '</div>
                                    <div class="clearfix"></div>';
                break;
            case 'one_third':
                $return_string .= '<div class="col-xs-12 col-sm-4 pb-40">' . $content . '</div>';
                break;
            case 'one_third_last':
                $return_string .= '<div class="col-xs-12 col-sm-4 pb-40">' . $content . '</div>
                                    <div class="clearfix"></div>';
                break;
            case 'one_fourth':
                $return_string .= '<div class="col-xs-12 col-sm-3 pb-40">' . $content . '</div>';
                break;
            case 'one_fourth_last':
                $return_string .= '<div class="col-xs-12 col-md-3 pb-40">' . $content . '</div>
                                    <div class="clearfix"></div>';
                break;
            case 'two_third':
                $return_string .= '<div class="col-xs-12 col-sm-8 pb-40">' . $content . '</div>';
                break;
            case 'two_third_last':
                $return_string .= '<div class="col-xs-12 col-sm-8 pb-40">' . $content . '</div>
                                    <div class="clearfix"></div>';
                break;
            case 'three_fourth':
                $return_string .= '<div class="col-xs-12 col-sm-9 pb-40">' . $content . '</div>';
                break;
            case 'three_fourth_last':
                $return_string .= '<div class="col-xs-12 col-sm-9 pb-40">' . $content . '</div>
                                    <div class="clearfix"></div>';
                break;
        }

        wp_reset_query();

        return $return_string;
    }
endif;
?>