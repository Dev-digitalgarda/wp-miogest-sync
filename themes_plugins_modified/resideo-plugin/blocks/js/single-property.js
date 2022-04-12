(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button        = wp.components.Button;
    var Modal         = wp.components.Modal;
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

    function SinglePropertyControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var isOpen        = props.isOpen;
        var className     = props.className;
        var isSelected    = props.isSelected;
        var propsList     = props.propsList;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var name      = getObjectProperty(data_json, 'name');
        var id        = getObjectProperty(data_json, 'id');
        var position  = getObjectProperty(data_json, 'position');
        var margin    = getObjectProperty(data_json, 'margin');
        var cta_color = getObjectProperty(data_json, 'cta_color');

        var getPropertiesList = function(term) {
            var properties = [];

            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: sh_vars.ajaxurl,
                data: {
                    'action': 'resideo_get_autocomplete_properties',
                    'keyword': term
                },
                success: function(data) {
                    properties = [];
                    if (data.getprops === true) {
                        for (var i = 0; i < data.props.length; i++) {
                            properties.push({
                                'label': data.props[i].title,
                                'value': data.props[i].id
                            });
                        }
                    } else {
                        properties.push({
                            'label': sh_vars.modal_no_properties,
                            'value': ''
                        });
                    }
                    setState({ propsList: properties });
                },
                error: function(errorThrown) {}
            });
        };

        var onPropertyClick = function(event) {
            var target = jQuery(event.target);

            data_json.name = target.text().trim();
            data_json.id = target.attr('data-id');
            setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });

            setState({
                isOpen: false,
                propsList: null
            });
        };

        var renderPropertiesList = function(ps) {
            var list = [];

            if (ps) {
                for (var i = 0; i < ps.length; i++) {
                    list.push(el('div', 
                        {
                            className: 'is-property'
                        },
                        el('div', 
                            {
                                className: 'pxp-properties-item',
                                'data-id': ps[i].value,
                                onClick: (event) => {
                                    onPropertyClick(event);
                                }
                            },
                            ps[i].label
                        )
                    ));
                }
            }

            return list;
        };

        var renderPropertiesModal = isOpen ? el(Modal, 
            {
                title: __('Properties', 'resideo'),
                className: 'pxp-single-property-list-modal',
                onRequestClose: function() {
                    setState({
                        isOpen: false, 
                        propsList: null 
                    });
                }
            },
            el('div', 
                {
                    className: 'pxp-properties-modal-search'
                },
                el('div', 
                    {
                        className: 'pxp-properties-search-field'
                    },
                    el('span', 
                        {
                            className: 'fa fa-search'
                        }
                    ),
                    el(TextControl, 
                        {
                            placeholder: __('Search properties...', 'resideo'),
                            onChange: function(value) {
                                getPropertiesList(value);
                            }
                        }
                    )
                )
            ),
            el('div', 
                {},
                renderPropertiesList(propsList)
            )
        ) : null;

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

        var singlePropertyOptions = [
            el(Button,
                {
                    className: 'pxp-add-single-property-btn',
                    isSecondary: true,
                    isSmall: true,
                    onClick: function() {
                        setState({ isOpen: true });
                    }
                },
                __('Add Property', 'resideo')
            ),
            renderPropertiesModal,
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
                            label: __('Image Position', 'resideo'),
                            value: position,
                            options: [
                                { label: __('Left', 'resideo'), value: 'left' },
                                { label: __('Right', 'resideo'), value: 'right' }
                            ],
                            onChange: function(value) {
                                data_json.position = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    ),
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
            renderCTAColorSelector
        ];

        if (isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'single-property-placeholder-header'
                    },
                    name
                ),
                singlePropertyOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'single-property-placeholder-header'
                    },
                    name
                ),
                el('div', 
                    {
                        className: 'single-property-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/single-property', {
        title: __('Single Property', 'resideo'),
        description: __('Resideo single property block.', 'resideo'),
        icon: {
            src: 'admin-home',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('property', 'resideo'),
            __('single', 'resideo'),
            __('listing', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22name%22%3A%22%22%2C%22id%22%3A%22%22%2C%22position%22%3A%22left%22%2C%22margin%22%3A%22no%22%2C%22cta_color%22%3A%22%23333333%22%7D'
            }
        },
        edit: withState({})(SinglePropertyControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);