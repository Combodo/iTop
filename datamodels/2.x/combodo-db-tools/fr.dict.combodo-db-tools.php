<?php
// Copyright (c) 2010-2018 Combodo SARL
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
//
// Database inconsistencies
Dict::Add('FR FR', 'French', 'Français', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Intégrité base de données',
	'DBTools:Class' => 'Classe',
	'DBTools:Title' => 'Outils maintenance base de données',
	'DBTools:ErrorsFound' => 'Erreurs trouvées',
	'DBTools:Error' => 'Erreur',
	'DBTools:Count' => 'Nombre',
	'DBTools:SQLquery' => 'Requête SQL',
	'DBTools:FixitSQLquery' => 'Requête SQL pour nettoyer la base (indication)',
	'DBTools:SQLresult' => 'Résultat SQL',
	'DBTools:NoError' => 'La base de données est OK',
	'DBTools:HideIds' => 'Erreurs',
	'DBTools:ShowIds' => 'Détails des erreurs',
	'DBTools:ShowReport' => 'Rapport',
	'DBTools:IntegrityCheck' => 'Contrôle d\'intégrité',
	'DBTools:FetchCheck' => 'Contrôle de récupération (long)',

	'DBTools:Analyze' => 'Analyser',
	'DBTools:Details' => 'Afficher détails',
	'DBTools:ShowAll' => 'Afficher toutes les erreurs',

	'DBTools:Inconsistencies' => 'Incohérences de base de données',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Enregistrement orphelin dans `%1$s`, il devrait avoir son équivalent dans la tableit  `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Clef externe invalide %1$s (colonne: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Clef externe manquante %1$s (colonne: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valeur invalide pour %1$s (colonne: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Certains comptes utilisateurs n\'ont aucun profile',
	'DBAnalyzer-Fetch-Count-Error' => 'Erreur de récupération dans `%1$s`, %2$d enregistrements récupérés / %3$d comptés',
	'DBAnalyzer-Integrity-FinalClass' => 'Le champ `%2$s`.`%1$s` doit avoir la même valeur que `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Le champ `%2$s`.`%1$s` doit contenir une classe valide',
));

// Database Info
Dict::Add('FR FR', 'French', 'Français', array(
	'DBTools:DatabaseInfo' => 'Information Base de Données',
	'DBTools:Base' => 'Base',
	'DBTools:Size' => 'Taille',
));

// Lost attachments
Dict::Add('FR FR', 'French', 'Français', array(
	'DBTools:LostAttachments' => 'Pièces jointes perdues',
	'DBTools:LostAttachments:Disclaimer' => 'Ici vous pouvez retrouver des pièces jointes perdues ou égarées dans votre base de données. Ceci n\'est PAS un outil de récupération des données, il ne récupère pas les données effacées.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analyser',
	'DBTools:LostAttachments:Button:Restore' => 'Restaurer',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Cet action ne peut être annuler, veuillez confirmer que vous voulez restaurer les fichiers sélectionnés.',
	'DBTools:LostAttachments:Button:Busy' => 'Patientez ...',

	'DBTools:LostAttachments:Step:Analyze' => 'Tout d\'abord, scannez la base de données à la recherche de pièces jointes perdues/égarées.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Résultat de l\'analyse :',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Parfait ! Il semble que tout soit en ordre.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Certaines pièces jointes (%1$d) semblent être au mauvais endroit. Examinez la liste suivante et cochez celles que vous souhaitez déplacer.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nom de fichier',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Emplacement actuel',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Déplacer vers ...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Résultats de la restauration :',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d pièces jointes ont été restaurées.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Stockée comme "InlineImage"',
	'DBTools:LostAttachments:History' => 'Pièce jointe "%1$s" restaurée avec l\'outil de BDD'
));
