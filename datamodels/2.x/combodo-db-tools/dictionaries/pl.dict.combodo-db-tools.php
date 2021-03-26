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
 */

// Database inconsistencies
Dict::Add('PL PL', 'Polish', 'Polski', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Integralność bazy danych',
	'DBTools:Class' => 'Klasa',
	'DBTools:Title' => 'Narzędzia do konserwacji bazy danych',
	'DBTools:ErrorsFound' => 'Znalezione błędy',
	'DBTools:Error' => 'Błąd',
	'DBTools:Count' => 'Liczba',
	'DBTools:SQLquery' => 'Zapytanie SQL',
	'DBTools:FixitSQLquery' => 'Zapytanie SQL, aby to naprawić (wskazanie)',
	'DBTools:SQLresult' => 'Wynik SQL',
	'DBTools:NoError' => 'Baza danych jest w porządku',
	'DBTools:HideIds' => 'Lista błędów',
	'DBTools:ShowIds' => 'Widok szczegółowy',
	'DBTools:ShowReport' => 'Raport',
	'DBTools:IntegrityCheck' => 'Sprawdzanie integralności',
	'DBTools:FetchCheck' => 'Sprawdzenie przestrzeni (długie)',

	'DBTools:Analyze' => 'Analiza',
	'DBTools:Details' => 'Pokaż szczegóły',
	'DBTools:ShowAll' => 'Pokaż wszystkie błędy',

	'DBTools:Inconsistencies' => 'Niespójności bazy danych',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Osierocony rekord w `%1$s`, powinien mieć swój odpowiednik w tabeli `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Nieprawidłowy klucz zewnętrzny %1$s (kolumna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Brak klucza zewnętrznego %1$s (kolumna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Nieprawidłowa wartość dla %1$s (kolumna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Niektóre konta użytkowników w ogóle nie mają profilu',
	'DBAnalyzer-Fetch-Count-Error' => 'Błąd liczby wpisów w `%1$s`, %2$d pobrane wpisy / %3$d obliczone',
	'DBAnalyzer-Integrity-FinalClass' => 'Pole `%2$s`.`%1$s` musi mieć taką samą wartość jak `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Pole `%2$s`.`%1$s` musi zawierać prawidłową klasę',
));

// Database Info
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'DBTools:DatabaseInfo' => 'Informacje o bazie danych',
	'DBTools:Base' => 'Baza',
	'DBTools:Size' => 'Rozmiar',
));

// Lost attachments
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'DBTools:LostAttachments' => 'Utracone załączniki',
	'DBTools:LostAttachments:Disclaimer' => 'Tutaj możesz przeszukiwać bazę danych w poszukiwaniu zagubionych załączników. To NIE jest narzędzie do odzyskiwania danych, nie pobiera usuniętych danych.',

	'DBTools:LostAttachments:Button:Analyze' => 'Analiza',
	'DBTools:LostAttachments:Button:Restore' => 'Przywróć',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Tej czynności nie można cofnąć, potwierdź, że chcesz przywrócić wybrane pliki.',
	'DBTools:LostAttachments:Button:Busy' => 'Proszę czekać...',

	'DBTools:LostAttachments:Step:Analyze' => 'Najpierw wyszukaj zagubione załączniki, analizując bazę danych.',

	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Wynik analizy:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Świetnie! Wszystko wydaje się być na właściwym miejscu.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Niektóre załączniki (%1$d) wydają się być zagubione. Spójrz na poniższą listę i zaznacz te, które chcesz przenieść.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nazwa pliku',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Aktualna lokalizacja',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Przenieś do...',

	'DBTools:LostAttachments:Step:RestoreResults' => 'Wyniki przywracania:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d załączniki zostały przywrócone.',

	'DBTools:LostAttachments:StoredAsInlineImage' => 'Zapisane jako obraz w treści',
	'DBTools:LostAttachments:History' => 'Załącznik "%1$s" przywrócony za pomocą narzędzi DB'
));
