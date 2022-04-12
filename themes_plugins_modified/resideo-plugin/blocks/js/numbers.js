(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button        = wp.components.Button;

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

    function NumbersControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newSum       = props.newSum;
        var newSign      = props.newSign;
        var newDelay     = props.newDelay;
        var newIncrement = props.newIncrement;
        var newTitle     = props.newTitle;
        var newText      = props.newText;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var subtitle  = getObjectProperty(data_json, 'subtitle');
        var image     = getObjectProperty(data_json, 'image');
        var image_src = getObjectProperty(data_json, 'image_src');
        var margin    = getObjectProperty(data_json, 'margin');
        var numbers   = getObjectProperty(data_json, 'numbers');

        if (!jQuery.isArray(numbers)) {
            numbers = [];
        }

        var renderNumbersList = function() {
            var items = [];

            if (numbers.length > 0) {
                jQuery.each(numbers, function(index, elem) {
                    items.push(
                        el('li',
                            {},
                            el('div',
                                {
                                    className: 'row'
                                },
                                el('div',
                                    {
                                        className: 'col-xs-3'
                                    },
                                    el('div',
                                        {
                                            className: 'numbers-list-item-number'
                                        },
                                        elem.sum + elem.sign
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'col-xs-7'
                                    },
                                    el('div',
                                        {
                                            className: 'numbers-list-item-title'
                                        },
                                        elem.title
                                    ),
                                    el('div',
                                        {
                                            className: 'numbers-list-item-text'
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

                                                data_json.numbers.splice(elemIndex, 1);

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

        var numbersOptions = [
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
                ),
                el('div',
                    {
                        className: 'col-xs-12 col-sm-6'
                    },
                    el(MediaUpload,
                        {
                            onSelect: function(media) {
                                jQuery('.pxp-block-numbers-bg-image-btn')
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
                                        className: 'pxp-block-numbers-bg-image-btn',
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
                )
            ),
            el('h4', 
                {
                    className: 'numbers-list-header'
                },
                __('Numbers List', 'resideo')
            ),
            el('div',
                {
                    className: 'numbers-list-container'
                },
                el('ul',
                    {},
                    renderNumbersList()
                )
            ),
            el(Button,
                {
                    className: 'numbers-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.numbers-list-new-form').show();
                    }
                },
                __('Add New Number', 'resideo')
            ),
            el('div',
                {
                    className: 'numbers-list-new-form'
                },
                el('h5', 
                    {
                        className: 'numbers-list-new-header'
                    },
                    __('New Number', 'resideo')
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
                                className: 'numbers-list-new-sum',
                                label: __('Number', 'resideo'),
                                value: newSum,
                                placeholder: '1',
                                onChange: function(newSum) {
                                    setState({ newSum });
                                }
                            }
                        ),
                    ),
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-6'
                        },
                        el(TextControl, 
                            {
                                className: 'numbers-list-new-sign',
                                label: __('Number Sign', 'resideo'),
                                value: newSign,
                                placeholder: __('Enter number sign', 'resideo'),
                                onChange: function(newSign) {
                                    setState({ newSign });
                                }
                            }
                        ),
                    ),
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-6'
                        },
                        el(TextControl, 
                            {
                                className: 'numbers-list-new-delay',
                                label: __('Number Delay', 'resideo'),
                                value: newDelay,
                                placeholder: '1',
                                onChange: function(newDelay) {
                                    setState({ newDelay });
                                }
                            }
                        ),
                    ),
                    el('div',
                        {
                            className: 'col-xs-12 col-sm-6'
                        },
                        el(TextControl, 
                            {
                                className: 'numbers-list-new-increment',
                                label: __('Number Increment', 'resideo'),
                                value: newIncrement,
                                placeholder: '1',
                                onChange: function(newIncrement) {
                                    setState({ newIncrement });
                                }
                            }
                        ),
                    ),
                    el('div',
                        {
                            className: 'col-xs-12'
                        },
                        el(TextControl, 
                            {
                                className: 'numbers-list-new-title',
                                label: __('Number Title', 'resideo'),
                                value: newTitle,
                                placeholder: __('Enter number title', 'resideo'),
                                onChange: function(newTitle) {
                                    setState({ newTitle });
                                }
                            }
                        ),
                        el(TextControl, 
                            {
                                className: 'numbers-list-new-text',
                                label: __('Number Text', 'resideo'),
                                value: newText,
                                placeholder: __('Enter number text', 'resideo'),
                                onChange: function(newText) {
                                    setState({ newText });
                                }
                            }
                        )
                    )
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'numbers-list-new-ok',
                        onClick: function() {
                            numbers.push({
                                'sum'      : newSum,
                                'sign'     : newSign,
                                'delay'    : newDelay,
                                'increment': newIncrement,
                                'title'    : newTitle,
                                'text'     : newText
                            });

                            data_json.numbers = numbers;
                            setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });

                            setState({ 
                                newSum      : '1',
                                newSign     : '',
                                newDelay    : '1',
                                newIncrement: '1',
                                newTitle    : '',
                                newText     : ''
                            });

                            jQuery('.numbers-list-new-form').hide();
                            jQuery('.numbers-list-new-btn').show();
                        }
                    },
                    __('Add Number', 'resideo')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'numbers-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newSum      : '1',
                                newSign     : '',
                                newDelay    : '1',
                                newIncrement: '1',
                                newTitle    : '',
                                newText     : ''
                            });

                            jQuery('.numbers-list-new-form').hide();
                            jQuery('.numbers-list-new-btn').show();
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
                el('h3', 
                    {
                        className: 'numbers-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'numbers-placeholder-subheader'
                    },
                    subtitle
                ),
                numbersOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'numbers-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'numbers-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'numbers-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/numbers', {
        title: __('Numbers', 'resideo'),
        description: __('Numbers numbers block.', 'resideo'),
        icon: {
            src: 'editor-ol',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('numbers', 'resideo'),
            __('counters', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22image%22%3A%22%22%2C%22image_src%22%3A%22%22%2C%22margin%22%3A%22no%22%2C%22numbers%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newSum      : '1',
            newSign     : '',
            newDelay    : '1',
            newIncrement: '1',
            newTitle    : '',
            newText     : ''
        })(NumbersControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);