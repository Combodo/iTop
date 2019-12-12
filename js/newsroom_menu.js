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
				'no_message': 'No new message',
				'mark_all_as_read': 'Mark all as read',
				'view_all': 'View all messages'
			}
		},
	
		// the constructor
		_create: function()
		{
			var me = this;
			this.aMessageByProvider = [];

			this.element
			.addClass('itop-newsroom_menu');
			
			this._load();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-newsroom_menu');
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
			setTimeout(function() { me._getAllMessages(); }, 1000);
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
		    }).error(function() {
		    	 console.warn('Newsroom: failed to fetch data from the web for provider '+idx+' url: '+me.options.providers[idxProvider].fetch_url);
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
			
			this._buildMenu(aAllMessages);
		},
		_buildMenu: function(aAllMessages)
		{
			var me = this;
			var iTotalCount = aAllMessages.length;
			var iCount = 0;
			var sHtml = '<span id="newsroom_menu" class="itop_popup toolkit_menu"><ul><li><i id="newsroom_menu_icon" class="top-right-icon icon-additional-arrow '+this.options.image_icon+'"></i><ul>';
			sHtml += '<li class="newsroom_menu_item" id="newsroom_menu_dismiss_all"><i class="fas fa-fw fa-check"></i>'+this.options.labels.mark_all_as_read+'</li>';
			moment.locale(GetUserLanguage());
			var aUnreadMessagesByProvider = [];
			for(var k in this.options.providers)
			{
				aUnreadMessagesByProvider[k] = 0;
			}
			for(var k in aAllMessages)
			{
				var oMessage = aAllMessages[k];
				aUnreadMessagesByProvider[oMessage.provider]++;
				if (iCount < this.options.display_limit)
				{
					var sImage = '';
					if ((oMessage.image !== undefined) && (oMessage.image !== null))
					{
						sImage = '<img src="'+oMessage.image+'">';
					}
					else
					{
						sImage = '<i class="'+this.options.placeholder_image_icon+'"></i>';
					}				
					var div = document.createElement("div");
					div.textContent = oMessage.text;
					var sDescription = div.innerHTML; // Escape HTML entities for XSS prevention
					var converter = new showdown.Converter({noHeaderId: true});
				    var sRichDescription = converter.makeHtml(sDescription);
				    sRichDescription += '<span class="newsroom_menu_item_date">'+this.options.providers[oMessage.provider].label+' - '+moment(oMessage.start_date).fromNow()+'</span>';
					sHtml += '<li class="newsroom_menu_item" data-msg-id="'+oMessage.id+'" data-provider-id="'+oMessage.provider+'" data-url="'+oMessage.url+'" id="newsroom_menu_item_'+oMessage.id+'"><div>'+sImage+'<p>'+sRichDescription+'</p><div style="clear:both"></div></div></li>';
				}
				iCount++;
			}
			if (iCount == 0)
			{
				sHtml += '<li class="newsroom_menu_item" id="newsroom_no_new_message"><div><p>'+this.options.labels.no_message+'</p><div style="clear:both"></div></div></li>';				
			}
			if (this.options.providers.length == 1)
			{
				sHtml += '<li class="newsroom_menu_item" id="newsroom_menu_show_all">'+this.options.labels.view_all+'</li>';				
			}
			else
			{
				sHtml += '<li class="no-padding"><span id="newsroom_show_all_submenu" class="itop_popup toolkit_menu"><ul><li>'+this.options.labels.view_all+'&nbsp;▾<ul>';
				for(k in this.options.providers)
				{
					var sExtraMessages = '';
					if (aUnreadMessagesByProvider[k] > 0)
					{
						sExtraMessages = ' <span class="newsroom_extra_messages_counter">'+aUnreadMessagesByProvider[k]+'</span>'
					}
					sHtml += '<li class="newsroom_sub_menu_item" data-provider-id="'+k+'">'+this.options.providers[k].label+sExtraMessages+'</li>';
				}
				sHtml += '</ul></li></ul></li></ul></span>';
			}
			if (iCount > 0)
			{
				sHtml += '</ul></li></ul></span><div id="newsroom_menu_counter_container"><span id="newsroom_menu_counter">'+iTotalCount+'</span></div></span>';
				$(this.element).html(sHtml);
				var me = this;
				$('#newsroom_menu > ul').popupmenu();
				$('#newsroom_menu_counter').on('click', function() {setTimeout(function(){ $('#newsroom_menu_icon').trigger('click') }, 10);});
				$('.newsroom_menu_item[data-msg-id]').on('click', function(ev) { me._handleClick(this); });
				$('#newsroom_menu_dismiss_all').on('click', function(ev) { me._markAllAsRead(); });
				if (this.options.providers.length == 1)
				{
					$('#newsroom_menu_show_all').on('click', function(ev) { window.open(me.options.providers[0].view_all_url, '_blank'); });
				}
				else
				{
					$('#newsroom_show_all_submenu > ul').popupmenu();
					$('.newsroom_sub_menu_item').on('click', function() { var idx = parseInt($(this).attr('data-provider-id'), 10); window.open(me.options.providers[idx].view_all_url, '_blank');});
				}
			}
			else
			{
				sHtml += '</ul></li></ul></span><div id="newsroom_menu_counter_container"><span id="newsroom_menu_counter" style="visibility:hidden"></span></div></span>';
				$(this.element).html(sHtml);
				$('#newsroom_menu_dismiss_all').remove();
				var me = this;
				$('#newsroom_menu > ul').popupmenu();
				$('#top-left-newsroom-cell > img').attr('title', this.options.labels.no_message);
				if (this.options.providers.length == 1)
				{
					$('#newsroom_menu_show_all').on('click', function(ev) { window.open(me.options.providers[0].view_all_url, '_blank'); });
				}
				else
				{
					$('#newsroom_show_all_submenu > ul').popupmenu();
					$('.newsroom_sub_menu_item').on('click', function() { var idx = parseInt($(this).attr('data-provider-id'), 10); window.open(me.options.providers[idx].view_all_url, '_blank');});
				}
			}
			
		},
		_handleClick: function(elem)
		{
			var idxProvider = $(elem).attr('data-provider-id');
			var msgId = $(elem).attr('data-msg-id');
			var sUrl = $(elem).attr('data-url');
			
			this._markOneMessageAsRead(idxProvider, msgId);
			window.open(sUrl, '_blank');
			$('#newsroom_menu').remove();
			$('#newsroom_menu_counter_container').remove();
			this._getAllMessages();
		},
		_resetUnseenCount: function()
		{
			var display = $('#newsroom_menu_counter').css('display');
			$('#newsroom_menu_counter').fadeOut(500, function() {
				   $(this).css('visibility', 'hidden'); 
				   $(this).css('display', display);
				});
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
				console.warn('Newsroom: Failed to store newsroom messages into local storage !! reason: '+e);
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
				console.warn('Newsroom: Failed to fetch newsroom messages from local storage !! reason: '+e);
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
			$('#newsroom_menu').html('<i class="top-right-icon '+this.options.image_icon+'" style="opacity:0.4" title="'+this.options.labels.no_message+'"></i>');
			$('#newsroom_menu_counter_container').remove();
			this._getAllMessages();
		}
	});	
});
