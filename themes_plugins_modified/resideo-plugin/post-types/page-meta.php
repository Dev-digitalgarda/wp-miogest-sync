<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Page header metabox
 */
if (!function_exists('resideo_add_page_metaboxes')): 
    function resideo_add_page_metaboxes() {
        add_meta_box('page-header-section', __('Header', 'resideo'), 'resideo_page_header_render', 'page', 'normal', 'default');
        add_meta_box('page-template-section', __('Template Design', 'resideo'), 'resideo_page_template_render', 'page', 'normal', 'default');
        add_meta_box('page-contact-settings-section', __('Page Settings', 'resideo'), 'resideo_page_contact_settings_render', 'page', 'normal', 'default');
        add_meta_box('page-contact-office-settings-section', __('Page Settings', 'resideo'), 'resideo_page_contact_office_settings_render', 'page', 'normal', 'default');
        add_meta_box('page-agents-settings-section', __('Page Settings', 'resideo'), 'resideo_page_agents_settings_render', 'page', 'normal', 'default');
        add_meta_box('page-settings-section', __('Page Settings', 'resideo'), 'resideo_page_settings_render', 'page', 'normal', 'default');
    }
endif;
add_action('add_meta_boxes', 'resideo_add_page_metaboxes');

if (!function_exists('resideo_page_header_render')): 
    function resideo_page_header_render($post) {
        wp_nonce_field('resideo_page', 'page_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if(isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        /* ---------------------------------------------------
            Header types select box
        --------------------------------------------------- */
        $header_types_value = '';

        if (isset($post_id)) {
            $header_types_value = get_post_meta($post_id, 'page_header_type', true);
        }

        $header_types = array(
            'none'         => __('None', 'resideo'),
            'slideshow'    => __('Slideshow', 'resideo'),
            'slider'       => __('Slider', 'resideo'),
            'p_slider'     => __('Properties Slider', 'resideo'),
            'image'        => __('Image', 'resideo'),
            'video'        => __('Video', 'resideo'),
            'contact_form' => __('Contact Form', 'resideo'),
            'rev'          => __('Slider Revolution', 'resideo'),
        );

        $header_types_select = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Header Type', 'resideo') . '</label><br />
                            <div class="layout-radio-container">';
        foreach ($header_types as $key => $value) {
            $header_types_select .= '<div class="layout-radio layout-radio-' . esc_attr($key) . '" data-toggle="tooltip" title="' . esc_attr($value) . '"><label><input type="radio" name="page_header_type" value="' . esc_attr($key) . '"';
            if (isset($header_types_value) && $header_types_value == $key) {
                $header_types_select .= ' checked';
            }
            $header_types_select .= '><span><span class="fa fa-check"></span></span></label></div>';
        }
        $header_types_select .= '
                            </div>
                        </div>
                    </td>
                </tr>
            </table>';

        print $header_types_select;

        /* ---------------------------------------------------
            Slideshow settings
        --------------------------------------------------- */
        if ($header_types_value == 'slideshow') {
            $hide_slideshow = 'block';
        } else {
            $hide_slideshow = 'none';
        }

        $slideshow_settings = ' 
            <div class="header-settings header-slideshow-settings" style="display: ' . esc_attr($hide_slideshow) . '">
                <p><strong>' . __('Slideshow Settings', 'resideo') . '</strong></p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slideshow_caption_title">' . __('Caption Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_slideshow_caption_title" name="page_header_slideshow_caption_title" value="' . esc_attr(get_post_meta($post->ID, 'page_header_slideshow_caption_title', true)) . '" placeholder="' . __('Enter the caption title', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slideshow_caption_subtitle">' . __('Caption Subtitle', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_slideshow_caption_subtitle" name="page_header_slideshow_caption_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'page_header_slideshow_caption_subtitle', true)) . '" placeholder="' . __('Enter the caption subtitle', 'resideo') . '" />
                            </div>
                        </td>
                    </tr>
                </table>';
        $slideshow_settings .= '
                <br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slideshow_caption_position">' . __('Caption Position', 'resideo') . '</label><br />
                                <select id="page_header_slideshow_caption_position" name="page_header_slideshow_caption_position" style="width: 50%;">';
        $slideshow_caption_positions = array(
            'middle' => __('Middle', 'resideo'),
            'left'   => __('Left', 'resideo'),
            'bottom' => __('Bottom', 'resideo'),
        );
        foreach ($slideshow_caption_positions as $key => $value) {
            $slideshow_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_slideshow_caption_position', true) == $key) {
                $slideshow_settings .= 'selected="selected"';
            }
            $slideshow_settings .= '>' . esc_html($value) . '</option>';
        }
        $slideshow_settings .= '
                                </select>
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slideshow_opacity">' . __('Caption background opacity', 'resideo') . '</label>
                                <select id="page_header_slideshow_opacity" name="page_header_slideshow_opacity">';
        $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
        foreach ($opacity as $key => $value) {
            $slideshow_settings .= '<option value="' . esc_attr($key) . '"';

            if (get_post_meta($post->ID, 'page_header_slideshow_opacity', true) == $key) {
                $slideshow_settings .= 'selected="selected"';
            }
            $slideshow_settings .= '>' . esc_html($value) . '</option>';
        }
        $slideshow_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br><hr>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                    <td width="25%" valign="top" align="left">
                        <div class="adminField">
                            &nbsp;<br>
                            <label for="page_header_slideshow_show_search">
                            <input type="hidden" name="page_header_slideshow_show_search" value="">
                            <input type="checkbox" name="page_header_slideshow_show_search" value="1"';
        if (get_post_meta($post->ID, 'page_header_slideshow_show_search', true) == 1) {
            $slideshow_settings .= ' checked';
        }
        $slideshow_settings .= ' />' . __('Show Search Form', 'resideo') . '</label>
                            </div>
                        </td>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slideshow_search_status_type">' . __('Status Type', 'resideo') . '</label><br />
                                <select id="page_header_slideshow_search_status_type" name="page_header_slideshow_search_status_type" style="width: 50%;">';
        $slideshow_search_status_types = array(
            'dropdown' => __('Dropdown', 'resideo'),
            'tabs'     => __('Tabs', 'resideo')
        );
        foreach ($slideshow_search_status_types as $key => $value) {
            $slideshow_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_slideshow_search_status_type', true) == $key) {
                $slideshow_settings .= 'selected="selected"';
            }
            $slideshow_settings .= '>' . esc_html($value) . '</option>';
        }
        $slideshow_settings .= '
                                </select>
                            </div>
                        </td>
                        <td width="50%">&nbsp;</td>
                    </tr>
                </table>
                <br><hr><br>';

        $ss_photos = get_post_meta($post->ID, 'page_header_slideshow_gallery', true);
        $ss_ids = explode(',', $ss_photos);

        $slideshow_settings .= '
                <br><p>' . __('Slides Photos', 'resideo') . '</p>
                <input type="hidden" id="page_header_slideshow_gallery" name="page_header_slideshow_gallery" value="' . esc_attr($ss_photos) . '" />
                <ul class="list-group" id="slideshow-gallery-list">';
        foreach ($ss_ids as $id) {
            if ($id != '') {
                $ss_photo_src = wp_get_attachment_image_src($id, 'pxp-agent');

                $slideshow_settings .= '
                    <li class="list-group-item" data-id="' . esc_attr($id) . '"><img class="pull-left rtl-pull-right" src="' . esc_url($ss_photo_src[0]) . '" />
                        <a href="javascript:void(0);" class="pull-right del-btn slideshow-del-photo"><span class="fa fa-trash-o"></span></a>
                        <a href="javascript:void(0);" class="pull-right edit-btn slideshow-edit-photo"><span class="fa fa-pencil"></span></a>
                        <div class="clearfix"></div>
                    </li>';
            }
        }
        $slideshow_settings .= '
                </ul><div class="clearfix"></div>
                <input id="slideshow-add-photo-btn" type="button" class="button" value="' . __('Add Photos', 'resideo') . '" /><br><br><hr><br>
                <label for="page_header_slideshow_interval">' . __('Slides Change Interval', 'resideo') . '</label><br />
                <input type="text" id="page_header_slideshow_interval" name="page_header_slideshow_interval" value="' . esc_attr(get_post_meta($post->ID, 'page_header_slideshow_interval', true)) . '" placeholder="0" style="width:80px;" /> ' . __('seconds', 'resideo') . '
            </div>';

        print $slideshow_settings;

        /* ---------------------------------------------------
            Slider settings
        --------------------------------------------------- */
        if ($header_types_value == 'slider') {
            $hide_slider = 'block';
        } else {
            $hide_slider = 'none';
        }

        $slider_settings = '
            <div class="header-settings header-slider-settings" style="display: ' . esc_attr($hide_slider) . '">
                <p><strong>' . __('Slider Settings', 'resideo') . '</strong></p>
                <input type="hidden" id="page_header_slider" name="page_header_slider" value="' . esc_attr(get_post_meta($post->ID, 'page_header_slider', true)) . '">
                <div class="new-slide-container">
                    <p style="margin-top: 0; margin-bottom: 10px;">' . __('Add New Slide', 'resideo') . '</p>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%" valign="top" align="left">
                                <div class="adminField">
                                    <input type="hidden" id="page_header_slider_id" name="page_header_slider_id" />
                                    <div class="slider-placeholder-container">
                                        <div id="slider-image-placeholder" style="background-image: url(' . RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png);"></div>
                                    </div>
                                </div>
                            </td>
                            <td width="40%" valign="top" align="left">
                                <div class="adminField">
                                    <label for="page_header_slider_caption_title">' . __('Caption Title', 'resideo') . '</label><br />
                                    <input type="text" class="formInput" id="page_header_slider_caption_title" name="page_header_slider_caption_title" placeholder="' . __('Enter the caption title', 'resideo') . '" />
                                </div>
                                <div class="adminField">
                                    <label for="page_header_slider_caption_subtitle">' . __('Caption Subtitle', 'resideo') . '</label><br />
                                    <input type="text" class="formInput" id="page_header_slider_caption_subtitle" name="page_header_slider_caption_subtitle" placeholder="' . __('Enter the caption subtitle', 'resideo') . '" />
                                </div>
                                <div class="adminField">
                                    <label for="page_header_slider_caption_solid_color">' . __('Solid Color', 'resideo') . '</label><br />
                                    <input type="text" class="formInput pxp-color-field" id="page_header_slider_caption_solid_color" name="page_header_slider_caption_solid_color" />
                                </div>
                            </td>
                            <td width="40%" valign="top" align="left">
                                <div class="adminField">
                                    <label for="page_header_slider_caption_cta_text">' . __('CTA Button Text', 'resideo') . '</label><br />
                                    <input type="text" class="formInput" id="page_header_slider_caption_cta_text" name="page_header_slider_caption_cta_text" placeholder="' . __('Enter the CTA button text', 'resideo') . '" />
                                </div>
                                <div class="adminField">
                                    <label for="page_header_slider_caption_cta_link">' . __('CTA Button Link', 'resideo') . '</label><br />
                                    <input type="text" class="formInput" id="page_header_slider_caption_cta_link" name="page_header_slider_caption_cta_link" placeholder="' . __('Enter the CTA button link', 'resideo') . '" />
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br>';

        wp_nonce_field('modal_props_ajax_nonce', 'security-modal-props', true);

        $slider_settings .= '
                    <input id="add_slider_item" type="button" class="button" value="' . __('Add Slide', 'resideo') . '">
                </div>';

        $slider_settings .= '<p>' . __('Sliders List', 'resideo') . '</p>';

        $slider = get_post_meta($post->ID, 'page_header_slider', true);
        $slider_obj = json_decode($slider, true);

        $slider_settings .= '
                <ul class="list-group" id="slider-list">';
        if (is_array($slider_obj) && count($slider_obj) > 0) {
            foreach ($slider_obj as $obj) {
                $slide_src = wp_get_attachment_image_src($obj['id'], 'pxp-agent');
                $slide_color = isset($obj['color']) ? $obj['color'] : '';

                $slider_settings .= '
                    <li class="list-group-item" data-id="' . $obj['id'] . '">
                        <div class="list-group-item-img">
                            <img class="pull-left rtl-pull-right" src="' . esc_url($slide_src[0]) . '" />
                            <div class="list-group-item-img-edit">
                                <span class="fa fa-pencil"></span>
                            </div>
                        </div>
                        <div class="list-group-item-info">
                            <div class="list-group-item-info-title">
                                <div class="editable">
                                    <span class="slide-title">' . $obj['title'] . '</span>
                                </div>
                            </div>
                            <div class="list-group-item-info-caption">
                                <div class="editable">
                                    <span class="slide-subtitle">' . $obj['subtitle'] . '</span>
                                </div>
                            </div>
                            <span style="display: none;" class="slide-cta-text" data-value="' . $obj['cta_text'] . '"></span>
                            <span style="display: none;" class="slide-cta-link" data-value="' . $obj['cta_link'] . '"></span>
                            <span style="display: none;" class="slide-color" data-value="' . $slide_color . '"></span>
                        </div>
                        <div class="list-group-item-edit">
                            <input type="text" class="edit-slide-title" placeholder="' . __('Enter the caption title', 'resideo') . '">
                            <input type="text" class="edit-slide-cta-text" placeholder="' . __('Enter the CTA button text', 'resideo') . '">
                            <br>
                            <input type="text" class="edit-slide-subtitle" placeholder="' . __('Enter the caption subtitle', 'resideo') . '">
                            <input type="text" class="edit-slide-cta-link" placeholder="' . __('Enter the CTA button link', 'resideo') . '">
                            <br>
                            <input type="text" class="edit-slide-color">
                        </div>
                        <a href="javascript:void(0);" class="pull-right del-btn del-slide"><span class="fa fa-trash-o"></span></a>
                        <a href="javascript:void(0);" class="pull-right edit-btn edit-slide"><span class="fa fa-pencil"></span></a>
                        <input type="button" class="button edit-slide-ok" value="' . __('OK', 'resideo') . '">
                        <div class="clearfix"></div>
                    </li>';
            }
        } else {
            $slider_settings .= '
                    <li class="slider-list-empty"><p class="help">' . __('Sliders list is empty.', 'resideo') . '</p></li>';
        }

        $slider_settings .= '</ul><br><hr><br>';

        $slider_settings .= '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="40%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="page_header_slider_interval">' . __('Autoslide Interval', 'resideo') . '</label><br />
                                <input type="text" id="page_header_slider_interval" name="page_header_slider_interval" value="' . esc_attr(get_post_meta($post->ID, 'page_header_slider_interval', true)) . '" placeholder="0" style="width:80px;" /> ' . __('seconds', 'resideo') .
                            '</div>
                        </td>
                        <td width="60%">&nbsp;</td>
                    </tr>
                </table>
                <br><hr><br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slider_opacity">' . __('Caption background opacity', 'resideo') . '</label>
                                <select id="page_header_slider_opacity" name="page_header_slider_opacity">';
        $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
        foreach ($opacity as $key => $value) {
            $slider_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_slider_opacity', true) == $key) {
                $slider_settings .= 'selected="selected"';
            }
            $slider_settings .= '>' . esc_html($value) . '</option>';
        }
        $slider_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br><hr><br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_slider_design">' . __('Design', 'resideo') . '</label>
                                <select id="page_header_slider_design" name="page_header_slider_design">';
        $design = array(
            'full' => __('Full width image'),
            'half' => __('Half width image')
        );
        foreach ($design as $key => $value) {
            $slider_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_slider_design', true) == $key) {
                $slider_settings .= 'selected="selected"';
            }
            $slider_settings .= '>' . esc_html($value) . '</option>';
        }
        $slider_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>';

        print $slider_settings;

        /* ---------------------------------------------------
            Properties Slider settings
        --------------------------------------------------- */
        if ($header_types_value == 'p_slider') {
            $hide_p_slider = 'block';
        } else {
            $hide_p_slider = 'none';
        }

        $p_slider_settings = '
            <div class="header-settings header-p_slider-settings" style="display: ' . esc_attr($hide_p_slider) . '">
                <p><strong>' . __('Properties Slider Settings', 'resideo') . '</strong></p>

                <p>' . __('Properties List', 'resideo') . '</p>
                <input type="hidden" id="page_header_p_slider" name="page_header_p_slider" value="' . esc_attr(get_post_meta($post->ID, 'page_header_p_slider', true)) . '">';

        $ps_props = get_post_meta($post->ID, 'page_header_p_slider', true);
        $ps_ids = explode(',', $ps_props);

        $props_list = resideo_get_page_header_settings_slider_properties($ps_ids);

        $p_slider_settings .= '
                <ul class="list-group" id="p-slider-list">';
        if (count($props_list) > 0) {
            foreach ($props_list as $prop) {
                $p_slider_settings .= '
                    <li class="list-group-item" data-id="' . esc_attr($prop->id) . '">
                        <img class="pull-left rtl-pull-right" src="' . esc_url($prop->photo[0]) . '" />
                        <div class="list-group-item-info">
                            <div class="list-group-item-info-title">
                                <span class="p-slide-title">' . $prop->title . '</span>
                            </div>
                            <div class="list-group-item-info-caption">
                                <span class="p-slide-address">' . $prop->address . '</span>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="pull-right del-btn del-p-slide"><span class="fa fa-trash-o"></span></a>
                        <div class="clearfix"></div>
                    </li>';
            }
        } else {
            $p_slider_settings .= '
                    <li class="p-slider-list-empty"><p class="help">' . __('Properties list is empty.', 'resideo') . '</p></li>';
        }
        $p_slider_settings .= '
                </ul>';
        $p_slider_settings .= '
                <input id="add_p_slider_item" type="button" class="button" value="' . __('Add Property', 'resideo') . '"><br><br><hr><br>';
        $p_slider_settings .= '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="40%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="page_header_p_slider_interval">' . __('Autoslide Interval', 'resideo') . '</label><br />
                                <input type="text" id="page_header_p_slider_interval" name="page_header_p_slider_interval" value="' . esc_attr(get_post_meta($post->ID, 'page_header_p_slider_interval', true)) . '" placeholder="0" style="width:80px;" /> ' . __('seconds', 'resideo') .
                            '</div>
                        </td>
                        <td width="60%">&nbsp;</td>
                    </tr>
                </table>
                <br><hr><br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_p_slider_opacity">' . __('Caption background opacity', 'resideo') . '</label>
                                <select id="page_header_p_slider_opacity" name="page_header_p_slider_opacity">';
        $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
        foreach ($opacity as $key => $value) {
            $p_slider_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_p_slider_opacity', true) == $key) {
                $p_slider_settings .= 'selected="selected"';
            }
            $p_slider_settings .= '>' . esc_html($value) . '</option>';
        }
        $p_slider_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br><hr><br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_p_slider_design">' . __('Design', 'resideo') . '</label>
                                <select id="page_header_p_slider_design" name="page_header_p_slider_design">';
        $design = array(
            'full' => __('Full width image'),
            'half' => __('Half width image')
        );
        foreach ($design as $design_key => $design_value) {
            $p_slider_settings .= '<option value="' . esc_attr($design_key) . '"';
            if (get_post_meta($post->ID, 'page_header_p_slider_design', true) == $design_key) {
                $p_slider_settings .= 'selected="selected"';
            }
            $p_slider_settings .= '>' . esc_html($design_value) . '</option>';
        }
        $p_slider_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>';

        print $p_slider_settings;

        /* ---------------------------------------------------
            Image settings
        --------------------------------------------------- */
        if ($header_types_value == 'image') {
            $hide_image = 'block';
        } else {
            $hide_image = 'none';
        }

        $image_settings = '
            <div class="header-settings header-image-settings" style="display: ' . esc_attr($hide_image) . '">
                <p><strong>' . __('Image Settings', 'resideo') . '</strong></p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_image_height">' . __('Height', 'resideo') . '</label><br />
                                <select id="page_header_image_height" name="page_header_image_height" style="width: 50%;">';
        $image_heights = array(
            'full'  => __('Full', 'resideo'),
            'half' => __('Half', 'resideo'),
        );
        foreach ($image_heights as $key => $value) {
            $image_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_image_height', true) == $key) {
                $image_settings .= 'selected="selected"';
            }
            $image_settings .= '>' . esc_html($value) . '</option>';
        }
        $full_height_style = '';
        $half_height_style = 'display: none;';
        if (get_post_meta($post->ID, 'page_header_image_height', true) == 'half') {
            $full_height_style = 'display: none;';
            $half_height_style = '';
        }
        $image_settings .= '
                                </select>
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_image_caption_title">' . __('Caption Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_image_caption_title" name="page_header_image_caption_title" value="' . esc_attr(get_post_meta($post->ID, 'page_header_image_caption_title', true)) . '" placeholder="' . __('Enter the caption title', 'resideo') . '" />
                            </div>
                            <div class="adminField">
                                <label for="page_header_image_caption_subtitle">' . __('Caption Subtitle', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_image_caption_subtitle" name="page_header_image_caption_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'page_header_image_caption_subtitle', true)) . '" placeholder="' . __('Enter the caption subtitle', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField pxp-js-page-header-image-full" style="' . esc_attr($full_height_style) . '">
                                <label for="page_header_image_caption_position">' . __('Caption Position', 'resideo') . '</label><br />
                                <select id="page_header_image_caption_position" name="page_header_image_caption_position" style="width: 50%;">';
        $image_caption_positions = array(
            'middle' => __('Middle', 'resideo'),
            'left'   => __('Left', 'resideo'),
            'bottom' => __('Bottom', 'resideo'),
        );
        foreach ($image_caption_positions as $key => $value) {
            $image_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_image_caption_position', true) == $key) {
                $image_settings .= 'selected="selected"';
            }
            $image_settings .= '>' . esc_html($value) . '</option>';
        }
        $image_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_image_opacity">' . __('Caption background opacity', 'resideo') . '</label>
                                <select id="page_header_image_opacity" name="page_header_image_opacity">';
        $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
        foreach ($opacity as $key => $value) {
            $image_settings .= '
                                    <option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_image_opacity', true) == $key) {
                $image_settings .= 'selected="selected"';
            }
            $image_settings .= '>' . esc_html($value) . '</option>';
        }
        $image_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br><hr>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                &nbsp;<br>
                                <label for="page_header_image_show_search">
                                <input type="hidden" name="page_header_image_show_search" value="">
                                <input type="checkbox" name="page_header_image_show_search" value="1"';
        if (get_post_meta($post->ID, 'page_header_image_show_search', true) == 1) {
            $image_settings .= ' checked';
        }
        $image_settings .= '
                                />' . __('Show Search Form', 'resideo') . '</label>
                            </div>
                        </td>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_image_search_status_type">' . __('Status Type', 'resideo') . '</label><br />
                                <select id="page_header_image_search_status_type" name="page_header_image_search_status_type" style="width: 50%;">';
        $image_search_status_types = array(
            'dropdown' => __('Dropdown', 'resideo'),
            'tabs'     => __('Tabs', 'resideo')
        );
        foreach ($image_search_status_types as $key => $value) {
            $image_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_image_search_status_type', true) == $key) {
                $image_settings .= 'selected="selected"';
            }
            $image_settings .= '>' . esc_html($value) . '</option>';
        }
        $image_settings .= '
                                </select>
                            </div>
                        </td>
                        <td width="50%">&nbsp;</td>
                    </tr>
                </table>
                <br><hr>';

        $image_src = wp_get_attachment_image_src(get_post_meta($post->ID, 'page_header_image', true), 'pxp-agent');

        $image_settings .= '
                <p>' . __('Image', 'resideo') . '</p>
                <div class="adminField">
                    <input type="hidden" id="page_header_image" name="page_header_image" value="' . esc_attr(get_post_meta($post->ID, 'page_header_image', true)) . '">
                    <div class="header-image-placeholder-container';
        if ($image_src !== false) { 
            $image_settings .= ' has-image'; 
        }
        $image_settings .= '"><div id="header-image-placeholder" style="background-image: url(';
        if ($image_src !== false) { 
            $image_settings .= $image_src[0]; 
        } else { 
            $image_settings .= RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png';
        }
        $image_settings .= ');">
                        </div>
                        <div id="delete-header-image"><span class="fa fa-trash-o"></span></div>
                    </div>
                </div>
            </div>';

        print $image_settings;

        /* ---------------------------------------------------
            Video settings
        --------------------------------------------------- */
        if ($header_types_value == 'video') {
            $hide_video = 'block';
        } else {
            $hide_video = 'none';
        }

        $video_settings = ' 
            <div class="header-settings header-video-settings" style="display: ' . esc_attr($hide_video) . '">
                <p><strong>' . __('Video Settings', 'resideo') . '</strong></p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_caption_title">' . __('Caption Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_video_caption_title" name="page_header_video_caption_title" value="' . esc_attr(get_post_meta($post->ID, 'page_header_video_caption_title', true)) . '" placeholder="' . __('Enter the caption title', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_caption_position">' . __('Caption Position', 'resideo') . '</label><br />
                                <select id="page_header_video_caption_position" name="page_header_video_caption_position" style="width: 50%;">';
        $video_caption_positions = array(
            'middle' => __('Middle', 'resideo'),
            'left'   => __('Left', 'resideo'),
            'bottom' => __('Bottom', 'resideo'),
        );
        foreach ($video_caption_positions as $key => $value) {
            $video_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_video_caption_position', true) == $key) {
                $video_settings .= 'selected="selected"';
            }
            $video_settings .= '>' . esc_html($value) . '</option>';
        }
        $video_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_caption_subtitle">' . __('Caption Subtitle', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_video_caption_subtitle" name="page_header_video_caption_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'page_header_video_caption_subtitle', true)) . '" placeholder="' . __('Enter the caption subtitle', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left"></td>
                </table>';
        $video_settings .= '
                <br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_opacity">' . __('Caption background opacity', 'resideo') . '</label>
                                <select id="page_header_video_opacity" name="page_header_video_opacity">';
        $video_opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
        foreach ($video_opacity as $key => $value) {
            $video_settings .= '<option value="' . esc_attr($key) . '"';

            if (get_post_meta($post->ID, 'page_header_video_opacity', true) == $key) {
                $video_settings .= 'selected="selected"';
            }
            $video_settings .= '>' . esc_html($value) . '</option>';
        }
        $video_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br><hr>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                &nbsp;<br>
                                <label for="page_header_video_show_search">
                                <input type="hidden" name="page_header_video_show_search" value="">
                                <input type="checkbox" name="page_header_video_show_search" value="1"';
        if (get_post_meta($post->ID, 'page_header_video_show_search', true) == 1) {
            $video_settings .= ' checked';
        }
        $video_settings .= ' />' . __('Show Search Form', 'resideo') . '</label>
                            </div>
                        </td>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_search_status_type">' . __('Status Type', 'resideo') . '</label><br />
                                <select id="page_header_video_search_status_type" name="page_header_video_search_status_type" style="width: 50%;">';
        $video_search_status_types = array(
            'dropdown' => __('Dropdown', 'resideo'),
            'tabs'     => __('Tabs', 'resideo')
        );
        foreach ($video_search_status_types as $key => $value) {
            $video_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_video_search_status_type', true) == $key) {
                $video_settings .= 'selected="selected"';
            }
            $video_settings .= '>' . esc_html($value) . '</option>';
        }
        $video_settings .= '
                                </select>
                            </div>
                        </td>
                        <td width="50%">&nbsp;</td>
                    </tr>
                </table>
                <br><hr><br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_id">' . __('Youtube video ID', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_video_id" name="page_header_video_id" value="' . esc_attr(get_post_meta($post->ID, 'page_header_video_id', true)) . '" placeholder="d1EaFyBqH5o" />
                                <p class="help" style="margin-top: 5px; font-size: 11px !important;">E.g. <span style="color: #999;">https://www.youtube.com/watch?v=</span><strong style="color: green; font-style: normal;">d1EaFyBqH5o</strong></p>
                            </div>
                        </td>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_video_sound">' . __('Sound', 'resideo') . '</label><br />
                                <select id="page_header_video_sound" name="page_header_video_sound" style="width: 80px;">';
        $sound = array(
            'off' => __('Off', 'resideo'),
            'on'  => __('On', 'resideo'),
        );
        foreach ($sound as $key => $value) {
            $video_settings .= '<option value="' . esc_attr($key) . '"';
            if (get_post_meta($post->ID, 'page_header_video_sound', true) == $key) {
                $video_settings .= 'selected="selected"';
            }
            $video_settings .= '>' . esc_html($value) . '</option>';
        }
        $video_settings .= '
                                </select>
                            </div>
                        </td>
                        <td width="50%">&nbsp;</td>
                    </tr>
                </table>';
        $cover_src = wp_get_attachment_image_src(get_post_meta($post->ID, 'page_header_video_cover', true), 'avatar');
        $video_settings .=  '
                <p>' . __('Video Cover', 'resideo') . '</p>
                <div class="adminField">
                    <input type="hidden" id="page_header_video_cover" name="page_header_video_cover" value="' . esc_attr(get_post_meta($post->ID, 'page_header_video_cover', true)) . '">
                    <div class="header-video-cover-placeholder-container';
        if ($cover_src !== false) { 
            $video_settings .= ' has-image'; 
        }
        $video_settings .= '
                    "><div id="header-video-cover-placeholder" style="background-image: url(';
        if ($cover_src !== false) { 
            $video_settings .= $cover_src[0]; 
        } else { 
            $video_settings .= RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png';
        }
        $video_settings .= '
                    );"></div>
                        <div id="delete-header-video-cover"><span class="fa fa-trash-o"></span></div>
                    </div></div>
                </div>';

        print $video_settings;

        /* ---------------------------------------------------
            Contact form settings
        --------------------------------------------------- */
        if ($header_types_value == 'contact_form') {
            $hide_contact_form = 'block';
        } else {
            $hide_contact_form = 'none';
        }

        $contact_form_settings = '
            <div class="header-settings header-contact_form-settings" style="display: ' . esc_attr($hide_contact_form) . '">
                <p><strong>' . __('Contact Form Settings', 'resideo') . '</strong></p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_caption_title">' . __('Caption Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_caption_title" name="page_header_contact_form_caption_title" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_caption_title', true)) . '" placeholder="' . __('Enter the caption title', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_caption_subtitle">' . __('Caption Subtitle', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_caption_subtitle" name="page_header_contact_form_caption_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_caption_subtitle', true)) . '" placeholder="' . __('Enter the caption subtitle', 'resideo') . '" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_caption_cta_text">' . __('Caption CTA Text', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_caption_cta_text" name="page_header_contact_form_caption_cta_text" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_caption_cta_text', true)) . '" placeholder="' . __('Enter the caption CTA text', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="50%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_caption_cta_link">' . __('Caption CTA Link', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_caption_cta_link" name="page_header_contact_form_caption_cta_link" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_caption_cta_link', true)) . '" placeholder="' . __('Enter the caption CTA link', 'resideo') . '" />
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_opacity">' . __('Caption background opacity', 'resideo') . '</label>
                                <select id="page_header_contact_form_opacity" name="page_header_contact_form_opacity">';
        $contact_form_opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
        foreach ($contact_form_opacity as $cfo_key => $cfo_value) {
            $contact_form_settings .= '<option value="' . esc_attr($cfo_key) . '"';

            if (get_post_meta($post->ID, 'page_header_contact_form_opacity', true) == $cfo_key) {
                $contact_form_settings .= 'selected="selected"';
            }
            $contact_form_settings .= '>' . esc_html($cfo_value) . '</option>';
        }
        $contact_form_settings .= '
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <br><hr>';

        $contact_form_image_src = wp_get_attachment_image_src(get_post_meta($post->ID, 'page_header_contact_form_image', true), 'pxp-agent');

        $contact_form_settings .= '
                <p>' . __('Background Image', 'resideo') . '</p>
                <div class="adminField">
                    <input type="hidden" id="page_header_contact_form_image" name="page_header_contact_form_image" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_image', true)) . '">
                    <div class="header-contact-form-image-placeholder-container';
        if ($contact_form_image_src !== false) { 
            $contact_form_settings .= ' has-image'; 
        }
        $contact_form_settings .= '"><div id="header-contact-form-image-placeholder" style="background-image: url(';
        if ($contact_form_image_src !== false) { 
            $contact_form_settings .= $contact_form_image_src[0];
        } else { 
            $contact_form_settings .= RESIDEO_PLUGIN_PATH . 'images/image-placeholder.png';
        }
        $contact_form_settings .= ');">
                        </div>
                        <div id="delete-header-contact-form-image"><span class="fa fa-trash-o"></span></div>
                    </div>
                </div>
                <br><hr>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="30%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_title">' . __('Form Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_title" name="page_header_contact_form_title" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_title', true)) . '" placeholder="' . __('Enter the form title', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="30%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_subtitle">' . __('Form Subtitle', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_subtitle" name="page_header_contact_form_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_subtitle', true)) . '" placeholder="' . __('Enter the form subtitle', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="30%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_contact_form_email">' . __('Form Email', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_contact_form_email" name="page_header_contact_form_email" value="' . esc_attr(get_post_meta($post->ID, 'page_header_contact_form_email', true)) . '" placeholder="' . __('Enter the form email', 'resideo') . '" />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>';

        print $contact_form_settings;

        /* ---------------------------------------------------
            Slider Revolution settings
        --------------------------------------------------- */
        if ($header_types_value == 'rev') {
            $hide_rev = 'block';
        } else {
            $hide_rev = 'none';
        }

        $rev_settings = '
            <div class="header-settings header-rev-settings" style="display: ' . esc_attr($hide_rev) . '">
                <p><strong>' . __('Slider Revolution Settings', 'resideo') . '</strong></p>
                <p class="help" style="font-size: 11px !important;">' . __('NOTE: This header type requires Slider Revolution plugin. The plugin is not included in the theme.', 'resideo') . '</p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="25%" valign="top" align="left">
                            <div class="adminField">
                                <label for="page_header_rev_alias">' . __('Slider Revolution alias', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="page_header_rev_alias" name="page_header_rev_alias" value="' . esc_attr(get_post_meta($post->ID, 'page_header_rev_alias', true)) . '" placeholder="' . __('Enter the Slider Revolution alias', 'resideo') . '" />
                            </div>
                        </td>
                        <td width="75%">&nbsp;</td>
                    </tr>
                </table>
            </div>';

        print $rev_settings;

        /* ---------------------------------------------------
            Page Title settings
        --------------------------------------------------- */

        $page_title_settings = '
            <br><hr>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="30%" valign="top" align="left">
                        <div class="adminField">
                            &nbsp;<br>
                            <label for="page_title_hide">
                            <input type="hidden" name="page_title_hide" value="">
                            <input type="checkbox" name="page_title_hide" value="1"';
        if (get_post_meta($post->ID, 'page_title_hide', true) == 1) {
            $page_title_settings .= ' checked';
        }
        $page_title_settings .= ' />' . __('Hide Page Title', 'resideo') . '</label>
                        </div>
                    </td>
                    <td width="70%">&nbsp;</td>
                </tr>
            </table>';

        print $page_title_settings;
    }
