
( function() {
  
  tinymce.PluginManager.add( 'cool_timeline', function( editor, url ) {
	function re_enable_button() {	
				var state = this.enabled();
			}
		
		editor.on( 'keyup' , function() {
	
				if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1 ) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				} else {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', false);
				}
			});
			
      
        editor.addButton( 'cool_timeline_shortcode_button', {
				title: 'Cool Timeline Shortcode',
				text: false,
				image: url + '/cooltimeline.png',
				
			});
		
	
		editor.onSetContent.add(function(editor, o) {
			  if ( editor.getContent().indexOf( '[cool-timeline]' ) > -1 ) {
					editor.controlManager.setDisabled('cool_timeline_shortcode_button', true);
				}
		  });

	
	});
	

})();