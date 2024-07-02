/**
 * 
 * @api
 * @since 3.2.0
 */
const CombodoCKEditorHandler = {
	instances: {},
	instances_promise: {},

	/**
	 *
	 * @param sElem
	 * @param aConfiguration
	 * @constructor
	 */
	PrepareConfiguration: function(sElem, aConfiguration){

		// mention
		if(aConfiguration !== undefined && aConfiguration['mention'] !== undefined){

			// iterate throw feeds...
			aConfiguration['mention']['feeds'].forEach(function(e){

				// ajax feed
				if(e['feed_type'] === 'ajax'){

					// feed callback
					e['feed'] = CombodoCKEditorFeeds.getAjaxItems(e['feed_ajax_options']);

					// feed item render
					e['itemRenderer'] = CombodoCKEditorFeeds.customItemRenderer(sElem);
				}
			})
		}
	},

	/**
	 * Make the oElem enter the fullscreen mode, meaning that it will take all the screen and be above everything else.
	 *
	 * @param {string} sElem The id object of the element
	 * @param {array} aConfiguration The CKEditor configuration
	 * @constructor
	 */
	CreateInstance: function (sElem, aConfiguration) {

		// prepare configuration
		CombodoCKEditorHandler.PrepareConfiguration(sElem, aConfiguration);

		return this.instances_promise[sElem] = new Promise((resolve, reject) => {
			ClassicEditor.create($(sElem)[0], aConfiguration)
			.then(editor => {
				// Adjust size if passed in configuration
				// - Width
				if (aConfiguration.width !== undefined) {
					editor.editing.view.change( writer => { writer.setStyle( 'width', aConfiguration.width, editor.editing.view.document.getRoot() ); } );
				}
				// - Height
				if (aConfiguration.height !== undefined) {
					editor.editing.view.change( writer => { writer.setStyle( 'height', aConfiguration.height, editor.editing.view.document.getRoot() ); } );
				}

				this.instances[sElem] = editor;
				resolve(editor);
			})
			.catch( error => {
				console.error( error );
			} );
		});
	},
	DeleteInstance: async function(sElem){
		let oInstance = await this.GetInstance(sElem);
		if (oInstance) {
			oInstance.destroy().then(() => {
				CombodoJSConsole.Debug('CKEditor for #'+sId+' destroyed successfully');
			}).catch(error => {
				CombodoJSConsole.Error('Error during #'+sId+' editor destruction:' + error);
			});
		}
	},
	GetInstance: async function(sElem){
		if (this.instances[sElem]) {
			return this.instances[sElem];
		}
		else{
			let oEditor = null
			if(!this.instances_promise[sElem]){
				this.instances_promise[sElem] = new Promise((resolve, reject) => {
				});
			}
			await this.instances_promise[sElem].then((editor) => {
				oEditor = editor;
			});
			return oEditor;
		}
	},
	GetInstanceSynchronous: function(sElem) {
		return this.instances[sElem];
	},
	EnableImageUpload: async function(sElem, sUrl){
		const editor = await this.GetInstance(sElem);
				class SimpleUploadAdapter {
					constructor(loader) {
						this.loader = loader;
					}

					upload() {
						return this.loader.file
							.then(file => new Promise((resolve, reject) => {
								// Replace 'your-upload-url' with your server-side upload endpoint
								const uploadUrl = sUrl;

								const formData = new FormData();
								formData.append('upload', file);

								CombodoHTTP.Fetch(uploadUrl, {
									method: 'POST',
									body: formData
								})
									.then(response => response.json())
									.then(responseData => {
										if (responseData.uploaded) {    
											resolve({ default: responseData.url });
										} else {
											reject(responseData.error.message || 'Upload failed');
										}
									})
									.catch(error => {
										reject('Upload failed due to a network error.');
									});
							}));
					}
				}

				// Enable the custom upload adapter
				editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
					return new SimpleUploadAdapter(loader);
				};
	},
	InsertHtmlInsideInstance: function(sElem, sHtml){

		CombodoCKEditorHandler.GetInstance(sElem).then((oCKEditor) => {
			oCKEditor.execute('insert-html', sHtml);
		});

	}
}
