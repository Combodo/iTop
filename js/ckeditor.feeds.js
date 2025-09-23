/**
 * CKEditor Feeds.
 *
 * @api
 * @since 3.2.0
 */
const CombodoCKEditorFeeds = {

	/**
	 * Get AJAX items.
	 *
	 * @param options
	 * @returns {function(*): Promise<*>}
	 */
	getAjaxItems: function( options ) {
		return async function(queryText) {
			return new Promise( resolve => {
				setTimeout( () => {
					CombodoHTTP.Fetch(options.url + queryText)
						.then(response => {
							return response.json();
						})
						.then(json => {
							// ckeditor mandatory data
							json.search_data.forEach(e => {
								e['name'] = e['friendlyname'];
								e['id'] = options['marker']+e['friendlyname'];
							});
							// return searched data
							resolve( json.search_data);
						});

				}, options.throttle);
			});
		}
	},

	/**
	 * Item Renderer.
	 *
	 * @param id
	 * @returns {function(*): *}
	 */
	customItemRenderer: function( id ) {
		return function(item){
			return CombodoGlobalToolbox.RenderTemplate(id + '_items_template', item, 'ibo-mention-item')[0];
		};
	}

}
