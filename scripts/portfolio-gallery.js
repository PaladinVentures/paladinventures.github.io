$(window).load(function() {
  var $gallery = $('.portfolio-items');

  $gallery.isotope({
    // options
    itemSelector: '.portfolio-item',
    layoutMode: 'fitRows'
  });

  $filter = $('.filter-wrapper');
  $selectors = $filter.find('a');

  $filter.find('a').click(function() {
    var selector = $(this).attr('data-filter');

    $selectors.removeClass('button-active');
    $(this).addClass('button-active');

    $gallery.isotope({
      filter: selector
    });
    return false;
  });

  var $currentURL = './index.html';
  var $start = 9; // ajax start from last limit
  var $limit = 3;
  var $totalitem = 12;

  $('a.port-more-button').on('click', function(e) {

    var $this = $(this);
    $this.removeClass('done').addClass('loading');
    $.get($currentURL, {
      moduleID: 115,
      start: $start,
      limit: $limit
    }, function(data) {

      $start += $limit;

      var $newItems = $(data);
      $gallery.isotope('insert', $newItems);

      if ($totalitem <= $start) {
        $this.removeClass('loading').addClass('hide');

        // AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
        if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
          $(function($) {
            $("a[rel^='lightbox']").slimbox({ /* Put custom options here */ }, null, function(el) {
              return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
            });
          });
        }
        ////

        return false;
      } else {
        $this.removeClass('loading').addClass('done');
        ////

        // AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
        if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
          $(function($) {
            $("a[rel^='lightbox']").slimbox({ /* Put custom options here */ }, null, function(el) {
              return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
            });
          });
        }

      }

    });

    return false;
  });

});