endif;

if (!function_exists('resideo_page_template_render')):
    function resideo_page_template_render($post) {
        wp_nonce_field('resideo_page', 'page_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if (isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        /* ---------------------------------------------------
            Page templates select box
        --------------------------------------------------- */
        $page_templates_value = '';

        if (isset($post_id)) {
            $page_templates_value = get_post_meta($post_id, 'page_template_type', true);
            $page_listing_type = get_post_meta($post_id, 'page_listing_type', true);
        }

        $page_templates = array(
            'half_map_left'  => __('Half map left side', 'resideo'),
            'half_map_right' => __('Half map right side', 'resideo'),
            'no_map'         => __('No map', 'resideo'),
            'sidebar_left'   => __('Sidebar left', 'resideo'),
            'sidebar_right'  => __('Sidebar right', 'resideo'),
        );

        $page_templates_select = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Properties Search Results Template', 'resideo') . '</label><br />
                            <div class="layout-radio-container">';
        foreach ($page_templates as $key => $value) {
            $page_templates_select .= '<div class="layout-radio layout-radio-' . esc_attr($key) . '" data-toggle="tooltip" title="' . esc_attr($value) . '"><label><input type="radio" name="page_template_type" value="' . esc_attr($key) . '"';
            if (isset($page_templates_value) && $page_templates_value == $key) {
                $page_templates_select .= ' checked';
            }
            $page_templates_select .= '><span><span class="fa fa-check"></span></span></label></div>';
        }
        $page_templates_select .= '
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top" align="left">
                        <div class="adminField">
                            <label for="page_listing_type">' . __('Listing Type', 'resideo') . '</label><br />
                            <select id="page_listing_type" name="page_listing_type" style="width: 50%;">';
        $listing_types = array(
            'grid_1' => __('Grid 1', 'resideo'),
            'grid_2' => __('Grid 2', 'resideo'),
            'grid_3' => __('Grid 3', 'resideo'),
            'list'   => __('List', 'resideo'),
        );
        foreach ($listing_types as $key => $value) {
            $page_templates_select .= '<option value="' . esc_attr($key) . '"';
            if (isset($page_listing_type) && $page_listing_type == $key) {
                $page_templates_select .= 'selected="selected"';
            }
            $page_templates_select .= '>' . esc_html($value) . '</option>';
        }
        $page_templates_select .= '
                            </select>
                        </div>
                    </td>
                    <td width="75%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>';

        print $page_templates_select;
    }
endif;

if (!function_exists('resideo_page_contact_settings_render')):
    function resideo_page_contact_settings_render($post) {
        wp_nonce_field('resideo_page', 'page_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if (isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        /* ---------------------------------------------------
            Contact details
        --------------------------------------------------- */

        $contact_details = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Page subtitle', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="contact_page_subtitle" name="contact_page_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_subtitle', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Email', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="contact_page_email" name="contact_page_email" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_email', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>

            <p style="padding-top: 20px;"><strong>' . __('Offices', 'resideo') . '</strong></p>

            <input type="hidden" id="contact_page_offices" name="contact_page_offices" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_offices', true)) . '">
            <div class="new-office-container">
                <p style="margin-top: 0; margin-bottom: 10px;">' . __('Add New Office', 'resideo') . '</p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="contact_page_office_title">' . __('Office Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_office_title" name="contact_page_office_title" />
                            </div>
                        </td>';
        if (wp_script_is('gmaps', 'enqueued')) {
            $contact_details .= '
                        <td width="25%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="contact_page_office_lat">' . __('Latitude', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_office_lat" name="contact_page_office_lat" />
                            </div>
                        </td>
                        <td width="25%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="contact_page_office_lng">' . __('Longitude', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_office_lng" name="contact_page_office_lng" />
                            </div>
                        </td>';
        } else {
            $contact_details .= '
                        <td width="50%" valign="middle" align="left">&nbsp;</td>';
        }
        $contact_details .= '
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="80%" valign="top" align="left">
                                        <div class="adminField">
                                            <label for="contact_page_office_address_line_1">' . __('Address', 'resideo') . '</label><br />
                                            <input type="text" class="formInput" id="contact_page_office_address_line_1" name="contact_page_office_address_line_1" placeholder="' . __('Street address', 'resideo') . '" />
                                            <input type="text" class="formInput" id="contact_page_office_address_line_2" name="contact_page_office_address_line_2" placeholder="' . __('State/Zip', 'resideo') . '" />
                                        </div>
                                    </td>';
        if (wp_script_is('gmaps', 'enqueued')) {
            $contact_details .= '
                                    <td width="20%" valign="middle" align="left">
                                        <label>&nbsp;</label><br />
                                        <button id="contact_page_office_position_btn" title="' . __('Position pin by address', 'resideo') . '" class="button"><span class="icon-target"></span></button>
                                    </td>';
        }
        $contact_details .= '
                                </tr>
                            </table>
                            <div class="adminField">
                                <label for="contact_page_office_phone">' . __('Phone', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_office_phone" name="contact_page_office_phone" />
                            </div>
                            <div class="adminField">
                                <label for="contact_page_office_email">' . __('Email', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_office_email" name="contact_page_office_email" />
                            </div>
                            <div class="adminField">
                                <label>&nbsp;</label><br /><br />
                                <input id="contact_page_office_add" type="button" class="button" value="' . __('Add Office', 'resideo') . '" />
                            </div>
                        </td>';
        if (wp_script_is('gmaps', 'enqueued')) {
            $contact_details .= '
                        <td width="50%" valign="top" align="left">
                            <div id="contact_page_office_map"></div>
                        </td>';
        } else {
            $contact_details .= '
                        <td width="50%" valign="top" align="left">&nbsp;</td>';
        }
        $contact_details .= '
                    </tr>
                </table>';

        $contact_details .= '
            </div>';
        
        $contact_details .= '<p>' . __('Offices List', 'resideo') . '</p>';

        $offices = get_post_meta($post->ID, 'contact_page_offices', true);
        $offices_obj = json_decode($offices, true);

        $contact_details .= '
            <ul class="list-group" id="offices-list">';
        if (is_array($offices_obj) && count($offices_obj) > 0) {
            foreach ($offices_obj as $office) {
                $contact_details .= '
                <li class="list-group-item" 
                    data-title="' . $office['title'] . '" 
                    data-address1="' . $office['address1'] . '" 
                    data-address2="' . $office['address2'] . '" 
                    data-lat="' . $office['lat'] . '" 
                    data-lng="' . $office['lng'] . '" 
                    data-phone="' . $office['phone'] . '" 
                    data-email="' . $office['email'] . '" 
                >
                    <div class="list-group-item-info">
                        <div class="list-group-item-info-title">
                            <div class="editable">
                                <span class="office-title">' . $office['title'] . '</span>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="pull-right del-btn del-office"><span class="fa fa-trash-o"></span></a>
                    <div class="clearfix"></div>
                </li>';
            }
        } else {
            $contact_details .= '<li class="offices-list-empty"><p class="help">' . __('Offices list is empty.', 'resideo') . '</p></li>';
        }

        $contact_details .= '</ul>';

        print $contact_details;
    }
endif;

if (!function_exists('resideo_page_contact_office_settings_render')):
    function resideo_page_contact_office_settings_render($post) {
        wp_nonce_field('resideo_page', 'page_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if (isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        /* ---------------------------------------------------
            Contact details
        --------------------------------------------------- */

        $contact_details = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Page subtitle', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="contact_page_office_subtitle" name="contact_page_office_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_office_subtitle', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Email', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="contact_page_office_email" name="contact_page_office_email" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_office_email', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>

            <p style="padding-top: 20px;"><strong>' . __('Office Info', 'resideo') . '</strong></p>
            <div class="contact-office-container">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="contact_page_single_office_title">' . __('Office Title', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_single_office_title" name="contact_page_single_office_title" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_title', true)) . '" />
                            </div>
                        </td>';
        if (wp_script_is('gmaps', 'enqueued')) {
            $contact_details .= '
                        <td width="25%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="contact_page_single_office_lat">' . __('Latitude', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_single_office_lat" name="contact_page_single_office_lat" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_lat', true)) . '" />
                            </div>
                        </td>
                        <td width="25%" valign="middle" align="left">
                            <div class="adminField">
                                <label for="contact_page_single_office_lng">' . __('Longitude', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_single_office_lng" name="contact_page_single_office_lng" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_lng', true)) . '" />
                            </div>
                        </td>';
        } else {
            $contact_details .= '
                        <td width="50%" valign="middle" align="left">&nbsp;</td>';
        }
        $contact_details .= '
                    </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" valign="top" align="left">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="80%" valign="top" align="left">
                                        <div class="adminField">
                                            <label for="contact_page_single_office_address_line_1">' . __('Address', 'resideo') . '</label><br />
                                            <input type="text" class="formInput" id="contact_page_single_office_address_line_1" name="contact_page_single_office_address_line_1" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_address_line_1', true)) . '" placeholder="' . __('Street address', 'resideo') . '" />
                                            <input type="text" class="formInput" id="contact_page_single_office_address_line_2" name="contact_page_single_office_address_line_2" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_address_line_2', true)) . '" placeholder="' . __('State/Zip', 'resideo') . '" />
                                        </div>
                                    </td>';
        if (wp_script_is('gmaps', 'enqueued')) {
            $contact_details .= '
                                    <td width="20%" valign="middle" align="left">
                                        <label>&nbsp;</label><br />
                                        <button id="contact_page_single_office_position_btn" title="' . __('Position pin by address', 'resideo') . '" class="button"><span class="icon-target"></span></button>
                                    </td>';
        }
        $contact_details .= '
                                </tr>
                            </table>
                            <div class="adminField">
                                <label for="contact_page_single_office_phone">' . __('Phone', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_single_office_phone" name="contact_page_single_office_phone" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_phone', true)) . '" />
                            </div>
                            <div class="adminField">
                                <label for="contact_page_single_office_email">' . __('Email', 'resideo') . '</label><br />
                                <input type="text" class="formInput" id="contact_page_single_office_email" name="contact_page_single_office_email" value="' . esc_attr(get_post_meta($post->ID, 'contact_page_single_office_email', true)) . '" />
                            </div>
                            <div class="adminField">
                                &nbsp;<br>
                                <label for="contact_page_margin_bottom">
                                <input type="hidden" name="contact_page_margin_bottom" value="">
                                <input type="checkbox" name="contact_page_margin_bottom" value="1"';
        if (get_post_meta($post->ID, 'contact_page_margin_bottom', true) == 1) {
            $contact_details .= ' checked';
        }
        $contact_details .= ' />' . __('Margin Bottom', 'resideo') . '</label>
                            </div>
                        </td>';
        if (wp_script_is('gmaps', 'enqueued')) {
            $contact_details .= '
                        <td width="50%" valign="top" align="left">
                            <div id="contact_page_single_office_map"></div>
                        </td>';
        } else {
            $contact_details .= '
                        <td width="50%" valign="top" align="left">&nbsp;</td>';
        }
        $contact_details .= '
                    </tr>
                </table>';

        $contact_details .= '
            </div>';

        print $contact_details;
    }
