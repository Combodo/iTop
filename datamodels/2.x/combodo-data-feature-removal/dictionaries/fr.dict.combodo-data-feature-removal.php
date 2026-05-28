<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
* Localized data
*/

Dict::Add('FR FR', 'French', 'Français', [
	'Menu:DataFeatureRemovalMenu' => 'Gestion des extensions',
	'combodo-data-feature-removal/Operation:Main/Title' => 'Gestion des extensions',

	'DataFeatureRemoval:Main:Title' => 'Gestion des extensions',
	'DataFeatureRemoval:Main:SubTitle' => 'Sélectionner les extensions à installer sur votre iTop',
	'DataFeatureRemoval:Failure:Title' => 'Erreurs lors de la simulation de suppression d\'extensions',
	'DataFeatureRemoval:Helper:Title' => 'Activez ou désactivez les extensions installées dans votre iTop.',

	'DataFeatureRemoval:Features:Title' => 'Extensions',
	'DataFeatureRemoval:Result:Title' => 'Modification demandée',
	'DataFeatureRemoval:Execution:Title' => 'Suppressions',
	'DataFeatureRemoval:Analysis:Title' => 'Résultat de l’analyse',
	'DataFeatureRemoval:Analysis:Subtitle' => 'Vérifier les éléments à nettoyer',
	'DataFeatureRemoval:Analysis:SubTitle' => '%1$s élément(s) à nettoyer avant de poursuivre',

	'DataFeatureRemoval:DeletionPlan:Title' => 'Plan de suppression des données',
	'DataFeatureRemoval:DeletionPlan:SubTitle' => '%1$s ligne(s) à nettoyer avant de poursuivre',
	'DataFeatureRemoval:DoDeletion:Title' => 'Exécuter la suppression',
	'DataFeatureRemoval:DoDeletion:SubTitle' => 'Supprime toutes les entrées de la base de données',
	'DataFeatureRemoval:DeletionPlan:Error:Issues' => 'Certains objets doivent être supprimés manuellement avant le nettoyage',

	'DataFeatureRemoval:Table:Analysis:ClassName' => 'Élément à supprimer',
	'DataFeatureRemoval:Table:Analysis:FeatureName' => 'Fonctionnalité',
	'DataFeatureRemoval:Table:Analysis:Module' => 'Module',
	'DataFeatureRemoval:Table:Analysis:Occurrence' => 'Occurrence',

	'DataFeatureRemoval:CleanupComplete:Title' => 'All clear.',
	'DataFeatureRemoval:CompilComplete' => 'Compilation successful. No Cleanup needed. You can proceed to setup.',

	'UI:Button:Analyze' => 'Analyser',
	'UI:Button:ModifyChoices' => 'Modifier les choix',
	'UI:Button:AnalyzeAndSetup' => 'Analyser et ouvrir l’assistant de configuration',
	'UI:Button:PlanDeletion' => 'Préparer le plan de suppression',
	'UI:Button:DoDeletion' => 'Supprimer les données',
	'UI:Button:BackToMain' => 'Retour à la suppression de fonctionnalités',
	'UI:Button:Setup' => 'Retour à l’assistant de configuration',

	'UI:Action:ForceUninstall' => 'Forcer la désinstallation',
	'UI:Action:MoreInfo' => 'Plus d’informations',

	'DataFeatureRemoval:Table:Empty' => 'Aucune donnée à supprimer',

	'DataFeatureRemoval:Column:Class' => 'Classe',
	'DataFeatureRemoval:Column:DeleteCount' => 'Entrées à supprimer',
	'DataFeatureRemoval:Column:UpdateCount' => 'Entrées à mettre à jour',
	'DataFeatureRemoval:Column:IssueCount' => 'Problèmes empêchant le nettoyage automatique',

	'DataFeatureRemoval:Column:DeletedCount' => 'Entrées supprimées',
	'DataFeatureRemoval:Column:UpdatedCount' => 'Entrées mises à jour',
]);
