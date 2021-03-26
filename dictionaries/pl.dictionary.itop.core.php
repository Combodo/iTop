<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Core:DeletedObjectLabel' => '%1s (usunięto)',
	'Core:DeletedObjectTip' => 'Obiekt został usunięty w dniu %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Nie znaleziono obiektu (klasa: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Nie można znaleźć obiektu. Być może został usunięty jakiś czas temu, a od tego czasu dziennik został wyczyszczony.',

	'Core:UniquenessDefaultError' => 'Błąd zasady niepowtarzalności \'%1$s\'',

	'Core:AttributeLinkedSet' => 'Tablica obiektów',
	'Core:AttributeLinkedSet+' => 'Wszelkiego rodzaju obiekty tej samej klasy lub podklasy',

	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplikaty w polu \'%1$s\' : %2$s',

	'Core:AttributeDashboard' => 'Pulpit',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => 'Numer telefonu',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Data utraty ważności',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'Lista tagów',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'kliknij, aby dodać',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s od %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s z klas podrzędnych)',

	'Core:AttributeCaseLog' => 'Log',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Obliczone wyliczenie',
	'Core:AttributeMetaEnum+' => '',

	'Core:AttributeLinkedSetIndirect' => 'Tablica obiektów (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Dowolny rodzaj obiektów [podklasa] tej samej klasy',

	'Core:AttributeInteger' => 'Liczba całkowita',
	'Core:AttributeInteger+' => 'Wartość liczbowa (może być ujemna)',

	'Core:AttributeDecimal' => 'Wartość dziesiętna',
	'Core:AttributeDecimal+' => 'Wartość dziesiętna (może być ujemna)',

	'Core:AttributeBoolean' => 'Wartość logiczna',
	'Core:AttributeBoolean+' => 'Wartość logiczna',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Tak',
	'Core:AttributeBoolean/Value:no' => 'Nie',

	'Core:AttributeArchiveFlag' => 'Flaga archiwum',
	'Core:AttributeArchiveFlag/Value:yes' => 'Tak',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Ten obiekt jest widoczny tylko w trybie archiwum',
	'Core:AttributeArchiveFlag/Value:no' => 'Nie',
	'Core:AttributeArchiveFlag/Label' => 'Zarchiwizowano',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Data archiwizacji',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Flaga utraty ważności',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Tak',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Ten obiekt jest wykluczony z analizy wpływu i ukryty w wynikach wyszukiwania',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Nie',
	'Core:AttributeObsolescenceFlag/Label' => 'Wycofany',
	'Core:AttributeObsolescenceFlag/Label+' => 'Obliczane dynamicznie na innych atrybutach',
	'Core:AttributeObsolescenceDate/Label' => 'Data utraty ważności',
	'Core:AttributeObsolescenceDate/Label+' => 'Przybliżona data, w której obiekt został uznany za wycofany',

	'Core:AttributeString' => 'Ciąg',
	'Core:AttributeString+' => 'Ciąg alfanumeryczny',

	'Core:AttributeClass' => 'Klasa',
	'Core:AttributeClass+' => 'Klasa',

	'Core:AttributeApplicationLanguage' => 'Język użutkownika',
	'Core:AttributeApplicationLanguage+' => 'Język i kraj (EN US)',

	'Core:AttributeFinalClass' => 'Klasa (auto)',
	'Core:AttributeFinalClass+' => 'Prawdziwa klasa obiektu (automatycznie tworzona)',

	'Core:AttributePassword' => 'Hasło',
	'Core:AttributePassword+' => 'Hasło urządzenia zewnętrznego',

	'Core:AttributeEncryptedString' => 'Zaszyfrowany ciąg',
	'Core:AttributeEncryptedString+' => 'Łańcuch zaszyfrowany kluczem lokalnym',
	'Core:AttributeEncryptUnknownLibrary' => 'Określono nieznaną bibliotekę szyfrowania (%1$s)',
	'Core:AttributeEncryptFailedToDecrypt' => '** Błąd deszyfrowania **',

	'Core:AttributeText' => 'Tekst',
	'Core:AttributeText+' => 'Wielowierszowy ciąg znaków',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'Ciąg HTML',

	'Core:AttributeEmailAddress' => 'Adres e-mail',
	'Core:AttributeEmailAddress+' => 'Adres e-mail',

	'Core:AttributeIPAddress' => 'Adres IP',
	'Core:AttributeIPAddress+' => 'Adres IP',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Język wyrażeń zapytania obiektowego OQL',

	'Core:AttributeEnum' => 'Typ wyliczeniowy',
	'Core:AttributeEnum+' => 'Lista predefiniowanych ciągów alfanumerycznych',

	'Core:AttributeTemplateString' => 'Ciąg szablonu',
	'Core:AttributeTemplateString+' => 'Ciąg zawierający symbole zastępcze',

	'Core:AttributeTemplateText' => 'Tekst szablonu',
	'Core:AttributeTemplateText+' => 'Tekst zawierający symbole zastępcze',

	'Core:AttributeTemplateHTML' => 'Szablon HTML',
	'Core:AttributeTemplateHTML+' => 'HTML zawierający symbole zastępcze',

	'Core:AttributeDateTime' => 'Data/czas',
	'Core:AttributeDateTime+' => 'Data i czas (rok-miesiąc-dzień gg:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Format daty:<br/>
	<b>%1$s</b><br/>
	Przykład: %2$s
