<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2017 ITOMIG GmbH
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Menus
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:Integrations' => 'Integrációk',
	'Dashboard:Integrations:Title' => 'Integrációk külső alkalmazásokkal',
	'Dashboard:Integrations:Outgoing:Title' => 'Kimenő webhook integrációk',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Webhook típusú műveletek',
));

// Base classes
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// RemoteApplicationType
	'Class:RemoteApplicationType' => 'Távoli alkalmazás típusa',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Kapcsolatok',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Csatlakozások az adott alkalmazáshoz',

	// RemoteApplicationConnection
	'Class:RemoteApplicationConnection' => 'Távoli alkalmazáskapcsolat',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Alkalmazás típus',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Az alkalmazás típusa, amelyre a kapcsolat vonatkozik (használja a \'Generic\' opciót, ha a sajátja nem szerepel a listán).',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Környezet',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'A kapcsolat környezetének típusa',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Fejlesztés alatt',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Tesztelés alatt',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Bevezetve',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Webhook értesítések',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Webhook értesítések ezzel a kapcsolattal',
	// - Fieldsets
	'RemoteApplicationConnection:baseinfo' => 'Általános információ',
	'RemoteApplicationConnection:moreinfo' => 'További információ',
	'RemoteApplicationConnection:authinfo' => 'Azonosítás',

	// EventWebhook
	'Class:EventWebhook' => 'Webhook kibocsátási esemény',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Végleges osztály',
	'Class:EventWebhook/Attribute:webhook_url' => 'Webhook URL',
	'Class:EventWebhook/Attribute:headers' => 'Fejlécek',
	'Class:EventWebhook/Attribute:payload' => 'Adatfolyam',
	'Class:EventWebhook/Attribute:response' => 'Válasz',

	// ActionWebhook
	'Class:ActionWebhook' => 'Webhook hívás (generic)',
	'Class:ActionWebhook+' => 'Webhook hívás bármilyen alkalmazáshoz',
	'Class:ActionWebhook/Attribute:language' => 'Nyelv',
	'Class:ActionWebhook/Attribute:language+' => 'Az értesítés nyelve, leginkább az értesítések keresésekor használatos, de az attribútumok címkéjének lefordítására is használható.',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Kapcsolat',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'A \'Bevezetve\' állapot esetén használandó kapcsolati információ',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Teszt kapcsolat',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Kapcsolati információ, amelyet akkor kell használni, ha az állapot \'Tesztelés alatt\'',
	'Class:ActionWebhook/Attribute:method' => 'Módszer',
	'Class:ActionWebhook/Attribute:method+' => 'A HTTP-kérés módszere',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:headers' => 'Fejlécek',
	'Class:ActionWebhook/Attribute:headers+' => 'A HTTP-kérelem fejlécei, soronként egynek kell lennie (pl. \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:payload' => 'Adatfolyam',
	'Class:ActionWebhook/Attribute:payload+' => 'A webhook-hívás során küldött adatok, legtöbbször ez egy JSON karakterlánc. Ezt használja, ha az adatfolyam struktúrája statikus.

FONTOS: A rendszer figyelmen kívül hagyja, ha a \'Adatfolyam visszahívás előkészítése\' be van állítva',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Adatfolyam visszahívás előkészítése',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'PHP-módszer a webhook-hívás során elküldendő hasznos adatok előkészítésére. Ezt használja, ha az adatfolyam struktúráját dinamikusan kell felépíteni.

2 féle módszert használhat:
- A kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($aContextArgs, $oLog, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

FONTOS: Ha be van állítva, az \'Adatfolyam\' attribútumot figyelmen kívül hagyjuk.',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Folyamat válasz visszahívás',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP-módszer a webhook-hívás válaszának feldolgozására.

2 típusú metódust használhat:
- Magából a kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($oResponse, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $oResponse, $oAction)
- $oResponse bizonyos esetekben (pl. a kérés elküldése sikertelen) null lehet.',
	// - Fieldsets
	'ActionWebhook:baseinfo' => 'Általános információ',
	'ActionWebhook:moreinfo' => 'További információ',
	'ActionWebhook:webhookconnection' => 'Webhook kapcsolat',
	// Note: This one is used by derivated classes
	'ActionWebhook:requestparameters' => 'Lekérési paraméterek',
	'ActionWebhook:advancedparameters' => 'Speciális paraméterek',
));

