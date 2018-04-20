/**
 *
 * iTop Search form history handler
 *
 * the widget exposes
 *
 * $(cssSelector).search_form_handler_history({"itop_root_class":"FooBarClass"}) : constructor
 * $(cssSelector).search_form_handler_history("getHistory") : history array getter
 * $(cssSelector).search_form_handler_history("setLatest", "field.ref") : prepend the field ref to the beginning of the history array
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
			"history_max_length": 5,				// if the history is longer, the older entries are removed
			"history_backend_store_timeout": 5000,	// wait for this time before storing the new history in the backend
		},

		aHistory : [],
		
		iStoreHistoryTimeoutHandler : undefined,

		/**
		 * the constructor, called by the widget factory
		 * @private
		 */
		_create: function()
		{
			var me = this;

			if (me.options.preference_code == undefined)
			{
				me.options.preference_code = me.options.itop_root_class + '__search_history';
			}


			me.aHistory = JSON.parse(GetUserPreference(me._getPreferenceCode(), "[]"));
		},

		getHistory: function()
		{
			var me = this;

			return me.aHistory;
		},

		setLatest: function(sFieldRef)
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
				me.aHistory.splice(-iDeleteCount, iDeleteCount);
			}

			//store it in the backend (with a delay in the hope to wait long enough to make bulk modifications
			me._storeHistory();

			//setter should never return a value!
			return;
		},



		/**
		 * @returns String
		 * @private
		 */
		_getPreferenceCode: function()
		{
			return this.options.preference_code;
		},


		/**
		 * should only be called by setLatest in order to store the updated history
		 * @private
		 */
		_storeHistory: function()
		{
			var me = this;

			if (undefined != this.iStoreHistoryTimeoutHandler)
			{
				clearTimeout(this.iStoreHistoryTimeoutHandler);
			}

			this.iStoreHistoryTimeoutHandler = setTimeout(function(){ me._storeHistoryTimeoutFunction(); }, this.options.history_backend_store_timeout);
		},

		/**
		 * should only be called by _storeHistory using a timeout
		 * @private
		 */
		_storeHistoryTimeoutFunction: function()
		{
			SetUserPreference(this._getPreferenceCode(), JSON.stringify(this.getHistory()), true);

			this.iStoreHistoryTimeoutHandler = undefined;
		}

	});
});
