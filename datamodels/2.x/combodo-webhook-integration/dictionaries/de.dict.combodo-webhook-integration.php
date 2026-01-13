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
Dict::Add('DE DE', 'German', 'Deutsch', [
	'ActionGoogleChatNotification:message' => 'Nachricht',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'Zusätzlich einzubeziehende Elemente',
	'ActionMicrosoftTeamsNotification:message' => 'Basis-Nachricht',
	'ActionMicrosoftTeamsNotification:theme' => 'Theme',
	'ActionRocketChatNotification:additionalelements' => 'Bot-Informationen',
	'ActionRocketChatNotification:message' => 'Basis-Nachricht',
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Benachrichtigung von <%2$s|%1$s> (%3$s)',
	'ActionSlackNotification:additionalelements' => 'Zusätzlich einzubeziehende Elemente',
	'ActionSlackNotification:message' => 'Basis-Nachricht',
	'ActionWebhook:advancedparameters' => 'Erweiterte-Parameter',
	'ActionWebhook:baseinfo' => 'Allgemeine Informationen',
	'ActionWebhook:moreinfo' => 'Weitere Informationen',
	'ActionWebhook:requestparameters' => 'Request-Parameter',
	'ActionWebhook:webhookconnection' => 'Webhook-Verbindung',
	'Class:ActionGoogleChatNotification' => 'Google Chat-Benachrichtigung',
	'Class:ActionGoogleChatNotification+' => 'Senden einer Benachrichtigung als Google Chat-Nachricht in einen Bereich',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Nachricht',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'Nachricht, die im Chat angezeigt wird, Zurzeit wird nur Plain-Text unterstützt.',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'PHP-Methode zur Vorbereitung des Payloads, der während des Webhook-Aufrufs gesendet werden sollen. Wählen Sie diese Option, wenn die Standardoptionen nicht flexibel genug sind oder wenn Ihre Nutzlaststruktur dynamisch aufgebaut werden muss.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX

WICHTIG: Wenn dies gesetzt ist, wird das Attribut \'Nachricht\' ignoriert.',
	'Class:ActionMicrosoftTeamsNotification' => 'Microsoft Teams-Benachrichtigung',
	'Class:ActionMicrosoftTeamsNotification+' => 'Senden einer Benachrichtigung als Microsoft Teams-Nachricht in einen Kanal',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Medaillon-Bild',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'URL des Bildes, das als Medaillon in der Nachrichtenkarte angezeigt werden soll. Es muss im Internet öffentlich zugänglich sein, damit Microsoft Teams es anzeigen kann.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Löschen-Button',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Fügen Sie einen Button unter der Nachricht ein, um das Objekt in '.ITOP_APPLICATION_SHORT.' zu löschen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'Nein',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Ja',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Attribute von',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'Anzeige zusätzlicher Attribute unterhalb der Nachricht. Sie können entweder aus der üblichen Ansicht \'list\' oder der benutzerdefinierten Ansicht \'msteams\' des Objekts stammen, das die Meldung auslöst. Beachten Sie, dass die \'msteams\'-Sicht zuerst im Datenmodell definiert werden muss (zlist)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'Die übliche Listenansicht',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'Die benutzerdefinierte "msteams"-Ansicht',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Bearbeiten-Button',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Fügen Sie einen Button unter der Nachricht ein, um das Objekt in '.ITOP_APPLICATION_SHORT.' zu bearbeiten',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'Nein',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Ja',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Button für andere Aktionen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Andere Aktionen (z. B. Übergänge, die im aktuellen Zustand verfügbar sind) unterhalb der Nachricht einfügen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'Nein',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'Spezifisch',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Ja',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format' => 'JSON-Format',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format+' => 'Festlegung welches Format zur Übertragung verwendet werden soll.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format/Value:message_card' => 'Message Card',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format/Value:adaptive_card' => 'Adaptive Card',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Nachricht',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'PHP-Methode zur Vorbereitung des Payloads, der während des Webhook-Aufrufs gesendet werden sollen. Wählen Sie diese Option, wenn die Standardoptionen nicht flexibel genug sind oder wenn Ihre Nutzlaststruktur dynamisch aufgebaut werden muss.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX

WICHTIG: Wenn dies gesetzt ist, werden das Attribut \'Titel\', \'Nachricht\' sowie alle \'zusätzlichen Elemente\' ignoriert.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Codes der anderen Aktionen',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Geben Sie an, welche Aktionen als Buttons unter der Nachricht angezeigt werden sollen. Es sollte eine durch Kommata getrennte Liste der Aktionscodes sein (bspw. \'ev_reopen, ev_close\')',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Highlight-Farbe',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Highlight-Farbe der Nachrichtenkarte in Microsoft Teams, für Message Card muss eine gültige hexadezimale Farbe sein (bspw. FF0000) und für Adaptive Card einer der folgenden Werte sein: "default", "emphasis", "good", "attention", "warning" oder "accent".',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Titel',
	'Class:ActionRocketChatNotification' => 'Rocket.Chat-Benachrichtigung',
	'Class:ActionRocketChatNotification+' => 'Senden einer Benachrichtigung als Rocket.Chat-Nachricht in einem Kanal oder an einen Benutzer',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Überschreibt den Standard-Bot-Alias, wird vor dem Benutzernamen der Nachricht angezeigt',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Avatar-Emoji',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Überschreibt den Standard-Bot-Avatar, kann ein beliebiges Rocket.Chat-Emoji sein (bspw.. :ghost:, :white_check_mark:, ...). Beachten Sie, dass das Emoji nicht angezeigt wird, wenn ein Avatar-Bild (URL) eingestellt ist.',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Avatar-Bild',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Überschreibt den Standard-Bot-Avatar, muss eine absolute URL zu dem zu verwendenden Bild sein',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Nachricht',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Nachricht, die im Chat angezeigt wird.',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'PHP-Methode zur Vorbereitung des Payloads, der während des Webhook-Aufrufs gesendet werden sollen. Wählen Sie diese Option, wenn die Standardoptionen nicht flexibel genug sind oder wenn Ihre Nutzlaststruktur dynamisch aufgebaut werden muss.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX

WICHTIG: Wenn dies gesetzt ist, werden das Attribut \'Nachricht\' sowie alle \'Bot-Informationen\' ignoriert.',
	'Class:ActionSlackNotification' => 'Slack-Benachrichtigung',
	'Class:ActionSlackNotification+' => 'Senden einer Benachrichtigung als Slack-Nachricht in einem Kanal oder an einen Benutzer',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Löschen-Button',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Fügen Sie einen Button unter der Nachricht ein, um das Objekt in '.ITOP_APPLICATION_SHORT.' zu löschen',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'Nein',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attribute von',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Anzeige zusätzlicher Attribute unterhalb der Nachricht. Sie können entweder aus der üblichen Ansicht \'list\' oder der benutzerdefinierten Ansicht \'slack\' des Objekts stammen, das die Meldung auslöst. Beachten Sie, dass die \'slack\'-Sicht zuerst im Datenmodell definiert werden muss (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'Die übliche Listenansicht',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'Die benutzerdefinierte "slack"-Ansicht',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Bearbeiten-Button',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Fügen Sie einen Button unter der Nachricht ein, um das Objekt in '.ITOP_APPLICATION_SHORT.' zu bearbeiten',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'Nein',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Button für andere Aktionen',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Andere Aktionen (z. B. Übergänge, die im aktuellen Zustand verfügbar sind) unterhalb der Nachricht einfügen',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'Nein',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'Spezifisch',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Benutzerinformationen',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Anzeige von Benutzerinformationen (vollständiger Name) unterhalb der Nachricht',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'Nein',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Ja',
	'Class:ActionSlackNotification/Attribute:message' => 'Nachricht',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'PHP-Methode zur Vorbereitung des Payloads, der während des Webhook-Aufrufs gesendet werden sollen. Wählen Sie diese Option, wenn die Standardoptionen nicht flexibel genug sind oder wenn Ihre Nutzlaststruktur dynamisch aufgebaut werden muss.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX

WICHTIG: Wenn dies gesetzt ist, werden das Attribut \'Nachricht\' sowie alle \'zusätzlichen Elemente\' ignoriert.',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Codes der anderen Aktionen',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Geben Sie an, welche Aktionen als Buttons unter der Nachricht angezeigt werden sollen. Es sollte eine durch Kommata getrennte Liste der Aktionscodes sein (bspw. \'ev_reopen, ev_close\')',
	'Class:ActionWebhook' => 'Webhook-Aufruf (generisch)',
	'Class:ActionWebhook+' => 'Webhook-Aufruf für eine beliebige Art von Anwendung',
	'Class:ActionWebhook/Attribute:asynchronous+' => 'Ob diese Aktion im Hintergrund ausgeführt werden soll oder nicht (beachten Sie, dass die globale Einstellung für Webhook-Aktionen der "prefer_asynchronous"-Konfigurationsparameter des "combodo-webhook-action"-Moduls ist)',
	'Class:ActionWebhook/Attribute:headers' => 'Headers',
	'Class:ActionWebhook/Attribute:headers+' => 'Headers des HTTP-Requests, nur ein Header pro Zeile (bspw. \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:language' => 'Sprache',
	'Class:ActionWebhook/Attribute:language+' => 'Sprache dieser Benachrichtigung, wird meist bei der Suche nach Benachrichtigungen verwendet, kann aber auch zur Übersetzung von Attributen verwendet werden',
	'Class:ActionWebhook/Attribute:method' => 'Method',
	'Class:ActionWebhook/Attribute:method+' => 'Method des HTTP-Requests',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:path' => 'Pfad',
	'Class:ActionWebhook/Attribute:path+' => 'Zusätzlicher Pfad, der an die Verbindungs-URL angehängt wird (bspw. \'/some/specific-endpoint\')',
	'Class:ActionWebhook/Attribute:payload' => 'Payload',
	'Class:ActionWebhook/Attribute:payload+' => 'Daten, die während des Webhook-Aufrufs gesendet werden. Meistens handelt es sich dabei um einen JSON-String. Verwenden Sie dies, wenn Ihre Payload-Struktur statisch ist.

WICHTIG: Wird ignoriert, wenn \'Callback zur Vorbereitung des Payloads\' gesetzt ist',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Callback zur Vorbereitung des Payloads',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'PHP-Methode zur Vorbereitung des Payloads, der während des Webhook-Aufrufs gesendet werden sollen. Verwenden Sie diese Methode, wenn Ihre Payload-Struktur dynamisch erstellt werden muss.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX

WICHTIG: Wenn es gesetzt ist, wird das Attribut "Payload" ignoriert.',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Callback zur Verarbeitung der Antwort',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP-Methode zur Verarbeitung der Antwort auf den Webhook-Aufruf.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- - Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX
- $oResponse kann in einigen Fällen null sein (bspw. wenn der Request fehlgeschlagen ist)',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Verbindung',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Verbindungsinformationen, die zu verwenden sind, wenn der Status \'Im Einsatz\' ist',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Testverbindung',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Verbindungsinformationen, die zu verwenden sind, wenn der Status \'Wird getestet\' ist',
	'Class:ActioniTopWebhook' => 'iTop-Webhook-Aufruf',
	'Class:ActioniTopWebhook+' => 'Webhook-Aufruf an eine remote iTop-Anwendung',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Headers des HTTP-Requests, nur ein Header pro Zeile (bspw. \'Content-type: application/json\')

WICHTIG:
- \'Content-type\' sollte für iTop auf \'application/x-www-form-urlencoded\' gesetzt, auch wenn JSON gesendet wird
- Ein \'Basic authorization\'-Header wird beim Senden automatisch an den Request angehängt und enthält die Anmeldedaten der ausgewählten Verbindung',
	'Class:ActioniTopWebhook/Attribute:payload' => 'JSON-Payload',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'Der JSON-Payload muss eine JSON-Zeichenkette sein, die den Namen der Operation und die Parameter enthält; detaillierte Informationen finden Sie in der Dokumentation.',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'PHP-Methode zur Vorbereitung des Payloads, der während des Webhook-Aufrufs gesendet werden sollen. Verwenden Sie diese Methode, wenn Ihre Payload-Struktur dynamisch erstellt werden muss.

Sie können 2 Arten von Methoden verwenden:
- Vom auslösenden Objekt selbst (z.B. UserRequest), muss \'public\' sein. Beispiel: $this->XXX
- Von jeder PHP-Klasse, muss \'static\' UND \'public\' sein. Der Name muss ein voll qualifizierter Name sein. Beispiel: \SomeClass::XXX

WICHTIG: Wenn es gesetzt ist, wird das Attribut "Payload" ignoriert.',
	'Class:EventWebhook' => 'Gesendeter Webhook-Aufruf',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Finale Klasse',
	'Class:EventWebhook/Attribute:headers' => 'Headers',
	'Class:EventWebhook/Attribute:payload' => 'Payload',
	'Class:EventWebhook/Attribute:response' => 'Antwort',
	'Class:EventWebhook/Attribute:webhook_url' => 'Webhook URL',
	'Class:RemoteApplicationConnection' => 'Verbindung zu einer Remote-Anwendung',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Webhook-Benachrichtigungen',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Webhook-Benachrichtigungen, die diese Verbindung verwenden',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Umgebung',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Typ der Umgebung der Verbindung',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Entwicklung',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Test',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Produktion',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Anwendungstyp',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Art der Anwendung, für die die Verbindung bestimmt ist (verwenden Sie \'Generisch\', wenn Ihre Anwendung nicht in der Liste enthalten ist)',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationType' => 'Typ der Remote-Anwendung',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Verbindungen',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Verbindungen für diese Anwendung',
	'Class:RemoteiTopConnection' => 'Remote-iTop-Verbindung',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Passwort',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Passwort des Benutzers (auf dem Remote-iTop), der für die Authentifizierung verwendet wird',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Name des Benutzers',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Login des Benutzers (auf dem Remote-iTop), der für die Authentifizierung verwendet wird',
	'Class:RemoteiTopConnection/Attribute:version' => 'API-Version',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Version der aufzurufenden API (bspw. 1.3)',
	'Class:RemoteiTopConnectionToken' => 'Remote iTop-Verbindung über ein Token',
	'Class:RemoteiTopConnectionToken/Attribute:token+' => 'Token',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Aktionen vom Typ Webhook',
	'Dashboard:Integrations:Outgoing:Title' => 'Ausgehende Webhook-Integrationen',
	'Dashboard:Integrations:Title' => 'Integrationen mit externen Anwendungen',
	'Menu:Integrations' => 'Integrationen',
	'RemoteApplicationConnection:authinfo' => 'Authentifizierung',
	'RemoteApplicationConnection:baseinfo' => 'Allgemeine Informationen',
	'RemoteApplicationConnection:moreinfo' => 'Weitere Informationen',
	'Class:ActionWebhook/Error:InvalidJSONFormatForPayload' => 'Invalid JSON format for Payload~~',
]);
