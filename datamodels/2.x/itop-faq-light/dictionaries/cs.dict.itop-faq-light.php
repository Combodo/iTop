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
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
//
// Class: FAQ
//
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Často kladené dotazy',
	'Class:FAQ/Attribute:title' => 'Název',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Shrnutí',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Popis',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Kategorie',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Název kategorie',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Kód chyby',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Klíčová slova',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domains~~',
));

//
// Class: FAQCategory
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:FAQCategory' => 'Kategorie FAQ',
	'Class:FAQCategory+' => 'Kategorie pro FAQ',
	'Class:FAQCategory/Attribute:name' => 'Název',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => 'Všechny často kladené dotazy v této kategorii',
));
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Menu:ProblemManagement' => 'Správa problémů',
	'Menu:ProblemManagement+' => 'Správa problémů',
	'Menu:Problem:Shortcuts' => 'Odkazy',
	'Menu:FAQCategory' => 'Kategorie FAQ',
	'Menu:FAQCategory+' => '',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => 'FAQ - Často kladené dotazy',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Často kladené dotazy (FAQ)',
	'Brick:Portal:FAQ:Title+' => '<p>Spěcháte?</p><p>Podívejte se na seznam nejčastějších otázek a (možná) najdete hledanou odpověď ihned.</p>',
));
