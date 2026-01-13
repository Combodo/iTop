<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2022)
 *
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'ActionGoogleChatNotification:message' => 'Bericht',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'Andere elementen om toe te voegen',
	'ActionMicrosoftTeamsNotification:message' => 'Basisbericht',
	'ActionMicrosoftTeamsNotification:theme' => 'Thema',
	'ActionRocketChatNotification:additionalelements' => 'Bot info',
	'ActionRocketChatNotification:message' => 'Basis-bericht',
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Notificatie van <%2$s|%1$s> (%3$s)',
	'ActionSlackNotification:additionalelements' => 'Extra elementen',
	'ActionSlackNotification:message' => 'Basis-bericht',
	'ActionWebhook:advancedparameters' => 'Geävanceerde parameters',
	'ActionWebhook:baseinfo' => 'Algemene info',
	'ActionWebhook:moreinfo' => 'Meer info',
	'ActionWebhook:requestparameters' => 'Request parameters',
	'ActionWebhook:webhookconnection' => 'Webhook-connectie',
	'Class:ActionGoogleChatNotification' => 'Google Chat-notificatie',
	'Class:ActionGoogleChatNotification+' => 'Stuur een notificatie als een Google Chat-bericht in een space',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Bericht',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'Bericht dat getoond zal worden in de chat. Enkel tekst zonder opmaak wordt momenteel ondersteund.',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'PHP-methode om de payload klaar te zetten die verstuurd wordt via de webhook. Gebruik dit als de standaardopties niet volstaan of als de payload-structuur dynamisch opgebouwd moet worden.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). Bv: "$this->XXX" voor een functie die binnen de PHP-klasse van het object gedefinieerd is als "public function XXX"
- Via eender welke PHP-klasse. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass::XXX" voor een functie die in de PHP-klasse "\SomeClass" gedefinieerd is "public static function XXX"

BELANGRIJK: Indien geconfigureerd, zal \'bericht\' genegeerd worden.',
	'Class:ActionMicrosoftTeamsNotification' => 'Microsoft Teams notification',
	'Class:ActionMicrosoftTeamsNotification+' => 'Stuur een notificatie als een Microsoft Teams-bericht in een kanaal.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Afbeelding medallion',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'URL van de afbeelding die als medaillon wordt getoond bij het bericht. Dit moet een geldige  publiek toegankelijke URL zijn zodat Microsoft Teams dit kan laden.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Delete button',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Voeg een knop toe onder het bericht om het object te verwijderen in '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'Nee',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Ja',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Attributen van',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'Toon bijkomende attributen onder het bericht. Deze komen doorgaans uit de \'lijst\'-weergave of uit de \'msteams\'-weergave van het object dat de notificatie triggert. Merk op dat de \'msteams\'-weergave eerst gedefinieerd moet worden in het datamodel (zlist)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'Standaard lijst-weergave',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'Aangepaste MS Teams-weergave',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Knop "Modify"',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Voeg een knop toe onder het bericht om het object aan te passen in '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'Nee',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Ja',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Andere actie-knoppen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Voeg andere acties toe (bv. beschikbare transities voor het object) onder het bericht',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'Nee',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'Specifieer',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Ja',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Bericht',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'PHP-methode om de payload klaar te zetten die verstuurd wordt via de webhook. Gebruik dit als de standaardopties niet volstaan of als de payload-structuur dynamisch opgebouwd moet worden.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). Bv: "$this->XXX" voor een functie die binnen de PHP-klasse van het object gedefinieerd is als "public function XXX"
- Via eender welke PHP-klasse. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass::XXX" voor een functie die in de PHP-klasse "\SomeClass" gedefinieerd is "public static function XXX"

BELANGRIJK: Indien geconfigureerd, zullen \'titel\', \'bericht\' en alle \'extra elementen\' genegeerd worden.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Andere actiecodes',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Specifieer welke andere actieknoppen beschikbaar moeten zijn onder het bericht. Dit wordt een kommagescheiden oplijsting van actiecodes (bv. \'ev_reopen, ev_close\')',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Highlight-kleur',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Highlight-kleur van het bericht in Microsoft Teams. Dit moet een geldige hexadecimale waarde zijn (bv. #FF0000)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Titel',
	'Class:ActionRocketChatNotification' => 'Rocket.Chat-notificatie',
	'Class:ActionRocketChatNotification+' => 'Stuur een notificatie als een Rocket.Chat-bericht in een kanaal of naar een gebruiker',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Hiermee wordt de standaard-alias van de bot overschreven. Dit verschijnt voor de gebruikersnaam van het bericht.',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Emoji avatar',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Hiermee wordt de standaard-avatar van de bot overschreven met eender welke Rocket.Chat emoji (bv. :ghost:, :white_check_mark:, ...). Als er al een URL opgegeven werd, zal de emoji-avatar niet getoond worden.',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Avatar',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Hiermee wordt de standaard-avatar van de bot overschreven. Dit moet een absolute URL zijn naar een afbeelding die gebruikt zal worden.',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Bericht',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Bericht dat getoond zal worden in de chat',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'PHP-methode om de payload klaar te zetten die verstuurd wordt via de webhook. Gebruik dit als de standaardopties niet volstaan of als de payload-structuur dynamisch opgebouwd moet worden.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). Bv: "$this->XXX" voor een functie die binnen de PHP-klasse van het object gedefinieerd is als "public function XXX"
