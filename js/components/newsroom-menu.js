$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "newsroom_menu" the widget name
	$.widget( "itop.newsroom_menu",
	{
		// default options
		options:
		{
			image_icon: '',
			cache_uuid: '',
			display_limit: 7,
			placeholder_image_icon: '',
			providers: [],
			labels: {
				no_notification: 'UI:Newsroom:NoNewMessage',
				x_notifications: 'UI:Newsroom:XNewMessage',
				mark_all_as_read: 'UI:Newsroom:MarkAllAsRead',
				view_all: 'UI:Newsroom:ViewAllMessages'
			}
		},
		css_classes:
		{
			newsroom_menu: 'ibo-newsroom-menu',
			empty : 'ibo-is-empty'
		},
		js_selectors:
		{
			menu_toggler: '[data-role="ibo-navigation-menu--notifications-toggler"]',
			menu_toggler_message: '[data-role="ibo-navigation-menu--user-notifications--toggler--message"]',
			notification_message: '[data-role="ibo-navigation-menu--notifications-item"]',
			notification_dismiss_all: '[data-role="ibo-navigation-menu--notifications-dismiss-all"]',
		},
	
		// the constructor
		_create: function()
		{
			var me = this;
			this.aMessageByProvider = [];
			this._load();
		},
		_initializePopoverMenu: function()
		{
			var me = this;

			// Check if popover menu is already initialized
			if ($(this.js_selectors.menu_toggler).hasClass('ibo-is-loaded') === true) {
				return;
			}

			// Important: For now, the popover menu is manually instantiated even though the PHP NewsroomMenu class inherits PopoverMenu because the jQuery widget doesn't. We might refactor this in the future.
			$(me.element).popover_menu({'toggler': this.js_selectors.menu_toggler});
			$(this.js_selectors.menu_toggler).on('click', function (oEvent) {
				var oEventTarget = $(oEvent.target);
				var aEventTargetPos = oEventTarget.position();
				var aEventTargetOffset = oEventTarget.offset();

				// N°2039 - When opening the menu, refresh messages without waiting for the providers TTL to avoid news not being visible even though they have been created
				me.clearCache();
				me._getAllMessages();

				$iHeight = Math.abs(aEventTargetOffset.top-100);
				$(me.element).css({
					'max-height': $iHeight+'px',
					'top': (aEventTargetPos.top+parseInt(oEventTarget.css('marginTop'), 10)-Math.min($(me.element).height(), $iHeight))+'px',
					'left': (aEventTargetPos.left+parseInt(oEventTarget.css('marginLeft'), 10)+oEventTarget.width())+'px',
				});
			});
			this.element.addClass(this.css_classes.newsroom_menu);
			$(this.js_selectors.menu_toggler).addClass('ibo-is-loaded');
		},
		// called when created, and later when changing options
		_refresh: function()
		{
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass(this.css_classes.newsroom_menu);
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function(key, value)
		{
			if (this.options[key] != value)
			{
				// If any option changes, clear the cache BEFORE applying the new settings
				this._clearCache();
			}
			
			this._superApply(arguments);
		},
		_load: function()
		{
			var me = this;

			if(this.options.providers.length > 0) {
				setTimeout(function() { me._getAllMessages(); }, 1000);
			}
		},
		_getAllMessages: function()
		{
			this.aMessageByProvider = [];
			this._getMessages(0); // start at the first provider (index == 0)
		},
		_getMessages: function(idxProvider)
		{
			var sKey = this._makeCacheKey(idxProvider);
			var oJSONData = this._getCachedData(idxProvider);
			if (oJSONData != null)
			{
				this._onMessagesFetched(idxProvider, oJSONData);
			}
			else
			{
				this._fetchMessages(idxProvider); // Asynchronous
			}
		},
		_fetchMessages: function(idxProvider)
		{
			var sUrl = this.options.providers[idxProvider].fetch_url;
			var me = this;
			var idx = idxProvider;
			
			$.ajax({ type: "GET",
		        	 url: sUrl,
		        	 async: true,
		        	 dataType : 'jsonp',
		        	 crossDomain: true,
		        	 jsonp: "callback"
		     })
		     .done(function(oJSONData) {
			     me._cacheData(idx, oJSONData);
		    	 me._onMessagesFetched(idx, oJSONData);
		    }).fail(function() {
				CombodoJSConsole.Warn('Newsroom: failed to fetch data from the web for provider '+idx+' url: '+me.options.providers[idxProvider].fetch_url);
		    	 me._cacheData(idx, []);
		    	 me._onMessagesFetched(idx, []);		    	
		    });
		},
		_onMessagesFetched: function(idxProvider, oJSONData)
		{
			this.aMessageByProvider[idxProvider] = oJSONData;
			if ((1+idxProvider) < this.options.providers.length)
			{
				this._getMessages(idxProvider+1); // Process the next provider
			}
			else
			{
				this._onAllMessagesFetched(); // All messages retrieved
			}
		},
		_onAllMessagesFetched: function()
		{
			var aAllMessages = [];
			for(var k in this.aMessageByProvider)
			{ 
				for(var j in this.aMessageByProvider[k])
				{
					var oMsg = this.aMessageByProvider[k][j];
					oMsg.id = ''+oMsg.id; // Stringify
					
					// Process the provider specific placeholders, if any
					if (this.options.providers[k].placeholders !== undefined)
					{
						for(var sSearch in this.options.providers[k].placeholders)
						{
							var sReplace = this.options.providers[k].placeholders[sSearch];
							var sResult = oMsg.url.replace(sSearch, sReplace);
							oMsg.url = sResult;
						}
					}
					oMsg.provider = k;
					aAllMessages.push(oMsg);
				}
			}
			
			aAllMessages.sort(function(msg1, msg2) {
				if (msg1.priority < msg2.priority) return -1;
				if (msg1.priority > msg2.priority) return 1;
				var oDate1 = new Date(msg1.start_date);
				var oDate2 = new Date(msg2.start_date);
				if (oDate1 > oDate2) return -1;
				if (oDate1 < oDate2) return 1;
				return 1;
			});
			this._refreshTogglerMessage(aAllMessages.length);
			this._buildMenu(aAllMessages);
		},
		_refreshTogglerMessage : function(iItemCount){
			var sMessage = Dict.S(this.options.labels.no_notification);
			if(iItemCount > 0){
				sMessage = Dict.Format(this.options.labels.x_notifications, iItemCount);
			}
			$(this.js_selectors.menu_toggler_message).html(sMessage);
			$(this.js_selectors.menu_toggler).attr('data-tooltip-content', sMessage);
			CombodoTooltip.InitTooltipFromMarkup($(this.js_selectors.menu_toggler), true);
		},
		_buildDismissAllSection: function()
		{
			return '<div class="ibo-popover-menu--section ibo-navigation-menu--notifications-dismiss-all" data-role="ibo-popover-menu--section"><a class="ibo-popover-menu--item" data-role="ibo-navigation-menu--notifications-dismiss-all" ><i class="fas fa-fw fa-check' +
				' ibo-navigation-menu--notifications-dismiss-all--icon"></i>' + Dict.S(this.options.labels.mark_all_as_read) + '</a><hr class="ibo-popover-menu--item ibo-popover-menu--item-separator"></div>';
		},
		_buildMessageSection: function () {
			return '<div class="ibo-popover-menu--section ibo-navigation-menu--notifications--messages-section" data-role="ibo-popover-menu--section">';
		},
		_buildShowAllMessagesSection: function () {
			return '<div class="ibo-popover-menu--section ibo-navigation-menu--notifications--show-all-messages" data-role="ibo-popover-menu--section">';
		},
		_buildMessageItems: function (sId, sText, sImage, sStartDate, sProvider, sUrl, sTarget, sPriority, oConverter) {
			let sNewMessageIndicatorTooltip = Dict.S('UI:Newsroom:Priority:'+sPriority+':Tooltip');
			var sNewMessageIndicator = '<div class="ibo-navigation-menu--notifications--item--new-message-indicator ibo-is-priority-'+sPriority+'" data-tooltip-content="'+sNewMessageIndicatorTooltip+'"></div>';
			sImage = '<img class="ibo-navigation-menu--notifications--item--image" src="' + sImage + '"><i class="ibo-navigation-menu--notifications--item--image ' + this.options.placeholder_image_icon + '"></i>';

			var div = document.createElement("div");
			div.textContent = sText;
			var sDescription = div.innerHTML; // Escape HTML entities for XSS prevention

			var sRichDescription = '<div class="ibo-navigation-menu--notifications--item--content ibo-is-html-content">' + oConverter.makeHtml(sDescription) + '</div>';

			var sBottomText = '<span class="ibo-navigation-menu--notifications--item--bottom-text">' + sImage + '<span>' + this.options.providers[sProvider].label + '</span> <span> ' + moment(sStartDate).fromNow() + '</span></span>';

			return '<div class="ibo-popover-menu--item ibo-navigation-menu--notifications-item" data-role="ibo-navigation-menu--notifications-item" data-msg-id="' + sId + '" data-provider-id="' + sProvider + '" href="' + sUrl + '" target="' + sTarget + '" id="newsroom_menu_item_' + sId + '">' +
				sNewMessageIndicator + sRichDescription + sBottomText + '</div>';
		},
		_buildNoMessageItem: function()
		{
			return '<div class="ibo-popover-menu--item ibo-popover-menu--item--no-message">' + Dict.S(this.options.labels.no_notification) +
				'<div class="ibo-popover-menu--item--no-message--image ibo-svg-illustration--container">' + this.options.no_message_icon + '</div></div>';
		},
		_buildSingleShowAllMessagesItem: function()
		{
			return '<a class="ibo-popover-menu--item" data-role="ibo-navigation-menu--notifications-show-all" href="' + this.options.providers[0].view_all_url + '" target="' + this.options.providers[0].target + '">' + Dict.S(this.options.labels.view_all) + '</a>';
		},
		_buildMultipleShowAllMessagesItem: function(aUnreadMessagesByProvider)
		{
			var sUnreadMessages = ''
			for(k in this.options.providers) {
				var sExtraMessages = '';
				if (aUnreadMessagesByProvider[k] > 0) {
					sExtraMessages = ' <span class="ibo-navigation-menu--notifications-show-all-multiple--counter">(' + aUnreadMessagesByProvider[k] + ')</span>'
				}
				sUnreadMessages += '<a class="ibo-popover-menu--item" data-provider-id="' + k + '" href="' + this.options.providers[k].view_all_url + '" target="' + this.options.providers[k].target + '">' + this.options.providers[k].label + sExtraMessages + '</a>';
			}
			return '<a class="ibo-popover-menu--item ibo-navigation-menu--notifications-show-all-multiple" data-role="ibo-navigation-menu--notifications-show-all-multiple" href="#">'+Dict.S(this.options.labels.view_all)+'<i class="fas fas-caret-down"></i></a>' +
				'<div class="ibo-popover-menu" data-role="ibo-popover-menu"><div class="ibo-popover-menu--section" data-role="ibo-popover-menu--section">'+sUnreadMessages+'</div></div>';
		},
		_buildMenu: function(aAllMessages)
		{
			const me = this;
			var iTotalCount = aAllMessages.length;
			var iCount = 0;
			var sDismissAllSection = this._buildDismissAllSection();
			var sMessageSection = this._buildMessageSection();
			var sShowAllMessagesSection = this._buildShowAllMessagesSection();

			moment.locale(GetUserLanguage());
			var aUnreadMessagesByProvider = [];
			for(var k in this.options.providers)
			{
				aUnreadMessagesByProvider[k] = 0;
			}
			var oConverter = new showdown.Converter({noHeaderId: true});
			for(var k in aAllMessages)
			{
				var oMessage = aAllMessages[k];
				aUnreadMessagesByProvider[oMessage.provider]++;
				if (iCount < this.options.display_limit) {
					var sMessageItem = this._buildMessageItems(oMessage.id, oMessage.text, oMessage.image, oMessage.start_date, oMessage.provider, oMessage.url, oMessage.target, oMessage.priority, oConverter)
					sMessageSection += sMessageItem;
				}
				iCount++;
			}

			if (iCount == 0)
			{
				var sNoMessageItem = this._buildNoMessageItem();
				sMessageSection += sNoMessageItem;
			}
			sMessageSection += '<hr class="ibo-popover-menu--item ibo-popover-menu--item-separator"></div>';

			if (this.options.providers.length == 1)
			{
				var SingleShowAllMessagesItem = this._buildSingleShowAllMessagesItem();
				sShowAllMessagesSection += SingleShowAllMessagesItem;
				sShowAllMessagesSection += '</div>'
			}
			else
			{
				var MultipleShowAllMessagesItem = this._buildMultipleShowAllMessagesItem(aUnreadMessagesByProvider);
				sShowAllMessagesSection += MultipleShowAllMessagesItem + '</div>'
			}
			if (iCount > 0)
			{
				$(this.element).html(sDismissAllSection + sMessageSection + sShowAllMessagesSection);
				$('.ibo-navigation-menu--notifications--item--content img').each(function(){
					tippy(this, {'content': this.outerHTML, 'placement': 'left', 'trigger': 'mouseenter focus', 'animation':'shift-away-subtle', 'allowHTML': true });
				});
				CombodoTooltip.InitAllNonInstantiatedTooltips($(this.element), true);
				// Add events listeners
				$(this.js_selectors.notification_message).on('click', function(oEvent){
					me._handleClick(this, oEvent);
				});
				$(this.js_selectors.notification_dismiss_all).on('click', function(ev) {
					me._markAllAsRead();
				});

				// Remove class to show there is new messages
				$(this.js_selectors.menu_toggler).removeClass(this.css_classes.empty);

			}
			else
			{
				$(this.element).html(sMessageSection + sShowAllMessagesSection);

				// Add class to show there is no messages
				$(this.js_selectors.menu_toggler).addClass(this.css_classes.empty);
			}

			if (this.options.providers.length != 1) {
				var oElem = $('[data-role="ibo-navigation-menu--notifications-show-all-multiple"]~[data-role="ibo-popover-menu"]');
				oElem.popover_menu({
					'toggler': '[data-role="ibo-navigation-menu--notifications-show-all-multiple"]',
					'position': {
						'horizontal': "(oTargetPos.left+parseInt(oTargetElem.css('marginLeft'), 10)+(oTargetElem.outerWidth() / 2)-(oElem.outerWidth() / 2))+'px'",
					},
				});

			}
			this._initializePopoverMenu();
		},
		_handleClick: function(oElem, oEvent)
		{
			// If click was made on an hyperlink in the message, just follow the hyperlink
			if (oEvent.target.nodeName.toLowerCase() === 'a') {
				oEvent.stopPropagation();
				return;
			}

			// Otherwise we mark the message as read...
			var idxProvider = $(oElem).attr('data-provider-id');
			var msgId = $(oElem).attr('data-msg-id');
			this._markOneMessageAsRead(idxProvider, msgId);

			// ... and open it as intended
			// Note: Default behavior is to open the news on a new tab unless it is specified otherwise
			const urlTarget = $(oElem).attr('target') !== 'undefined' ? $(oElem).attr('target') : '_blank';
			window.open($(oElem).attr('href'), urlTarget);

			// Finally refresh messages
			$(this.element).popover_menu("togglePopup");
			this._getAllMessages();
		},
		clearCache: function(idx)
		{
			if (idx == undefined)
			{
				for(var k in this.options.providers)
				{
					var sKey = this._makeCacheKey(k);
					localStorage.removeItem(sKey);			
				}				
			}
			else
			{
				var sKey = this._makeCacheKey(idx);
				localStorage.removeItem(sKey);			
			}
		},
		_makeCacheKey: function(idxProvider)
		{
			return this.options.cache_uuid+'_'+idxProvider;
		},
		_cacheData: function(idxProvider, oJSONData)
		{
			var sKey = this._makeCacheKey(idxProvider);
			var bSuccess = true;
			var oNow = new Date();
			var oExpirationDate = new Date(oNow.getTime() + this.options.providers[idxProvider].ttl * 1000);
			
			var oData = {value: JSON.stringify(oJSONData), expiration_date: oExpirationDate.getTime() };
			try
			{
				localStorage.setItem(sKey, JSON.stringify(oData))
			}
			catch(e)
			{
				CombodoJSConsole.Warn('Newsroom: Failed to store newsroom messages into local storage. Reason: ' + e);
				bSuccess = false;
			}
			return bSuccess;
		},
		_getCachedData: function(idxProvider)
		{
			var sKey = this._makeCacheKey(idxProvider);
			var sData = localStorage.getItem(sKey);
			if (sData == null) return null; // No entry in the local storage cache
			try
			{
				var oData = JSON.parse(sData);
				var oExpiration = new Date(oData.expiration_date);
				var oNow = new Date();
				if (oExpiration < oNow)
				{
					return null;
				}
				return JSON.parse(oData.value);
			}
			catch(e)
			{
				CombodoJSConsole.Warn('Newsroom: Failed to fetch newsroom messages from local storage. Reason: '+e);
				this.clearCache(idxProvider);
				return null;
			}
		},
		_markOneMessageAsRead: function(idxProvider, msgId)
		{
			// Remove the given message from the cache
			var aData = this._getCachedData(idxProvider);
			if (aData !== null)
			{
				var aRemainingData = [];
				for(var k in aData)
				{
					var sId = aData[k].id.toString();
					if(sId !== msgId)
					{
						aRemainingData.push(aData[k]);
					}
				}
				this._cacheData(idxProvider, aRemainingData); // Also extends the TTL of the cache
			}
		},
		_markAllMessagesAsRead: function(idxProvider)
		{
			this._cacheData(idxProvider, []); //Store an empty list in the cache
			
			$.ajax({ type: "GET",
		        	 url: this.options.providers[idxProvider].mark_all_as_read_url,
		        	 async: true,
		        	 dataType : 'jsonp',
		        	 crossDomain: true,
		        	 jsonp: "callback"
		     })
		     .done(function(oJSONData) {
		    });
			
			
		},
		_markAllAsRead: function()
		{
			for(var k in this.options.providers)
			{
				this._markAllMessagesAsRead(k);
			}
			this._getAllMessages();
		}
	});	
});
