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
	'DBAnalyzer-Fetch-Count-Error' => 'Erreur de récupération dans `%1$s`, %2$d enregistrements récupérés / %3$d comptés',
	'DBAnalyzer-Integrity-FinalClass' => 'Le champ `%2$s`.`%1$s` doit avoir la même valeur que `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-HKInvalid' => 'Clé hiérarchique `%1$s` invalide',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Clé externe invalide %1$s (colonne: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valeur invalide pour %1$s (colonne: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Clé externe manquante %1$s (colonne: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Enregistrement orphelin dans `%1$s`, il devrait avoir son équivalent dans la table `%2$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Le champ `%2$s`.`%1$s` doit contenir une classe valide',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Certains comptes utilisateurs n\'ont aucun profile',
	'DBTools:Analyze' => 'Analyser',
	'DBTools:Base' => 'Base',
	'DBTools:Class' => 'Classe',
	'DBTools:Count' => 'Nombre',
	'DBTools:DatabaseInfo' => 'Information Base de Données',
	'DBTools:DetailedErrorLimit' => 'Liste limitée à %1$s erreurs',
	'DBTools:DetailedErrorTitle' => '%2$s erreur(s) dans la classe %1$s : %3$s',
	'DBTools:Details' => 'Afficher détails',
	'DBTools:Disclaimer' => 'ATTENTION : EFFECTUEZ UNE SAUVEGARDE DE LA BASE AVANT D\'APPLIQUER LES CORRECTIONS',
	'DBTools:Error' => 'Erreur',
	'DBTools:ErrorsFound' => 'Erreurs trouvées',
	'DBTools:FetchCheck' => 'Contrôle de récupération (long)',
	'DBTools:FixitSQLquery' => 'Requête SQL pour nettoyer la base (indication)',
	'DBTools:HideIds' => 'Erreurs',
	'DBTools:Inconsistencies' => 'Incohérences de base de données',
	'DBTools:Indication' => 'Important : après correction il est nécessaire de relancer l\'analyse car d\'autres inconsistances peuvent être générées par les modifications',
	'DBTools:IntegrityCheck' => 'Contrôle d\'intégrité',
	'DBTools:LostAttachments' => 'Pièces jointes perdues',
	'DBTools:LostAttachments:Button:Analyze' => 'Analyser',
	'DBTools:LostAttachments:Button:Busy' => 'Patientez ...',
	'DBTools:LostAttachments:Button:Restore' => 'Restaurer',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Cet action ne peut être annuler, veuillez confirmer que vous voulez restaurer les fichiers sélectionnés.',
	'DBTools:LostAttachments:Disclaimer' => 'Ici vous pouvez retrouver des pièces jointes perdues ou égarées dans votre base de données. Ceci n\'est PAS un outil de récupération des données, il ne récupère pas les données effacées.',
	'DBTools:LostAttachments:History' => 'Pièce jointe "%1$s" restaurée avec l\'outil de BDD',
	'DBTools:LostAttachments:Step:Analyze' => 'Tout d\'abord, scannez la base de données à la recherche de pièces jointes perdues/égarées.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Résultat de l\'analyse :',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Emplacement actuel',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nom de fichier',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Déplacer vers ...',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Parfait ! Il semble que tout soit en ordre.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Certaines pièces jointes (%1$d) semblent être au mauvais endroit. Examinez la liste suivante et cochez celles que vous souhaitez déplacer.',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Résultats de la restauration :',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d pièces jointes ont été restaurées.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Stockée comme "InlineImage"',
	'DBTools:NoError' => 'La base de données est OK',
	'DBTools:SQLquery' => 'Requête SQL',
	'DBTools:SQLresult' => 'Résultat SQL',
	'DBTools:SelectAnalysisType' => 'Type d\'analyse',
	'DBTools:ShowAll' => 'Afficher toutes les erreurs',
	'DBTools:ShowIds' => 'Détails des erreurs',
	'DBTools:ShowReport' => 'Rapport',
	'DBTools:Size' => 'Taille',
	'DBTools:Title' => 'Contrôle de l\'intégrité de la base de données',
	'Menu:DBToolsMenu' => 'Intégrité base de données',
]);