</p>
<p>
Operatory:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>
<p>
Jeśli czas zostanie pominięty, domyślnie to 00:00:00
</p>',

	'Core:AttributeDate' => 'Data',
	'Core:AttributeDate+' => 'Data (rok-miesiąc-dzień)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Format daty:<br/>
	<b>%1$s</b><br/>
	Przykład: %2$s
</p>
<p>
Operatory:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Ostateczny termin',
	'Core:AttributeDeadline+' => 'Data wyświetlana w stosunku do aktualnego czasu',

	'Core:AttributeExternalKey' => 'Klucz zewnętrzny',
	'Core:AttributeExternalKey+' => 'Klucz zewnętrzny (lub obcy)',

	'Core:AttributeHierarchicalKey' => 'Klucz hierarchiczny',
	'Core:AttributeHierarchicalKey+' => 'Klucz zewnętrzny (lub obcy) do rodzica',

	'Core:AttributeExternalField' => 'Pole zewnętrzne',
	'Core:AttributeExternalField+' => 'Pole mapowane na klucz zewnętrzny',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Bezwzględny lub względny adres URL jako ciąg tekstowy',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Dowolna zawartość binarna (dokument)',

	'Core:AttributeOneWayPassword' => 'Hasło jednokierunkowe',
	'Core:AttributeOneWayPassword+' => 'Hasło zaszyfrowane (mieszane) w jedną stronę',

	'Core:AttributeTable' => 'Tabela',
	'Core:AttributeTable+' => 'Tablica indeksowana mająca dwa wymiary',

	'Core:AttributePropertySet' => 'Właściwości',
	'Core:AttributePropertySet+' => 'Lista nietypowych właściwości (nazwa i wartość)',

	'Core:AttributeFriendlyName' => 'Przyjazna nazwa',
	'Core:AttributeFriendlyName+' => 'Atrybut tworzony automatycznie; przyjazna nazwa jest obliczana po kilku atrybutach',

	'Core:FriendlyName-Label' => 'Pełna nazwa',
	'Core:FriendlyName-Description' => 'Pełna nazwa',

	'Core:AttributeTag' => 'Tagi',
	'Core:AttributeTag+' => 'Tagi',
	
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchronizacja',
	'Core:Context=Setup' => 'Instalacja',
	'Core:Context=GUI:Console' => 'Konsola',
	'Core:Context=CRON' => 'cron',
	'Core:Context=GUI:Portal' => 'Portal',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChange' => 'Zmiana',
	'Class:CMDBChange+' => 'Śledzenie zmian',
	'Class:CMDBChange/Attribute:date' => 'data',
	'Class:CMDBChange/Attribute:date+' => 'data i czas zarejestrowania zmian',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'zdefiniowane informacje gościa',
));

//
// Class: CMDBChangeOp
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOp' => 'Operacje zmian',
	'Class:CMDBChangeOp+' => 'Zmiana dokonana przez osobę na jednym obiekcie w jednostce czasu',
	'Class:CMDBChangeOp/Attribute:change' => 'zmiana',
	'Class:CMDBChangeOp/Attribute:change+' => 'zmiana',
	'Class:CMDBChangeOp/Attribute:date' => 'data',
	'Class:CMDBChangeOp/Attribute:date+' => 'data i czas zmiany',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'użytkownik',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'kto dokonał zmiany',
	'Class:CMDBChangeOp/Attribute:objclass' => 'klasa obiektu',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'klasa obiektu, którego dotyczy zmiana',
	'Class:CMDBChangeOp/Attribute:objkey' => 'id obiektu',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'identyfikator obiektu, którego dotyczy zmiana',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Podklasa CMDBChangeOp',
	'Class:CMDBChangeOp/Attribute:finalclass+' => 'Nazwa finalna klasy gdzie dokonano zmiany',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOpCreate' => 'tworzenie obiektu',
	'Class:CMDBChangeOpCreate+' => 'Śledzenie tworzenia obiektów',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOpDelete' => 'usunięcie obiektu',
	'Class:CMDBChangeOpDelete+' => 'Śledzenie usuwania obiektów',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOpSetAttribute' => 'zmiana obiektu',
	'Class:CMDBChangeOpSetAttribute+' => 'Śledzenie zmian właściwości obiektu',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atrybut',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'kod zmodyfikowanej właściwości',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'zmiana właściwości',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Śledzenie zmian właściwości skalarnych obiektu',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Poprzednia wartość',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'poprzednia wartość atrybutu',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nowa wartość',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'nowa wartość atrybutu',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Change:ObjectCreated' => 'Utworzono obiekt',
	'Change:ObjectDeleted' => 'Obiekt usunięty',
	'Change:ObjectModified' => 'Obiekt zmodyfikowany',
	'Change:TwoAttributesChanged' => 'Zmodyfikowano %1$s i %2$s',
	'Change:ThreeAttributesChanged' => 'Zmodyfikowano %1$s, %2$s i 1 inny',
	'Change:FourOrMoreAttributesChanged' => 'Zmodyfikowano %1$s, %2$s i %3$s inne',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s zmianiono na %2$s (poprzednia wartość: %3$s)',
	'Change:AttName_SetTo' => '%1$s zmieniono na %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s dołączone do %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s zmodyfikowano, poprzednia wartość: %2$s',
	'Change:AttName_Changed' => '%1$s zmodyfikowano',
	'Change:AttName_EntryAdded' => '%1$s zmodyfikowano, dodano nowy wpis: %2$s',
	'Change:State_Changed_NewValue_OldValue' => 'Zmieniono z %2$s na %1$s',
	'Change:LinkSet:Added' => 'dodano %1$s',
	'Change:LinkSet:Removed' => 'usunięto %1$s',
	'Change:LinkSet:Modified' => 'zmodyfikowano %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'zmiana danych',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'śledzenie zmian danych',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Poprzednie dane',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'poprzednia zawartość atrybutu',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:CMDBChangeOpSetAttributeText' => 'zmiana tekstu',
	'Class:CMDBChangeOpSetAttributeText+' => 'śledzenie zmian tekstu',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Poprzednie dane',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'poprzednia zawartość atrybutu',
));

