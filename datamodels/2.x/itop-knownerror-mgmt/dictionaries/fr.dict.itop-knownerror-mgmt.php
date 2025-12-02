<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('FR FR', 'French', 'Français', [
	'Class:KnownError' => 'Erreur Connue',
	'Class:KnownError+' => 'Erreur documenté pour un problème connu',
	'Class:KnownError/Attribute:name' => 'Nom',
	'Class:KnownError/Attribute:name+' => 'Ce nom devrait être unique parmi les erreurs connues de cette organisation',
	'Class:KnownError/Attribute:org_id' => 'Organisation',
	'Class:KnownError/Attribute:org_id+' => 'Lier l\'erreur connue au fournisseur de services responsable de sa gestion, ou éventuellement à une organisation cliente si l\'erreur lui est spécifique',
	'Class:KnownError/Attribute:cust_name' => 'Nom organisation',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problème lié',
	'Class:KnownError/Attribute:problem_id+' => 'Le problème qui n\'ayant pû être résolu rapidement, a conduit à créer cette erreur connue',
	'Class:KnownError/Attribute:problem_ref' => 'Rérérence problème lié',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptôme',
	'Class:KnownError/Attribute:symptom+' => 'Quels sont les effets observables de cette erreur ?',
	'Class:KnownError/Attribute:root_cause' => 'Cause première',
	'Class:KnownError/Attribute:root_cause+' => 'Quelle est la cause première de cette erreur ?',
	'Class:KnownError/Attribute:workaround' => 'Contournement',
	'Class:KnownError/Attribute:workaround+' => 'Comment éviter les effets de cette erreur, en attendant sa résolution ?',
	'Class:KnownError/Attribute:solution' => 'Solution',
	'Class:KnownError/Attribute:solution+' => 'Que faut-il faire pour corriger définitivement cette erreur ?',
	'Class:KnownError/Attribute:error_code' => 'Code d\'erreur',
	'Class:KnownError/Attribute:error_code+' => 'Si l\'erreur est associée à un code d\'erreur spécifique (ex: un code d\'erreur système), indiquez-le ici',
	'Class:KnownError/Attribute:domain' => 'Domaine',
	'Class:KnownError/Attribute:domain+' => 'Choisissez le domaine technique auquel cette erreur appartient',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Application',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Bureautique',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Réseau',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Serveur',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Vendeur',
	'Class:KnownError/Attribute:vendor+' => 'Un texte libre pour identifier le vendeur des éléments de configuration concernés par cette erreur',
	'Class:KnownError/Attribute:model' => 'Modèle',
	'Class:KnownError/Attribute:model+' => 'Modèle des éléments de configuration concerné par cette erreur',
	'Class:KnownError/Attribute:version' => 'Version',
	'Class:KnownError/Attribute:version+' => 'Version des éléments de configuration concernée par cette erreur',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => 'Les éléments de configuration potentiellement impactés par cette erreur connue',
	'Class:KnownError/Attribute:document_list' => 'Documents',
	'Class:KnownError/Attribute:document_list+' => 'Tous les documents liés à cette erreur connue',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkErrorToFunctionalCI' => 'Lien Erreur / CI',
	'Class:lnkErrorToFunctionalCI+' => 'Lien entre une erreur et un ci',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Erreur connue',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Nom erreur',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Motif du lien',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkDocumentToError' => 'Lien Documents / Errors',
	'Class:lnkDocumentToError+' => 'Lien entre un document et une erreur',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s',
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
]);

Dict::Add('FR FR', 'French', 'Français', [
	'Menu:ProblemManagement' => 'Gestion des problèmes',
	'Menu:ProblemManagement+' => 'Gestion des problèmes',
	'Menu:Problem:Shortcuts' => 'Raccourcis',
	'Menu:NewError' => 'Nouvelle erreur connue',
	'Menu:NewError+' => 'Créer une erreur connue',
	'Menu:SearchError' => 'Rechercher une erreur connue',
	'Menu:SearchError+' => 'Rechercher une erreur connue',
	'Menu:Problem:KnownErrors' => 'Toutes les erreurs connues',
	'Menu:Problem:KnownErrors+' => 'Toutes les erreurs connues',
]);
