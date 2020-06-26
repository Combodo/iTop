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
// Copyright 2018 David Gümbel, ITOMIG GmbH, david.guembel @ itomig DE
// Database inconsistencies
Dict::Add('DE DE', 'German', 'Deutsch', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'DB Tools',
	'DBTools:Class' => 'Klasse',
	'DBTools:Title' => 'Datenbank-Pflege-Tools',
	'DBTools:ErrorsFound' => 'Fehler gefunden',
	'DBTools:Error' => 'Fehler',
	'DBTools:Count' => 'Anzahl',
	'DBTools:SQLquery' => 'SQL Query',
	'DBTools:FixitSQLquery' => 'SQL Query zur Fehlerbehebung (Indikation)',
	'DBTools:SQLresult' => 'SQL Ergebnis',
	'DBTools:NoError' => 'Die Datenbank ist OK',
	'DBTools:HideIds' => 'Fehler',
	'DBTools:ShowIds' => 'Fehler und Werte',
	'DBTools:ShowReport' => 'Report',
	'DBTools:IntegrityCheck' => 'Integritätscheck',
	'DBTools:FetchCheck' => 'Fetch Check (dauert länger)',

	'DBTools:Analyze' => 'Analysiere',
	'DBTools:Details' => 'Details anzeigen',
	'DBTools:ShowAll' => 'Alle Fehler anzeigen',

	'DBTools:Inconsistencies' => 'Datenbank-Inkonsistenzen',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Verwaister Eintrag in `%1$s`, er sollte eine Entsprechung in Tabelle `%2$s` haben',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Ungültiger Externer Key %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Fehlender Externer Key %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Ungültiger Wert für %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Manche Benutzerkonten haben keinerlei zugewiesenes Profi',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch-Count-Fehler in `%1$s`, %2$d Einträge geholt (fetched) / %3$d gezählt',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contains a valid class~~',
));

// Database Info
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'DBTools:DatabaseInfo' => 'Datenbank-Information',
	'DBTools:Base' => 'Datenbank',
	'DBTools:Size' => 'Größe',
));

// Lost attachments
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'DBTools:LostAttachments' => 'Verlorene Attachments',
	'DBTools:LostAttachments:Disclaimer' => 'Hier können Sie Ihre Datenbank nach verlorenen oder falsch platzierten Attachments durchsuchen. Dies ist kein Recovery-Tool - es stellt keine gelöschten Daten wieder her.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analysieren',
	'DBTools:LostAttachments:Button:Restore' => 'Wiederherstellen',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Diese Aktion kann nicht rückgängig gemacht werden, bitte bestätigen Sie dass Sie die ausgewählten Dateien wiederherstellen möchten.',
	'DBTools:LostAttachments:Button:Busy' => 'Bitte warten...',

	'DBTools:LostAttachments:Step:Analyze' => 'Suche zunächst nach verlorenen / falsch platzierten Attachments, mittels einer Analyse der Datenbank',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analyseergebnisse:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Toll! Alles scheint am richtigen Ort zu sein.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Manche Attachments scheinen am falschen Ort zu sein. Werfen Sie einen Blick auf die folgende Liste und wählen Sie diejenigen aus, die Sie gerne verschieben möchten.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Dateiname',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Derzeitiger Ort',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Verschieben nach...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Restore-Ergebnisse:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d Attachments wurden wiederhergestellt.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Als Inline-Bild gespeichert',
	'DBTools:LostAttachments:History' => 'Attachment "%1$s" mit DB-Tools wiederhergestellt'
));
