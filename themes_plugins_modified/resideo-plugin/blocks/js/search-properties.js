(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl     = wp.components.TextControl;
    var SelectControl   = wp.components.SelectControl;
    var CheckboxControl = wp.components.CheckboxControl;

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

    function SearchPropertiesControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title        = getObjectProperty(data_json, 'title');
        var subtitle     = getObjectProperty(data_json, 'subtitle');
        var align        = getObjectProperty(data_json, 'align');
        var id           = getObjectProperty(data_json, 'id');
        var address      = getObjectProperty(data_json, 'address');
        var city         = getObjectProperty(data_json, 'city');
        var neighborhood = getObjectProperty(data_json, 'neighborhood');
        var state        = getObjectProperty(data_json, 'state');
        var price        = getObjectProperty(data_json, 'price');
        var size         = getObjectProperty(data_json, 'size');
        var beds         = getObjectProperty(data_json, 'beds');
        var baths        = getObjectProperty(data_json, 'baths');
        var type         = getObjectProperty(data_json, 'type');
        var status       = getObjectProperty(data_json, 'status');
        var keywords     = getObjectProperty(data_json, 'keywords');
        var amenities    = getObjectProperty(data_json, 'amenities');
        var limit        = getObjectProperty(data_json, 'limit');

        var customFieldsList = sh_vars.property_custom_fields.replace(/\+/g, '%20');
        customFieldsList = customFieldsList != '' ? jQuery.parseJSON(decodeURIComponent(customFieldsList)) : [];

        var renderCustomFields = function() {
            var customFieldsCheckboxes = [];

            jQuery.each(customFieldsList, function(index, value) {
                var customFieldValue = getObjectProperty(data_json, value.name);
                var customFieldName = value.name;

                customFieldsCheckboxes.push(
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-6 col-md-4'
                        },
                        el(CheckboxControl, 
                            {
                                label: value.label,
                                checked: customFieldValue,
                                onChange: function(value) {
                                    data_json[customFieldName] = value;
                                    setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                                }
                            }
                        )
                    )
                );
            });

            return customFieldsCheckboxes;
        };

        var searchPropertiesOptions = [
            el('div',
                {
                    className: 'row'
                },
                el('div',
                    {
                        className: 'col-xs-12 col-sm-5'
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
                        className: 'col-xs-12 col-sm-5'
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
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-2'
                    },
                    el(SelectControl, 
                        {
                            label: __('Align', 'resideo'),
                            value: align,
                            options: [
                                { label: __('Left', 'resideo'), value: 'left' },
                                { label: __('Center', 'resideo'), value: 'center' }
                            ],
                            onChange: function(value) {
                                data_json.align = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
            ),
            el('h4', 
                {
                    className: 'search-properties-list-header'
                },
                __('Fields List', 'resideo')
            ),
            el('div',
                {
                    className: 'row'
                },
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Status', 'resideo'),
                            checked: status,
                            onChange: function(value) {
                                data_json.status = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Address', 'resideo'),
                            checked: address,
                            onChange: function(value) {
                                data_json.address = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('City', 'resideo'),
                            checked: city,
                            onChange: function(value) {
                                data_json.city = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Neighborhood', 'resideo'),
                            checked: neighborhood,
                            onChange: function(value) {
                                data_json.neighborhood = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('County/State', 'resideo'),
                            checked: state,
                            onChange: function(value) {
                                data_json.state = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Type', 'resideo'),
                            checked: type,
                            onChange: function(value) {
                                data_json.type = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Price', 'resideo'),
                            checked: price,
                            onChange: function(value) {
                                data_json.price = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Beds', 'resideo'),
                            checked: beds,
                            onChange: function(value) {
                                data_json.beds = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Baths', 'resideo'),
                            checked: baths,
                            onChange: function(value) {
                                data_json.baths = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Size', 'resideo'),
                            checked: size,
                            onChange: function(value) {
                                data_json.size = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Keywords', 'resideo'),
                            checked: keywords,
                            onChange: function(value) {
                                data_json.keywords = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Property ID', 'resideo'),
                            checked: id,
                            onChange: function(value) {
                                data_json.id = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6 col-md-4'
                    },
                    el(CheckboxControl, 
                        {
                            label: __('Amenitites', 'resideo'),
                            checked: amenities,
                            onChange: function(value) {
                                data_json.amenities = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            ),
            el('h4', 
                {
                    className: 'search-properties-list-header'
                },
                __('Custom Fields List', 'resideo')
            ),
            el('div',
                {
                    className: 'row'
                },
                renderCustomFields()
            ),
            el('h4', 
                {
                    className: 'search-properties-list-header'
                },
                __('Limit Main Fields', 'resideo')
            ),
            el('span',
                {
                    className: 'search-properties-inline-label'
                },
                __('Display', 'resideo')
            ),
            el(TextControl, 
                {
                    className: 'search-properties-inline-field',
                    value: limit,
                    onChange: function(value) {
                        data_json.limit = value;
                        setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                    }
                }
            ),
            el('span',
                {
                    className: 'search-properties-inline-label'
                },
                __('fields in main area', 'resideo')
            ),
        ];

        if (isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'search-properties-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'search-properties-placeholder-subheader'
                    },
                    subtitle
                ),
                searchPropertiesOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'search-properties-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'search-properties-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'search-properties-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/search-properties', {
        title: __('Search Properties', 'resideo'),
        description: __('Resideo search properties form block.', 'resideo'),
        icon: {
            src: 'search',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('properties', 'resideo'),
            __('search', 'resideo'),
            __('listings', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22align%22%3A%22left%22%2C%22id%22%3A%22%22%2C%22address%22%3A%22%22%2C%22city%22%3A%22%22%2C%22neighborhood%22%3A%22%22%2C%22state%22%3A%22%22%2C%22price%22%3A%22%22%2C%22size%22%3A%22%22%2C%22beds%22%3A%22%22%2C%22baths%22%3A%22%22%2C%22type%22%3A%22%22%2C%22status%22%3A%22%22%2C%22keywords%22%3A%22%22%2C%22limit%22%3A%222%22%2C%22amenities%22%3A%22%22%7D'
            }
        },
        edit: withState({})(SearchPropertiesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);