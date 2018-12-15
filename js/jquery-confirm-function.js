function jQueryAlert(title, content, buttonText='OK', theme='light', icon='fa fa-warning', width='25%') {
	$.alert({
		title: title,
		useBootstrap: false,
		boxWidth: width,
		content: content,
		theme: theme,
		type: 'red',
		icon: icon,
		buttons: {
			ok: {
				text: buttonText,
				btnClass: 'btn-blue',
			}
		}
	});	
}
