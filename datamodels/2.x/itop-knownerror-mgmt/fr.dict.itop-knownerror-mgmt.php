<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * @author	Erwan Taloc <erwan.taloc@combodo.com>
 * @author	Romain Quetiez <romain.quetiez@combodo.com>
 * @author	Denis Flaven <denis.flaven@combodo.com>
 * @licence	http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//
// Class: KnownError
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:KnownError' => 'Erreur Connue',
	'Class:KnownError+' => 'Erreur documenté pour un problème connu',
	'Class:KnownError/Attribute:name' => 'Nom',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Client',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Nom du client',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problème lié',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Rérérence problème lié',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptome',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Cause première',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Contournement',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Solution',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Code d\'erreur',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Domaine',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Application',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Application',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Bureautique',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Bureautique',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Réseau',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Réseau',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Serveur',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Serveur',
	'Class:KnownError/Attribute:vendor' => 'Vendeur',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Modèle',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Version',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Documents',
	'Class:KnownError/Attribute:document_list+' => '',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkErrorToFunctionalCI' => 'Lien Erreur / CI',
	'Class:lnkErrorToFunctionalCI+' => 'Lien entre une erreur et un ci',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Erreur',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Nom erreur',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Reason',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDocumentToError' => 'Lien Documents / Errors',
	'Class:lnkDocumentToError+' => 'Lien entre un document et une erreur',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Nom Document',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Erreur',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Nom Erreur',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

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
));

//
// Class: FAQcategory
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:FAQcategory' => 'Catégorie de FAQ',
	'Class:FAQcategory+' => 'Catégorie de FAQ',
	'Class:FAQcategory/Attribute:name' => 'Nom',
	'Class:FAQcategory/Attribute:name+' => '',
	'Class:FAQcategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQcategory/Attribute:faq_list+' => '',
));

Dict::Add('EN US', 'English', 'English', array(
	'Menu:NewError' => 'Nouvelle erreur connue',
	'Menu:NewError+' => 'Créer une erreur connue',
	'Menu:SearchError' => 'Rechercher une erreur connue',
	'Menu:SearchError+' => 'Rechercher une erreur connue',
        'Menu:Problem:KnownErrors' => 'Toutes les erreurs connues',
        'Menu:Problem:KnownErrors+' => 'Toutes les erreurs connues',
	'Menu:FAQCategory' => 'Catégories de FAQ',
	'Menu:FAQCategory+' => 'Toutes les catégories de FAQ',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => 'Toutes les  FAQs',

));
?>
