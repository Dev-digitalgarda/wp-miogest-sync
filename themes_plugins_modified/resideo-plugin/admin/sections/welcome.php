<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_welcome')): 
    function resideo_admin_welcome() {
        add_settings_section('resideo_welcome_section', __('Welcome', 'resideo'), 'resideo_welcome_section_callback', 'resideo_welcome_settings');
    }
endif;

if (!function_exists('resideo_welcome_section_callback')): 
    function resideo_welcome_section_callback() { 
        print '
            <div class="row">
                <div class="col-xs-12 col-sm-2 mb-20 rtl-pull-right">' . __('Theme version', 'resideo') . ': </div>
                <div class="col-xs-12 col-sm-10 mb-20 rtl-pull-right">
                    <strong>' . RESIDEO_VERSION . '</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 mb-20 rtl-pull-right">
                    <a href="http://pixelprime.co/themes/resideo-wp/documentation/" class="ep-link" target="_blank">
                        <span class="fa fa-file-text-o"></span> ' . __('Read the documentation', 'resideo') . '
                    </a>
                </div>
            </div>
        ';
    }
endif;
?>