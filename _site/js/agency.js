/*!
 * Start Bootstrap - Agnecy Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});

$('div.modal').on('show.bs.modal', function() {
	var modal = this;
	var hash = modal.id;
	window.location.hash = hash;
	window.onhashchange = function() {
		if (!location.hash){
			$(modal).modal('hide');
		}
	}
});




// CUSTOM STUFF
$('#send-email').on('click', function(){
  var to = "contact@paravaneventures.com";
  var subject = $('#subject').val();
  var message = $('#message').val();
  // var name = $('#name').val();

  // var body = message + "From: " + name;

  window.open(getMailtoUrl(to, subject, message));
});

function getMailtoUrl(to, subject, body) {
    var args = [];
    if (typeof subject !== 'undefined') {
        args.push('subject=' + encodeURIComponent(subject));
    }
    if (typeof body !== 'undefined') {
        args.push('body=' + encodeURIComponent(body))
    }

    var url = 'mailto:' + encodeURIComponent(to);
    if (args.length > 0) {
        url += '?' + args.join('&');
    }
    return url;
}
