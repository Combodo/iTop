$.widget( "itop.scrollabletabs", $.ui.tabs, {
	widgetEventPrefix: 'tabs',
	js_selectors:
		{
			tab_toggler: '[data-role="ibo-tab-container--tab-toggler"]',
			tab_container_list: '[data-role="ibo-tab-container--tab-container-list"]'
		},
	options:
		{
			remote_tab_load_dict: 'Click to load',
			remotePanelCreated: function( panel, tab , placeholder ) {
				if(tab.attr('data-role') === 'ibo-tab-container--tab-header')
				{
					panel.prepend('<div class="ibo-tab-container--tab-container--label"><span>' + tab.text() + '</span></div>');
					let oTempDiv = $('<div>').addClass('ibo-tab--temporary-remote-content')
					let oPlaceholder = $('<div>').addClass('ibo-tab--temporary-remote-content--placeholder').load(tab.attr('data-placeholder'));
					let oLoadButton = $('<div>').addClass('ibo-tab--temporary-remote-content--button').text(placeholder).on('click', function(){tab.find('a').click()})
					oTempDiv.append(oPlaceholder)
					oTempDiv.append(oLoadButton)
					panel.append(oTempDiv);
				}
			},
		},
	// Used keep the beginning of the panel visible when scrolling to it
	scroll_offset_y: null,
	controller: null,
	_create: function() {
		var me = this;
		// Initialize a single controller for this tab container
		this.controller = new ScrollMagic.Controller({'container': this.element.scrollParent()[0], 'refreshInterval' : 200});
		
		// Add remote tabs to controller after they are loaded
		var afterloadajax = function (a, b)
		{
			me._newScene(b.tab, b.panel).addTo(me.controller);
		};
		this.element.on('scrollabletabsload', afterloadajax);

		this._super(this.options);

		// Initialize the vertical scroll offset
		let oFirstPanel = this.element.find('#' + this.tabs.eq(0).attr('data-tab-id'));
		this.scroll_offset_y = oFirstPanel.length > 0 ? oFirstPanel.offset().top : this.element.find(this.js_selectors.tab_container_list).offset().top;
		
		// Add every other tab to the controller 
		$(this.js_selectors.tab_toggler).each(function(){
			var that = this;

			if($(that).attr('href').charAt(0) === '#') {
				me._newScene($(that).parent('li'), $($(that).attr('href'))).addTo(me.controller);
			}
		});
		
		// Set active tab, tab-container gives us a tab based on url hash or 0
		this.setTab(this._findActive(this.options.active));
		// If not on the first tab, we scroll directly to it
		// Note: We don't want to scroll if we are on the first one, otherwise it will look buggy because the page will be a bit scrolled and it doesn't feel right
		if(this.options.active > 0) {
			const oActiveTab = this.tabs.eq(this.options.active);
			const oActivePanel = this.element.find('#' + oActiveTab.attr('data-tab-id'));

			// Remove from scroll length the initial space between the top of the first panel and the top of the screen; this is to avoid scrolling too far
			// That being said, as lists are fetched / updated asynchronously, once they got their responses, the layout will change/shift and the current tab won't be the good one anymore 😕
			// We check if the active panel is loaded as we may try to scroll to it before it is loaded, and it doesn't exist yet
			if(oActivePanel.length > 0) {
				this.controller.scrollTo(oActivePanel.offset().top-this.scroll_offset_y);
			}
		}
	},
	// Create a new scene to be added to the controller
	_newScene: function(tab, panel)
	{
		var me = this;
		var sPanelId = panel.attr('id');
		return new ScrollMagic.Scene({
			triggerElement: '#' + sPanelId,
			triggerHook: 0.2, // show, when scrolled 20% into view
			duration: function () {
				return $('#' + sPanelId).outerHeight();
			}
		})
			.on("enter", function (event) {
				$(tab).addClass("ui-tabs-active ui-state-active");
				$(tab).siblings('li').removeClass("ui-tabs-active ui-state-active");
				me.setTab($(tab));
				me.element.trigger('tabscrolled', [{'newTab': $(tab)}]);
			})
			.on("leave", function (event){
				$(tab).removeClass("ui-tabs-active ui-state-active");
			})
	},
	// jQuery UI overload
	_refresh: function() {
		this._setOptionDisabled( this.options.disabled );
		this._setupEvents( this.options.event );
		this._setupHeightStyle( this.options.heightStyle );

		this.tabs.not( this.active ).attr( {
			"aria-selected": "false",
			"aria-expanded": "false",
			tabIndex: -1
		} );
		this.panels.not( this._getPanelForTab( this.active ) )
			// jQuery UI overload : Do NOT hide panels
			//.hide()
			.attr( {
				"aria-hidden": "true"
			} );

		// Make sure one tab is in the tab order
		if ( !this.active.length ) {
			this.tabs.eq( 0 ).attr( "tabIndex", 0 );
		} else {
			this.active
				.attr( {
					"aria-selected": "true",
					"aria-expanded": "true",
					tabIndex: 0
				} );
			this._addClass( this.active, "ui-tabs-active", "ui-state-active" );
			this._getPanelForTab( this.active )
				.show()
				.attr( {
					"aria-hidden": "false"
				} );
		}
	}, 
// jQuery UI overload
// Handles show/hide for selecting tabs
	_toggle: function( event, eventData ) {
		var that = this,
			toShow = eventData.newPanel,
			toHide = eventData.oldPanel;
		this.running = true;

		function complete() {
			that.running = false;
			// We don't want to trigger activate event in this mode
			//that._trigger( "activate", event, eventData );
		}

		function show() {
			// jQuery UI overload
			//Showing a tab here equals to scrolling to its content.
			//Enter/leave events on scenes handle the active/inactive classes on tabs
			//that._addClass( eventData.newTab.closest( "li" ), "ui-tabs-active", "ui-state-active" );
			if ( toShow.length && that.options.show ) {
				//that._show( toShow, that.options.show, complete );
				that.controller.scrollTo('#' + $(toShow).attr('id'));
			} else {
				that.controller.scrollTo('#' + $(toShow).attr('id'));
				// toShow.show();
				complete();
			}
		}
		
		// jQuery UI overload
		// We just want to scroll to the new tab with our "show" function, nothing more

		// Start out by hiding, then showing, then completing
		// if ( toHide.length && this.options.hide ) {
		//     this._hide( toHide, this.options.hide, function() {
		//         that._removeClass( eventData.oldTab.closest( "li" ),
		//             "ui-tabs-active", "ui-state-active" );
		//         show();
		//     } );
		// } else {
		//     this._removeClass( eventData.oldTab.closest( "li" ),
		//         "ui-tabs-active", "ui-state-active" );
		//     toHide.hide();
		//     show();
		// }
		//this._removeClass( eventData.oldTab.closest( "li" ), "ui-tabs-active", "ui-state-active" );
		show();

		toHide.attr( "aria-hidden", "true" );
		eventData.oldTab.attr( {
			"aria-selected": "false",
			"aria-expanded": "false"
		} );

		// If we're switching tabs, remove the old tab from the tab order.
		// If we're opening from collapsed state, remove the previous tab from the tab order.
		// If we're collapsing, then keep the collapsing tab in the tab order.
		if ( toShow.length && toHide.length ) {
			eventData.oldTab.attr( "tabIndex", -1 );
		} else if ( toShow.length ) {
			this.tabs.filter( function() {
					return $( this ).attr( "tabIndex" ) === 0;
				} )
				.attr( "tabIndex", -1 );
		}

		toShow.attr( "aria-hidden", "false" );
		eventData.newTab.attr( {
			"aria-selected": "true",
			"aria-expanded": "true",
			tabIndex: 0
		} );
	},
	// jQuery UI overload
// Trigger a new event
	_processTabs: function() {
		var that = this,
			prevTabs = this.tabs,
			prevAnchors = this.anchors,
			prevPanels = this.panels;

		this.tablist = this._getList().attr( "role", "tablist" );
		this._addClass( this.tablist, "ui-tabs-nav",
			"ui-helper-reset ui-helper-clearfix ui-widget-header" );

		// Prevent users from focusing disabled tabs via click
		this.tablist
			.on( "mousedown" + this.eventNamespace, "> li", function( event ) {
				if ( $( this ).is( ".ui-state-disabled" ) ) {
					event.preventDefault();
				}
			} )

			// Support: IE <9
			// Preventing the default action in mousedown doesn't prevent IE
			// from focusing the element, so if the anchor gets focused, blur.
			// We don't have to worry about focusing the previously focused
			// element since clicking on a non-focusable element should focus
			// the body anyway.
			.on( "focus" + this.eventNamespace, ".ui-tabs-anchor", function() {
				if ( $( this ).closest( "li" ).is( ".ui-state-disabled" ) ) {
					this.blur();
				}
			} );

		this.tabs = this.tablist.find( "> li:has(a[href])" )
			.attr( {
				role: "tab",
				tabIndex: -1
			} );
		this._addClass( this.tabs, "ui-tabs-tab", "ui-state-default" );

		this.anchors = this.tabs.map( function() {
				return $( "a", this )[ 0 ];
			} )
			.attr( {
				tabIndex: -1
			} );
		this._addClass( this.anchors, "ui-tabs-anchor" );

		this.panels = $();

		this.anchors.each( function( i, anchor ) {
			var selector, panel, panelId,
				anchorId = $( anchor ).uniqueId().attr( "id" ),
				tab = $( anchor ).closest( "li" ),
				originalAriaControls = tab.attr( "aria-controls" );

			// Inline tab
			if ( that._isLocal( anchor ) ) {
				selector = anchor.hash;
				panelId = selector.substring( 1 );
				panel = that.element.find( that._sanitizeSelector( selector ) );

				// remote tab
			} else {

				// If the tab doesn't already have aria-controls,
				// generate an id by using a throw-away element
				panelId = tab.attr( "aria-controls" ) || $( {} ).uniqueId()[ 0 ].id;
				selector = "#" + panelId;
				panel = that.element.find( selector );
				if ( !panel.length ) {
					panel = that._createPanel( panelId );
					// If we can't attach to an other tab, try to get tab-container-list right after
					if( that.panels[ i - 1 ] ) {
						panel.insertAfter( that.panels[ i - 1 ] );
					}
					else if( that.element.find(that.js_selectors.tab_container_list) ) {
						that.element.find(that.js_selectors.tab_container_list).append( panel );
					}
					else {
						panel.insertAfter( that.tablist );

					}
					that.options.remotePanelCreated(panel, tab, that.options.remote_tab_load_dict);
				}
				panel.attr( "aria-live", "polite" );
			}

			if ( panel.length ) {
				that.panels = that.panels.add( panel );
			}
			if ( originalAriaControls ) {
				tab.data( "ui-tabs-aria-controls", originalAriaControls );
			}
			tab.attr( {
				"aria-controls": panelId,
				"aria-labelledby": anchorId
			} );
			panel.attr( "aria-labelledby", anchorId );
		} );

		this.panels.attr( "role", "tabpanel" );
		this._addClass( this.panels, "ui-tabs-panel", "ui-widget-content" );

		// Avoid memory leaks (#10056)
		if ( prevTabs ) {
			this._off( prevTabs.not( this.tabs ) );
			this._off( prevAnchors.not( this.anchors ) );
			this._off( prevPanels.not( this.panels ) );
		}
	},
	// jQuery UI overload
// Append content to panel instead of replacing all html
	load: function( index, event ) {
		index = this._getIndex( index );
		var that = this,
			tab = this.tabs.eq( index ),
			anchor = tab.find( ".ui-tabs-anchor" ),
			panel = this._getPanelForTab( tab ),
			eventData = {
				tab: tab,
				panel: panel
			},
			complete = function( jqXHR, status ) {
				if ( status === "abort" ) {
					that.panels.stop( false, true );
				}

				that._removeClass( tab, "ui-tabs-loading" );
				panel.removeAttr( "aria-busy" );

				if ( jqXHR === that.xhr ) {
					delete that.xhr;
				}
			};

		// Not remote
		if ( this._isLocal( anchor[ 0 ] ) ) {
			return;
		}
		// Remote already loaded
		else if (panel.attr("data-loaded") == "true")
		{
			return
		}

		this.xhr = $.ajax( this._ajaxSettings( anchor, event, eventData ) );

		// Support: jQuery <1.8
		// jQuery <1.8 returns false if the request is canceled in beforeSend,
		// but as of 1.8, $.ajax() always returns a jqXHR object.
		if ( this.xhr && this.xhr.statusText !== "canceled" ) {
			this._addClass( tab, "ui-tabs-loading" );
			panel.attr( "aria-busy", "true" );

			this.xhr
				.done( function( response, status, jqXHR ) {

					// support: jQuery <1.8
					// http://bugs.jquery.com/ticket/11778
					setTimeout( function() {
						var tempdiv = $('<div>').addClass('ibo-tab').html(response);
						panel.find('.ibo-tab--temporary-remote-content').remove();
						panel.append( tempdiv );
						panel.attr( "data-loaded", "true" );
						that._trigger( "load", event, eventData );

						complete( jqXHR, status );
					}, 1 );
				} )
				.fail( function( jqXHR, status ) {

					// support: jQuery <1.8
					// http://bugs.jquery.com/ticket/11778
					setTimeout( function() {
						complete( jqXHR, status );
					}, 1 );
				} );
		}
	},
	// Set the current tab information 
	setTab : function(tab){
		this.active = tab;
	},
	// JQuery UI overload
	disable: function(index){
		const panel = this._getPanelForTab( this.tabs[index] );
		panel.addClass('ibo-is-hidden'); // Do not use .hide() since it alters the tab state
		this._super( index );        
	},
	// JQuery UI overload
	enable: function(index) {
		const panel = this._getPanelForTab( this.tabs[index] );
		panel.removeClass('ibo-is-hidden'); // Do not use .show() since it alters the tab state
		this._super( index );  
	},
});
