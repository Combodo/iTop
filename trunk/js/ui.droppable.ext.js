(function($) {
	
	// options.activeClass
	$.ui.plugin.add("droppable", "activate", "activeClass", function(e,ui) {
		$(this).addClass(ui.options.activeClass);
	});
	$.ui.plugin.add("droppable", "deactivate", "activeClass", function(e,ui) {
		$(this).removeClass(ui.options.activeClass);
	});
	$.ui.plugin.add("droppable", "drop", "activeClass", function(e,ui) {
		$(this).removeClass(ui.options.activeClass);
	});

	// options.hoverClass
	$.ui.plugin.add("droppable", "over", "hoverClass", function(e,ui) {
		$(this).addClass(ui.options.hoverClass);
	});
	$.ui.plugin.add("droppable", "out", "hoverClass", function(e,ui) {
		$(this).removeClass(ui.options.hoverClass);
	});
	$.ui.plugin.add("droppable", "drop", "hoverClass", function(e,ui) {
		$(this).removeClass(ui.options.hoverClass);
	});

})(jQuery);
