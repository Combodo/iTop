/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
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
	            is_scrollable: 'ibo-is-scrollable',
	            tab_container: 'ibo-tab-container',
            },
            js_selectors:
            {
                tabs_list: '[data-role="ibo-tab-container--tabs-list"]',
                tab_header: '[data-role="ibo-tab-container--tab-header"]',
                tab_toggler: '[data-role="ibo-tab-container--tab-toggler"]',
                extra_tabs_container: '[data-role="ibo-tab-container--extra-tabs-container"]',
                extra_tabs_list_toggler: '[data-role="ibo-tab-container--extra-tabs-list-toggler"]',
                extra_tabs_list: '[data-role="ibo-tab-container--extra-tabs-list"]',
                extra_tab_toggler: '[data-role="ibo-tab-container--extra-tab-toggler"]',
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
		            active: $.bbq.getState( this.element.attr('id'), true ) || 0,
		            remote_tab_load_dict: this.options.remote_tab_load_dict
	            };
	            if ($.bbq) {
		            // Enable tabs on all tab widgets. The `event` property must be overridden so
		            // that the tabs aren't changed on click, and any custom event name can be
		            // specified. Note that if you define a callback for the 'select' event, it
		            // will be executed for the selected tab whenever the hash changes.
		            oTabsParams['event'] = 'change';
	            }
	            this._addTabsWidget(oTabsParams);


	            this._bindEvents();
            },
	        /**
	         * @param oParams {Object} Structured object representing the options for the jQuery UI Tabs widget
	         * @private
	         */
	        _addTabsWidget: function (oParams) {
		        if (this.element.hasClass(this.css_classes.is_scrollable)) {
			        this.element.scrollabletabs(oParams);
		        } else {
			        this.element.tabs(oParams);
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
                if(window.ResizeObserver)
                {
                    const oTabsListRO = new ResizeObserver(function(){
                        // Note: For a reason I don't understand, when called instantly the sub function CombodoGlobalToolbox.IsElementVisibleToTheUser() won't be able to retrieve an element using the document.elementFromPoint() function
	                    // As it won't return anything, the function always thinks it's invisible...
                        setTimeout(function(){
                            me._onTabContainerResize();
                        }, 200);

                    });
                    oTabsListRO.observe($('.ibo-tab-container--tabs-list')[0]);
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

	            // Get the index of this tab.
	            const iIdx = $(oUI.newTab).prevAll().length;

	            // Set the state!
	            oState[sId] = iIdx;
	            $.bbq.pushState(oState);
            },
            // - Change current tab as necessary when URL hash changes
            _onHashChange: function()
            {
                // Get the index for this tab widget from the hash, based on the
                // appropriate id property. In jQuery 1.4, you should use e.getState()
                // instead of $.bbq.getState(). The second, 'true' argument coerces the
                // string value to a number.
                const iIdx = $.bbq.getState( this.element.attr('id'), true ) || 0;

                // Select the appropriate tab for this tab widget by triggering the custom
                // event specified in the .tabs() init above (you could keep track of what
                // tab each widget is on using .data, and only select a tab if it has
                // changed).
                this.element.find(this.js_selectors.tab_toggler).eq(iIdx).triggerHandler('change');

                // Iterate over all truncated lists to find whether they are expanded or not
                $('a.truncated').each(function()
                {
                    const sState = $.bbq.getState( this.id, true ) || 'close';
                    if (sState === 'open')
                    {
                        $(this).trigger('open');
                    }
                    else
                    {
                        $(this).trigger('close');
                    }
                });
            },
            // - Define our own click handler for the tabs, overriding the default.
            _onTabTogglerClick: function(oTabHeaderElem)
            {
                if ($.bbq) {
                    let oState = {};

                    // Get the id of this tab widget.
                    const sId = this.element.attr('id');

                    // Get the index of this tab.
                    const iIdx = oTabHeaderElem.parent().prevAll().length;

                    // Set the state!
                    oState[sId] = iIdx;
                    $.bbq.pushState(oState);
                }
            },
	        // - Forward click event to real tab toggler
            _onExtraTabTogglerClick: function(oExtraTabTogglerElem, oEvent)
            {
                // Prevent anchor default behaviour
                oEvent.preventDefault();

                // Trigger click event on real tab toggler (the hidden one)
                const sTargetTabId = oExtraTabTogglerElem.attr('href').replace(/#/, '');
                this.element.find(this.js_selectors.tab_header+'[data-tab-id="'+sTargetTabId+'"] '+this.js_selectors.tab_toggler).trigger('click');
            },
            // - Toggle extra tabs list
            _onExtraTabsListTogglerClick: function(oElem, oEvent)
            {
                // Prevent anchor default behaviour
                oEvent.preventDefault();

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
             * @param oTabHeaderElem jQuery element
             * @private
             */
            _updateTabHeaderDisplay(oTabHeaderElem)
            {
            	const sTabId = oTabHeaderElem.attr('data-tab-id');
            	const oMatchingExtraTabElem = this.element.find(this.js_selectors.extra_tab_toggler+'[href="#'+sTabId+'"]');

	            if (!CombodoGlobalToolbox.IsElementVisibleToTheUser(oTabHeaderElem[0], true, 2)) {
		            oMatchingExtraTabElem.removeClass(this.css_classes.is_hidden);
	            } else {
		            oMatchingExtraTabElem.addClass(this.css_classes.is_hidden);
	            }
            },
            // - Update extra tabs list
            _updateExtraTabsList: function()
            {
	            const iVisibleExtraTabsCount = this.element.find(this.js_selectors.extra_tab_toggler+':not(.'+this.css_classes.is_hidden+')').length;
	            const oExtraTabsContainerElem = this.element.find(this.js_selectors.extra_tabs_container);

	            if(iVisibleExtraTabsCount > 0)
	            {
	            	oExtraTabsContainerElem.removeClass(this.css_classes.is_hidden);
	            }
	            else
	            {
	            	oExtraTabsContainerElem.addClass(this.css_classes.is_hidden);
	            }
            }
        });
});
