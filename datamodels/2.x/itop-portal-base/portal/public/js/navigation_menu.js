$(document).ready(function()
{
	$('body').on('click', '[data-role="ipb-navigation-menu--toggler"]', function (oEvent) {
		$('[data-role="ipb-navigation-menu"]').toggleClass('ipb-is-expanded');
	});
});