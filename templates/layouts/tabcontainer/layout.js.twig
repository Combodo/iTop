// The "tab widgets" to handle.
var tabs = $('div[id^=tabbedContent]');

// Ugly patch for a change in the behavior of jQuery UI:
// Before jQuery UI 1.9, tabs were always considered as "local" (opposed to Ajax)
// when their href was beginning by #. Starting with 1.9, a <base> tag in the page
// is taken into account and causes "local" tabs to be considered as Ajax
// unless their URL is equal to the URL of the page...
if ($('base').length > 0) {
    $('div[id^=tabbedContent] > ul > li > a').each(function () {
        var sHash = location.hash;
        var sCleanLocation = location.href.toString().replace(sHash, '').replace(/#$/, '');
        $(this).attr("href", sCleanLocation + $(this).attr("href"));
    });
}
if ($.bbq) {
    // This selector will be reused when selecting actual tab widget A elements.
    var tab_a_selector = 'ul.ui-tabs-nav a';

    // Enable tabs on all tab widgets. The `event` property must be overridden so
    // that the tabs aren't changed on click, and any custom event name can be
    // specified. Note that if you define a callback for the 'select' event, it
    // will be executed for the selected tab whenever the hash changes.
    tabs.tabs({event: 'change'});

    // Define our own click handler for the tabs, overriding the default.
    tabs.find(tab_a_selector).click(function () {
        var state = {};

        // Get the id of this tab widget.
        var id = $(this).closest('div[id^=tabbedContent]').attr('id');

        // Get the index of this tab.
        var idx = $(this).parent().prevAll().length;

        // Set the state!
        state[id] = idx;
        $.bbq.pushState(state);
    });
} else {
    tabs.tabs();
}
