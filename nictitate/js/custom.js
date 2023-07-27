/* =========================================================
 Comment Form
 ============================================================ */
jQuery(document).ready(function() {

    if (jQuery("#contact-form").length > 0) {
        // get front validate localization
        var validateLocalization = kopa_custom_front_localization.validate;

        jQuery('#contact-form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: validateLocalization.name.required,
                    minlength: jQuery.format(validateLocalization.name.minlength)
                },
                email: {
                    required: validateLocalization.email.required,
                    email: validateLocalization.email.email
                },
                url: {
                    required: validateLocalization.url.required,
                    url: validateLocalization.url.url
                },
                message: {
                    required: validateLocalization.message.required,
                    minlength: jQuery.format(validateLocalization.message.minlength)
                }
            },
            submitHandler: function(form) {
                jQuery("#submit-contact").attr("value", validateLocalization.form.sending);
                jQuery(form).ajaxSubmit({
                    success: function(responseText, statusText, xhr, $form) {
                        jQuery("#response").html(responseText).hide().slideDown("fast");
                        jQuery("#submit-contact").attr("value", validateLocalization.form.submit);
                        jQuery(form).find('[name=name]').val('');
                        jQuery(form).find('[name=email]').val('');
                        jQuery(form).find('[name=url]').val('');
                        jQuery(form).find('[name=message]').val('');
                    }
                });
                return false;
            }
        });
        
         
    }
if (jQuery(".comment-form").length > 0) {
        // get front validate localization
        var validateLocalization = kopa_custom_front_localization.validate;
    jQuery('.comment-form').validate({
            rules: {
                author: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                comment: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                author: {
                    required: validateLocalization.name.required,
                    minlength: jQuery.format(validateLocalization.name.minlength)
                },
                email: {
                    required: validateLocalization.email.required,
                    email: validateLocalization.email.email
                },
                url: {
                    required: validateLocalization.url.required,
                    url: validateLocalization.url.url
                },
                comment: {
                    required: validateLocalization.message.required,
                    minlength: jQuery.format(validateLocalization.message.minlength)
                }
            }
            
        });
    }
});

/* =========================================================
 Search box
 ============================================================ */
jQuery(document).ready(function() {
    jQuery(".search-icon").click(function() {
        jQuery(this).toggleClass("close-icon");
        jQuery("#header-top .search-box").toggle(300);
        jQuery("#header-top .search-icon").toggleClass("search-active");
    });

});

/* =========================================================
 Sticky
 ============================================================ */
jQuery(document).ready(function() {
    jQuery(function() {
        jQuery(window).scroll(function() {
            if (jQuery(this).scrollTop() > 41) {
                jQuery('#header-bottom.cbp-af-header').addClass('sticky-header');
            } else {
                jQuery('#header-bottom.cbp-af-header').removeClass('sticky-header');
            }
        });
    });

});

/* =========================================================
 Show background when hover menu
 ============================================================ */

(function($) {

    "use strict";

    jQuery(document).ready(function() {


        /* Navigation Hover
         ================================================== */
        jQuery('#main-nav').bind('mouseenter', showBackGround).bind('mouseleave', hideBackGround);

        var background = 'inactive',
                hoverstate = 'inactive';

        function showBackGround() {

            hoverstate = 'active';

            if (jQuery(window).scrollTop() > 41) {
                background = 'active';
                jQuery(".kopa-background").height(jQuery(document).height()).stop(true, true).fadeIn();
            }
        }

        function hideBackGround() {

            hoverstate = 'inactive';
            jQuery(".kopa-background").stop().fadeOut();

        }

        jQuery(window).scroll(function() {

            if (jQuery(this).scrollTop() > 0 && background === 'inactive' && hoverstate === 'active') {

                showBackGround();
                background = 'active';

            } else {

                background = 'inactive';
                hoverstate = 'inactive';
            }

        });
    });

})(jQuery);


/* =========================================================
 Sub menu
 ==========================================================*/
