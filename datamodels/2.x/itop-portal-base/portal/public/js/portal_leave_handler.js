/*
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'portal_leave_handler' the widget name
	$.widget( 'itop.portal_leave_handler',
		{
			// default options
			options:
			{
				'message': 'allo la',
			},
			events: ['hide.bs.modal', 'beforeunload'],
			//[event]
			registered_blockers: {},
			// {id : {target : 'event1', target : 'event2'}}

			// the constructor
			_create: function()
			{
				var me =this;
				this.element
					.addClass('portal_leave_handler');

				this.element.on('register_blocker.portal.itop', function(oEvent, oData){
					me._onRegisterBlocker(oData.sBlockerId, oData.sTargetElemSelector, oData.oTargetElemSelector, oData.sEventName);
				});
				this.element.on('unregister_blocker.portal.itop', function(oEvent, oData){
					me._onUnregisterBlocker(oData.sBlockerId);
				});

				this.element.on('hide.bs.modal', function(oEvent) {return me._onLeaveHandler(oEvent);});
				window.addEventListener('beforeunload', function(oEvent) {return me._onLeaveHandler(oEvent);});
				
				this._super();
			},
			_onRegisterBlocker: function(sBlockerId, sTargetElemSelector, oTargetElemSelector, sEventName)
			{
				var aRegisteredBlock = {};
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
				var me = this;
				for(var aRegisteredBlocker in me.registered_blockers)
				{
					for(var sBlockerTarget in me.registered_blockers[aRegisteredBlocker])
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
								var $bReturnValue = confirm(me.options.message);
								if ($bReturnValue)
								{
									$('body').trigger('unregister_blocker.portal.itop', {'sBlockerId': aRegisteredBlocker});
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
					.removeClass('portal_leave_handler');

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

