(function ($) {
    "use strict";



    var initPhotoSwipeFromDOM = function (gallerySelector) {
        // parse slide data (url, title, size ...) from DOM elements 
        // (children of gallerySelector)
        var parseThumbnailElements = function (el) {
            var thumbElements = el.childNodes,
                numNodes = thumbElements.length,
                items = [],
                figureEl,
                linkEl,
                size,
                item;

            for (var i = 0; i < numNodes; i++) {
                figureEl = thumbElements[i]; // <figure> element

                // include only element nodes 
                if (figureEl.nodeType !== 1) {
                    continue;
                }

                linkEl = figureEl.children[0]; // <a> element

                size = linkEl.getAttribute('data-size').split('x');

                // create slide object
                // ADDED
                if ($(linkEl).data('type') == 'video') {
                    item = {
                        html: $(linkEl).data('video')
                    };
                } else {
                    item = {
                        src: linkEl.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10)
                    };
                }

                if (figureEl.children.length > 1) {
                    // <figcaption> content
                    item.title = $(figureEl).find('.caption').html();
                }

                if (linkEl.children.length > 0) {
                    // <img> thumbnail element, retrieving thumbnail url
                    item.msrc = linkEl.children[0].getAttribute('src');
                }

                item.el = figureEl; // save link to element for getThumbBoundsFn
                items.push(item);
            }

            return items;
        };

        // find nearest parent element
        var closest = function closest(el, fn) {
            return el && (fn(el) ? el : closest(el.parentNode, fn));
        };

        // triggers when user clicks on thumbnail
        var onThumbnailsClick = function (e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : e.returnValue = false;

            var eTarget = e.target || e.srcElement;

            // find root element of slide
            var clickedListItem = closest(eTarget, function (el) {
                return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
            });

            if (!clickedListItem) {
                return;
            }

            // find index of clicked item by looping through all child nodes
            // alternatively, you may define index via data- attribute
            var clickedGallery = clickedListItem.parentNode,
                childNodes = clickedListItem.parentNode.childNodes,
                numChildNodes = childNodes.length,
                nodeIndex = 0,
                index;

            for (var i = 0; i < numChildNodes; i++) {
                if (childNodes[i].nodeType !== 1) {
                    continue;
                }

                if (childNodes[i] === clickedListItem) {
                    index = nodeIndex;
                    break;
                }

                nodeIndex++;
            }

            if (index >= 0) {
                // open PhotoSwipe if valid index found
                openPhotoSwipe(index, clickedGallery);
            }

            return false;
        };

        // parse picture index and gallery index from URL (#&pid=1&gid=2)
        var photoswipeParseHash = function () {
            var hash = window.location.hash.substring(1),
                params = {};

            if (hash.length < 5) {
                return params;
            }

            var vars = hash.split('&');

            for (var i = 0; i < vars.length; i++) {
                if (!vars[i]) {
                    continue;
                }

                var pair = vars[i].split('=');

                if (pair.length < 2) {
                    continue;
                }

                params[pair[0]] = pair[1];
            }

            if (params.gid) {
                params.gid = parseInt(params.gid, 10);
            }

            return params;
        };

        var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
            var pswpElement = document.querySelectorAll('.pswp')[0],
                gallery,
                options,
                items;

            items = parseThumbnailElements(galleryElement);

            // define options (if needed)
            options = {
                // define gallery index (for URL)
                galleryUID: galleryElement.getAttribute('data-pswp-uid'),
            };

            // PhotoSwipe opened from URL
            if (fromURL) {
                if (options.galleryPIDs) {
                    // parse real index when custom PIDs are used 
                    // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                    for (var j = 0; j < items.length; j++) {
                        if (items[j].pid == index) {
                            options.index = j;
                            break;
                        }
                    }
                } else {
                    // in URL indexes start from 1
                    options.index = parseInt(index, 10) - 1;
                }
            } else {
                options.index = parseInt(index, 10);
            }

            // exit if index not found
            if (isNaN(options.index)) {
                return;
            }

            if (disableAnimation) {
                options.showAnimationDuration = 0;
            }

            // Pass data to PhotoSwipe and initialize it
            gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();

            // ADDED
            gallery.listen('beforeChange', function () {
                var currItem = $(gallery.currItem.container);
                $('.pswp__video').removeClass('active');
                var currItemIframe = currItem.find('.pswp__video').addClass('active');
                $('.pswp__video').each(function () {
                    if (!$(this).hasClass('active')) {
                        $(this).attr('src', $(this).attr('src'));
                    }
                });
            });

            gallery.listen('close', function () {
                $('.pswp__video').each(function () {
                    $(this).attr('src', $(this).attr('src'));
                });
            });
        };

        // loop through all gallery elements and bind events
        var galleryElements = document.querySelectorAll(gallerySelector);

        for (var i = 0, l = galleryElements.length; i < l; i++) {
            galleryElements[i].setAttribute('data-pswp-uid', i + 1);
            galleryElements[i].onclick = onThumbnailsClick;
        }

        // Parse URL and open gallery if it contains #&pid=3&gid=1
        var hashData = photoswipeParseHash();

        if (hashData.pid && hashData.gid) {
            openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
        }
    };

    initPhotoSwipeFromDOM('.pxp-single-property-gallery');
    //initPhotoSwipeFromDOM('.pxp-single-property-plans');
    initPhotoSwipeFromDOM('.pxp-single-property-gallery-d2-inner');
    initPhotoSwipeFromDOM('.pxp-single-property-gallery-d3-stage .owl-stage');
    initPhotoSwipeFromDOM('.pxp-single-property-gallery-d4-inner');
    initPhotoSwipeFromDOM('.pxp-single-property-gallery-d5-inner');
    initPhotoSwipeFromDOM('.pxp-single-property-gallery-d6-inner');
    initPhotoSwipeFromDOM('.pxp-single-property-gallery-d7-inner');

    $('.pxp-sp-gallery-btn').click(function () {
        $('.pxp-single-property-gallery figure:first-child').click();
    });

    $('#pxp-sp-top-btn-view-photos').click(function () {
        $('.pxp-single-property-gallery-d5 figure:first-child').click();
    });

    if ($('.pxp-single-property-gallery-d2-thumbs').length > 0) {
        $('.pxp-single-property-gallery-d2-thumbs').owlCarousel({
            'rtl': (gallery_vars.is_rtl == '1'),
            'nav': true,
            'dots': false,
            'margin': 10,
            'responsive': {
                0: {
                    'items': 3
                },
                600: {
                    'items': 4
                },
                1200: {
                    'items': 6
                }
            },
            'checkVisible': false,
            'smartSpeed': 600
        });
    }

    var thumbClicked = false;
    $('.pxp-single-property-gallery-d2-thumbs-item').on('click', function () {
        var elemIndex = $(this).parent().parent().index();

        $('.pxp-single-property-gallery-d2').carousel(elemIndex);
        $('.pxp-single-property-gallery-d2-thumbs-item').removeClass('pxp-active');
        $(this).addClass('pxp-active');

        thumbClicked = true;
    });

    $('.pxp-single-property-gallery-d2').on('slid.bs.carousel', function (carousel) {
        $('.pxp-single-property-gallery-d2-thumbs-item').removeClass('pxp-active');
        $('.pxp-single-property-gallery-d2-thumbs .owl-item').eq(carousel.to).find('.pxp-single-property-gallery-d2-thumbs-item').addClass('pxp-active');
        if (thumbClicked === false) {
            $('.pxp-single-property-gallery-d2-thumbs').trigger('to.owl.carousel', [carousel.to, 300]);
        } else {
            thumbClicked = false;
        }
    });

    if ($('.pxp-single-property-gallery-d3-stage').length > 0) {
        $('.pxp-single-property-gallery-d3-stage').owlCarousel({
            'rtl': (gallery_vars.is_rtl == '1'),
            'nav': true,
            'dots': true,
            'center': true,
            'loop': true,
            'margin': false,
            'responsive': {
                0: {
                    'items': 1,
                    'stagePadding': 80
                },
                600: {
                    'items': 1,
                    'stagePadding': 120
                },
                900: {
                    'items': 1,
                    'stagePadding': 220
                },
                1200: {
                    'items': 1,
                    'stagePadding': 320
                },
                1600: {
                    'items': 1,
                    'stagePadding': 420
                },
                1800: {
                    'items': 1,
                    'stagePadding': 520
                }
            },
            'navText': (gallery_vars.is_rtl == '1')
                ? [`<div class="pxp-single-property-gallery-d3-left-arrow pxp-animate">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828" class="pxp-arrow-1">
                                    <g id="Symbol_1_1" data-name="Symbol 1 - 1" transform="translate(-1847.5 -1589.086)">
                                        <line id="Line_2" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_3" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_4" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    </g>
                                </svg>
                            </div>`,
                    `<div class="pxp-single-property-gallery-d3-right-arrow pxp-animate">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                    <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                        <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    </g>
                                </svg>
                            </div>`]
                : [`<div class="pxp-single-property-gallery-d3-left-arrow pxp-animate">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828" class="pxp-arrow-1">
                                    <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                        <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    </g>
                                </svg>
                            </div>`,
                    `<div class="pxp-single-property-gallery-d3-right-arrow pxp-animate">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                    <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                        <line id="Line_2" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_3" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                        <line id="Line_4" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    </g>
                                </svg>
                            </div>`],
            'checkVisible': false,
            'smartSpeed': 600
        });
    }

    if ($('.pxp-single-property-gallery-d4-thumbs').length > 0) {
        $('.pxp-single-property-gallery-d4-thumbs').owlCarousel({
            'rtl': (gallery_vars.is_rtl == '1'),
            'nav': true,
            'dots': false,
            'margin': 10,
            'responsive': {
                0: {
                    'items': 4
                },
                600: {
                    'items': 6
                },
                700: {
                    'items': 8
                },
                800: {
                    'items': 10
                },
                992: {
                    'items': 6
                },
                1300: {
                    'items': 6
                },
                1400: {
                    'items': 8
                },
                1600: {
                    'items': 10
                }
            },
            'checkVisible': false,
            'smartSpeed': 600
        });
    }

    var thumbClickedD4 = false;
    $('.pxp-single-property-gallery-d4-thumbs-item').on('click', function () {
        var elemIndex = $(this).parent().parent().index();

        $('.pxp-single-property-gallery-d4').carousel(elemIndex);
        $('.pxp-single-property-gallery-d4-thumbs-item').removeClass('pxp-active');
        $(this).addClass('pxp-active');

        thumbClickedD4 = true;
    });

    $('.pxp-single-property-gallery-d4').on('slid.bs.carousel', function (carousel) {
        $('.pxp-single-property-gallery-d4-thumbs-item').removeClass('pxp-active');
        $('.pxp-single-property-gallery-d4-thumbs .owl-item').eq(carousel.to).find('.pxp-single-property-gallery-d4-thumbs-item').addClass('pxp-active');
        if (thumbClickedD4 === false) {
            $('.pxp-single-property-gallery-d4-thumbs').trigger('to.owl.carousel', [carousel.to, 300]);
        } else {
            thumbClickedD4 = false;
        }
    });

    if ($('.pxp-single-property-gallery-d6-thumbs').length > 0) {
        $('.pxp-single-property-gallery-d6-thumbs').owlCarousel({
            'nav': true,
            'dots': false,
            'margin': 10,
            'responsive': {
                0: {
                    'items': 3
                },
                600: {
                    'items': 4
                },
                800: {
                    'items': 6
                },
                1200: {
                    'items': 8
                },
                1400: {
                    'items': 8
                },
                1600: {
                    'items': 10
                }
            },
            'checkVisible': false,
            'smartSpeed': 600
        });
    }

    var thumbClickedD6 = false;
    $('.pxp-single-property-gallery-d6-thumbs-item').on('click', function () {
        var elemIndex = $(this).parent().parent().index();

        $('.pxp-single-property-gallery-d6').carousel(elemIndex);
        $('.pxp-single-property-gallery-d6-thumbs-item').removeClass('pxp-active');
        $(this).addClass('pxp-active');

        thumbClickedD6 = true;
    });

    $('.pxp-single-property-gallery-d6').on('slid.bs.carousel', function (carousel) {
        $('.pxp-single-property-gallery-d6-thumbs-item').removeClass('pxp-active');
        $('.pxp-single-property-gallery-d6-thumbs .owl-item').eq(carousel.to).find('.pxp-single-property-gallery-d6-thumbs-item').addClass('pxp-active');
        if (thumbClickedD6 === false) {
            $('.pxp-single-property-gallery-d6-thumbs').trigger('to.owl.carousel', [carousel.to, 300]);
        } else {
            thumbClickedD6 = false;
        }
    });
})(jQuery);