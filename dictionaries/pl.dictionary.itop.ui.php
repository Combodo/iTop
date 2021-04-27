<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:AuditCategory' => 'Kategoria audytu',
	'Class:AuditCategory+' => 'Sekcja w ramach ogólnego audytu',
	'Class:AuditCategory/Attribute:name' => 'Nazwa kategorii',
	'Class:AuditCategory/Attribute:name+' => 'Krótka nazwa kategorii',
	'Class:AuditCategory/Attribute:description' => 'Opis kategorii audytu',
	'Class:AuditCategory/Attribute:description+' => 'Długi opis kategorii audytu',
	'Class:AuditCategory/Attribute:definition_set' => 'Zestaw definicji',
	'Class:AuditCategory/Attribute:definition_set+' => 'Wyrażenie OQL definiujące zbiór obiektów do audytu',
	'Class:AuditCategory/Attribute:rules_list' => 'Zasady audytu',
	'Class:AuditCategory/Attribute:rules_list+' => 'Zasady audytu dla kategorii',
));

//
// Class: AuditRule
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:AuditRule' => 'Reguła audytu',
	'Class:AuditRule+' => 'Reguła sprawdzania dla danej kategorii audytu',
	'Class:AuditRule/Attribute:name' => 'Nazwa reguły',
	'Class:AuditRule/Attribute:name+' => 'Krótka nazwa reguły',
	'Class:AuditRule/Attribute:description' => 'Opis reguły audytu',
	'Class:AuditRule/Attribute:description+' => 'Długi opis reguły inspekcji',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Klasa znacznika (tag)',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Klasa obiektu',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Kod pola',
	'Class:AuditRule/Attribute:query' => 'Zapytanie do wykonania',
	'Class:AuditRule/Attribute:query+' => 'Wyrażenie OQL do wykonania',
	'Class:AuditRule/Attribute:valid_flag' => 'Prawidłowe obiekty?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Prawda, jeśli reguła zwraca prawidłowe obiekty, w przeciwnym razie fałsz',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'prawda',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'prawda',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'fałsz',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'fałsz',
	'Class:AuditRule/Attribute:category_id' => 'Kategoria',
	'Class:AuditRule/Attribute:category_id+' => 'Kategoria dla reguły',
	'Class:AuditRule/Attribute:category_name' => 'Kategoria',
	'Class:AuditRule/Attribute:category_name+' => 'Nazwa kategorii dla reguły',
));

//
// Class: QueryOQL
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Query' => 'Zapytanie',
	'Class:Query+' => 'Zapytanie to zbiór danych zdefiniowany w sposób dynamiczny',
	'Class:Query/Attribute:name' => 'Nazwa',
	'Class:Query/Attribute:name+' => 'Identyfikacja zapytania',
	'Class:Query/Attribute:description' => 'Opis',
	'Class:Query/Attribute:description+' => 'Długi opis zapytania (cel, zastosowanie itp.)',
	'Class:Query/Attribute:is_template' => 'Szablon dla pól OQL',
	'Class:Query/Attribute:is_template+' => 'Może służyć jako źródło OQL odbiorcy w powiadomieniach',
	'Class:Query/Attribute:is_template/Value:yes' => 'Tak',
	'Class:Query/Attribute:is_template/Value:no' => 'Nie',
	'Class:QueryOQL/Attribute:fields' => 'Pola',
	'Class:QueryOQL/Attribute:fields+' => 'Rozdzielana przecinkami lista atrybutów (lub alias.attribute) do wyeksportowania',
	'Class:QueryOQL' => 'Zapytanie OQL',
	'Class:QueryOQL+' => 'Zapytanie oparte na języku zapytań obiektowych (OQL)',
	'Class:QueryOQL/Attribute:oql' => 'Wyrażenie',
	'Class:QueryOQL/Attribute:oql+' => 'Wyrażenie OQL',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:User' => 'Użytkownik',
	'Class:User+' => 'Login użytkownika',
	'Class:User/Attribute:finalclass' => 'Typ konta',
	'Class:User/Attribute:finalclass+' => 'Nazwa klasy konta',
	'Class:User/Attribute:contactid' => 'Osoba',
	'Class:User/Attribute:contactid+' => 'Dane osobowe z danych biznesowych',
	'Class:User/Attribute:org_id' => 'Organizacja',
	'Class:User/Attribute:org_id+' => 'Organizacja osoby kontaktowej',
	'Class:User/Attribute:last_name' => 'Nazwisko',
	'Class:User/Attribute:last_name+' => 'Nazwisko osoby kontaktowej',
	'Class:User/Attribute:first_name' => 'Imię',
	'Class:User/Attribute:first_name+' => 'Imię osoby kontaktowej',
	'Class:User/Attribute:email' => 'E-mail',
	'Class:User/Attribute:email+' => 'E-mail osoby kontaktowej',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'ciąg identyfikacyjny użytkownika',
	'Class:User/Attribute:language' => 'Język',
	'Class:User/Attribute:language+' => 'Język użytkownika',
	'Class:User/Attribute:language/Value:EN US' => 'Angielski',
	'Class:User/Attribute:language/Value:EN US+' => 'Angielski (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'Francuski',
	'Class:User/Attribute:language/Value:FR FR+' => 'Francuski (Francja)',
	'Class:User/Attribute:profile_list' => 'Profil',
	'Class:User/Attribute:profile_list+' => 'Role, nadane prawa osobie',
	'Class:User/Attribute:allowed_org_list' => 'Dozwolone organizacje',
	'Class:User/Attribute:allowed_org_list+' => 'Użytkownik końcowy może przeglądać dane należące do następujących organizacji. Jeśli nie określono organizacji, nie ma ograniczeń.',
	'Class:User/Attribute:status' => 'Status',
	'Class:User/Attribute:status+' => 'Czy konto użytkownika jest włączone czy wyłączone.',
	'Class:User/Attribute:status/Value:enabled' => 'Włączone',
	'Class:User/Attribute:status/Value:disabled' => 'Wyłączone',

	'Class:User/Error:LoginMustBeUnique' => 'Login musi być unikatowy - "%1s" jest już używany.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Do użytkownika musi być przypisany co najmniej jeden profil.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Do użytkownika musi być przypisana co najmniej jedna organizacja.',
	'Class:User/Error:OrganizationNotAllowed' => 'Organizacja niedozwolona.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'Konto użytkownika nie należy do Twoich dozwolonych organizacji.',
	'Class:User/Error:PersonIsMandatory' => 'Kontakt jest obowiązkowy.',
	'Class:UserInternal' => 'Użytkownik wewnętrzny',
	'Class:UserInternal+' => 'Użytkownik zdefiniowany w ramach '.ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_Profiles' => 'Profil',
	'Class:URP_Profiles+' => 'Profil użytkownika',
	'Class:URP_Profiles/Attribute:name' => 'Nazwa',
	'Class:URP_Profiles/Attribute:name+' => 'etykieta',
	'Class:URP_Profiles/Attribute:description' => 'opis',
	'Class:URP_Profiles/Attribute:description+' => 'jeden wiersz opisu',
	'Class:URP_Profiles/Attribute:user_list' => 'Użytkownicy',
	'Class:URP_Profiles/Attribute:user_list+' => 'osoby pełniące tę rolę',
));

//
// Class: URP_Dimensions
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_Dimensions' => 'wymiar',
	'Class:URP_Dimensions+' => 'wymiar aplikacji (definiowanie silosów)',
	'Class:URP_Dimensions/Attribute:name' => 'Nazwa',
	'Class:URP_Dimensions/Attribute:name+' => 'etykieta',
	'Class:URP_Dimensions/Attribute:description' => 'Opis',
	'Class:URP_Dimensions/Attribute:description+' => 'jeden wiersz opisu',
	'Class:URP_Dimensions/Attribute:type' => 'Typ',
	'Class:URP_Dimensions/Attribute:type+' => 'nazwa klasy lub typ danych (jednostka projekcji)',
));

//
// Class: URP_UserProfile
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_UserProfile' => 'Profil użytkownika',
	'Class:URP_UserProfile+' => 'profile użytkowników',
	'Class:URP_UserProfile/Attribute:userid' => 'Użytkownik',
	'Class:URP_UserProfile/Attribute:userid+' => 'konto użytkownika',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Login użytkownika',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Profil użytkowania',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nazwa profilu',
	'Class:URP_UserProfile/Attribute:reason' => 'Powód',
	'Class:URP_UserProfile/Attribute:reason+' => 'wyjaśnij, dlaczego ta osoba może pełnić tę rolę',
));

//
// Class: URP_UserOrg
//


Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_UserOrg' => 'Organizacje użytkowników',
	'Class:URP_UserOrg+' => 'Dozwolone organizacje',
	'Class:URP_UserOrg/Attribute:userid' => 'Użytkownik',
	'Class:URP_UserOrg/Attribute:userid+' => 'konto użytkownika',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login użytkownika',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organizacja',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Dozwolona organizacja',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organizacja',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Dozwolona organizacja',
	'Class:URP_UserOrg/Attribute:reason' => 'Powód',
	'Class:URP_UserOrg/Attribute:reason+' => 'wyjaśnij, dlaczego ta osoba może zobaczyć dane należące do tej organizacji',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'projekcje profili',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Wymiar',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'wymiar aplikacji',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Wymiar',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'wymiar aplikacji',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Profil użytkowania',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Nazwa profilu',
	'Class:URP_ProfileProjection/Attribute:value' => 'Wyrażenie wartości',
	'Class:URP_ProfileProjection/Attribute:value+' => 'Wyrażenie OQL (używając $user) | stała |  | + kod atrybutu',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Atrybut',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Kod atrybutu docelowego (opcjonalnie)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'projekcje klas',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Wymiar',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'wymiar aplikacji',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Wymiar',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'wymiar aplikacji',
	'Class:URP_ClassProjection/Attribute:class' => 'Klasa',
	'Class:URP_ClassProjection/Attribute:class+' => 'Klasa docelowa',
	'Class:URP_ClassProjection/Attribute:value' => 'Wyrażenie wartości',
	'Class:URP_ClassProjection/Attribute:value+' => 'Wyrażenie OQL (używając $this) | stała |  | + kod atrybutu',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Atrybut',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Kod atrybutu docelowego (opcjonalnie)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'uprawnienia do klas',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'profil użytkownika',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'profil użytkownika',
	'Class:URP_ActionGrant/Attribute:class' => 'Klasa',
	'Class:URP_ActionGrant/Attribute:class+' => 'Klasa docelowa',
	'Class:URP_ActionGrant/Attribute:permission' => 'Uprawnienie',
	'Class:URP_ActionGrant/Attribute:permission+' => 'dozwolone lub niedozwolone?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'tak',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'tak',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'nie',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'nie',
	'Class:URP_ActionGrant/Attribute:action' => 'Działanie',
	'Class:URP_ActionGrant/Attribute:action+' => 'operacje do wykonania na danej klasie',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'uprawnienia do bodźca w cyklu życia obiektu',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'profil użytkownika',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'profil użytkownika',
	'Class:URP_StimulusGrant/Attribute:class' => 'Klasa',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Klasa docelowa',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Uprawnienie',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'dozwolone lub niedozwolone?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'tak',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'tak',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'nie',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'nie',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Bodziec',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'kod bodźca',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'uprawnienia na poziomie atrybutów',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Nadane działanie',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'nadane działanie',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Atrybut',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'kod atrybutu',
));

//
// Class: UserDashboard
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:UserDashboard' => 'Panel użytkownika',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'Użytkownik',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Kod menu',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Zawartość',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 't',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'r',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'BooleanLabel:yes' => 'tak',
	'BooleanLabel:no' => 'nie',
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login',
	'Menu:WelcomeMenu' => 'Witaj', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Witaj w '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Witaj', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Witaj w '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Witaj w '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' to kompletny portal operacyjny OpenSource IT.</p>
