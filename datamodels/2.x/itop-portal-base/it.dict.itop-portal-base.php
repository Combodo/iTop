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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Page:DefaultTitle' => '%1$s Portale Utente',
	'Page:PleaseWait' => 'Attendere…',
	'Page:Home' => 'Home',
	'Page:GoPortalHome' => 'Home Page',
	'Page:GoPreviousPage' => 'Pagina precedente',
	'Page:ReloadPage' => 'Ricaricare pagina',
	'Portal:Button:Submit' => 'Invia',
	'Portal:Button:Apply' => 'Aggiorna',
	'Portal:Button:Cancel' => 'Cancella',
	'Portal:Button:Close' => 'Chiudi',
	'Portal:Button:Add' => 'Aggiungi',
	'Portal:Button:Remove' => 'Rimuovi',
	'Portal:Button:Delete' => 'Elimina',
	'Portal:EnvironmentBanner:Title' => 'Sei attualmente in modalità <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Ritorna alla modalità Produzione',
	'Error:HTTP:400' => 'Bad request~~',
	'Error:HTTP:401' => 'Autenticazione',
	'Error:HTTP:404' => 'La Pagina non funziona',
	'Error:HTTP:500' => 'Oops, si è presentato un errore',
	'Error:HTTP:GetHelp' => 'Contattate il suovstro $1$s amministratore se il problema persiste',
	'Error:XHR:Fail' => 'Non è possibile caricare i dati , cotattate il vostro %1$s amministratore',
	'Portal:ErrorUserLoggedOut' => 'Sei disconnesso, bisogna effettuare un nuovo accesso per continuare',
	'Portal:Datatables:Language:Processing' => 'Attendere…',
	'Portal:Datatables:Language:Search' => 'Filtrare:',
	'Portal:Datatables:Language:LengthMenu' => 'Visualizza _MENU_items per pagina',
	'Portal:Datatables:Language:ZeroRecords' => 'Nessun Risultato',
	'Portal:Datatables:Language:Info' => 'Pagina _PAGE_ di _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Nessun Informazione',
	'Portal:Datatables:Language:InfoFiltered' => 'Filtro oltre _MAX_ items',
	'Portal:Datatables:Language:EmptyTable' => 'Nessun dato disponibile per questa tabella',
	'Portal:Datatables:Language:DisplayLength:All' => 'Tutti',
	'Portal:Datatables:Language:Paginate:First' => 'Primo',
	'Portal:Datatables:Language:Paginate:Previous' => 'Precedente',
	'Portal:Datatables:Language:Paginate:Next' => 'Prossimo',
	'Portal:Datatables:Language:Paginate:Last' => 'Ultimo',
	'Portal:Datatables:Language:Sort:Ascending' => 'Attiva per crescente',
	'Portal:Datatables:Language:Sort:Descending' => 'Attiva de decrescente',
	'Portal:Autocomplete:NoResult' => 'No data',
	'Portal:Attachments:DropZone:Message' => 'Trascina il tuo file per aggiungerlo tra gli allegati',
	'Portal:File:None' => 'No File',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>~~',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Open</a> / <a href="%4$s" class="file_download_link">Download</a>~~',
	'Portal:Calendar-FirstDayOfWeek' => 'it', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Do you want to leave this form ? Data entered may be lost~~',
));

// UserProfile brick
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:UserProfile:Name' => 'User profile~~',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'My profile~~',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Logoff~~',
	'Brick:Portal:UserProfile:Password:Title' => 'Password~~',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Choose password~~',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Confirm password~~',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'To change your password, please contact your %1$s administrator~~',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Can\'t change password, please contact your %1$s administrator~~',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Personal informations~~',
	'Brick:Portal:UserProfile:Photo:Title' => 'Photo~~',
));

// AggregatePageBrick
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Dashboard~~',
));

// BrowseBrick brick
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:Browse:Name' => 'Browse throught items~~',
	'Brick:Portal:Browse:Mode:List' => 'List~~',
	'Brick:Portal:Browse:Mode:Tree' => 'Tree~~',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mosaic~~',
	'Brick:Portal:Browse:Action:Drilldown' => 'Drilldown~~',
	'Brick:Portal:Browse:Action:View' => 'Details~~',
	'Brick:Portal:Browse:Action:Edit' => 'Edit~~',
	'Brick:Portal:Browse:Action:Create' => 'Create~~',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'New %1$s~~',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Expand all~~',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Collapse all~~',
	'Brick:Portal:Browse:Filter:NoData' => 'No item~~',
));

// ManageBrick brick
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:Manage:Name' => 'Manage items~~',
	'Brick:Portal:Manage:Table:NoData' => 'No item.~~',
	'Brick:Portal:Manage:Table:ItemActions' => 'Actions~~',
	'Brick:Portal:Manage:DisplayMode:list' => 'List~~',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Pie Chart~~',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Bar Chart~~',
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:Object:Name' => 'Object~~',
	'Brick:Portal:Object:Form:Create:Title' => 'New %1$s~~',
	'Brick:Portal:Object:Form:Edit:Title' => 'Updating %2$s (%1$s)~~',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s~~',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Please, fill the following informations:~~',
	'Brick:Portal:Object:Form:Message:Saved' => 'Saved~~',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s saved~~',
	'Brick:Portal:Object:Search:Regular:Title' => 'Select %1$s (%2$s)~~',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Select %1$s (%2$s)~~',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s~~',
	'Brick:Portal:Object:Copy:Tooltip' => 'Copy object link~~',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Copied~~'
));

// CreateBrick brick
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:Create:Name' => 'Quick creation~~',
	'Brick:Portal:Create:ChooseType' => 'Please, choose a type~~',
));

// Filter brick
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Brick:Portal:Filter:Name' => 'Prefilter a brick~~',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'eg. connect wifi~~',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Search~~',
));
