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
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Question fréquement posée',
	'Class:FAQ/Attribute:title' => 'Titre',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Résumé',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Description',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Categorie',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Nom catégorie',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Code d\'erreur',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Mots clés',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domaines',
));

//
// Class: FAQCategory
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:FAQCategory' => 'Catégorie de FAQ',
	'Class:FAQCategory+' => 'Catégorie de FAQ',
	'Class:FAQCategory/Attribute:name' => 'Nom',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => '',
));
Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:ProblemManagement' => 'Gestion des problèmes',
	'Menu:ProblemManagement+' => 'Gestion des problèmes',
	'Menu:Problem:Shortcuts' => 'Raccourcis',
	'Menu:FAQCategory' => 'Catégories de FAQ',
	'Menu:FAQCategory+' => 'Toutes les catégories de FAQ',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => 'Toutes les  FAQs',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Foire Aux Questions',
	'Brick:Portal:FAQ:Title+' => '<p>Vous êtes pressé&nbsp;?</p><p>Consultez la liste des questions les plus fréquentes et vous trouverez (peut-être) immédiatement la réponse à votre besoin.</p>',
));
