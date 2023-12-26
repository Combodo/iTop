
const iTopFormWidgetFactory = new function(){

	/**
	 * Widgets auto installation.
	 *
	 * @constructor
	 */
	const AutoInstall = function()
	{
		console.log('AutoInstall');

		// SELECT widget implementation
		$('[data-widget="SelectWidget"]').each(function(e){
			const oElement = $(this);
			if(!oElement.data('widget-state-initialized')){
				const sId = oElement.attr('id');
				const aOptions = oElement.data('widget-options');
				iTopFormWidgetFactory.CreateSelectWidget(sId, aOptions);
				oElement.data('widget-state-initialized', true);
			}
		});

		$('[data-widget="TextWidget"]').each(function(e){
			const oElement = $(this);
			if(!oElement.data('widget-state-initialized')){
				const sId = oElement.attr('id');
				const aOptions = oElement.data('widget-options');
				iTopFormWidgetFactory.CreateTextWidget(sId, aOptions);
				oElement.data('widget-state-initialized', true);
			}
		});


	}

	/**
	 * CreateTextWidget.
	 *
	 * @param {string} sId
	 * @param {array} aOptions
	 * @constructor
	 */
	const CreateTextWidget = function(sId, aOptions){

		console.log('CreateTextWidget', sId, aOptions);

		if(aOptions['pattern'] !== undefined){

			const oElement = $(`#${sId}`);
			const oMask = IMask(oElement[0],
				{
					mask: aOptions['pattern'],
					lazy: false,
				}
			);
			oMask.on('accept', () => {
				masked = oMask.masked;
				oElement.closest('div').toggleClass('complete', masked.isComplete)
			});
		}

	};

	/**
	 * CreateSelectAjaxWidget.
	 *
	 * @param {string} sId
	 * @param {array} aOptions
	 * @constructor
	 */
	const CreateSelectWidget = function(sId, aOptions){

		console.log('CreateSelectAjaxWidget', sId, aOptions);

		new TomSelect(`#${sId}`, {
			valueField: aOptions['value_field'],
			labelField: aOptions['label_field'],
			searchField: aOptions['search_field'],
			maxItems: aOptions['maxItems'],
			preload: aOptions['preload'],
			plugins: {
				remove_button:{
					title:'Remove this item',
				}
			},
			load: function(query, callback) {
				let sUrl = aOptions['url'];
				if(!sUrl.includes('?')){
					sUrl += '?';
				}
				sUrl += '&'  + aOptions['query_parameter'] + '=' + encodeURIComponent(query);
				fetch(sUrl)
					.then(response => response.json())
					.then(json => {
						callback(json.items);
					}).catch(()=>{
					callback();
				});
			}
		});
	};


	return {
		AutoInstall,
		CreateTextWidget,
		CreateSelectWidget,
	}
};
