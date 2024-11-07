// add X-Combodo-Ajax for all request (just after jaquery is loaded)
// mandatory for ajax requests with JQuery (CSRF protection)
$(document).ajaxSend(function (event, jqxhr, options) {
	jqxhr.setRequestHeader('X-Combodo-Ajax', 'true');
});