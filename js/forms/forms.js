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

function addFormToCollection(e) {
	const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
	const item = document.createElement('div');

	item.style.marginTop = '20px';
	item.innerHTML = collectionHolder
		.dataset
		.prototype
		.replace(
			/__name__/g,
			collectionHolder.dataset.index
		);

	collectionHolder.appendChild(item);
	collectionHolder.dataset.index++;
	console.log(collectionHolder.dataset.index);
}