// iTop
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// RemoteiTopConnection
	'Class:RemoteiTopConnection' => 'Távoli iTop kapcsolat',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Felhasználónév',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'A hitelesítéshez használt felhasználó bejelentkezése (a távoli iTopon)',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Jelszó',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'A hitelesítéshez használt felhasználó jelszava (a távoli iTopon)',
	'Class:RemoteiTopConnection/Attribute:version' => 'API verzió',
	'Class:RemoteiTopConnection/Attribute:version+' => 'A meghívott API verziója (pl. 1.3)',

	// ActioniTopWebhook
	'Class:ActioniTopWebhook' => 'iTop webhook hívás',
	'Class:ActioniTopWebhook+' => 'Webhook hívás egy távoli iTop alkalmazáshoz',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'A HTTP-kérelem fejlécének soronként egynek kell lennie (pl. \'Content-type: application/x-www-form-urlencoded\')

IFONTOS:
- \'Content-type\' az \'application/x-www-form-urlencoded\' értéket kell beállítani az iTop esetében, még akkor is, ha JSON-t küldünk.
- A \'Basic authorization\' fejléc automatikusan hozzá lesz csatolva a kéréshez a küldés során, amely tartalmazza a kiválasztott kapcsolat hitelesítő adatait.',
	'Class:ActioniTopWebhook/Attribute:payload' => 'JSON adat',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'A JSON adatfolyamnak egy JSON karakterláncnak kell lennie, amely tartalmazza a művelet nevét és paramétereit, részletes információért lásd a dokumentációt.',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'PHP-módszer a webhook-hívás során elküldendő hasznos adatok előkészítésére. Ezt használja, ha az adatfolyam struktúráját dinamikusan kell felépíteni.

2 típusú metódust használhat:
- Magából a kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($aContextArgs, $oLog, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

FONTOS: Ha be van állítva, a \'JSON adat\' attribútum figyelmen kívül marad.',
));

// Slack
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// ActionSlackNotification
	'Class:ActionSlackNotification' => 'Slack értesítés',
	'Class:ActionSlackNotification+' => 'Értesítés küldése Slack-üzenetként egy csatornában vagy egy felhasználónak',
	'Class:ActionSlackNotification/Attribute:message' => 'Üzenet',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attribútumok',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'További attribútumok megjelenítése az üzenet alatt. Ezek lehetnek az értesítést kiváltó objektum szokásos \'Lista\' nézetéből vagy az egyéni \'Slack\' nézetből. Megjegyzendő, hogy a \'Slack\' nézetet először az adatmodellben kell definiálni (zlist).',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'a szokásos listanézetből',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'az egyéni "slack" nézetből',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Felhasználói infó.',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Felhasználói információk megjelenítése (teljes név) az üzenet alatt',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'Nem',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Igen',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Módosítás gomb',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Rakjon ki egy gombot az üzenet alatt az objektum szerkesztéséhez az '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'Nem',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Igen',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Törlés gomb',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Rakjon ki egy gombot az üzenet alatt az objektum törléséhez az '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'Nem',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Igen',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Egyéb művelet gombok',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Az üzenet alatt egyéb műveletek (például az aktuális állapotban elérhető átmenetek) feltüntetése.',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'Nem',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'Adja meg',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Igen',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Egyéb műveletkódok',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Adja meg, hogy mely műveleteket kívánja gombokként megjeleníteni az üzenet alatt. A műveletek kódjainak vesszővel elválasztott listája kell, hogy legyen (pl. \'ev_reopen, ev_close\').',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'PHP-módszer a webhook-hívás során elküldendő hasznos adatok előkészítésére. Ezt akkor használja, ha a standard opciók nem elég rugalmasak, vagy ha az adatfolyam struktúráját dinamikusan kell felépíteni.

2 típusú metódust használhat:
- Magából a kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($aContextArgs, $oLog, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

FONTOS: Ha be van állítva, az \'Üzenet\' és az összes \'További elemek\' figyelmen kívül marad.',
	// - Fieldsets
	'ActionSlackNotification:message' => 'Bázisüzenet',
	'ActionSlackNotification:additionalelements' => 'További elemek',

	// Payload
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Üzenetet küldött <%2$s|%1$s> (%3$s)',
));

// Rocket.Chat
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// ActionRocketChatNotification
	'Class:ActionRocketChatNotification' => 'Rocket.Chat értesítés',
	'Class:ActionRocketChatNotification+' => 'Értesítés küldése Rocket.Chat üzenetként egy csatornán vagy egy felhasználónak',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Üzenet',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'A csevegésben megjelenő üzenet',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Felülírja az alapértelmezett bot aliast, az üzenet felhasználóneve előtt jelenik meg.',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Avatár kép',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Felülírja az alapértelmezett bot avatárt, abszolút URL-nek kell lennie a használni kívánt képhez.',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Emoji avatár',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Felülírja az alapértelmezett bot avatárt, lehet bármilyen Rocket.Chat emoji (pl. :ghost:, :white_check_mark:, ...). Vegye figyelembe, hogy ha egy URL avatar van beállítva, az emoji nem jelenik meg.',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'PHP-módszer a webhook-hívás során elküldendő hasznos adatok előkészítésére. Ezt akkor használja, ha a standard opciók nem elég rugalmasak, vagy ha az adatfolyam struktúráját dinamikusan kell felépíteni.

