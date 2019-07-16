jQuery(document).ready(function ($) {
	$('.ctl_dismiss_notice').on('click', function (event) {
		var $this = $(this);
		var wrapper=$this.parents('.cool-feedback-notice-wrapper');
		var ajaxURL=wrapper.data('ajax-url');
		var ajaxCallback=wrapper.data('ajax-callback');
		
		$.post(ajaxURL, { 'action':ajaxCallback }, function( data ) {
			wrapper.slideUp('fast');
		  }, "json");

	});
});