<?php
// Copyright (C) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('FR FR', 'French', 'Français', array(
	'Core:DeletedObjectLabel' => '%1s (effacé)',
	'Core:DeletedObjectTip' => 'L\'objet a été effacé le %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Classe: %1$s, Identifiant: %2$d',
	'Core:UnknownObjectTip' => 'L\'objet n\'a pu être trouvé. Il se peut que les archives aient été purgées après son effacement.',

	'Core:UniquenessDefaultError' => 'La règle d\'unicité \'%1$s\' renvoie une erreur',

	'Core:AttributeLinkedSet' => 'Objets liés (1-n)',
	'Core:AttributeLinkedSet+' => 'Liste d\'objets d\'une classe donnée et pointant sur l\'objet courant',

	'Core:AttributeDashboard' => 'Tableau de bord',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => 'Numéro de téléphone',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Date d\'obsolescence',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'Liste d\'étiquettes',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'cliquer pour ajouter',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s de la classe %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s d\'une sous-classe)',

	'Core:AttributeCaseLog' => 'Log~~',
	'Core:AttributeCaseLog+' => '~~',

	'Core:AttributeMetaEnum' => 'Computed enum~~',
	'Core:AttributeMetaEnum+' => '~~',

	'Core:AttributeLinkedSetIndirect' => 'Objets liés (1-n)',
	'Core:AttributeLinkedSetIndirect+' => 'Liste d\'objets d\'une classe donnée et liés à l\'objet courant via une classe intermédiaire',

	'Core:AttributeInteger' => 'Nombre entier',
	'Core:AttributeInteger+' => 'Valeur numérique entière',

	'Core:AttributeDecimal' => 'Nombre décimal',
	'Core:AttributeDecimal+' => 'Valeur numérique décimale',

	'Core:AttributeBoolean' => 'Booléen',
	'Core:AttributeBoolean+' => 'Booléen',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Oui',
	'Core:AttributeBoolean/Value:no' => 'Non',

	'Core:AttributeArchiveFlag' => 'Drapeau Archive',
	'Core:AttributeArchiveFlag/Value:yes' => 'Oui',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Cet object n\'est visible que dans le mode Archive',
	'Core:AttributeArchiveFlag/Value:no' => 'Non',
	'Core:AttributeArchiveFlag/Label' => 'Archivé',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Date archivage',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Drapeau Obsolète',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Oui',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Cet objet est exclus de l\'analyse d\'impact, et n\'est pas affiché dans les résultats de recherche',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Non',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolète',
	'Core:AttributeObsolescenceFlag/Label+' => 'Calculé dynamiquement en fonction d\'autres attributs de l\'objet',
	'Core:AttributeObsolescenceDate/Label' => 'Date d\'obsolescence',
	'Core:AttributeObsolescenceDate/Label+' => 'Date approximative du jour où l\'objet est devenu obsolète',

	'Core:AttributeString' => 'Chaîne de caractères',
	'Core:AttributeString+' => 'Chaîne de caractères (limitée à une ligne)',

	'Core:AttributeClass' => 'Classe',
	'Core:AttributeClass+' => 'Classe d\'objets',

	'Core:AttributeApplicationLanguage' => 'Langue',
	'Core:AttributeApplicationLanguage+' => 'Codes langue et pays (EN US)',

	'Core:AttributeFinalClass' => 'Classe',
	'Core:AttributeFinalClass+' => 'Classe réelle de l\'objet (attribut créé automatiquement)',

	'Core:AttributePassword' => 'Mot de passe',
	'Core:AttributePassword+' => 'Mot de passe qui peut être lu en clair',

	'Core:AttributeEncryptedString' => 'Chaîne encryptée',
	'Core:AttributeEncryptedString+' => 'Chaîne encryptée avec une clé locale',
	'Core:AttributeEncryptUnknownLibrary' => 'La bibliothèque de chiffrement specifée (%1$s) est inconnue',
	'Core:AttributeEncryptFailedToDecrypt' => '** erreur de déchiffrage **',

	'Core:AttributeText' => 'Texte',
	'Core:AttributeText+' => 'Chaîne de caractères de plusieurs lignes',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'Texte formaté en HTML',

	'Core:AttributeEmailAddress' => 'Adresse électronique',
	'Core:AttributeEmailAddress+' => 'Adresse électronique (xxxx@yyy.zzz)',

	'Core:AttributeIPAddress' => 'Adresse IP',
	'Core:AttributeIPAddress+' => 'Adresse IP',

	'Core:AttributeOQL' => 'Expression OQL',
	'Core:AttributeOQL+' => 'Expression formattée en "Object Query Language"',

	'Core:AttributeEnum' => 'Enumération',
	'Core:AttributeEnum+' => 'Valeur choisie parmi un liste de chaîne de caractères',

	'Core:AttributeTemplateString' => 'Modèle de chaîne de caractères',
	'Core:AttributeTemplateString+' => 'Chaîne de caractères d\'une ligne, contenant des espaces réservés pour des données iTop',

	'Core:AttributeTemplateText' => 'Modèle de texte',
	'Core:AttributeTemplateText+' => 'Texte contenant des espaces réservés pour des données iTop',

	'Core:AttributeTemplateHTML' => 'Modèle HTML',
	'Core:AttributeTemplateHTML+' => 'HTML contenant des espaces réservés pour des données iTop',

	'Core:AttributeDateTime' => 'Date/heure',
	'Core:AttributeDateTime+' => 'Date et heure (année-mois-jour hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Format de date :<br/>
	<b>%1$s</b><br/>
	Exemple : %2$s
