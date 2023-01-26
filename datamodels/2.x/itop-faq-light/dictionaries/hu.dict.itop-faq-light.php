<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: FAQ
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:FAQ' => 'GyIK',
	'Class:FAQ+' => 'Gyakran Ismételt Kérdések',
	'Class:FAQ/Attribute:title' => 'Cím',
	'Class:FAQ/Attribute:title+' => '~~',
	'Class:FAQ/Attribute:summary' => 'Összefoglaló',
	'Class:FAQ/Attribute:summary+' => '~~',
	'Class:FAQ/Attribute:description' => 'Leírás',
	'Class:FAQ/Attribute:description+' => '~~',
	'Class:FAQ/Attribute:category_id' => 'Kategória',
	'Class:FAQ/Attribute:category_id+' => '~~',
	'Class:FAQ/Attribute:category_name' => 'Kategória név',
	'Class:FAQ/Attribute:category_name+' => '~~',
	'Class:FAQ/Attribute:error_code' => 'Hibakód',
	'Class:FAQ/Attribute:error_code+' => '~~',
	'Class:FAQ/Attribute:key_words' => 'Kulcsszavak',
	'Class:FAQ/Attribute:key_words+' => '~~',
	'Class:FAQ/Attribute:domains' => 'Domain-ek',
));

//
// Class: FAQCategory
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:FAQCategory' => 'GyIK kategória',
	'Class:FAQCategory+' => 'Category for FAQ~~',
	'Class:FAQCategory/Attribute:name' => 'Név',
	'Class:FAQCategory/Attribute:name+' => '~~',
	'Class:FAQCategory/Attribute:faq_list' => 'GyIK-ek',
	'Class:FAQCategory/Attribute:faq_list+' => 'All the frequently asked questions related to this category~~',
));
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:ProblemManagement' => 'Probléma kezelés',
	'Menu:ProblemManagement+' => '',
	'Menu:Problem:Shortcuts' => 'Gyorsmenü',
	'Menu:FAQCategory' => 'GyIK kategória',
	'Menu:FAQCategory+' => 'Összes GyIK kategória',
	'Menu:FAQ' => 'GyIK-ek',
	'Menu:FAQ+' => 'Összes GyIK',
	'Brick:Portal:FAQ:Menu' => 'GyIK',
	'Brick:Portal:FAQ:Title' => 'Gyakran Ismételt kérdések',
	'Brick:Portal:FAQ:Title+' => '<p>Elfoglalt?</p><p>Nézze meg a leggyakoribb kérdések listáját, és (talán) azonnal megtalálja a várt választ.</p>',
));
