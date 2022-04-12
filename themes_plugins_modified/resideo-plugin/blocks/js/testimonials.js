(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button        = wp.components.Button;
    var ColorPalette  = wp.components.ColorPalette;

    var el = wp.element.createElement;

    var MediaUpload = wp.blockEditor.MediaUpload;

    var withState = wp.compose.withState;

    var __ = wp.i18n.__;

    function getObjectProperty(obj, prop) {
        var prop = typeof prop !== 'undefined' ? prop : '';
        var obj = typeof obj !== 'undefined' ? obj : '';

        if(!prop || !obj) {
            return '';
        }

        var ret = obj.hasOwnProperty(prop) ? ( String(obj[prop]) !== ''? obj[prop] : '') : '';

        return ret;
    }

    function getAttr(s, n) {
        n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
        return n ? window.decodeURIComponent(n[1]) : '';
    };

    function TestimonialsControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title      = getObjectProperty(data_json, 'title');
        var subtitle   = getObjectProperty(data_json, 'subtitle');
        var cta_text   = getObjectProperty(data_json, 'cta_text');
        var cta_link   = getObjectProperty(data_json, 'cta_link');
        var cta_color  = getObjectProperty(data_json, 'cta_color');
        var image      = getObjectProperty(data_json, 'image');
        var image_src  = getObjectProperty(data_json, 'image_src');
        var margin     = getObjectProperty(data_json, 'margin');
        var text_color = getObjectProperty(data_json, 'text_color');
        var layout     = getObjectProperty(data_json, 'layout');

        var renderTextColorSelector = el('div',
            {
                className: 'components-base-control'
            },
            el('div',
                {
                    className: 'components-base-control__field'
                },
                el('fieldset',
                    {},
                    el('legend',
                        {},
                        el('div',
                            {},
                            el('span',
                                {
                                    className: 'components-base-control__label'
                                },
                                __('Text Color', 'resideo'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: text_color,
                            colors: [
                                { name: 'Pale pink', color: '#f58fa8' },
                                { name: 'Vivid red', color: '#cd3235' },
                                { name: 'Luminous vivid orange', color: '#fd6a29' },
                                { name: 'Luminous vivid amber', color: '#fcb738' },
                                { name: 'Light green cyan', color: '#80dab7' },
                                { name: 'Vivid green cyan', color: '#2bcd89' },
                                { name: 'Pale cyan blue', color: '#8fd2f9' },
                                { name: 'Vivid cyan blue', color: '#0896df' },
                                { name: 'Vivid purple', color: '#975cdb' },
                                { name: 'Very light gray', color: '#eeeeee' },
                                { name: 'Cyan bluish gray', color: '#abb9c2' },
                                { name: 'Very dark gray', color: '#333333' }
                            ],
                            onChange: function(value) {
                                data_json.text_color = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            )
        );

        var renderCTAColorSelector = el('div',
            {
                className: 'components-base-control'
            },
            el('div',
                {
                    className: 'components-base-control__field'
                },
                el('fieldset',
                    {},
                    el('legend',
                        {},
                        el('div',
                            {},
                            el('span',
                                {
                                    className: 'components-base-control__label'
                                },
                                __('CTA Color', 'resideo'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: cta_color,
                            colors: [
                                { name: 'Pale pink', color: '#f58fa8' },
                                { name: 'Vivid red', color: '#cd3235' },
                                { name: 'Luminous vivid orange', color: '#fd6a29' },
                                { name: 'Luminous vivid amber', color: '#fcb738' },
                                { name: 'Light green cyan', color: '#80dab7' },
                                { name: 'Vivid green cyan', color: '#2bcd89' },
                                { name: 'Pale cyan blue', color: '#8fd2f9' },
                                { name: 'Vivid cyan blue', color: '#0896df' },
                                { name: 'Vivid purple', color: '#975cdb' },
                                { name: 'Very light gray', color: '#eeeeee' },
                                { name: 'Cyan bluish gray', color: '#abb9c2' },
                                { name: 'Very dark gray', color: '#333333' }
                            ],
                            onChange: function(value) {
                                data_json.cta_color = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            )
        );

        var testimonialsOptions = [
            el('div', 
                {
                    className: 'row'
                },
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(TextControl, 
                        {
                            label: __('Title', 'resideo'),
                            value: title,
                            placeholder: __('Enter title', 'resideo'),
                            onChange: function(value) {
                                data_json.title = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(TextControl, 
                        {
                            label: __('Subtitle', 'resideo'),
                            value: subtitle,
                            placeholder: __('Enter subtitle', 'resideo'),
                            onChange: function(value) {
                                data_json.subtitle = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            ),
            el('div',
                {
                    className: 'row'
                },
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(SelectControl, 
                        {
                            label: __('Layout', 'resideo'),
                            value: layout,
                            options: [
                                { label: __('Layout 1', 'resideo'), value: '1' },
                                { label: __('Layout 2', 'resideo'), value: '2' }
                            ],
                            onChange: function(value) {
                                data_json.layout = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(SelectControl, 
                        {
                            label: __('Margin', 'resideo'),
                            value: margin,
                            options: [
                                { label: __('No', 'resideo'), value: 'no' },
                                { label: __('Yes', 'resideo'), value: 'yes' }
                            ],
                            onChange: function(value) {
                                data_json.margin = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            ),
            renderTextColorSelector,
            el('div',
                {
                    className: 'row',
                    style: {
                        display: layout == 2 ? 'none' : 'block'
                    }
                },
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(MediaUpload,
                        {
                            onSelect: function(media) {
                                jQuery('.pxp-block-testimonials-bg-image-btn')
                                    .css('background-image', 'url(' + media.url + ')')
                                    .text('')
                                    .attr({
                                        'data-src': media.url,
                                        'data-id': media.id
                                    });
                                data_json.image_src = media.url;
                                data_json.image = media.id;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            },
                            type: 'image',
                            render: function(obj) {
                                return el(Button,
                                    {
                                        className: 'pxp-block-testimonials-bg-image-btn',
                                        'data-src': image_src,
                                        'data-id': image,
                                        style: {
                                            backgroundImage: 'url(' + image_src + ')',
                                        },
                                        onClick: obj.open
                                    },
                                    __('Background Image', 'resideo')
                                );
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(TextControl, 
                        {
                            label: __('CTA Button Text', 'resideo'),
                            value: cta_text,
                            placeholder: __('Enter the CTA button text', 'resideo'),
                            onChange: function(value) {
                                data_json.cta_text = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    ),
                    el(TextControl, 
                        {
                            label: __('CTA Button Link', 'resideo'),
                            value: cta_link,
                            placeholder: __('Enter the CTA button link', 'resideo'),
                            onChange: function(value) {
                                data_json.cta_link = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12'
                    },
                    renderCTAColorSelector
                )
            )
        ];

        if (isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'testimonials-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'testimonials-placeholder-subheader'
                    },
                    subtitle
                ),
                testimonialsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'testimonials-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'testimonials-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'testimonials-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/testimonials', {
        title: __('Testimonials', 'resideo'),
        description: __('Resideo testimonials block.', 'resideo'),
        icon: {
            src: 'format-quote',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('testimonials', 'resideo'),
            __('clients', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22cta_text%22%3A%22%22%2C%22cta_link%22%3A%22%22%2C%22cta_color%22%3A%22%22%2C%22image%22%3A%22%22%2C%22image_src%22%3A%22%22%2C%22margin%22%3A%22no%22%2C%22text_color%22%3A%22%22%2C%22layout%22%3A%221%22%7D'
            }
        },
        edit: withState({})(TestimonialsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);