</p>
<p>
Opérateurs :<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
Si l\'heure n\'est pas spécifiée, cela revient à 00:00:00 (minuit)
</p>',

	'Core:AttributeDate' => 'Date',
	'Core:AttributeDate+' => 'Date (année-mois-jour)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Format de date :<br/>
	<b>%1$s</b><br/>
	Exemple : %2$s
</p>
<p>
Opérateurs :<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Délai',
	'Core:AttributeDeadline+' => 'Date/heure exprimée relativement à l\'heure courante',

	'Core:AttributeExternalKey' => 'Clé externe',
	'Core:AttributeExternalKey+' => 'Clé externe',

	'Core:AttributeHierarchicalKey' => 'Clé externe (hiérarchie)',
	'Core:AttributeHierarchicalKey+' => 'Clé externe vers le parent',

	'Core:AttributeExternalField' => 'Attribut externe',
	'Core:AttributeExternalField+' => 'Copie de la valeur d\'un attribut de l\'objet lié par une clé externe',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'URL absolue ou relative',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Contenu binaire (document)',

	'Core:AttributeOneWayPassword' => 'Mot de passe "one way"',
	'Core:AttributeOneWayPassword+' => 'Mot de passe qui peut être vérifié mais jamais lu en clair',

	'Core:AttributeTable' => 'Table',
	'Core:AttributeTable+' => 'Tableau à deux dimensions',

	'Core:AttributePropertySet' => 'Propriétés',
	'Core:AttributePropertySet+' => 'Liste de propriétés (nom et valeur) non typées',

	'Core:AttributeFriendlyName' => 'Nom usuel (convivial)',
	'Core:AttributeFriendlyName+' => 'Attribut créé automatiquement ; sa valeur est calculée d\'après d\'autres attributs',

	'Core:FriendlyName-Label' => 'Nom complet',
	'Core:FriendlyName-Description' => 'Nom complet',

	'Core:AttributeTag' => 'Taxon',
	'Core:AttributeTag+' => 'Taxon',
	
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Setup',
	'Core:Context=GUI:Console' => 'Console',
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

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChange' => 'Modification',
	'Class:CMDBChange+' => '',
	'Class:CMDBChange/Attribute:date' => 'Date',
	'Class:CMDBChange/Attribute:date+' => '',
	'Class:CMDBChange/Attribute:userinfo' => 'Autres informations',
	'Class:CMDBChange/Attribute:userinfo+' => '',
));

//
// Class: CMDBChangeOp
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOp' => 'Operation de changement',
	'Class:CMDBChangeOp+' => '',
	'Class:CMDBChangeOp/Attribute:change' => 'Modification',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'Date',
	'Class:CMDBChangeOp/Attribute:date+' => '',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Utilisateur',
	'Class:CMDBChangeOp/Attribute:userinfo+' => '',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Type d\'objet',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'Clé',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Type',
	'Class:CMDBChangeOp/Attribute:finalclass+' => 'Nom de la classe instanciable',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOpCreate' => 'Création de l\'objet',
	'Class:CMDBChangeOpCreate+' => '',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOpDelete' => 'Effacement de l\'objet',
	'Class:CMDBChangeOpDelete+' => '',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOpSetAttribute' => 'Modification de l\'objet',
	'Class:CMDBChangeOpSetAttribute+' => '',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Champ',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => '',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Modification de valeur',
	'Class:CMDBChangeOpSetAttributeScalar+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Ancienne valeur',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nouvelle valeur',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('FR FR', 'French', 'Français', array(
	'Change:ObjectCreated' => 'Elément créé',
	'Change:ObjectDeleted' => 'Elément effacé',
	'Change:ObjectModified' => 'Elément modifié',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s modifié en %2$s (ancienne valeur: %3$s)',
	'Change:AttName_SetTo' => '%1$s modifié en %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s ajouté à %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modifié, ancienne valeur: %2$s',
	'Change:AttName_Changed' => '%1$s modifié',
	'Change:AttName_EntryAdded' => '%1$s champ modifié, une nouvelle entrée a été ajoutée: %2$s',
	'Change:LinkSet:Added' => 'ajout de %1$s',
	'Change:LinkSet:Removed' => 'suppression de %1$s',
	'Change:LinkSet:Modified' => 'modification de %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Modification de données',
	'Class:CMDBChangeOpSetAttributeBlob+' => '',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Ancienne valeur',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Modification de texte',
	'Class:CMDBChangeOpSetAttributeText+' => '',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Ancienne valeur',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '',
));