2 típusú metódust használhat:
- Magából a kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($aContextArgs, $oLog, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

FONTOS: Ha be van állítva, a \'Üzenet\' és az összes \'Bot információ\' figyelmen kívül marad.',
	// - Fieldsets
	'ActionRocketChatNotification:message' => 'Bázisüzenet',
	'ActionRocketChatNotification:additionalelements' => 'Bot információ'
));

// Google Chat
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// ActionGoogleChatNotification
	'Class:ActionGoogleChatNotification' => 'Google Chat értesítés',
	'Class:ActionGoogleChatNotification+' => 'Értesítés küldése Google Chat üzenetként',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Üzenet',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'A csevegésben megjelenő üzenet, egyelőre csak egyszerű szöveges üzenet támogatott.',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'PHP-módszer a webhook-hívás során elküldendő hasznos adatok előkészítésére. Ezt akkor használja, ha a standard opciók nem elég rugalmasak, vagy ha az adatfolyam struktúráját dinamikusan kell felépíteni.

2 típusú metódust használhat:
- Magából a kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($aContextArgs, $oLog, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

FONTOS: Ha be van állítva, az \'Üzenet\' figyelmen kívül marad.',
	// - Fieldsets
	'ActionGoogleChatNotification:message' => 'Üzenet',
));

// Microsoft Teams
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// ActionMicrosoftTeamsNotification
	'Class:ActionMicrosoftTeamsNotification' => 'Microsoft Teams értesítés',
	'Class:ActionMicrosoftTeamsNotification+' => 'Értesítés küldése Microsoft Teams üzenetként egy csatornában',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Cím',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Üzenet',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Attribútumok',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'További attribútumok megjelenítése az üzenet alatt. Ezek lehetnek a szokásos \'lista\' nézetből vagy az értesítést kiváltó objektum egyéni \'msteams\' nézetéből. Megjegyzendő, hogy a \'msteams\' nézetet először az adatmodellben kell definiálni (zlist).',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'a szokásos listanézetből',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'az egyéni "msteams" nézetből',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Módosítás gomb',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Rakjon ki egy gombot az üzenet alatt az objektum szerkesztéséhez az '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'Nem',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Igen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Törlés gomb',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Rakjon ki egy gombot az üzenet alatt az objektum törléséhez az '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'Nem',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Igen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Egyéb művelet gombok',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Más műveletek (például az aktuális állapotban rendelkezésre álló átmenetek) az üzenet alatt.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'Nem',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'Adja meg',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Igen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Egyéb műveletkódok',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Adja meg, hogy mely műveleteket kívánja gombokként megjeleníteni az üzenet alatt. A műveletek kódjainak vesszővel elválasztott listája kell, hogy legyen (pl. \'ev_reopen, ev_close\')',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Kiemelőszín',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Az üzenetkártya kiemelési színe a Microsoft Teamsben, érvényes hexadecimális színnek kell lennie (pl. FF0000)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Medál kép',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'Az üzenetkártyán medálként megjelenítendő kép URL-címe; a képnek nyilvánosan elérhetőnek kell lennie az interneten ahhoz, hogy a Microsoft Teams meg tudja jeleníteni.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'PHP-módszer a webhook-hívás során elküldendő hasznos adatok előkészítésére. Ezt akkor használja, ha a standard opciók nem elég rugalmasak, vagy ha a hasznos teher struktúráját dinamikusan kell felépíteni.

2 típusú metódust használhat:
- Magából a kiváltó objektumból (pl. UserRequest), nyilvánosnak kell lennie. Példa: $this->XXX($aContextArgs, $oLog, $oAction)
- Bármely PHP osztályból, statikusnak ÉS nyilvánosnak kell lennie. A névnek teljesen minősített névnek kell lennie. Példa: \SomeClass::XXX($oObject, $aContextArgs, $oLog, $oAction)

FONTOS: Ha be van állítva, a \'Cím\', \'Üzenet\' és minden \'További elemek\' figyelmen kívül marad.',
	// - Fieldsets
	'ActionMicrosoftTeamsNotification:message' => 'Bázisüzenet',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'További elemek',
	'ActionMicrosoftTeamsNotification:theme' => 'Téma',
));
