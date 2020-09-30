
$(function()
{
    $.widget( 'itop.tab_container',
        {
            // default options
            options:
            {
            },
            css_classes:
            {
            },
            js_selectors:
            {
                tabs_list: '[data-role="ibo-tab-container--tabs-list"]',
                tab_header: '[data-role="ibo-tab-container--tab-header"]',
                tab_toggler: '[data-role="ibo-tab-container--tab-toggler"]',
            },

            // the constructor
            _create: function()
            {
                this.element.addClass('ibo-tab-container');

                // Ugly patch for a change in the behavior of jQuery UI:
                // Before jQuery UI 1.9, tabs were always considered as "local" (opposed to Ajax)
                // when their href was beginning by #. Starting with 1.9, a <base> tag in the page
                // is taken into account and causes "local" tabs to be considered as Ajax
                // unless their URL is equal to the URL of the page...
                if ($('base').length > 0) {
                    this.element.find(this.js_selectors.tab_toggler).each(function () {
                        const sHash = location.hash;
                        const sCleanLocation = location.href.toString().replace(sHash, '').replace(/#$/, '');
                        $(this).attr('href', sCleanLocation + $(this).attr('href'));
                    });
                }

                if ($.bbq) {
                    // This selector will be reused when selecting actual tab widget A elements.
                    const sTabAnchorSelector = 'ul.ui-tabs-nav a';

                    // Enable tabs on all tab widgets. The `event` property must be overridden so
                    // that the tabs aren't changed on click, and any custom event name can be
                    // specified. Note that if you define a callback for the 'select' event, it
                    // will be executed for the selected tab whenever the hash changes.
                    this.element.tabs({event: 'change'});
                } else {
                    this.element.tabs();
                }

                this._bindEvents();
            },
            // events bound via _bind are removed automatically
            // revert other modifications here
            _destroy: function()
            {
                this.element.removeClass('ibo-tab-container');
            },
            _bindEvents: function()
            {
                const me = this;

                // Bind an event on tab activation
                this.element.on('tabsactivate', function(oEvent, oUI){
                    me._onTabActivated(oUI);
                });
                // Bind an event to window.onhashchange that, when the history state changes,
                // iterates over all tab widgets, changing the current tab as necessary.
                $(window).on('hashchange', function(){
                    me._onHashChange();
                });
                // Define our own click handler for the tabs, overriding the default.
                this.element.find(this.js_selectors.tab_toggler).on('click', function(){
                    me._onTogglerClick($(this));
                });
            },

            // Events callbacks
            // - Update URL hash when tab is activated
            _onTabActivated: function(oUI)
            {
                let oState = {};

                // Get the id of this tab widget.
                const sId = this.element.attr( 'id' );

                // Get the index of this tab.
                const iIdx = $(oUI.newTab).prevAll().length;

                // Set the state!
                oState[ sId ] = iIdx;
                $.bbq.pushState( oState );
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
            _onTogglerClick: function(oTabHeaderElem)
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
            }
        });
});
