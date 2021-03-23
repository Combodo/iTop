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
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2021 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*		
*/
//
// Class: FAQ
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => '',
	'Class:FAQ/Attribute:title' => 'Titel',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Zusammenfassung',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Beschreibung',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Kategorie',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Kategoriename',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Fehlercode',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Schlüsselwörter',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domäne',
));

//
// Class: FAQCategory
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:FAQCategory' => 'FAQ-Kategorie',
	'Class:FAQCategory+' => '',
	'Class:FAQCategory/Attribute:name' => 'Name',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => '',
));
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Shortcuts' => 'Shortcuts',
	'Menu:FAQCategory' => 'FAQ-Kategorien',
	'Menu:FAQCategory+' => '',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => '',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Oft gestellte Fragen (FAQs)',
	'Brick:Portal:FAQ:Title+' => '<p>In Eile?</p><p>Sehen Sie sich die meistgestellten Fragen an (FAQs) und finden Sie (eventuell) die Antwort direkt dort.</p>',
));