//
// Class: Event
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Event' => 'Dziennik zdarzeń',
	'Class:Event+' => 'Zdarzenie wewnętrzne aplikacji',
	'Class:Event/Attribute:message' => 'Wiadomość',
	'Class:Event/Attribute:message+' => 'krótki opis wydarzenia',
	'Class:Event/Attribute:date' => 'Data',
	'Class:Event/Attribute:date+' => 'data i czas zarejestrowania zmian',
	'Class:Event/Attribute:userinfo' => 'Informacje użytkownika',
	'Class:Event/Attribute:userinfo+' => 'identyfikacja użytkownika wykonującego czynność, która wywołała to zdarzenie',
	'Class:Event/Attribute:finalclass' => 'Podklasa zdarzenia',
	'Class:Event/Attribute:finalclass+' => 'Nazwa finalnej klasy: określa rodzaj zdarzenia, które miało miejsce',
));

//
// Class: EventNotification
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:EventNotification' => 'Powiadomienie o zdarzeniu',
	'Class:EventNotification+' => 'Ślad powiadomienia, które zostało wysłane',
	'Class:EventNotification/Attribute:trigger_id' => 'Wyzwalacz',
	'Class:EventNotification/Attribute:trigger_id+' => 'konto użytkownika',
	'Class:EventNotification/Attribute:action_id' => 'użytkownik',
	'Class:EventNotification/Attribute:action_id+' => 'konto użytkownika',
	'Class:EventNotification/Attribute:object_id' => 'Id obiektu',
	'Class:EventNotification/Attribute:object_id+' => 'id obiektu (klasa zdefiniowana przez wyzwalacz?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:EventNotificationEmail' => 'Zdarzenie wysyłki wiadomości e-mail',
	'Class:EventNotificationEmail+' => 'Ślad e-maila, który został wysłany',
	'Class:EventNotificationEmail/Attribute:to' => 'TO',
	'Class:EventNotificationEmail/Attribute:to+' => 'TO',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'Od',
	'Class:EventNotificationEmail/Attribute:from+' => 'Nadawca wiadomości',
	'Class:EventNotificationEmail/Attribute:subject' => 'Temat',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Temat',
	'Class:EventNotificationEmail/Attribute:body' => 'Treść',
	'Class:EventNotificationEmail/Attribute:body+' => 'Treść',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Załączniki',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:EventIssue' => 'Zdarzenie związane z problemem',
	'Class:EventIssue+' => 'Ślad problemu (ostrzeżenie, błąd itp.)',
	'Class:EventIssue/Attribute:issue' => 'Problem',
	'Class:EventIssue/Attribute:issue+' => 'Co się stało',
	'Class:EventIssue/Attribute:impact' => 'Wpływ',
	'Class:EventIssue/Attribute:impact+' => 'Jakie są konsekwencje',
	'Class:EventIssue/Attribute:page' => 'Strona',
	'Class:EventIssue/Attribute:page+' => 'Punkt wejścia HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Wysłane argumenty',
	'Class:EventIssue/Attribute:arguments_post+' => 'Argumenty HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Argumenty adresu URL',
	'Class:EventIssue/Attribute:arguments_get+' => 'Argumenty HTTP GET',
	'Class:EventIssue/Attribute:callstack' => 'Stos wywołań',
	'Class:EventIssue/Attribute:callstack+' => 'Stos wywołań',
	'Class:EventIssue/Attribute:data' => 'Dane',
	'Class:EventIssue/Attribute:data+' => 'Więcej informacji',
));