- Via eender welke PHP-klasse. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass::XXX" voor een functie die in de PHP-klasse "\SomeClass" gedefinieerd is "public static function XXX"

BELANGRIJK: Indien geconfigureerd, zullen \'bericht\' en alle \'bot-informatie\' genegeerd worden.',
	'Class:ActionSlackNotification' => 'Slack-notificatie',
	'Class:ActionSlackNotification+' => 'Stuur een notificatie als een Slack-bericht in een kanaal of naar een gebruiker.',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Knop "Delete"',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Voeg een knop toe onder het bericht om het object te verwijderen in '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'Nee',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attributen van',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Toon bijkomende attributen onder het bericht. Deze komen doorgaans uit de \'lijst\'-weergave of uit de \'slack\'-weergave van het object dat de notificatie triggert. Merk op dat de \'slack\'-weergave eerst gedefinieerd moet worden in het datamodel (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'Standaard lijst-weergave',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'Aangepaste Slack-weergave',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Knop "Modify"',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Voeg een knop toe onder het bericht om het object aan te passen in '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'No',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Yes',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Andere actie-knoppen',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Voeg andere acties toe (bv. beschikbare transities voor het object) onder het bericht',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'Nee',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'Specifieer',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Gebruikersinfo.',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Toon de gebruikersinfo (volledige naam) onder het bericht',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'Nee',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:message' => 'Bericht',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'PHP-methode om de payload klaar te zetten die verstuurd wordt via de webhook. Gebruik dit als de standaardopties niet volstaan of als de payload-structuur dynamisch opgebouwd moet worden.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). Bv: "$this->XXX" voor een functie die binnen de PHP-klasse van het object gedefinieerd is als "public function XXX"
- Via eender welke PHP-klasse. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass::XXX" voor een functie die in de PHP-klasse "\SomeClass" gedefinieerd is "public static function XXX"

BELANGRIJK: Indien geconfigureerd, zullen \'bericht\' en \'Extra elementen\' genegeerd worden.',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Andere acties',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Specifieer welke andere actieknoppen beschikbaar moeten zijn onder het bericht. Dit wordt een kommagescheiden oplijsting van actiecodes (bv. \'ev_reopen, ev_close\')',
	'Class:ActionWebhook' => 'Webhook (generiek)',
	'Class:ActionWebhook+' => 'Webhook voor eender welke soort applicatie',
	'Class:ActionWebhook/Attribute:asynchronous+' => 'Whether this action should be executed in background or not (mind that global setting for webhook actions is the "prefer_asynchronous" conf. parameter of the "combodo-webhook-action" module)~~',
	'Class:ActionWebhook/Attribute:headers' => 'Hoofding (headers)',
	'Class:ActionWebhook/Attribute:headers+' => 'Headers voor het HTTP-verzoek. Eén header per regel (bv. \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:language' => 'Taal',
	'Class:ActionWebhook/Attribute:language+' => 'Taal van deze notificatie. Meestal gebruikt om meldingen te zoeken, maar ook om de labels van attributen te vertalen',
	'Class:ActionWebhook/Attribute:method' => 'Methode',
	'Class:ActionWebhook/Attribute:method+' => 'Methode die gebruikt wordt om het HTTP-verzoek uit te voeren.',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:path' => 'Pad',
	'Class:ActionWebhook/Attribute:path+' => 'Additioneel pad om toe te voegen aan de connectie-URL (bv. \'/some/specific-endpoint\')',
	'Class:ActionWebhook/Attribute:payload' => 'Bericht (payload)',
	'Class:ActionWebhook/Attribute:payload+' => 'Data die verstuurd wordt tijdens het aanroepen van de webhook. Meestal is dit in een vast JSON-formaat.

BELANGRIJK: Dit wordt genegeerd als er een \'"Bericht opmaak"-functie\' geconfigureerd is',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => '"Bericht opmaak"-functie',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'PHP-methode om de payload klaar te zetten die verstuurd wordt via de webhook. Gebruik dit als de payload een dynamische structuur heeft.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). Bv: "$this->XXX" voor een functie die binnen de PHP-klasse van het object gedefinieerd is als "public function XXX"
- Via eender welke PHP-klasse. De methode moet static en public zijn. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass::XXX" voor een functie die in de PHP-klasse "\SomeClass" gedefinieerd is "public static function XXX"

