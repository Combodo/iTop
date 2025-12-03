/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

$aFormBlockDataTransmittedData = {};

function triggerTurbo(el) {

	const name = el.getAttribute('name');

	if(isCheckbox(el) || $aFormBlockDataTransmittedData[name] !== el.value) {
		let sFormName = el.form.getAttribute("name");
		el.form.querySelector(`[name="${sFormName}[_turbo_trigger]"]`).value = el.getAttribute('name');
		el.form.setAttribute('novalidate', true);
		el.form.requestSubmit();
		el.form.querySelector(`[name="${sFormName}[_turbo_trigger]"]`).value = null;
		el.form.setAttribute('novalidate', false);
		$aFormBlockDataTransmittedData[name] = el.value;
	}

}

function isCheckbox (element) {
	return element instanceof HTMLInputElement
		&& element.getAttribute('type') === 'checkbox'
}

