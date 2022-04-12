<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_get_page_header')):
    function resideo_get_page_header($header_info) {
        switch ($header_info['header_type']) {
            case 'slideshow': 
                $caption_title    = get_post_meta($header_info['post_id'], 'page_header_slideshow_caption_title', true);
                $caption_subtitle = get_post_meta($header_info['post_id'], 'page_header_slideshow_caption_subtitle', true);
                $caption_position = get_post_meta($header_info['post_id'], 'page_header_slideshow_caption_position', true);
                $opacity          = get_post_meta($header_info['post_id'], 'page_header_slideshow_opacity', true);
                $show_search      = get_post_meta($header_info['post_id'], 'page_header_slideshow_show_search', true);
                $status_type      = get_post_meta($header_info['post_id'], 'page_header_slideshow_search_status_type', true);

                $gallery = get_post_meta($header_info['post_id'], 'page_header_slideshow_gallery', true);
                $ids = explode(',', $gallery);

                $interval = get_post_meta($header_info['post_id'], 'page_header_slideshow_interval', true);
                if ($interval == '') {
                    $data_interval = 6000;
                } else {
                    $data_interval = intval($interval) * 1000;
                }

                $caption_class = '';
                $caption_container_class = 'container';
                if ($caption_position == 'bottom') {
                    $caption_class = 'pxp-hero-caption-bottom-left container';
                    $caption_container_class = '';
                }
                if ($caption_position == 'left') {
                    $caption_class = 'pxp-hero-caption-left container';
                    $caption_container_class = '';
                }

                $hero_search_class = '';
                if ($status_type == 'tabs') {
                    $hero_search_class = 'pxp-has-tabs-margin';
                }

                $no_form_class = '';
                if ($show_search != '1') {
                    $no_form_class = 'pxp-no-form';
                }?>

                <div class="pxp-hero vh-100">
                    <div id="pxp-hero-carousel" class="carousel slide carousel-fade pxp-hero-bg" data-ride="carousel" data-interval="<?php echo esc_attr($data_interval); ?>" data-pause="">
                        <div class="carousel-inner">
                            <?php $counter = 0;
                            foreach ($ids as $id) {
                                if ($id != '') {
                                    $photo_src = wp_get_attachment_image_src($id, 'pxp-full');
                                    print '<div class="carousel-item pxp-cover';
                                    if ($counter == 0) {
                                        print ' active';
                                    }
                                    print '" style="background-image: url(' . esc_url($photo_src[0]) . ')"></div>';
                                    $counter++;
                                }
                            } ?>
                        </div>
                    </div>
                    <div class="pxp-hero-opacity" style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"></div>
                    <div class="pxp-hero-caption <?php echo esc_attr($caption_class); ?> <?php echo esc_attr($no_form_class); ?>">
                        <div class="<?php echo esc_attr($caption_container_class); ?>">
                            <h1 class="text-white"><?php echo esc_html($caption_title); ?></h1>
                            <p class="pxp-text-light text-white mb-0"><?php echo esc_html($caption_subtitle); ?></p>

                            <?php if ($show_search == '1') { ?>
                                <div class="hero-search <?php echo esc_attr($hero_search_class); ?>">
                                    <?php if (function_exists('resideo_get_search_properties_form')) {
                                        resideo_get_search_properties_form($status_type);
                                    } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php break;
            case 'slider': 
                $slider = get_post_meta($header_info['post_id'], 'page_header_slider', true);
                $slider_obj = json_decode($slider, true);

                $interval = get_post_meta($header_info['post_id'], 'page_header_slider_interval', true);
                $data_interval = 7000;
                $timer = false;

                if ($interval != '') {
                    $data_interval = intval($interval) * 1000;
                    $timer = floatval($interval) - 0.6;
                }

                $prev_class = 'prev';
                $next_class = 'next';
                if (is_rtl()) {
                    $prev_class = 'next';
                    $next_class = 'prev';
                }

                if ($timer) { ?>
                    <input type="hidden" class="pxp-hero-props-carousel-1-timer" value="<?php echo esc_attr($timer); ?>">
                <?php }

                $opacity = get_post_meta($header_info['post_id'], 'page_header_slider_opacity', true); ?>
                <input type="hidden" class="pxp-hero-props-carousel-1-opacity" value="<?php echo esc_attr($opacity); ?>">

                <?php $design =  get_post_meta($header_info['post_id'], 'page_header_slider_design', true); ?>

                <div class="pxp-hero vh-100">
                    <?php if ($design == 'half') { ?>
                        <div class="pxp-hero-props-carousel-2">
                            <div class="carousel slide pxp-hero-props-carousel-2-left" data-ride="carousel" data-pause="false" data-interval="false">
                                <div class="carousel-inner">
                                    <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                        $count = 0;
                                        foreach ($slider_obj as $obj) { 
                                            $slide_color = isset($obj['color']) ? $obj['color'] : '' ?>
                                            <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>" data-color="<?php echo esc_attr($slide_color); ?>">
                                                <div class="pxp-caption">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-10">
                                                            <div class="pxp-caption-prop-title"><?php echo esc_html($obj['title']); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="pxp-caption-prop-features mt-4"><?php echo esc_html($obj['subtitle']); ?></div>
                                                </div>
                                                <a href="<?php echo esc_url($obj['cta_link']); ?>" class="pxp-cta text-uppercase pxp-animate"><?php echo esc_html($obj['cta_text']); ?></a>
                                            </div>
                                            <?php $count++;
                                        }
                                    } ?>
                                </div>
                            </div>
                            <div id="pxp-hero-props-carousel-2-right" class="carousel slide pxp-hero-props-carousel-2-right" data-ride="carousel" data-pause="false" data-interval="<?php echo esc_attr($data_interval); ?>">
                                <div class="carousel-inner">
                                    <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                        $count = 0;
                                        foreach ($slider_obj as $obj) {
                                            $photo_src = wp_get_attachment_image_src($obj['id'], 'pxp-full'); ?>
                                            <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>">
                                                <div class="pxp-hero-bg pxp-cover" data-src="<?php echo esc_url($photo_src[0]); ?>" style="background-image: url(<?php echo esc_url($photo_src[0]); ?>);"></div>
                                            </div>
                                            <?php $count++;
                                        }
                                    } ?>
                                </div>
                            </div>
                            <div class="pxp-carousel-controls">
                                <a class="pxp-carousel-control-<?php echo esc_attr($prev_class); ?>" role="button" data-slide="<?php echo esc_attr($prev_class); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                        <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                            <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        </g>
                                    </svg>
                                </a>
                                <a class="pxp-carousel-control-<?php echo esc_attr($next_class); ?>" role="button" data-slide="<?php echo esc_attr($next_class); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                        <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                            <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            <div class="pxp-carousel-ticker">
                                <div class="pxp-carousel-ticker-counter"></div>
                                <div class="pxp-carousel-ticker-divider">&nbsp;&nbsp;/&nbsp;&nbsp;</div>
                                <div class="pxp-carousel-ticker-total"></div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div id="pxp-hero-props-carousel-1" class="carousel slide pxp-hero-props-carousel-1" data-ride="carousel" data-pause="false" data-interval="<?php echo esc_attr($data_interval); ?>">
                            <ol class="carousel-indicators container">
                                <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                    $count = 0;
                                    foreach ($slider_obj as $obj) {
                                        $photo_src = wp_get_attachment_image_src($obj['id'], 'pxp-thmb'); ?>
                                        <li data-target="#pxp-hero-props-carousel-1" data-slide-to="<?php echo esc_attr($count); ?>" class="pxp-cover <?php if($count == 0) { echo esc_attr('active'); } ?>" style="background-image: url(<?php echo esc_url($photo_src[0]); ?>);"></li>
                                        <?php $count++;
                                    }
                                } ?>
                            </ol>
                            <div class="carousel-inner">
                                <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                    $count = 0;
                                    foreach ($slider_obj as $obj) {
                                        $photo_src = wp_get_attachment_image_src($obj['id'], 'pxp-full'); ?>
                                        <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>">
                                            <div class="pxp-hero-bg pxp-cover" data-src="<?php echo esc_url($photo_src[0]); ?>" style="background-image: url(<?php echo esc_url($photo_src[0]); ?>)"><span></span></div>
                                            <div class="pxp-caption">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-8 col-lg-6">
                                                            <div class="pxp-caption-prop-title"><?php echo esc_html($obj['title']); ?></div>
                                                            <div class="pxp-caption-prop-features mt-4"><?php echo esc_html($obj['subtitle']); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $count++;
                                    }
                                } ?>
                            </div>
                            <div class="pxp-carousel-controls">
                                <a class="pxp-carousel-control-<?php echo esc_attr($prev_class); ?>" role="button" data-slide="<?php echo esc_attr($prev_class); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                        <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                            <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        </g>
                                    </svg>
                                </a>
                                <a class="pxp-carousel-control-<?php echo esc_attr($next_class); ?>" role="button" data-slide="<?php echo esc_attr($next_class); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                        <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                            <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="carousel slide pxp-hero-props-carousel-1-prices" data-ride="carousel" data-pause="false" data-interval="false">
                            <div class="carousel-inner">
                                <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                    $count = 0;
                                    foreach ($slider_obj as $obj) { 
                                        $slide_color = isset($obj['color']) ? $obj['color'] : '' ?>
                                        <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>" data-color="<?php echo esc_attr($slide_color); ?>">
                                            <span></span>
                                            <div class="pxp-progress"></div>
                                            <a href="<?php echo esc_url($obj['cta_link']); ?>" class="pxp-cta text-uppercase pxp-animate pxp-is-left"><?php echo esc_html($obj['cta_text']); ?></a>
                                        </div>
                                        <?php $count++;
                                    }
                                } ?>
                            </div>
                            <div class="pxp-carousel-ticker">
                                <div class="pxp-carousel-ticker-counter"></div>
                                <div class="pxp-carousel-ticker-divider">&nbsp;&nbsp;/&nbsp;&nbsp;</div>
                                <div class="pxp-carousel-ticker-total"></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php break;
            case 'p_slider': 
                $slider = get_post_meta($header_info['post_id'], 'page_header_p_slider', true);
                $slider_ids = explode(',', $slider);

                $slider_obj = array();
                if (is_array($slider_ids) && count($slider_ids) > 0) {
                    $slider_obj = resideo_get_page_header_slider_properties($slider_ids);
                }

                $interval = get_post_meta($header_info['post_id'], 'page_header_p_slider_interval', true);
                $data_interval = 7000;
                $timer = false;

                if ($interval != '') {
                    $data_interval = intval($interval) * 1000;
                    $timer = floatval($interval) - 0.6;
                }

                $prev_class = 'prev';
                $next_class = 'next';
                if (is_rtl()) {
                    $prev_class = 'next';
                    $next_class = 'prev';
                }

                if ($timer) { ?>
                    <input type="hidden" class="pxp-hero-props-carousel-1-timer" value="<?php echo esc_attr($timer); ?>">
                <?php }

                $opacity = get_post_meta($header_info['post_id'], 'page_header_p_slider_opacity', true); ?>
                <input type="hidden" class="pxp-hero-props-carousel-1-opacity" value="<?php echo esc_attr($opacity); ?>">

                <?php $design = get_post_meta($header_info['post_id'], 'page_header_p_slider_design', true); ?>

                <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                    $general_settings = get_option('resideo_general_settings');
                    $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
                    $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
                    $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA'; 

                    $currency     = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
                    $currency_pos = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
                    $locale       = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
                    $decimals     = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
                    setlocale(LC_MONETARY, $locale); ?>

                    <div class="pxp-hero vh-100">
                        <?php if ($design == 'half') { ?>
                            <div class="pxp-hero-props-carousel-2">
                                <div class="carousel slide pxp-hero-props-carousel-2-left" data-ride="carousel" data-pause="false" data-interval="false">
                                    <div class="carousel-inner">
                                        <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                            $count = 0;
                                            foreach ($slider_obj as $obj) { ?>
                                                <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>">
                                                    <div class="pxp-caption">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-10">
                                                                <div class="pxp-caption-prop-title"><?php echo esc_html($obj->title); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="pxp-caption-prop-features mt-4">
                                                            <?php if ($obj->beds != '') {
                                                                echo esc_html($obj->beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                            }
                                                            if ($obj->baths != '') {
                                                                echo esc_html($obj->baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                            }
                                                            if ($obj->size != '') {
                                                                echo esc_html($obj->size) . ' ' . esc_html($unit);
                                                            } ?>
                                                        </div>
                                                        <div class="pxp-caption-prop-price mt-5">
                                                            <?php $p_price = $obj->price;
                                                            $p_price_label = $obj->price_label;

                                                            $currency_str = $currency;

                                                            if (is_numeric($p_price)) {
                                                                if ($decimals == '1') {
                                                                    $p_price = money_format('%!i', $p_price);
                                                                } else {
                                                                    $p_price = money_format('%!.0i', $p_price);
                                                                }
                                                            } else {
                                                                $p_price_label = '';
                                                                $currency_str = '';
                                                            }

                                                            if ($currency_pos == 'before') {
                                                                echo esc_html($currency_str) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            } else {
                                                                echo esc_html($p_price) . esc_html($currency_str) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <a href="<?php echo esc_url($obj->link); ?>" class="pxp-cta text-uppercase pxp-animate"><?php esc_html_e('View Details', 'resideo'); ?></a>
                                                </div>
                                                <?php $count++;
                                            }
                                        } ?>
                                    </div>
                                </div>

                                <div id="pxp-hero-props-carousel-2-right" class="carousel slide pxp-hero-props-carousel-2-right" data-ride="carousel" data-pause="false" data-interval="<?php echo esc_attr($data_interval); ?>">
                                    <div class="carousel-inner">
                                        <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                            $count = 0;
                                            foreach ($slider_obj as $obj) { ?>
                                                <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>">
                                                    <div class="pxp-hero-bg pxp-cover" data-src="<?php echo esc_url($obj->photo[0]); ?>" style="background-image: url(<?php echo esc_url($obj->photo[0]); ?>);"></div>
                                                </div>
                                                <?php $count++;
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <div class="pxp-carousel-controls">
                                    <a class="pxp-carousel-control-<?php echo esc_attr($prev_class); ?>" role="button" data-slide="<?php echo esc_attr($prev_class); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                            <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <a class="pxp-carousel-control-<?php echo esc_attr($next_class); ?>" role="button" data-slide="<?php echo esc_attr($next_class); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                            <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                                <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                                <div class="pxp-carousel-ticker">
                                    <div class="pxp-carousel-ticker-counter"></div>
                                    <div class="pxp-carousel-ticker-divider">&nbsp;&nbsp;/&nbsp;&nbsp;</div>
                                    <div class="pxp-carousel-ticker-total"></div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div id="pxp-hero-props-carousel-1" class="carousel slide pxp-hero-props-carousel-1" data-ride="carousel" data-pause="false" data-interval="<?php echo esc_attr($data_interval); ?>">
                                <ol class="carousel-indicators container">
                                    <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                        $count = 0;
                                        foreach ($slider_obj as $obj) { ?>
                                            <li data-target="#pxp-hero-props-carousel-1" data-slide-to="<?php echo esc_attr($count); ?>" class="pxp-cover <?php if ($count == 0) { echo esc_attr('active'); } ?>" style="background-image: url(<?php echo esc_url($obj->thmb[0]); ?>);"></li>
                                            <?php $count++;
                                        }
                                    } ?>
                                </ol>

                                <div class="carousel-inner">
                                    <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                        $count = 0;
                                        foreach ($slider_obj as $obj) { ?>
                                            <div class="carousel-item <?php if ($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>">
                                                <div class="pxp-hero-bg pxp-cover" data-src="<?php echo esc_url($obj->photo[0]); ?>" style="background-image: url(<?php echo esc_url($obj->photo[0]); ?>)"><span></span></div>
                                                <div class="pxp-caption">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-8 col-lg-6">
                                                                <div class="pxp-caption-prop-title"><?php echo esc_html($obj->title); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="pxp-caption-prop-features mt-4">
                                                            <?php if ($obj->beds != '') {
                                                                echo esc_html($obj->beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                                                            }
                                                            if ($obj->baths != '') {
                                                                echo esc_html($obj->baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                                                            }
                                                            if ($obj->size != '') {
                                                                echo esc_html($obj->size) . ' ' . esc_html($unit);
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $count++;
                                        }
                                    } ?>
                                </div>

                                <div class="pxp-carousel-controls">
                                    <a class="pxp-carousel-control-<?php echo esc_attr($prev_class); ?>" role="button" data-slide="<?php echo esc_attr($prev_class); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                            <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                                <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <a class="pxp-carousel-control-<?php echo esc_attr($next_class); ?>" role="button" data-slide="<?php echo esc_attr($next_class); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                            <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                                <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                                <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="carousel slide pxp-hero-props-carousel-1-prices" data-ride="carousel" data-pause="false" data-interval="false">
                                <div class="carousel-inner">
                                    <?php if (is_array($slider_obj) && count($slider_obj) > 0) {
                                        $count = 0;
                                        foreach ($slider_obj as $obj) {
                                            $p_price = $obj->price;
                                            $p_price_label = $obj->price_label;

                                            $currency_str = $currency;

                                            if (is_numeric($p_price)) {
                                                if ($decimals == '1') {
                                                    $p_price = money_format('%!i', $p_price);
                                                } else {
                                                    $p_price = money_format('%!.0i', $p_price);
                                                }
                                            } else {
                                                $p_price_label = '';
                                                $currency_str = '';
                                            } ?>
                                            <div class="carousel-item <?php if($count == 0) { echo esc_attr('active'); } ?>" data-slide="<?php echo esc_attr($count); ?>">
                                                <span></span>
                                                <div class="pxp-progress"></div>
                                                <div class="pxp-price">
                                                    <span>
                                                    <?php if ($currency_pos == 'before') {
                                                        echo esc_html($currency_str) . esc_html($p_price) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                    } else {
                                                        echo esc_html($p_price) . esc_html($currency_str) . ' <span>' . esc_html($p_price_label) . '</span>';
                                                    } ?>
                                                    </span>
                                                </div>
                                                <a href="<?php echo esc_url($obj->link); ?>" class="pxp-cta text-uppercase pxp-animate"><?php esc_html_e('View Details', 'resideo'); ?></a>
                                            </div>
                                            <?php $count++;
                                        }
                                    } ?>
                                </div>
                                <div class="pxp-carousel-ticker">
                                    <div class="pxp-carousel-ticker-counter"></div>
                                    <div class="pxp-carousel-ticker-divider">&nbsp;&nbsp;/&nbsp;&nbsp;</div>
                                    <div class="pxp-carousel-ticker-total"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php }

                break;
            case 'image':
                $caption_title    = get_post_meta($header_info['post_id'], 'page_header_image_caption_title', true);
                $caption_subtitle = get_post_meta($header_info['post_id'], 'page_header_image_caption_subtitle', true);
                $opacity          = get_post_meta($header_info['post_id'], 'page_header_image_opacity', true);
                $show_search      = get_post_meta($header_info['post_id'], 'page_header_image_show_search', true);
                $status_type      = get_post_meta($header_info['post_id'], 'page_header_image_search_status_type', true);
                $caption_position = get_post_meta($header_info['post_id'], 'page_header_image_caption_position', true);

                $image = get_post_meta($header_info['post_id'], 'page_header_image', true);
                $image_src = wp_get_attachment_image_src($image, 'pxp-full'); 

                $caption_class = '';
                $caption_container_class = 'container';
                if ($caption_position == 'bottom') {
                    $caption_class = 'pxp-hero-caption-bottom-left container';
                    $caption_container_class = '';
                }
                if ($caption_position == 'left') {
                    $caption_class = 'pxp-hero-caption-left container';
                    $caption_container_class = '';
                }

                $hero_height = get_post_meta($header_info['post_id'], 'page_header_image_height', true);

                $hero_search_class = '';
                if ($status_type == 'tabs') {
                    $hero_search_class = 'pxp-has-tabs-margin';
                }

                $no_form_class = '';
                if ($show_search != '1') {
                    $no_form_class = 'pxp-no-form';
                }

                if ($hero_height == 'half') { ?>
                    <div class="pxp-hero pxp-hero-small vh-50">
                        <div class="pxp-hero-bg pxp-cover" style="background-image: url(<?php echo esc_url($image_src[0]); ?>);"></div>
                        <div class="pxp-hero-opacity" style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"></div>
                        <div class="pxp-hero-caption pxp-hero-caption-bottom-left pxp-is-small container <?php echo esc_attr($no_form_class); ?>">
                            <h1 class="text-white"><?php echo esc_html($caption_title); ?></h1>
                            <p class="pxp-text-light text-white mb-0"><?php echo esc_html($caption_subtitle); ?></p>

                            <?php if ($show_search == '1') { ?>
                                <div class="hero-search <?php echo esc_attr($hero_search_class); ?>">
                                    <?php if (function_exists('resideo_get_search_properties_form')) {
                                        resideo_get_search_properties_form($status_type);
                                    } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="pxp-hero vh-100">
                        <div class="pxp-hero-bg pxp-cover pxp-cover-bottom" style="background-image: url(<?php echo esc_url($image_src[0]); ?>);"></div>
                        <div class="pxp-hero-opacity" style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"></div>
                        <div class="pxp-hero-caption <?php echo esc_attr($caption_class); ?> <?php echo esc_attr($no_form_class); ?>">
                            <div class="<?php echo esc_attr($caption_container_class); ?>">
                                <h1 class="text-white"><?php echo esc_html($caption_title); ?></h1>
                                <p class="pxp-text-light text-white mb-0"><?php echo esc_html($caption_subtitle); ?></p>
    
                                <?php if ($show_search == '1') { ?>
                                    <div class="hero-search <?php echo esc_attr($hero_search_class); ?>">
                                        <?php if (function_exists('resideo_get_search_properties_form')) {
                                            resideo_get_search_properties_form($status_type);
                                        } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php }

                break;
            case 'video':
                $caption_title    = get_post_meta($header_info['post_id'], 'page_header_video_caption_title', true);
                $caption_subtitle = get_post_meta($header_info['post_id'], 'page_header_video_caption_subtitle', true);
                $opacity          = get_post_meta($header_info['post_id'], 'page_header_video_opacity', true);
                $show_search      = get_post_meta($header_info['post_id'], 'page_header_video_show_search', true);
                $status_type      = get_post_meta($header_info['post_id'], 'page_header_video_search_status_type', true);
                $caption_position = get_post_meta($header_info['post_id'], 'page_header_video_caption_position', true);

                $caption_class = '';
                $caption_container_class = 'container';
                if ($caption_position == 'bottom') {
                    $caption_class = 'pxp-hero-caption-bottom-left container';
                    $caption_container_class = '';
                }
                if ($caption_position == 'left') {
                    $caption_class = 'pxp-hero-caption-left container';
                    $caption_container_class = '';
                }

                $no_form_class = '';
                if ($show_search != '1') {
                    $no_form_class = 'pxp-no-form';
                }

                $hero_search_class = '';
                if ($status_type == 'tabs') {
                    $hero_search_class = 'pxp-has-tabs-margin';
                }

                $video_id = get_post_meta($header_info['post_id'], 'page_header_video_id', true);
                $video_sound = get_post_meta($header_info['post_id'], 'page_header_video_sound', true);

                $cover = get_post_meta($header_info['post_id'], 'page_header_video_cover', true);
                $cover_src = wp_get_attachment_image_src($cover, 'pxp-full'); ?>

                <div class="pxp-hero pxp-has-video vh-100">
                    <?php if ($cover_src !== false) { ?>
                        <div class="pxp-hero-video-cover" style="background-image: url(<?php echo esc_url($cover_src[0]); ?>)"></div>
                    <?php } ?>
                    <div class="pxp-hero-video" data-id="<?php echo esc_attr($video_id); ?>" data-sound="<?php echo esc_attr($video_sound); ?>">
                        <div class="pxp-screen mute" id="pxp-hero-video"></div>
                    </div>
                    <div class="pxp-hero-opacity" style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"></div>
                    <div class="pxp-hero-caption <?php echo esc_attr($caption_class); ?> <?php echo esc_attr($no_form_class); ?>">
                        <div class="<?php echo esc_attr($caption_container_class); ?>">
                            <h1 class="text-white"><?php echo esc_html($caption_title); ?></h1>
                            <p class="pxp-text-light text-white mb-0"><?php echo esc_html($caption_subtitle); ?></p>

                            <?php if ($show_search == '1') { ?>
                                <div class="hero-search <?php echo esc_attr($hero_search_class); ?>">
                                    <?php if (function_exists('resideo_get_search_properties_form')) {
                                        resideo_get_search_properties_form($status_type);
                                    } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php break;
            case 'contact_form':
                $caption_title = get_post_meta($header_info['post_id'], 'page_header_contact_form_caption_title', true);
                $caption_subtitle = get_post_meta($header_info['post_id'], 'page_header_contact_form_caption_subtitle', true);
                $cta_text = get_post_meta($header_info['post_id'], 'page_header_contact_form_caption_cta_text', true);
                $cta_link = get_post_meta($header_info['post_id'], 'page_header_contact_form_caption_cta_link', true);
                $opacity = get_post_meta($header_info['post_id'], 'page_header_contact_form_opacity', true);
                $form_title = get_post_meta($header_info['post_id'], 'page_header_contact_form_title', true);
                $form_subtitle = get_post_meta($header_info['post_id'], 'page_header_contact_form_subtitle', true);
                $form_email = get_post_meta($header_info['post_id'], 'page_header_contact_form_email', true);

                $image = get_post_meta($header_info['post_id'], 'page_header_contact_form_image', true);
                $image_src = wp_get_attachment_image_src($image, 'pxp-full'); ?>

                <div class="pxp-hero pxp-hero-contact">
                    <div class="pxp-hero-bg pxp-cover pxp-cover-bottom" style="background-image: url(<?php echo esc_url($image_src[0]); ?>);"></div>
                    <div class="pxp-hero-opacity" style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"></div>
                    <div class="pxp-hero-caption">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-xl-4 align-left">
                                    <h1 class="text-white"><?php echo esc_html($caption_title); ?></h1>
                                    <div class="pxp-hero-contact-form-caption-subtitle text-white mt-3 mt-lg-4"><?php echo esc_html($caption_subtitle); ?></div>
                                    <div class="pxp-hero-contact-form-caption-cta mt-4 mt-lg-5">
                                        <a href="<?php echo esc_url($cta_link) ?>" class="pxp-cta text-uppercase pxp-animate"><?php echo esc_html($cta_text); ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-xl-3"></div>
                                <div class="col-lg-5 align-left">
                                    <div class="pxp-hero-contact-form mt-5 mt-lg-0">
                                        <h2 class="pxp-section-h2"><?php echo esc_html($form_title); ?></h2>
                                        <p><?php echo esc_html($form_subtitle); ?></p>
                                        <div class="pxp-hero-contact-form-response mt-4"></div>
                                        <div class="mt-4">
                                            <?php $contact_fields_settings = get_option('resideo_contact_fields_settings'); 

                                            $has_fields = false;
                                            if (is_array($contact_fields_settings)) {
                                                if (count($contact_fields_settings)) {
                                                    $has_fields = true; ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="pxp-hero-contact-form-email" placeholder="<?php esc_attr_e('Your email', 'resideo'); ?>">
                                                            </div>
                                                        </div>

                                                        <?php uasort($contact_fields_settings, "resideo_compare_position");

                                                        foreach ($contact_fields_settings as $key => $value) {
                                                            $is_optional = $value['mandatory'] == 'no' ? '(' . __('optional', 'resideo') . ')' : '';

                                                            switch ($value['type']) {
                                                                case 'text_input_field': ?>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <input type="text" data-type="text_input_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-hero-contact-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                                        </div>
                                                                    </div>
                                                                <?php break;
                                                                case 'textarea_field': ?>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <textarea data-type="textarea_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-hero-contact-field" rows="4" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>"></textarea>
                                                                        </div>
                                                                    </div>
                                                                <?php break;
                                                                case 'select_field': 
                                                                    $list = explode(',', $value['list']); ?>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <select data-type="select_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="custom-select pxp-js-hero-contact-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>">
                                                                                <option value="<?php esc_attr_e('None', 'resideo'); ?>"><?php echo esc_html($value['label']); ?> <?php echo esc_attr($is_optional); ?></option>
                                                                                <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                                                    <option value="<?php echo esc_html($list[$i]); ?>"><?php echo esc_html($list[$i]); ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                <?php break;
                                                                case 'checkbox_field': ?>
                                                                    <div class="col-12">
                                                                        <div class="form-group form-check">
                                                                            <input data-type="checkbox_field" type="checkbox" class="form-check-input pxp-js-hero-contact-field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>"> <label class="form-check-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?></label>
                                                                        </div>
                                                                    </div>
                                                                <?php break;
                                                                case 'date_field': ?>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <input data-type="date_field" type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-hero-contact-field date-picker" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                                        </div>
                                                                    </div>
                                                                <?php break;
                                                                default: ?>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <input type="text" data-type="text_input_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-hero-contact-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                                        </div>
                                                                    </div>
                                                                <?php break;
                                                            }
                                                        } ?>
                                                    </div>
                                                <?php }
                                            } ?>

                                            <?php if ($has_fields === false) { ?>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="pxp-hero-contact-form-name" placeholder="<?php esc_attr_e('Your name', 'resideo'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="pxp-hero-contact-form-phone" placeholder="<?php esc_attr_e('Your number', 'resideo'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="pxp-hero-contact-form-email" placeholder="<?php esc_attr_e('Your email', 'resideo'); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" id="pxp-hero-contact-form-message" rows="6" placeholder="<?php esc_attr_e('Type your message...', 'resideo'); ?>"></textarea>
                                                </div>
                                            <?php } ?>

                                            <input type="hidden" id="pxp-hero-contact-form-company-email" value="<?php echo esc_attr($form_email); ?>">
                                            <a href="javascript:void(0);" class="btn pxp-hero-contact-form-btn" data-custom="<?php echo esc_attr($has_fields); ?>">
                                                <span class="pxp-hero-contact-form-btn-text"><?php esc_html_e('Send Message', 'resideo'); ?></span>
                                                <span class="pxp-hero-contact-form-btn-sending"><img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Sending...', 'resideo'); ?></span>
                                            </a>
                                            <?php wp_nonce_field('hero_contact_form_page_ajax_nonce', 'hero_contact_security', true, true); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php break;
            case 'rev': 
                $rev_alias = get_post_meta($header_info['post_id'], 'page_header_rev_alias', true); ?>

                <div class="pxp-hero vh-100">
                    <?php if ($rev_alias != '') { 
                        putRevSlider($rev_alias);
                    } ?>
                </div>
            <?php break;
        }
    }
endif;
?>