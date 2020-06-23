<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 */
// Database inconsistencies
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Databasetools',
	'DBTools:Class' => 'Klasse',
	'DBTools:Title' => 'Onderhoudstools voor de database',
	'DBTools:ErrorsFound' => 'Fouten gevonden',
	'DBTools:Error' => 'Fout',
	'DBTools:Count' => 'Aantal',
	'DBTools:SQLquery' => 'SQL-query',
	'DBTools:FixitSQLquery' => 'SQL-query die mogelijk het probleem verhelpt',
	'DBTools:SQLresult' => 'Resultaat SQL-query',
	'DBTools:NoError' => 'De database is OK',
	'DBTools:HideIds' => 'Overzicht fouten',
	'DBTools:ShowIds' => 'Gedetailleerde weergave',
	'DBTools:ShowReport' => 'Rapport',
	'DBTools:IntegrityCheck' => 'Integriteitscheck',
	'DBTools:FetchCheck' => 'Opvraag-check (fetch) (long)',

	'DBTools:Analyze' => 'Analyseer',
	'DBTools:Details' => 'Toon details',
	'DBTools:ShowAll' => 'Toon alle fouten',

	'DBTools:Inconsistencies' => 'Inconsistenties in database',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Wees-record in "%1$s", het zou een verwant record moeten hebben in de tabel "%2$s"',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Ongeldige externe sleutel %1$s (kolom: "%2$s.%3$s")',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Ontbrekende externe sleutel %1$s (kolom: "%2$s.%3$s")',
	'DBAnalyzer-Integrity-InvalidValue' => 'Ongeldige waarde voor %1$s (kolom: "%2$s.%3$s")',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Sommige gebruikersaccounts hebben geen profiel',
	'DBAnalyzer-Fetch-Count-Error' => 'Opvraag-fout in "%1$s", %2$d records opgevraagd / %3$d geteld',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contains a valid class~~',
));

// Database Info
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'DBTools:DatabaseInfo' => 'Database-informatie',
	'DBTools:Base' => 'Base',
	'DBTools:Size' => 'Grootte',
));

// Lost attachments
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'DBTools:LostAttachments' => 'Verloren bijlages',
	'DBTools:LostAttachments:Disclaimer' => 'Zoek hier verloren or verkeerd geplaatste bijlages. Dit is geen recovery-tool, het kan geen gewiste data herstellen.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analyseer',
	'DBTools:LostAttachments:Button:Restore' => 'Herstel',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Deze actie kan niet ongedaan worden gemaakt. Bevestig dat je de bijlages wil herstellen.',
	'DBTools:LostAttachments:Button:Busy' => 'Even geduld...',

	'DBTools:LostAttachments:Step:Analyze' => 'Zoek eerst verloren/verkeerd geplaatste bijlages door de database te analyseren.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Resultaten analyse:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Perfect, alles lijkt op de juiste plaats te staan!',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Somme bijlages (%1$d) lijken verkeerd te staan. Overloop de lijst en duid aan welke je wil verplaatsen.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Bestandsnaam',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Huidige locatie',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Verplaats naar ...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Resultaten herstel:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d bijlages werden hersteld.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Opgeslagen als afbeelding in tekst',
	'DBTools:LostAttachments:History' => 'Bijlage "%1$s" werd hersteld met de databasetools'
));
