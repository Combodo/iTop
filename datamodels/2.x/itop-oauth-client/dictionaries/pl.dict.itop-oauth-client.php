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
	'Class:OAuthClient' => 'Klient OAuth',
	'Class:OAuthClient/Attribute:client_id' => 'Id klienta',
	'Class:OAuthClient/Attribute:client_id+' => 'Długi ciąg znaków dostarczony przez dostawcę protokołu OAuth2',
	'Class:OAuthClient/Attribute:client_secret' => 'Sekretny ciąg',
	'Class:OAuthClient/Attribute:client_secret+' => 'Kolejny długi ciąg znaków dostarczony przez dostawcę OAuth2',
	'Class:OAuthClient/Attribute:description' => 'Opis',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Skrzynki pocztowe',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
	'Class:OAuthClient/Attribute:name' => 'Login',
	'Class:OAuthClient/Attribute:name+' => 'Ogólnie rzecz biorąc, jest to Twój adres e-mail',
	'Class:OAuthClient/Attribute:provider' => 'Dostawca',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => 'Adres URL przekierowania',
	'Class:OAuthClient/Attribute:redirect_url+' => 'Ten adres URL należy skopiować w konfiguracji OAuth2 dostawcy
Usuń pole, aby ponownie obliczyć wartość domyślną',
	'Class:OAuthClient/Attribute:refresh_token' => 'Token odświeżania',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Czas wygaśnięcia tokena odświeżania',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:status' => 'Status',
	'Class:OAuthClient/Attribute:status+' => 'Po utworzeniu użyj akcji „Generuj token dostępu”, aby móc korzystać z tego klienta OAuth',
	'Class:OAuthClient/Attribute:status/Value:
              active
              
                $ibo-lifecycle-active-state-primary-color
                $ibo-lifecycle-active-state-secondary-color
	' => '
              aktywny
              
                $ibo-lifecycle-active-state-primary-color
                $ibo-lifecycle-active-state-secondary-color
                
              
            ',
	'Class:OAuthClient/Attribute:status/Value:
              active
              
                $ibo-lifecycle-active-state-primary-color
                $ibo-lifecycle-active-state-secondary-color
                
              
            +' => '',
	'Class:OAuthClient/Attribute:status/Value:
              inactive
              
                $ibo-lifecycle-inactive-state-primary-color
                $ibo-lifecycle-inactive-state-secondary-color
	' => '
              nieaktywny
              
                $ibo-lifecycle-inactive-state-primary-color
                $ibo-lifecycle-inactive-state-secondary-color
                
              
            ',
	'Class:OAuthClient/Attribute:status/Value:
              inactive
              
                $ibo-lifecycle-inactive-state-primary-color
                $ibo-lifecycle-inactive-state-secondary-color
                
              
            +' => '',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Wygenerowano token dostępu',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'Brak tokena dostępu',
	'Class:OAuthClient/Attribute:token' => 'Token dostępu',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => 'Wygaśnięcie tokenu dostępu',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClientAzure' => 'Klient OAuth dla Microsoft Azure',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Zaawansowany zakres',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'Gdy tylko coś tu wpiszesz, będzie to miało pierwszeństwo przed wyborem „Zakres”, który zostanie następnie zignorowany',
	'Class:OAuthClientAzure/Attribute:scope' => 'Zakres',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Zwykle odpowiedni jest wybór domyślny',
	'Class:OAuthClientAzure/Attribute:scope/Value:
              IMAP
	' => '
              IMAP
            ',
	'Class:OAuthClientAzure/Attribute:scope/Value:
              IMAP
            +' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:
              SMTP
	' => '
              SMTP
            ',
	'Class:OAuthClientAzure/Attribute:scope/Value:
              SMTP
            +' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientAzure/Attribute:tenant' => 'Dzierżawa',
	'Class:OAuthClientAzure/Attribute:tenant+' => 'Identyfikator dzierżawy skonfigurowanej aplikacji. W przypadku aplikacji z wieloma dzierżawcami użyj „wspólnego”.',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Używany do SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'Przynajmniej jeden klient OAuth musi mieć tę flagę ustawioną na „Tak”, jeśli chcesz, aby iTop używał jej do wysyłania wiadomości e-mail',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:
              no
	' => '
              nie
            ',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:
              no
            +' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:
              yes
	' => '
              tak
            ',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:
              yes
            +' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'Nie',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Tak',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Użyty zakres',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:
              advanced
	' => '
              zaawansowany
            ',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:
              advanced
            +' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:
              simple
	' => '
              prosty
            ',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:
              simple
            +' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Zaawansowany',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Prosty',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle' => 'Klient OAuth dla Google',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Zaawansowany zakres',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'Gdy tylko coś tu wpiszesz, będzie to miało pierwszeństwo przed wyborem „Zakres”, który zostanie następnie zignorowany',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Zakres',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Zwykle odpowiedni jest wybór domyślny',
	'Class:OAuthClientGoogle/Attribute:scope/Value:
              IMAP
	' => '
              IMAP
            ',
	'Class:OAuthClientGoogle/Attribute:scope/Value:
              IMAP
            +' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:
              SMTP
	' => '
              SMTP
            ',
	'Class:OAuthClientGoogle/Attribute:scope/Value:
              SMTP
            +' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Używany do SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'Przynajmniej jeden klient OAuth musi mieć tę flagę ustawioną na „Tak”, jeśli chcesz, aby iTop używał jej do wysyłania wiadomości e-mail',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:
              no
	' => '
              nie
            ',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:
              no
            +' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:
              yes
	' => '
              tak
            ',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:
              yes
            +' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'Nie',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Tak',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Użyty zakres',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:
              advanced
	' => '
              zaawansowany
            ',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:
              advanced
            +' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:
              simple
	' => '
              prosty
            ',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:
              simple
            +' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Zaawansowany',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Prosty',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Menu:CreateMailbox' => 'Utwórz skrzynkę pocztową...',
	'Menu:GenerateTokens' => 'Wygeneruj token dostępu...',
	'Menu:OAuthClient' => 'Klient OAuth',
	'Menu:OAuthClient+' => '',
	'Menu:RegenerateTokens' => 'Wygeneruj ponownie token dostępu...',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'Kombinacja Loginu (%1$s) i SMTP (%2$s) była już użyta dla klienta OAuth',
	'OAuthClient:baseinfo' => 'Informacje podstawowe',
	'OAuthClient:scope' => 'Zakres',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Tworzenie skrzynki pocztowej',
	'itop-oauth-client:Message:MissingToken' => 'Wygeneruj token dostępu przed użyciem tego klienta OAuth',
	'itop-oauth-client:Message:RegenerateToken' => 'Wygeneruj ponownie token dostępu, aby uwzględnić zmiany',
	'itop-oauth-client:Message:TokenCreated' => 'Utworzono token dostępu',
	'itop-oauth-client:Message:TokenError' => 'Token dostępu nie został wygenerowany z powodu błędu serwera',
	'itop-oauth-client:Message:TokenRecreated' => 'Token dostępu został wygenerowany ponownie',
	'itop-oauth-client:MissingOAuthClient' => 'Brak klienta Oauth dla nazwy użytkownika %1$s',
	'itop-oauth-client:TestSMTP' => 'Test wysyłania e-maili',
	'itop-oauth-client:UsedForSMTP' => 'Ten klient OAuth jest używany do SMTP',
]);