(function($) { //create closure so we can safely use $ as alias for jQuery

    jQuery(document).ready(function() {

        // initialise plugin
        jQuery('#main-menu').superfish({
            delay: 0,
            speed: 'fast',
            speedOut: 'fast',
            disableHI: true
        });
    });

})(jQuery);

/* =========================================================
 Mobile menu
 ============================================================ */
jQuery(document).ready(function() {

    jQuery('#mobile-menu > span').click(function() {

        var mobile_menu = jQuery('#toggle-view-menu');

        if (mobile_menu.is(':hidden')) {
            mobile_menu.slideDown('300');
            jQuery(this).children('span').html('-');
        } else {
            mobile_menu.slideUp('300');
            jQuery(this).children('span').html('+');
        }



    });

    jQuery('#toggle-view-menu li').click(function() {

        var text = jQuery(this).children('div.menu-panel');

        if (text.is(':hidden')) {
            text.slideDown('300');
            jQuery(this).children('span').html('-');
        } else {
            text.slideUp('300');
            jQuery(this).children('span').html('+');
        }

        jQuery(this).toggleClass('active');

    });

});

/* =========================================================
 Carousel
 ============================================================ */
jQuery(window).load(function() {

    jQuery('.kopa-latest-work-carousel').each(function() {
        var $this = jQuery(this),
                prevID = $this.data('prev-id'),
                nextID = $this.data('next-id'),
                maxItems = $this.data('max-items'),
                scrollItems = $this.data('scroll-items');

        $this.carouFredSel({
            responsive: true,
            prev: prevID,
            next: nextID,
            width: '100%',
            scroll: scrollItems,
            auto: false,
            items: {
                width: 252,
                height: 'auto',
                visible: {
                    min: 1,
                    max: maxItems
                }
            }
        });
    });

    jQuery('.kopa-testimonial-carousel').each(function() {
        var $this = jQuery(this),
                prevID = $this.data('prev-id'),
                nextID = $this.data('next-id');

        $this.carouFredSel({
            responsive: true,
            prev: prevID,
            next: nextID,
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 530,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 2
                }
            }
        });
    });

    jQuery('.kopa-featured-product-carousel').each(function() {
        var $this = jQuery(this),
                prevID = $this.data('prev-id'),
                nextID = $this.data('next-id'),
                maxItems = $this.data('max-items'),
                scrollItems = $this.data('scroll-items');

        $this.carouFredSel({
            responsive: true,
            prev: prevID,
            next: nextID,
            width: '100%',
            scroll: scrollItems,
            auto: false,
            items: {
                width: 252,
                height: 'auto',
                visible: {
                    min: 1,
                    max: maxItems
                }
            }
        });
    });

    if (jQuery(".kopa-related-post-carousel").length > 0) {
        jQuery('.kopa-related-post-carousel').carouFredSel({
            responsive: true,
            prev: '#prev-4',
            next: '#next-4',
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 390,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 2
                }
            }
        });
    }

    if (jQuery(".kopa-related-products").length > 0) {
        jQuery('.kopa-related-products').carouFredSel({
            responsive: true,
            prev: '#prev-3',
            next: '#next-3',
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 252,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 3
                }
            }
        });
    }

});

/* =========================================================
 Set auto margin for client logo
 ============================================================ */
jQuery(document).ready(function() {

    var imgs = jQuery('div.auto-margin img');
    var img_height;
    var div_height;

    jQuery.each(imgs, function(index, value) {
        img_height = parseInt(jQuery(this).height());

        div_height = parseInt(jQuery(this).parent().parent().height());

        number = div_height - img_height;
        number = parseInt(number / 2);

        jQuery(this).css('margin-top', number);

    });

});

/* =========================================================
 BlogPost slider
 ============================================================ */
jQuery(window).load(function() {
    jQuery('.blogpost-slider').flexslider({
        animation: "slide",
        start: function(slider) {
            jQuery('body').removeClass('loading');
        }
    });
});

