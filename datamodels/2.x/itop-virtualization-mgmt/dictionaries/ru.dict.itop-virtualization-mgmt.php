<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
//
// Fieldsets for Virtualization classes
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Virtualization:baseinfo' => 'Общее',
	'Virtualization:moreinfo' => 'Особенности виртуализации',
	'Virtualization:otherinfo' => 'Даты и описание',
]);

//
// Class Cloud
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:Cloud/Name' => '%1$s',
	'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
	'Class:Cloud' => 'Облако',
	'Class:Cloud+' => 'Виртуальный хост, управляемый облачным провайдером. Может размещать виртуальные машины и хосты контейнеров.',
	'Class:Cloud/Attribute:logo' => 'Логотип',
	'Class:Cloud/Attribute:logo+' => 'Используется как иконка объекта на графах анализа влияния',
	'Class:Cloud/Attribute:provider_id+' => 'Кто предоставляет облако',
	'Class:Cloud/Attribute:location_id' => 'Местоположение',
	'Class:Cloud/Attribute:location_id+' => 'Где расположено облако',
]);

//
// Class: LogicalInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:LogicalInterface/Attribute:org_id' => 'Организация',
	'Class:LogicalInterface/Attribute:org_id+' => '',
]);
