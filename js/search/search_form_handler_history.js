/**
 *
 * iTop Search form history handler
 *
 * the widget exposes
 *
 * $(cssSelector).search_form_handler_history({"itop_root_class":"FooBarClass"}) : constructor
 * $(cssSelector).search_form_handler_history("getHistory") : history array getter
 * $(cssSelector).search_form_handler_history("historyUnshift", "field.ref") : prepend the field ref to the beginning of the history array
 *
 *
 * please take a look at the options for custom constructor values
 *
 *
 * The persistence layer rely on these two core iTop's JS functions :
 *  - GetUserPreference(sPreferenceCode, sDefaultValue)
 *  - SetUserPreference(sPreferenceCode, sPrefValue, bPersistent)
 *
 */
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_handler_history' the widget name
	$.widget( 'itop.search_form_handler_history',
	{
		// default options
		options:
		{
			"itop_root_class": null,
			"preference_code": undefined, 			// if undefined, it is computed on _create
			"history_max_length": 10,				// if the history is longer, the older entries are removed
			"history_backend_store_timeout": 5000,	// wait for this time before storing the new history in the backend
		},

		aHistory : [],
		
		iStoreHistoryTimeoutHandler : undefined,

		getHistory: function()
		{
			return me.aHistory;
		},

		historyUnshift: function(sFieldRef)
		{
			var me = this;

			//if present, delete
			var iIndexInHistory = me.aHistory.indexOf(sFieldRef);
			if (iIndexInHistory > -1)
			{
				me.aHistory.splice(iIndexInHistory, 1);
			}

			//add to the begining
			me.aHistory.unshift(sFieldRef);

			//restrain the length to me.options.history_max_length
			var iDeleteCount = me.aHistory.length - me.options.history_max_length;
			if (iDeleteCount > 0)
			{
				me.aHistory.splice(0, iDeleteCount);
			}

			//store it in the backend (with a delay in the hope to wait long enough to make bulk modifications
			me._storeHistory();

			//setter should never return a value!
			return;
		},

		/**
		 * the constructor, called by the widget factory
		 * @private
		 */
		_create: function()
		{
			var me = this;

			if (me.options.preference_code == undefined)
			{
				me.options.preference_code = me.options.itop_root_class + '|search_history';
			}


			me.aHistory = GetUserPreference(me._getPreferenceCode(), []);
		},

		/**
		 * @returns String
		 * @private
		 */
		_getPreferenceCode: function()
		{
			return me.options.preference_code;
		},


		/**
		 * should only be called by historyUnshift in order to store the updated history
		 * @private
		 */
		_storeHistory: function()
		{
			var me = this;

			if (undefined != me.iStoreHistoryTimeoutHandler)
			{
				clearTimeout(me.iStoreHistoryTimeoutHandler);
			}

			me.iStoreHistoryTimeoutHandler = setTimeout(me._storeHistoryTimeoutFunction, me.options.history_backend_store_timeout);
		},

		/**
		 * should only be called by _storeHistory using a timeout
		 * @private
		 */
		_storeHistoryTimeoutFunction: function()
		{
			var me = this;

			SetUserPreference(me._getPreferenceCode(), me.getHistory(), true);

			me.iStoreHistoryTimeoutHandler = undefined;
		}

	});
});
