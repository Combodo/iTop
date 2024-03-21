$('body').on('change', '.ibo-toggler', function() {
	$('.ibo-notifications--view-all--bulk-buttons').toggleClass('ibo-is-hidden');
	$('.ibo-object-summary').toggleClass('ibo-is-selectable').removeClass('ibo-is-selected');
});

$('body').on('click', '.ibo-object-summary.ibo-is-selectable', function() {
	$(this).toggleClass('ibo-is-selected');
});

$('body').on('itop.notification.deleted', '.ibo-notifications--view-all--container', function() {
	if($(this).find('.ibo-object-summary').length === 0) {
		$('.ibo-notifications--view-all--empty').removeClass('ibo-is-hidden');
		$('.ibo-notifications--view-all--container').addClass('ibo-is-hidden');
		$('.ibo-notifications--view-all--read-action').attr('disabled', 'disabled');
		$('.ibo-notifications--view-all--unread-action').attr('disabled', 'disabled');
		$('.ibo-notifications--view-all--delete-action').attr('disabled', 'disabled');
	}

	$('#ibo-navigation-menu--notifications-menu').newsroom_menu("clearCache");
});

let fReadUnreadDisabled = function() {
	if($('.ibo-object-summary.ibo-notifications--view-all--item--unread').length === 0) {
		$('.ibo-notifications--view-all--read-action').attr('disabled', 'disabled');
		$('.ibo-notifications--view-all--unread-action').removeAttr('disabled');
	} else if ($('.ibo-object-summary.ibo-notifications--view-all--item--read').length === 0) {
		$('.ibo-notifications--view-all--read-action').removeAttr('disabled');
		$('.ibo-notifications--view-all--unread-action').attr('disabled', 'disabled');
	} else {
		$('.ibo-notifications--view-all--read-action').removeAttr('disabled');
		$('.ibo-notifications--view-all--unread-action').removeAttr('disabled');
	}

	$('#ibo-navigation-menu--notifications-menu').newsroom_menu("clearCache");
}

$('body').on('itop.notification.read itop.notification.unread', '.ibo-notifications--view-all--container', fReadUnreadDisabled);

$('body').on('itop.notification.unread', '.ibo-notifications--view-all--container', fReadUnreadDisabled);