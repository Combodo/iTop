<?php
// Copyright (C) 2010-2018 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Perguntas mais frequentes',
	'Class:FAQ/Attribute:title' => 'Título',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Índice',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Descrição',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Categoria',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Nome categoria',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Código erro',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Palavras-chaves',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domínios',
));

//
// Class: FAQCategory
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FAQCategory' => 'Categoria FAQ',
	'Class:FAQCategory+' => 'Categoria por FAQ',
	'Class:FAQCategory/Attribute:name' => 'Nome',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => 'Todas as perguntas mais frequentes vinculadas a essa categoria',
));
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:ProblemManagement' => 'Gerencimento Problemas',
	'Menu:ProblemManagement+' => 'Gerencimento Problemas',
	'Menu:Problem:Shortcuts' => 'Atalho',
	'Menu:FAQCategory' => 'Categorias FAQ',
	'Menu:FAQCategory+' => 'Todas categorias FAQ',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => 'Todas FAQs',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Perguntes frequentes (FAQ)',
	'Brick:Portal:FAQ:Title+' => '<p>Com pressa?</p><p>Confira a lista de perguntas mais comuns e (talvez) encontre a resposta esperada imediatamente.</p>',
));