//
// Class: Event
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Event' => 'Evènement',
	'Class:Event+' => '',
	'Class:Event/Attribute:message' => 'Message',
	'Class:Event/Attribute:message+' => '',
	'Class:Event/Attribute:date' => 'Date',
	'Class:Event/Attribute:date+' => '',
	'Class:Event/Attribute:userinfo' => 'Utilisateur',
	'Class:Event/Attribute:userinfo+' => '',
	'Class:Event/Attribute:finalclass' => 'Sous-classe d\'Evènement',
	'Class:Event/Attribute:finalclass+' => 'Nom de la classe instanciable',
));

//
// Class: EventNotification
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:EventNotification' => 'Notification',
	'Class:EventNotification+' => '',
	'Class:EventNotification/Attribute:trigger_id' => 'Déclencheur',
	'Class:EventNotification/Attribute:trigger_id+' => '',
	'Class:EventNotification/Attribute:action_id' => 'Action',
	'Class:EventNotification/Attribute:action_id+' => '',
	'Class:EventNotification/Attribute:object_id' => 'Objet',
	'Class:EventNotification/Attribute:object_id+' => '',
));

//
// Class: EventNotificationEmail
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:EventNotificationEmail' => 'Envoi d\'Email',
	'Class:EventNotificationEmail+' => '',
	'Class:EventNotificationEmail/Attribute:to' => 'A',
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'De',
	'Class:EventNotificationEmail/Attribute:from+' => '',
	'Class:EventNotificationEmail/Attribute:subject' => 'Sujet',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Message',
	'Class:EventNotificationEmail/Attribute:body+' => '',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Pièces jointes',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:EventIssue' => 'Erreur',
	'Class:EventIssue+' => '',
	'Class:EventIssue/Attribute:issue' => 'Erreur',
	'Class:EventIssue/Attribute:issue+' => '',
	'Class:EventIssue/Attribute:impact' => 'Impact',
	'Class:EventIssue/Attribute:impact+' => '',
	'Class:EventIssue/Attribute:page' => 'Page Web',
	'Class:EventIssue/Attribute:page+' => '',
	'Class:EventIssue/Attribute:arguments_post' => 'Arguments (POST)',
	'Class:EventIssue/Attribute:arguments_post+' => '',
	'Class:EventIssue/Attribute:arguments_get' => 'Arguments (GET)',
	'Class:EventIssue/Attribute:arguments_get+' => '',
	'Class:EventIssue/Attribute:callstack' => 'Pile d\'appel',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Données',
	'Class:EventIssue/Attribute:data+' => '',
));

