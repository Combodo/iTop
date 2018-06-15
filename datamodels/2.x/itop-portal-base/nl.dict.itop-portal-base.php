<?php

// Copyright (C) 2010-2015 Combodo SARL
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

/**
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license	 http://opensource.org/licenses/AGPL-3.0
 */


// Portal
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Page:DefaultTitle' => '%1$s Gebruikersportaal',
	'Page:PleaseWait' => 'Even geduld...',
	'Page:Home' => 'Welkom',
	'Page:GoPortalHome' => 'Startpagina',
	'Page:GoPreviousPage' => 'Vorige pagina',
    'Page:ReloadPage' => 'Reload page~~',
	'Portal:Button:Submit' => 'Verstuur',
    'Portal:Button:Apply' => 'Update~~',
	'Portal:Button:Cancel' => 'Afbreken',
	'Portal:Button:Close' => 'Sluiten',
	'Portal:Button:Add' => 'Toevoegen',
	'Portal:Button:Remove' => 'Verwijderen',
	'Portal:Button:Delete' => 'Verwijderen',
    'Error:HTTP:401' => 'Authentication~~',
    'Error:HTTP:404' => 'Pagina kan niet worden gevonden',
	'Error:HTTP:500' => 'Oeps! Er is een fout opgetreden',
	'Error:HTTP:GetHelp' => 'Neem contact op met de beheerder als dit probleem zich blijft voordoen',
	'Error:XHR:Fail' => 'De data kan niet worden geladen, neem contact op met de beheerder',
    'Portal:ErrorUserLoggedOut' => 'You are logged out and need to log in again in order to continue.~~',
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
	'Portal:Attachments:DropZone:Message' => 'Plaats jouw bestanden om ze bij te voegen',
));

// UserProfile brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:UserProfile:Name' => 'Gebruikersprofiel',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mijn profiel',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Uitloggen',
	'Brick:Portal:UserProfile:Password:Title' => 'Wachtwoord',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Nieuw wachtwoord',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Bevestig nieuwe wachtwoord',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Neem contact op met de beheerder om uw wachtwoord te wijzgen',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Uw wachtwoord kan niet worden gewijzigd, neem contact op met de beheerder',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Persoonlijke informatie',
	'Brick:Portal:UserProfile:Photo:Title' => 'Foto',
));

// BrowseBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Browse:Name' => 'Bladeren',
	'Brick:Portal:Browse:Mode:List' => 'Lijst',
	'Brick:Portal:Browse:Mode:Tree' => 'Boomstructuur',
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
    'Brick:Portal:Manage:Table:ItemActions' => 'Actions~~',
    'Brick:Portal:Manage:DisplayMode:list' => 'List~~',
    'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Pie Chart~~',
    'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Bar Chart',
    'Brick:Portal:Manage:Others' => 'Others~~',
    'Brick:Portal:Manage:All' => 'All~~',
    'Brick:Portal:Manage:Group' => 'Group~~',
    'Brick:Portal:Manage:fct:count' => 'Total~~',
    'Brick:Portal:Manage:fct:sum' => 'Sum~~',
    'Brick:Portal:Manage:fct:avg' => 'Average~~',
    'Brick:Portal:Manage:fct:min' => 'Min~~',
    'Brick:Portal:Manage:fct:max' => 'Max~~',
));

// ObjectBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'Nieuw %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Verwerken %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Vul alstublieft de volgende informatie in:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Opgeslagen',
	'Brick:Portal:Object:Search:Regular:Title' => 'Geselecteerd %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Selecteer %1$s (%2$s)',
));

// CreateBrick brick
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Brick:Portal:Create:Name' => 'Snel aanmaken',
));
?>
