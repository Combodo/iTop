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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 *
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 * 
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Veelgestelde vragen',
	'Class:FAQ/Attribute:title' => 'Titel',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Samenvatting',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Omschrijving',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Categorie',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Naam categorie',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Foutcode',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Zoektermen',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domeinen',
));

//
// Class: FAQCategory
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FAQCategory' => 'FAQ-categorie',
	'Class:FAQCategory+' => 'Categorie voor de FAQ',
	'Class:FAQCategory/Attribute:name' => 'Naam',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ\'s',
	'Class:FAQCategory/Attribute:faq_list+' => 'Alle veelgestelde vragen gerelateerd aan deze categorie',
));
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ProblemManagement' => 'Probleem Management',
	'Menu:ProblemManagement+' => 'Probleem Management',
	'Menu:Problem:Shortcuts' => 'Snelkoppelingen',
	'Menu:FAQCategory' => 'FAQ-categorieën',
	'Menu:FAQCategory+' => 'Alle FAQ-categorieën',
	'Menu:FAQ' => 'FAQ\'s',
	'Menu:FAQ+' => 'Alle FAQ\'s',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Veelgestelde vragen',
	'Brick:Portal:FAQ:Title+' => '<p>Gehaast?</p><p>Bekijk deze lijst van veelgestelde vragen. Misschien staat er al een antwoord tussen.</p>',
));