//
// Class: EventWebService
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:EventWebService' => 'Appel de webservice',
	'Class:EventWebService+' => '',
	'Class:EventWebService/Attribute:verb' => 'Verbe',
	'Class:EventWebService/Attribute:verb+' => '',
	'Class:EventWebService/Attribute:result' => 'Résultat',
	'Class:EventWebService/Attribute:result+' => '',
	'Class:EventWebService/Attribute:log_info' => 'Informations',
	'Class:EventWebService/Attribute:log_info+' => '',
	'Class:EventWebService/Attribute:log_warning' => 'Avertissement',
	'Class:EventWebService/Attribute:log_warning+' => '',
	'Class:EventWebService/Attribute:log_error' => 'Erreurs',
	'Class:EventWebService/Attribute:log_error+' => '',
	'Class:EventWebService/Attribute:data' => 'Données',
	'Class:EventWebService/Attribute:data+' => '',
));

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:EventRestService' => 'Appel REST/JSON',
	'Class:EventRestService+' => 'Trace de l\'appel au service REST/JSON (rest.php)',
	'Class:EventRestService/Attribute:operation' => 'Opération',
	'Class:EventRestService/Attribute:operation+' => 'Paramètre \'opération\'',
	'Class:EventRestService/Attribute:version' => 'Version',
	'Class:EventRestService/Attribute:version+' => 'Paramètre \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Données d\'entrée',
	'Class:EventRestService/Attribute:json_input+' => 'Paramètre \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Code',
	'Class:EventRestService/Attribute:code+' => 'Code de retour',
	'Class:EventRestService/Attribute:json_output' => 'Réponse',
	'Class:EventRestService/Attribute:json_output+' => 'Réponse HTTP (structure json)',
	'Class:EventRestService/Attribute:provider' => 'Fournisseur',
	'Class:EventRestService/Attribute:provider+' => 'Classe PHP qui a pris en charge l\'opération demandée',
));

//
// Class: EventLoginUsage
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:EventLoginUsage' => 'Utilisation de l\'application',
	'Class:EventLoginUsage+' => '',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Nom de l\'utilisateur',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Email',
	'Class:EventLoginUsage/Attribute:contact_email+' => '',
));

//
// Class: Action
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Action' => 'Action',
	'Class:Action+' => 'Action spécifique',
	'Class:Action/Attribute:name' => 'Nom',
	'Class:Action/Attribute:name+' => 'Label',
	'Class:Action/Attribute:description' => 'Description',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Etat',
	'Class:Action/Attribute:status+' => '',
	'Class:Action/Attribute:status/Value:test' => 'En test',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'En production',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inactive',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Déclencheurs liés',
	'Class:Action/Attribute:trigger_list+' => '',
	'Class:Action/Attribute:finalclass' => 'Sous-classe d\'Action',
	'Class:Action/Attribute:finalclass+' => 'Nom de la classe instanciable',
));

//
// Class: ActionNotification
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ActionNotification' => 'notification',
	'Class:ActionNotification+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ActionEmail' => 'Notification par mél',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Destinataire de test',
	'Class:ActionEmail/Attribute:test_recipient+' => '',
	'Class:ActionEmail/Attribute:from' => 'De',
	'Class:ActionEmail/Attribute:from+' => '',
	'Class:ActionEmail/Attribute:reply_to' => 'Répondre à',
	'Class:ActionEmail/Attribute:reply_to+' => '',
	'Class:ActionEmail/Attribute:to' => 'A',
	'Class:ActionEmail/Attribute:to+' => '',
	'Class:ActionEmail/Attribute:cc' => 'Copie',
	'Class:ActionEmail/Attribute:cc+' => '',
	'Class:ActionEmail/Attribute:bcc' => 'Copie Cachée',
	'Class:ActionEmail/Attribute:bcc+' => '',
	'Class:ActionEmail/Attribute:subject' => 'Sujet',
	'Class:ActionEmail/Attribute:subject+' => '',
	'Class:ActionEmail/Attribute:body' => 'Message',
	'Class:ActionEmail/Attribute:body+' => '',
	'Class:ActionEmail/Attribute:importance' => 'Importance',
	'Class:ActionEmail/Attribute:importance+' => '',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Basse',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Normale',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Haute',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
));

//
// Class: Trigger
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Trigger' => 'Déclencheur',
	'Class:Trigger+' => '',
	'Class:Trigger/Attribute:description' => 'Description',
	'Class:Trigger/Attribute:description+' => '',
	'Class:Trigger/Attribute:action_list' => 'Actions déclenchées',
	'Class:Trigger/Attribute:action_list+' => '',
	'Class:Trigger/Attribute:finalclass' => 'Sous-classe de Déclencheur',
	'Class:Trigger/Attribute:finalclass+' => 'Nom de la classe instanciable',
	'Class:Trigger/Attribute:context' => 'Contexte',
	'Class:Trigger/Attribute:context+' => 'Contexte de déclenchement',
));