/* =========================================================
 About page slider
 ============================================================ */
jQuery(window).load(function() {
    jQuery('.about-slider').flexslider({
        animation: "slide",
        start: function(slider) {
            jQuery('body').removeClass('loading');
        }
    });
});

/* =========================================================
 Testimonials slider
 ============================================================ */
jQuery(window).load(function() {
    jQuery('.kopa-testimonial-slider').flexslider({
        animation: "slide",
        start: function(slider) {
            jQuery('body').removeClass('loading');
        }
    });
});

/* =========================================================
 Single post slider
 ============================================================ */
jQuery(window).load(function() {

    jQuery('.kp-single-carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 194,
        itemMargin: 5,
        asNavFor: '.kp-single-slider'
    });

    jQuery('.kp-single-slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: ".kp-single-carousel",
        start: function(slider) {
            jQuery('body').removeClass('loading');
        }
    });
});

/* =========================================================
 prettyPhoto
 ============================================================ */
jQuery(document).ready(function() {
    init_image_effect();
});

jQuery(window).resize(function() {
    init_image_effect();
});

function init_image_effect() {

    var view_p_w = jQuery(window).width();
    var pp_w = 500;
    var pp_h = 344;

    if (view_p_w <= 479) {
        pp_w = '120%';
        pp_h = '100%';
    }
    else if (view_p_w >= 480 && view_p_w <= 599) {
        pp_w = '100%';
        pp_h = '170%';
    }

    jQuery("a[rel^='prettyPhoto']").prettyPhoto({
        show_title: false,
        deeplinking: false,
        social_tools: false,
        default_width: pp_w,
        default_height: pp_h
    });
}

/* =========================================================
 Flickr Feed
 ============================================================ */
jQuery(document).ready(function() {
    jQuery('.flickr-wrap').each(function() {
        var $this = jQuery(this),
                flickrID = $this.data('flickr-id'),
                limit = $this.data('limit');

        $this.jflickrfeed({
            limit: limit, // user option
            qstrings: {
                id: flickrID // user option
            },
            itemTemplate:
                    '<li class="flickr-badge-image">' +
                    '<a rel="prettyPhoto[kopa-flickr]" href="{{image}}" title="{{title}}">' +
                    '<img src="{{image_s}}" alt="{{title}}" width="73px" height="73px" />' +
                    '</a>' +
                    '</li>'
        }, function(data) {
            jQuery("a[rel^='prettyPhoto']").prettyPhoto({
                show_title: false,
                deeplinking: false
            }).mouseenter(function() {
                //jQuery(this).find('img').fadeTo(500, 0.6);
            }).mouseleave(function() {
                //jQuery(this).find('img').fadeTo(400, 1);
            });
        });
    });
});

/* =========================================================
 Tabs
 ============================================================ */
