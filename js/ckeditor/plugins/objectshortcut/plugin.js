// Register the plugin within the editor.
CKEDITOR.plugins.add( 'objectshortcut', {

	// Register the icons.
	icons: 'objectshortcut',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog window.
		editor.addCommand( 'objectshortcut', new CKEDITOR.dialogCommand( 'objectshortcutDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'Objectshortcut', {

			// The text part of the button (if available) and the tooltip.
			label: 'Object Shortcut',

			// The command to execute on click.
			command: 'objectshortcut',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'insert'
		});

		// Register our dialog file -- this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'objectshortcutDialog', this.path + 'dialogs/objectshortcut.js' );
	}
});
