/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Leave handler
 *
 * Prevent unexcepted loose of data when leaving a modal / page by prompting a confirmation to the user
 *
 * @since 3.1.0
 * @internal
 */
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'leave_handler' the widget name
	$.widget( 'itop.leave_handler',
		{
			// default options
			options:
			{
				'message': 'Do you really want to loose your changes?',
				'extra_events': {},
			},
			//[event]
			events: {
				'window': ['beforeunload'],
				'body': [],
				'element': []
			},
			// {id : {target : 'event1', target : 'event2'}}
			registered_blockers: {},

			// the constructor
			_create: function()
			{
				const me =this;
				this.element
					.addClass('leave_handler');

				this.element.on('register_blocker.itop', function(oEvent, oData){
					me._onRegisterBlocker(oData.sBlockerId, oData.sTargetElemSelector, oData.oTargetElemSelector, oData.sEventName);
				});
				this.element.on('unregister_blocker.itop', function(oEvent, oData){
					me._onUnregisterBlocker(oData.sBlockerId);
				});

				// Merge default events with extra events from the consumer
				// Note: There is no native way yet to recursively merge objects with arrays (and no duplicate)
				for (const sTarget in this.options.extra_events) {
					for (const sEvent of this.options.extra_events[sTarget]) {
						// Ignore event already present
						if (this.events[sTarget] === undefined || this.events[sTarget].indexOf(sEvent) !== -1) {
							continue;
						}

						this.events[sTarget].push(sEvent);
					}
				}

				// Register listeners for all events
				for (const sTarget in this.events) {
					if (sTarget === 'window') {
						for (const sEvent of this.events[sTarget]) {
							window.addEventListener(sEvent, function(oEvent) {
								return me._onLeaveHandler(oEvent);
							});
						}
					} else if (sTarget === 'body') {
						for (const sEvent of this.events[sTarget]) {
							$('body').on(sEvent, function(oEvent) {
								return me._onLeaveHandler(oEvent);
							});
						}
					} else if (sTarget === 'element') {
						for (const sEvent of this.events[sTarget]) {
							this.element.on(sEvent, function(oEvent) {
								return me._onLeaveHandler(oEvent);
							});
						}
					}
				}

				this._super();
			},
			_onRegisterBlocker: function(sBlockerId, sTargetElemSelector, oTargetElemSelector, sEventName)
			{
				let aRegisteredBlock = {};
				aRegisteredBlock[sTargetElemSelector] = {'eventName': sEventName, 'selector': oTargetElemSelector};
				$.extend(
					aRegisteredBlock,
					this.registered_blockers[sBlockerId]
				);
				this.registered_blockers[sBlockerId] = aRegisteredBlock;
			},
			_onUnregisterBlocker: function(sBlockerId)
			{
				delete this.registered_blockers[sBlockerId];
			},
			_onLeaveHandler: function(oEvent)
			{
				const me = this;
				for(const aRegisteredBlocker in me.registered_blockers)
				{
					for(const sBlockerTarget in me.registered_blockers[aRegisteredBlocker])
					{
						if($(me.registered_blockers[aRegisteredBlocker][sBlockerTarget]['selector'])[0] === oEvent.target && me.registered_blockers[aRegisteredBlocker][sBlockerTarget]['eventName'].split('.')[0] === oEvent.type)
						{
							if(oEvent.type === 'beforeunload')
							{
								oEvent.returnValue = me.options.message;
								return;
							}
							else
							{
								const $bReturnValue = confirm(me.options.message);
								if ($bReturnValue)
								{
									$('body').trigger('unregister_blocker.itop', {'sBlockerId': aRegisteredBlocker});
								}
								return $bReturnValue;
							}
						}
					}
				}
			},
			// events bound via _bind are removed automatically
			// revert other modifications here
			_destroy: function()
			{
				this.element
					.removeClass('leave_handler');

				this._super();
			},
			// _setOptions is called with a hash of all options that are changing
			// always refresh when changing options
			_setOptions: function()
			{
				this._superApply(arguments);
			},
			// _setOption is called for each individual option that is changing
			_setOption: function( key, value )
			{
				this._super( key, value );
			},
			showOptions: function()
			{
				return this.options;
			}
		});
});

