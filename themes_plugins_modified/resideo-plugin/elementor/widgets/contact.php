<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Elementor_Resideo_Contact_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'contact';
    }

    public function get_title() {
        return __('Contact', 'resideo');
    }

    public function get_icon() {
        return 'fa fa-paper-plane';
    }

    public function get_categories() {
        return ['resideo'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter title', 'resideo'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'input_type' => 'string',
                'placeholder' => __('Enter subtitle', 'resideo'),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'resideo'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'background_section',
            [
                'label' => __('Background Image', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'background_image',
            [
                'label' => __('Choose image', 'resideo'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'form_section',
            [
                'label' => __('Form', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_title',
            [
                'label' => __('Form Title', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter form title', 'resideo'),
            ]
        );

        $this->add_control(
            'form_subtitle',
            [
                'label' => __('Form Subtitle', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'input_type' => 'string',
                'placeholder' => __('Enter form subtitle', 'resideo'),
            ]
        );

        $this->add_control(
            'form_email',
            [
                'label' => __('Form Email', 'resideo'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter form email', 'resideo'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'resideo'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __('Form Position', 'resideo'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'right' => __('Right', 'resideo'),
                    'left' => __('Left', 'resideo')
                )
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'resideo'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'resideo'),
                'label_off' => __('No', 'resideo'),
                'return_value' => 'yes'
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $margin_class = $settings['margin'] == 'yes' ? 'mt-100' : '';

        $bg_image_src = '';
        $bg_image = false;
        if (isset($settings['background_image'])) {
            $bg_image = wp_get_attachment_image_src($settings['background_image']['id'], 'pxp-full');

            if ($bg_image != false) {
                $bg_image_src = $bg_image[0];
            }
        }

        $text_color    = isset($settings['text_color']) ? 'color: ' . $settings['text_color'] : '';
        $form_title    = isset($settings['form_title']) ? $settings['form_title']: '';
        $form_subtitle = isset($settings['form_subtitle']) ? $settings['form_subtitle']: '';
        $form_email    = isset($settings['form_email']) ? $settings['form_email']: '';
        $form_position = isset($settings['position']) ? $settings['position']: 'right';

        $intro_column_class = 'order-1';
        $form_column_class = 'order-3';
        if ($form_position == 'left') {
            $intro_column_class = 'order-3';
            $form_column_class = 'order-1';
        } ?>

        <div class="pxp-contact-section pxp-cover pt-100 pb-100 <?php echo esc_attr($margin_class); ?>" style="background-image: url(<?php echo esc_url($bg_image_src); ?>)">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-xl-4 align-left <?php echo esc_attr($intro_column_class); ?>">
                        <h2 class="pxp-section-h2" style="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($settings['title']); ?></h2>
                        <div class="pxp-text-light" style="<?php echo esc_attr($text_color); ?>"><?php echo $settings['subtitle']; ?></div>
                    </div>
                    <div class="col-lg-1 col-xl-3 order-2"></div>
                    <div class="col-lg-5 align-left <?php echo esc_attr($form_column_class); ?>">
                        <div class="pxp-contact-section-form mt-5 mt-lg-0">
                            <h2 class="pxp-section-h2"><?php echo esc_html($form_title); ?></h2>
                            <div><?php echo $form_subtitle; ?></div>
                            <div class="pxp-contact-section-form-response mt-4"></div>
                            <div class="mt-4">
                                <?php $contact_fields_settings = get_option('resideo_contact_fields_settings');
                                $has_fields = false;
                                if (is_array($contact_fields_settings)) {
                                    if (count($contact_fields_settings)) {
                                        $has_fields = true; ?>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="pxp-contact-section-form-email" placeholder="<?php esc_attr_e('Your email...', 'resideo'); ?>">
                                                </div>
                                            </div>
                                            <?php uasort($contact_fields_settings, "resideo_compare_position");
                                            foreach ($contact_fields_settings as $key => $value) {
                                                $is_optional = $value['mandatory'] == 'no' ? '(' . __('optional', 'resideo') . ')' : '';

                                                switch ($value['type']) {
                                                    case 'text_input_field': ?>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input type="text" data-type="text_input_field" name="<?php echo esc_attr($key) ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-section-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                            </div>
                                                        </div>
                                                    <?php break;
                                                    case 'textarea_field': ?>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <textarea data-type="textarea_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-section-field" rows="4" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>"></textarea>
                                                            </div>
                                                        </div>
                                                    <?php break;
                                                    case 'select_field':
                                                        $list = explode(',', $value['list']); ?>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <select data-type="select_field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="custom-select pxp-js-contact-section-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>">
                                                                    <option value="<?php esc_attr_e('None', 'resideo'); ?>"><?php echo esc_html($value['label']); ?> <?php echo esc_html($is_optional); ?></option>
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
                                                                <input data-type="checkbox_field" type="checkbox" class="form-check-input pxp-js-contact-section-field" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>"> <label class="form-check-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value['label']); ?> <?php echo esc_html($is_optional); ?></label>
                                                            </div>
                                                        </div>
                                                    <?php break;
                                                    case 'date_field': ?>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input data-type="date_field" type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-section-field date-picker" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                            </div>
                                                        </div>
                                                    <?php break;
                                                    default: ?>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input type="text" data-type="text_input_field" name="<?php echo esc_attr($key) ?>" id="<?php echo esc_attr($key); ?>" class="form-control pxp-js-contact-section-field" data-mandatory="<?php echo esc_attr($value['mandatory']); ?>" data-label="<?php echo esc_attr($value['label']); ?>" placeholder="<?php echo esc_attr($value['label']); ?> <?php echo esc_attr($is_optional); ?>" />
                                                            </div>
                                                        </div>
                                                    <?php break;
                                                }
                                            } ?>
                                        </div>
                                    <?php }
                                }
                                if ($has_fields === false) { ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-section-form-name" placeholder="<?php esc_attr_e('Your name', 'resideo'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="pxp-contact-section-form-phone" placeholder="<?php esc_attr_e('Your number', 'resideo'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="pxp-contact-section-form-email" placeholder="<?php esc_attr_e('Your email', 'resideo'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" id="pxp-contact-section-form-message" rows="6" placeholder="<?php esc_attr_e('Type your message...', 'resideo'); ?>"></textarea>
                                    </div>
                                <?php } ?>
                                <input type="hidden" id="pxp-contact-section-form-company-email" value="<?php echo esc_attr($form_email); ?>">
                                <a href="javascript:void(0);" class="btn pxp-contact-section-form-btn" data-custom="<?php echo esc_attr($has_fields); ?>">
                                    <span class="pxp-contact-section-form-btn-text"><?php esc_html_e('Send Message', 'resideo'); ?></span>
                                    <span class="pxp-contact-section-form-btn-sending"><img src="<?php echo esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-light.svg'); ?>" class="pxp-loader pxp-is-btn" alt="..."> <?php esc_html_e('Sending...', 'resideo'); ?></span>
                                </a>
                                <?php wp_nonce_field('contact_section_form_ajax_nonce', 'contact_section_security', true, true); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}
?>