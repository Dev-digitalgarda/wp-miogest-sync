(function(wp) {
    "use strict";

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

    function AreasControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newImageSrc       = props.newImageSrc;
        var newImageId        = props.newImageId;
        var newNeighborhood   = props.newNeighborhood;
        var newNeighborhoodId = props.newNeighborhoodId;
        var newCity           = props.newCity;
        var newCityId         = props.newCityId;
        var newCTAColor       = props.newCTAColor;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var subtitle  = getObjectProperty(data_json, 'subtitle');
        var cta_label = getObjectProperty(data_json, 'cta_label');
        var cta_link  = getObjectProperty(data_json, 'cta_link');
        var cta_color = getObjectProperty(data_json, 'cta_color');
        var margin    = getObjectProperty(data_json, 'margin');
        var layout    = getObjectProperty(data_json, 'layout');
        var areas     = getObjectProperty(data_json, 'areas');

        if (!jQuery.isArray(areas)) {
            areas = [];
        }

        var areas_cities_list = sh_vars.areas_cities_list.replace(/\+/g, '%20');
        areas_cities_list = areas_cities_list != '' ? jQuery.parseJSON(decodeURIComponent(areas_cities_list)) : [];

        var areas_neighborhoods_list = sh_vars.areas_neighborhoods_list.replace(/\+/g, '%20');
        areas_neighborhoods_list = areas_neighborhoods_list != '' ? jQuery.parseJSON(decodeURIComponent(areas_neighborhoods_list)) : [];

        var renderNeighborhoodField = function() {
            if (sh_vars.areas_neighborhood_type == 'list') {
                var neighborhoodsList = [
                    { label: __('Select a neighborhood', 'resideo'), value: '' }
                ];

                jQuery.each(areas_neighborhoods_list, function(index, value) {
                    neighborhoodsList.push({
                        label: value.name,
                        value: value.id
                    });
                });

                return el(SelectControl, 
                    {
                        id: 'areas-list-new-neighborhood',
                        label: __('Neighborhood', 'resideo'),
                        value: newNeighborhoodId,
                        options: neighborhoodsList,
                        onChange: function(newNeighborhoodId) {
                            var new_neighborhood = '';
                            jQuery.each(areas_neighborhoods_list, function(index, value) {
                                if (value.id == newNeighborhoodId) {
                                    new_neighborhood = value.name;
                                }
                            });
                            setState({ newNeighborhood: new_neighborhood, newNeighborhoodId });
                        }
                    }
                );
            } else {
                return el(TextControl, 
                    {
                        id: 'areas-list-new-neighborhood',
                        label: __('Neighborhood', 'resideo'),
                        placeholder: __('Enter neighborhood', 'resideo'),
                        value: newNeighborhood,
                        onChange: function(newNeighborhood) {
                            setState({ newNeighborhood });
                        }
                    }
                );
            }
        };

        var renderCityField = function() {
            if (sh_vars.areas_city_type == 'list') {
                var citiesList = [
                    { label: __('Select a city', 'resideo'), value: '' }
                ];

                jQuery.each(areas_cities_list, function(index, value) {
                    citiesList.push({
                        label: value.name,
                        value: value.id
                    });
                });

                return el(SelectControl, 
                    {
                        id: 'areas-list-new-city',
                        label: __('City', 'resideo'),
                        value: newCityId,
                        options: citiesList,
                        onChange: function(newCityId) {
                            var new_city = '';
                            jQuery.each(areas_cities_list, function(index, value) {
                                if (value.id == newCityId) {
                                    new_city = value.name;
                                }
                            });
                            setState({ newCity: new_city, newCityId });
                        }
                    }
                );
            } else {
                return el(TextControl, 
                    {
                        id: 'areas-list-new-city',
                        label: __('City', 'resideo'),
                        placeholder: __('Enter city', 'resideo'),
                        value: newCity,
                        onChange: function(newCity) {
                            setState({ newCity });
                        }
                    }
                );
            }
        };

        var onNewImageSelect = function(media) {
            setState({
                newImageSrc: media.url,
                newImageId: media.id,
            });
        };

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

        var renderAreaCTAColorSelector = el('div',
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
                                __('Area CTA Color', 'resideo'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: newCTAColor,
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
                            onChange: function (newCTAColor) {
                                setState({ newCTAColor });
                            }
                        }
                    )
                )
            )
        );

        var renderAreasList = function() {
            var items = [];

            if (areas.length > 0) {
                jQuery.each(areas, function(index, elem) {
                    items.push(
                        el('li',
                            {},
                            el('div',
                                {
                                    className: 'row'
                                },
                                el('div',
                                    {
                                        className: 'col-xs-2'
                                    },
                                    el('div',
                                        {
                                            className: 'areas-list-item-img',
                                            style: {
                                                'background-image': 'url(' + elem.src + ')'
                                            }
                                        }
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'col-xs-8'
                                    },
                                    el('div',
                                        {
                                            className: 'areas-list-item-neighborhood'
                                        },
                                        elem.neighborhood
                                    ),
                                    el('div',
                                        {
                                            className: 'areas-list-item-city'
                                        },
                                        elem.city
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'col-xs-2'
                                    },
                                    el('a', 
                                        {
                                            onClick: function(event) {
                                                var target = jQuery(event.target);
                                                var elemIndex = target.parent().parent().parent().index();

                                                data_json.areas.splice(elemIndex, 1);

                                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                                            }
                                        },
                                        __('Delete', 'resideo')
                                    )
                                )
                            )
                        )
                    );
                });
            }

            return items;
        };

        var areasOptions = [
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
                            placeholder: __('Enter the CTA button text', 'resideo'),
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
                            placeholder: __('Enter the CTA button link', 'resideo'),
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
                            label: __('Layout', 'resideo'),
                            value: layout,
                            options: [
                                { label: __('Layout 1', 'resideo'), value: '1' },
                                { label: __('Layout 2', 'resideo'), value: '2' },
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
            el('h4', 
                {
                    className: 'areas-list-header'
                },
                __('Areas List', 'resideo')
            ),
            el('div',
                {
                    className: 'areas-list-container'
                },
                el('ul',
                    {},
                    renderAreasList()
                )
            ),
            el(Button,
                {
                    className : 'areas-list-add-form-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.areas-list-new-form').show();
                    }
                },
                __('Add New Area', 'resideo')
            ),
            el('div',
                {
                    className: 'areas-list-new-form'
                },
                el('h5', 
                    {
                        className: 'areas-list-header'
                    },
                    __('New Area', 'resideo')
                ),
                el('div',
                    {
                        className: 'row'
                    },
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-4'
                        },
                        el(MediaUpload,
                            {
                                onSelect: function(media) {
                                    onNewImageSelect(media);
                                },
                                type: 'image',
                                render: function(obj) {
                                    return el(Button,
                                        {
                                            className: 'areas-list-new-upload',
                                            style: {
                                                backgroundImage: newImageSrc != '' ? 'url(' + newImageSrc + ')' : 'none',
                                            },
                                            onClick: obj.open
                                        },
                                        __('Add Image', 'resideo')
                                    );
                                }
                            }
                        )
                    ),
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-8'
                        },
                        renderNeighborhoodField(),
                        renderCityField()
                    )
                ),
                renderAreaCTAColorSelector,
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className : 'areas-list-add-btn',
                        onClick: function(event) {
                            areas.push({
                                'src'             : newImageSrc,
                                'id'              : newImageId,
                                'neighborhood'    : newNeighborhood,
                                'neighborhood_id' : newNeighborhoodId,
                                'city'            : newCity,
                                'city_id'         : newCityId,
                                'cta_color'       : newCTAColor
                            });

                            data_json.areas = areas;
                            setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });

                            setState({
                                newImageSrc: '',
                                newImageId: '',
                                newNeighborhood: '',
                                newNeighborhoodId: '',
                                newCity: '',
                                newCityId: '',
                                newCTAColor: ''
                            });

                            jQuery('.areas-list-new-form').hide();
                            jQuery('.areas-list-add-form-btn').show();
                        }
                    },
                    __('Add Area', 'resideo')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className : 'areas-list-cancel-btn',
                        onClick: function(event) {
                            setState({
                                newImageSrc: '',
                                newImageId: '',
                                newNeighborhood: '',
                                newNeighborhoodId: '',
                                newCity: '',
                                newCityId: '',
                                newCTAColor: ''
                            });

                            jQuery('.areas-list-new-form').hide();
                            jQuery('.areas-list-add-form-btn').show();
                        }
                    },
                    __('Cancel', 'resideo')
                )
            )
        ];

        if(isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'areas-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'areas-placeholder-subheader'
                    },
                    subtitle
                ),
                areasOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'areas-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'areas-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'areas-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/areas', {
        title: __('Areas', 'resideo'),
        description: __('Resideo areas block.', 'resideo'),
        icon: {
            src: 'layout',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('areas', 'resideo'),
            __('neighborhoods', 'resideo'),
            __('locations', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22cta_label%22%3A%22%22%2C%22cta_link%22%3A%22%22%2C%22cta_color%22%3A%22%23333333%22%2C%22margin%22%3A%22no%22%2C%22layout%22%3A%221%22%2C%22areas%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newImageSrc: '',
            newImageId: '',
            newNeighborhood: '',
            newNeighborhoodId: '',
            newCity: '',
            newCityId: '',
            newCTAColor: '#333333'
        })(AreasControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);