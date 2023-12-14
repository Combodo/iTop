<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Page:DefaultTitle' => '%1$s Felhasználói portál',
	'Page:PleaseWait' => 'Kérem várjon...',
	'Page:Home' => 'Kezdőlap',
	'Page:GoPortalHome' => 'Kezdőlap',
	'Page:GoPreviousPage' => 'Előző oldal',
	'Page:ReloadPage' => 'Oldal újratöltése',
	'Portal:Button:Submit' => 'Beküldés',
	'Portal:Button:Apply' => 'Alkalmazás',
	'Portal:Button:Cancel' => 'Mégsem',
	'Portal:Button:Close' => 'Bezárás',
	'Portal:Button:Add' => 'Hozzáadás',
	'Portal:Button:Remove' => 'Eltávolítás',
	'Portal:Button:Delete' => 'Törlés',
	'Portal:EnvironmentBanner:Title' => 'Jelenleg <strong>%1$s</strong> módban van',
	'Portal:EnvironmentBanner:GoToProduction' => 'Visszatérés az ÉLES módba',
	'Error:HTTP:400' => 'Hibás kérelem',
	'Error:HTTP:401' => 'Azonosítás',
	'Error:HTTP:404' => 'Az oldal nem található',
	'Error:HTTP:500' => 'Hopp! Valami hiba történt.',
	'Error:HTTP:GetHelp' => 'Kérjük, lépjen kapcsolatba a %1$s rendszergazdával, ha a probléma továbbra is fennáll.',
	'Error:XHR:Fail' => 'Nem sikerült betölteni az adatokat, kérjük, lépjen kapcsolatba a %1$s rendszergazdával',
	'Portal:ErrorUserLoggedOut' => 'Kijelentkezett, és a folytatáshoz újra be kell jelentkeznie.',
	'Portal:Datatables:Language:Processing' => 'Kérem várjon...',
	'Portal:Datatables:Language:Search' => 'Szűrő:',
	'Portal:Datatables:Language:LengthMenu' => '_MENU_ elemek kijelzése oldalanként',
	'Portal:Datatables:Language:ZeroRecords' => 'Nincs eredmény',
	'Portal:Datatables:Language:Info' => '_PAGE_ oldal a _PAGES_ oldalból',
	'Portal:Datatables:Language:InfoEmpty' => 'Nincs információ',
	'Portal:Datatables:Language:InfoFiltered' => 'leszűrve _MAX_ elemből',
	'Portal:Datatables:Language:EmptyTable' => 'Nincs adat ehhez a táblázathoz',
	'Portal:Datatables:Language:DisplayLength:All' => 'Összes',
	'Portal:Datatables:Language:Paginate:First' => 'Első',
	'Portal:Datatables:Language:Paginate:Previous' => 'Előző',
	'Portal:Datatables:Language:Paginate:Next' => 'Következő',
	'Portal:Datatables:Language:Paginate:Last' => 'Utolsó',
	'Portal:Datatables:Language:Sort:Ascending' => 'Növekvő rendezés',
	'Portal:Datatables:Language:Sort:Descending' => 'Csökkenő rendezés',
	'Portal:Autocomplete:NoResult' => 'Nincs adat',
	'Portal:Attachments:DropZone:Message' => 'Húzza ide a fájlokat, hogy csatolmányként hozzáadhassa őket',
	'Portal:File:None' => 'Nincs fájl',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Megnyitás</a> / <a href="%4$s" class="file_download_link">Letöltés</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'hu', //work with moment.js locales
));

// Object form
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Portal:Form:Caselog:Entry:Close:Tooltip' => 'Bejegyzés bezárása',
	'Portal:Form:Close:Warning' => 'Szeretné elhagyni ezt az űrlapot? A megadott adatok elveszhetnek',
	'Portal:Error:ObjectCannotBeCreated' => 'Hiba: az objektum nem hozható létre. Ellenőrizze a kapcsolódó objektumokat és mellékleteket, mielőtt újra elküldi ezt az űrlapot.',
	'Portal:Error:ObjectCannotBeUpdated' => 'Hiba: az objektum nem frissíthető. Ellenőrizze a kapcsolódó objektumokat és mellékleteket, mielőtt újra elküldi ezt az űrlapot.',
));

// UserProfile brick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:UserProfile:Name' => 'Felhasználói profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Saját profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Kijelentkezés',
	'Brick:Portal:UserProfile:Password:Title' => 'Jelszó',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Új jelszó',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Jelszó megerősítése',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Jelszóváltoztatáshoz forduljon a %1$s rendszergazdához',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Nem tudja megváltoztatni a jelszót, lépjen kapcsolatba a %1$s rendszergazdával',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Személyi adatok',
	'Brick:Portal:UserProfile:Photo:Title' => 'Fénykép',
));

// AggregatePageBrick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Műszerfal',
));

// BrowseBrick brick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:Browse:Name' => 'Böngésszen az elemek között',
	'Brick:Portal:Browse:Mode:List' => 'Lista',
	'Brick:Portal:Browse:Mode:Tree' => 'Fa',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mozaik',
	'Brick:Portal:Browse:Action:Drilldown' => 'Lefúrás',
	'Brick:Portal:Browse:Action:View' => 'Részletek',
	'Brick:Portal:Browse:Action:Edit' => 'Szerkesztés',
	'Brick:Portal:Browse:Action:Create' => 'Létrehozás',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Új %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Összes kinyitása',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Összecsukás',
	'Brick:Portal:Browse:Filter:NoData' => 'Nincs elem',
));

// ManageBrick brick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:Manage:Name' => 'Elemek kezelése',
	'Brick:Portal:Manage:Table:NoData' => 'Nincs elem',
	'Brick:Portal:Manage:Table:ItemActions' => 'Műveletek',
	'Brick:Portal:Manage:DisplayMode:list' => 'Lista',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Tortadiagram',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Oszlopdiagram',
	'Brick:Portal:Manage:Others' => 'Egyéb',
	'Brick:Portal:Manage:All' => 'Összes',
	'Brick:Portal:Manage:Group' => 'Csoport',
	'Brick:Portal:Manage:fct:count' => 'Összesen',
	'Brick:Portal:Manage:fct:sum' => 'Összeg',
	'Brick:Portal:Manage:fct:avg' => 'Átlag',
	'Brick:Portal:Manage:fct:min' => 'Minimum',
	'Brick:Portal:Manage:fct:max' => 'Maximum',
));

// ObjectBrick brick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:Object:Name' => 'Objektum',
	'Brick:Portal:Object:Form:Create:Title' => 'Új %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => '%2$s frissítése (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Adja meg a következő információkat:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Mentve',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s mentve',
	'Brick:Portal:Object:Search:Regular:Title' => '%1$s kiválasztása (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => '%1$s kiválasztása (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Objektum hivatkozás másolása',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Másolva'
));

// CreateBrick brick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:Create:Name' => 'Gyors létrehozás',
	'Brick:Portal:Create:ChooseType' => 'Válasszon típust',
));

// Filter brick
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Brick:Portal:Filter:Name' => 'Tégla előszűrése',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'pl. wifi kapcsolat',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Keresés',
));