//
// Class: EventWebService
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:EventWebService' => 'Usługa internetowa',
	'Class:EventWebService+' => 'Ślad połączenia z usługą internetową',
	'Class:EventWebService/Attribute:verb' => 'Operacja',
	'Class:EventWebService/Attribute:verb+' => 'Nazwa operacji',
	'Class:EventWebService/Attribute:result' => 'Wynik',
	'Class:EventWebService/Attribute:result+' => 'Ogólny sukces / porażka',
	'Class:EventWebService/Attribute:log_info' => 'Dziennik informacyjny',
	'Class:EventWebService/Attribute:log_info+' => 'Wyniki dziennika informacyjnego',
	'Class:EventWebService/Attribute:log_warning' => 'Dziennik ostrzeżeń',
	'Class:EventWebService/Attribute:log_warning+' => 'Wyniki dziennika ostrzeżeń',
	'Class:EventWebService/Attribute:log_error' => 'Dziennik błędów',
	'Class:EventWebService/Attribute:log_error+' => 'Wyniki dziennika błędów',
	'Class:EventWebService/Attribute:data' => 'Dane',
	'Class:EventWebService/Attribute:data+' => 'Dane wynikowe',
));

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:EventRestService' => 'Połączenie REST / JSON',
	'Class:EventRestService+' => 'Śledzenie wywołania usługi REST / JSON',
	'Class:EventRestService/Attribute:operation' => 'Operacja',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operacji\'',
	'Class:EventRestService/Attribute:version' => 'Wersja',
	'Class:EventRestService/Attribute:version+' => 'Argument \'wersji\'',
	'Class:EventRestService/Attribute:json_input' => 'Wejście',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Kod',
	'Class:EventRestService/Attribute:code+' => 'Kod wyniku',
	'Class:EventRestService/Attribute:json_output' => 'Odpowiedź',
	'Class:EventRestService/Attribute:json_output+' => 'Odpowiedź HTTP (json)',
	'Class:EventRestService/Attribute:provider' => 'Dostawca',
	'Class:EventRestService/Attribute:provider+' => 'Klasa PHP implementująca oczekiwaną operację',
));

//
// Class: EventLoginUsage
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:EventLoginUsage' => 'Korzystanie z logowania',
	'Class:EventLoginUsage+' => 'Połączenie z aplikacją',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Nazwa Użytkownika',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Nazwa Użytkownika',
	'Class:EventLoginUsage/Attribute:contact_email' => 'E-mail użytkownika',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Adres e-mail użytkownika',
));

//
// Class: Action
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Action' => 'Działanie własne',
	'Class:Action+' => 'Działanie zdefiniowane przez użytkownika',
	'Class:Action/Attribute:name' => 'Nazwa',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Opis',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'Ten stan steruje działaniem',
	'Class:Action/Attribute:status/Value:test' => 'Testowane',
	'Class:Action/Attribute:status/Value:test+' => 'Testowane',
	'Class:Action/Attribute:status/Value:enabled' => 'W użytkowaniu',
	'Class:Action/Attribute:status/Value:enabled+' => 'W użytkowaniu',
	'Class:Action/Attribute:status/Value:disabled' => 'Nieaktywne',
	'Class:Action/Attribute:status/Value:disabled+' => 'Nieaktywne',
	'Class:Action/Attribute:trigger_list' => 'Powiązane wyzwalacze',
	'Class:Action/Attribute:trigger_list+' => 'Wyzwalacze powiązane z działaniem',
	'Class:Action/Attribute:finalclass' => 'Podklasa działania',
	'Class:Action/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
));

//
// Class: ActionNotification
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ActionNotification' => 'Powiadomienie',
	'Class:ActionNotification+' => 'Powiadomienie (abstrakcja)',
));

//
// Class: ActionEmail
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ActionEmail' => 'Powiadomienie e-mail',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:status+' => 'Ten status decyduje o tym, kto zostanie powiadomiony: tylko odbiorca testowy, wszyscy (Do, DW i UDW) lub nikt',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Powiadomiony zostanie tylko odbiorca testowy',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Wszystkie e-maile "Do", "DW" i "UDW" są powiadamiane',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'Powiadomienie e-mail nie zostanie wysłane',
	'Class:ActionEmail/Attribute:test_recipient' => 'Odbiorca testowy',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Miejsce docelowe w przypadku, gdy status jest ustawiony na "Test"',
	'Class:ActionEmail/Attribute:from' => 'Z',
	'Class:ActionEmail/Attribute:from+' => 'Zostanie wysłany do nagłówka wiadomości e-mail',
	'Class:ActionEmail/Attribute:from_label' => 'Z (etykieta)',
	'Class:ActionEmail/Attribute:from_label+' => 'Wyświetlana nazwa nadawcy zostanie wysłana do nagłówka wiadomości e-mail',
	'Class:ActionEmail/Attribute:reply_to' => 'Odpowiedź do',
	'Class:ActionEmail/Attribute:reply_to+' => 'Zostanie wysłany do nagłówka wiadomości e-mail',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Odpowiedź do (etykieta)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Odpowiedź do zostanie wysłana do nagłówka wiadomości e-mail',
	'Class:ActionEmail/Attribute:to' => 'Do',
	'Class:ActionEmail/Attribute:to+' => 'Miejsce docelowe wiadomości e-mail',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Ukryta kopia',
	'Class:ActionEmail/Attribute:bcc' => 'Bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Bardzo ukryta kopia',
	'Class:ActionEmail/Attribute:subject' => 'Temat',
	'Class:ActionEmail/Attribute:subject+' => 'Tytuł wiadomości e-mail',
	'Class:ActionEmail/Attribute:body' => 'Treść',
	'Class:ActionEmail/Attribute:body+' => 'Treść wiadomości e-mail',
	'Class:ActionEmail/Attribute:importance' => 'ważna',
	'Class:ActionEmail/Attribute:importance+' => 'Flaga ważności',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'niska',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'niska',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normalna',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normalna',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'wysoka',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'wysoka',
));

