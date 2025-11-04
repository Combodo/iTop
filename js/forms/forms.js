/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */



function triggerTurbo(el) {
	let sFormName = el.form.getAttribute("name");
	el.form.querySelector(`[name="${sFormName}[_turbo_trigger]"]`).value = el.getAttribute('name');
	el.form.setAttribute('novalidate', true);
	el.form.requestSubmit();
	console.log('Auto submitting form due to change in field ' + el.getAttribute('name'));
}