<ul>Obejmuje:
<li>Kompletna baza danych CMDB (baza danych zarządzania konfiguracją) do dokumentowania inwentaryzacji IT i zarządzania nią.</li>
<li>Moduł zarządzania incydentami do śledzenia i komunikowania się o wszystkich problemach występujących w IT.</li>
<li>Moduł zarządzania zmianami do planowania i śledzenia zmian w środowisku IT.</li>
<li>Baza danych znanych błędów przyspieszająca rozwiązywanie incydentów.</li>
<li>Moduł przestojów do dokumentowania wszystkich planowanych przestojów i powiadamiania odpowiednich kontaktów.</li>
<li>Pulpity nawigacyjne, aby szybko uzyskać przegląd swojego IT.</li>
</ul>
<p>Wszystkie moduły można ustawiać krok po kroku niezależnie od siebie.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' jest zorientowany na usługodawcę, umożliwia inżynierom IT łatwe zarządzanie wieloma klientami lub organizacjami.
<ul>'.ITOP_APPLICATION_SHORT.', dostarcza bogaty w funkcje zestaw procesów biznesowych:
<li>Zwiększa efektywność zarządzania IT</li> 
<li>Napędza wydajność operacji IT</li> 
<li>Zwiększa satysfakcję klientów i zapewnia kierownictwu wgląd w wyniki biznesowe.</li>
</ul>
</p>
<p>'.ITOP_APPLICATION_SHORT.' jest całkowicie otwarty na integrację z obecną infrastrukturą zarządzania IT.</p>
<p>
<ul>Pomoże Ci w tym przyjęcie nowej generacji portalu operacyjnego IT:
<li>Lepiej zarządzaj coraz bardziej złożonym środowiskiem IT.</li>
<li>Wdrażaj procesy ITIL we własnym tempie.</li>
<li>Zarządzaj najważniejszym zasobem swojego IT: dokumentacją.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Otwarte zgłoszenia: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Moje zgłoszenia',
	'UI:WelcomeMenu:OpenIncidents' => 'Otwarte incydenty: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Elementy konfiguracji: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incydenty przydzielone mi',
	'UI:AllOrganizations' => ' Wszystkie organizacje ',
	'UI:YourSearch' => 'Twoje wyszukiwania',
	'UI:LoggedAsMessage' => 'zalogowany jako %1$s',
	'UI:LoggedAsMessage+Admin' => 'Zalogowany jako %1$s (Administrator)',
	'UI:Button:Logoff' => 'Wyloguj',
	'UI:Button:GlobalSearch' => 'Szukaj',
	'UI:Button:Search' => ' Szukaj ',
	'UI:Button:Clear' => ' Wyczyść ',
	'UI:Button:SearchInHIerarchy' => ' Szukaj w hierarchii ',
	'UI:Button:Query' => ' Zapytanie ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Zapisz',
	'UI:Button:Cancel' => 'Anuluj',
	'UI:Button:Close' => 'Zamknij',
	'UI:Button:Apply' => 'Zastosuj',
	'UI:Button:Send' => 'Wyślij',
	'UI:Button:Back' => ' << Wstecz ',
	'UI:Button:Restart' => ' |<< Restart ',
	'UI:Button:Next' => ' Następny >> ',
	'UI:Button:Finish' => ' Koniec ',
	'UI:Button:DoImport' => ' Uruchom Import ! ',
	'UI:Button:Done' => ' Gotowe ',
	'UI:Button:SimulateImport' => ' Symuluj import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Wykonaj ',
	'UI:Button:Evaluate:Title' => ' Wykonaj (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Dodaj... ',
	'UI:Button:BrowseObjects' => ' Przeglądaj... ',
	'UI:Button:Add' => ' Dodaj ',
	'UI:Button:AddToList' => ' << Dodaj ',
	'UI:Button:RemoveFromList' => ' Usuń >> ',
	'UI:Button:FilterList' => ' Filtruj... ',
	'UI:Button:Create' => ' Utwórz ',
	'UI:Button:Delete' => ' Usuń ! ',
	'UI:Button:Rename' => ' Zmień nazwę... ',
	'UI:Button:ChangePassword' => ' Zmień hasło ',
	'UI:Button:ResetPassword' => ' Reset hasła ',
	'UI:Button:Insert' => 'Wstaw',
	'UI:Button:More' => 'Więcej',
	'UI:Button:Less' => 'Mniej',
	'UI:Button:Wait' => 'Proszę czekać, trwa aktualizowanie pól',
	'UI:Treeview:CollapseAll' => 'Zwiń wszystkie',
	'UI:Treeview:ExpandAll' => 'Rozwiń wszystkie',
	'UI:UserPref:DoNotShowAgain' => 'Nie pokazuj ponownie',
	'UI:InputFile:NoFileSelected' => 'Nie wybrano pliku',
	'UI:InputFile:SelectFile' => 'Wybierz plik',

	'UI:SearchToggle' => 'Szukaj',
	'UI:ClickToCreateNew' => 'Utwórz %1$s',
	'UI:SearchFor_Class' => 'Szukaj obiektów %1$s',
	'UI:NoObjectToDisplay' => 'Brak obiektów do wyświetlenia.',
	'UI:Error:SaveFailed' => 'Nie można zapisać obiektu :',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parametr object_id jest obowiązkowy, gdy określono link_attr. Sprawdź definicję szablonu wyświetlania.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parametr target_attr jest obowiązkowy, gdy określono link_attr. Sprawdź definicję szablonu wyświetlania.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parametr group_by jest obowiązkowy. Sprawdź definicję szablonu wyświetlania.',
	'UI:Error:InvalidGroupByFields' => 'Nieprawidłowa lista pól do grupowania: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Błąd: nieobsługiwany styl bloku: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Nieprawidłowa definicja łącza: klasa obiektów do zarządzania: %1$s nie został znaleziony jako klucz zewnętrzny w klasie %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Obiekt: %1$s:%2$d nie znaleziony.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Błąd: odwołanie cykliczne w zależnościach między polami, sprawdź model danych.',
	'UI:Error:UploadedFileTooBig' => 'Przesłany plik jest za duży. (Dopuszczalny rozmiar %1$s). Aby zmienić ten limit, skontaktuj się z administratorem '.ITOP_APPLICATION_SHORT.'. (Sprawdź konfigurację PHP pod kątem upload_max_filesize i post_max_size na serwerze).',
	'UI:Error:UploadedFileTruncated.' => 'Przesłany plik został obcięty !',
	'UI:Error:NoTmpDir' => 'Katalog tymczasowy nie jest zdefiniowany.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Nie można zapisać pliku tymczasowego na dysku. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Przesyłanie zatrzymane przez rozszerzenie. (Oryginalna nazwa pliku = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Przesyłanie pliku nie powiodło się, nieznana przyczyna. (Kod błędu = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Błąd: dla tej operacji należy określić następujący parametr: %1$s.',
	'UI:Error:2ParametersMissing' => 'Błąd: dla tej operacji należy określić następujące parametry: %1$s i %2$s.',
	'UI:Error:3ParametersMissing' => 'Błąd: dla tej operacji należy określić następujące parametry: %1$s, %2$s i %3$s.',
	'UI:Error:4ParametersMissing' => 'Błąd: dla tej operacji należy określić następujące parametry: %1$s, %2$s, %3$s i %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Błąd: nieprawidłowe zapytanie OQL: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Wystąpił błąd podczas wykonywania zapytania: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Błąd: obiekt został już zaktualizowany.',
	'UI:Error:ObjectCannotBeUpdated' => 'Błąd: nie można zaktualizować obiektu.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Błąd: obiekty zostały już usunięte!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Nie możesz zbiorczo usuwać obiektów klasy %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Nie możesz usuwać obiektów klasy %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'Nie możesz przeglądać obiektów klasy %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Nie możesz przeprowadzić zbiorczej aktualizacji obiektów klasy %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Błąd: obiekt został już sklonowany!',
	'UI:Error:ObjectAlreadyCreated' => 'Błąd: obiekt został już utworzony!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => '%2$s jest obecnie w stanie "%3$s", żądanej operacji "%1$s" nie można zastosować.',
	'UI:Error:InvalidDashboardFile' => 'Błąd: nieprawidłowy plik pulpitu nawigacyjnego',
	'UI:Error:InvalidDashboard' => 'Błąd: nieprawidłowy pulpit nawigacyjny',
	'UI:Error:MaintenanceMode' => 'Aplikacja jest obecnie w trakcie konserwacji',
	'UI:Error:MaintenanceTitle' => 'Konserwacja',
	'UI:Error:InvalidToken' => 'Błąd: żądana operacja została już wykonana (nie znaleziono tokena CSRF)',
	'UI:Error:TemplateRendering' => 'Błąd renderowania szablonu',

	'UI:GroupBy:Count' => 'Licznik',
	'UI:GroupBy:Count+' => 'Liczba elementów',
	'UI:CountOfObjects' => '%1$d obiektów spełniających kryteria.',
	'UI_CountOfObjectsShort' => '%1$d obiektów.',
	'UI:NoObject_Class_ToDisplay' => 'Brak %1$s do wyświetlenia',
	'UI:History:LastModified_On_By' => 'Ostatnia modyfikacja dnia %1$s przez %2$s.',
	'UI:HistoryTab' => 'Historia',
	'UI:NotificationsTab' => 'Powiadomienia',
	'UI:History:BulkImports' => 'Historia',
	'UI:History:BulkImports+' => 'Lista importowanych plików CSV (najpierw ostatni import)',
	'UI:History:BulkImportDetails' => 'Zmiany wynikające z importu CSV wykonanego w dniu %1$s (przez %2$s)',
	'UI:History:Date' => 'Data',
	'UI:History:Date+' => 'Data zmiany',
	'UI:History:User' => 'Użytkownik',
	'UI:History:User+' => 'Użytkownik wprowadzający zmianę',
	'UI:History:Changes' => 'Zmiany',
	'UI:History:Changes+' => 'Zmiany wprowadzone w obiekcie',
	'UI:History:StatsCreations' => 'Utworzono',
	'UI:History:StatsCreations+' => 'Utworzono obiektów',
	'UI:History:StatsModifs' => 'Zmodyfikowano',
	'UI:History:StatsModifs+' => 'Zmodyfikowano obiektów',
	'UI:History:StatsDeletes' => 'Usunięto',
	'UI:History:StatsDeletes+' => 'Usunięto obiektów',
	'UI:Loading' => 'Ładowanie...',
	'UI:Menu:Actions' => 'Działania',
	'UI:Menu:OtherActions' => 'Inne działania',
	'UI:Menu:New' => 'Nowy...',
	'UI:Menu:Add' => 'Dodaj...',
	'UI:Menu:Manage' => 'Zarządzaj...',
	'UI:Menu:EMail' => 'e-mail',
	'UI:Menu:CSVExport' => 'Eksport CSV...',
	'UI:Menu:Modify' => 'Zmień...',
	'UI:Menu:Delete' => 'Usuń...',
	'UI:Menu:BulkDelete' => 'Usuń...',
	'UI:UndefinedObject' => 'nieokreślony',
	'UI:Document:OpenInNewWindow:Download' => 'Otwórz w nowym oknie: %1$s, Pobierz: %2$s',
	'UI:SplitDateTime-Date' => 'data',
	'UI:SplitDateTime-Time' => 'czas',
	'UI:TruncatedResults' => '%1$d obiekty wyświetlane z %2$d',
	'UI:DisplayAll' => 'Wyświetl wszystko',
	'UI:CollapseList' => 'Zwiń',
	'UI:CountOfResults' => '%1$d obiekt(y)',
	'UI:ChangesLogTitle' => 'Dziennik zmian (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Dziennik zmian jest pusty',
	'UI:SearchFor_Class_Objects' => 'Szukaj obiektów %1$s',
	'UI:OQLQueryBuilderTitle' => 'Budowa zapytań OQL',
	'UI:OQLQueryTab' => 'Zapytanie OQL',
	'UI:SimpleSearchTab' => 'Proste wyszukiwanie',
	'UI:Details+' => 'Szczegóły',
	'UI:SearchValue:Any' => '* Każdy *',
	'UI:SearchValue:Mixed' => '* mieszany *',
	'UI:SearchValue:NbSelected' => '# wybrany',
	'UI:SearchValue:CheckAll' => 'Zaznacz wszystko',
	'UI:SearchValue:UncheckAll' => 'Odznacz wszystko',
	'UI:SelectOne' => '-- wybierz --',
	'UI:Login:Welcome' => 'Witamy w '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nieprawidłowy login/hasło, spróbuj ponownie.',
	'UI:Login:IdentifyYourself' => 'Zidentyfikuj się przed wejściem',
	'UI:Login:UserNamePrompt' => 'Login',
	'UI:Login:PasswordPrompt' => 'Hasło',
	'UI:Login:ForgotPwd' => 'Zapomniałeś hasła?',
	'UI:Login:ForgotPwdForm' => 'Resetowanie hasła',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' może wysłać Ci wiadomość e-mail, w której znajdziesz instrukcje dotyczące resetowania hasła.',
	'UI:Login:ResetPassword' => 'Wyślij !',
	'UI:Login:ResetPwdFailed' => 'Nie udało się wysłać e-maila: %1$s',
	'UI:Login:SeparatorOr' => 'Lub',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\'nie jest prawidłowym loginem',
	'UI:ResetPwd-Error-NotPossible' => 'konta zewnętrzne nie pozwalają na resetowanie hasła.',
	'UI:ResetPwd-Error-FixedPwd' => 'konto nie pozwala na resetowanie hasła.',
	'UI:ResetPwd-Error-NoContact' => 'konto nie jest powiązane z osobą.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'konto nie jest powiązane z osobą mającą atrybut e-mail. Skontaktuj się z administratorem.',
	'UI:ResetPwd-Error-NoEmail' => 'brak adresu e-mail. Skontaktuj się z administratorem.',
	'UI:ResetPwd-Error-Send' => 'problem techniczny dotyczący transportu poczty elektronicznej. Skontaktuj się z administratorem.',
	'UI:ResetPwd-EmailSent' => 'Sprawdź swoją skrzynkę e-mail i postępuj zgodnie z instrukcjami. Jeśli nie otrzymasz wiadomości e-mail, sprawdź wpisany login.',
	'UI:ResetPwd-EmailSubject' => 'Reset hasła '.ITOP_APPLICATION_SHORT.'',
	'UI:ResetPwd-EmailBody' => '<body><p>Poprosiłeś o zresetowanie hasła '.ITOP_APPLICATION_SHORT.'.</p><p>Proszę skorzystać z tego linku (jednorazowe użycie), <a href="%1$s">wpisz nowe hasło</a></p>.',

	'UI:ResetPwd-Title' => 'Zresetuj hasło',
	'UI:ResetPwd-Error-InvalidToken' => 'Przepraszamy, albo hasło zostało już zresetowane, albo otrzymałeś kilka e-maili. Upewnij się, że używasz linku podanego w ostatniej otrzymanej wiadomości e-mail.',
	'UI:ResetPwd-Error-EnterPassword' => 'Wprowadź nowe hasło do konta \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Hasło zostało zmienione.',
	'UI:ResetPwd-Login' => 'Kliknij tutaj aby się zalogować...',

	'UI:Login:About' => ITOP_APPLICATION.' Obsługiwane przez Combodo',
	'UI:Login:ChangeYourPassword' => 'Zmień swoje hasło',
	'UI:Login:OldPasswordPrompt' => 'Stare hasło',
	'UI:Login:NewPasswordPrompt' => 'Nowe hasło',
	'UI:Login:RetypeNewPasswordPrompt' => 'Powtórz nowe hasło',
	'UI:Login:IncorrectOldPassword' => 'Błąd: stare hasło jest nieprawidłowe',
	'UI:LogOffMenu' => 'Wyloguj',
	'UI:LogOff:ThankYou' => 'Dziękujemy za użycie '.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain' => 'Kliknij tutaj, aby zalogować się ponownie...',
	'UI:ChangePwdMenu' => 'Zmień hasło...',
	'UI:Login:PasswordChanged' => 'Hasło ustawione pomyślnie!',
	'UI:AccessRO-All' => ITOP_APPLICATION.' jest tylko do odczytu',
	'UI:AccessRO-Users' => ITOP_APPLICATION.' jest tylko do odczytu dla użytkowników końcowych',
	'UI:ApplicationEnvironment' => 'Środowisko aplikacji: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nowe hasło i powtórzone nowe hasło nie pasują!',
	'UI:Button:Login' => 'Wejdź do '.ITOP_APPLICATION,
	'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' dostęp jest ograniczony. Prosimy o kontakt z administratorem '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin' => 'Dostęp ograniczony do osób z uprawnieniami administratora. Prosimy o kontakt z administratorem '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:WrongOrganizationName' => 'Nieznana organizacja',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Wiele kontaktów ma ten sam adres e-mail',
	'UI:Login:Error:NoValidProfiles' => 'Nie podano prawidłowego profilu',
	'UI:CSVImport:MappingSelectOne' => '-- wybierz jeden --',
	'UI:CSVImport:MappingNotApplicable' => '-- zignoruj to pole --',
	'UI:CSVImport:NoData' => 'Pusty zestaw danych ... proszę podać dane!',
	'UI:Title:DataPreview' => 'Podgląd danych',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Błąd: dane zawierają tylko jedną kolumnę. Czy wybrałeś odpowiedni znak separatora?',
	'UI:CSVImport:FieldName' => 'Pole %1$d',
	'UI:CSVImport:DataLine1' => 'Linia danych 1',
	'UI:CSVImport:DataLine2' => 'Linia danych 2',
	'UI:CSVImport:idField' => 'id (Klucz podstawowy)',
	'UI:Title:BulkImport' => ITOP_APPLICATION_SHORT.' - Import zbiorczy',
	'UI:Title:BulkImport+' => 'Kreator importu CSV',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronizacja %1$d pbiektów klasy %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- wybierz jeden --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Błąd wewnętrzny: "%1$s" to nieprawidłowy kod, ponieważ "%2$s" NIE jest zewnętrznym kluczem klasy "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d obiekt(y) pozostaną niezmienione.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d obiekt(y) zostaną zmodyfikowane.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d obiekt(y) zostaną dodane.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d obiekt(y) będą miały błędy.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d obiekt(y) pozostały niezmienione.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d obiekt(y) zostały zmodyfikowane.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d obiekt(y) zostały dodane.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d obiekt(y) miały błędy.',
	'UI:Title:CSVImportStep2' => 'Krok 2 z 5: Opcje danych CSV',
	'UI:Title:CSVImportStep3' => 'Krok 3 z 5: Mapowanie danych',
	'UI:Title:CSVImportStep4' => 'Krok 4 z 5: Symulacja importu',
	'UI:Title:CSVImportStep5' => 'Krok 5 z 5: Import zakończony',
	'UI:CSVImport:LinesNotImported' => 'Linie, których nie można wczytać:',
	'UI:CSVImport:LinesNotImported+' => 'Następujące wiersze nie zostały zaimportowane, ponieważ zawierają błędy',
	'UI:CSVImport:SeparatorComma+' => ', (przecinek)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (średnik)',
	'UI:CSVImport:SeparatorTab+' => 'tabulator',
	'UI:CSVImport:SeparatorOther' => 'inny:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (cudzysłów)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (pojedynczy cudzysłów)',
	'UI:CSVImport:QualifierOther' => 'inny:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Traktuj pierwszą linię jako nagłówek (nazwy kolumn)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Ppomiń %1$s linię(e) na początku pliku',
	'UI:CSVImport:CSVDataPreview' => 'Podgląd danych CSV',
	'UI:CSVImport:SelectFile' => 'Wybierz plik do zaimportowania:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Załaduj z pliku',
	'UI:CSVImport:Tab:CopyPaste' => 'Skopiuj i wklej dane',
	'UI:CSVImport:Tab:Templates' => 'Szablony',
	'UI:CSVImport:PasteData' => 'Wklej dane do zaimportowania:',
	'UI:CSVImport:PickClassForTemplate' => 'Wybierz szablon do pobrania: ',
	'UI:CSVImport:SeparatorCharacter' => 'Znak separatora:',
	'UI:CSVImport:TextQualifierCharacter' => 'Znak kwalifikatora tekstu',
	'UI:CSVImport:CommentsAndHeader' => 'Komentarze i nagłówek',
	'UI:CSVImport:SelectClass' => 'Wybierz klasę do zaimportowania:',
	'UI:CSVImport:AdvancedMode' => 'Tryb zaawansowany',
	'UI:CSVImport:AdvancedMode+' => 'W trybie zaawansowanym "id" (klucz podstawowy) obiektów może być używany do aktualizacji i zmiany nazw obiektów.'.
		'Jednak kolumna "id" (jeśli występuje) może służyć tylko jako kryterium wyszukiwania i nie może być łączona z żadnymi innymi kryteriami wyszukiwania.',
	'UI:CSVImport:SelectAClassFirst' => 'Aby skonfigurować mapowanie, wybierz najpierw klasę.',
	'UI:CSVImport:HeaderFields' => 'Pola',
	'UI:CSVImport:HeaderMappings' => 'Mapowania',
	'UI:CSVImport:HeaderSearch' => 'Szukaj?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Wybierz mapowanie dla każdego pola.',
	'UI:CSVImport:AlertMultipleMapping' => 'Upewnij się, że pole docelowe jest mapowane tylko raz.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Wybierz co najmniej jedno kryterium wyszukiwania',
	'UI:CSVImport:Encoding' => 'Kodowanie znaków',
	'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Wyszukiwanie uniwersalne',
	'UI:UniversalSearch:Error' => 'Błąd: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Wybierz klasę do przeszukania: ',

	'UI:CSVReport-Value-Modified' => 'Zmodyfikowano',
	'UI:CSVReport-Value-SetIssue' => 'Nie można było zmienić - powód: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Nie można zmienić na %1$s - powód: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Nie pasuje',
	'UI:CSVReport-Value-Missing' => 'Brak wymaganej wartości',
	'UI:CSVReport-Value-Ambiguous' => 'Uwaga: znaleziono %1$s obiektów',
	'UI:CSVReport-Row-Unchanged' => 'niezmieniony',
	'UI:CSVReport-Row-Created' => 'utworzony',
	'UI:CSVReport-Row-Updated' => 'zaktualizowano %1$d kolumn',
	'UI:CSVReport-Row-Disappeared' => 'zniknął, zmieniono %1$d kolumn',
	'UI:CSVReport-Row-Issue' => 'Problem: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Puste (null) niedozwolone',
	'UI:CSVReport-Value-Issue-NotFound' => 'Obiekt nie znaleziony',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Znaleziono %1$d dopasowań',
	'UI:CSVReport-Value-Issue-Readonly' => 'Atrybut \'%1$s\' jest tylko do odczytu i nie można go modyfikować (bieżąca wartość: %2$s, proponowana wartość: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Nie udało się przetworzyć danych wejściowych: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Nieoczekiwana wartość atrybutu \'%1$s\': nie znaleziono dopasowania, sprawdź pisownię',
	'UI:CSVReport-Value-Issue-Unknown' => 'Nieoczekiwana wartość atrybutu \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Atrybuty nie są ze sobą spójne: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Nieoczekiwane wartość(ci) atrybutu',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Nie można utworzyć z powodu braku kluczy zewnętrznych: %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'zły format daty',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'nie udało się uzgodnić',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'niejednoznaczne uzgodnienie',
	'UI:CSVReport-Row-Issue-Internal' => 'Błąd wewnętrzny: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Niezmieniony',
	'UI:CSVReport-Icon-Modified' => 'Zmodyfikowano',
	'UI:CSVReport-Icon-Missing' => 'Brakujący',
	'UI:CSVReport-Object-MissingToUpdate' => 'Brakujący obiekt: zostanie zaktualizowany',
	'UI:CSVReport-Object-MissingUpdated' => 'Brakujący obiekt: zaktualizowany',
	'UI:CSVReport-Icon-Created' => 'Utworzony',
	'UI:CSVReport-Object-ToCreate' => 'Obiekt zostanie utworzony',
	'UI:CSVReport-Object-Created' => 'Obiekt utworzony',
	'UI:CSVReport-Icon-Error' => 'Błąd',
	'UI:CSVReport-Object-Error' => 'BŁĄD: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'UWAGA: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% załadowanych obiektów zawiera błędy i zostanie zignorowanych.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% załadowanych obiektów zostanie utworzonych.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% załadowanych obiektów zostanie zmodyfikowanych.',

	'UI:CSVExport:AdvancedMode' => 'Tryb zaawansowany',
	'UI:CSVExport:AdvancedMode+' => 'W trybie zaawansowanym do eksportu dodawanych jest kilka kolumn: id obiektu, id kluczy zewnętrznych i ich atrybuty uzgadniania.',
	'UI:CSVExport:LostChars' => 'Problem z kodowaniem',
	'UI:CSVExport:LostChars+' => 'Pobrany plik zostanie zakodowany w formacie %1$s. '.ITOP_APPLICATION_SHORT.' wykrył znaki, które nie są zgodne z tym formatem. Znaki te zostaną albo zastąpione substytutem (np. zaakcentowane znaki tracące akcent), albo zostaną odrzucone. Możesz skopiować / wkleić dane z przeglądarki internetowej. Alternatywnie możesz skontaktować się z administratorem w celu zmiany kodowania (patrz parametr \'csv_file_default_charset\').',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - Audyt CMDB',
	'UI:Audit:InteractiveAudit' => 'Audyt interaktywny',
	'UI:Audit:HeaderAuditRule' => 'Reguła audytu',
	'UI:Audit:HeaderNbObjects' => '# Obiekty',
	'UI:Audit:HeaderNbErrors' => '# Błędy',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:OqlError' => 'Błąd OQL',
	'UI:Audit:Error:ValueNA' => 'n/d',
	'UI:Audit:ErrorIn_Rule' => 'Błąd w regule',
	'UI:Audit:ErrorIn_Rule_Reason' => 'Błąd OQL w regule %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category' => 'Błąd w kategorii',
	'UI:Audit:ErrorIn_Category_Reason' => 'Błąd OQL w kategorii %1$s: %2$s.',
	'UI:Audit:AuditErrors' => 'Błędy audytu',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Obiekty poddane audytowi',
	'UI:Audit:Dashboard:ObjectsInError' => 'Obiekty z błędami',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Obiekty sprawdzone',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s błędów z %2$s - %3$s%%',


	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - Wykonywanie zapytań OQL',
	'UI:RunQuery:QueryExamples' => 'Przykłady zapytań',
	'UI:RunQuery:QueryResults' => 'Query Results~~',
	'UI:RunQuery:HeaderPurpose' => 'Cel, powód',
	'UI:RunQuery:HeaderPurpose+' => 'Wyjaśnienie dotyczące zapytania',
	'UI:RunQuery:HeaderOQLExpression' => 'Wyrażenie OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'Zapytanie w składni OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Wyrażenie do wykonania: ',
	'UI:RunQuery:MoreInfo' => 'Więcej informacji o zapytaniu: ',
	'UI:RunQuery:DevelopedQuery' => 'Rozwinięte wyrażenie zapytania: ',
	'UI:RunQuery:SerializedFilter' => 'Filtr serializowany: ',
	'UI:RunQuery:DevelopedOQL' => 'Rozwinięte OQL',
	'UI:RunQuery:DevelopedOQLCount' => 'Rozwinięte OQL do przeliczenia',
	'UI:RunQuery:ResultSQLCount' => 'Wynikowy kod SQL do przeliczenia',
	'UI:RunQuery:ResultSQL' => 'Wynikowy SQL',
	'UI:RunQuery:Error' => 'Wystąpił błąd podczas wykonywania zapytania',
	'UI:Query:UrlForExcel' => 'Adres URL do użycia w kwerendach web MS-Excel',
	'UI:Query:UrlV1' => 'Lista pól pozostała nieokreślona. Strona <em>export-V2.php</em> nie może zostać wywołana bez tych informacji. Dlatego sugerowany poniżej adres URL wskazuje na starszą stronę: <em>export.php</em>. Ta starsza wersja eksportu ma następujące ograniczenie: lista eksportowanych pól może się różnić w zależności od formatu wyjściowego i modelu danych '.ITOP_APPLICATION_SHORT.'. <br/> Jeśli chcesz zagwarantować, że lista eksportowanych kolumn pozostanie stabilna w dłuższej perspektywie, musisz określić wartość dla atrybutu "Pola" i użyć strony <em>export-V2.php</em >.',
	'UI:Schema:Title' => ITOP_APPLICATION_SHORT.' schemat obiektów',
	'UI:Schema:CategoryMenuItem' => 'Kategoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relacje',
	'UI:Schema:AbstractClass' => 'Klasa abstrakcyjna: nie można utworzyć instancji obiektu z tej klasy.',
	'UI:Schema:NonAbstractClass' => 'Klasa nie abstrakcyjna: można tworzyć instancje obiektów z tej klasy.',
	'UI:Schema:ClassHierarchyTitle' => 'Hierarchia klas',
	'UI:Schema:AllClasses' => 'Wszystkie klasy',
	'UI:Schema:ExternalKey_To' => 'Klucz zewnętrzny do %1$s',
	'UI:Schema:Columns_Description' => 'Kolumny: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Domyślna: "%1$s"',
	'UI:Schema:NullAllowed' => 'Puste (Null) dozwolone',
	'UI:Schema:NullNotAllowed' => 'Puste (Null) NIE dozwolone',
	'UI:Schema:Attributes' => 'Atrybuty',
	'UI:Schema:AttributeCode' => 'Kod atrybutu',
	'UI:Schema:AttributeCode+' => 'Kod wewnętrzny atrybutu',
	'UI:Schema:Label' => 'Etykieta',
	'UI:Schema:Label+' => 'Etykieta atrybutu',
	'UI:Schema:Type' => 'Typ',

	'UI:Schema:Type+' => 'Typ danych atrybutu',
	'UI:Schema:Origin' => 'Pochodzenie',
	'UI:Schema:Origin+' => 'Klasa bazowa, w której zdefiniowano ten atrybut',
	'UI:Schema:Description' => 'Opis',
	'UI:Schema:Description+' => 'Opis atrybutu',
	'UI:Schema:AllowedValues' => 'Dozwolone wartości',
	'UI:Schema:AllowedValues+' => 'Ograniczenia dotyczące możliwych wartości tego atrybutu',
	'UI:Schema:MoreInfo' => 'Więcej informacji',
	'UI:Schema:MoreInfo+' => 'Więcej informacji o polu zdefiniowanym w bazie danych',
	'UI:Schema:SearchCriteria' => 'Kryteria wyszukiwania',
	'UI:Schema:FilterCode' => 'Kod filtra',
	'UI:Schema:FilterCode+' => 'Kod tego kryterium wyszukiwania',
	'UI:Schema:FilterDescription' => 'Opis',
	'UI:Schema:FilterDescription+' => 'Opis tych kryteriów wyszukiwania',
	'UI:Schema:AvailOperators' => 'Dostępne operatory',
	'UI:Schema:AvailOperators+' => 'Możliwe operatory dla tych kryteriów wyszukiwania',
	'UI:Schema:ChildClasses' => 'Klasy podrzędne',
	'UI:Schema:ReferencingClasses' => 'Klasy referencyjne',
	'UI:Schema:RelatedClasses' => 'Klasy powiązane',
	'UI:Schema:LifeCycle' => 'Koło życia',
	'UI:Schema:Triggers' => 'Wyzwalacze',
	'UI:Schema:Relation_Code_Description' => 'Relacja <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'W dół: %1$s',
	'UI:Schema:RelationUp_Description' => 'W górę: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propaguje %2$d poziomów, zapytanie: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: nie propaguje (%2$d poziomów), zapytanie: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s odwołuje się klasa %2$s przez pole %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s jest połączony z %2$s przez %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Klasy wskazują na %1$s (1:n linków):',
	'UI:Schema:Links:n-n' => 'KLasy połączone z %1$s (n:n linków):',
	'UI:Schema:Links:All' => 'Wykres wszystkich powiązanych klas',
	'UI:Schema:NoLifeCyle' => 'Nie ma zdefiniowanego cyklu życia dla tej klasy.',
	'UI:Schema:LifeCycleTransitions' => 'Stany i przejścia',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opcje atrybutów',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Ukryty',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Tylko do odczytu',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obowiązkowy',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Musi się zmienić',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Użytkownik zostanie poproszony o zmianę wartości',
	'UI:Schema:LifeCycleEmptyList' => 'pusta lista',
	'UI:Schema:ClassFilter' => 'Klasa:~~',
	'UI:Schema:DisplayLabel' => 'Pokaż:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Etykieta i kod~~',
	'UI:Schema:DisplaySelector/Label' => 'Etykieta~~',
	'UI:Schema:DisplaySelector/Code' => 'Kod~~',
	'UI:Schema:Attribute/Filter' => 'Filtr~~',
	'UI:Schema:DefaultNullValue' => 'Domyślnie pusty (null) : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => 'Wpisz pierwsze 3 znaki...',
	'UI:Edit:SearchQuery' => 'Wybierz wstępnie zdefiniowane zapytanie',
	'UI:Edit:TestQuery' => 'Zapytanie testowe',
	'UI:Combo:SelectValue' => '--- wybierz wartość ---',
	'UI:Label:SelectedObjects' => 'Wybrane obiekty: ',
	'UI:Label:AvailableObjects' => 'Dostępne obiekty: ',
	'UI:Link_Class_Attributes' => '%1$s atrybuty',
	'UI:SelectAllToggle+' => 'Zaznacz / odznacz wszystko',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Dodaj obiekty %1$s powiązane z %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Dodaj obiekty %1$s do połączenia z %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Zarządzaj obiektami %1$s powiązanymi z %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Dodaj obiekty %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Usuń wybrane obiekty',
	'UI:Message:EmptyList:UseAdd' => 'Lista jest pusta, użyj przycisku "Dodaj...", aby dodać elementy.',
	'UI:Message:EmptyList:UseSearchForm' => 'Użyj powyższego formularza wyszukiwania, aby wyszukać obiekty do dodania.',
	'UI:Wizard:FinalStepTitle' => 'Ostatni krok: potwierdzenie',
	'UI:Title:DeletionOf_Object' => 'Usunięcie %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Zbiorcze usuwanie obiektów %1$d klasy %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Nie możesz usunąć tego obiektu',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Nie możesz aktualizować pól: %1$s',
	'UI:Error:ActionNotAllowed' => 'Nie możesz wykonać tej czynności',
	'UI:Error:NotEnoughRightsToDelete' => 'Nie można usunąć tego obiektu, ponieważ bieżący użytkownik nie ma wystarczających uprawnień',
	'UI:Error:CannotDeleteBecause' => 'Nie można usunąć tego obiektu, ponieważ: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Tego obiektu nie można usunąć, ponieważ wcześniej trzeba było wykonać pewne operacje ręczne',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Tego obiektu nie można usunąć, ponieważ wcześniej trzeba było wykonać pewne operacje ręczne',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s w imieniu %2$s',
	'UI:Delete:Deleted' => 'usunięto',
	'UI:Delete:AutomaticallyDeleted' => 'usunięto automatycznie',
	'UI:Delete:AutomaticResetOf_Fields' => 'automatyczne resetowanie pól: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Czyszczenie wszystkich odniesień do %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Czyszczenie wszystkich odniesień do obiektów %1$d klasy %2$s...',
	'UI:Delete:Done+' => 'Co było zrobione...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s usunięto.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Usunięcie %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Usunięcie obiektów %1$d klasy %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Nie można było usunąć: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Powinien zostać automatycznie usunięty, ale nie jest to wykonalne: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Należy usunąć ręcznie, ale nie jest to wykonalne: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Zostanie automatycznie usunięty',
	'UI:Delete:MustBeDeletedManually' => 'Należy usunąć ręcznie',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Powinien być aktualizowany automatycznie, ale: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'zostanie automatycznie zaktualizowany (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => 'Obiekty / łącza %1$d odnoszą się do %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => 'Obiekty / łącza %1$d odnoszą się do niektórych obiektów do usunięcia',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Aby zapewnić integralność bazy danych, należy dodatkowo wyeliminować wszelkie odniesienia',
	'UI:Delete:Consequence+' => 'Co zostanie zrobione',
	'UI:Delete:SorryDeletionNotAllowed' => 'Przepraszamy, nie możesz usunąć tego obiektu, zobacz szczegółowe wyjaśnienia powyżej',
	'UI:Delete:PleaseDoTheManualOperations' => 'Przed złożeniem wniosku o usunięcie tego obiektu wykonaj czynności ręczne wymienione powyżej',
	'UI:Delect:Confirm_Object' => 'Potwierdź, że chcesz usunąć %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Potwierdź, że chcesz usunąć następujące obiekty %1$d klasy %2$s.',
	'UI:WelcomeToITop' => 'Witaj w '.ITOP_APPLICATION,
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s szczegóły',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Błąd',
	'UI:ObjectDoesNotExist' => 'Przepraszamy, ten obiekt nie istnieje (lub nie masz uprawnień do jego przeglądania).',
	'UI:ObjectArchived' => 'Ten obiekt został zarchiwizowany. Włącz tryb archiwizacji lub skontaktuj się z administratorem.',
	'Tag:Archived' => 'Zarchiwizowano',
	'Tag:Archived+' => 'Dostęp można uzyskać tylko w trybie archiwum',
	'Tag:Obsolete' => 'Wycofane',
	'Tag:Obsolete+' => 'Wyłączone z analizy wpływu i wyników wyszukiwania',
	'Tag:Synchronized' => 'Zsynchronizowane',
	'ObjectRef:Archived' => 'Zarchiwizowano',
	'ObjectRef:Obsolete' => 'Wycofane',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Wyniki wyszukiwania',
	'UI:SearchResultsTitle' => 'Wyniki wyszukiwania',
	'UI:SearchResultsTitle+' => 'Wyniki wyszukiwania pełnotekstowego',
	'UI:Search:NoSearch' => 'Nie ma czego szukać',
	'UI:Search:NeedleTooShort' => 'Ciąg wyszukiwania "%1$s" jest za krótki. Wpisz przynajmniej %2$d znaków.',
	'UI:Search:Ongoing' => 'Wyszukiwanie "%1$s"',
	'UI:Search:Enlarge' => 'Poszerz poszukiwania',
	'UI:FullTextSearchTitle_Text' => 'Wyniki dla "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => 'znaleziono obiektów %1$d klasy %2$s.',
	'UI:Search:NoObjectFound' => 'Nie znaleziono obiektu.',
	'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s zmiana',
	'UI:ModificationTitle_Class_Object' => 'Zmiana %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - Klonuj %1$s - %2$s zmianę',
	'UI:CloneTitle_Class_Object' => 'Klonuje %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - Tworzenie %1$s ',
	'UI:CreationTitle_Class' => 'Tworzenie %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Wybierz typ %1$s do utworzenia:',
	'UI:Class_Object_NotUpdated' => 'Nie wykryto żadnej zmiany, %1$s (%2$s) <strong>NIE</strong> został zmieniony.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) zaktualizowany.',
	'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Usuń zbiorczo',
	'UI:BulkDeleteTitle' => 'Wybierz obiekty, które chcesz usunąć:',
	'UI:PageTitle:ObjectCreated' => ITOP_APPLICATION_SHORT.' Utworzono obiekt.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s utworzono.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Zastosowano %1$s na obiekcie: %2$s w stanie %3$s do stanu docelowego: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Nie można zapisać obiektu: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Błąd krytyczny',
	'UI:SystemIntrusion' => 'Brak dostępu. Zażądałeś operacji, która nie jest dla Ciebie dozwolona.',
	'UI:FatalErrorMessage' => 'Błąd krytyczny, '.ITOP_APPLICATION_SHORT.' nie może kontynuować.',
	'UI:Error_Details' => 'Błąd: %1$s.',

	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' zarządzanie użytkownikami - projekcje profili',
	'UI:UserManagement:Class' => 'Klasa',
	'UI:UserManagement:Class+' => 'Klasa obiektów',
	'UI:UserManagement:ProjectedObject' => 'Obiekt',
	'UI:UserManagement:ProjectedObject+' => 'Rzutowany obiekt',
	'UI:UserManagement:AnyObject' => '* dowolny *',
	'UI:UserManagement:User' => 'Użytkownik',
	'UI:UserManagement:User+' => 'Użytkownik zaangażowany w projekcję',
	'UI:UserManagement:Action:Read' => 'Czytanie',
	'UI:UserManagement:Action:Read+' => 'Odczytaj / wyświetl obiekty',
	'UI:UserManagement:Action:Modify' => 'Zmienianie',
	'UI:UserManagement:Action:Modify+' => 'Twórz i edytuj (modyfikuj) obiekty',
	'UI:UserManagement:Action:Delete' => 'Usuwanie',
	'UI:UserManagement:Action:Delete+' => 'Usuń obiekty',
	'UI:UserManagement:Action:BulkRead' => 'Odczyt zbiorczy (eksport)',
	'UI:UserManagement:Action:BulkRead+' => 'Wyświetlaj obiekty lub eksportuj masowo',
	'UI:UserManagement:Action:BulkModify' => 'Zbiorcza modyfikacja',
	'UI:UserManagement:Action:BulkModify+' => 'Masowe tworzenie / edycja (import CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Usuń zbiorczo',
	'UI:UserManagement:Action:BulkDelete+' => 'Masowe usuwanie obiektów',
	'UI:UserManagement:Action:Stimuli' => 'Impulsy',
	'UI:UserManagement:Action:Stimuli+' => 'Dozwolone (złożone) działania',
	'UI:UserManagement:Action' => 'Działanie',
	'UI:UserManagement:Action+' => 'Działanie wykonywana przez użytkownika',
	'UI:UserManagement:TitleActions' => 'Działania',
	'UI:UserManagement:Permission' => 'Uprawnienie',
	'UI:UserManagement:Permission+' => 'Uprawnienia użytkownika',
	'UI:UserManagement:Attributes' => 'Atrybuty',
	'UI:UserManagement:ActionAllowed:Yes' => 'tak',
	'UI:UserManagement:ActionAllowed:No' => 'nie',
	'UI:UserManagement:AdminProfile+' => 'Administratorzy mają pełny dostęp do odczytu / zapisu do wszystkich obiektów w bazie danych.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'Nie dotyczy',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Dla tej klasy nie zdefiniowano żadnego cyklu życia',
	'UI:UserManagement:GrantMatrix' => 'Matryca uprawnień',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Link między %1$s i %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Link między %1$s i %2$s',

	'Menu:AdminTools' => 'Administracja', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Narzędzia administracyjne', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Narzędzia dostępne tylko dla użytkowników posiadających profil administratora', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System',

	'UI:ChangeManagementMenu' => 'Zarządzanie zmianami',
	'UI:ChangeManagementMenu+' => 'Zarządzanie zmianami',
	'UI:ChangeManagementMenu:Title' => 'Przegląd zmian',
	'UI-ChangeManagementMenu-ChangesByType' => 'Zmiany według typu',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Zmiany według statusu',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Zmiany jeszcze nie przypisane',

	'UI:ConfigurationManagementMenu' => 'Zarządzanie konfiguracją',
	'UI:ConfigurationManagementMenu+' => 'Zarządzanie konfiguracją',
	'UI:ConfigurationManagementMenu:Title' => 'Przegląd infrastruktury',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Obiekty infrastruktury według typu',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Obiekty infrastruktury według statusu',

	'UI:ConfigMgmtMenuOverview:Title' => 'Pulpit zarządzania konfiguracją',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Elementy konfiguracji według statusu',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Elementy konfiguracji według typu',

	'UI:RequestMgmtMenuOverview:Title' => 'Pulpit zarządzania zgłoszeniami',
	'UI-RequestManagementOverview-RequestByService' => 'Zgłoszenia użytkowników według usług',
	'UI-RequestManagementOverview-RequestByPriority' => 'Zgłoszenia użytkowników według priorytetu',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Zgłoszenia użytkownika nie przypisane agentowi',

	'UI:IncidentMgmtMenuOverview:Title' => 'Pulpit zarządzania incydentami',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incydenty według usług',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incydenty według priorytetu',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incydenty nie przypisane agentowi',

	'UI:ChangeMgmtMenuOverview:Title' => 'Pulpit zarządzania zmianami',
	'UI-ChangeManagementOverview-ChangeByType' => 'Zmiany według typu',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Zmiany nie przypisane agentowi',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Przerwy spowodowane zmianami',

	'UI:ServiceMgmtMenuOverview:Title' => 'Pulpit zarządzania usługami',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Umowy z klientami do odnowienia za 30 dni',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Umowy z dostawcami do odnowienia za 30 dni',

	'UI:ContactsMenu' => 'Kontakty',
	'UI:ContactsMenu+' => 'Kontakty',
	'UI:ContactsMenu:Title' => 'Przegląd kontaktów',
	'UI-ContactsMenu-ContactsByLocation' => 'Kontakty według lokalizacji',
	'UI-ContactsMenu-ContactsByType' => 'Kontakty według typu',
	'UI-ContactsMenu-ContactsByStatus' => 'Kontakty według statusu',

	'Menu:CSVImportMenu' => 'Import CSV', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Zbiorcze tworzenie lub aktualizacja', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Model danych', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Przegląd modelu danych', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Eksport', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Eksport wyników dowolnego zapytania w formacie HTML, CSV lub XML', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Powiadomienia', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Konfiguracja powiadomień', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Konfiguracja Powiadomienia',
	'UI:NotificationsMenu:Help' => 'Pomoc',
	'UI:NotificationsMenu:HelpContent' => '<p>W '.ITOP_APPLICATION_SHORT.' powiadomienia są w pełni konfigurowalne. Opierają się na dwóch zestawach obiektów: <i> wyzwalaczach i działaniach </i>.</p>