BELANGRIJK: Indien geconfigureerd, zal het \'Payload\'-veld genegeerd worden.',
	'Class:ActionWebhook/Attribute:process_response_callback' => '"Verwerk antwoord"-functie',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP-methode om het antwoord te verwerken.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). Bv: $this->XXX
- Via eender welke PHP-klasse. De methode moet static en public zijn. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass:XXX" waarbij deze functie gedefinieerd is in de PHP-klasse "\SomeClass" als "public static function XXX($oObject, $oResponse, $oAction)"
- $oResponse kan null zijn in sommige gevallen (bv. de HTTP-aanvraag is mislukt)',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Connectie',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Welke informatie gebruikt moet worden als de status \'In productie\' is',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Test-connection',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Welke informatie gebruikt moet worden als de status \'Wordt getest\' is',
	'Class:ActioniTopWebhook' => 'iTop webhook',
	'Class:ActioniTopWebhook+' => 'Webhook naar een externe iTop-applicatie',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Headers voor het HTTP-verzoek. Eén header per regel (bv. \'Content-type: application/x-www-form-urlencoded\')

BELANGRIJK:
- \'Content-type\' moet ingesteld worden op \'application/x-www-form-urlencoded\' voor iTop, ook al wordt er JSON verstuurd.
- Een \'Basic authorization\' header wordt automatisch toegevoegd tijdens het versturen. Het bevat de logingegevens die geconfigureerd zijn in de gekozen iTop-connectie.',
	'Class:ActioniTopWebhook/Attribute:payload' => 'JSON-data',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'De JSON-payload, dit moet een JSON-structuur zijn met de naam van de operatie (operation) en parameters. Zie documentatie voor gedetailleerde info.',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'PHP-methode om de payload klaar te zetten die verstuurd wordt via de webhook. Gebruik dit als de payload een dynamische structuur heeft.

Je kan 2 soorten methodes gebruiken:
- Gedefinieerd op het object zelf (bv. Gebruikersverzoek). De methode moet publiek zijn. Bv: "$this->XXX" voor een functie die binnen de PHP-klasse van het object gedefinieerd is als "public function XXX"
- Via eender welke PHP-klasse. De naam moet fully qualified opgegeven worden. Bv: "\SomeClass::XXX" voor een functie die in de PHP-klasse "\SomeClass" gedefinieerd is "public static function XXX"

BELANGRIJK: Indien geconfigureerd, zal het \'JSON-data\'-veld genegeerd worden.',
	'Class:EventWebhook' => 'Webhook verzendgebeurtenis',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Klasse',
	'Class:EventWebhook/Attribute:headers' => 'Hoofding (headers)',
	'Class:EventWebhook/Attribute:payload' => 'Bericht (payload)',
	'Class:EventWebhook/Attribute:response' => 'Antwoord (response)',
	'Class:EventWebhook/Attribute:webhook_url' => 'URL Webhook',
	'Class:RemoteApplicationConnection' => 'Connectie met externe applicatie',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Webhook-notificaties',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Webhook-notificaties die deze connectie gebruiken',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Omgeving',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Soort omgeving van deze connectie',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Ontwikkeling',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Test',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Productie',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Soort applicatie',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Soort applicatie (gebruik "generiek" als jouw applicatie niet in de lijst staat)',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationType' => 'Soort externe applicatie',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Connecties',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Connecties voor deze applicatie',
	'Class:RemoteiTopConnection' => 'Externe iTop-verbinding',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Auth. wachtwoord',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Wachtwoord van de gebruiker (op de externe iTop) die gebruikt wordt voor authenticatie',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Auth. gebruiker',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Login van de gebruiker (op de externe iTop) die gebruikt wordt voor authenticatie',
	'Class:RemoteiTopConnection/Attribute:version' => 'API versie',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Versie van de API (bv. 1.3)',
	'Class:RemoteiTopConnectionToken' => 'Remote iTop connection using a Token~~',
	'Class:RemoteiTopConnectionToken/Attribute:token+' => 'Token~~',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Soort webhook-acties',
	'Dashboard:Integrations:Outgoing:Title' => 'Uitgaande webhook-integraties',
	'Dashboard:Integrations:Title' => 'Integraties met externe applicaties',
	'Menu:Webhook' => 'Webhooks~~',
	'RemoteApplicationConnection:authinfo' => 'Authenticatie',
	'RemoteApplicationConnection:baseinfo' => 'Algemene info',
	'RemoteApplicationConnection:moreinfo' => 'Meer info',
	'Class:ActionWebhook/Error:InvalidJSONFormatForPayload' => 'Invalid JSON format for Payload~~',
]);
