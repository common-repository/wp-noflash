// closure to avoid namespace collision
(function(){
	// creates the plugin
	var root = userSettings.noflash_plugin_dir;
	
	tinymce.create('tinymce.plugins.noflash', {
		// creates control instances based on the control's id.
		// our button's id is &quot;reg_button&quot;
		createControl : function(id, controlManager) {
			if (id == 'noflash_button') {
				// creates the button
				var button = controlManager.createButton('noflash_button', {
					title : 'Embed NoFlash Slideshow', // title of the button
					image : root+'noflash.gif',  // path to the button's image
					onclick : function() {
						// do something when the button is clicked :)
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Embed NoFlash Slideshow', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=noflash-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	
	// registers the plugin.
	tinymce.PluginManager.add('noflash', tinymce.plugins.noflash);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		jQuery('#footer').append(function() {
				jQuery(this).after('<div id="hook"></div>');
				jQuery('#hook').load(root + 'noflash-editor.php');
			});
	
	});

	
})()