<p><i><b>Wyzwalacze</b></i> określają, kiedy powiadomienie zostanie wykonane. W ramach programu istnieją różne wyzwalacze '.ITOP_APPLICATION_SHORT.', ale inne mogą zostać wprowadzone przez rozszerzenia:
<ol>
	<li>Niektóre wyzwalacze są wykonywane, gdy obiekt określonej klasy jest <b>utworzony</b>, <b>zaktualizowany</b> lub <b>usunięty</b>.</li>
	<li>Niektóre wyzwalacze są wykonywane, gdy obiekt danej klasy <b>wejście</b> lub <b>wyjście</b> ma określony </b>stan</b>.</li>
	<li>Niektóre wyzwalacze są wykonywane, gdy <b>próg termin podjęcia TTO lub termin rozwiązania TTR</b> został <b>osiągnięty</b>.</li>
</ol>
</p>
<p>
<i><b>Działania</b></i> definiuje działania, które mają zostać wykonane, gdy wyzwalacze zostaną wykonane. Na razie istnieje tylko jeden rodzaj działania polegający na wysłaniu wiadomości e-mail.
Takie działania definiują również szablon, który ma być używany do wysyłania wiadomości e-mail, a także inne parametry wiadomości, takie jak odbiorcy, ważność itp.
</p>
<p>Specjalna strona: <a href="../setup/email.test.php" target="_blank">email.test.php</a> jest dostępna do testowania i rozwiązywania problemów z konfiguracją poczty PHP.</p>
<p>Aby zostały wykonane, działania muszą być powiązane z wyzwalaczami.
W przypadku powiązania z wyzwalaczem, każde działanie otrzymuje numer "porządkowy", określający, w jakiej kolejności mają być wykonywane.</p>',
	'UI:NotificationsMenu:Triggers' => 'Wyzwalacze',
	'UI:NotificationsMenu:AvailableTriggers' => 'Dostępne wyzwalacze',
	'UI:NotificationsMenu:OnCreate' => 'Kiedy obiekt jest tworzony',
	'UI:NotificationsMenu:OnStateEnter' => 'Kiedy obiekt wejdzie w określony stan',
	'UI:NotificationsMenu:OnStateLeave' => 'Kiedy obiekt opuszcza dany stan',
	'UI:NotificationsMenu:Actions' => 'Działania',
	'UI:NotificationsMenu:AvailableActions' => 'Dostępne działania',

	'Menu:TagAdminMenu' => 'Konfiguracja tagów',
	'Menu:TagAdminMenu+' => 'Zarządzanie wartościami tagów',
	'UI:TagAdminMenu:Title' => 'Konfiguracja tagów',
	'UI:TagAdminMenu:NoTags' => 'Nie skonfigurowano pola tagu',
	'UI:TagSetFieldData:Error' => 'Błąd: %1$s',

	'Menu:AuditCategories' => 'Kategorie audytu', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Kategorie audytu', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Kategorie audytu', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Uruchom zapytania', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Uruchom dowolne zapytanie', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Słownik zapytań', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Słownik zapytań', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Administracja danymi', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Administracja danymi', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Wyszukiwanie uniwersalne', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Szukaj wszystkiego...', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Zarządzanie użytkownikami', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Zarządzanie użytkownikami', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profile', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profile', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profile',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Konta użytkowników',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Konta użytkowników',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Konta użytkowników',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => '%1$s wersja %2$s',
	'UI:iTopVersion:Long' => '%1$s wersja %2$s-%3$s zbudowana na %4$s',
	'UI:PropertiesTab' => 'Właściwości',

	'UI:OpenDocumentInNewWindow_' => 'Otwórz~~',
	'UI:DownloadDocument_' => 'Pobierz~~',
	'UI:Document:NoPreview' => 'Brak podglądu tego typu dokumentu',
	'UI:Download-CSV' => 'Pobierz %1$s',

	'UI:DeadlineMissedBy_duration' => 'Nieodebrane przez %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Pomoc',
	'UI:PasswordConfirm' => '(Potwierdenie)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Zanim dodasz więcej obiektów %1$s, zapisz ten obiekt.',
	'UI:DisplayThisMessageAtStartup' => 'Wyświetl tę wiadomość podczas uruchamiania',
	'UI:RelationshipGraph' => 'Widok graficzny',
	'UI:RelationshipList' => 'Lista',
	'UI:RelationGroups' => 'Grupy',
	'UI:OperationCancelled' => 'Operacja anulowana',
	'UI:ElementsDisplayed' => 'Filtrowanie',
	'UI:RelationGroupNumber_N' => 'Grupa #%1$d',
	'UI:Relation:ExportAsPDF' => 'Eksport jako PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Próg grupowania',
	'UI:Relation:AdditionalContextInfo' => 'Dodatkowe informacje kontekstowe',
	'UI:Relation:NoneSelected' => 'Żaden',
	'UI:Relation:Zoom' => 'Powiększenie',
	'UI:Relation:ExportAsAttachment' => 'Eksportuj jako załącznik...',
	'UI:Relation:DrillDown' => 'Szczegóły...',
	'UI:Relation:PDFExportOptions' => 'Opcje eksportu PDF',
	'UI:Relation:AttachmentExportOptions_Name' => 'Opcje załączania do %1$s',
	'UI:RelationOption:Untitled' => 'Bez tytułu',
	'UI:Relation:Key' => 'Klucz',
	'UI:Relation:Comments' => 'Komentarze',
	'UI:RelationOption:Title' => 'Tytuł',
	'UI:RelationOption:IncludeList' => 'Dołącz listę obiektów',
	'UI:RelationOption:Comments' => 'Komentarze',
	'UI:Button:Export' => 'Eksport',
	'UI:Relation:PDFExportPageFormat' => 'Format strony',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter',
	'UI:Relation:PDFExportPageOrientation' => 'Orientacja strony',
	'UI:PageOrientation_Portrait' => 'Portret',
	'UI:PageOrientation_Landscape' => 'Krajobraz',
	'UI:RelationTooltip:Redundancy' => 'Nadmierność',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# dotkniętych elementów: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Krytyczny próg: %1$d / %2$d',
	'Portal:Title' => ITOP_APPLICATION_SHORT.' portal użytkownika',
	'Portal:NoRequestMgmt' => 'Drogi %1$s, zostałeś przekierowany na tę stronę, ponieważ Twoje konto jest skonfigurowane z profilem \'Portal użytkownika\'. Niestety, '.ITOP_APPLICATION_SHORT.' nie został zainstalowany z funkcją \'Zarządzanie zgłoszeniami\'. Skontaktuj się z administratorem.',
	'Portal:Refresh' => 'Odśwież',
	'Portal:Back' => 'Wstecz',
	'Portal:WelcomeUserOrg' => 'Witaj %1$s, z %2$s',
	'Portal:TitleDetailsFor_Request' => 'Szczegóły zgłoszenia',
	'Portal:ShowOngoing' => 'Pokaż otwarte zgłoszenia',
	'Portal:ShowClosed' => 'Pokaż zamknięte zgłoszenia',
	'Portal:CreateNewRequest' => 'Utwórz nowe zgłoszenie',
	'Portal:CreateNewRequestItil' => 'Utwórz nowe zgłoszenie',
	'Portal:CreateNewIncidentItil' => 'Utwórz nowy raport incydentów',
	'Portal:ChangeMyPassword' => 'Zmień moje hasło',
	'Portal:Disconnect' => 'Rozłącz się',
	'Portal:OpenRequests' => 'Moje otwarte zgłoszenia',
	'Portal:ClosedRequests' => 'Moje zamknięte zgłoszenia',
	'Portal:ResolvedRequests' => 'Moje rozwiązane zgłoszenia',
	'Portal:SelectService' => 'Wybierz usługę z katalogu:',
	'Portal:PleaseSelectOneService' => 'Wybierz jedną usługę',
	'Portal:SelectSubcategoryFrom_Service' => 'Wybierz podkategorię usługi %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Wybierz jedną podkategorię',
	'Portal:DescriptionOfTheRequest' => 'Wpisz opis swojego zgłoszenia:',
	'Portal:TitleRequestDetailsFor_Request' => 'Szczegóły zgłoszenia %1$s:',
	'Portal:NoOpenRequest' => 'Brak zgłoszeń w tej kategorii',
	'Portal:NoClosedRequest' => 'Brak zgłoszeń w tej kategorii',
	'Portal:Button:ReopenTicket' => 'Otwórz ponownie zgłoszenie',
	'Portal:Button:CloseTicket' => 'Zamknij zgłoszenie',
	'Portal:Button:UpdateRequest' => 'Zaktualizuj zgłoszenie',
	'Portal:EnterYourCommentsOnTicket' => 'Wpisz swoje uwagi dotyczące rozwiązania tego zgłoszenia:',
	'Portal:ErrorNoContactForThisUser' => 'Błąd: bieżący użytkownik nie jest powiązany z kontaktem / osobą. Skontaktuj się z administratorem.',
	'Portal:Attachments' => 'Załączniki',
	'Portal:AddAttachment' => ' Dodaj załącznik ',
	'Portal:RemoveAttachment' => ' Usuń załącznik ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Załącznik #%1$d do %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Wybierz szablon dla %1$s',
	'Enum:Undefined' => 'Nieokreślony',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s d %2$s g %3$s min %4$s s',
	'UI:ModifyAllPageTitle' => 'Zmień wszystko',
	'UI:Modify_N_ObjectsOf_Class' => 'Zmiana obiektów %1$d klasy %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Zmiana obiektów %1$d klasy %2$s poza %3$d',
	'UI:Menu:ModifyAll' => 'Zmień...',
	'UI:Button:ModifyAll' => 'Zmień wszystko',
	'UI:Button:PreviewModifications' => 'Podgląd zmian >>',
	'UI:ModifiedObject' => 'Obiekt zmieniony',
	'UI:BulkModifyStatus' => 'Operacja',
	'UI:BulkModifyStatus+' => 'Status operacji',
	'UI:BulkModifyErrors' => 'Błędy (jeśli występują)',
	'UI:BulkModifyErrors+' => 'Błędy uniemożliwiające zmianę',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Błąd',
	'UI:BulkModifyStatusModified' => 'Zmieniono',
	'UI:BulkModifyStatusSkipped' => 'Pominięto',
	'UI:BulkModify_Count_DistinctValues' => '%1$d odrębne wartości:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d czas',
	'UI:BulkModify:N_MoreValues' => '%1$d więcej wartości...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Próba ustawienia pola tylko do odczytu: %1$s',
	'UI:FailedToApplyStimuli' => 'Działanie nie powiodło się.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Zmiana obiektów %2$d klasy %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Tutaj wpisz swój tekst...',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Wartość początkowa:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Pole %1$s nie jest zapisywalne, ponieważ jest kontrolowane przez synchronizację danych. Wartość nie została ustawiona.',
	'UI:ActionNotAllowed' => 'Nie możesz wykonać działania na tych obiektach.',
	'UI:BulkAction:NoObjectSelected' => 'Wybierz co najmniej jeden obiekt do wykonania tej operacji',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Pole %1$s nie jest zapisywalne, ponieważ jest kontrolowane przez synchronizację danych. Wartość pozostaje niezmieniona.',
	'UI:Pagination:HeaderSelection' => 'Łącznie: %1$s obiektów (%2$s obiektów wybranych).',
	'UI:Pagination:HeaderNoSelection' => 'Łącznie: %1$s obiektów.',
	'UI:Pagination:PageSize' => '%1$s obiektów na stronę',
	'UI:Pagination:PagesLabel' => 'Strony:',
	'UI:Pagination:All' => 'Wszystkie',
	'UI:HierarchyOf_Class' => 'Hierarchia %1$s',
	'UI:Preferences' => 'Preferencje...',
	'UI:ArchiveModeOn' => 'Aktywuj tryb archiwizacji',
	'UI:ArchiveModeOff' => 'Dezaktywuj tryb archiwizacji',
	'UI:ArchiveMode:Banner' => 'Tryb archiwizacji',
	'UI:ArchiveMode:Banner+' => 'Zarchiwizowane obiekty są widoczne i nie można ich modyfikować',
	'UI:FavoriteOrganizations' => 'Ulubione organizacje',
	'UI:FavoriteOrganizations+' => 'Sprawdź na liście poniżej organizacje, które chcesz zobaczyć w menu rozwijanym, aby uzyskać szybki dostęp. '.
		'Pamiętaj, że to nie jest ustawienie zabezpieczeń, obiekty z dowolnej organizacji są nadal widoczne i można uzyskać do nich dostęp, wybierając z listy rozwijanej opcję "Wszystkie organizacje".',
	'UI:FavoriteLanguage' => 'Język interfejsu użytkownika',
	'UI:Favorites:SelectYourLanguage' => 'Wybierz preferowany język',
	'UI:FavoriteOtherSettings' => 'Inne ustawienia',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Domyślna długość:  %1$s pozycji na stronę',
	'UI:Favorites:ShowObsoleteData' => 'Pokaż wycofane dane',
	'UI:Favorites:ShowObsoleteData+' => 'Pokaż wycofane dane w wynikach wyszukiwania i listach elementów do wybrania',
	'UI:NavigateAwayConfirmationMessage' => 'Wszelkie modyfikacje zostaną odrzucone.',
	'UI:CancelConfirmationMessage' => 'Utracisz wprowadzone zmiany. Kontynuować mimo to?',
	'UI:AutoApplyConfirmationMessage' => 'Niektóre zmiany nie zostały jeszcze zastosowane. Czy chcesz aby '.ITOP_APPLICATION_SHORT.' wziął je pod uwagę?',
	'UI:Create_Class_InState' => 'Utwórz %1$s w stanie: ',
	'UI:OrderByHint_Values' => 'Porządek sortowania: %1$s',
	'UI:Menu:AddToDashboard' => 'Dodaj do pulpitu...',
	'UI:Button:Refresh' => 'Odśwież',
	'UI:Button:GoPrint' => 'Drukuj...',
	'UI:ExplainPrintable' => 'Kliknij w ikonę %1$s, aby ukryć elementy na wydruku.<br/>Użyj funkcji "podgląd wydruku" swojej przeglądarki, aby wyświetlić podgląd przed drukowaniem. <br/> Uwaga: ten nagłówek i inne elementy sterujące dostrajaniem nie zostaną wydrukowane.',
	'UI:PrintResolution:FullSize' => 'Pełny rozmiar',
	'UI:PrintResolution:A4Portrait' => 'A4 portret',
	'UI:PrintResolution:A4Landscape' => 'A4 krajobraz',
	'UI:PrintResolution:LetterPortrait' => 'Letter portret',
	'UI:PrintResolution:LetterLandscape' => 'Letter krajobraz',
	'UI:Toggle:StandardDashboard' => 'Standard',
	'UI:Toggle:CustomDashboard' => 'Własny',

	'UI:ConfigureThisList' => 'Skonfiguruj listę...',
	'UI:ListConfigurationTitle' => 'Konfiguracja listy',
	'UI:ColumnsAndSortOrder' => 'Kolumny i porządek sortowania:',
	'UI:UseDefaultSettings' => 'Użyj ustawień domyślnych',
	'UI:UseSpecificSettings' => 'Użyj następujących ustawień:',
	'UI:Display_X_ItemsPerPage_prefix' => 'Pokaż',
	'UI:Display_X_ItemsPerPage_suffix' => 'pozycji na stronę',
	'UI:UseSavetheSettings' => 'Zapisz ustawienia',
	'UI:OnlyForThisList' => 'Tylko dla tej listy',
	'UI:ForAllLists' => 'Domyślnie dla wszystkich list',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Przyjazna nazwa)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Wyżej',
	'UI:Button:MoveDown' => 'Niżej',

	'UI:OQL:UnknownClassAndFix' => 'Nieznana klasa "%1$s". Możesz spróbować "%2$s" w zamian.',
	'UI:OQL:UnknownClassNoFix' => 'Nieznana klasa "%1$s"',

	'UI:Dashboard:EditCustom' => 'Edytuj własną wersję...',
	'UI:Dashboard:CreateCustom' => 'Utwórz wersję...',
	'UI:Dashboard:DeleteCustom' => 'Usuń własną wersję...',
	'UI:Dashboard:RevertConfirm' => 'Wszystkie zmiany wprowadzone w oryginalnej wersji zostaną utracone. Potwierdź, że chcesz to zrobić.',
	'UI:ExportDashBoard' => 'Eksportuj do pliku',
	'UI:ImportDashBoard' => 'Importuj z pliku...',
	'UI:ImportDashboardTitle' => 'Importuj z pliku',
	'UI:ImportDashboardText' => 'Wybierz plik pulpitu do zaimportowania:',


	'UI:DashletCreation:Title' => 'Utwórz nową wtyczkę',
	'UI:DashletCreation:Dashboard' => 'Pulpit',
	'UI:DashletCreation:DashletType' => 'Typ wtyczki',
	'UI:DashletCreation:EditNow' => 'Edytuj pulpit',

	'UI:DashboardEdit:Title' => 'Edytor pulpitu',
	'UI:DashboardEdit:DashboardTitle' => 'Tytuł',
	'UI:DashboardEdit:AutoReload' => 'Automatyczne odświeżanie',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatyczne odświeżanie (w sekundach)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Dopuszczalne minimum %1$d sekund',

	'UI:DashboardEdit:Layout' => 'Układ',
	'UI:DashboardEdit:Properties' => 'Właściwości pulpitu',
	'UI:DashboardEdit:Dashlets' => 'Dostępne wtyczki',
	'UI:DashboardEdit:DashletProperties' => 'Właściwości wtyczki',

	'UI:Form:Property' => 'Właściwość',
	'UI:Form:Value' => 'Wartość',

	'UI:DashletUnknown:Label' => 'Nieznana',
	'UI:DashletUnknown:Description' => 'Nieznana wtyczka (mogła zostać odinstalowana)',
	'UI:DashletUnknown:RenderText:View' => 'Nie można wyrenderować wtyczki.',
	'UI:DashletUnknown:RenderText:Edit' => 'Nie można wyrenderować wtyczki (klasa "%1$s"). Skontaktuj się z administratorem, jeśli jest nadal dostępny.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'Brak podglądu dla wtyczki (klasa "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Konfiguracja (pokazana jako nieprzetworzony XML)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Wtyczka Proxy',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'Brak podglądu wtyczki innej firmy (klasa "%1$s").',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Konfiguracja (pokazana jako nieprzetworzony XML)',

	'UI:DashletPlainText:Label' => 'Tekst',
	'UI:DashletPlainText:Description' => 'Zwykły tekst (bez formatowania)',
	'UI:DashletPlainText:Prop-Text' => 'Tekst',
	'UI:DashletPlainText:Prop-Text:Default' => 'Proszę tu wpisać tekst...',

	'UI:DashletObjectList:Label' => 'Lista obiektów',
	'UI:DashletObjectList:Description' => 'Wtyczka listy obiektów',
	'UI:DashletObjectList:Prop-Title' => 'Tytuł',
	'UI:DashletObjectList:Prop-Query' => 'Zapytanie',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Tytuł',
	'UI:DashletGroupBy:Prop-Query' => 'Zapytanie',
	'UI:DashletGroupBy:Prop-Style' => 'Styl',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Grupuj według...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Godzina %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Miesiąc %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Dzień tygodnia %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Dzieńmiesiąca %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (godzina)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (miesiąc)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (dzień tygodnia)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (dzień miesiąca)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Proszę wybrać pole, po którym będą grupowane obiekty',

	'UI:DashletGroupByPie:Label' => 'Wykres kołowy',
	'UI:DashletGroupByPie:Description' => 'Wykres kołowy',
	'UI:DashletGroupByBars:Label' => 'Wykres słupkowy',
	'UI:DashletGroupByBars:Description' => 'Wykres słupkowy',
	'UI:DashletGroupByTable:Label' => 'Grupuj według (tabela)',
	'UI:DashletGroupByTable:Description' => 'Lista (pogrupowana według pola)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Funkcja agregacji',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Atrybut funkcji',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Kierunek',
	'UI:DashletGroupBy:Prop-OrderField' => 'Sortuj po',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit',

	'UI:DashletGroupBy:Order:asc' => 'Rosnąco',
	'UI:DashletGroupBy:Order:desc' => 'Malejąco',

	'UI:GroupBy:count' => 'Liczba',
	'UI:GroupBy:count+' => 'Liczba elementów',
	'UI:GroupBy:sum' => 'Suma',
	'UI:GroupBy:sum+' => 'Suma %1$s',
	'UI:GroupBy:avg' => 'Średnia',
	'UI:GroupBy:avg+' => 'Średnia %1$s',
	'UI:GroupBy:min' => 'Minimum',
	'UI:GroupBy:min+' => 'Minimum %1$s',
	'UI:GroupBy:max' => 'Maksimum',
	'UI:GroupBy:max+' => 'Maksimum %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Nagłówek',
	'UI:DashletHeaderStatic:Description' => 'Wyświetla separator poziomy',
	'UI:DashletHeaderStatic:Prop-Title' => 'Tytuł',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kontakty',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Ikona',

	'UI:DashletHeaderDynamic:Label' => 'Nagłówek ze statystykami',
	'UI:DashletHeaderDynamic:Description' => 'Nagłówek ze statystykami (pogrupowane według ...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Tytuł',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kontakty',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Ikona',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Podtytuł',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kontakty',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Zapytanie',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Grupuj według',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Wartości',

	'UI:DashletBadge:Label' => 'Symbol',
	'UI:DashletBadge:Description' => 'Ikona obiektu z nowym / wyszukiwaniem',
	'UI:DashletBadge:Prop-Class' => 'Klasa',

	'DayOfWeek-Sunday' => 'Niedziela',
	'DayOfWeek-Monday' => 'Poniedziałek',
	'DayOfWeek-Tuesday' => 'Wtorek',
	'DayOfWeek-Wednesday' => 'Środa',
	'DayOfWeek-Thursday' => 'Czwartek',
	'DayOfWeek-Friday' => 'Piątek',
	'DayOfWeek-Saturday' => 'Sobota',
	'Month-01' => 'Styczeń',
	'Month-02' => 'Luty',
	'Month-03' => 'Marzec',
	'Month-04' => 'Kwiecień',
	'Month-05' => 'Maj',
	'Month-06' => 'Czerwiec',
	'Month-07' => 'Lipiec',
	'Month-08' => 'Sierpień',
	'Month-09' => 'Wrzesień',
	'Month-10' => 'Październik',
	'Month-11' => 'Listopad',
	'Month-12' => 'Grudzień',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Ni',
	'DayOfWeek-Monday-Min' => 'Po',
	'DayOfWeek-Tuesday-Min' => 'Wt',
	'DayOfWeek-Wednesday-Min' => 'Śr',
	'DayOfWeek-Thursday-Min' => 'Cz',
	'DayOfWeek-Friday-Min' => 'Pi',
	'DayOfWeek-Saturday-Min' => 'So',
	'Month-01-Short' => 'Sty',
	'Month-02-Short' => 'Lut',
	'Month-03-Short' => 'Mar',
	'Month-04-Short' => 'Kwi',
	'Month-05-Short' => 'Maj',
	'Month-06-Short' => 'Cze',
	'Month-07-Short' => 'Lip',
	'Month-08-Short' => 'Sie',
	'Month-09-Short' => 'Wrz',
	'Month-10-Short' => 'Paź',
	'Month-11-Short' => 'Lis',
	'Month-12-Short' => 'Gru',
	'Calendar-FirstDayOfWeek' => 1, // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Utwórz skrót...',
	'UI:ShortcutRenameDlg:Title' => 'Zmień nazwę skrótu',
	'UI:ShortcutListDlg:Title' => 'Utwórz skrót do listy',
	'UI:ShortcutDelete:Confirm' => 'Potwierdź, że chcesz usunąć skrót(y).',
	'Menu:MyShortcuts' => 'Moje skróty', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Skrót',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Nazwa',
	'Class:Shortcut/Attribute:name+' => 'Etykieta używana w menu i tytule strony',
	'Class:ShortcutOQL' => 'Skrót do wyników wyszukiwania',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Zapytanie',
	'Class:ShortcutOQL/Attribute:oql+' => 'Zapytanie OQL definiujące listę obiektów do wyszukania',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatyczne odświeżanie',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Wyłączone',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Własne',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatyczne odświeżanie (sekundy)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'Dopuszczalne minimum %1$d sekund',

	'UI:FillAllMandatoryFields' => 'Proszę wypełnić wszystkie wymagane pola.',
	'UI:ValueMustBeSet' => 'Podaj wartość',
	'UI:ValueMustBeChanged' => 'Zmień wartość',
	'UI:ValueInvalidFormat' => 'Niepoprawny format',

	'UI:CSVImportConfirmTitle' => 'Potwierdź operację',
	'UI:CSVImportConfirmMessage' => 'Czy na pewno chcesz to zrobić?',
	'UI:CSVImportError_items' => 'Błędy: %1$d',
	'UI:CSVImportCreated_items' => 'Utworzono: %1$d',
	'UI:CSVImportModified_items' => 'Zmieniono: %1$d',
	'UI:CSVImportUnchanged_items' => 'Bez zmian: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Format daty i czasu',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Domyślny format: %1$s (np. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Własny format: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Dostępne symbole:<table>
<tr><td>Y</td><td>rok (4 cyfry, np. 2016)</td></tr>
<tr><td>y</td><td>rok (2 cyfry, np. 16 dla 2016)</td></tr>
<tr><td>m</td><td>miesiąc (2 cyfry, np. 01..12)</td></tr>
<tr><td>n</td><td>miesiąc (1 lub 2 cyfry bez zera wiodącego, np. 1..12)</td></tr>
<tr><td>d</td><td>dzień (2 cyfry, np. 01..31)</td></tr>
<tr><td>j</td><td>dzień (1 lub 2 cyfry bez zera wiodącego, np. 1..31)</td></tr>
<tr><td>H</td><td>godzina (24 godziny, 2 cyfry, np. 00..23)</td></tr>
<tr><td>h</td><td>godzina (12 godzin, 2 cyfry, np. 01..12)</td></tr>
<tr><td>G</td><td>godzina (24-godzinna, 1 lub 2 cyfry bez zera wiodącego, np. 0..23)</td></tr>
<tr><td>g</td><td>godzina (12 godzin, 1 lub 2 cyfry bez zera wiodącego, np. 1..12)</td></tr>
<tr><td>a</td><td>godzina, am lub pm (małe litery)</td></tr>
<tr><td>A</td><td>godzina, AM lub PM (duże litery)</td></tr>
<tr><td>i</td><td>minuty (2 cyfry, np. 00..59)</td></tr>
<tr><td>s</td><td>sekundy (2 cyfry, np. 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => 'Usuń',
	'UI:AddAnExisting_Class' => 'Dodaj obiekty typu %1$s...',
	'UI:SelectionOf_Class' => 'Wybór obiektów typu %1$s',

	'UI:AboutBox' => 'O '.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'O '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Model danych',
	'UI:About:Support' => 'Informacje o pomocy technicznej',
	'UI:About:Licenses' => 'Licencje',
	'UI:About:InstallationOptions' => 'Opcje instalacji',
	'UI:About:ManualExtensionSource' => 'Rozbudowa',
	'UI:About:Extension_Version' => 'Wersja: %1$s',
	'UI:About:RemoteExtensionSource' => 'Dane',

	'UI:DisconnectedDlgMessage' => 'Jesteś rozłączony. Aby kontynuować korzystanie z aplikacji, musisz się zidentyfikować.',
	'UI:DisconnectedDlgTitle' => 'Uwaga!',
	'UI:LoginAgain' => 'Zaloguj się ponownie',
	'UI:StayOnThePage' => 'Zostań na tej stronie',

	'ExcelExporter:ExportMenu' => 'Eksport do Excela...',
	'ExcelExporter:ExportDialogTitle' => 'Eksport do Excela',
	'ExcelExporter:ExportButton' => 'Eksport',
	'ExcelExporter:DownloadButton' => 'Pobierz %1$s',
	'ExcelExporter:RetrievingData' => 'Pobieranie danych...',
	'ExcelExporter:BuildingExcelFile' => 'Tworzenie pliku Excel...',
	'ExcelExporter:Done' => 'Gotowe.',
	'ExcelExport:AutoDownload' => 'Rozpocznij pobieranie automatycznie, gdy eksport jest gotowy',
	'ExcelExport:PreparingExport' => 'Przygotowanie eksportu...',
	'ExcelExport:Statistics' => 'Statystyka',
	'portal:legacy_portal' => 'Portal użytkownika',
	'portal:backoffice' => ITOP_APPLICATION_SHORT.' Interfejs użytkownika biurowego',

	'UI:CurrentObjectIsLockedBy_User' => 'Obiekt jest zablokowany, ponieważ jest obecnie modyfikowany przez %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Obiekt jest obecnie modyfikowany przez %1$s. Twoje modyfikacje nie mogą zostać przesłane, ponieważ zostałyby nadpisane.',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'Obiekt jest obecnie modyfikowany przez %1$s. Będziesz mógł przesłać swoje modyfikacje, gdy zostanie on zwolniony.~~',
	'UI:CurrentObjectLockExpired' => 'Blokada zapobiegająca jednoczesnym modyfikacjom obiektu wygasła.',
	'UI:CurrentObjectLockExpired_Explanation' => 'Blokada zapobiegająca jednoczesnym modyfikacjom obiektu wygasła. Nie możesz już przesłać swojej modyfikacji, ponieważ inni użytkownicy mogą teraz modyfikować ten obiekt.',
	'UI:ConcurrentLockKilled' => 'Usunięto blokadę uniemożliwiającą modyfikacje bieżącego obiektu.',
	'UI:Menu:KillConcurrentLock' => 'Ubij blokadę jednoczesnej modyfikacji !',

	'UI:Menu:ExportPDF' => 'Eksport jako PDF...',
	'UI:Menu:PrintableVersion' => 'Wersja do druku',

	'UI:BrowseInlineImages' => 'Przeglądaj obrazy...',
	'UI:UploadInlineImageLegend' => 'Prześlij nowy obraz',
	'UI:SelectInlineImageToUpload' => 'Wybierz obraz do przesłania',
	'UI:AvailableInlineImagesLegend' => 'Dostępne obrazy',
	'UI:NoInlineImage' => 'Na serwerze nie ma obrazu. Użyj przycisku "Przeglądaj" powyżej, aby wybrać obraz ze swojego komputera i przesłać go na serwer.',

	'UI:ToggleFullScreen' => 'Przełącz Maksymalizuj / Minimalizuj',
	'UI:Button:ResetImage' => 'Odzyskaj poprzedni obraz',
	'UI:Button:RemoveImage' => 'Usuń obraz',
	'UI:Button:UploadImage' => 'Prześlij obraz z dysku',
	'UI:UploadNotSupportedInThisMode' => 'Modyfikacja obrazów lub plików nie jest obsługiwana w tym trybie.',

	'UI:Button:RemoveDocument' => 'Usuń dokument',

	// Search form
	'UI:Search:Toggle' => 'Zwiń / Rozwiń',
	'UI:Search:AutoSubmit:DisabledHint' => '<i class="fas fa-sync fa-1x"></i> Automatyczne przesyłanie zostało wyłączone dla tej klasy',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> W oparciu o Twoje preferencje wycofane dane są ukrywane',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Dodaj jakieś kryterium w polu wyszukiwania lub kliknij przycisk wyszukiwania, aby wyświetlić obiekty.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Dodaj nowe kryteria',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Ostatnio używane',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Najbardziej popularne',
	'UI:Search:AddCriteria:List:Others:Title' => 'Inne',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Jeszcze nic.',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Każdy',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s jest pusty',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s nie jest pusty',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s równa się %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s zawiera %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s zaczyna się od %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s kończy się na %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s dopasowanie %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s pomiędzy [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Każdy',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s z %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s do %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Każdy',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s z %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s aż do %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s i %3$s inne',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Każdy',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s zdefiniowany',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s nie zdefiniowany',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s i %3$s inne',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Każdy',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s zdefiniowany',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s nie zdefiniowany',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s i %3$s inne',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Każdy',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Jest pusty',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Nie jest pusty',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Równe',
	'UI:Search:Criteria:Operator:Default:Between' => 'Pomiędzy',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Zawiera',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Zaczyna się od',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Kończy się na',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Wyr. regularne',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Równe',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Większe',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Większe / równe',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Mniejsze',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Mniejsze / równe',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Różne',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Dopasowania',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filtruj...',
	'UI:Search:Value:Search:Placeholder' => 'Szukaj...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Zacznij wpisywać możliwe wartości.',
	'UI:Search:Value:Autocomplete:Wait' => 'Proszę czekać...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Brak wyników.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Zaznacz wszystkie / żadne',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Zaznacz wszystkie / żadne widoczne',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'Z',
	'UI:Search:Criteria:Numeric:Until' => 'Do',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Każdy',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Każdy',
	'UI:Search:Criteria:DateTime:From' => 'Z',
	'UI:Search:Criteria:DateTime:FromTime' => 'Z',
	'UI:Search:Criteria:DateTime:Until' => 'aż do',
	'UI:Search:Criteria:DateTime:UntilTime' => 'aż do',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Kiedykolwiek',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Kiedykolwiek',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Kiedykolwiek',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Kiedykolwiek',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Uwzględnione zostaną zależności wybranych obiektów.',

	'UI:Search:Criteria:Raw:Filtered' => 'Wyfiltrowane',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtr według %1$s',

	'UI:StateChanged' => 'Stan zmieniony',
));

//
// Expression to Natural language
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Expression:Operator:AND' => ' AND ',
	'Expression:Operator:OR' => ' OR ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'y',

	'Expression:Unit:Long:DAY' => 'dzień(i)',
	'Expression:Unit:Long:HOUR' => 'godzina(y)',
	'Expression:Unit:Long:MINUTE' => 'minuta(y)',

	'Expression:Verb:NOW' => 'teraz',
	'Expression:Verb:ISNULL' => ': nieokreślony',
));

