/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

$(function()
{
    $.widget( 'itop.tab_container',
        {
            // default options
            options:
            {
	            remote_tab_load_dict: 'Click to load',
            },
            css_classes:
            {
            	is_hidden: 'ibo-is-hidden',
            	is_disabled: 'ibo-is-disabled',
	            is_transparent: 'ibo-is-transparent',
	            is_opaque: 'ibo-is-opaque',
	            is_scrollable: 'ibo-is-scrollable',
	            tab_container: 'ibo-tab-container',
            },
            js_selectors:
            {
                tabs_list: '[data-role="ibo-tab-container--tabs-list"]',
                tab_header: '[data-role="ibo-tab-container--tab-header"]',
                tab_ajax_type: '[data-tab-type="ajax"]',
                tab_html_type: '[data-tab-type="html"]',
                tab_toggler: '[data-role="ibo-tab-container--tab-toggler"]',
                extra_tabs_container: '[data-role="ibo-tab-container--extra-tabs-container"]',
                extra_tabs_list_toggler: '[data-role="ibo-tab-container--extra-tabs-list-toggler"]',
                extra_tabs_list: '[data-role="ibo-tab-container--extra-tabs-list"]',
                extra_tab_toggler: '[data-role="ibo-tab-container--extra-tab-toggler"]',
	            global: {
		            fullscreen_elements: '.ibo-is-fullscreen',
	            },
            },

            // the constructor
            _create: function()
            {
	            var me = this;
	            this.element.addClass(this.css_classes.tab_container);

	            // Ugly patch for a change in the behavior of jQuery UI:
	            // Before jQuery UI 1.9, tabs were always considered as "local" (opposed to Ajax)
	            // when their href was beginning by #. Starting with 1.9, a <base> tag in the page
	            // is taken into account and causes "local" tabs to be considered as Ajax
	            // unless their URL is equal to the URL of the page...
	            if ($('base').length > 0) {
		            this.element.find(this.js_selectors.tab_toggler).each(function () {
			            const sHash = location.hash;
			            const sCleanLocation = location.href.toString().replace(sHash, '').replace(/#$/, '');
			            $(this).attr('href', sCleanLocation+$(this).attr('href'));
		            });
	            }

	            let oTabsParams = {
		            classes: {
			            'ui-tabs-panel': 'ibo-tab-container--tab-container',    // For ajax tabs, so their containers have the right CSS classes
		            },
		            // There we want a number as it is for the jQuery Tabs API
		            active: this._getTabIndexFromTabId($.bbq.getState(this.element.attr('id'), true)) || 0,
		            remote_tab_load_dict: this.options.remote_tab_load_dict
	            };
	            if ($.bbq) {
		            // Enable tabs on all tab widgets. The `event` property must be overridden so
		            // that the tabs aren't changed on click, and any custom event name can be
		            // specified. Note that if you define a callback for the 'select' event, it
		            // will be executed for the selected tab whenever the hash changes.
		            oTabsParams['event'] = 'change';
	            }
	            
	            // While our tab widget is loading, protect tab toggler from being triggered
	            this.element.find(this.js_selectors.tab_toggler).on('click', function(e){
		            if(me.element.attr('data-status') === 'loading') {
			            e.preventDefault();
			            e.stopImmediatePropagation();
		            }
	            });
	            // Now that we are protected from toggler being triggered without tab container being loaded, we can put back
	            // data-target attribute value back into href attribute
	            $.each(this.element.find(this.js_selectors.tab_header + this.js_selectors.tab_ajax_type), function (a){
	            	let oLink = $(this).find(me.js_selectors.tab_toggler);
		            oLink.attr('href', oLink.attr('data-target'));
	            })
	            
	            this._addTabsWidget(oTabsParams);

	            this._bindEvents()
	            
	            // We're done, set our status as loaded
	            this.element.attr('data-status', 'loaded');
            },
	        /**
	         * @param oParams {Object} Structured object representing the options for the jQuery UI Tabs widget
	         * @private
	         */
	        _addTabsWidget: function (oParams) {
		        if (this.element.hasClass(this.css_classes.is_scrollable)) {
			        this.element.scrollabletabs(oParams);
		        } else {
			        this.element.regulartabs(oParams);
		        }
	        },
	        /**
	         * Return tabs widget instance
	         * @public
	         */
	        GetTabsWidget: function () {
		        if (this.element.hasClass(this.css_classes.is_scrollable)) {
			        return this.element.scrollabletabs('instance');
		        } else {
			        return this.element.regulartabs('instance');
		        }
	        },
            // events bound via _bind are removed automatically
            // revert other modifications here
            _destroy: function()
            {
                this.element.removeClass(this.css_classes.tab_container);
            },
            _bindEvents: function()
            {
                const me = this;

                // Bind an event on tab activation
                this.element.on('tabsactivate', function(oEvent, oUI){
                    me._onTabActivated(oUI);
                });
	            this.element.on('tabscrolled', function(oEvent, oUI){
		            me._onTabActivated(oUI);
	            });
                // Bind an event to window.onhashchange that, when the history state changes,
                // iterates over all tab widgets, changing the current tab as necessary.
                $(window).on('hashchange', function(){
                    me._onHashChange();
                });
                // Click on tab togglers
                this.element.find(this.js_selectors.tab_toggler).on('click', function(){
                    me._onTabTogglerClick($(this));
                });
                // Resize of the tab container
                if(window.IntersectionObserver) {
                	const oTabsListIntersectObs = new IntersectionObserver(function(aEntries, oTabsListIntersectObs){
						// N°4631 - If a non-intersecting element is fullscreen, we do nothing
						if ($(me.js_selectors.global.fullscreen_elements).length > 0) {
							return;
						}
                		aEntries.forEach(oEntry => {
                			let oTabHeaderElem = $(oEntry.target);
                			let bIsVisible = oEntry.isIntersecting;
			                if(bIsVisible) {
				                oTabHeaderElem.removeClass(me.css_classes.is_transparent);
				                oTabHeaderElem.css('visibility', '');
			                }
			                else {
				                oTabHeaderElem.removeClass(me.css_classes.is_transparent);
				                // This is necessary, otherwise link will still be clickable
				                oTabHeaderElem.css('visibility', 'hidden');
			                }
			                me._updateTabHeaderDisplay(oTabHeaderElem, bIsVisible);
		                });
                		me._updateExtraTabsList();
	                }, {
                		root: this.element.find(this.js_selectors.tabs_list)[0],
		                threshold: [0.9] // N°4783 Should be completely visible, but lowering the threshold prevents a bug in the JS Observer API when the window is zoomed in/out, in which case all items respond as being hidden even when they are not.
	                });
                	this.element.find(this.js_selectors.tab_header).each(function(){
		                oTabsListIntersectObs.observe(this);
	                });
                }
                // Click on extra tabs list toggler
                this.element.find(this.js_selectors.extra_tabs_list_toggler).on('click', function(oEvent){
                    me._onExtraTabsListTogglerClick($(this), oEvent);
                });
                // Click on "extra tab togglers"
                this.element.find(this.js_selectors.extra_tab_toggler).on('click', function(oEvent){
	                me._onExtraTabTogglerClick($(this), oEvent);
                });
                // Mostly for outside clicks that should close elements
                $('body').on('click', function(oEvent){
                    me._onBodyClick(oEvent);
                });
            },

            // Events callbacks
            // - Update tab headers display on container resize
            _onTabContainerResize: function()
            {
                const me = this;
                this.element.find(this.js_selectors.tab_header).each(function(){
                	me._updateTabHeaderDisplay($(this));
                });
                this._updateExtraTabsList();
            },
            // - Update URL hash when tab is activated
            _onTabActivated: function(oUI)
            {
	            let oState = {};

	            // Get the id of this tab widget.
	            const sId = this.element.attr('id');

	            //Datatable are not displayed correctly when hidden
	            $(oUI.newPanel).find('.dataTables_scrollBody > .ibo-datatable').each(function () {
		            $('#'+this.id).DataTable().columns.adjust().draw();
	            });

	            // Get the ID of this tab.
	            const sTabId = $(oUI.newTab).attr('data-tab-id');

	            // Set the state!
	            oState[sId] = sTabId;
	            $.bbq.pushState(oState);
            },
	        // - Change current tab as necessary when URL hash changes
	        _onHashChange: function () {
		        // Get the index for this tab widget from the hash, based on the
		        // appropriate id property. In jQuery 1.4, you should use e.getState()
		        // instead of $.bbq.getState(). The second, 'true' argument coerces the
		        // string value to a number.
		        const sTabId = $.bbq.getState(this.element.attr('id'), true) || this._getTabIdFromTabIndex(0);

		        // Select the appropriate tab for this tab widget by triggering the custom
		        // event specified in the .tabs() init above (you could keep track of what
		        // tab each widget is on using .data, and only select a tab if it has
		        // changed).
		        this.element.children(this.js_selectors.tabs_list).children(this.js_selectors.tab_header+'[data-tab-id="'+sTabId+'"]').find(this.js_selectors.tab_toggler).triggerHandler('change');

		        // Iterate over all truncated lists to find whether they are expanded or not
		        $('a.truncated').each(function () {
			        const sState = $.bbq.getState(this.id, true) || 'close';
			        if (sState === 'open') {
				        $(this).trigger('open');
			        } else {
				        $(this).trigger('close');
			        }
		        });
	        },
	        // - Define our own click handler for the tabs, overriding the default.
	        _onTabTogglerClick: function (oTabHeaderElem) {
		        if ($.bbq) {
			        let oState = {};

			        // Get the id of this tab widget.
			        const sId = this.element.attr('id');

			        // Get the index of this tab.
			        const sTabId = oTabHeaderElem.closest(this.js_selectors.tab_header).attr('data-tab-id');

			        // Set the state!
			        oState[sId] = sTabId;
			        $.bbq.pushState(oState);
		        }
	        },
	        // - Forward click event to real tab toggler
	        _onExtraTabTogglerClick: function (oExtraTabTogglerElem, oEvent) {
		        // Prevent anchor default behaviour
		        oEvent.preventDefault();

		        if (oExtraTabTogglerElem.attr('aria-disabled') === 'true') {
		            // Corresponding tab is disabled, do nothing			
		            oEvent.stopPropagation();
		            return;
		        }			
		        // Trigger click event on real tab toggler (the hidden one)
		        const sTargetTabId = oExtraTabTogglerElem.attr('href').replace(/#/, '');
		        this.element.find(this.js_selectors.tab_header+'[data-tab-id="'+sTargetTabId+'"] '+this.js_selectors.tab_toggler).trigger('click');
	        },
            // - Toggle extra tabs list
            _onExtraTabsListTogglerClick: function(oElem, oEvent)
            {
                // Prevent anchor default behaviour
                oEvent.preventDefault();

				// Compute list position
	            // Note: Arbitrary +6px for the position as we don't want it to be exactly against the toggler
	            let fTopOffset = this.element.find(this.js_selectors.extra_tabs_list_toggler).offset().top + this.element.find(this.js_selectors.extra_tabs_list_toggler).outerHeight() + 6;
				// We need to compute position from the right side of the screen because at this time the list isn't visible and we can't know its width, so we can't position it regarding the left side of the screen
	            // Note: We use window.innerWidth instead of outerWidth as we need the width of the actual viewport, not the OS browser window
				let fRightOffset = window.innerWidth - this.element.find(this.js_selectors.extra_tabs_list_toggler).offset().left - this.element.find(this.js_selectors.extra_tabs_list_toggler).outerWidth();
	            this.element.find(this.js_selectors.extra_tabs_list)
		            .css('top', fTopOffset + 'px')
		            .css('right', fRightOffset + 'px');

                // TODO 3.0.0: Should/could we use a popover menu instead here?
                this.element.find(this.js_selectors.extra_tabs_list).toggleClass(this.css_classes.is_hidden);
            },
            _onBodyClick: function(oEvent)
            {
                // Close extra tabs list if opened
                if($(oEvent.target.closest(this.js_selectors.extra_tabs_container)).length === 0)
                {
                    this.element.find(this.js_selectors.extra_tabs_list).addClass(this.css_classes.is_hidden);
                }
            },

            // Helpers
            /**
             * Update tab header display based on its visibility to the user
             *
             * @param oTabHeaderElem {Object} jQuery element
             * @param bIsVisible {boolean|null} If null, visibility will be computed automatically. Not that performance might not be great so it's preferable to pass the value when known
             * @private
             */
            _updateTabHeaderDisplay(oTabHeaderElem, bIsVisible = null)
            {
            	const sTabId = oTabHeaderElem.attr('data-tab-id');
            	const oMatchingExtraTabElem = this.element.find(this.js_selectors.extra_tab_toggler+'[href="#'+sTabId+'"]');

                // Disabled tabs should be disabled in the ExtraTabs list as well
                let bIsDisabled = false;
                if (oTabHeaderElem.attr('aria-disabled') === 'true') {
                    bIsDisabled = true;
                }
            	// Manually check if the tab header is visible if the info isn't passed
            	if (bIsVisible === null) {
                    bIsVisible = CombodoGlobalToolbox.IsElementVisibleToTheUser(oTabHeaderElem[0], true, 2);
            	}

            	// Hide/show the corresponding extra tab element
            	if (bIsVisible) {
                    oMatchingExtraTabElem.addClass(this.css_classes.is_hidden);
            	} else {
                    oMatchingExtraTabElem.removeClass(this.css_classes.is_hidden);
            	}
            	// Enable/disable the corresponding extra tab element
            	if (bIsDisabled) {
                    oMatchingExtraTabElem.attr('aria-disabled', 'true');
                    oMatchingExtraTabElem.addClass(this.css_classes.is_disabled);
            	} else {
                    oMatchingExtraTabElem.attr('aria-disabled', 'false');
                     oMatchingExtraTabElem.removeClass(this.css_classes.is_disabled);
            	}
            },
	        // - Update extra tabs list
	        _updateExtraTabsList: function () {
		        const iVisibleExtraTabsCount = this.element.find(this.js_selectors.extra_tab_toggler+':not(.'+this.css_classes.is_hidden+')').length;
		        const oExtraTabsContainerElem = this.element.find(this.js_selectors.extra_tabs_container);

		        if (iVisibleExtraTabsCount > 0) {
			        oExtraTabsContainerElem.removeClass(this.css_classes.is_hidden);
		        } else {
			        oExtraTabsContainerElem.addClass(this.css_classes.is_hidden);
		        }
	        },
	        // - Get tab's "data-tab-id" from tab's index
	        /**
	         * @param iIdx {number} Index (starting from 0) of the tab in the container
	         * @return {string} The [data-tab-id] of the iIdx-th tab (zero based). Can return undefined if it has not [data-tab-id] attribute
	         * @private
	         */
	        _getTabIdFromTabIndex: function(iIdx) {
		        return this.element.children(this.js_selectors.tabs_list).children(this.js_selectors.tab_header).eq(iIdx).attr('data-tab-id');
	        },
	        /**
	         * @param sId {string} The [data-tab-id] of the tab
	         * @return {number} The index (zero based) of the tab. If no matching tab, 0 will be returned.
	         * @private
	         */
	        _getTabIndexFromTabId: function(sId) {
		        const oTabElem = this.element.children(this.js_selectors.tabs_list).children(this.js_selectors.tab_header+'[data-tab-id="'+sId+'"]');

		        return oTabElem.length === 0 ? 0 : oTabElem.prevAll().length;
	        },
            /**
             * @param sId {string} The [data-tab-id] of the tab
             * @return {Object} The jQuery object representing the tab element
             *
             * @private
             */
            _getTabElementFromTabId: function(sId) {
                return this.element.children(this.js_selectors.tabs_list).children(this.js_selectors.tab_header+'[data-tab-id="'+sId+'"]');
            },
            /**
             * @param sId {string} The [data-tab-id] of the tab
             * @return {Object} The jQuery object representing the tab element
             */
            disableTab: function(sId){
               const tabsWidget = this.GetTabsWidget();
               const iIdx = this._getTabIndexFromTabId(sId);
               tabsWidget.disable(iIdx);
               const tabElement = this._getTabElementFromTabId(sId);
               this._updateTabHeaderDisplay(tabElement); 
            },
            /**
             * @param sId {string} The [data-tab-id] of the tab
             * @return {Object} The jQuery object representing the tab element
             */
            enableTab: function(sId){
               const tabsWidget = this.GetTabsWidget();
               const iIdx = this._getTabIndexFromTabId(sId);
               tabsWidget.enable(iIdx);
               const tabElement = this._getTabElementFromTabId(sId);
               this._updateTabHeaderDisplay(tabElement);                
            }
        });
});
