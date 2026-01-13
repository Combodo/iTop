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
Dict::Add('FR FR', 'French', 'Français', [
	'ActionGoogleChatNotification:message' => 'Message',
	'ActionMicrosoftTeamsNotification:additionalelements' => 'Eléments additionnels à inclure',
	'ActionMicrosoftTeamsNotification:message' => 'Message de base',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format' => 'Format JSON',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format+' => 'Détermine quel format doit être utilisé pour la transmission.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format/Value:message_card' => 'Carte de message',
	'Class:ActionMicrosoftTeamsNotification/Attribute:json_format/Value:adaptive_card' => 'Carte adaptative',
	'ActionMicrosoftTeamsNotification:theme' => 'Thème',
	'ActionRocketChatNotification:additionalelements' => 'Informations du bot',
	'ActionRocketChatNotification:message' => 'Message de base',
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => 'Notification de <%2$s|%1$s> (%3$s)',
	'ActionSlackNotification:additionalelements' => 'Eléments additionnels à inclure',
	'ActionSlackNotification:message' => 'Message de base',
	'ActionWebhook:advancedparameters' => 'Paramètres avancés',
	'ActionWebhook:baseinfo' => 'Informations générales',
	'ActionWebhook:moreinfo' => 'Autres informations',
	'ActionWebhook:requestparameters' => 'Paramètres de la requête',
	'ActionWebhook:webhookconnection' => 'Informations de connexion',
	'ActioniTopWebhook:requestparameters' => 'Paramètres de la requête',
	'Class:ActionGoogleChatNotification' => 'Notification par Google Chat',
	'Class:ActionGoogleChatNotification+' => 'Envoi une notification sous forme de message dans un espace Google Chat',
	'Class:ActionGoogleChatNotification/Attribute:message' => 'Message',
	'Class:ActionGoogleChatNotification/Attribute:message+' => 'Message qui sera affiché dans le chat, seul le text brut est supporté pour le moment.',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => 'Si les options standard ne sont pas assez flexibles ou si la charge utile JSON doit être construite dynamiquement, ce champ contiendra le nom de la méthode PHP pour la générer lors de l\'appel au Webhook.

Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX

IMPORTANT : Si ce champ est renseigné, le champ \'message\' sera ignoré.',
	'Class:ActionMicrosoftTeamsNotification' => 'Notification par Microsoft Teams',
	'Class:ActionMicrosoftTeamsNotification+' => 'Envoi une notification sous forme de message dans un canal Microsoft Teams',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => 'Image en médaillon',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => 'URL de l\'image à afficher comme médaillon du message, elle doit être accessible publiquement sur Internet pour que Microsoft Teams puisse l\'afficher',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => 'Bouton supprimer',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => 'Ajoute un bouton sous le message pour supprimer l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => 'Non',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => 'Oui',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => 'Attributs de',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => 'Affiche des attributs additionels de l\'objet ayant déclenché l\'action sous le message. Ils correspondent aux attributs soit à ceux de la vue liste habituelle ou d\'une vue \'msteams\' personnalisée. Note : La vue \'msteams\' doit être définie dans le modèle de données au préalable (zlist)',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => 'la vue liste habituelle',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => 'la vue personnalisée "msteams"',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => 'Bouton modifier',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => 'Ajoute un bouton sous le message pour modifier l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => 'Non',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => 'Oui',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => 'Autres boutons d\'actions',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => 'Ajoute d\'autres actions (comme les transitions possibles dans l\'état courant) sous le message',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => 'Non',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => 'A spécifier',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => 'Oui',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => 'Message',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => 'Si les options standard ne sont pas assez flexibles ou si la charge utile JSON doit être construite dynamiquement, ce champ contiendra le nom de la méthode PHP pour la générer lors de l\'appel au Webhook.
	
Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX

IMPORTANT : Si ce champ est renseigné, les champs \'titre\', \'message\' et tous les \'éléments additionnels\' seront ignorés.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => 'Autres codes d\'actions',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => 'Spécifie les autres actions à ajouter comme boutons sous le message. Doit être liste de code d\'actions séparés par des virgules (ex : \'ev_reopen, ev_close\')',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => 'Couleur de la carte',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => 'Couleur de la carte dans Teams, doit être une couleur hexadécimale valide (ex : FF0000) pour les cartes de message, et l\'une des valeurs suivantes: "default", "emphasis", "good", "attention", "warning" ou "accent" pour les cartes adaptatives.',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => 'Titre',
	'Class:ActionRocketChatNotification' => 'Notification par Rocket.Chat',
	'Class:ActionRocketChatNotification+' => 'Envoi une notification sous forme de message dans un canal ou à un utilisateur Rocket.Chat',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => 'Alias',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => 'Remplace l\'alias par défaut du bot, apparaîtra devant le nom d\'utilisateur du message',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => 'Avatar emoji',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => 'Remplace l\'avatar par défaut du bot, peut être n\'importe quel emojis de Rocket.Chat (ex : :ghost:, :white_check_mark:, ...). Note : si une "avatar image" est renseigné, l\'emoji ne sera pas affiché.',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => 'Avatar image',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => 'Remplace l\'avatar par défaut du bot, doit être une URL absolue de l\'image à utiliser',
	'Class:ActionRocketChatNotification/Attribute:message' => 'Message',
	'Class:ActionRocketChatNotification/Attribute:message+' => 'Message qui sera affiché dans le chat',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => 'Si les options standard ne sont pas assez flexibles ou si la charge utile JSON doit être construite dynamiquement, ce champ contiendra le nom de la méthode PHP pour la générer lors de l\'appel au Webhook.

Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX

IMPORTANT : Si ce champ est renseigné, les champs \'message\' et toutes les \'informations du bot\' seront ignorés.',
	'Class:ActionSlackNotification' => 'Notification par Slack',
	'Class:ActionSlackNotification+' => 'Envoi une notification sous forme de message dans un canal ou à un utilisateur Slack',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => 'Bouton supprimer',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => 'Ajoute un bouton sous le message pour supprimer l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => 'Attributs de',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => 'Affiche des attributs additionels de l\'objet ayant déclenché l\'action sous le message. Ils correspondent aux attributs soit à ceux de la vue liste habituelle ou d\'une vue \'slack\' personnalisée. Note : La vue \'slack\' doit être définie dans le modèle de données au préalable (zlist)',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => 'la vue liste habituelle',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => 'la vue personnalisée "slack"',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => 'Bouton modifier',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => 'Ajoute un bouton sous le message pour modifier l\'objet correspondant dans '.ITOP_APPLICATION_SHORT,
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => 'Autres boutons d\'actions',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => 'Ajoute d\'autres actions (comme les transitions possibles dans l\'état courant) sous le message',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => 'A spécifier',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:include_user_info' => 'Info. utilisateur',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => 'Affiche les infos (nom complet) de l\'utilisateur courant sous le message',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => 'Non',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => 'Oui',
	'Class:ActionSlackNotification/Attribute:message' => 'Message',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => 'Si les options standard ne sont pas assez flexibles ou si la charge utile JSON doit être construite dynamiquement, ce champ contiendra le nom de la méthode PHP pour la générer lors de l\'appel au Webhook.

Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX

IMPORTANT : Si ce champ est renseigné, les champs \'message\' et tous les \'éléments additionels\' seront ignorés.',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => 'Autres codes d\'actions',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => 'Liste des actions qui seront incluses sous forme de boutons sous le message. La valeur doit être une liste de code d\'actions séparés par virgule (par exemple :  \'ev_reopen, ev_close\')',
	'Class:ActionWebhook' => 'Action par webhook (générique)',
	'Class:ActionWebhook+' => 'Appel webhook pour tout types d\'applications',
	'Class:ActionWebhook/Attribute:asynchronous+' => 'L\'action est-elle exécutée en arrière plan ? (notez que le paramètrage global pour les actions webhooks est le paramètre de conf. "prefer_asynchronous" du module "combodo-webhook-action")',
	'Class:ActionWebhook/Attribute:headers' => 'Entêtes',
	'Class:ActionWebhook/Attribute:headers+' => 'Entêtes de la requête HTTP, seulement une par ligne (ex : \'Content-type: application/json\')',
	'Class:ActionWebhook/Attribute:language' => 'Langue',
	'Class:ActionWebhook/Attribute:language+' => 'Langue de la notification, principalement utilisée pour filtrer les notifications, mais peut aussi être utilisée pour traduire des libellés d\'attributs',
	'Class:ActionWebhook/Attribute:method' => 'Méthode',
	'Class:ActionWebhook/Attribute:method+' => 'Méthode HTTP de la requête',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:path' => 'Chemin',
	'Class:ActionWebhook/Attribute:path+' => 'Chemin additionnel à concaténer à l\'URL de la connection (par exemple :  \'/some/specific-endpoint\')',
	'Class:ActionWebhook/Attribute:payload' => 'Charge utile',
	'Class:ActionWebhook/Attribute:payload+' => 'Données envoyées lors de l\'appel webhook, une chaîne JSON le plus souvent. Utiliser ce champ si la structure de la charge utile est statique / fixe.

IMPORTANT : Sera ignoré si le champ \'Callback de préparation de la charge utile\' est renseigné',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => 'Callback de préparation de la charge utile',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => 'Méthode PHP pour préparer les données de la charge utile à envoyer lors de l\'appel du webhook. Utiliser ce champ si la charge utile a une structure qui doit être construite dynamiquement.

IMPORTANT : Si renseigné, le champ \'Charge utile\' sera ignoré. Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Exemple : $this->XXX
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX',
	'Class:ActionWebhook/Attribute:process_response_callback' => 'Callback de traitement de la réponse',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'Méthode PHP pour traiter la réponse de reçue lors de l\'appel webhook.

IMPORTANT : Vous pouvez utiliser 2 types de méthodes :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), doit être publique. Example : $this->XXX
- Méthode de n\'importe quelle classe PHP, doit être statique ET publique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple : \UneClass::XXX
- $oResponse peut être null dans certains cas (ex : échec de l\'envoi de la requête)',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => 'Connexion',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => 'Informations de connexion à utiliser quand le statut est à \'en production\'',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => 'Connexion de test',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => 'Informations de connexion à utiliser quand le statut est à \'en test\'',
	'Class:ActioniTopWebhook' => 'Action par API REST '.ITOP_APPLICATION_SHORT,
	'Class:ActioniTopWebhook+' => 'Appel de webhook d\'une application iTop distante',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'Entêtes de la requête HTTP, seulement une par ligne (ex : \'Content-type: application/x-www-form-urlencoded\')

IMPORTANT :
- Le \'Content-type\' devrait être \'application/x-www-form-urlencoded\' pour iTop, quand bien même nous envoyons du JSON
- Une entête \'Basic authorization\' sera ajoutée automatiquement à la requête durant l\'envoi, contenant les identifiants de la connexion sélectionnée',
	'Class:ActioniTopWebhook/Attribute:payload' => 'Données JSON',
	'Class:ActioniTopWebhook/Attribute:payload+' => 'La charge utile JSON, doit être une chaine JSON contenant le nom de l\'opération et ses paramètres, voir la documentation pour plus d\'informations',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => 'Si la charge utile JSON doit être construite dynamiquement, ce champ contiendra le nom de la méthode PHP pour la générer lors de l\'appel au Webhook.

Deux types de méthodes peuvent être utilisés :
- Méthode de l\'objet déclenchant l\'action (ex : Demande utilisateur), dans ce cas elle doit être publique. Exemple : $this->XXX
- Méthode de n\'importe quelle classe PHP, dans ce cas doit être publique ET statique. Le nom doit être entièrement qualifié (inclure le namespace). Exemple: \UneClasse::XXX,

IMPORTANT : Si ce champ est renseigné, le champ \'Charge utile\' sera ignoré.',
	'Class:EventWebhook' => 'Webhook déclenché',
	'Class:EventWebhook/Attribute:action_finalclass' => 'Classe finale',
	'Class:EventWebhook/Attribute:headers' => 'Entêtes',
	'Class:EventWebhook/Attribute:payload' => 'Charge utile',
	'Class:EventWebhook/Attribute:response' => 'Réponse',
	'Class:EventWebhook/Attribute:webhook_url' => 'URL du webhook',
	'Class:RemoteApplicationConnection' => 'Connexion application distante',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Appels webhook',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => 'Appels utilisant cette URL de webhook',
	'Class:RemoteApplicationConnection/Attribute:auth_pwd' => 'Mot de passe',
	'Class:RemoteApplicationConnection/Attribute:auth_pwd+' => 'Mot de passe de l\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteApplicationConnection/Attribute:auth_user' => 'Nom d\'utilisateur',
	'Class:RemoteApplicationConnection/Attribute:environment' => 'Environnement',
	'Class:RemoteApplicationConnection/Attribute:environment+' => 'Type d\'environnement de la connexion',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => 'Développement',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => 'Test',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => 'Production',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => 'Application',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => 'Type d\'application de la connexion (mettre \'Générique\' si le votre ne figure pas la liste)',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationConnection/Attribute:version' => 'Version de l\'API',
	'Class:RemoteApplicationConnection/Attribute:version+' => 'Version de l\'API utilisée sur l\'itop distant (ex : 1.3)',
	'Class:RemoteApplicationType' => 'Type d\'application distante',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => 'Connexions',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => 'Connexions pour cette application',
	'Class:RemoteiTopConnection' => 'Connexion iTop distant',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => 'Mot de passe',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => 'Mot de passe de l\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteiTopConnection/Attribute:auth_user' => 'Nom d\'utilisateur',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => 'Nom d\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Class:RemoteiTopConnection/Attribute:version' => 'Version de l\'API',
	'Class:RemoteiTopConnection/Attribute:version+' => 'Version de l\'API utilisée sur l\'itop distant (ex : 1.3)',
	'Class:RemoteiTopConnectionToken' => 'Connexion iTop distant via un Jeton',
	'Class:RemoteiTopConnectionToken/Attribute:token' => 'Jeton',
	'Class:RemoteiTopConnectionToken/Attribute:token+' => 'Un jeton personnel d\'identification',

	//RemoteOauthConnection
	'Class:RemoteOauthConnection' => 'Connexion via OAuth',
	'Class:RemoteOauthConnection/Attribute:oauth_id+' => 'Oauth Connexion',
	'Class:RemoteOauthConnection/Attribute:oauth2client_id' => 'ID du Client Oauth2',
	'Class:RemoteOauthConnection/Attribute:version' => 'Version',

	'Class:v/Attribute:auth_user+' => 'Nom d\'utilisateur (sur l\'iTop distant) utilisé pour l\'authentification',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Actions de type webhook',
	'Dashboard:Integrations:Outgoing:Title' => 'Webhooks d\'intégration sortants',
	'Menu:Webhook' => 'Webhooks',
	'Dashboard:Integrations:Title' => 'Intégrations avec les applications externes',
	'Menu:Integrations' => 'Intégrations',
	'RemoteApplicationConnection:authinfo' => 'Authentification',
	'RemoteApplicationConnection:baseinfo' => 'Informations générales',
	'RemoteApplicationConnection:moreinfo' => 'Autres informations',
	'Class:ActionWebhook/Error:InvalidJSONFormatForPayload' => 'Format JSON incorrect pour la Charge utile',
]);
