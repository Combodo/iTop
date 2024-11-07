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
Dict::Add('DE DE', 'German', 'Deutsch', [
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch-Count-Fehler in `%1$s`, %2$d Einträge geholt (fetched) / %3$d gezählt',
	'DBAnalyzer-Integrity-FinalClass' => 'Das Feld `%2$s`.`%1$s` muss den gleichen Wert `%3$s`.`%1$s` haben',
	'DBAnalyzer-Integrity-HKInvalid' => 'Kaputter hierarchischer Schlüssel `%1$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Ungültiger Externer Key %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Ungültiger Wert für %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Fehlender Externer Key %1$s (Spalte: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Verwaister Eintrag in `%1$s`, er sollte eine Entsprechung in Tabelle `%2$s` haben',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Das Feld `%2$s`.`%1$s` muss eine gültige Klasse enthalten',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Manche Benutzerkonten haben keinerlei zugewiesenes Profil',
	'DBTools:Analyze' => 'Analysiere',
	'DBTools:Base' => 'Datenbank',
	'DBTools:Class' => 'Klasse',
	'DBTools:Count' => 'Anzahl',
	'DBTools:DatabaseInfo' => 'Datenbank-Informationen',
	'DBTools:DetailedErrorLimit' => 'Liste auf %1$s Fehler begrenzt',
	'DBTools:DetailedErrorTitle' => '%2$s Fehler(s) in der Klasse %1$s: %3$s',
	'DBTools:Details' => 'Details anzeigen',
	'DBTools:Disclaimer' => 'DISCLAIMER: FERTIGEN SIE EIN BACKUP IHRER DATENBANK AN, BEVOR SIE DIE FIXES ANWENDEN!',
	'DBTools:Error' => 'Fehler',
	'DBTools:ErrorsFound' => 'Fehler gefunden',
	'DBTools:FetchCheck' => 'Fetch Check (dauert länger)',
	'DBTools:FixitSQLquery' => 'SQL Query zur Fehlerbehebung (Indikation)',
	'DBTools:HideIds' => 'Fehler',
	'DBTools:Inconsistencies' => 'Datenbank-Inkonsistenzen',
	'DBTools:Indication' => 'Wichtig: Nach dem Fixen der Errors in der Datenbank müssen Sie die Analyse erneut laufen lassen, weil durch die Fixes eventuell weitere Inkonsistenzen entstanden sind',
	'DBTools:IntegrityCheck' => 'Integritätscheck',
	'DBTools:LostAttachments' => 'Verlorene Attachments',
	'DBTools:LostAttachments:Button:Analyze' => 'Analysieren',
	'DBTools:LostAttachments:Button:Busy' => 'Bitte warten...',
	'DBTools:LostAttachments:Button:Restore' => 'Wiederherstellen',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Diese Aktion kann nicht rückgängig gemacht werden, bitte bestätigen Sie, dass Sie die ausgewählten Dateien wiederherstellen möchten.',
	'DBTools:LostAttachments:Disclaimer' => 'Hier können Sie Ihre Datenbank nach verlorenen oder falsch platzierten Attachments durchsuchen. Dies ist kein Recovery-Tool - es stellt keine gelöschten Daten wieder her.',
	'DBTools:LostAttachments:History' => 'Attachment "%1$s" mit DB-Tools wiederhergestellt',
	'DBTools:LostAttachments:Step:Analyze' => 'Suche zunächst nach verlorenen / falsch platzierten Attachments mittels einer Analyse der Datenbank',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Analyseergebnisse:',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Derzeitiger Ort',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Dateiname',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Verschieben nach...',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Toll! Alles scheint am richtigen Ort zu sein.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Manche Attachments scheinen am falschen Ort zu sein. Werfen Sie einen Blick auf die folgende Liste und wählen Sie diejenigen aus, die Sie gerne verschieben möchten.',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Restore-Ergebnisse:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d Attachments wurden wiederhergestellt.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Als Inline-Bild gespeichert',
	'DBTools:NoError' => 'Die Datenbank ist OK',
	'DBTools:SQLquery' => 'SQL Query',
	'DBTools:SQLresult' => 'SQL Ergebnis',
	'DBTools:SelectAnalysisType' => 'Analysetyp auswählen',
	'DBTools:ShowAll' => 'Alle Fehler anzeigen',
	'DBTools:ShowIds' => 'Fehler und Werte',
	'DBTools:ShowReport' => 'Report',
	'DBTools:Size' => 'Größe',
	'DBTools:Title' => 'Datenbankpflege-Tools',
	'Menu:DBToolsMenu' => 'DB Tools',
]);
