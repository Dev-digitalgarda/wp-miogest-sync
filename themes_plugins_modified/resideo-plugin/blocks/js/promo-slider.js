(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl     = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var SelectControl   = wp.components.SelectControl;
    var Button          = wp.components.Button;
    var ColorPalette    = wp.components.ColorPalette;

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

    function PromoSliderControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newImageSrc = props.newImageSrc;
        var newImageId  = props.newImageId;
        var newTitle    = props.newTitle;
        var newText     = props.newText;
        var newCTAText  = props.newCTAText;
        var newCTALink  = props.newCTALink;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var position   = getObjectProperty(data_json, 'position');
        var margin     = getObjectProperty(data_json, 'margin');
        var ctas_color = getObjectProperty(data_json, 'ctas_color');
        var interval   = getObjectProperty(data_json, 'interval');
        var slides     = getObjectProperty(data_json, 'slides');

        if (!jQuery.isArray(slides)) {
            slides = [];
        }

        var renderCTAsColorSelector = el('div',
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
                                __('CTA Buttons Color', 'resideo'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: ctas_color,
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
                                data_json.ctas_color = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            )
        );

        var onNewImageSelect = function(media) {
            setState({
                newImageSrc: media.url,
                newImageId: media.id
            });
        };

        var renderPromoSliderListItemImg = function(item) {
            return el('div',
                {
                    className: 'promo-slider-list-item-img',
                    style: {
                        'background-image': 'url(' + item.src + ')'
                    },
                }
            );
        };

        var renderPromoSliderList = function() {
            var items = [];

            if (slides.length > 0) {
                jQuery.each(slides, function(index, elem) {
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
                                    renderPromoSliderListItemImg(elem)
                                ),
                                el('div',
                                    {
                                        className: 'col-xs-8'
                                    },
                                    el('div',
                                        {
                                            className: 'promo-slider-list-item-title'
                                        },
                                        elem.title
                                    ),
                                    el('div',
                                        {
                                            className: 'promo-slider-list-item-text'
                                        },
                                        elem.text
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

                                                data_json.slides.splice(elemIndex, 1);

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

        var promoSliderOptions = [
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
                            label: __('Caption Position', 'resideo'),
                            value: position,
                            options: [
                                { label: __('Top Left', 'resideo'), value: 'topLeft' },
                                { label: __('Top Right', 'resideo'), value: 'topRight' },
                                { label: __('Center Left', 'resideo'), value: 'centerLeft' },
                                { label: __('Center', 'resideo'), value: 'center' },
                                { label: __('Center Right', 'resideo'), value: 'centerRight' },
                                { label: __('Bottom Left', 'resideo'), value: 'bottomLeft' },
                                { label: __('Bottom Right', 'resideo'), value: 'bottomRight' }
                            ],
                            onChange: function(value) {
                                data_json.position = value;
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
                    el(TextControl, 
                        {
                            label: __('Autoslide Interval (seconds)', 'resideo'),
                            value: interval,
                            placeholder: '0',
                            onChange: function(value) {
                                data_json.interval = value;
                                setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                            }
                        }
                    )
                )
            ),
            renderCTAsColorSelector,
            el('h4', 
                {
                    className: 'promo-slider-list-header'
                },
                __('Slides', 'resideo')
            ),
            el('div',
                {
                    className: 'promo-slider-list-container'
                },
                el('ul',
                    {},
                    renderPromoSliderList()
                )
            ),
            el(Button,
                {
                    className: 'promo-slider-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.promo-slider-list-new-form').show();
                    }
                },
                __('Add New Slide', 'resideo')
            ),
            el('div',
                {
                    className: 'promo-slider-list-new-form'
                },
                el('h5', 
                    {
                        className: 'promo-slider-list-new-header'
                    },
                    __('New Slide', 'resideo')
                ),
                el('div',
                    {
                        className: 'row'
                    },
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-4'
                        },
                        el('div',
                            {
                                className: 'promo-slider-list-new-image-placeholder',
                                style: {
                                    backgroundImage: newImageSrc != '' ? 'url(' + newImageSrc + ')' : 'none',
                                },
                            },
                            el(MediaUpload,
                                {
                                    onSelect: (media) => {
                                        onNewImageSelect(media);
                                    },
                                    type: 'image',
                                    render: function(obj) {
                                        return el(Button,
                                            {
                                                isSecondary: true,
                                                isSmall: true,
                                                className: 'promo-slider-list-new-image-btn',
                                                onClick: obj.open
                                            },
                                            __('Add Image', 'resideo')
                                        );
                                    }
                                }
                            )
                        )
                    ),
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-8'
                        },
                        el(TextControl, 
                            {
                                label: __('Slide Title', 'resideo'),
                                value: newTitle,
                                placeholder: __('Enter slide title', 'resideo'),
                                onChange: function(newTitle) {
                                    setState({ newTitle });
                                }
                            }
                        ),
                        el(TextareaControl, 
                            {
                                label: __('Slide Text', 'resideo'),
                                value: newText,
                                placeholder: __('Enter slide text', 'resideo'),
                                onChange: function(newText) {
                                    setState({ newText });
                                }
                            }
                        ),
                        el(TextControl, 
                            {
                                label: __('CTA Button Text', 'resideo'),
                                value: newCTAText,
                                placeholder: __('Enter the CTA button text', 'resideo'),
                                onChange: function(newCTAText) {
                                    setState({ newCTAText });
                                }
                            }
                        ),
                        el(TextControl, 
                            {
                                label: __('CTA Button Link', 'resideo'),
                                value: newCTALink,
                                placeholder: __('Enter the CTA button link', 'resideo'),
                                onChange: function(newCTALink) {
                                    setState({ newCTALink });
                                }
                            }
                        )
                    )
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'promo-slider-list-new-ok',
                        onClick: function() {
                            slides.push({
                                'src'     : newImageSrc,
                                'value'   : newImageId,
                                'title'   : newTitle,
                                'text'    : newText,
                                'cta_text': newCTAText,
                                'cta_link': newCTALink
                            });

                            data_json.slides = slides;
                            setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });

                            setState({ 
                                newImageSrc: '',
                                newImageId : '',
                                newTitle   : '',
                                newText    : '',
                                newCTAText : '',
                                newCTALink : ''
                            });

                            jQuery('.promo-slider-list-new-form').hide();
                            jQuery('.promo-slider-list-new-btn').show();
                        }
                    },
                    __('Add Slide', 'resideo')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'promo-slider-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newImageSrc: '',
                                newImageId : '',
                                newTitle   : '',
                                newText    : '',
                                newCTAText : '',
                                newCTALink : ''
                            });

                            jQuery('.promo-slider-list-new-form').hide();
                            jQuery('.promo-slider-list-new-btn').show();
                        }
                    },
                    __('Cancel', 'resideo')
                )
            )
        ];

        if (isSelected) {
            return el('div', 
                {
                    className: className
                },
                promoSliderOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('div', 
                    {
                        className: 'promo-slider-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/promo-slider', {
        title: __('Promo Slider', 'resideo'),
        description: __('Resideo promo slider block.', 'resideo'),
        icon: {
            src: 'format-gallery',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('promo', 'resideo'),
            __('banner', 'resideo'),
            __('ad', 'resideo'),
            __('slider', 'resideo'),
            __('info', 'resideo'),
            __('carousel', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22position%22%3A%22topLeft%22%2C%22margin%22%3A%22no%22%2C%22ctas_color%22%3A%22%23333333%22%2C%22interval%22%3A%220%22%2C%22slides%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newImageSrc: '',
            newImageId : '',
            newTitle   : '',
            newText    : '',
            newCTAText : '',
            newCTALink : ''
        })(PromoSliderControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);