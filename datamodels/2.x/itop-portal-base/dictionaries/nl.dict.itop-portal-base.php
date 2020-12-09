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
/**
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 */
// Portal
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Page:DefaultTitle' => '%1$s Gebruikersportaal',
	'Page:PleaseWait' => 'Even geduld...',
	'Page:Home' => 'Welkom',
	'Page:GoPortalHome' => 'Startpagina',
	'Page:GoPreviousPage' => 'Vorige pagina',
	'Page:ReloadPage' => 'Pagina herladen',
	'Portal:Button:Submit' => 'Verstuur',
	'Portal:Button:Apply' => 'Update',
	'Portal:Button:Cancel' => 'Afbreken',
	'Portal:Button:Close' => 'Sluiten',
	'Portal:Button:Add' => 'Toevoegen',
	'Portal:Button:Remove' => 'Verwijderen',
	'Portal:Button:Delete' => 'Verwijderen',
	'Portal:EnvironmentBanner:Title' => 'Je werkt momenteel in de <strong>%1$s</strong>-omgeving',
	'Portal:EnvironmentBanner:GoToProduction' => 'Keer terug naar de productie-omgeving',
	'Error:HTTP:400' => 'Ongeldig verzoek',
	'Error:HTTP:401' => 'Aanmelden is vereist',
	'Error:HTTP:404' => 'Pagina kan niet worden gevonden',
	'Error:HTTP:500' => 'Oeps! Er is een fout opgetreden',
	'Error:HTTP:GetHelp' => 'Neem contact op met de beheerder als dit probleem zich blijft voordoen',
	'Error:XHR:Fail' => 'De data kan niet worden geladen, neem contact op met de beheerder',
	'Portal:ErrorUserLoggedOut' => 'Je bent afgemeld en moet opnieuw aanmelden om verder te kunnen werken.',
	'Portal:Datatables:Language:Processing' => 'Even geduld...',
	'Portal:Datatables:Language:Search' => 'Filter :',
	'Portal:Datatables:Language:LengthMenu' => 'Toon _MENU_ items per pagina',
	'Portal:Datatables:Language:ZeroRecords' => 'Geen resultaten',
	'Portal:Datatables:Language:Info' => 'Pagina _PAGE_ van _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Geen informatie',
	'Portal:Datatables:Language:InfoFiltered' => 'gefilterd van _MAX_ items',
	'Portal:Datatables:Language:EmptyTable' => 'Geen data beschikbaar in deze tabel',
	'Portal:Datatables:Language:DisplayLength:All' => 'Alles',
	'Portal:Datatables:Language:Paginate:First' => 'Eerste',
	'Portal:Datatables:Language:Paginate:Previous' => 'Vorige',
	'Portal:Datatables:Language:Paginate:Next' => 'Volgende',
	'Portal:Datatables:Language:Paginate:Last' => 'Laatste',
	'Portal:Datatables:Language:Sort:Ascending' => 'inschakelen voor een oplopende sortering',
	'Portal:Datatables:Language:Sort:Descending' => 'inschakelen voor een aflopende sortering',
	'Portal:Autocomplete:NoResult' => 'Geen data',
	'Portal:Attachments:DropZone:Message' => 'Sleep jouw bestanden hier om ze toe te voegen',
	'Portal:File:None' => 'Geen bestand',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Open</a> / <a href="%4$s" class="file_download_link">Download</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'nl', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Ben je zeker dat je dit venster wil sluiten? Ingevoerde gegevens kunnen verloren gaan.',
));

// UserProfile brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:UserProfile:Name' => 'Gebruikersprofiel',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mijn profiel',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Uitloggen',
	'Brick:Portal:UserProfile:Password:Title' => 'Wachtwoord',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Nieuw wachtwoord',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Bevestig nieuw wachtwoord',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Neem contact op met de beheerder om jouw wachtwoord te wijzgen',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Jouw wachtwoord kan niet gewijzigd worden. Neem contact op met de beheerder',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Persoonlijke informatie',
	'Brick:Portal:UserProfile:Photo:Title' => 'Foto',
));

// AggregatePageBrick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Dashboard',
));

// BrowseBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Browse:Name' => 'Bladeren',
	'Brick:Portal:Browse:Mode:List' => 'Lijst',
	'Brick:Portal:Browse:Mode:Tree' => 'Boomstructuur',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mozaïek',
	'Brick:Portal:Browse:Action:Drilldown' => 'Drilldown',
	'Brick:Portal:Browse:Action:View' => 'Details',
	'Brick:Portal:Browse:Action:Edit' => 'Wijzigen',
	'Brick:Portal:Browse:Action:Create' => 'Aanmaken',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Nieuw %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Toon alles',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Verberg alles',
	'Brick:Portal:Browse:Filter:NoData' => 'Geen gegevens',
));

// ManageBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Manage:Name' => 'Beheer items',
	'Brick:Portal:Manage:Table:NoData' => 'Geen gegevens',
	'Brick:Portal:Manage:Table:ItemActions' => 'Acties',
	'Brick:Portal:Manage:DisplayMode:list' => 'Lijst',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Taartgrafiek',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Staafgrafiek',
	'Brick:Portal:Manage:Others' => 'Andere',
	'Brick:Portal:Manage:All' => 'Alles',
	'Brick:Portal:Manage:Group' => 'Groep',
	'Brick:Portal:Manage:fct:count' => 'Totaal',
	'Brick:Portal:Manage:fct:sum' => 'Som',
	'Brick:Portal:Manage:fct:avg' => 'Gemiddelde',
	'Brick:Portal:Manage:fct:min' => 'Min',
	'Brick:Portal:Manage:fct:max' => 'Max',
));

// ObjectBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'Nieuw %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Verwerken %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Vul de volgende informatie in:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Opgeslagen',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s opgeslagen',
	'Brick:Portal:Object:Search:Regular:Title' => 'Geselecteerd %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Selecteer %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Kopieer link naar object',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Gekopieerd'
));

// CreateBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Create:Name' => 'Snel aanmaken',
	'Brick:Portal:Create:ChooseType' => 'Geef een type op.',
));

// Filter brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Filter:Name' => 'Voorfilteren van een bouwsteen',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'bv. wifi-verbinding',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Zoek',
));
