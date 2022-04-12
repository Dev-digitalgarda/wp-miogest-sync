(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPalette  = wp.components.ColorPalette;

    var el = wp.element.createElement;

    var withState = wp.compose.withState;

    var __ = wp.i18n.__;

    var types = [{
        'label' : __('All', 'resideo'),
        'value' : '0'
    }];
    var statuses = [{
        'label' : __('All', 'resideo'),
        'value' : '0'
    }];

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

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
            'action': 'resideo_get_types_statuses'
        },
        success: function(data) {
            if (data.getts === true) {
                for (var i = 0; i < data.types.length; i++) {
                    types.push({ 
                        'label' : data.types[i].name,
                        'value' : data.types[i].term_id
                    });
                }
                for (var i = 0; i < data.statuses.length; i++) {
                    statuses.push({
                        'label' : data.statuses[i].name,
                        'value' : data.statuses[i].term_id
                    });
                }
            }
        },
        error: function(errorThrown) {}
    });

    function RecentPropertiesControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var subtitle  = getObjectProperty(data_json, 'subtitle');
        var cta_label = getObjectProperty(data_json, 'cta_label');
        var cta_link  = getObjectProperty(data_json, 'cta_link');
        var cta_color = getObjectProperty(data_json, 'cta_color');
        var type      = getObjectProperty(data_json, 'type');
        var status    = getObjectProperty(data_json, 'status');
        var number    = getObjectProperty(data_json, 'number');
        var margin    = getObjectProperty(data_json, 'margin');
        var layout    = getObjectProperty(data_json, 'layout');

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

        var recentPropertiesOptions = [
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
                    el(TextControl, 
                        {
                            label: __('CTA Button Text', 'resideo'),
                            value: cta_label,
                            placeholder: __('Enter CTA button text', 'resideo'),
                            onChange: function(value) {
                                data_json.cta_label = value;
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
                            label: __('CTA Button Link', 'resideo'),
                            value: cta_link,
                            placeholder: __('Enter CTA button link', 'resideo'),
                            onChange: function(value) {
                                data_json.cta_link = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            ),
            renderCTAColorSelector,
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
                            label: __('Type', 'resideo'),
                            value: type,
                            options: types,
                            onChange: function(value) {
                                data_json.type = value;
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
                            label: __('Status', 'resideo'),
                            value: status,
                            options: statuses,
                            onChange: function(value) {
                                data_json.status = value;
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
                    el(TextControl, 
                        {
                            label: __('Number of Properties', 'resideo'),
                            value: number,
                            placeholder: __('Enter number of properties', 'resideo'),
                            onChange: function(value) {
                                data_json.number = value;
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
                                { label: __('Layout 2', 'resideo'), value: '2' },
                                { label: __('Layout 3', 'resideo'), value: '3' }
                            ],
                            onChange: function(value) {
                                data_json.layout = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
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
                        className: 'recent-properties-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'recent-properties-placeholder-subheader'
                    },
                    subtitle
                ),
                recentPropertiesOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'recent-properties-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'recent-properties-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'recent-properties-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/recent-properties', {
        title: __('Recent Properties', 'resideo'),
        description: __('Resideo recent properties block.', 'resideo'),
        icon: {
            src: 'admin-multisite',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('latest', 'resideo'),
            __('recent', 'resideo'),
            __('properties', 'resideo'),
            __('listings', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22cta_label%22%3A%22%22%2C%22cta_link%22%3A%22%22%2C%22cta_color%22%3A%22%23333333%22%2C%22type%22%3A%220%22%2C%22status%22%3A%220%22%2C%22number%22%3A%223%22%2C%22margin%22%3A%22no%22%2C%22layout%22%3A%221%22%7D'
            }
        },
        edit: withState({})(RecentPropertiesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);