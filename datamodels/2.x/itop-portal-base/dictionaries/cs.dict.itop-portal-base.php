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
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Page:DefaultTitle' => 'Uživatelský portál %1$s',
	'Page:PleaseWait' => 'Počkejte prosím',
	'Page:Home' => 'Domů',
	'Page:GoPortalHome' => 'Domů',
	'Page:GoPreviousPage' => 'Předchozí stránka',
	'Page:ReloadPage' => 'Znovunačtení stránky',
	'Portal:Button:Submit' => 'Odeslat',
	'Portal:Button:Apply' => 'Aktualizovat',
	'Portal:Button:Cancel' => 'Zrušit',
	'Portal:Button:Close' => 'Zavřít',
	'Portal:Button:Add' => 'Přidat',
	'Portal:Button:Remove' => 'Odebrat',
	'Portal:Button:Delete' => 'Smazat',
	'Portal:EnvironmentBanner:Title' => 'Pracujete v režimu <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Přejít zpátky do PRUDUKČNÍHO režimu',
	'Error:HTTP:400' => 'Špatný požadavek',
	'Error:HTTP:401' => 'Ověřování',
	'Error:HTTP:404' => 'Stránka nenalezena',
	'Error:HTTP:500' => 'Jejda! Nastal problém',
	'Error:HTTP:GetHelp' => 'Kontaktujte prosím administrátora, pokud problém přetrvá.',
	'Error:XHR:Fail' => 'Data se nepodařilo načíst, kontaktujte prosím administrátora.',
	'Portal:ErrorUserLoggedOut' => 'Jste odhlášen, pro pokračování se musíte znovu přihlásit.',
	'Portal:Datatables:Language:Processing' => 'Počkejte prosím',
	'Portal:Datatables:Language:Search' => 'Filtr :',
	'Portal:Datatables:Language:LengthMenu' => 'Zobrazit _MENU_ položek na stránku',
	'Portal:Datatables:Language:ZeroRecords' => 'Žádný výsledek',
	'Portal:Datatables:Language:Info' => 'Stránka _PAGE_ z _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Žádná informace',
	'Portal:Datatables:Language:InfoFiltered' => 'vyfiltrováno z _MAX_ položek',
	'Portal:Datatables:Language:EmptyTable' => 'Žádná data',
	'Portal:Datatables:Language:DisplayLength:All' => 'Vše',
	'Portal:Datatables:Language:Paginate:First' => 'První',
	'Portal:Datatables:Language:Paginate:Previous' => 'Předchozí',
	'Portal:Datatables:Language:Paginate:Next' => 'Následující',
	'Portal:Datatables:Language:Paginate:Last' => 'Poslední',
	'Portal:Datatables:Language:Sort:Ascending' => 'řadit vzestupně',
	'Portal:Datatables:Language:Sort:Descending' => 'řadit sestupně',
	'Portal:Autocomplete:NoResult' => 'Žádná data',
	'Portal:Attachments:DropZone:Message' => 'Přesuňte soubory myší pro vložení',
	'Portal:File:None' => 'Žádný soubor nenalezen',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Otevřít</a> / <a href="%4$s" class="file_download_link">Stáhnout</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'cs', //work with moment.js locales
]);

// Object form
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Portal:Form:Caselog:Entry:Close:Tooltip' => 'Zavřít tento vstup',
	'Portal:Form:Close:Warning' => 'Opravdu chcete opustit tento formulář? Data vložená do formuláře budou ztracena ',
	'Portal:Error:ObjectCannotBeCreated' => 'Chyba: objekt nelze vytvořit. Před opětovným odesláním tohoto formuláře zkontrolujte související objekty a přílohy.',
	'Portal:Error:ObjectCannotBeUpdated' => 'Chyba: objekt nelze vytvořit. Před opětovným odesláním tohoto formuláře zkontrolujte související objekty a přílohy.',
	'Portal:Error:CheckToWriteFailed' => 'Error during validation of field \'%1$s\': %2$s~~',
]);

// UserProfile brick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:UserProfile:Name' => 'Uživatelský profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Můj profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Odhlásit',
	'Brick:Portal:UserProfile:Password:Title' => 'Heslo',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Zadejte heslo',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Potvrďte heslo',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Pro změnu hesla kontaktujte administrátora',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Nepodařilo se změnit heslo, kontaktujte prosím administrátora',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Osobní informace',
	'Brick:Portal:UserProfile:Photo:Title' => 'Foto',
]);

// AggregatePageBrick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Nástěnka',
]);

// BrowseBrick brick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:Browse:Name' => 'Procházet položky',
	'Brick:Portal:Browse:Mode:List' => 'Seznam',
	'Brick:Portal:Browse:Mode:Tree' => 'Strom',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mozaika',
	'Brick:Portal:Browse:Action:Drilldown' => 'Rozpad',
	'Brick:Portal:Browse:Action:View' => 'Podrobnosti',
	'Brick:Portal:Browse:Action:Edit' => 'Upravit',
	'Brick:Portal:Browse:Action:Create' => 'Vytvořit',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Nový %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Rozbalit vše',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Sbalit vše',
	'Brick:Portal:Browse:Filter:NoData' => 'Žádná položka',
	'Brick:Portal:Browse:Mosaic:Back' => 'Zpět',
]);

// ManageBrick brick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:Manage:Name' => 'Spravovat položky',
	'Brick:Portal:Manage:Table:NoData' => 'Žádná položka',
	'Brick:Portal:Manage:Table:ItemActions' => 'Akce',
	'Brick:Portal:Manage:DisplayMode:list' => 'List',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Koláčový graf',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Sloupcový graf',
	'Brick:Portal:Manage:Others' => 'Ostatní',
	'Brick:Portal:Manage:All' => 'Vše',
	'Brick:Portal:Manage:Group' => 'Skupina',
	'Brick:Portal:Manage:fct:count' => 'Celkem',
	'Brick:Portal:Manage:fct:sum' => 'Suma',
	'Brick:Portal:Manage:fct:avg' => 'Průměr',
	'Brick:Portal:Manage:fct:min' => 'Min',
	'Brick:Portal:Manage:fct:max' => 'Max',
]);

// ObjectBrick brick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:Object:Name' => 'Objekt',
	'Brick:Portal:Object:Form:Create:Title' => 'Nový %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Aktualizace %1$s',
	'Brick:Portal:Object:Form:View:Title' => '%1$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Vyplňte prosím následující informace:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Uloženo',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s uloženo',
	'Brick:Portal:Object:Search:Regular:Title' => 'Vybrat %1$s',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Vybrat %1$s',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Zkopíruj odkaz na objekt',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Zkopírováno',
]);

// CreateBrick brick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:Create:Name' => 'Rychlé vytvoření',
	'Brick:Portal:Create:ChooseType' => 'Vyberte typ',
]);

// Filter brick
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Brick:Portal:Filter:Name' => 'Předfiltrování dlaždice',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'např. připojení k wifi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Vyhledat',
]);
