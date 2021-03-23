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
 * Localized data
 *
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Domande frequenti',
	'Class:FAQ/Attribute:title' => 'Titolo',
	'Class:FAQ/Attribute:title+' => '~~',
	'Class:FAQ/Attribute:summary' => 'Riepilogo',
	'Class:FAQ/Attribute:summary+' => '~~',
	'Class:FAQ/Attribute:description' => 'Descrizione',
	'Class:FAQ/Attribute:description+' => '~~',
	'Class:FAQ/Attribute:category_id' => 'Categoria',
	'Class:FAQ/Attribute:category_id+' => '~~',
	'Class:FAQ/Attribute:category_name' => 'Nome della Categoria',
	'Class:FAQ/Attribute:category_name+' => '~~',
	'Class:FAQ/Attribute:error_code' => 'Codice errore',
	'Class:FAQ/Attribute:error_code+' => '~~',
	'Class:FAQ/Attribute:key_words' => 'Parola Chiave',
	'Class:FAQ/Attribute:key_words+' => '~~',
	'Class:FAQ/Attribute:domains' => 'Dominio',
));

//
// Class: FAQCategory
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:FAQCategory' => 'Fategoria FAQ',
	'Class:FAQCategory+' => 'Categoria per FAQ',
	'Class:FAQCategory/Attribute:name' => 'Nome',
	'Class:FAQCategory/Attribute:name+' => '~~',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => 'Tutte le faq legate a questa categoria',
));
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:ProblemManagement' => 'Gestione dei Problemi',
	'Menu:ProblemManagement+' => 'Gestione dei Problemi',
	'Menu:Problem:Shortcuts' => 'Scorciatoia',
	'Menu:FAQCategory' => 'Categoria FAQ',
	'Menu:FAQCategory+' => 'Tutte le categorie FAQ',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => 'Tutte le FAQs',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Domande frequenti',
	'Brick:Portal:FAQ:Title+' => '<p>Sei di fretta?</p><p>Verifica nella lista delle FAQ se trovi la risposta al tuo problema.</p>',
));
