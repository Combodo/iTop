<?php

/**
 * @copyright   Copyright (C) 2010-2026 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Localized data
 */

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Menu:DataFeatureRemovalMenu' => 'Extension-Verwaltung',
	'combodo-data-feature-removal/Operation:Main/Title' => 'Extension-Verwaltung',

	'DataFeatureRemoval:Main:Title' => 'Extension-Verwaltung',
	'DataFeatureRemoval:Main:SubTitle' => 'In Ihrem iTop installierte Extensions aktivieren oder deaktivieren',
	'DataFeatureRemoval:Failure:Title' => 'Fehler beim Testlauf der Extension-Entfernung',
	'DataFeatureRemoval:Helper:Title' => 'Prüfen Sie, ob Daten oder Abhängigkeiten dem Hinzufügen bzw. Entfernen einer Extension entgegenstehen.',

	'DataFeatureRemoval:Features:Title' => 'Extensions',
	'DataFeatureRemoval:Result:Title' => 'Angeforderte Änderung',
	'DataFeatureRemoval:NoResult:Title' => 'Keine Änderung angefordert',
	'DataFeatureRemoval:Execution:Title' => 'Ausführung der Löschvorgänge',
	'DataFeatureRemoval:Analysis:Title' => 'Analyseergebnis',
	'DataFeatureRemoval:Analysis:Subtitle' => 'Prüfen Sie alle Elemente, die Ihre Aufmerksamkeit erfordern',
	'DataFeatureRemoval:Analysis:SubTitle' => '%1$s Element(e) müssen bereinigt werden, bevor Sie fortfahren können',

	'DataFeatureRemoval:DeletionPlan:Title' => 'Plan zur Datenlöschung',
	'DataFeatureRemoval:DeletionPlan:SubTitle' => '%1$s Zeilen müssen bereinigt werden, bevor Sie fortfahren können',
	'DataFeatureRemoval:DoDeletion:Title' => 'Löschung durchführen',
	'DataFeatureRemoval:DoDeletion:SubTitle' => 'Alle Einträge aus der Datenbank entfernen',
	'DataFeatureRemoval:DeletionPlan:Error:Issues' => 'Einige Objekte müssen vor der Bereinigung manuell gelöscht werden',

	'DataFeatureRemoval:Table:Analysis:ClassName' => 'Zu entfernendes Element',
	'DataFeatureRemoval:Table:Analysis:FeatureName' => 'Name der Extension',
	'DataFeatureRemoval:Table:Analysis:Module' => 'Modulname',
	'DataFeatureRemoval:Table:Analysis:Occurrence' => 'Vorkommen',

	'DataFeatureRemoval:CleanupComplete:Title' => 'Alles bereinigt.',
	'DataFeatureRemoval:CompilComplete' => 'Kompilierung erfolgreich. Keine Bereinigung erforderlich. Sie können mit dem Setup fortfahren.',

	'DataFeatureRemoval:Compile:InProgress' => 'Kompilierung läuft...',
	'DataFeatureRemoval:Compile:Success' => 'Kompilierung erfolgreich',
	'DataFeatureRemoval:Compile:Error' => 'Fehler bei der Kompilierung',

	'DataFeatureRemoval:RunAudit:InProgress' => 'Analyse läuft...',
	'DataFeatureRemoval:RunAudit:Success' => 'Analyse abgeschlossen',
	'DataFeatureRemoval:RunAudit:Error' => 'Fehler während der Analyse',

	'UI:Button:Analyze' => 'Analysieren',
	'UI:Button:ModifyChoices' => 'Auswahl ändern',
	'UI:Button:AnalyzeAndSetup' => 'Analysieren und zum Setup',
	'UI:Button:PlanDeletion' => 'Mit der Löschung fortfahren',
	'UI:Button:DoDeletion' => 'Mit der Löschung fortfahren',
	'UI:Button:BackToMain' => 'Auswahl ändern',
	'UI:Button:Setup' => 'Setup ausführen',

	'UI:Action:ForceUninstall' => 'Deinstallation erzwingen',
	'UI:Action:MoreInfo' => 'Weitere Informationen',

	'DataFeatureRemoval:Table:Empty' => 'Keine zu entfernenden Daten',

	'DataFeatureRemoval:Column:Class' => 'Klasse',
	'DataFeatureRemoval:Column:DeleteCount' => 'Zu löschende Einträge',
	'DataFeatureRemoval:Column:UpdateCount' => 'Zu aktualisierende Einträge',
	'DataFeatureRemoval:Column:IssueCount' => 'Gefundene Probleme, die eine automatische Bereinigung verhindern',

	'DataFeatureRemoval:Column:DeletedCount' => 'Gelöschte Einträge',
	'DataFeatureRemoval:Column:UpdatedCount' => 'Aktualisierte Einträge',
]);
