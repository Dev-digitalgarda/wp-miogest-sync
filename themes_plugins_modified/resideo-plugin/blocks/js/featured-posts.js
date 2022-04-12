(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPalette  = wp.components.ColorPalette;

    var el = wp.element.createElement;

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

    function FeaturedPostsControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title          = getObjectProperty(data_json, 'title');
        var subtitle       = getObjectProperty(data_json, 'subtitle');
        var margin         = getObjectProperty(data_json, 'margin');
        var cta_color      = getObjectProperty(data_json, 'cta_color');
        var card_cta_color = getObjectProperty(data_json, 'card_cta_color');

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
                                __('CTA Button Color', 'resideo'),
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

        var renderCardCTAColorSelector = el('div',
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
                                __('Post Card CTA Color', 'resideo'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: card_cta_color,
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
                                data_json.card_cta_color = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            )
        );

        var featuredPostsOptions = [
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
            renderCTAColorSelector,
            renderCardCTAColorSelector
        ];

        if(isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'featured-posts-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'featured-posts-placeholder-subheader'
                    },
                    subtitle
                ),
                featuredPostsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'featured-posts-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'featured-posts-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'featured-posts-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/featured-posts', {
        title: __('Featured Blog Posts', 'resideo'),
        description: __('Resideo featured blog posts block.', 'resideo'),
        icon: {
            src: 'excerpt-view',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('featured', 'resideo'),
            __('posts', 'resideo'),
            __('articles', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22margin%22%3A%22no%22%2C%22cta_color%22%3A%22%23333333%22%2C%22card_cta_color%22%3A%22%23333333%22%7D'
            }
        },
        edit: withState({})(FeaturedPostsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);