//
// iTop Newsroom menu
//
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'UI:Newsroom:NoNewMessage' => 'Brak nowej wiadomości',
	'UI:Newsroom:MarkAllAsRead' => 'Oznacz wszystkie wiadomości jako przeczytane',
	'UI:Newsroom:ViewAllMessages' => 'Wyświetl wszystkie wiadomości',
	'UI:Newsroom:Preferences' => 'Preferencje newsroomu',
	'UI:Newsroom:ConfigurationLink' => 'Konfiguracja',
	'UI:Newsroom:ResetCache' => 'Zresetuj pamięć podręczną',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Wyświetl wiadomości od %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Wyświetlaj do %1$s wiadomiości w %2$s menu.',
));


Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:DataSources' => 'Źródła danych synchronizacji',
	'Menu:DataSources+' => 'Wszystkie źródła danych synchronizacji',
	'Menu:WelcomeMenu' => 'Witaj',
	'Menu:WelcomeMenu+' => 'Witaj w '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage' => 'Witaj',
	'Menu:WelcomeMenuPage+' => 'Witaj w '.ITOP_APPLICATION_SHORT,
	'Menu:AdminTools' => 'Administracja',
	'Menu:AdminTools+' => 'Narzędzia administracyjne',
	'Menu:AdminTools?' => 'Narzędzia dostępne tylko dla użytkowników posiadających profil administratora',
	'Menu:DataModelMenu' => 'Model danych',
	'Menu:DataModelMenu+' => 'Omówienie modelu danych',
	'Menu:ExportMenu' => 'Eksport',
	'Menu:ExportMenu+' => 'Eksportuj wyniki dowolnego zapytania w formacie HTML, CSV lub XML',
	'Menu:NotificationsMenu' => 'Powiadomienia',
	'Menu:NotificationsMenu+' => 'Konfiguracja powiadomień',
	'Menu:AuditCategories' => 'Kategorie audytu',
	'Menu:AuditCategories+' => 'Kategorie audytu',
	'Menu:Notifications:Title' => 'Kategorie audytu',
	'Menu:RunQueriesMenu' => 'Zapytania',
	'Menu:RunQueriesMenu+' => 'Uruchom dowolne zapytanie',
	'Menu:QueryMenu' => 'Słownik zapytań',
	'Menu:QueryMenu+' => 'Słownik zapytań',
	'Menu:UniversalSearchMenu' => 'Wyszukiwanie uniwersalne',
	'Menu:UniversalSearchMenu+' => 'Wyszukiwanie wszystkiego...',
	'Menu:UserManagementMenu' => 'Zarządzanie użytkownikami',
	'Menu:UserManagementMenu+' => 'UZarządzanie użytkownikami',
	'Menu:ProfilesMenu' => 'Profile',
	'Menu:ProfilesMenu+' => 'Profile',
	'Menu:ProfilesMenu:Title' => 'Profile',
	'Menu:UserAccountsMenu' => 'Konta użytkowników',
	'Menu:UserAccountsMenu+' => 'Konta użytkowników',
	'Menu:UserAccountsMenu:Title' => 'Konta użytkowników',
	'Menu:MyShortcuts' => 'Moje skróty',
	'Menu:UserManagement' => 'Zarządzanie użytkownikami',
	'Menu:Queries' => 'Zapytania',
	'Menu:ConfigurationTools' => 'Konfiguracja',
));