//
// Class: Trigger
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Trigger' => 'Wyzwalacz',
	'Class:Trigger+' => 'Niestandardowa obsługa zdarzeń',
	'Class:Trigger/Attribute:description' => 'Opis',
	'Class:Trigger/Attribute:description+' => 'jedna linia opisu',
	'Class:Trigger/Attribute:action_list' => 'Działania wyzwalacza',
	'Class:Trigger/Attribute:action_list+' => 'Działania wykonywane po aktywacji wyzwalacza',
	'Class:Trigger/Attribute:finalclass' => 'Podklasa wyzwalacza',
	'Class:Trigger/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
	'Class:Trigger/Attribute:context' => 'Kontekst',
	'Class:Trigger/Attribute:context+' => 'Kontekst umożliwiający uruchomienie wyzwalacza',
));

//
// Class: TriggerOnObject
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnObject' => 'Wyzwalacz (zależny od klasy)',
	'Class:TriggerOnObject+' => 'Wyzwalanie na danej klasie obiektów',
	'Class:TriggerOnObject/Attribute:target_class' => 'Klasa docelowa',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filtr',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Błędne zapytanie filtru: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'Zapytanie filtru musi zwracać obiekty klasy "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnPortalUpdate' => 'Wyzwalacz (po aktualizacji z portalu)',
	'Class:TriggerOnPortalUpdate+' => 'Wyzwalanie po aktualizacji użytkownika z portalu',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnStateChange' => 'Wyzwalacz (przy zmianie stanu)',
	'Class:TriggerOnStateChange+' => 'Wyzwalanie przy zmianie stanu obiektu',
	'Class:TriggerOnStateChange/Attribute:state' => 'Stan',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnStateEnter' => 'Wyzwalacz (przy wejściu w stan)',
	'Class:TriggerOnStateEnter+' => 'Wyzwalanie przy zmianie stanu obiektu - wejście',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnStateLeave' => 'Wyzwalacz (przy opuszczaniu stanu)',
	'Class:TriggerOnStateLeave+' => 'Wyzwalanie przy zmianie stanu obiektu - wyjście',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnObjectCreate' => 'Wyzwalacz (przy tworzeniu obiektu)',
	'Class:TriggerOnObjectCreate+' => 'Wyzwalacz przy tworzeniu obiektu [klasy potomnej] danej klasy',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnObjectDelete' => 'Wyzwalacz (przy usunięciu obiektu)',
	'Class:TriggerOnObjectDelete+' => 'Wyzwalanie w przypadku usunięcia obiektu [klasy potomnej] danej klasy',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnObjectUpdate' => 'Wyzwalacz (przy aktualizacji obiektu)',
	'Class:TriggerOnObjectUpdate+' => 'Wyzwalanie przy aktualizacji obiektu [klasy potomnej] danej klasy',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Pola docelowe',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnObjectMention' => 'Wyzwalacz (przy wzmiance o obiekcie)',
	'Class:TriggerOnObjectMention+' => 'Wyzwalanie przy wzmiance (@xxx) o obiekcie [klasy potomnej] danej klasy w atrybucie dziennika',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TriggerOnThresholdReached' => 'Wyzwalacz (na progu)',
	'Class:TriggerOnThresholdReached+' => 'Osiągnięto próg wyzwalania przy stoperze',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stoper',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Próg',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkTriggerAction' => 'Działanie / wyzwalacz',
	'Class:lnkTriggerAction+' => 'Powiązanie między wyzwalaczem a działaniem',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Działanie',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Działanie do wykonania',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Działanie',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Wyzwalacz',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Wyzwalacz',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Order',
	'Class:lnkTriggerAction/Attribute:order+' => 'Kolejność wykonywania działań',
));

