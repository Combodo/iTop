<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2024 Combodo SAS
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

Dict::Add('EN GB', 'British English', 'British English', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Frequently asked questions',
	'Class:FAQ/Attribute:title' => 'Title',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Summary',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Description',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Category',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Category name',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Error code',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Key words',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domains',
));

//
// Class: FAQCategory
//

Dict::Add('EN GB', 'British English', 'British English', array(
	'Class:FAQCategory' => 'FAQ Category',
	'Class:FAQCategory+' => 'Category for FAQ',
	'Class:FAQCategory/Attribute:name' => 'Name',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => 'All the frequently asked questions related to this category',
));
Dict::Add('EN GB', 'British English', 'British English', array(
	'Menu:ProblemManagement' => 'Problem management',
	'Menu:ProblemManagement+' => 'Problem management',
	'Menu:Problem:Shortcuts' => 'Shortcuts',
	'Menu:FAQCategory' => 'FAQ categories',
	'Menu:FAQCategory+' => 'All FAQ categories',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => 'All FAQs',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Frequently Asked Questions',
	'Brick:Portal:FAQ:Title+' => '<p>In a hurry?</p><p>Check out the list of most common questions and (maybe) find the expected answer right away.</p>',
));