//
// Class: TriggerOnObject
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnObject' => 'Déclencheur sur modification de données',
	'Class:TriggerOnObject+' => '',
	'Class:TriggerOnObject/Attribute:target_class' => 'Classe cible',
	'Class:TriggerOnObject/Attribute:target_class+' => 'label',
	'Class:TriggerOnObject/Attribute:filter' => 'Filtre',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Requête de filtrage incorrecte: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'La requête de filtrage doit retourner des objets de la classe "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnPortalUpdate' => 'Déclencheur sur mise à jour depuis le portail',
	'Class:TriggerOnPortalUpdate+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnStateChange' => 'Déclencheur sur changement d\'état',
	'Class:TriggerOnStateChange+' => '',
	'Class:TriggerOnStateChange/Attribute:state' => 'Etat',
	'Class:TriggerOnStateChange/Attribute:state+' => 'label',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnStateEnter' => 'Déclencheur sur un objet entrant dans un état',
	'Class:TriggerOnStateEnter+' => '',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnStateLeave' => 'Déclencheur sur un objet quittant un état',
	'Class:TriggerOnStateLeave+' => '',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnObjectCreate' => 'Déclencheur sur la création d\'un objet',
	'Class:TriggerOnObjectCreate+' => '',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnObjectDelete' => 'Déclencheur sur la suppression d\'un objet',
	'Class:TriggerOnObjectDelete+' => '',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnObjectUpdate' => 'Déclencheur sur la modification d\'un objet',
	'Class:TriggerOnObjectUpdate+' => '',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Attributs cible',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TriggerOnThresholdReached' => 'Déclencheur sur dépassement de seuil',
	'Class:TriggerOnThresholdReached+' => 'Déclencheur sur franchissement de seuil d\'un chronomètre',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Chronomètre',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Seuil',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkTriggerAction' => 'Actions-Déclencheur',
	'Class:lnkTriggerAction+' => '',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Action',
	'Class:lnkTriggerAction/Attribute:action_id+' => '',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Nom de l\'action',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Déclencheur',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Nom du déclencheur',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Ordre',
	'Class:lnkTriggerAction/Attribute:order+' => '',
));