//
// Synchro Data Source
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:SynchroDataSource/Attribute:name' => 'Nazwa',
	'Class:SynchroDataSource/Attribute:name+' => 'Nazwa',
	'Class:SynchroDataSource/Attribute:description' => 'Opis',
	'Class:SynchroDataSource/Attribute:status' => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Klasa docelowa',
	'Class:SynchroDataSource/Attribute:user_id' => 'Użytkownik',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Kontakt do powiadomienia',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Kontakt do powiadomienia w przypadku błędu',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Hiperłącze do ikony',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hiperłącze (mały) obraz przedstawiający aplikację, z którą synchronizowany jest '.ITOP_APPLICATION_SHORT,
	'Class:SynchroDataSource/Attribute:url_application' => 'Hiperłącze do aplikacji',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hiperłącze do obiektu '.ITOP_APPLICATION_SHORT.' w zewnętrznej aplikacji, z którą '.ITOP_APPLICATION_SHORT.' jest zsynchronizowany (jeśli dotyczy). Możliwe symbole zastępcze: $this->attribute$ i $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Polityka uzgadniania',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Pełen interwał ładowania',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Całkowite przeładowanie wszystkich danych musi następować co najmniej tak często, jak określono w tym miejscu',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Działanie dla zera',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Działanie podejmowane, gdy wyszukiwanie nie zwraca żadnego obiektu',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Działanie dla jednego',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Działanie podejmowane, gdy wyszukiwanie zwraca dokładnie jeden obiekt',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Działanie na wielu',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Akcja podejmowana, gdy wyszukiwanie zwraca więcej niż jeden obiekt',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Użytkownicy dozwoleni',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Kto może usuwać zsynchronizowane obiekty',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nikt',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Tylko administratorzy',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Wszyscy dozwoleni użytkownicy',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Zaktualizuj zasady',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Składnia: field_name:value; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Okres przechowywania',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Czas przechowywania wycofanego obiektu przed usunięciem',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Tabela danych',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Nazwa tabeli do przechowywania danych synchronizacji. Jeśli pozostanie puste, zostanie obliczona nazwa domyślna.',
	'SynchroDataSource:Description' => 'Opis',
	'SynchroDataSource:Reconciliation' => 'Wyszukiwanie &amp; uzgodnione',
	'SynchroDataSource:Deletion' => 'Zasady usuwania',
	'SynchroDataSource:Status' => 'Status',
	'SynchroDataSource:Information' => 'Informacja',
	'SynchroDataSource:Definition' => 'Definicja',
	'Core:SynchroAttributes' => 'Atrybuty',
	'Core:SynchroStatus' => 'Status',
	'Core:Synchro:ErrorsLabel' => 'Błędy',
	'Core:Synchro:CreatedLabel' => 'Utworzony',
	'Core:Synchro:ModifiedLabel' => 'Zmieniony',
	'Core:Synchro:UnchangedLabel' => 'Bez zmian',
	'Core:Synchro:ReconciledErrorsLabel' => 'Błędy',
	'Core:Synchro:ReconciledLabel' => 'Uzgodniony',
	'Core:Synchro:ReconciledNewLabel' => 'Utworzony',
	'Core:SynchroReconcile:Yes' => 'Tak',
	'Core:SynchroReconcile:No' => 'Nie',
	'Core:SynchroUpdate:Yes' => 'Tak',
	'Core:SynchroUpdate:No' => 'Nie',
	'Core:Synchro:LastestStatus' => 'Ostatni Status',
	'Core:Synchro:History' => 'Historia synchronizacji',
	'Core:Synchro:NeverRun' => 'Synchronizacja nigdy nie został uruchomiona. Nie ma jeszcze dziennika.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Ostatnia synchronizacja zakończyła się w dniu %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Synchronizacja rozpoczęta w dniu %1$s nadal działa...',
	'Menu:DataSources' => 'Źródła danych synchronizacji', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Wszystkie źródła danych synchronizacji', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Zignorowano (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Zaginięte (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Istniejące (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nowe (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Usunięte (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Wycofane (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Błędy (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Brak działań (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Bez zmian (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Zmieniony (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Błędy (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Bez zmian (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Zmieniony (%1$s)',
	'Core:Synchro:label_obj_created' => 'Utworzony (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Błędy (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replika przetworzona: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Należy określić przynajmniej jeden klucz uzgadniania lub zasady uzgadniania muszą używać klucza podstawowego.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Należy określić okres przechowywania podczas usuwania, ponieważ obiekty mają zostać usunięte po oznaczeniu ich jako wycofane',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Wycofane obiekty mają zostać zaktualizowane, ale nie określono aktualizacji.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Tabela %1$s już istnieje w bazie danych. Użyj innej nazwy dla tabeli danych synchronizacji.',
	'Core:SynchroReplica:PublicData' => 'Dane publiczne',
	'Core:SynchroReplica:PrivateDetails' => 'Dane prywatne',
	'Core:SynchroReplica:BackToDataSource' => 'Wróć do źródła danych synchronizacji: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lista replik',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Klucz podstawowy)',
	'Core:SynchroAtt:attcode' => 'Atrybut',
	'Core:SynchroAtt:attcode+' => 'Pole obiektu',
	'Core:SynchroAtt:reconciliation' => 'Uzgodnienie ?',
	'Core:SynchroAtt:reconciliation+' => 'Używane do wyszukiwania',
	'Core:SynchroAtt:update' => 'Aktualizacja ?',
	'Core:SynchroAtt:update+' => 'Służy do aktualizacji obiektu',
	'Core:SynchroAtt:update_policy' => 'Zasady aktualizacji',
	'Core:SynchroAtt:update_policy+' => 'Zachowanie zaktualizowanego pola',
	'Core:SynchroAtt:reconciliation_attcode' => 'Klucz uzgodnienia',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Kod atrybutu dla zewnętrznego uzgadniania kluczy',
	'Core:SyncDataExchangeComment' => '(Żródła danych)',
	'Core:Synchro:ListOfDataSources' => 'Lista źródeł danych:',
	'Core:Synchro:LastSynchro' => 'Ostatnia synchronizacja:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Ten obiekt jest synchronizowany z zewnętrznym źródłem danych',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Obiekt został <b>utworzony</b> przez zewnętrzne źródło danych %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Obiekt <b>może zostać usunięty</b> przez zewnętrzne źródło danych %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Nie możesz <b>usunąć obiektu</b>, ponieważ należy on do zewnętrznego źródła danych %1$s',
	'TitleSynchroExecution' => 'Wykonanie synchronizacji',
	'Class:SynchroDataSource:DataTable' => 'Tabela bazy danych: %1$s',
	'Core:SyncDataSourceObsolete' => 'Źródło danych jest oznaczone jako wycofane. Operacja anulowana.',
	'Core:SyncDataSourceAccessRestriction' => 'Tylko administratorzy lub użytkownik określony w źródle danych mogą wykonać tę operację. Operacja anulowana.',
	'Core:SyncTooManyMissingReplicas' => 'Wszystkie rekordy były od jakiegoś czasu nietknięte (wszystkie obiekty można było usunąć). Sprawdź, czy proces zapisujący w tabeli synchronizacji nadal działa. Operacja anulowana.',
	'Core:SyncSplitModeCLIOnly' => 'Synchronizacja może być wykonywana fragmentami tylko wtedy, gdy jest uruchomiona w trybie CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replik, %2$s błąd(y), %3$s ostrzeżenie(a).',
	'Core:SynchroReplica:TargetObject' => 'Zsynchronizowany obiekt: %1$s',
	'Class:AsyncSendEmail' => 'E-mail (asynchroniczny)',
	'Class:AsyncSendEmail/Attribute:to' => 'Do',
	'Class:AsyncSendEmail/Attribute:subject' => 'Temat',
	'Class:AsyncSendEmail/Attribute:body' => 'Treść',
	'Class:AsyncSendEmail/Attribute:header' => 'Nagłówek',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Zaszyfrowane hasło',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Poprzednia wartość',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Zaszyfrowane pole',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Poprzednia wartość',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Dziennik przypadku',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Ostatni wpis',
	'Class:SynchroDataSource' => 'Źródło danych synchronizacji',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Wdrażane',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Wycofane',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Użytkowane',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Ograniczenie zakresu',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Użycie atrybutów',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Użycie pola primary_key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Utwórz',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Błąd',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Błąd',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Zmień',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Utwórz',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Błąd',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Weź pierwszy (losowy?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Zasada usuwania',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Usuń',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignoruj',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Zmień',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Zaktualizuj, a potem Usuń',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lista atrybutów',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Tylko administratorzy',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Każdy mógże usunąć te obiekty',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nikt',
	'Class:SynchroAttribute' => 'Atrybut synchronizacji',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Źródło danych synchronizacji',
	'Class:SynchroAttribute/Attribute:attcode' => 'Kod atrybutu',
	'Class:SynchroAttribute/Attribute:update' => 'Zmień',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Uzgodnienie',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Zasady aktualizacji',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Zablokowane',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Odblokowane',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Zainicjuj, jeśli jest pusty',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Klasa',
	'Class:SynchroAttExtKey' => 'Atrybut synchronizacji (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Atrybut uzgodnienia',
	'Class:SynchroAttLinkSet' => 'Atrybut synchronizacji (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Separator wierszy',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Separator atrybutów',
	'Class:SynchroLog' => 'Dziennik synchronizacji',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Źródło danych synchronizacji',
	'Class:SynchroLog/Attribute:start_date' => 'Data rozpoczęcia',
	'Class:SynchroLog/Attribute:end_date' => 'Data zakończenia',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Zakończony',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Błąd',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Nadal działa',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nr replik widocznych',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nr wszystkich replik',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nr obiektów usuniętych',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nr błędów podczas usuwania',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nr obiektów wycofanych',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nr błędów podczas wycofywania',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nr obiektów utworzonych',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nr błędów podczas tworzenia',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nr obiektów zaktualizowanych',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nr błędów podczas aktualizacji',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nr błędów podczas uzgadniania',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nr replik zaginiętych',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nr obiektów zaktualizowanych',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nr obiektów niezmienionych',
	'Class:SynchroLog/Attribute:last_error' => 'Ostatni błąd',
	'Class:SynchroLog/Attribute:traces' => 'Ślady',
	'Class:SynchroReplica' => 'Replika synchronizacji',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Źródło danych synchronizacji',
	'Class:SynchroReplica/Attribute:dest_id' => 'Obiekt docelowy (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Docelowy typ',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Ostatnio widoczny',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Zmieniony',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Nowy',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Wycofany',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Sierota',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Zsynchronizowane',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Obiekt utworzony ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Ostatni błąd',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Ostrzeżenia',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Data utworzenia',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Data ostatniej zmiany',
	'Class:appUserPreferences' => 'Preferencje użytkownika',
	'Class:appUserPreferences/Attribute:userid' => 'Użytkownik',
	'Class:appUserPreferences/Attribute:preferences' => 'Preferencje',
	'Core:ExecProcess:Code1' => 'Niewłaściwe polecenie lub polecenie zakończone błędami (np. zła nazwa skryptu)',
	'Core:ExecProcess:Code255' => 'Błąd PHP (parsowanie lub środowisko uruchomieniowe)',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Czas, który upłynął (przechowywany jako "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Czas spędzony dla "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Ostateczny termin dla "%1$s" w %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Brak parametru "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Nieprawidłowa wartość parametru "query". Nie ma słownika zapytań odpowiadającego identyfikatorowi: "%1$s".',
	'Core:BulkExport:ExportFormatPrompt' => 'Format eksportu:',
	'Core:BulkExportOf_Class' => '%1$s Eksport',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Kliknij tutaj, aby pobrać %1$s',
	'Core:BulkExport:ExportResult' => 'Wynik eksportu:',
	'Core:BulkExport:RetrievingData' => 'Pobieranie danych...',
	'Core:BulkExport:HTMLFormat' => 'Strona internetowa (*.html)',
	'Core:BulkExport:CSVFormat' => 'Wartości oddzielone przecinkami (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 lub nowszy (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Dokument PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Przeciągnij i upuść nagłówki kolumn, aby uporządkować kolumny. Podgląd %1$s linii. Całkowita liczba linii do wyeksportowania: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Wybierz kolumny do wyeksportowania z powyższej listy',
	'Core:BulkExport:ColumnsOrder' => 'Kolejność kolumn',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Dostępne kolumny od %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Wybierz co najmniej jedną kolumnę do wyeksportowania',
	'Core:BulkExport:CheckAll' => 'Zaznacz wszystkie',
	'Core:BulkExport:UncheckAll' => 'Odznacz wszystkie',
	'Core:BulkExport:ExportCancelledByUser' => 'Eksport anulowany przez użytkownika',
	'Core:BulkExport:CSVOptions' => 'Opcje CSV',
	'Core:BulkExport:CSVLocalization' => 'Lokalizacja',
	'Core:BulkExport:PDFOptions' => 'Opcje PDF',
	'Core:BulkExport:PDFPageFormat' => 'Format strony',
	'Core:BulkExport:PDFPageSize' => 'Rozmiar strony:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter',
	'Core:BulkExport:PDFPageOrientation' => 'Orientacja strony:',
	'Core:BulkExport:PageOrientation-L' => 'Krajobraz',
	'Core:BulkExport:PageOrientation-P' => 'Portret',
	'Core:BulkExport:XMLFormat' => 'Plik XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Opcje XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Format arkusza HTML (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Opcje arkusza HTML',
	'Core:BulkExport:OptionNoLocalize' => 'Kod eksportu zamiast etykiety',
	'Core:BulkExport:OptionLinkSets' => 'Uwzględnij połączone obiekty',
	'Core:BulkExport:OptionFormattedText' => 'Zachowaj formatowanie tekstu',
	'Core:BulkExport:ScopeDefinition' => 'Definicja obiektów do eksportu',
	'Core:BulkExportLabelOQLExpression' => 'Zapytanie OQL:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Wpis do słownika zapytań:',
	'Core:BulkExportMessageEmptyOQL' => 'Wprowadź prawidłowe zapytanie OQL.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Wybierz prawidłowy wpis ze słownika.',
	'Core:BulkExportQueryPlaceholder' => 'Wpisz tutaj zapytanie OQL...',
	'Core:BulkExportCanRunNonInteractive' => 'Kliknij tutaj, aby uruchomić eksport w trybie nieinteraktywnym.',
	'Core:BulkExportLegacyExport' => 'Kliknij tutaj, aby uzyskać dostęp do starszego eksportu.',
	'Core:BulkExport:XLSXOptions' => 'Opcje Excel',
	'Core:BulkExport:TextFormat' => 'Pola tekstowe zawierające znaczniki HTML',
	'Core:BulkExport:DateTimeFormat' => 'Format daty i czasu',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Domyślny format (%1$s), np. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Własny format: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Strona %1$s',
	'Core:DateTime:Placeholder_d' => 'DD', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Zły format',
	'Core:Validator:Mandatory' => 'Proszę wypełnić to pole',
	'Core:Validator:MustBeInteger' => 'Musi być liczbą całkowitą',
	'Core:Validator:MustSelectOne' => 'Proszę wybrać jeden',
));

