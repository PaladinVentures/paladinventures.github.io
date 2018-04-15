/**
 * @copyright	Copyright (c) 2013 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
	$.fn.SLScrollToTop	= function(options) {
		var defaults	= {
			'id':			'scrollToTop',
			'className':	'scrollToTop',
			'image':		'',
			'text':			'^ Scroll to Top',
			'title':		'Scroll to Top',
			'duration':		500
		};
		
		var options		= $.extend(defaults, options);
		var $link		= $('<div />', {
			'id':		options.id,
			'html':		options.text,
			'title':	options.title,
			'class':	options.className
		}).appendTo('body').hide();

		if (options.image != '') {
			$('<img src="' + options.image + '" alt="' + options.title + '" />').prependTo($link);
		}

		$(window).scroll(function() {
			$this	= $(this);

			if ($this.scrollTop() != 0) {
				$link.fadeIn();
			} else {
				$link.fadeOut();
			}
		});

		$link.click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, options.duration);
		});
	}
})(jQuery);