jQuery(document).ready(function() {

    if (jQuery(".tab-content-1").length > 0) {
        //Default Action Product Tab
        jQuery(".tab-content-1").hide(); //Hide all content
        jQuery("ul.tabs-1 li:first").addClass("active").show(); //Activate first tab
        jQuery(".tab-content-1:first").show(); //Show first tab content
        //On Click Event Product Tab
        jQuery("ul.tabs-1 li").click(function() {
            jQuery("ul.tabs-1 li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery(".tab-content-1").hide(); //Hide all tab content
            var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            jQuery(activeTab).fadeIn(); //Fade in the active content
            return false;

        });
    }

    if (jQuery(".tab-content-2").length > 0) {
        //Default Action Product Tab
        jQuery(".tab-content-2").hide(); //Hide all content
        jQuery("ul.tabs-2 li:first").next().addClass("active").show(); //Activate first tab
        jQuery(".tab-content-2:first").next().show(); //Show first tab content
        //On Click Event Product Tab
        jQuery("ul.tabs-2 li").click(function() {
            jQuery("ul.tabs-2 li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery(".tab-content-2").hide(); //Hide all tab content
            var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            jQuery(activeTab).fadeIn(); //Fade in the active content
            return false;

        });
    }
});

/* =========================================================
 Progress bar jQuery plugin
 ==========================================================*/
jQuery(function() {
    jQuery(".progress-bar > span").each(function() {
        jQuery(this)
                .data("origWidth", jQuery(this).width())
                .width(0)
                .animate({
                    width: jQuery(this).data("origWidth")
                }, 1200);
    });
});

/* =========================================================
 Accordion
 ========================================================= */
jQuery(document).ready(function() {
    var acc_wrapper = jQuery('.acc-wrapper');
    if (acc_wrapper.length > 0)
    {

        jQuery('.acc-wrapper .accordion-container').hide();
        jQuery.each(acc_wrapper, function(index, item) {
            jQuery(this).find(jQuery('.accordion-title')).first().addClass('active').next().show();

        });

        jQuery('.accordion-title').on('click', function(e) {
            kopa_accordion_click(jQuery(this));
            e.preventDefault();
        });

        var titles = jQuery('.accordion-title');

        jQuery.each(titles, function() {
            kopa_accordion_click(jQuery(this));
        });
    }

});

function kopa_accordion_click(obj) {
    if (obj.next().is(':hidden')) {
        obj.parent().find(jQuery('.active')).removeClass('active').next().slideUp(300);
        obj.toggleClass('active').next().slideDown(300);

    }
    jQuery('.accordion-title span').html('+');
    if (obj.hasClass('active')) {
        obj.find('span').first().html('-');
    }
}

/* =========================================================
 Toggle Boxes
 ============================================================ */
jQuery(document).ready(function() {

    jQuery('#toggle-view li').click(function(event) {

        var text = jQuery(this).children('div.panel');

        if (text.is(':hidden')) {
            jQuery(this).addClass('active');
            text.slideDown('300');
            jQuery(this).children('span').html('-');
        } else {
            jQuery(this).removeClass('active');
            text.slideUp('300');
            jQuery(this).children('span').html('+');
        }

    });

});

/* =========================================================
 Direction-aware hover effect
 ============================================================ */
jQuery(function() {

    jQuery(' .da-thumbs > li ').each(function() {
        jQuery(this).hoverdir();
    });

});


/* =========================================================
 Scroll to top
 ============================================================ */
jQuery(document).ready(function() {

    // hide #back-top first
    jQuery("#back-top").hide();

    // fade in #back-top
    jQuery(function() {
        jQuery(window).scroll(function() {
            if (jQuery(this).scrollTop() > 200) {
                jQuery('#back-top').fadeIn();
            } else {
                jQuery('#back-top').fadeOut();
            }
        });

        // scroll body to 0px on click
        jQuery('#back-top a').click(function() {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });

});

/* =========================================================
 Sequence Slider
 ============================================================ */
jQuery(document).ready(function() {
    if (jQuery('#sequence').length > 0) {
        var options = {
            nextButton: true,
            prevButton: true,
            animateStartingFrameIn: true,
            autoPlayDelay: 3000,
            preloader: true,
            pauseOnHover: true,
            preloadTheseFrames: [1]
        };

        var sequence = jQuery("#sequence").sequence(options).data("sequence");

        sequence.afterLoaded = function() {
            jQuery("#nav").fadeIn(100);
            jQuery("#nav li:nth-child(" + (sequence.settings.startingFrameID) + ") img").addClass("active");
        };

        sequence.beforeNextFrameAnimatesIn = function() {
            jQuery("#nav li:not(:nth-child(" + (sequence.nextFrameID) + ")) img").removeClass("active");
            jQuery("#nav li:nth-child(" + (sequence.nextFrameID) + ") img").addClass("active");
        };

        jQuery("#nav li").click(function() {
            jQuery(this).children("img").removeClass("active").children("img").addClass("active");
            sequence.nextFrameID = jQuery(this).index() + 1;
            sequence.goTo(sequence.nextFrameID);
        });
    }
});