//
// Synchro Data Source
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SynchroDataSource/Attribute:name' => 'Nom',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Description',
	'Class:SynchroDataSource/Attribute:status' => 'Etat',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Type cible',
	'Class:SynchroDataSource/Attribute:user_id' => 'Utilisateur',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contact à notifier',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact à notifier en cas d\'erreur',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icône (hyperlien)',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlien vers une icône représentant l\'application source des données',
	'Class:SynchroDataSource/Attribute:url_application' => 'Application (hyperlien)',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Un hyperlien vers l\'application source des données. Paramètres possibles: $this->nom_de_champ$ et $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Politique de recherche',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Obsolescence après',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Un objet est considéré comme obsolète s\'il n\'apparaît pas dans les données au delà de cette durée',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Action si zéro',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => '',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Action si un',
	'Class:SynchroDataSource/Attribute:action_on_one+' => '',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Action si plusieurs',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => '',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Utilisateurs autorisés',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Quels utilisateurs sont autorisés à effacer des objets synchronisés',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Personne',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Uniquement les administrateurs',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Tous les utilisateurs autorisés',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Mise à jour',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Format: nom_de_champ:valeur; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Durée de rétention',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Si la politique est \'Mettre à jour puis effacer\', les objets obsolètes sont encore conservés pendant cette durée avant d\'être effacés',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Table de données',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Nom de la table stockant les données de cette source. Un nom par défaut est calculé automatiquement si ce champ est laissé vide.',
	'SynchroDataSource:Description' => 'Description',
	'SynchroDataSource:Reconciliation' => 'Recherche et réconciliation',
	'SynchroDataSource:Deletion' => 'Règles d\'effacement',
	'SynchroDataSource:Status' => 'Etat',
	'SynchroDataSource:Information' => 'Information',
	'SynchroDataSource:Definition' => 'Définition',
	'Core:SynchroAttributes' => 'Champs',
	'Core:SynchroStatus' => 'Etat',
	'Core:Synchro:ErrorsLabel' => 'Erreurs',
	'Core:Synchro:CreatedLabel' => 'Créations',
	'Core:Synchro:ModifiedLabel' => 'Modifications',
	'Core:Synchro:UnchangedLabel' => 'Sans changement',
	'Core:Synchro:ReconciledErrorsLabel' => 'Erreurs',
	'Core:Synchro:ReconciledLabel' => 'Trouvés',
	'Core:Synchro:ReconciledNewLabel' => 'Créations',
	'Core:SynchroReconcile:Yes' => 'Oui',
	'Core:SynchroReconcile:No' => 'Non',
	'Core:SynchroUpdate:Yes' => 'Oui',
	'Core:SynchroUpdate:No' => 'Non',
	'Core:Synchro:LastestStatus' => 'Dernier état',
	'Core:Synchro:History' => 'Historique de synchronisation',
	'Core:Synchro:NeverRun' => 'Aucun historique, la synchronisation n\'a pas encore fonctionné',
	'Core:Synchro:SynchroEndedOn_Date' => 'La dernière synchronisation s\'est terminée à: %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Synchronisation en cours (début à %1$s)',
	'Menu:DataSources' => 'Synchronisation', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignorés (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Disparus (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Existants (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nouveau (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Effacés (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoletés (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Erreurs (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Aucune action (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Sans changement (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Mis à jour (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Erreurs (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Sans changement (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Mis à jour (%1$s)',
	'Core:Synchro:label_obj_created' => 'Créations (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Erreurs (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica traités: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Si la politique de réconciliation n\'est pas la clé primaire, au moins une clé de recherche doit être spécifiée',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Pour que les objets soient effacés après avoir été obsoletés, il faut spécifier une durée de rétention',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Les objets obsolètes doivent être mis à jour, mais aucune information de mise à jour n\'est spécifiée',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'La table %1$s existe déjà dans la base de données. Veuillez utiliser un autre nom pour la table des données de cette source.',
	'Core:SynchroReplica:PublicData' => 'Données synchronisées',
	'Core:SynchroReplica:PrivateDetails' => 'Informations internes',
	'Core:SynchroReplica:BackToDataSource' => 'Retourner aux détails de la source de données: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Liste des réplica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (clé primaire)',
	'Core:SynchroAtt:attcode' => 'Champ',
	'Core:SynchroAtt:attcode+' => '',
	'Core:SynchroAtt:reconciliation' => 'Réconciliation ?',
	'Core:SynchroAtt:reconciliation+' => '',
	'Core:SynchroAtt:update' => 'Mise  jour ?',
	'Core:SynchroAtt:update+' => '',
	'Core:SynchroAtt:update_policy' => 'Politique de mise à jour',
	'Core:SynchroAtt:update_policy+' => '',
	'Core:SynchroAtt:reconciliation_attcode' => 'Clé de recherche',
	'Core:SynchroAtt:reconciliation_attcode+' => '',
	'Core:SyncDataExchangeComment' => '(Synchronisation)',
	'Core:Synchro:ListOfDataSources' => 'Sources de données:',
	'Core:Synchro:LastSynchro' => 'Dernière synchronisation:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Cet objet est synchronisé avec une source de données',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Cet objet a été <b>créé</b> par la source de données %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Cet objet <b>peut être effacé/b> par la source de données %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Vous <b>ne pouvez pas effacer</b> cet objet car il est géré par le source de données %1$s',
	'TitleSynchroExecution' => 'Exécution de la synchronisation',
	'Class:SynchroDataSource:DataTable' => 'Table contenant les données: %1$s',
	'Core:SyncDataSourceObsolete' => 'Cette source de données est obsolète. Opération annulée.',
	'Core:SyncDataSourceAccessRestriction' => 'Seuls les administrateurs et l\'utilisateur spécifié dans la source de données peuvent exécuter cette synchronisation. Opération annulée.',
	'Core:SyncTooManyMissingReplicas' => 'Tous les réplicas sont absents de l\'import. L\'import a-t-il réellement tourné. Opération annulée.',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI~~',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s erreur(s), %3$s avertissement(s).',
	'Core:SynchroReplica:TargetObject' => 'Objet Synchronisé : %1$s',
	'Class:AsyncSendEmail' => 'Envoi d\'Email Asynchrone',
	'Class:AsyncSendEmail/Attribute:to' => 'A',
	'Class:AsyncSendEmail/Attribute:subject' => 'Sujet',
	'Class:AsyncSendEmail/Attribute:body' => 'Message',
	'Class:AsyncSendEmail/Attribute:header' => 'En-tête',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Mot de passe chiffré',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Ancien mot de passe',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Champ chiffré',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Ancienne valeur',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Archive de journal',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Dernière entrée',
	'Class:SynchroDataSource' => 'Source de données',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implémentation',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Production',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Restriction',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Utiliser les champs',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Utiliser la clé primaire',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Créer',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Erreur',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Erreur',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Mettre à jour',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Créer',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Erreur',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Prendre le premier',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Politique d\'effacement',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Effacer',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorer',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Mettre à jour',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Mettre à jour puis effacer',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Liste des champs',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Seulement les administrateurs',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Tous les utilisateurs autorisés',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Personne',
	'Class:SynchroAttribute' => 'Champs de synchronisation',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Source de données',
	'Class:SynchroAttribute/Attribute:attcode' => 'Champ',
	'Class:SynchroAttribute/Attribute:update' => 'Mise à jour',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Recherche',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Politique de mise à jour',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Maître (verrouillé)',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Maître (non-verrouillé)',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Ecrire si le champ est vide',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Type',
	'Class:SynchroAttExtKey' => 'Synchro Clé Externe',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Champ',
	'Class:SynchroAttLinkSet' => 'Synchro Linkset',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Séparateur de colonnes',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Séparateur de champs',
	'Class:SynchroLog' => 'Journal de Synchro',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Source de données',
	'Class:SynchroLog/Attribute:start_date' => 'Date/heure de début',
	'Class:SynchroLog/Attribute:end_date' => 'Date/heure de fin',
	'Class:SynchroLog/Attribute:status' => 'Etat',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Terminé Ok',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Erreur',
	'Class:SynchroLog/Attribute:status/Value:running' => 'En cours',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb de réplicas vus',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb total de réplicas',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb d\'objets effacés',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb d\'erreurs lors de l\'effacement',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb d\'objets obsolètés',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb d\'erreurs lors de l\'obsolescence',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb d\'objets créés',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb d\'erreurs lors de la création',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb d\'objets mis à jour',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb d\\erreurs lors de la mise à jour',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb d\'erreurs lors de la réconciliation',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb d\'objets disparus',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb d\'objets (nouveaux) mis à jour',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb d\'objets (nouveaux) inchangés',
	'Class:SynchroLog/Attribute:last_error' => 'Dernière erreur',
	'Class:SynchroLog/Attribute:traces' => 'Traces',
	'Class:SynchroReplica' => 'Réplica de Synchronisation',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Source de données',
	'Class:SynchroReplica/Attribute:dest_id' => 'Objet destination',
	'Class:SynchroReplica/Attribute:dest_class' => 'Type de l\'objet',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Dernière détection',
	'Class:SynchroReplica/Attribute:status' => 'Etat',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modifié',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Nouveau',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphelin',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synchronisé',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Créé par la source ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Dernière erreur',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Avertissements',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Date de création',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Date de dernière modification',
	'Class:appUserPreferences' => 'Préférences utilisateur',
	'Class:appUserPreferences/Attribute:userid' => 'Utilisateur',
	'Class:appUserPreferences/Attribute:preferences' => 'Préférences',
	'Core:ExecProcess:Code1' => 'Wrong command or command finished with errors (e.g. wrong script name)~~',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)~~',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sj %2$dh %3$dmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Temps écoulé (enregistré dans "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Temps écoulé pour "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Date/heure de butée pour "%1$s" à %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Il manque le paramètre "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Valeur incorrecte pour le paramètre "query". Il n\'existe aucune entrée dans le livre des requêtes pour l\'identifiant: "%1$s"',
	'Core:BulkExport:ExportFormatPrompt' => 'Format d\'export:',
	'Core:BulkExportOf_Class' => 'Export de %1$s',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Cliquez ici pour télécharger %1$s',
	'Core:BulkExport:ExportResult' => 'Résultat de l\'export:',
	'Core:BulkExport:RetrievingData' => 'Récupération des données...',
	'Core:BulkExport:HTMLFormat' => 'Page Web (*.html)',
	'Core:BulkExport:CSVFormat' => 'Fichier CSV (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 ou plus récent (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Document PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Faites glisser les en-têtes des colonnes pour modifier leur ordre. Aperçu de %1$s lignes sur un total de %2$s lignes à exporter.',
	'Core:BulkExport:EmptyPreview' => 'Selectionnez les colonnes à exporter dans la liste ci-dessus...',
	'Core:BulkExport:ColumnsOrder' => 'Ordre des colonnes',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Colonnes de la classe %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Veuillez sélectionner au moins une colonne à exporter',
	'Core:BulkExport:CheckAll' => 'Tout cocher',
	'Core:BulkExport:UncheckAll' => 'Tout décocher',
	'Core:BulkExport:ExportCancelledByUser' => 'Export annulé par l\'utilisateur',
	'Core:BulkExport:CSVOptions' => 'Options du format CSV',
	'Core:BulkExport:CSVLocalization' => 'Traduction',
	'Core:BulkExport:PDFOptions' => 'Options du format PDF',
	'Core:BulkExport:PDFPageFormat' => 'Format de page',
	'Core:BulkExport:PDFPageSize' => 'Taille de page:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Lettre (US)',
	'Core:BulkExport:PDFPageOrientation' => 'Orientation de la page:',
	'Core:BulkExport:PageOrientation-L' => 'Paysage',
	'Core:BulkExport:PageOrientation-P' => 'Portrait',
	'Core:BulkExport:XMLFormat' => 'Fichier XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Options XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Format HTML pour Excel (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Options du format HTML pour Excel',
	'Core:BulkExport:OptionNoLocalize' => 'Exporter les Codes au lieu des Labels',
	'Core:BulkExport:OptionLinkSets' => 'Inclure les objets liés',
	'Core:BulkExport:OptionFormattedText' => 'Préserver le formatage du texte',
	'Core:BulkExport:ScopeDefinition' => 'Définition des objets à exporter',
	'Core:BulkExportLabelOQLExpression' => 'Requête OQL:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Entrée du livre des requêtes:',
	'Core:BulkExportMessageEmptyOQL' => 'Veuillez saisir une requête OQL valide.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Veuillez sélectionner une entrée dans le livre des requêtes.',
	'Core:BulkExportQueryPlaceholder' => 'Saisissez une requête OQL...',
	'Core:BulkExportCanRunNonInteractive' => 'Cliquez ici pour exécuter l\'export en mode non-interactif.',
	'Core:BulkExportLegacyExport' => 'Cliquez ici pour exécuter l\'ancienne version de l\'export.',
	'Core:BulkExport:XLSXOptions' => 'Options du format Excel',
	'Core:BulkExport:TextFormat' => 'Champs texte contenant des balises HTML',
	'Core:BulkExport:DateTimeFormat' => 'Format de date et heure',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Format par défaut (%1$s), ex. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Format spécial: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Page %1$s',
	'Core:DateTime:Placeholder_d' => 'JJ', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'J', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'AAAA', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'AA', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Format incorrect',
	'Core:Validator:Mandatory' => 'Veuillez remplir ce champ',
	'Core:Validator:MustBeInteger' => 'Ce champ ne peut contenir qu\'un nombre entier',
	'Core:Validator:MustSelectOne' => 'Veuillez choisir une valeur',
));

//
// Class: TagSetFieldData
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TagSetFieldData' => '%2$s pour la classe %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Code',
	'Class:TagSetFieldData/Attribute:code+' => 'Code interne. Doit contenir au moins 3 caractères alphanumériques',
	'Class:TagSetFieldData/Attribute:label' => 'Label',
	'Class:TagSetFieldData/Attribute:label+' => 'Label',
	'Class:TagSetFieldData/Attribute:description' => 'Description',
	'Class:TagSetFieldData/Attribute:description+' => 'Description',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Type d\'étiquette',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Type d\'objet',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Code du champ',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Impossible de supprimer une étiquette utilisée',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Les codes et noms des étiquettes doivent être unique',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Le code de l\'étiquette doit contenir entre 3 et %1$d caractères alphanumériques, et commencer par une lettre.',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'Le code de l\'étiquette un mot réservé.',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Le nom de l\'étiquette ne doit pas être vide ni contenir le caractère \'%1$s\'',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Le code de l\'étiquette ne peut pas être changé',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'La classe de l\'étiquette ne peut pas être changée',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'L\'attribut de l\'étiquette ne peut pas être changé',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Utilisation (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Pas d\'utilisation de cette étiquette',
));

//
// Class: DBProperty
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DBProperty' => 'DB property~~',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Nom',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Description',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Valeur',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Date de modification',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Commentaire',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:BackgroundTask' => 'Tâche de fond',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Nom de la classe',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Date de première exécution',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Date de dernière exécution',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Prochaine date d\'exécution',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Nombre d\'exécutions',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Durée de la dernière exécution',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Durée minimum d\'exécution',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Durée maximum d\'exécution',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Durée moyenne d\'exécution',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'En cours',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'État',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:AsyncTask' => 'Tâche asynchrone',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Date de création',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Date d\'exécution',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Date de prochaine exécution',
	'Class:AsyncTask/Attribute:planned+' => '~~',
	'Class:AsyncTask/Attribute:event_id' => 'Évènement',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Sous-classe de tâche asynchrone',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Statut',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Essais restants',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Dernier code d\'erreur',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Dernière erreur',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Dernière tentative',
	'Class:AsyncTask/Attribute:last_attempt+' => '',


));

// Additional language entries not present in English dict
Dict::Add('FR FR', 'French', 'Français', array(
 'Core:Context=REST/JSON+' => 'REST/JSON',
 'Core:Context=Synchro+' => 'Synchro',
 'Core:Context=Setup+' => 'Setup',
 'Core:Context=GUI:Console+' => 'GUI:Console',
 'Core:Context=CRON+' => 'cron',
 'Core:Context=GUI:Portal+' => 'GUI:Portal',
));
