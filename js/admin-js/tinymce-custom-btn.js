( function() {
    tinymce.PluginManager.add( 'cool_timeline', function( editor, url ) {
      
   var layouts=[];

	   layouts.push({"text":'Default Layout',"value":'default'});
	   layouts.push({"text":'One Side Layout',"value":'one-side'});
	   layouts.push({"text":'Simple Layout',"value":'simple'});
	   layouts.push({"text":'Compact Layout',"value":'compact'});
     var animations_eff={
		   "animations":[
				{"text":"None","value":"none"},
			   {"text":"fadeInUp","value":"fadeInUp"}
				 ]}

	 var s_order=[{"text":"DESC","value":"DESC"},
				 {"text":"ASC","value":"ASC"}
		];
	var s_cont=[{"text":"Summary","value":"short"},
		{"text":"Full Text","value":"full"}
		];

    var skin_list={
		   "skins":[
				{"text":"Default","value":"default"},
			   {"text":"Clean","value":"clean"}
				 ]}
	  var icons_options=[];
	   icons_options.push({"text":"NO","value":"NO"});
	   icons_options.push({"text":"YES","value":"YES"});
  	  var date_formats={
		   "formats":[
			   {"text":"F j","value":"F j"},
			   {"text":"F j Y","value":"F j Y"},
			   {"text":"Y-m-d","value":"Y-m-d"},
			   {"text":"m/d/Y","value":"m/d/Y"},
			   {"text":"d/m/Y","value":"d/m/Y"},
			   {"text":"F j Y g:i A","value":"F j Y g:i A"},
			   {"text":"Y","value":"Y"},
			   ]};
   
	  editor.addButton( 'cool_timeline_btn', {
				title: 'Cool Timeline Shortcode',
				text: false,
				image: url + '/cooltimeline.png',
				type: 'menubutton',
				menu: [
                {
                    text: 'Add Cool Timeline Shortcode',
					onclick: function() {

						editor.windowManager.open( {
							title: 'Add Cool Timeline Shortcode	',
							body: [
								{
									type: 'listbox',
									name: 'timeline_layout',
									label: 'Timeline Layout',
									'values':layouts
								},
							
								{
									type: 'listbox',
									name: 'date_format',
									label: 'Timeline Date Formats',
									'values':date_formats.formats
								},
								{
									type: 'listbox',
									name: 'skin',
									label: 'Timeline Skin',
									'values':skin_list.skins
								},
								{
									type: 'listbox',
									name: 'ctl_icons',
									label: 'Story Icon',
									'values':icons_options
								},
								
								{
									type: 'textbox',
									name: 'number_of_posts',
									label: 'Show number of Stories',
									value:20
								},
									{
									type: 'listbox',
									name: 'animations',
									label: 'Stories Animation Effect',
									'values':animations_eff.animations
								},
								{
									type: 'listbox',
									name: 'story_content',
									label: 'Stories Description?',
									'values':s_cont
								},
								{
									type: 'listbox',
									name: 'stories_order',
									label: 'Story Order',
									'values':s_order
								}
								],
							onsubmit: function( e ) {
								editor.insertContent( '[cool-timeline layout="'+ e.data.timeline_layout+'"   animation="' + e.data.animations + '" date-format="' + e.data.date_format + '" icons="' + e.data.ctl_icons + '" show-posts="' + e.data.number_of_posts + '" skin="' + e.data.skin + '" order="' + e.data.stories_order + '"  story-content="'+ e.data.story_content +'"]');
							}
						});
					}
                },
                {
                    text: 'Add Cool Horizontal Timeline',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Add Horizontal Timeline/Roadmap',
							body: [
								{
									type: 'listbox',
									name: 'date_format',
									label: 'Timeline Date Formats',
									'values':date_formats.formats
								},
								{
									type: 'textbox',
									name: 'number_of_posts',
									label: 'Show Per Page',
									value:4
								},
								{
									type: 'listbox',
									name: 'story_content',
									label: 'Stories Description?',
									'values':s_cont
								},
								{
									type: 'listbox',
									name: 'stories_order',
									label: 'Story Order',
									'values':s_order
								}
							
							],
							onsubmit: function( e ) {
								editor.insertContent( '[cool-timeline layout="horizontal"  show-posts="' + e.data.number_of_posts + '"   date-format="' + e.data.date_format + '" order="' + e.data.stories_order + '" story-content="'+ e.data.story_content +'"]');
							}
						});
					}
                }

                ],
			});
			
			
	});
})();