$.widget( "itop.scrollabletabs", $.ui.tabs, {
	js_selectors:
		{
			tab_toggler: '[data-role="ibo-tab-container--tab-toggler"]',
			tab_container_list: '[data-role="ibo-tab-container--tab-container-list"]'
		},
	controller: null,
	_create: function() {
		var me = this;

		// Initialize a single controller for this tab container
		this.controller = new ScrollMagic.Controller({'container': '#' + this.element.find(this.js_selectors.tab_container_list).attr('id'), 'refreshInterval' : 200});
		
		// Add remote tabs to controller after they are loaded
		var afterloadajax = function (a, b)
		{
			me._newScene(b.tab, b.panel).addTo(me.controller);
		};
		this.element.on('scrollabletabsload', afterloadajax);
		
		this._super();

		// Load remote tabs as soon as possible
		$(this.js_selectors.tab_toggler).each(function() {
			var that = this;
			if($(that).attr('href').charAt(0) !== '#') {
				var index = $(this).parent('li').prevAll().length
				me.load(index);
			}
		});
		
		// Add every other tab to the controller 
		$(this.js_selectors.tab_toggler).each(function(){
			var that = this;

			if($(that).attr('href').charAt(0) === '#') {
				me._newScene($(that).parent('li'), $($(that).attr('href'))).addTo(me.controller);
			}
		});

	},
	// Create a new scene to be added to the controller
	_newScene: function(tab, panel)
	{
		var me = this;
		var iPanelId = panel.attr('id');
		return new ScrollMagic.Scene({
			triggerElement: '#' + iPanelId,
			triggerHook: 0.03, // show, when scrolled 10% into view
			duration: function () {
				return $('#' + iPanelId).outerHeight();
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
	
	// Set the current tab information 
	setTab : function(tab){
		this.active = tab;
	},
});