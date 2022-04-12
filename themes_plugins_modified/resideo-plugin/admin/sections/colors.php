<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_colors')): 
    function resideo_admin_colors() {
        add_settings_section('resideo_colors_section', __( 'Colors', 'resideo' ), 'resideo_colors_section_callback', 'resideo_colors_settings');
    }
endif;

if (!function_exists('resideo_colors_section_callback')): 
    function resideo_colors_section_callback() { 
        $options = get_option('resideo_colors_settings'); ?>

        <br><h4><?php esc_html_e('Header', 'resideo'); ?></h4>

        <table>
            <tbody>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Background', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_header_bg_color_field]" 
                                            name="resideo_colors_settings[resideo_header_bg_color_field]" 
                                            value="<?php if (isset($options['resideo_header_bg_color_field'])) { echo esc_attr($options['resideo_header_bg_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Logo Text', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_header_logo_color_field]" 
                                            name="resideo_colors_settings[resideo_header_logo_color_field]" 
                                            value="<?php if (isset($options['resideo_header_logo_color_field'])) { echo esc_attr($options['resideo_header_logo_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Navigation', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_header_nav_color_field]" 
                                            name="resideo_colors_settings[resideo_header_nav_color_field]" 
                                            value="<?php if (isset($options['resideo_header_nav_color_field'])) { echo esc_attr($options['resideo_header_nav_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('User Icon', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_header_user_icon_color_field]" 
                                            name="resideo_colors_settings[resideo_header_user_icon_color_field]" 
                                            value="<?php if (isset($options['resideo_header_user_icon_color_field'])) { echo esc_attr($options['resideo_header_user_icon_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br><hr><br>

        <h4><?php esc_html_e('Content', 'resideo'); ?></h4>

        <table>
            <tbody>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Button Background', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_button_bg_color_field]" 
                                            name="resideo_colors_settings[resideo_button_bg_color_field]" 
                                            value="<?php if (isset($options['resideo_button_bg_color_field'])) { echo esc_attr($options['resideo_button_bg_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Button Text', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_button_text_color_field]" 
                                            name="resideo_colors_settings[resideo_button_text_color_field]" 
                                            value="<?php if (isset($options['resideo_button_text_color_field'])) { echo esc_attr($options['resideo_button_text_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Featured Property Label Background', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_feat_prop_bg_color_field]" 
                                            name="resideo_colors_settings[resideo_feat_prop_bg_color_field]" 
                                            value="<?php if (isset($options['resideo_feat_prop_bg_color_field'])) { echo esc_attr($options['resideo_feat_prop_bg_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Featured Property Label Text', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_feat_prop_text_color_field]" 
                                            name="resideo_colors_settings[resideo_feat_prop_text_color_field]" 
                                            value="<?php if (isset($options['resideo_feat_prop_text_color_field'])) { echo esc_attr($options['resideo_feat_prop_text_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Featured Post Label Background', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_feat_post_bg_color_field]" 
                                            name="resideo_colors_settings[resideo_feat_post_bg_color_field]" 
                                            value="<?php if (isset($options['resideo_feat_post_bg_color_field'])) { echo esc_attr($options['resideo_feat_post_bg_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Featured Post Label Text', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_feat_post_text_color_field]" 
                                            name="resideo_colors_settings[resideo_feat_post_text_color_field]" 
                                            value="<?php if (isset($options['resideo_feat_post_text_color_field'])) { echo esc_attr($options['resideo_feat_post_text_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Map Marker Background', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_map_marker_bg_color_field]" 
                                            name="resideo_colors_settings[resideo_map_marker_bg_color_field]" 
                                            value="<?php if (isset($options['resideo_map_marker_bg_color_field'])) { echo esc_attr($options['resideo_map_marker_bg_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Map Marker Border', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_map_marker_border_color_field]" 
                                            name="resideo_colors_settings[resideo_map_marker_border_color_field]" 
                                            value="<?php if (isset($options['resideo_map_marker_border_color_field'])) { echo esc_attr($options['resideo_map_marker_border_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Map Marker Text', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_map_marker_text_color_field]" 
                                            name="resideo_colors_settings[resideo_map_marker_text_color_field]" 
                                            value="<?php if (isset($options['resideo_map_marker_text_color_field'])) { echo esc_attr($options['resideo_map_marker_text_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Agent Card CTA', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_agent_card_cta_color_field]" 
                                            name="resideo_colors_settings[resideo_agent_card_cta_color_field]" 
                                            value="<?php if (isset($options['resideo_agent_card_cta_color_field'])) { echo esc_attr($options['resideo_agent_card_cta_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Blog Post Card CTA', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_post_card_cta_color_field]" 
                                            name="resideo_colors_settings[resideo_post_card_cta_color_field]" 
                                            value="<?php if (isset($options['resideo_post_card_cta_color_field'])) { echo esc_attr($options['resideo_post_card_cta_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br><hr><br>

        <h4><?php esc_html_e('Footer', 'resideo'); ?></h4>

        <table>
            <tbody>
                <tr>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Background', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_footer_bg_color_field]" 
                                            name="resideo_colors_settings[resideo_footer_bg_color_field]" 
                                            value="<?php if (isset($options['resideo_footer_bg_color_field'])) { echo esc_attr($options['resideo_footer_bg_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="40">&nbsp;</td>
                    <td>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Text', 'resideo') ?></th>
                                    <td>
                                        <input 
                                            type="text" 
                                            class="color-field" 
                                            id="resideo_colors_settings[resideo_footer_text_color_field]" 
                                            name="resideo_colors_settings[resideo_footer_text_color_field]" 
                                            value="<?php if (isset($options['resideo_footer_text_color_field'])) { echo esc_attr($options['resideo_footer_text_color_field']); } ?>"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php }
endif;
?>