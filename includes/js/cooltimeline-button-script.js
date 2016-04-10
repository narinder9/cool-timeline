/* Custom Cooltimeline TinyMCE Button -> Shortcode */
( function() {
   
   tinymce.PluginManager.add( 'cool_timeline', function( editor, url ) {
	
		function disabled_button_on_click() {
				this.disabled( !this.disabled() );
				editor.insertContent( '[cool-timeline]' ); /* Do your true-stuff here */
			}
	
		function re_enable_button() {	
				var state = this.enabled();
			}
		
		editor.on( 'keyup' , function() {
				// re-enable our button once the shortcode has been deleted
				// we'll wait all the way till [cool-timeline]
				if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1 ) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				} else {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', false);
				}
			});
			
        // Add a button that drops in our shortcode
        editor.addButton( 'cool_timeline_shortcode_button', {
				title: 'Cool Timeline Shortcode',
				text: false,
				image: url + '/cooltimeline.png',
				onclick: disabled_button_on_click
			});
		
		// disable the button if the shortcode exists (when loading a previously saved page/post etc)
		editor.onSetContent.add(function(editor, o) {
			  if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1 ) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				}
		  });

	
	});
	

})();