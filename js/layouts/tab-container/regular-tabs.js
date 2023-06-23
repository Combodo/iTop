$.widget( "itop.regulartabs", $.ui.tabs, {
	widgetEventPrefix: 'tabs',
	js_selectors:
		{
			tab_container_list: '[data-role="ibo-tab-container--tab-container-list"]'
		},
	options:{
		remotePanelCreated: function( panel, tab ) {
			if(tab.attr('data-role') === 'ibo-tab-container--tab-header')
			{
				panel.prepend('<div class="ibo-tab-container--tab-container--label"><span>' + tab.text() + '</span></div>');
				let oTempDiv = $('<div>').addClass('ibo-tab--temporary-remote-content')
				let oPlaceholder = $('<div>').addClass('ibo-tab--temporary-remote-content--placeholder').load(tab.attr('data-placeholder'));
				oTempDiv.append(oPlaceholder)
				panel.append(oTempDiv);
			}
		},
	},
	// jQuery UI overload
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
	// JQuery UI overload
	disable: function(index){
		const panel = this._getPanelForTab( index );
		panel.addClass('ibo-is-hidden'); // Do not use .hide() since it alters the tab state
		this._super( index );        
	},
	// JQuery UI overload
	enable: function(index) {
		const panel = this._getPanelForTab( index );
		panel.removeClass('ibo-is-hidden'); // Do not use .show() since it alters the tab state
		this._super( index );  
	},
});