endif;

if (!function_exists('resideo_page_agents_settings_render')):
    function resideo_page_agents_settings_render($post) {
        wp_nonce_field('resideo_page', 'page_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if (isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        /* ---------------------------------------------------
            Page settings
        --------------------------------------------------- */

        $agents_settings = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="adminField">
                            <label>' . __('Page subtitle', 'resideo') . '</label><br />
                            <input type="text" class="formInput" id="agents_page_subtitle" name="agents_page_subtitle" value="' . esc_attr(get_post_meta($post->ID, 'agents_page_subtitle', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>
            <div class="adminField">
                <br>
                <label for="agents_page_search_form">
                    <input type="hidden" name="agents_page_search_form" value="">
                    <input type="checkbox" name="agents_page_search_form" value="1"';
        if (get_post_meta($post->ID, 'agents_page_search_form', true) == 1) {
            $agents_settings .= ' checked';
        }
        $agents_settings .= ' />' . __('Display search agents form', 'resideo') . '</label>
            </div>';

        print $agents_settings;
    }
endif;

if (!function_exists('resideo_page_settings_render')): 
    function resideo_page_settings_render($post) {
        wp_nonce_field('resideo_page', 'page_noncename');

        $page_settings = '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="30%" valign="top" align="left">
                        <div class="adminField">
                            &nbsp;<br>
                            <label for="page_margin_bottom">
                            <input type="hidden" name="page_margin_bottom" value="">
                            <input type="checkbox" name="page_margin_bottom" value="1"';
        if (get_post_meta($post->ID, 'page_margin_bottom', true) == 1) {
            $page_settings .= ' checked';
        }
        $page_settings .= ' />' . __('Margin Bottom', 'resideo') . '</label>
                        </div>
                    </td>
                    <td width="70%">&nbsp;</td>
                </tr>
            </table>';

        print $page_settings;
    }
endif;

if (!function_exists('resideo_page_meta_save')): 
    function resideo_page_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['page_noncename']) && wp_verify_nonce($_POST['page_noncename'], 'resideo_page')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['page_header_type'])) {
            update_post_meta($post_id, 'page_header_type', sanitize_text_field($_POST['page_header_type']));
        }
        if (isset($_POST['page_header_slideshow_caption_title'])) {
            update_post_meta($post_id, 'page_header_slideshow_caption_title', sanitize_text_field($_POST['page_header_slideshow_caption_title']));
        }
        if (isset($_POST['page_header_slideshow_caption_subtitle'])) {
            update_post_meta($post_id, 'page_header_slideshow_caption_subtitle', sanitize_text_field($_POST['page_header_slideshow_caption_subtitle']));
        }
        if (isset($_POST['page_header_slideshow_caption_position'])) {
            update_post_meta($post_id, 'page_header_slideshow_caption_position', sanitize_text_field($_POST['page_header_slideshow_caption_position']));
        }
        if (isset($_POST['page_header_slideshow_opacity'])) {
            update_post_meta($post_id, 'page_header_slideshow_opacity', sanitize_text_field($_POST['page_header_slideshow_opacity']));
        }
        if (isset($_POST['page_header_slideshow_search_status_type'])) {
            update_post_meta($post_id, 'page_header_slideshow_search_status_type', sanitize_text_field($_POST['page_header_slideshow_search_status_type']));
        }
        if (isset($_POST['page_header_slideshow_show_search'])) {
            update_post_meta($post_id, 'page_header_slideshow_show_search', sanitize_text_field($_POST['page_header_slideshow_show_search']));
        }
        if (isset($_POST['page_header_slideshow_gallery'])) {
            update_post_meta($post_id, 'page_header_slideshow_gallery', sanitize_text_field($_POST['page_header_slideshow_gallery']));
        }
        if (isset($_POST['page_header_slideshow_interval'])) {
            update_post_meta($post_id, 'page_header_slideshow_interval', sanitize_text_field($_POST['page_header_slideshow_interval']));
        }
        if (isset($_POST['page_header_slider'])) {
            update_post_meta($post_id, 'page_header_slider', sanitize_text_field($_POST['page_header_slider']));
        }
        if (isset($_POST['page_header_slider_interval'])) {
            update_post_meta($post_id, 'page_header_slider_interval', sanitize_text_field($_POST['page_header_slider_interval']));
        }
        if (isset($_POST['page_header_slider_opacity'])) {
            update_post_meta($post_id, 'page_header_slider_opacity', sanitize_text_field($_POST['page_header_slider_opacity']));
        }
        if (isset($_POST['page_header_slider_design'])) {
            update_post_meta($post_id, 'page_header_slider_design', sanitize_text_field($_POST['page_header_slider_design']));
        }
        if (isset($_POST['page_header_p_slider'])) {
            update_post_meta($post_id, 'page_header_p_slider', sanitize_text_field($_POST['page_header_p_slider']));
        }
        if (isset($_POST['page_header_p_slider_interval'])) {
            update_post_meta($post_id, 'page_header_p_slider_interval', sanitize_text_field($_POST['page_header_p_slider_interval']));
        }
        if (isset($_POST['page_header_p_slider_opacity'])) {
            update_post_meta($post_id, 'page_header_p_slider_opacity', sanitize_text_field($_POST['page_header_p_slider_opacity']));
        }
        if (isset($_POST['page_header_p_slider_design'])) {
            update_post_meta($post_id, 'page_header_p_slider_design', sanitize_text_field($_POST['page_header_p_slider_design']));
        }
        if (isset($_POST['page_header_image_height'])) {
            update_post_meta($post_id, 'page_header_image_height', sanitize_text_field($_POST['page_header_image_height']));
        }
        if (isset($_POST['page_header_image_caption_title'])) {
            update_post_meta($post_id, 'page_header_image_caption_title', sanitize_text_field($_POST['page_header_image_caption_title']));
        }
        if (isset($_POST['page_header_image_caption_subtitle'])) {
            update_post_meta($post_id, 'page_header_image_caption_subtitle', sanitize_text_field($_POST['page_header_image_caption_subtitle']));
        }
        if (isset($_POST['page_header_image_caption_position'])) {
            update_post_meta($post_id, 'page_header_image_caption_position', sanitize_text_field($_POST['page_header_image_caption_position']));
        }
        if (isset($_POST['page_header_image_opacity'])) {
            update_post_meta($post_id, 'page_header_image_opacity', sanitize_text_field($_POST['page_header_image_opacity']));
        }
        if (isset($_POST['page_header_image_show_search'])) {
            update_post_meta($post_id, 'page_header_image_show_search', sanitize_text_field($_POST['page_header_image_show_search']));
        }
        if (isset($_POST['page_header_image_search_status_type'])) {
            update_post_meta($post_id, 'page_header_image_search_status_type', sanitize_text_field($_POST['page_header_image_search_status_type']));
        }
        if (isset($_POST['page_header_image'])) {
            update_post_meta($post_id, 'page_header_image', sanitize_text_field($_POST['page_header_image']));
        }
        if (isset($_POST['page_header_video_caption_title'])) {
            update_post_meta($post_id, 'page_header_video_caption_title', sanitize_text_field($_POST['page_header_video_caption_title']));
        }
        if (isset($_POST['page_header_video_caption_subtitle'])) {
            update_post_meta($post_id, 'page_header_video_caption_subtitle', sanitize_text_field($_POST['page_header_video_caption_subtitle']));
        }
        if (isset($_POST['page_header_video_caption_position'])) {
            update_post_meta($post_id, 'page_header_video_caption_position', sanitize_text_field($_POST['page_header_video_caption_position']));
        }
        if (isset($_POST['page_header_video_opacity'])) {
            update_post_meta($post_id, 'page_header_video_opacity', sanitize_text_field($_POST['page_header_video_opacity']));
        }
        if (isset($_POST['page_header_video_show_search'])) {
            update_post_meta($post_id, 'page_header_video_show_search', sanitize_text_field($_POST['page_header_video_show_search']));
        }
        if (isset($_POST['page_header_video_search_status_type'])) {
            update_post_meta($post_id, 'page_header_video_search_status_type', sanitize_text_field($_POST['page_header_video_search_status_type']));
        }
        if (isset($_POST['page_header_video_id'])) {
            update_post_meta($post_id, 'page_header_video_id', sanitize_text_field($_POST['page_header_video_id']));
        }
        if (isset($_POST['page_header_video_sound'])) {
            update_post_meta($post_id, 'page_header_video_sound', sanitize_text_field($_POST['page_header_video_sound']));
        }
        if (isset($_POST['page_header_video_cover'])) {
            update_post_meta($post_id, 'page_header_video_cover', sanitize_text_field($_POST['page_header_video_cover']));
        }
        if (isset($_POST['page_header_rev_alias'])) {
            update_post_meta($post_id, 'page_header_rev_alias', sanitize_text_field($_POST['page_header_rev_alias']));
        }
        if (isset($_POST['page_title_hide'])) {
            update_post_meta($post_id, 'page_title_hide', sanitize_text_field($_POST['page_title_hide']));
        }
        if (isset($_POST['page_margin_bottom'])) {
            update_post_meta($post_id, 'page_margin_bottom', sanitize_text_field($_POST['page_margin_bottom']));
        }
        if (isset($_POST['page_template_type'])) {
            update_post_meta($post_id, 'page_template_type', sanitize_text_field($_POST['page_template_type']));
        }
        if (isset($_POST['page_listing_type'])) {
            update_post_meta($post_id, 'page_listing_type', sanitize_text_field($_POST['page_listing_type']));
        }
        if (isset($_POST['page_template_half_map_left'])) {
            update_post_meta($post_id, 'page_template_half_map_left', sanitize_text_field($_POST['page_template_half_map_left']));
        }
        if (isset($_POST['page_template_half_map_right'])) {
            update_post_meta($post_id, 'page_template_half_map_right', sanitize_text_field($_POST['page_template_half_map_right']));
        }
        if (isset($_POST['contact_page_subtitle'])) {
            update_post_meta($post_id, 'contact_page_subtitle', sanitize_text_field($_POST['contact_page_subtitle']));
        }
        if (isset($_POST['contact_page_email'])) {
            update_post_meta($post_id, 'contact_page_email', sanitize_text_field($_POST['contact_page_email']));
        }
        if (isset($_POST['contact_page_offices'])) {
            update_post_meta($post_id, 'contact_page_offices', sanitize_text_field($_POST['contact_page_offices']));
        }
        if (isset($_POST['contact_page_office_subtitle'])) {
            update_post_meta($post_id, 'contact_page_office_subtitle', sanitize_text_field($_POST['contact_page_office_subtitle']));
        }
        if (isset($_POST['contact_page_office_email'])) {
            update_post_meta($post_id, 'contact_page_office_email', sanitize_text_field($_POST['contact_page_office_email']));
        }
        if (isset($_POST['contact_page_single_office_title'])) {
            update_post_meta($post_id, 'contact_page_single_office_title', sanitize_text_field($_POST['contact_page_single_office_title']));
        }
        if (isset($_POST['contact_page_single_office_lat'])) {
            update_post_meta($post_id, 'contact_page_single_office_lat', sanitize_text_field($_POST['contact_page_single_office_lat']));
        }
        if (isset($_POST['contact_page_single_office_lng'])) {
            update_post_meta($post_id, 'contact_page_single_office_lng', sanitize_text_field($_POST['contact_page_single_office_lng']));
        }
        if (isset($_POST['contact_page_single_office_address_line_1'])) {
            update_post_meta($post_id, 'contact_page_single_office_address_line_1', sanitize_text_field($_POST['contact_page_single_office_address_line_1']));
        }
        if (isset($_POST['contact_page_single_office_address_line_2'])) {
            update_post_meta($post_id, 'contact_page_single_office_address_line_2', sanitize_text_field($_POST['contact_page_single_office_address_line_2']));
        }
        if (isset($_POST['contact_page_single_office_phone'])) {
            update_post_meta($post_id, 'contact_page_single_office_phone', sanitize_text_field($_POST['contact_page_single_office_phone']));
        }
        if (isset($_POST['contact_page_single_office_email'])) {
            update_post_meta($post_id, 'contact_page_single_office_email', sanitize_text_field($_POST['contact_page_single_office_email']));
        }
        if (isset($_POST['contact_page_margin_bottom'])) {
            update_post_meta($post_id, 'contact_page_margin_bottom', sanitize_text_field($_POST['contact_page_margin_bottom']));
        }
        if (isset($_POST['agents_page_subtitle'])) {
            update_post_meta($post_id, 'agents_page_subtitle', sanitize_text_field($_POST['agents_page_subtitle']));
        }
        if (isset($_POST['agents_page_search_form'])) {
            update_post_meta($post_id, 'agents_page_search_form', sanitize_text_field($_POST['agents_page_search_form']));
        }
        if (isset($_POST['page_header_contact_form_caption_title'])) {
            update_post_meta($post_id, 'page_header_contact_form_caption_title', sanitize_text_field($_POST['page_header_contact_form_caption_title']));
        }
        if (isset($_POST['page_header_contact_form_caption_subtitle'])) {
            update_post_meta($post_id, 'page_header_contact_form_caption_subtitle', sanitize_text_field($_POST['page_header_contact_form_caption_subtitle']));
        }
        if (isset($_POST['page_header_contact_form_caption_cta_text'])) {
            update_post_meta($post_id, 'page_header_contact_form_caption_cta_text', sanitize_text_field($_POST['page_header_contact_form_caption_cta_text']));
        }
        if (isset($_POST['page_header_contact_form_caption_cta_link'])) {
            update_post_meta($post_id, 'page_header_contact_form_caption_cta_link', sanitize_text_field($_POST['page_header_contact_form_caption_cta_link']));
        }
        if (isset($_POST['page_header_contact_form_opacity'])) {
            update_post_meta($post_id, 'page_header_contact_form_opacity', sanitize_text_field($_POST['page_header_contact_form_opacity']));
        }
        if (isset($_POST['page_header_contact_form_image'])) {
            update_post_meta($post_id, 'page_header_contact_form_image', sanitize_text_field($_POST['page_header_contact_form_image']));
        }
        if (isset($_POST['page_header_contact_form_title'])) {
            update_post_meta($post_id, 'page_header_contact_form_title', sanitize_text_field($_POST['page_header_contact_form_title']));
        }
        if (isset($_POST['page_header_contact_form_subtitle'])) {
            update_post_meta($post_id, 'page_header_contact_form_subtitle', sanitize_text_field($_POST['page_header_contact_form_subtitle']));
        }
        if (isset($_POST['page_header_contact_form_email'])) {
            update_post_meta($post_id, 'page_header_contact_form_email', sanitize_text_field($_POST['page_header_contact_form_email']));
        }
    }
endif;
add_action('save_post', 'resideo_page_meta_save');
?>