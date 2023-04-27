CKEDITOR.dialog.add( 'objectshortcutDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: 'Object shortcut',
		minWidth: 300,
		minHeight: 200,

		// Dialog window content definition.
		contents: [
			{
				id: 'tab-basic',
				label: 'Basic Settings',

				elements: [
					{
						type: 'text',
						id: 'class',
						label: 'Class',

						validate: CKEDITOR.dialog.validate.notEmpty( "Class field cannot be empty." )
					},
					{
						type: 'text',
						id: 'id',
						label: 'Id',
						validate: CKEDITOR.dialog.validate.notEmpty( "Id field cannot be empty." )
					},
					{
						type: 'text',
						id: 'label',
						label: 'Label',
					},
				]
			},
		],

		// This method is invoked once a user clicks the OK button, confirming the dialog.
		onOk: function() {
			editor.insertHtml( '[[' + this.getValueOf( 'tab-basic', 'class' ) + ':' 
				+ this.getValueOf( 'tab-basic', 'id' ) +
				( this.getValueOf( 'tab-basic', 'label' ) ? '|' + this.getValueOf( 'tab-basic', 'label' ) : '') +
				']]' );
		}
	};
});
