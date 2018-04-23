/*
 * @package Shakenup
 * @copyright (C) 2017 by Joomlastars - All rights reserved!
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author Joomlastars <author@joomlastars.co.in>
 * @authorurl <http://themeforest.net/user/joomlastars>
 */

(function($) {

  "use strict";

  var headerTimeout;
  var testimonialsTimeout;
  var animateFirstPart;
  var menuVisible;
  var portfolioRow;
  var portfolioMaxRow;
  var parallaxNumbersBegin;
  var headerNumberOfItems;
  var testimonialsNumberOfItems;
  var doResponsiveOnce;
  /* ==============================================
  Page Loader
  =============================================== */

  'use strict';

  $(window).load(function() {
    $(".loader-item").delay(700).fadeOut();
    $("#pageloader").delay(1200).fadeOut("slow");
  });



  $(window).load(function() {

    themeInitialize();

    var bodyHtml = $('body, html');

    //Clear sessionStorage on hard refresh
    $(document).on('keydown', function(e) {
      if (e.which == 116 && e.ctrlKey) {
        e.preventDefault();
        window.sessionStorage['animatePage'] = 0;
        location.reload(true);
      }
    });

    //Menu behavior
    $(document).on('mouseenter', '#header-menu li a, #navigation-menu li a', function() {
      if (window.innerWidth > 992)
        $(this).parent().find('ul').first().stop(true, true).fadeIn(200);
    });

    $(document).on('mouseleave', '#header-menu li, #navigation-menu li', function() {
      if (window.innerWidth > 992)
        $(this).find('ul').first().stop(true, true).fadeOut(200);
    });

    $(document).on('click', '#header-menu > li > a, #navigation-menu > li > a', function(e) {
      var stringValue = $(this).attr('href');
      if (stringValue.indexOf("#") != -1) {

        bodyHtml.animate({
          scrollTop: $(stringValue).offset().top - $('#navigation-wrapper').height() - 20
        }, 1500);
      }


    });

    $(document).on('click', '.mobile-menu-icon', function() {
      var $this = $(this);

      if ($this.parent().children('ul').css('display') == 'none')
        $this.parent().children('ul').fadeIn(400);
      else
        $this.parent().children('ul').fadeOut(400);
    });

    $(document).on('click', '.navigation-mobile-arrow', function() {
      var $this = $(this);
      var elemHeight = $this.parent().children('ul').children().length * $this.parent().height();

      if ($this.parent().children('ul').height() == 0) {
        $this.parent().children('ul').animate({
          height: elemHeight
        }, 200);
        $this.removeClass('fa-angle-down').addClass('fa-angle-up');
      } else {
        $this.parent().children('ul').animate({
          height: 0
        }, 200);
        $this.removeClass('fa-angle-up').addClass('fa-angle-down');
      }
    });

    //Navigation Dropdown
    if (screen.width <= 768) {
      $('#header-menu li a, #navigation-menu li a').click(function() {
        $("#header-menu, #navigation-menu").hide("hide");
      });
    }
    //Header slider arrows behavior
    $(document).on('click', '.header-slidernav-arrow', function() {
      var headerSlideCurrent = $('#header-slidernav-current');

      if ($(this).hasClass('fa-angle-right'))
        var nextSlide = parseInt(headerSlideCurrent.html()) % headerNumberOfItems;
      else
        var nextSlide = (parseInt(headerSlideCurrent.html()) - 2 + headerNumberOfItems) % headerNumberOfItems;

      clearTimeout(headerTimeout);
      headerAnimate(nextSlide, headerNumberOfItems);
    });

    //Header links behavior
    $(document).on('click', '.header-link', function(e) {
      e.preventDefault();
      bodyHtml.animate({
        scrollTop: $($(this).attr('href')).offset().top - 60
      }, 1500);
    });

    //Scroll down button behavior
    $(document).on('click', '#header-getstarted', function() {
      bodyHtml.animate({
        scrollTop: $('#content-wrapper').offset().top
      }, 1500);
    });

    //Parallax behavior
    $(window).on('scroll', function() {
      var direction = (/trident/.test(navigator.userAgent.toLowerCase())) ? -1 : 1;

      var headerImage = $('.header-image');
      var parallaxImage = $('#parallax-numbers-image');
      var testimonialsImage = $('#testimonials-image');
      var showcaseImage = $('#showcase-wrapper');
      var navigationWrapper = $('#navigation-wrapper');

      if (window.innerWidth > 992) {
        var scrollPos = $(window).scrollTop() * direction * 0.6;
        headerImage.css('background-position', '0px ' + scrollPos + 'px');

        var numbersScrollPos = ($(window).scrollTop() - $('#parallax-numbers-wrapper').offset().top) * direction * 0.6;
        parallaxImage.css('background-position', '0px ' + numbersScrollPos + 'px');

        var testimonialsScrollPos = ($(window).scrollTop() - $('#testimonials-wrapper').offset().top) * direction * 0.6;
        testimonialsImage.css('background-position', '0px ' + testimonialsScrollPos + 'px');

        var showcaseScrollPos = ($(window).scrollTop() - $('#showcase-wrapper').offset().top) * direction * 0.6;
        showcaseImage.css('background-position', '0px ' + showcaseScrollPos + 'px');
      } else {
        headerImage.css('background-position', '0px 0px');
        parallaxImage.css('background-position', '0px 0px');
        testimonialsImage.css('background-position', '0px 0px');
        showcaseImage.css('background-position', '0px 0px');
      }

      if ($(window).scrollTop() < $('#content-wrapper').offset().top && menuVisible == 1) {
        navigationWrapper.fadeOut(200);
        menuVisible = 0;
      }

      if ($(window).scrollTop() >= $('#content-wrapper').offset().top && menuVisible == 0) {
        navigationWrapper.fadeIn(200);
        menuVisible = 1;
      }

      clearTimeout($.data(this, 'scrollTimer'));
      $.data(this, 'scrollTimer', setTimeout(function() {
        var currentMenuItem = checkMenuItem();
        if (menuVisible == 1 && currentMenuItem != false) {
          $('#navigation-menu').find('a').each(function() {
            var $this = $(this);

            if (currentMenuItem.attr('id').indexOf($this.html().toLowerCase()) >= 0) {
              $this.css('font-family', 'novecento_sans_wide_bookbold');

              if (window.innerWidth > 992)
                $this.parent().css('top', '-1px');
            } else {
              $this.css('font-family', 'novecento_sans_widebook');

              if (window.innerWidth > 992)
                $this.parent().css('top', '0px');
            }
          });
        }
      }, 30));

      if (isScrolledIntoView('#parallax-numbers-holder')) {
        if (parallaxNumbersBegin == 0) {
          parallaxNumbersBegin = 1;
          $('.parallax-numbers-number').each(function(index) {
            var itemSelector = $(this);
            var maxValue = parseInt($(this).attr('data-max'));

            itemSelector.parent().find('.parallax-numbers-text').transition({
              top: 0,
              opacity: 1,
              delay: index * 100
            }, 200);

            $({
              value: 0
            }).animate({
              value: maxValue
            }, {
              duration: 2000,
              easing: 'swing',
              step: function(now, fx) {
                itemSelector.html(Math.ceil(this.value));
              },
              complete: function() {
                itemSelector.html(maxValue);
              }
            });
          });
        }
      }
    });

    //Portfolio item behaviors
    $(document).on('mouseenter', '.portfolio-item', function() {
      var $this = $(this);
      var imgOriginX = $(this).width() / 2 + 'px';
      var imgOriginY = $(this).height() / 2 + 'px';

      $this.find('.portfolio-item-hover').stop().fadeIn(200);

      if (/mozilla/.test(navigator.userAgent.toLowerCase()))
        $this.children('img').transitionStop().css({
          transformOrigin: imgOriginX + ' ' + imgOriginY
        }).transition({
          scale: 1.5,
          rotate: '0.02deg'
        }, 7000);
      else
        $this.children('img').transitionStop().css({
          transformOrigin: imgOriginX + ' ' + imgOriginY
        }).transition({
          scale: 1.5
        }, 7000);
    });

    $(document).on('mouseleave', '.portfolio-item', function() {
      var $this = $(this);

      $this.find('.portfolio-item-hover').stop().fadeOut(200);

      if (/mozilla/.test(navigator.userAgent.toLowerCase()))
        $this.children('img').transitionStop().transition({
          scale: 1,
          rotate: '0deg'
        }, 7000);
      else
        $this.children('img').transitionStop().transition({
          scale: 1
        }, 7000);
    });

    $(document).on('click', '.portfolio-item', function(e) {
      if ($(this).parent().attr('id') == 'portfolio-full-item-projects')
        var selector = $('#' + $(this).attr('data-id'));
      else
        var selector = $(this);

      $('.portfolio-item').each(function() {
        $(this).removeClass('selected');
      });
      selector.addClass('selected');

      $('#portfolio-full-item-holder').mCustomScrollbar('scrollTo', 0, {
          scrollInertia: 400
        })
        .fadeOut(400, function() {
          changePortfolioFull(selector);
        })
        .fadeIn(400);
      $('#portfolio-full-item').fadeIn(400);
    });

    $(document).on('click', '#portfolio-full-item-navigation > a', function(e) {
      e.preventDefault();

      var selector = $('#portfolio-holder').find('.selected');
      var nextSelector = ($(this).attr('class') == 'arrow-right') ? selector.nextOrFirst() : selector.prevOrLast();
      $('.portfolio-item').each(function() {
        $(this).removeClass('selected');
      });
      nextSelector.addClass('selected');

      $('#portfolio-full-item-holder').mCustomScrollbar('scrollTo', 0, {
          scrollInertia: 400
        })
        .fadeOut(400, function() {
          changePortfolioFull(nextSelector);
        })
        .fadeIn(400);
    });

    $(document).on('click', '#portfolio-full-item-close img', function() {
      $('#portfolio-full-item-holder').fadeOut(400);
      $('#portfolio-full-item').fadeOut(400);
    });

    $(document).on('click', '#portfolio-wrapper .filter-button', function(e) {
      e.preventDefault();
      var $this = $(this);
      var filterValue = (($this.attr('data-filter') == 'all') ? '*' : '.' + $this.attr('data-filter')) + ':not(.hidden)';

      $this.parent().find('.filter-button').each(function() {
        $(this).removeClass('button-active');
      });
      $this.addClass('button-active');

      $('#portfolio-holder').isotope({
        filter: filterValue
      });
    });

    //Our team behaviors
    $(document).on('mouseenter', '.team-item-image', function() {
      var $this = $(this);
      $this.find('.transparent-frame').stop().transition({
        opacity: 0.2
      }, 300);

      $this.find('.team-item-icons').find('a').each(function(index) {
        $(this).stop(true, true).transition({
          top: 0,
          opacity: 1,
          delay: index * 100
        }, 200);
      });
    });

    $(document).on('mouseleave', '.team-item-image', function() {
      var $this = $(this);
      $this.find('.transparent-frame').stop().transition({
        opacity: 0.6
      }, 300);

      $this.find('.team-item-icons').find('a').each(function(index) {
        $(this).stop(true, true).transition({
          top: 40,
          opacity: 0,
          delay: index * 100
        }, 200);
      });
    });

    //Services behavior
    $(document).on('mouseenter', '.services-item', function() {
      var $this = $(this);
      var selectorIcon = $this.find('.services-item-hover h3');
      var selectorImage = $this.find('.services-item-image');

      selectorImage.stop(true, true).transition({
        opacity: 1
      }, 200);
      selectorIcon.stop(true, true).animate({
        marginTop: 0
      }, 200, 'linear');
      $this.find('h4, h5').animate({
        top: selectorIcon.parent().height()
      }, 200, 'linear');
    });

    $(document).on('mouseleave', '.services-item', function() {
      var $this = $(this);
      var selectorIcon = $this.find('.services-item-hover h3');
      var selectorImage = $this.find('.services-item-image');

      selectorImage.stop(true, true).transition({
        opacity: 0
      }, 200);
      selectorIcon.stop(true, true).animate({
        marginTop: -selectorIcon.parent().height()
      }, 200, 'linear');
      $this.find('h4, h5').animate({
        top: 0
      }, 200, 'linear');
    });

    //Blog bevahior
    $(document).on('mouseenter', '.blog-item-inner', function() {
      var $this = $(this);
      var leftValue = $this.width() - 30 - $this.find('h4').width();
      var rightValue = $this.width() - 30 - $this.find('.blog-item-date').width();

      $this.find('.transparent-frame').stop(true, true).transition({
        opacity: 0.2
      }, 400);
      $this.find('h4').stop(true, true).transition({
        left: leftValue
      }, 400);
      $this.find('.blog-item-date').stop(true, true).transition({
        right: rightValue
      }, 400);
    });

    $(document).on('mouseleave', '.blog-item-inner', function() {
      var $this = $(this);

      $this.find('.transparent-frame').stop(true, true).transition({
        opacity: 0.6
      }, 400);
      $this.find('h4').stop(true, true).transition({
        left: 30
      }, 400);
      $this.find('.blog-item-date').stop(true, true).transition({
        right: 30
      }, 400);
    });

    //Testimonials behavior
    $(document).on('click', '#testimonials-dots-holder .testimonials-dot', function() {
      var $this = $(this);

      $('#testimonials-dots-holder').find('.testimonials-dot').each(function() {
        $(this).removeClass('testimonials-active-dot').addClass('testimonials-inactive-dot');
      });
      $this.addClass('testimonials-active-dot');

      var dotIndex = $this.index();
      clearTimeout(testimonialsTimeout);
      testimonialsAnimate(dotIndex, testimonialsNumberOfItems);
    });

    //Information behavior
    $(document).on('mouseenter', '.information-item', function() {
      var $this = $(this);
      var imgOriginX = $(this).width() / 2 + 'px';
      var imgOriginY = $(this).height() / 2 + 'px';

      if (/mozilla/.test(navigator.userAgent.toLowerCase()))
        $this.children('img').transitionStop().css({
          transformOrigin: imgOriginX + ' ' + imgOriginY
        }).transition({
          scale: 1.5,
          rotate: '0.02deg'
        }, 7000);
      else
        $this.children('img').transitionStop().css({
          transformOrigin: imgOriginX + ' ' + imgOriginY
        }).transition({
          scale: 1.5
        }, 7000);
    });

    $(document).on('mouseleave', '.information-item', function() {
      var $this = $(this);

      if (/mozilla/.test(navigator.userAgent.toLowerCase()))
        $this.children('img').transitionStop().transition({
          scale: 1,
          rotate: '0deg'
        }, 7000);
      else
        $this.children('img').transitionStop().transition({
          scale: 1
        }, 7000);
    });

    //Client behavior
    $(document).on('mouseenter', '.clients-item', function() {
      var $this = $(this);
      var imgOriginX = $(this).width() / 2 + 'px';
      var imgOriginY = $(this).height() / 2 + 'px';

      $this.find('.clients-background').transitionStop(true, true).css({
        transformOrigin: imgOriginX + ' ' + imgOriginY
      }).transition({
        scale: 1.2
      }, 400);
      $this.find('.clients-logo-clone').animate({
        top: 0
      }, 200, 'linear');
      $this.find('.clients-logo:not(.clients-logo-clone)').animate({
        top: '100%'
      }, 200, 'linear');
    });

    $(document).on('mouseleave', '.clients-item', function() {
      var $this = $(this);
      var imgOriginX = $(this).width() / 2 + 'px';
      var imgOriginY = $(this).height() / 2 + 'px';

      $this.find('.clients-background').transitionStop(true, true).transition({
        scale: 1
      }, 400);
      $this.find('.clients-logo-clone').animate({
        top: '-100%'
      }, 200, 'linear');
      $this.find('.clients-logo:not(.clients-logo-clone)').animate({
        top: 0
      }, 200, 'linear');
    });

    //Load more button behaviors
    $(document).on('click', '#portfolio-wrapper .load-more-button', function(e) {
      e.preventDefault();

      var $this = $(this);
      var filterButton = $this.parent().find('.filter-button');

      filterButton.each(function() {
        $(this).removeClass('button-active');
      });
      filterButton.eq(0).addClass('button-active');

      $('#portfolio-holder > .last').eq(portfolioRow - 1).next().portfolioAnimate();
      $('#portfolio-holder').isotope({
        filter: '*'
      });

      portfolioRow++;
      if (portfolioRow == portfolioMaxRow)
        $this.css('display', 'none');
    });

    $(document).on('click', '#learn-more-wrapper .load-more-button, #showcase-wrapper .load-more-button', function(e) {
      e.preventDefault();

      bodyHtml.animate({
        scrollTop: 0
      }, 1500);
    });

    //Contact form behaviors
    $(document).on('submit', '#contact-mail', function(e) {
      e.preventDefault();
      $('#contact-mail .contact-loading').css('display', 'inline-block');

      $.ajax({
        type: "POST",
        url: $('#contact-mail').attr('action'),
        data: $('#contact-mail').serialize(),
        success: function(response) {
          var rpl = JSON.parse(response);
          var submitMessage = $('#form-submit-message');

          $('#contact-mail .contact-loading').css('display', 'none');

          if (rpl.type == "error")
            submitMessage.html('<span style="color: #aa0000;"><i class="fa fa-exclamation-circle"></i> ' + rpl.text + '</span>');
          else
            submitMessage.html('<span style="color: #000000;"><i class="fa fa-check-circle"></i> ' + rpl.text + '</span>');
        }
      });
    });
  });

  $(window).resize(function() {
    doResponsive();
  });

  function themeInitialize() {
    menuVisible = 0;
    portfolioRow = $('#portfolio-holder').children('.last:not(.hidden)').length;
    portfolioMaxRow = $('#portfolio-holder').children('.last').length;
    parallaxNumbersBegin = 0;
    testimonialsNumberOfItems = $('#testimonials-item-inner .testimonials-item').length;
    headerNumberOfItems = $('#header-slider .header-image').length;

    window.viewportUnitsBuggyfill.init();
    //$.srSmoothscroll({step: 80, speed: 200, ease: 'linear'});

    if (/trident/.test(navigator.userAgent.toLowerCase()))
      $('.header-image, #parallax-numbers-image, #testimonials-image, #showcase-wrapper').css('background-attachment', 'fixed');

    $('.header-image').css('width', $('body').innerWidth() + 'px');
    $('#content-wrapper').css('margin-bottom', $('#footer-wrapper').height() + 'px');
    $('#header-navigation, #navigation-inner').css('display', 'block');
    $('#header-navigation').css('height', $('#header-logo').height() + parseInt($('#header-logo').css('margin-top')) * 2 + 'px');
    $('#navigation-inner').css('height', $('#navigation-logo').height() + parseInt($('#navigation-logo').css('margin-top')) * 2 + 'px');
    $('#navigation-wrapper').css('display', 'none');
    $('.services-item-hover h3').each(function() {
      $(this).animate({
        marginTop: -$(this).parent().height()
      }, 100);
    });
    $('#testimonials-item-inner').css({
      'width': $('#testimonials-item-inner .testimonials-item').length * 100 + '%',
      'height': $('#testimonials-item-inner .testimonials-item').height() + 'px'
    });
    $('#header-slider').css('width', $('#header-slider .header-image').length * 100 + '%');
    $('#header-slidernav-total').html('/' + $('#header-slider .header-image').length);
    $('#testimonials-item-holder').append(addTestimonialsDots(testimonialsNumberOfItems));
    $('.clients-logo').each(function() {
      var $this = $(this);
      $this.clone().addClass('clients-logo-clone').appendTo($this.parent());
    });

    $('#portfolio-full-item-holder').mCustomScrollbar({
      theme: '3d',
      mouseWheel: {
        scrollAmount: 400
      }
    });
    testimonialsTimeout = setTimeout(function() {
      testimonialsAnimate(1, testimonialsNumberOfItems);
    }, 10000);
    headerTimeout = setTimeout(function() {
      headerAnimate(1, headerNumberOfItems);
    }, 10000);

    $('#portfolio-holder').isotope({
      itemSelector: '.portfolio-item',
      layoutMode: 'fitRows'
    });

    doResponsiveOnce = 1;
    doResponsive();
    window.scrollReveal = new scrollReveal();
  }

  function isScrolledIntoView(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + window.innerHeight;

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return (elemTop <= docViewBottom);
  }

  $.fn.portfolioAnimate = function() {
    var $this = $(this);

    $this.removeClass('hidden');
    if ($this.attr('class').indexOf('last') == -1)
      $this.next().portfolioAnimate();
  };

  $.fn.nextOrFirst = function(selector) {
    var next = this.next(selector);
    return (next.length) ? next : this.prevAll(selector).last();
  };

  $.fn.prevOrLast = function(selector) {
    var prev = this.prev(selector);
    return (prev.length) ? prev : this.nextAll(selector).last();
  };

  function doResponsive() {
    $('.services-item-hover h3').each(function() {
      $(this).animate({
        marginTop: -$(this).parent().height()
      }, 100);
    });
    $('.testimonials-item').css('width', $('#testimonials-item-holder').width() + 'px');
    $('.header-image').css('width', $('body').innerWidth() + 'px');

    if (/safari/.test(navigator.userAgent.toLowerCase()))
      $('#header-wrapper').css('height', $(window).height() + 'px');

    if (window.innerWidth > 1200)
      calculatePortfolioRows(2);
    else if (window.innerWidth <= 1200 && window.innerWidth > 992)
      calculatePortfolioRows(3);
    else if (window.innerWidth <= 992 && window.innerWidth > 768)
      calculatePortfolioRows(6);
    else if (window.innerWidth <= 768 && window.innerWidth > 480)
      calculatePortfolioRows(6);
    else if (window.innerWidth <= 480)
      calculatePortfolioRows(12);

    var headerMenuNavMenu = $('#header-menu, #navigation-menu');
    var headerMenuNavMenuLi = $('#header-menu li, #navigation-menu li');
    var headerMenu = $('#header-menu');
    var navMenu = $('#navigation-menu');

    if (window.innerWidth > 992) {
      if (doResponsiveOnce == 0) {
        headerMenuNavMenu.removeAttr('style').has('ul').find('i').remove();
        headerMenuNavMenuLi.find('ul').find('a').removeAttr('style');

        $('#portfolio-full-item-close').remove().insertAfter('#portfolio-full-item-image');

        doResponsiveOnce = 1;
      }
    } else {
      $('#header-menu').css('margin-top', Math.ceil(($('#header-navigation').height() - $('#header-menu-wrapper').height()) / 2) + 'px');
      $('#navigation-menu').css('margin-top', Math.ceil(($('#navigation-inner').height() - $('#navigation-menu-wrapper').height()) / 2) + 'px');

      if (doResponsiveOnce == 1) {
        headerMenuNavMenuLi.has('ul').children('a').after('<i class="fa fa-angle-down navigation-mobile-arrow"></i>');
        headerMenuNavMenuLi.find('ul').find('a').each(function() {
          $(this).css('padding-left', (parseInt($(this).closest('ul').parent().children('a').css('padding-left')) + 15) + 'px')
        });
        $('#navigation-menu').find('a').each(function() {
          $(this).parent().css('top', '0px');
        });

        if (window.innerWidth > 480)
          $('#portfolio-full-item-close').remove().insertAfter('#portfolio-full-item-text');
        else
          $('#portfolio-full-item-close').remove().insertBefore('#portfolio-full-item-text');

        doResponsiveOnce = 0;
      }
    }
  }

  function calculatePortfolioRows(columnSize) {
    var itemsPerRow = 12 / columnSize;
    var portfolioHolder = $('#portfolio-holder');

    portfolioHolder.find('.portfolio-item').each(function(index) {
      var $this = $(this);

      $this.removeClass('last');

      if (index % itemsPerRow == itemsPerRow - 1)
        $this.addClass('last');
    });

    portfolioRow = portfolioHolder.children('.last:not(.hidden)').length;
    portfolioMaxRow = portfolioHolder.children('.last').length;
  }

  function headerAnimate(headerIndex, headerNumberOfItems) {
    // EDIT BY @HATHIX: STOP THE SLIDESHOW... IMAGES SHOULD BE STATIC
    // var translateX = -headerIndex * $('#header-wrapper').width() + 'px';
    //
    $('#header-slidernav-current').html(headerIndex);
    //
    // $('#header-slider').stop(true, true).transition({x: translateX}, 400, function() {
    // 	clearTimeout(headerTimeout);
    // 	headerTimeout = setTimeout(function() {headerAnimate((headerIndex + 1) % headerNumberOfItems, headerNumberOfItems); }, 10000);
    // });
  }

  function testimonialsAnimate(testimonialsIndex, testimonialsNumberOfItems) {
    var translateX = -testimonialsIndex * $('#testimonials-item-holder').width() + 'px';
    var testimonialsDotsHolder = $('#testimonials-dots-holder');

    testimonialsDotsHolder.find('.testimonials-dot').eq((testimonialsIndex - 1) % testimonialsNumberOfItems).removeClass('testimonials-active-dot').addClass('testimonials-inactive-dot');
    testimonialsDotsHolder.find('.testimonials-dot').eq(testimonialsIndex).removeClass('testimonials-inactive-dot').addClass('testimonials-active-dot');

    $('#testimonials-item-inner').stop(true, true).transition({
      x: translateX
    }, 400, function() {
      clearTimeout(testimonialsTimeout);
      testimonialsTimeout = setTimeout(function() {
        testimonialsAnimate((testimonialsIndex + 1) % testimonialsNumberOfItems, testimonialsNumberOfItems);
      }, 10000);
    });
  }

  function addTestimonialsDots(testimonialsNumberOfItemss) {
    var html = '<div id="testimonials-dots-holder">';

    for (var i = 0; i < testimonialsNumberOfItemss; i++)
      if (i == 0)
        html += '<div class="testimonials-active-dot testimonials-dot"></div>';
      else
        html += '<div class="testimonials-inactive-dot testimonials-dot"></div>';

    html += '<div class="clear"></div></div>';

    return html;
  }

  function changePortfolioFull(selector) {
    var portfolioFullItemText = $('#portfolio-full-item-text');
    var portfolioFullItemAdd = $('#portfolio-full-item-additional');
    var portfolioFullItemProjects = $('#portfolio-full-item-projects');

    portfolioFullItemText.find('h4').html('').html(selector.find('.portfolio-item-text').find('h4').html());
    portfolioFullItemText.find('p').html('').html(selector.find('.portfolio-full-text').html());
    $('#portfolio-full-item-image').find('img').attr('src', selector.children('img').attr('src'));

    var additionalImages = '';
    portfolioFullItemAdd.html('');
    selector.find('.portfolio-additional-photos').find('p').each(function() {
      additionalImages += '<img alt="" src="' + $(this).html() + '">';
    });
    portfolioFullItemAdd.html(additionalImages);

    portfolioFullItemProjects.html('');
    selector.find('.portfolio-related').find('p').each(function() {
      var itemName = $(this).html();

      $('.portfolio-item').each(function() {
        if ($(this).attr('id') == itemName) {
          var portfolioItem = $(this).clone();
          portfolioItem.attr('data-id', portfolioItem.attr('id')).removeAttr('id').removeAttr('style').removeClass('hidden');
          portfolioFullItemProjects.append(portfolioItem);
        }
      })
    });
  }

  function checkMenuItem() {
    var menuItem = false;
    var navigationWrapper = $('#navigation-wrapper');

    $('#content-wrapper').children('section').not('#parallax-numbers-wrapper, #learn-more-wrapper').each(function() {
      if ($(window).scrollTop() + navigationWrapper.height() + 30 >= $(this).offset().top && $(window).scrollTop() + navigationWrapper.height() <= $(this).offset().top + $(this).height())
        menuItem = $(this);
    });

    return menuItem;
  }
})(jQuery);
