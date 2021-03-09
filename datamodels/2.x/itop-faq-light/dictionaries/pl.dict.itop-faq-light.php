<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:FAQ' => 'Pytania FAQ',
	'Class:FAQ+' => 'Często Zadawane Pytania',
	'Class:FAQ/Attribute:title' => 'Tytuł',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Podsumowanie',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Opis',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Kategoria',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Nazwa kategorii',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Kod błędu',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Słowa kluczowe',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domeny',
));

//
// Class: FAQCategory
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:FAQCategory' => 'Kategoria FAQ',
	'Class:FAQCategory+' => 'Kategoria FAQ',
	'Class:FAQCategory/Attribute:name' => 'Nazwa',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'Pytania FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => 'Wszystkie najczęściej zadawane pytania związane z tą kategorią',
));
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:ProblemManagement' => 'Zarządzanie problemami',
	'Menu:ProblemManagement+' => 'Zarządzanie problemami',
	'Menu:Problem:Shortcuts' => 'Skróty',
	'Menu:FAQCategory' => 'Kategorie pytań FAQ',
	'Menu:FAQCategory+' => 'Wszystkie kategorie pytań FAQ',
	'Menu:FAQ' => 'Pytania FAQ',
	'Menu:FAQ+' => 'Wszystkie pytania FAQ',
	'Brick:Portal:FAQ:Menu' => 'Pytania FAQ',
	'Brick:Portal:FAQ:Title' => 'Często Zadawane Pytania',
	'Brick:Portal:FAQ:Title+' => '<p>W pośpiechu?</p><p>Sprawdź listę najczęściej zadawanych pytań i (być może) od razu znajdź oczekiwaną odpowiedź.</p>',
));
