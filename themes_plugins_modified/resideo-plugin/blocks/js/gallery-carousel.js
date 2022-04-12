(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var Button = wp.components.Button;

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

    function GalleryCarouselControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newImageSrc = props.newImageSrc;
        var newImageId  = props.newImageId;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var photos = getObjectProperty(data_json, 'photos');

        if (!jQuery.isArray(photos)) {
            photos = [];
        }

        var onNewImageSelect = function(media) {
            setState({
                newImageSrc: media.url,
                newImageId: media.id
            });
        };

        var renderGalleryCarouselList = function() {
            var items = [];

            if (photos.length > 0) {
                jQuery.each(photos, function(index, elem) {
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
                                            className: 'gallery-carousel-list-item-img',
                                            style: {
                                                'background-image': 'url(' + elem.src + ')'
                                            },
                                        }
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'col-xs-8'
                                    }
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

                                                data_json.photos.splice(elemIndex, 1);

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

        var galleryCarouselOptions = [
            el('h4', 
                {
                    className: 'gallery-carousel-list-header'
                },
                __('Gallery Carousel Photos', 'resideo')
            ),
            el('div',
                {
                    className: 'gallery-carousel-list-container'
                },
                el('ul',
                    {},
                    renderGalleryCarouselList()
                )
            ),
            el(Button,
                {
                    className: 'gallery-carousel-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.gallery-carousel-list-new-form').show();
                    }
                },
                __('Add New Photo', 'resideo')
            ),
            el('div',
                {
                    className: 'gallery-carousel-list-new-form'
                },
                el('h5', 
                    {
                        className: 'gallery-carousel-list-new-header'
                    },
                    __('New Photo', 'resideo')
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
                                className: 'gallery-carousel-list-new-image-placeholder',
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
                                                className: 'gallery-carousel-list-new-image-btn',
                                                onClick: obj.open
                                            },
                                            __('Add Photo', 'resideo')
                                        );
                                    }
                                }
                            )
                        )
                    )
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'gallery-carousel-list-new-ok',
                        onClick: function() {
                            photos.push({
                                'src': newImageSrc,
                                'value': newImageId
                            });

                            data_json.photos = photos;
                            setAttributes({ data_content: encodeURIComponent(JSON.stringify(data_json)) });

                            setState({ 
                                newImageSrc: '',
                                newImageId: ''
                            });

                            jQuery('.gallery-carousel-list-new-form').hide();
                            jQuery('.gallery-carousel-list-new-btn').show();
                        }
                    },
                    __('OK', 'resideo')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'gallery-carousel-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newImageSrc: '',
                                newImageId: ''
                            });

                            jQuery('.gallery-carousel-list-new-form').hide();
                            jQuery('.gallery-carousel-list-new-btn').show();
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
                galleryCarouselOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('div', 
                    {
                        className: 'gallery-carousel-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('resideo-plugin/gallery-carousel', {
        title: __('Gallery Carousel', 'resideo'),
        description: __('Resideo gallery carousel block.', 'resideo'),
        icon: {
            src: 'images-alt2',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('gallery', 'resideo'), 
            __('photos', 'resideo'),
            __('carousel', 'resideo'),
            __('slider', 'resideo')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22photos%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newImageSrc: '', 
            newImageId: '',
        })(GalleryCarouselControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);