//
// Class: TagSetFieldData
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TagSetFieldData' => '%2$s dla klasy %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Kod',
	'Class:TagSetFieldData/Attribute:code+' => 'Kod wewnętrzny. Musi zawierać co najmniej 3 znaki alfanumeryczne',
	'Class:TagSetFieldData/Attribute:label' => 'Etykieta',
	'Class:TagSetFieldData/Attribute:label+' => 'Wyświetlana etykieta',
	'Class:TagSetFieldData/Attribute:description' => 'Opis',
	'Class:TagSetFieldData/Attribute:description+' => 'Opis',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Klasa Tagu~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Klasa obiektu~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Kod pola~~',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Nie można usunąć używanych tagów',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Kody tagów lub etykiety muszą być unikalne',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Kod tagu musi zawierać od 3 do %1$d znaków alfanumerycznych, zaczynając od litery.',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'Wybrany kod tagu jest słowem zastrzeżonym',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Etykieta tagów nie może zawierać \'%1$s\' ani być pusta',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Kodu tagów nie można zmienić, gdy jest używany',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tagów "Klasa obiektów" nie można zmieniać',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tagów "Kod atrybutu" nie można zmieniać',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Użycie tagu (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Nie znaleziono wpisu dla tego tagu',
));

//
// Class: DBProperty
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DBProperty' => 'Właściwości DB',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Nazwa',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Opis',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Wartość',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Data zmiany',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Komentarz zmiany',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:BackgroundTask' => 'Zadanie w tle',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Nazwa klasy',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Data pierwszego uruchomienia',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Data ostatniego uruchomienia',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Data następnego uruchomienia',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Liczba wszystkich uruchomień',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Ostatni czas trwania',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. czas trwania',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. czas trwania',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Średni czas trwania',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Działa',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Status',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:AsyncTask' => 'Zadanie asynchroniczne',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Utworzono',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Rozpoczęto',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Zaplanowano',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Zdarzenie',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Klasa docelowa',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Status',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Pozostałe próby',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Ostatni kod błędu',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Ostatni błąd',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Ostatnia próba',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
));

//
// Class: AbstractResource
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:AbstractResource' => 'Zasób abstrakcyjny',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ResourceAdminMenu' => 'Zasób Menu administratora',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ResourceRunQueriesMenu' => 'Zasób Menu zapytań uruchamiania',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ResourceSystemMenu' => 'Zasób Menu systemowe',
	'Class:ResourceSystemMenu+' => '',
));


