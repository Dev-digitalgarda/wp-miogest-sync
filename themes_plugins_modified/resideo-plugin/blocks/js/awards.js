(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl     = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var SelectControl   = wp.components.SelectControl;
    var Button          = wp.components.Button;

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

    function AwardsControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newImageSrc = props.newImageSrc;
        var newImageId  = props.newImageId;
        var newTitle    = props.newTitle;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title    = getObjectProperty(data_json, 'title');
        var subtitle = getObjectProperty(data_json, 'subtitle');
        var margin   = getObjectProperty(data_json, 'margin');
        var text     = getObjectProperty(data_json, 'text');
        var awards   = getObjectProperty(data_json, 'awards');

        if (!jQuery.isArray(awards)) {
            awards = [];
        }

        var onNewImageSelect = function(media) {
            setState({
                newImageSrc: media.url,
                newImageId: media.id
            });
        };

        var renderAwardsListItemImg = function(item) {
            return el('div',
                {
                    className: 'awards-list-item-img',
                    style: {
                        'background-image': 'url(' + item.src + ')'
                    },
                }
            );
        };

        var renderAwardsList = function() {
            var items = [];

            if (awards.length > 0) {
                jQuery.each(awards, function(index, elem) {
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
                                    renderAwardsListItemImg(elem)
                                ),
                                el('div',
                                    {
                                        className: 'col-xs-8'
                                    },
                                    el('div',
                                        {
                                            className: 'awards-list-item-title'
                                        },
                                        elem.title
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

                                                data_json.awards.splice(elemIndex, 1);

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

        var awardsOptions = [
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
            el(TextareaControl, 
                {
                    label: __('Text', 'resideo'),
                    value: text,
                    placeholder: __('Enter text', 'resideo'),
                    onChange: function(value) {
                        data_json.text = value;
                        setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });
                    }
                }
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
            el('h4', 
                {
                    className: 'awards-list-header'
                },
                __('Awards List', 'resideo')
            ),
            el('div',
                {
                    className: 'awards-list-container'
                },
                el('ul',
                    {},
                    renderAwardsList()
                )
            ),
            el(Button,
                {
                    className: 'awards-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.awards-list-new-form').show();
                    }
                },
                __('Add New Award', 'resideo')
            ),
            el('div',
                {
                    className: 'awards-list-new-form'
                },
                el('h5', 
                    {
                        className: 'awards-list-new-header'
                    },
                    __('New Award', 'resideo')
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
                                className: 'awards-list-new-image-placeholder',
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
                                                className: 'awards-list-new-image-btn',
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
                                className: 'awards-list-new-title',
                                label: __('Award Title', 'resideo'),
                                value: newTitle,
                                placeholder: __('Enter award title', 'resideo'),
                                onChange: function(newTitle) {
                                    setState({ newTitle });
                                }
                            }
                        )
                    )
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'awards-list-new-ok',
                        onClick: function() {
                            awards.push({
                                'src'  : newImageSrc,
                                'value': newImageId,
                                'title': newTitle
                            });

                            data_json.awards = awards;
                            setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });

                            setState({ 
                                newImageSrc: '',
                                newImageId : '',
                                newTitle   : ''
                            });

                            jQuery('.awards-list-new-form').hide();
                            jQuery('.awards-list-new-btn').show();
                        }
                    },
                    __('Add Award', 'resideo')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'awards-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newImageSrc: '',
                                newImageId : '',
                                newTitle   : ''
                            });

                            jQuery('.awards-list-new-form').hide();
                            jQuery('.awards-list-new-btn').show();
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
                        className: 'awards-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'awards-placeholder-subheader'
                    },
                    subtitle
                ),
                awardsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'awards-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'awards-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'awards-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/awards', {
        title: __('Awards', 'resideo'),
        description: __('Resideo awards block.', 'resideo'),
        icon: {
            src: 'awards',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('awards', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22margin%22%3A%22no%22%2C%22text%22%3A%22%22%2C%22awards%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newImageSrc: '', 
            newImageId : '',
            newTitle   : ''
        })(AwardsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);