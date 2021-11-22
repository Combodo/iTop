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
 * @traductor   Miguel Turrubiates <miguel_tf@yahoo.com> 
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
//
// Class: FAQ
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FAQ' => 'Preguntas y Respuestas Frecuentes',
	'Class:FAQ+' => 'Preguntas y Respuestas Frecuentes',
	'Class:FAQ/Attribute:title' => 'Asunto',
	'Class:FAQ/Attribute:title+' => 'Asunto',
	'Class:FAQ/Attribute:summary' => 'Resumen',
	'Class:FAQ/Attribute:summary+' => 'Resumen',
	'Class:FAQ/Attribute:description' => 'Descripción',
	'Class:FAQ/Attribute:description+' => 'Descripción',
	'Class:FAQ/Attribute:category_id' => 'Categoría',
	'Class:FAQ/Attribute:category_id+' => 'Categoría',
	'Class:FAQ/Attribute:category_name' => 'Categoría',
	'Class:FAQ/Attribute:category_name+' => 'Categoría',
	'Class:FAQ/Attribute:error_code' => 'Código de Error',
	'Class:FAQ/Attribute:error_code+' => 'Código de Error',
	'Class:FAQ/Attribute:key_words' => 'Palabras Clave',
	'Class:FAQ/Attribute:key_words+' => 'Palabras Clave',
	'Class:FAQ/Attribute:domains' => 'Ámbito',
));

//
// Class: FAQCategory
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FAQCategory' => 'Categoría de Preguntas y Respuesta Frecuentes',
	'Class:FAQCategory+' => 'Categoría de Preguntas y Respuesta Frecuentes',
	'Class:FAQCategory/Attribute:name' => 'Nombre',
	'Class:FAQCategory/Attribute:name+' => 'Nombre de Categoría de Preguntas y Respuestas Frecuentes',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => 'FAQs',
));
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:ProblemManagement' => 'Gestión de problemas',
	'Menu:ProblemManagement+' => 'Gestión de problemas',
	'Menu:Problem:Shortcuts' => 'Acceso Rápido',
	'Menu:FAQCategory' => 'Categorías de FAQ',
	'Menu:FAQCategory+' => 'Categorías FAQ',
	'Menu:FAQ' => 'Preguntas y Respuestas Frecuentes',
	'Menu:FAQ+' => 'Preguntas y Respuestas Frecuentes',
	'Brick:Portal:FAQ:Menu' => 'Preguntas y Respuestas',
	'Brick:Portal:FAQ:Title' => 'Preguntas y Respuestas Frecuentes',
	'Brick:Portal:FAQ:Title+' => '<p>¿En una prisa?</p><p>Vea la lista de las preguntas más comunes y encontrará (tal vez) la respuesta inmediata a sus necesidades.</p>',
));
