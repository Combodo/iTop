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
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Page:DefaultTitle' => '%1$s portal użytkownika',
	'Page:PleaseWait' => 'Proszę czekać...',
	'Page:Home' => 'Start',
	'Page:GoPortalHome' => 'Strona startowa',
	'Page:GoPreviousPage' => 'Poprzednia strona',
	'Page:ReloadPage' => 'Odśwież stronę',
	'Portal:Button:Submit' => 'Zatwierdź',
	'Portal:Button:Apply' => 'Aktualizuj',
	'Portal:Button:Cancel' => 'Anuluj',
	'Portal:Button:Close' => 'Zamknij',
	'Portal:Button:Add' => 'Dodaj',
	'Portal:Button:Remove' => 'Usuń',
	'Portal:Button:Delete' => 'Kasuj',
	'Portal:EnvironmentBanner:Title' => 'Aktualnie jesteś w trybie <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Wróć do trybu PRODUKCYJNEGO',
	'Error:HTTP:400' => 'Zła prośba',
	'Error:HTTP:401' => 'Autentykacja',
	'Error:HTTP:404' => 'nie znaleziono strony',
	'Error:HTTP:500' => 'Ups! Wystąpił błąd.',
	'Error:HTTP:GetHelp' => 'Skontaktuj się z administratorem %1$s, jeśli problem będzie się powtarzał.',
	'Error:XHR:Fail' => 'Nie można załadować danych. Skontaktuj się z administratorem %1$s',
	'Portal:ErrorUserLoggedOut' => 'Jesteś wylogowany i musisz zalogować się ponownie, aby kontynuować.',
	'Portal:Datatables:Language:Processing' => 'Proszę czekać...',
	'Portal:Datatables:Language:Search' => 'Filtr:',
	'Portal:Datatables:Language:LengthMenu' => 'Wyświetlaj elementów _MENU_ na stronie',
	'Portal:Datatables:Language:ZeroRecords' => 'Brak wyników',
	'Portal:Datatables:Language:Info' => 'Strona _PAGE_ z _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Brak informacji',
	'Portal:Datatables:Language:InfoFiltered' => 'wyfiltrowanych z _MAX_ elementów',
	'Portal:Datatables:Language:EmptyTable' => 'Brak danych w tej tabeli',
	'Portal:Datatables:Language:DisplayLength:All' => 'Wszystkie',
	'Portal:Datatables:Language:Paginate:First' => 'Pierwszy',
	'Portal:Datatables:Language:Paginate:Previous' => 'poprzedni',
	'Portal:Datatables:Language:Paginate:Next' => 'Następny',
	'Portal:Datatables:Language:Paginate:Last' => 'Ostatni',
	'Portal:Datatables:Language:Sort:Ascending' => 'włącza sortowanie rosnąco',
	'Portal:Datatables:Language:Sort:Descending' => 'włącza sortowanie malejąco',
	'Portal:Autocomplete:NoResult' => 'Brak danych',
	'Portal:Attachments:DropZone:Message' => 'Upuść pliki, aby dodać je jako załączniki',
	'Portal:File:None' => 'Brak pliku',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Open</a> / <a href="%4$s" class="file_download_link">Pobierz</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'en-us', //work with moment.js locales
));


// Object form
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Portal:Form:Caselog:Entry:Close:Tooltip' => 'Zamknij ten wpis',
	'Portal:Form:Close:Warning' => 'Chcesz opuścić ten formularz? Wprowadzone dane mogą zostać utracone',
));

// UserProfile brick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:UserProfile:Name' => 'Profil użytkownika',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mój profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Wyloguj',
	'Brick:Portal:UserProfile:Password:Title' => 'Hasło',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Wpisz hasło',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Potwierdź hasło',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Aby zmienić hasło, skontaktuj się z administratorem %1$s',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Nie można zmienić hasła. Skontaktuj się z administratorem %1$s',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Informacje osobiste',
	'Brick:Portal:UserProfile:Photo:Title' => 'Zdjęcie',
));

// AggregatePageBrick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Pulpit',
));

// BrowseBrick brick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:Browse:Name' => 'Przeglądaj elementy',
	'Brick:Portal:Browse:Mode:List' => 'Lista',
	'Brick:Portal:Browse:Mode:Tree' => 'Drzewo',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mozaika',
	'Brick:Portal:Browse:Action:Drilldown' => 'Lista rozwijana',
	'Brick:Portal:Browse:Action:View' => 'Szczegóły',
	'Brick:Portal:Browse:Action:Edit' => 'Edytuj',
	'Brick:Portal:Browse:Action:Create' => 'Utwórz',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Nowy %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Rozwiń wszystkie',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Zwiń wszystkie',
	'Brick:Portal:Browse:Filter:NoData' => 'Brak elementu',
));

// ManageBrick brick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:Manage:Name' => 'Zarządzaj elementami',
	'Brick:Portal:Manage:Table:NoData' => 'Brak elementu.',
	'Brick:Portal:Manage:Table:ItemActions' => 'Akcje',
	'Brick:Portal:Manage:DisplayMode:list' => 'Lista',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Wykres kołowy',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Wykres słupkowy',
	'Brick:Portal:Manage:Others' => 'Inne',
	'Brick:Portal:Manage:All' => 'Wszystkie',
	'Brick:Portal:Manage:Group' => 'Grupa',
	'Brick:Portal:Manage:fct:count' => 'Razem',
	'Brick:Portal:Manage:fct:sum' => 'Suma',
	'Brick:Portal:Manage:fct:avg' => 'Średnia',
	'Brick:Portal:Manage:fct:min' => 'Minimum',
	'Brick:Portal:Manage:fct:max' => 'Maksimum',
));

// ObjectBrick brick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:Object:Name' => 'Obiekty',
	'Brick:Portal:Object:Form:Create:Title' => 'Nowy %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Aktualizacja %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s: %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Prosimy o uzupełnienie poniższych informacji:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Zapisany',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s zapisany',
	'Brick:Portal:Object:Search:Regular:Title' => 'Wybierz %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Wybierz %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Skopiuj obiekt',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Skopiowano'
));

// CreateBrick brick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:Create:Name' => 'Szybkie tworzenie',
	'Brick:Portal:Create:ChooseType' => 'Proszę wybrać typ',
));

// Filter brick
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Brick:Portal:Filter:Name' => 'Wstępny filtr',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'na przykład. podłącz wifi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Szukaj',
));
