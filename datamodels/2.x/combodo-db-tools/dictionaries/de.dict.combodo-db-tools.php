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
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:DBToolsMenu' => 'DB Tools',
	'DBTools:Class' => 'Klasse',
	'DBTools:Title' => 'Datenbankpflege-Tools',
	'DBTools:ErrorsFound' => 'Fehler gefunden',
	'DBTools:Indication' => 'Wichtig: Nach dem Fixen der Errors in der Datenbank müssen Sie die Analyse erneut laufen lassen, weil durch die Fixes eventuell weitere Inkonsistenzen entstanden sind',
	'DBTools:Disclaimer' => 'DISCLAIMER: FERTIGEN SIE EIN BACKUP IHRER DATENBANK AN, BEVOR SIE DIE FIXES ANWENDEN!',
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
	'DBTools:SelectAnalysisType' => 'Analysetyp auswählen',
	'DBTools:Analyze' => 'Analysiere',
	'DBTools:Details' => 'Details anzeigen',
	'DBTools:ShowAll' => 'Alle Fehler anzeigen',
	'DBTools:Inconsistencies' => 'Datenbank-Inkonsistenzen',
	'DBTools:DetailedErrorTitle' => '%2$s Fehler(s) in der Klasse %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'Liste auf %1$s Fehler begrenzt',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Verwaister Eintrag in `%1$s`, er sollte eine Entsprechung in Tabelle `%2$s` haben',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Ungültiger Externer Key %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Fehlender Externer Key %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Ungültiger Wert für %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Manche Benutzerkonten haben keinerlei zugewiesenes Profil',
	'DBAnalyzer-Integrity-HKInvalid' => 'Kaputter hierarchischer Schlüssel `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch-Count-Fehler in `%1$s`, %2$d Einträge geholt (fetched) / %3$d gezählt',
	'DBAnalyzer-Integrity-FinalClass' => 'Das Feld `%2$s`.`%1$s` muss den gleichen Wert `%3$s`.`%1$s` haben',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Das Feld `%2$s`.`%1$s` muss eine gültige Klasse enthalten',
));

// Database Info
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'DBTools:DatabaseInfo' => 'Datenbank-Informationen',
	'DBTools:Base' => 'Datenbank',
	'DBTools:Size' => 'Größe',
));

// Lost attachments
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'DBTools:LostAttachments' => 'Verlorene Attachments',
	'DBTools:LostAttachments:Disclaimer' => 'Hier können Sie Ihre Datenbank nach verlorenen oder falsch platzierten Attachments durchsuchen. Dies ist kein Recovery-Tool - es stellt keine gelöschten Daten wieder her.',
	'DBTools:LostAttachments:Button:Analyze' => 'Analysieren',
	'DBTools:LostAttachments:Button:Restore' => 'Wiederherstellen',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Diese Aktion kann nicht rückgängig gemacht werden, bitte bestätigen Sie, dass Sie die ausgewählten Dateien wiederherstellen möchten.',
	'DBTools:LostAttachments:Button:Busy' => 'Bitte warten...',
	'DBTools:LostAttachments:Step:Analyze' => 'Suche zunächst nach verlorenen / falsch platzierten Attachments mittels einer Analyse der Datenbank',
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
