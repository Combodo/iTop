<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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
 */
// Portal
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Page:DefaultTitle' => '%1$s - Benutzer Portal',
	'Page:PleaseWait' => 'Bitte warten...',
	'Page:Home' => 'Start',
	'Page:GoPortalHome' => 'Startseite',
	'Page:GoPreviousPage' => 'vorherige Seite',
	'Page:ReloadPage' => 'Seite neu laden',
	'Portal:Button:Submit' => 'Abschicken',
	'Portal:Button:Apply' => 'Anwenden',
	'Portal:Button:Cancel' => 'Zurück',
	'Portal:Button:Close' => 'Schließen',
	'Portal:Button:Add' => 'Hinzu',
	'Portal:Button:Remove' => 'Entfernen',
	'Portal:Button:Delete' => 'Löschen',
	'Portal:EnvironmentBanner:Title' => 'Sie sind im Moment im <strong>%1$s</strong> Modus',
	'Portal:EnvironmentBanner:GoToProduction' => 'Zurück zum PRODUCTION Modus',
	'Error:HTTP:400' => 'Bad Request',
	'Error:HTTP:401' => 'Authentifizierung',
	'Error:HTTP:404' => 'Seite nicht gefunden.',
	'Error:HTTP:500' => 'Oops! Es ist ein Fehler aufgetreten.',
	'Error:HTTP:GetHelp' => 'Bitte kontaktieren Sie Ihren %1$s Administrator falls das Problem öfter auftaucht.',
	'Error:XHR:Fail' => 'Konnte Daten nicht laden, bitte kontaktieren Sie Ihren %1$s administrator',
	'Portal:ErrorUserLoggedOut' => 'Sie sind ausgeloggt und müssen sich erneut einloggen, um fortfahren zu können.',
	'Portal:Datatables:Language:Processing' => 'Bitte warten...',
	'Portal:Datatables:Language:Search' => 'Filter :',
	'Portal:Datatables:Language:LengthMenu' => 'Anzahl _MENU_ Einträge pro Seite',
	'Portal:Datatables:Language:ZeroRecords' => 'Keine Resultate',
	'Portal:Datatables:Language:Info' => 'Seite _PAGE_ von _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Keine Information',
	'Portal:Datatables:Language:InfoFiltered' => 'gefiltert aus _MAX_ Resultaten',
	'Portal:Datatables:Language:EmptyTable' => 'Keine Daten in dieser Tabelle verfügbar',
	'Portal:Datatables:Language:DisplayLength:All' => 'Alle',
	'Portal:Datatables:Language:Paginate:First' => '1.Seite',
	'Portal:Datatables:Language:Paginate:Previous' => 'vorherige',
	'Portal:Datatables:Language:Paginate:Next' => 'Nächste',
	'Portal:Datatables:Language:Paginate:Last' => 'Letzte',
	'Portal:Datatables:Language:Sort:Ascending' => 'wähle aufsteigende Sortierung',
	'Portal:Datatables:Language:Sort:Descending' => 'wähle abfallende Sortierung',
	'Portal:Autocomplete:NoResult' => 'keine Daten',
	'Portal:Attachments:DropZone:Message' => 'Legen Sie hier Ihre Files ab, um sie als Anhang dem Ticket hinzuzufügen',
	'Portal:File:None' => 'Kein File vorhanden',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Öffnen</a> / <a href="%4$s" class="file_download_link">Download</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'de', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Soll diese Eingabemaske verlassen werden? Eingegebene Daten werden nicht gespeichert.',
));

// UserProfile brick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:UserProfile:Name' => 'Benutzer Profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mein Profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Abmelden',
	'Brick:Portal:UserProfile:Password:Title' => 'Passwort',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Passwort wählen',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Passwort bestätigen',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Um das Password zu ändern, kontaktieren Sie bitte Ihren %1$s Administrator',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Passwort kann nicht geändert werden - bitte kontaktieren Sie Ihren %1$s Administrator',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Persönliche Informationen',
	'Brick:Portal:UserProfile:Photo:Title' => 'Foto',
));

// AggregatePageBrick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Dashboard',
));

// BrowseBrick brick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:Browse:Name' => 'List durchgehen',
	'Brick:Portal:Browse:Mode:List' => 'Liste',
	'Brick:Portal:Browse:Mode:Tree' => 'Baum',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Kachel',
	'Brick:Portal:Browse:Action:Drilldown' => 'Drilldown',
	'Brick:Portal:Browse:Action:View' => 'Details',
	'Brick:Portal:Browse:Action:Edit' => 'Editieren',
	'Brick:Portal:Browse:Action:Create' => 'Erstellen',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Neue %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Alle expandieren',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Alle kollabieren',
	'Brick:Portal:Browse:Filter:NoData' => 'Kein Eintrag',
));

// ManageBrick brick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:Manage:Name' => 'Einträge managen',
	'Brick:Portal:Manage:Table:NoData' => 'Kein Eintrag.',
	'Brick:Portal:Manage:Table:ItemActions' => 'Aktionen',
	'Brick:Portal:Manage:DisplayMode:list' => 'Liste',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Tortendiagramm',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Balkendiagramm',
	'Brick:Portal:Manage:Others' => 'Andere',
	'Brick:Portal:Manage:All' => 'Alle',
	'Brick:Portal:Manage:Group' => 'Gruppe',
	'Brick:Portal:Manage:fct:count' => 'Anzahl',
	'Brick:Portal:Manage:fct:sum' => 'Summe',
	'Brick:Portal:Manage:fct:avg' => 'Durchschnitt',
	'Brick:Portal:Manage:fct:min' => 'Minimum',
	'Brick:Portal:Manage:fct:max' => 'Maximum',
));

// ObjectBrick brick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'Neue %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Wird aktualisiert %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Bitte folgende Informationen eintragen:',
	'Brick:Portal:Object:Form:Message:Saved' => 'gespeichert',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s gespeichert',
	'Brick:Portal:Object:Search:Regular:Title' => 'Select %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Select %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Objektverknüpfung kopieren',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Kopiert'
));

// CreateBrick brick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:Create:Name' => 'Schnelles Erstellen',
	'Brick:Portal:Create:ChooseType' => 'Bitte wählen Sie einen Typ',
));

// Filter brick
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Brick:Portal:Filter:Name' => 'Brick vorfiltern',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'z.B. connect wifi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Suchen',
));
