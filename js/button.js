( function() {
    tinymce.PluginManager.add( 'wplikelockplugin', function( editor, url ) {

	var cont_sel = false;
	var format_to_apply ='';
        // Add a button that opens a window
        editor.addButton( 'wplikelockplugin', {

            title: 'Facebook Like Lock',
			image : url + '/../img/wplike_lock.png',
            icon: 'sl-dashicons-lock',			
			
            onclick: function() {
            
				  selected = tinyMCE.activeEditor.selection.getContent();
					if(selected)
                        editor.selection.setContent( '[wp-like-lock]' +selected+' [/wp-like-lock]' );
					else
					    editor.execCommand( 'mceInsertContent', false,'[wp-like-lock] your content [/wp-like-lock]' );
               
            }

        } );

    } );

} )();