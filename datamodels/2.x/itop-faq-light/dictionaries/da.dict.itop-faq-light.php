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
 * @author	Erik Bøg <erik@boegmoeller.dk>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: FAQ
//
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => '',
	'Class:FAQ/Attribute:title' => 'Titel',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Sammenfatning',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Beskrivelse',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Kategori',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Kategorinavn',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Fejlkode',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Nøgleord',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domains~~',
));

//
// Class: FAQCategory
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:FAQCategory' => 'FAQ-Kategori',
	'Class:FAQCategory+' => '',
	'Class:FAQCategory/Attribute:name' => 'Navn',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => '',
));
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Shortcuts' => 'Genvej',
	'Menu:FAQCategory' => 'FAQ-Kategorier',
	'Menu:FAQCategory+' => '',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => '',
	'Brick:Portal:FAQ:Menu' => 'FAQ~~',
	'Brick:Portal:FAQ:Title' => 'Frequently Asked Questions~~',
	'Brick:Portal:FAQ:Title+' => '<p>In a hurry?</p><p>Check out the list of most common questions and (maybe) find the expected answer right away.</p>~~',
));
