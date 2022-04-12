<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Agents/Owners rating
 */

if (!function_exists('resideo_display_agent_rating')): 
    function resideo_display_agent_rating($grade, $top, $css_class) {
        if ($top === true) {
            $stars = '<span class="' . esc_attr($css_class) . '">';
        } else {
            $stars = '<div class="' . esc_attr($css_class) . '"><span>';
        }

        for ($i = 0; $i < 5; $i++) {
            if ($grade['avarage'] > $i) {
                $stars .= '<span class="fa fa-star"></span>';
            } else {
                $stars .= '<span class="fa fa-star-o"></span>';
            }
        }

        if ($top === true) {
            $stars .= '</span>';
        } else {
            $stars .= '</span></div>';
        }

        return $stars;
    }
endif;

if (!function_exists('resideo_get_agent_ratings')): 
    function resideo_get_agent_ratings($id) {
        $reviews_array = get_approved_comments($id);
        $count = 1;

        if ($reviews_array) {
            $i = 0;
            $total = 0;

            foreach ($reviews_array as $review){
                $rate = get_comment_meta($review->comment_ID, 'rate');

                if (isset($rate[0]) && $rate[0] !== '') {
                    $i++;
                    $total += $rate[0];
                }
            }

            if ($i == 0) {
                return array('avarage' => 0, 'users' => 0);
            } else {
                return array('avarage' => round($total / $i), 'users' => $i);
            }
        } else {
            return array('avarage' => 0, 'users' => 0);
        }
    }
endif;
?>