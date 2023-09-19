jQuery('document').ready(function($){
		// enabled animation on page scroll
	$(".cooltimeline_cont").each(function(index ){
		var animations=$(this).attr("data-animations");
	if(animations!="none") {
		// You can also pass an optional settings object
		// below listed default settings
		AOS.init({
			// Global settings:
			disable:'mobile', // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
			startEvent: 'DOMContentLoaded', // name of the event dispatched on the document, that AOS should initialize on
			offset: 75, // offset (in px) from the original trigger point
			delay: 0, // values from 0 to 3000, with step 50ms
			duration: 750, // values from 0 to 3000, with step 50ms
			easing: 'ease-in-out-sine', // default easing for AOS animations
			mirror: true,
		});
			}
});
});
