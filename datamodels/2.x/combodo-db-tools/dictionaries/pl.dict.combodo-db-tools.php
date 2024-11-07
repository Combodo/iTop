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
Dict::Add('PL PL', 'Polish', 'Polski', [
	'DBAnalyzer-Fetch-Count-Error' => 'Błąd liczby wpisów w `%1$s`, %2$d pobrane wpisy / %3$d obliczone',
	'DBAnalyzer-Integrity-FinalClass' => 'Pole `%2$s`.`%1$s` musi mieć taką samą wartość jak `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-HKInvalid' => 'Nieprawidłowy klucz hierarchiczny `%1$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Nieprawidłowy klucz zewnętrzny %1$s (kolumna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Nieprawidłowa wartość dla %1$s (kolumna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Brak klucza zewnętrznego %1$s (kolumna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Osierocony rekord w `%1$s`, powinien mieć swój odpowiednik w tabeli `%2$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Pole `%2$s`.`%1$s` musi zawierać prawidłową klasę',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Niektóre konta użytkowników w ogóle nie mają profilu',
	'DBTools:Analyze' => 'Analiza',
	'DBTools:Base' => 'Baza',
	'DBTools:Class' => 'Klasa',
	'DBTools:Count' => 'Liczba',
	'DBTools:DatabaseInfo' => 'Informacje o bazie danych',
	'DBTools:DetailedErrorLimit' => 'Lista ograniczona do %1$s błędów',
	'DBTools:DetailedErrorTitle' => '%2$s błąd(y) w klasie %1$s: %3$s',
	'DBTools:Details' => 'Pokaż szczegóły',
	'DBTools:Disclaimer' => 'OŚWIADCZENIE: PRZED URUCHOMIENIEM POPRAWEK NALEŻY WYKONAĆ KOPIĘ ZAPASOWĄ BAZY DANYCH',
	'DBTools:Error' => 'Błąd',
	'DBTools:ErrorsFound' => 'Znalezione błędy',
	'DBTools:FetchCheck' => 'Sprawdzenie przestrzeni (długie)',
	'DBTools:FixitSQLquery' => 'Zapytanie SQL, aby to naprawić (wskazanie)',
	'DBTools:HideIds' => 'Lista błędów',
	'DBTools:Inconsistencies' => 'Niespójności bazy danych',
	'DBTools:Indication' => 'Ważne: po naprawieniu błędów w bazie danych będziesz musiał ponownie uruchomić analizę, ponieważ będą generowane nowe niespójności',
	'DBTools:IntegrityCheck' => 'Sprawdzanie integralności',
	'DBTools:LostAttachments' => 'Utracone załączniki',
	'DBTools:LostAttachments:Button:Analyze' => 'Analiza',
	'DBTools:LostAttachments:Button:Busy' => 'Proszę czekać...',
	'DBTools:LostAttachments:Button:Restore' => 'Przywróć',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Tej czynności nie można cofnąć, potwierdź, że chcesz przywrócić wybrane pliki.',
	'DBTools:LostAttachments:Disclaimer' => 'Tutaj możesz przeszukiwać bazę danych w poszukiwaniu zagubionych załączników. To NIE jest narzędzie do odzyskiwania danych, nie pobiera usuniętych danych.',
	'DBTools:LostAttachments:History' => 'Załącznik "%1$s" przywrócony za pomocą narzędzi DB',
	'DBTools:LostAttachments:Step:Analyze' => 'Najpierw wyszukaj zagubione załączniki, analizując bazę danych.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Wynik analizy:',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Aktualna lokalizacja',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nazwa pliku',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Przenieś do...',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Świetnie! Wszystko wydaje się być na właściwym miejscu.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Niektóre załączniki (%1$d) wydają się być zagubione. Spójrz na poniższą listę i zaznacz te, które chcesz przenieść.',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Wyniki przywracania:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d załączniki zostały przywrócone.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Zapisane jako obraz w treści',
	'DBTools:NoError' => 'Baza danych jest w porządku',
	'DBTools:SQLquery' => 'Zapytanie SQL',
	'DBTools:SQLresult' => 'Wynik SQL',
	'DBTools:SelectAnalysisType' => 'Wybierz typ analizy',
	'DBTools:ShowAll' => 'Pokaż wszystkie błędy',
	'DBTools:ShowIds' => 'Widok szczegółowy',
	'DBTools:ShowReport' => 'Raport',
	'DBTools:Size' => 'Rozmiar',
	'DBTools:Title' => 'Narzędzia do konserwacji bazy danych',
	'Menu:DBToolsMenu' => 'Integralność bazy danych',
]);
