<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//
// Class: menuNode
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:menuNode' => 'menuNode',
	'Class:menuNode+' => 'Main menu configuration elements',
	'Class:menuNode/Attribute:name' => 'Menu Name',
	'Class:menuNode/Attribute:name+' => 'Short name for this menu',
	'Class:menuNode/Attribute:label' => 'Menu Description',
	'Class:menuNode/Attribute:label+' => 'Long description for this menu',
	'Class:menuNode/Attribute:hyperlink' => 'Hyperlink',
	'Class:menuNode/Attribute:hyperlink+' => 'Hyperlink to the page',
	'Class:menuNode/Attribute:icon_path' => 'Menu Icon',
	'Class:menuNode/Attribute:icon_path+' => 'Path to the icon o the menu',
	'Class:menuNode/Attribute:template' => 'Template',
	'Class:menuNode/Attribute:template+' => 'HTML template for the view',
	'Class:menuNode/Attribute:type' => 'Type',
	'Class:menuNode/Attribute:type+' => 'Type of menu',
	'Class:menuNode/Attribute:type/Value:application' => 'application',
	'Class:menuNode/Attribute:type/Value:application+' => 'application',
	'Class:menuNode/Attribute:type/Value:user' => 'user',
	'Class:menuNode/Attribute:type/Value:user+' => 'user',
	'Class:menuNode/Attribute:type/Value:administrator' => 'administrator',
	'Class:menuNode/Attribute:type/Value:administrator+' => 'administrator',
	'Class:menuNode/Attribute:rank' => 'Display rank',
	'Class:menuNode/Attribute:rank+' => 'Sort order for displaying the menu',
	'Class:menuNode/Attribute:parent_id' => 'Parent Menu Item',
	'Class:menuNode/Attribute:parent_id+' => 'Parent Menu Item',
	'Class:menuNode/Attribute:parent_name' => 'Parent Menu Item',
	'Class:menuNode/Attribute:parent_name+' => 'Parent Menu Item',
	'Class:menuNode/Attribute:user_id' => 'Owner of the menu',
	'Class:menuNode/Attribute:user_id+' => 'User who owns this menu (for user defined menus)',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:AuditCategory' => 'AuditCategory',
	'Class:AuditCategory+' => 'A section inside the overall audit',
	'Class:AuditCategory/Attribute:name' => 'Category Name',
	'Class:AuditCategory/Attribute:name+' => 'Short name for this category',
	'Class:AuditCategory/Attribute:description' => 'Audit Category Description',
	'Class:AuditCategory/Attribute:description+' => 'Long description for this audit category',
	'Class:AuditCategory/Attribute:definition_set' => 'Definition Set',
	'Class:AuditCategory/Attribute:definition_set+' => 'SibusQL expression defining the set of objects to audit',
));

//
// Class: AuditRule
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:AuditRule' => 'AuditRule',
	'Class:AuditRule+' => 'A rule to check for a given Audit category',
	'Class:AuditRule/Attribute:name' => 'Rule Name',
	'Class:AuditRule/Attribute:name+' => 'Short name for this rule',
	'Class:AuditRule/Attribute:description' => 'Audit Rule Description',
	'Class:AuditRule/Attribute:description+' => 'Long description for this audit rule',
	'Class:AuditRule/Attribute:query' => 'Query to Run',
	'Class:AuditRule/Attribute:query+' => 'The SibusQL expression to run',
	'Class:AuditRule/Attribute:valid_flag' => 'Valid objects?',
	'Class:AuditRule/Attribute:valid_flag+' => 'True if the rule returns the valid objects, false otherwise',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'false',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'false',
	'Class:AuditRule/Attribute:category_id' => 'Category',
	'Class:AuditRule/Attribute:category_id+' => 'The category for this rule',
	'Class:AuditRule/Attribute:category_name' => 'Category',
	'Class:AuditRule/Attribute:category_name+' => 'Name of the category for this rule',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: URP_Users
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_Users' => 'user',
	'Class:URP_Users+' => 'users and credentials',
	'Class:URP_Users/Attribute:userid' => 'Contact (person)',
	'Class:URP_Users/Attribute:userid+' => 'Personal details from the business data',
	'Class:URP_Users/Attribute:last_name' => 'Last name',
	'Class:URP_Users/Attribute:last_name+' => 'Name of the corresponding contact',
	'Class:URP_Users/Attribute:first_name' => 'First name',
	'Class:URP_Users/Attribute:first_name+' => 'First name of the corresponding contact',
	'Class:URP_Users/Attribute:email' => 'Email',
	'Class:URP_Users/Attribute:email+' => 'Email of the corresponding contact',
	'Class:URP_Users/Attribute:login' => 'Login',
	'Class:URP_Users/Attribute:login+' => 'user identification string',
	'Class:URP_Users/Attribute:password' => 'Password',
	'Class:URP_Users/Attribute:password+' => 'user authentication string',
	'Class:URP_Users/Attribute:profiles' => 'Profiles',
	'Class:URP_Users/Attribute:profiles+' => 'roles, granting rights for that person',
));

//
// Class: URP_Profiles
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_Profiles' => 'profile',
	'Class:URP_Profiles+' => 'usage profiles',
	'Class:URP_Profiles/Attribute:name' => 'Name',
	'Class:URP_Profiles/Attribute:name+' => 'label',
	'Class:URP_Profiles/Attribute:description' => 'Description',
	'Class:URP_Profiles/Attribute:description+' => 'one line description',
	'Class:URP_Profiles/Attribute:users' => 'Users',
	'Class:URP_Profiles/Attribute:users+' => 'persons having this role',
));

//
// Class: URP_Dimensions
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_Dimensions' => 'dimension',
	'Class:URP_Dimensions+' => 'application dimension (defining silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Name',
	'Class:URP_Dimensions/Attribute:name+' => 'label',
	'Class:URP_Dimensions/Attribute:description' => 'Description',
	'Class:URP_Dimensions/Attribute:description+' => 'one line description',
	'Class:URP_Dimensions/Attribute:type' => 'Type',
	'Class:URP_Dimensions/Attribute:type+' => 'class name or data type (projection unit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_UserProfile' => 'User to profile',
	'Class:URP_UserProfile+' => 'user profiles',
	'Class:URP_UserProfile/Attribute:userid' => 'User',
	'Class:URP_UserProfile/Attribute:userid+' => 'user account',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'User\'s login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profile',
	'Class:URP_UserProfile/Attribute:profileid+' => 'usage profile',
	'Class:URP_UserProfile/Attribute:profile' => 'Profile',
	'Class:URP_UserProfile/Attribute:profile+' => 'Profile name',
	'Class:URP_UserProfile/Attribute:reason' => 'Reason',
	'Class:URP_UserProfile/Attribute:reason+' => 'explain why this person may have this role',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'usage profile',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profile name',
	'Class:URP_ProfileProjection/Attribute:value' => 'Value expression',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Class',
	'Class:URP_ClassProjection/Attribute:class+' => 'Target class',
	'Class:URP_ClassProjection/Attribute:value' => 'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'permissions on classes',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:class' => 'Class',
	'Class:URP_ActionGrant/Attribute:class+' => 'Target class',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permission',
	'Class:URP_ActionGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Action',
	'Class:URP_ActionGrant/Attribute:action+' => 'operations to perform on the given class',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissions on stimilus in the life cycle of the object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:class' => 'Class',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Target class',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permission',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'permissions at the attributes level',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Action grant',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'action grant',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribute',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'attribute code',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('FR FR', 'French', 'Français', array(
	'UI:WelcomeMenu' => 'Bienvenue',
	'UI:WelcomeMenu+' => 'Bienvenue dans iTop',
	'UI:WelcomeMenu:Title' => 'Bienvenue dans iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop est un portail opérationnel complet et libre pour gérer votre SI.</p>
<ul>il contient:
<li>Une base de gestion des configuration (CMDB - Configuration management database) pour documenter et gérer votre parc informatique.</li>
<li>Un module de gestion des incidents pour suivre les incidents d\'exploitation et gérer la communication à propos de ces incidents.</li>
<li>Un module de gestion des changements pour planifier et suivre les modifications de votre SI.</li>
<li>Une base des erreurs connues, pour accélérer la résolution des incidents.</li>
<li>Un module de gestion de la maintenance pour documenter les maintenances planifiées et informer les contacts appropriés.</li>
<li>Des tableaux de bord pour avoir rapidement une vue synthétique de votre SI.</li>
</ul>
<p>Tous ces modules peuvent être installés séparément, à votre rythme.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop a été conçu pour les fournisseurs de service, il permet à vos équipes IT de gérer facilement de multiples clients et organisations.
<ul>iTop fournit un riche ensemble de processus métier&nbsp;pour:
<li>Augmenter l\'efficacité de la gestion de votre SI</li> 
<li>Accroitre la performance de vos équipes d\'exploitation</li> 
<li>Améliorer la satisfaction client et fournir aux responsables des vues sur la performance interne du SI.</li>
</ul>
</p>
<p>iTop est complètement ouvert pour s\'intéger avec votre environnement informatique.</p>
<p>
<ul>Grâce à ce portail opérationnel de nouvelle génération:
<li>Gérez un environnement informatique de plus en plus complexe.</li>
<li>Mettez en place la méthodologie ITIL à votre rythme.</li>
<li>Contrôllez l\'actif le plus important de votre SI&nbsp;: la documentation.</li>
</ul>
</p>',

	'UI:WelcomeMenu:MyCalls' => 'Mes Appels Support',
	'UI:WelcomeMenu:MyIncidents' => 'Mes Incidents',
	'UI:AllOrganizations' => ' Toutes les Organizations ',
	'UI:YourSearch' => 'Votre recherche',
	'UI:LoggedAsMessage' => 'Connecté comme: %1$s',
	'UI:LoggedAsMessage+Admin' => 'Connecté comme: %1$s (Administrateur)',
	'UI:Button:Logoff' => 'Déconnexion',
	'UI:Button:GlobalSearch' => 'Rechercher',
	'UI:Button:Search' => 'Rechercher',

	'UI:Button:Query' => ' Lancer la requête ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Annuler',
	'UI:Button:Apply' => 'Appliquer',
	'UI:Button:Back' => ' << Retour ',
	'UI:Button:Next' => ' Suite >> ',
	'UI:Button:Finish' => ' Terminer ',
	'UI:Button:DoImport' => ' Lancer l\'import ! ',
	'UI:Button:Done' => ' Terminé ',
	'UI:Button:SimulateImport' => ' Simuler l\'import ',
	'UI:Button:Test' => 'Tester !',
	'UI:Button:Evaluate' => ' Exécuter ',
	'UI:Button:AddObject' => ' Ajouter... ',
	'UI:Button:BrowseObjects' => ' Naviguer... ',
	'UI:Button:Add' => ' Ajouter ',
	'UI:Button:AddToList' => ' << Ajouter ',
	'UI:Button:RemoveFromList' => ' Enlever >> ',
	'UI:Button:FilterList' => ' Filtrer... ',
	'UI:Button:Create' => ' Créer ',
	'UI:Button:Delete' => ' Supprimer ! ',
	'UI:Button:ChangePassword' => ' Changer ! ',
	
	'UI:SearchToggle' => 'Recherche',

	'UI:ClickToCreateNew' => 'Cliquez ici pour créer un nouvel objet de type %1$s',
	'UI:NoObjectToDisplay' => 'Aucun objet à afficher.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Le paramètre object_id est obligatoire quand link_attr est spécifié. Vérifiez la définition du modèle.',
	'UI:Error:MandatoryTemplateParameter_link_attr' => 'Le paramètre target_attr est obligatoire quand link_attr est spécifié. Vérifiez la définition du modèle.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Le paramètre group_by est obligatoire. Vérifiez la définition du modèle.',
	'UI:Error:InvalidGroupByFields' => 'Liste des champs "group by" incorrecte: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Erreur: style de bloc("%1$s") inconnu.',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'la définition du lien est incorrecte: la classe d\'objets à gérer: %1$s n\'est référencée par aucune clef externe de la classe %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'L\'objet: %1$s:%2$d est introuvable.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Erreur: Référence circulaire entre les dépendences entre champs, vérifiez le modèle de données.',
	'UI:Error:UploadedFileTooBig' => 'Le fichier téléchargé est trop gros. (La taille maximale autorisée est %1$s). Vérifiez la valeur de la variable upload_max_filesize dans la configuration PHP.',
	'UI:Error:UploadedFileTruncated.' => 'Le fichier téléchargé a été tronqué !',
	'UI:Error:NoTmpDir' => 'Il n\'y a aucun répertoire temporaire de défini.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Impossible d\'écrire le fichier temporaire sur disque. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Téléchargement arrêté à cause de l\'extension. (Nom du fichier original = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Le téléchargement a échoué pour une raison inconnue. (Code d\'erreur = "%1$s").',
	
	'UI:Error:1ParametersMissing' => 'Erreur: Pour effectuer cette opération il manque le paramètre suivant: %1$s.',
	'UI:Error:2ParametersMissing' => 'Erreur: Pour effectuer cette opération il manque les paramètres suivants: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => 'Erreur: Pour effectuer cette opération il manque les paramètres suivants: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => 'Erreur: Pour effectuer cette opération il manque les paramètres suivants: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Erreur: requête OQL incorrecte: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Une erreur s\'est produite en exécutant la requête: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Erreur: l\'objet a déjà été mis à jour.',
	'UI:Error:ObjectCannotBeUpdated' => 'Erreur: l\'objet ne peut pas être mis à jour.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Erreur: les objets ont déjà été supprimés !',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Vous n\'êtes pas autorisé à faire une suppression massive sur les objets de type %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Vous n\'êtes pas autorisé supprimer des objets de type %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Erreur: l\'objet a déjà été dupliqué !',
	'UI:Error:ObjectAlreadyCreated' => 'Erreur: l\'objet a déjà été créé !',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Erreur: le stimulus "%1$s" n\'est pas valide pour l\'objet %2$s dans l\'état "%3$s".',
	
	'UI:GroupBy:Count' => 'Nombre',
	'UI:GroupBy:Count+' => 'Nombre d\'éléments',
	'UI:CountOfObjects' => '%1$d objets correspondants aux critères.',
	'UI:NoObject_Class_ToDisplay' => 'Aucun objet %1$s à afficher',
	'UI:History:LastModified_On_By' => 'Dernière modification par %2$s le %1$s.',
	'UI:History:Date' => 'Date',
	'UI:History:Date+' => 'Date de modification',
	'UI:History:User' => 'Utilisateur',
	'UI:History:User+' => 'Utilisateur qui a fait la modification',
	'UI:History:Changes' => 'Changements',
	'UI:History:Changes+' => 'Changements sur cet objet',
	'UI:Loading' => 'Chargement...',
	'UI:Menu:Actions' => 'Actions',
	'UI:Menu:New' => 'Créer...',
	'UI:Menu:Add' => 'Ajouter...',
	'UI:Menu:Manage' => 'Gérer...',
	'UI:Menu:EMail' => 'Envoyer par eMail',
	'UI:Menu:CSVExport' => 'Exporter en CSV',
	'UI:Menu:Modify' => 'Modifier...',
	'UI:Menu:Delete' => 'Supprimer...',
	'UI:Menu:Manage' => 'Gérer...',
	'UI:Menu:BulkDelete' => 'Supprimer...',
	'UI:UndefinedObject' => 'non défini',
	'UI:Document:OpenInNewWindow:Download' => 'Ouvrir dans un nouvelle fenêtre: %1$s, Télécharger: %2$s',
	'UI:SelectAllToggle+' => 'Tout Sélectionner / Tout Désélectionner',
	'UI:TruncatedResults' => '%1$d objets affichés sur %2$d',
	'UI:DisplayAll' => 'Tout afficher',
	'UI:CountOfResults' => '%1$d objet(s)',
	'UI:ChangesLogTitle' => 'Liste de modifications (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Aucune modification',
	'UI:SearchFor_Class_Objects' => 'Recherche d\'objets de type %1$s ',
	'UI:OQLQueryBuilderTitle' => 'Constructeur de requêtes OQL',
	'UI:OQLQueryTab' => 'Requête OQL',
	'UI:SimpleSearchTab' => 'Recherche simple',
	'UI:Details+' => 'Détails',
	'UI:SearchValue:Any' => '* Indifférent *',
	'UI:SearchValue:Mixed' => '* Plusieurs *',
	'UI:SelectOne' => '-- choisir une valeur --',
	'UI:Login:Welcome' => 'Bienvenue dans iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Mot de passe ou identifiant incorrect.',
	'UI:Login:IdentifyYourself' => 'Merci de vous identifier',
	'UI:Login:UserNamePrompt' => 'Identifiant',
	'UI:Login:PasswordPrompt' => 'Mot de passe',
	'UI:Login:ChangeYourPassword' => 'Changer de mot de passe',
	'UI:Login:OldPasswordPrompt' => 'Ancien mot de passe',
	'UI:Login:NewPasswordPrompt' => 'Nouveau mot de passe',
	'UI:Login:RetypeNewPasswordPrompt' => 'Resaisir le nouveau mot de passe',
	'UI:Login:IncorrectOldPassword' => 'Erreur: l\'ancien mot de passe est incorrect',
	'UI:LogOffMenu' => 'Déconnexion',
	'UI:ChangePwdMenu' => 'Changer de mot de passe...',
	'UI:Login:RetypePwdDoesNotMatch' => 'Les deux saisies du nouveau mot de passe ne sont pas identiques !',
	'UI:Button:Login' => 'Entrer dans iTop',
	'UI:Login:Error:AccessRestricted' => 'L\'accès à iTop est soumis à autorisation. Merci de contacter votre administrateur iTop.',
	'UI:CSVImport:MappingSelectOne' => '-- choisir une valeur --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignorer ce champ --',
	'UI:CSVImport:NoData' => 'Aucune donnée... merci de fournir des données !',
	'UI:Title:DataPreview' => 'Aperçu des données',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Erreur: Les données semblent ne contenir qu\'une seule colonne. Avez-vous choisi le bon séparateur ?',
	'UI:CSVImport:FieldName' => 'Champ n°%1$d',
	'UI:CSVImport:DataLine1' => 'Données Ligne 1',
	'UI:CSVImport:DataLine2' => 'Données Ligne  2',
	'UI:CSVImport:idField' => 'id (Clef primaire)',
	'UI:Title:BulkImport' => 'iTop - Import massif',
	'UI:Title:BulkImport+' => 'Assistant d\'import CSV',
	'UI:CSVImport:ClassesSelectOne' => '-- choisir une valeur --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Erreur interne: "%1$s" n\'est pas une code correct car "%2$s" n\'est pas une clef externe de la classe "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objets(s) resteront inchangés.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objets(s) seront modifiés.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objets(s) seront créés.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objets(s) seront en erreur.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objets(s) n\'ont pas changé.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objets(s)ont été modifiés.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objets(s) ont été créés.',
	'UI:CSVImport:ObjectsAddErrors' => '%1$d ligne(s) contenaient des erreurs.',
	'UI:Title:CSVImportStep2' => 'Etape 2 sur 5: Options du format CSV',
	'UI:Title:CSVImportStep3' => 'Etape 3 sur 5: Correspondance des données',
	'UI:Title:CSVImportStep4' => 'Etape 4 sur 5: Simulation de l\'import',
	'UI:Title:CSVImportStep5' => 'Etape 5 sur 5: Import terminé',
	'UI:CSVImport:LinesNotImported' => 'Des lignes n\'ont pas été importées:',
	'UI:CSVImport:LinesNotImported+' => 'Les lignes suivantes n\'ont pas été importées car elles contenaient des erreurs.',
	'UI:CSVImport:SeparatorComma+' => ', (virgule)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (point-virgule)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'autre :',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (guillemet double)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (guillemet simple / apostrophe)',
	'UI:CSVImport:QualifierOther' => 'autre :',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'La première ligne est l\'en-tête (noms des colonnes)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Ignorer les %1$s premières lignes du fichier',
	'UI:CSVImport:CSVDataPreview' => 'Aperçu des données CSV',
	'UI:CSVImport:SelectFile' => 'Sélectionnez le fichier à importer:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Import depuis un fichier',
	'UI:CSVImport:Tab:CopyPaste' => 'Copier/Coller de données',
	'UI:CSVImport:Tab:Templates' => 'Modèles',
	'UI:CSVImport:PasteData' => 'Collez ici les données à importer:',
	'UI:CSVImport:PickClassForTemplate' => 'Choisissez un modèle à télécharger: ',
	'UI:CSVImport:SeparatorCharacter' => 'Caractère séparateur:',
	'UI:CSVImport:TextQualifierCharacter' => 'Entourage des champs texte',
	'UI:CSVImport:CommentsAndHeader' => 'En-tête et commentaires',
	'UI:CSVImport:SeparatorCharacter' => 'Separator character:',
	'UI:CSVImport:TextQualifierCharacter' => 'Text qualifier character',
	'UI:CSVImport:CommentsAndHeader' => 'Comments and header',
	'UI:CSVImport:SelectClass' => 'Sélectionner le type d\'objets à importer:',
	'UI:CSVImport:AdvancedMode' => 'Mode expert',
	'UI:CSVImport:AdvancedMode+' => 'En mode expert, l\'"id" (clef primaire) des objets peut être utilisé pour renommer des objets.' .
									'Cependant la colonne "id" (si elle est présente) ne peut être utilisée que comme clef de recherche et ne peut pas être combinée avec une autre clef de recherche.',
	'UI:CSVImport:SelectAClassFirst' => 'Pour configurer la correspondance, choississez d\'abord un type ci-dessus.',
	'UI:CSVImport:HeaderFields' => 'Champs',
	'UI:CSVImport:HeaderMappings' => 'Correspondance',
	'UI:CSVImport:HeaderSearch' => 'Recherche ?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Veuillez choisir le correspondance de chacun des champs.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Veuillez choisir au moins une clef de recherche.',

	'UI:UniversalSearchTitle' => 'iTop - Recherche Universelle',
	'UI:UniversalSearch:Error' => 'Erreur : %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Sélectionnez le type d\'objets à rechercher : ',

	'UI:Audit:Title' => 'iTop - Audit de la CMDB',
	'UI:Audit:InteractiveAudit' => 'Audit Interactif',
	'UI:Audit:HeaderAuditRule' => 'Règle d\'audit',
	'UI:Audit:HeaderNbObjects' => 'Nb d\'Objets',
	'UI:Audit:HeaderNbErrors' => 'Nb d\'Erreurs',
	'UI:Audit:PercentageOk' => '% Ok',

	'UI:RunQuery:Title' => 'iTop - Evaluation de requêtes OQL',
	'UI:RunQuery:QueryExamples' => 'Exemples de requêtes',
	'UI:RunQuery:HeaderPurpose' => 'Objectif',
	'UI:RunQuery:HeaderPurpose+' => 'But de la requête',
	'UI:RunQuery:HeaderOQLExpression' => 'Requête OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'La requête en OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Requête à exécuter : ',
	'UI:RunQuery:MoreInfo' => 'Plus d\'information sur le requête : ',
	'UI:RunQuery:DevelopedQuery' => 'Requête OQL décompilée : ',
	'UI:RunQuery:SerializedFilter' => 'Version sérialisée : ',
	'UI:RunQuery:Error' => 'Une erreur s\'est produite durant l\'exécution de la requête : %1$s',

	'UI:Schema:Title' => 'Modèle de données iTop',
	'UI:Schema:CategoryMenuItem' => 'Catégorie <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relations',
	'UI:Schema:AbstractClass' => 'Classe abstraite : les objets de cette classe ne peuvent pas être instanciés.',
	'UI:Schema:NonAbstractClass' => 'Classe concrète : les objets de cette classe peuvent être instanciés.',
	'UI:Schema:ClassHierarchyTitle' => 'Hiérachie des classes',
	'UI:Schema:AllClasses' => 'Toutes les classes',
	'UI:Schema:ExternalKey_To' => 'Clef externe vers %1$s',
	'UI:Schema:Columns_Description' => 'Colonnes : <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Valeur par défaut: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null autorisé',
	'UI:Schema:NullNotAllowed' => 'Null interdit',
	'UI:Schema:Attributes' => 'Attributs',
	'UI:Schema:AttributeCode' => 'Code',
	'UI:Schema:AttributeCode+' => 'Code interne de l\'attribut',
	'UI:Schema:Label' => 'Label',
	'UI:Schema:Label+' => 'Label de l\'attribut',
	'UI:Schema:Type' => 'Type',
	'UI:Schema:Type+' => 'Type de données de l\'attribut',
	'UI:Schema:Origin' => 'Origine',
	'UI:Schema:Origin+' => 'La classe de base dans laquelle l\'attribut est défini',
	'UI:Schema:Description' => 'Description',
	'UI:Schema:Description+' => 'Description de l\'attribut',
	'UI:Schema:AllowedValues' => 'Valeurs possibles',
	'UI:Schema:AllowedValues+' => 'Restrictions des valeurs possibles pour cet attribut',
	'UI:Schema:MoreInfo' => 'Plus info',
	'UI:Schema:MoreInfo+' => 'Plus d\'information à propos de la définition de ce champ dans la base de données',
	'UI:Schema:SearchCriteria' => 'Critères de recherche',
	'UI:Schema:FilterCode' => 'Code',
	'UI:Schema:FilterCode+' => 'Code de ce critère de recherche',
	'UI:Schema:FilterDescription' => 'Description',
	'UI:Schema:FilterDescription+' => 'Description de ce critère de recherche',
	'UI:Schema:AvailOperators' => 'Opérateurs',
	'UI:Schema:AvailOperators+' => 'Opérateurs possibles pour ce critère de recherche',
	'UI:Schema:ChildClasses' => 'Classes dérivées',
	'UI:Schema:ReferencingClasses' => 'Classes faisant référence',
	'UI:Schema:RelatedClasses' => 'Classes reliées',
	'UI:Schema:LifeCycle' => 'Cycle de vie',
	'UI:Schema:Triggers' => 'Déclencheurs',
	'UI:Schema:Relation_Code_Description' => 'Relation <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Sens descendant: %1$s',
	'UI:Schema:RelationUp_Description' => 'Sens montant: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: se propage sur %2$d niveau(x), requête: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: ne se propage pas (%2$d niveaux), requête: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s est référencé par la classe %2$s via le champ %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s est lié à la classe %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Classes pointant sur %1$s (liens 1:n) :',
	'UI:Schema:Links:n-n' => 'Classes liées à %1$s (liens n:n) :',
	'UI:Schema:Links:All' => 'Graphe de toutes les classes liées',
	'UI:Schema:NoLifeCyle' => 'Aucun cycle de vie n\'est défini pour cette classe.',
	'UI:Schema:LifeCycleTransitions' => 'Transitions',
	'UI:Schema:LifeCyleAttributeOptions' => 'Options des attributs',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Caché',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Lecture seule',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obligatoire',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Doit changer',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'L\'utilisateur se verra proposer de changer la valeur',
	'UI:Schema:LifeCycleEmptyList' => 'liste vide',

	'UI:LinksWidget:Autocomplete+' => 'Tapez les 3 premiers caractères...',
	'UI:Combo:SelectValue' => '--- choisissez une valeur ---',
	'UI:Label:SelectedObjects' => 'Objets sélectionnés: ',
	'UI:Label:AvailableObjects' => 'Objets disponibles: ',
	'UI:Link_Class_Attributes' => 'Attributs du type %1$s',
	'UI:SelectAllToggle+' => 'Tout sélectionner / Tout déselectionner',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Ajouter des objets de type %1$s liés à %3$s (%2$s)',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Gérer les objets de type %1$s liés à %3$s (%2$s)',
	'UI:AddLinkedObjectsOf_Class' => 'Ajouter des objets de type %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Enlever les objets sélectionnés',
	'UI:Message:EmptyList:UseAdd' => 'La liste est vide, utilisez le bouton "Ajouter..." pour ajouter des objets.',
	'UI:Message:EmptyList:UseSearchForm' => 'Utilisez le formulaire de recherche ci-dessus pour trouver les objets à ajouter.',

	'UI:Wizard:FinalStepTitle' => 'Dernière étape: confirmation',
	'UI:Title:DeletionOf_Object' => 'Suppression de %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Suppression massive de %1$d objets de type %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Vous n\'êtes pas autorisé à supprimer cet objet',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Vous n\'êtes pas autorisé à mettre à jour les champs suivants : %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Cet objet ne peut pas être supprimé car l\'utilisateur courant n\'a pas les droits nécessaires.',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Cet objet ne peut pas être supprimé, des opérations manuelles sont nécessaire avant sa suppression.',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s pour %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'supprimé automatiquement',
	'UI:Delete:AutomaticResetOf_Fields' => 'mise à jour automatique des champ(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Suppression de toutes les références vers %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Suppression de toutes les références vers les %1$d objets de type %2$s...',
	'UI:Delete:Done+' => 'Ce qui a été effectué...',
	'UI:Delete:_Name_Class_Deleted' => ' %2$s %1$s supprimé.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Suppression de %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Suppression de %1$d objets de type %2$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotAllowed' => 'Devrait être supprimé automatiquement, mais vous n\'avez pas le droit d\'effectuer cette opération.',
	'UI:Delete:MustBeDeletedManuallyButNotAllowed' => 'Doit être supprimé manuellement - mais vous n\'avez pas le droit de supprimer cet objet, contactez votre administrateur pour régler ce problème',
	'UI:Delete:WillBeDeletedAutomatically' => 'Sera supprimé automatiquement',
	'UI:Delete:MustBeDeletedManually' => 'Doit être supprimé manuellement',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Devrait être mis à jour automatiquement, mais: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Va être mis à jour automatiquement (champs impactés : %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objets ou liens font référence à %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objets ou liens font référence à certain des objets à supprimer',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'pour garantir l\'intégrité de la base de données, toutes les références doivent être supprimées.',
	'UI:Delete:Consequence+' => 'Ce qui va être effectué',
	'UI:Delete:SorryDeletionNotAllowed' => 'Désolé, vous n\'êtes pas autorisé à supprimer cette objet. Voir les explications détaillées ci-dessus.',
	'UI:Delete:PleaseDoTheManualOperations' => 'Vous devez effectuer les opération manuelles listées ci-dessus avant de pourvoir supprimer cet objet.',
	'UI:Delect:Confirm_Object' => 'Confirmez que vous voulez bien supprimer %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Confirmez que vous voulez bien supprimer les %1$d objets de type %2$s ci-dessous.',
	'UI:WelcomeToITop' => 'Bienvenue dans iTop',
	'UI:DetailsPageTitle' => 'iTop - %2$s - Détails de %1$s',
	'UI:ErrorPageTitle' => 'iTop - Erreur',
	'UI:ObjectDoesNotExist' => 'Désolé cet objet n\'existe pas (où vous n\'êtes pas autorisé à l\'afficher).',
	'UI:SearchResultsPageTitle' => 'iTop - Résultats de la recherche',
	'UI:Search:NoSearch' => 'Rien à rechercher',
	'UI:FullTextSearchTitle_Text' => 'Résultats pour "%1$s" :',
	'UI:Search:Count_ObjectsOf_Class_Found' => 'Trouvé %1$d objet(s) de type %2$s.',
	'UI:Search:NoObjectFound' => 'Aucun objet trouvé.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %2$s - Modification de %1$s',
	'UI:ModificationTitle_Class_Object' => '%1$s - Modification de <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - %2$s - Duplication de %1$s',
	'UI:CloneTitle_Class_Object' => ' %1$s - Duplication de <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Création d\'un objet de type %1$s ',
	'UI:CreationTitle_Class' => 'Création d\'un objet de type %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Sélectionnez le type de %1$s à créer :',
	'UI:Class_Object_NotUpdated' => 'Aucun changement détecté, %2$s (type : %2$s) n\'a <strong>pas</strong> été modifié.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) mise à jour.',
	'UI:BulkDeletePageTitle' => 'iTop - Suppression massive',
	'UI:BulkDeleteTitle' => 'Sélectionnez les objets à supprimer:',
	'UI:PageTitle:ObjectCreated' => 'iTop objet créé.',
	'UI:Title:Object_Of_Class_Created' => '%2$s - %1$s créé(e).',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => '%1$s pour %2$s de l\'état %3$s vers l\'état %4$s.',
	'UI:PageTitle:FatalError' => 'iTop - Erreur Fatale',
	'UI:FatalErrorMessage' => 'Erreur fatale, iTop ne peut pas continuer.',
	'UI:Error_Details' => 'Erreur: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop gestion des utilisateurs - projections des classes',
	'UI:PageTitle:ProfileProjections' => 'iTop gestion des utilisateurs - projections des profils',
	'UI:UserManagement:Class' => 'Type',
	'UI:UserManagement:Class+' => 'Type des objets',
	'UI:UserManagement:ProjectedObject' => 'Objet',
	'UI:UserManagement:ProjectedObject+' => 'L\'objet projeté',
	'UI:UserManagement:AnyObject' => '* indifférent *',
	'UI:UserManagement:User' => 'Utilisateur',
	'UI:UserManagement:User+' => 'L\'utilisateur',
	'UI:UserManagement:Profile' => 'Profil',
	'UI:UserManagement:Profile+' => 'Profil dans lequel la projection est définie',
	'UI:UserManagement:Action:Read' => 'Lecture',
	'UI:UserManagement:Action:Read+' => 'Lecture et affichage d\'un objet',
	'UI:UserManagement:Action:Modify' => 'Modification',
	'UI:UserManagement:Action:Modify+' => 'Création et modification d\'un objet',
	'UI:UserManagement:Action:Delete' => 'Suppression',
	'UI:UserManagement:Action:Delete+' => 'Suppression d\'un objet',
	'UI:UserManagement:Action:BulkRead' => 'Lecture en masse (export)',
	'UI:UserManagement:Action:BulkRead+' => 'Export de liste d\'objets',
	'UI:UserManagement:Action:BulkModify' => 'Modification en masse',
	'UI:UserManagement:Action:BulkModify+' => 'Création et modification de plusieurs objets (import CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Suppression en masse',
	'UI:UserManagement:Action:BulkDelete+' => 'Suppression de plusieurs objets',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Actions autorisées',
	'UI:UserManagement:Action' => 'Action',
	'UI:UserManagement:Action+' => 'l\'action effectuée par l\'utilisateur',
	'UI:UserManagement:TitleActions' => 'Actions',
	'UI:UserManagement:Permission' => 'Permission',
	'UI:UserManagement:Permission+' => 'Les droits de l\'utilisateur',
	'UI:UserManagement:Attributes' => 'Champs',
	'UI:UserManagement:ActionAllowed:Yes' => 'Oui',
	'UI:UserManagement:ActionAllowed:No' => 'Non',
	'UI:UserManagement:AdminProfile+' => 'Les administrateurs ont un accès total à tous les objets de la base de données.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Aucun cycle de vie n\'est défini pour ce type d\'objets.',
	'UI:UserManagement:GrantMatrix' => 'Matrice des droits',
	'UI:USerManagement:LinkBetween_User_And_Profile' => 'Lien entre %1$s et %2$s',

	'Menu:AdminTools' => 'Outils d\'admin',
	'Menu:AdminTools+' => 'Outils d\'administration',
	'Menu:AdminTools?' => 'Ces outils sont accessibles uniquement aux utilisateur possédant le profil Administrateur.',

	'UI:AuditMenu' => 'Audit',
	'UI:AuditMenu+' => 'Audit',
	
	'UI:ChangeManagementMenu' => 'Gestion du Changement',
	'UI:ChangeManagementMenu+' => 'Gestion du Changement',
	'UI:ChangeManagementMenu:Title' => 'Résumé des changements',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changements par type',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changements par état',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changements par workgroup',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changements en attente d\'assignation',
	
	'UI:ConfigurationItemsMenu'=> 'Actifs (CIs)',
	'UI:ConfigurationItemsMenu+'=> 'Tous les actifs',
	'UI:ConfigurationItemsMenu:Title' => 'Résumé des actifs (CIs)',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Serveurs par criticité',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'PCs par criticité',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Equipements réseau par criticité',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Applications par criticité',
	
	'UI:ConfigurationManagementMenu' => 'Gestion de Configuration',
	'UI:ConfigurationManagementMenu+' => 'Gestion de Configuration',
	'UI:ConfigurationManagementMenu:Title' => 'Résumé de l\'Infrastructure',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Nombre d\'éléments par type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Nombre d\'éléments par état',
	
	'UI:ContactsMenu' => 'Contacts',
	'UI:ContactsMenu+' => 'Contacts',
	'UI:ContactsMenu:Title' => 'Résumé des contacts',
	'UI-ContactsMenu-ContactsByLocation' => 'Contacts par emplacement',
	'UI-ContactsMenu-ContactsByType' => 'Contacts par type',
	'UI-ContactsMenu-ContactsByStatus' => 'Contacts par état',


	'Menu:CSVImportMenu' => 'Import CSV',
	'Menu:CSVImportMenu+' => 'Import ou mise à jour en masse',
	
	'Menu:DataModelMenu' => 'Modèle de Données',
	'Menu:DataModelMenu+' => 'Résumé du Modèle de Données',
	
	'Menu:ExportMenu' => 'Exportation',
	'Menu:ExportMenu+' => 'Exportation des résultats d\'une requête en HTML, CSV ou XML',
	
	'Menu:IncidentManagementMenu' => 'Gestion des Incidents',
	'Menu:IncidentManagementMenu+' => 'Gestion des Incidents',
	'UI:IncidentManagementMenu:Title' => 'Résumé des incidents',
	'UI-IncidentManagementMenu-IncidentsByType' => 'Incidents par type',
	'UI-IncidentManagementMenu-IncidentsByStatus' => 'Incidents par état',
	'UI-IncidentManagementMenu-IncidentsByWorkgroup' => 'Incidents par workgroup',
	'UI-IncidentManagementMenu-IncidentsNotYetAssigned' => 'Incidents en attente d\'assignation',
		
	'UI:NotificationsMenu' => 'Notifications',
	'UI:NotificationsMenu+' => 'Configuration des Notifications',
	'UI:NotificationsMenu:Title' => 'Configuration des <span class="hilite">Notifications</span>',
	'UI:NotificationsMenu:Help' => 'Aide',
	'UI:NotificationsMenu:HelpContent' => '<p>Dans iTop les notifications sont totalement configurables. Elles sont basées sur deux types d\'objets: <i>déclencheurs et actions</i>.</p>
<p><i><b>Les déclencheurs</b></i> définissent quand une notification doit être exécutée. Il y a 3 types de déclencheurs pour couvrir les 3 différentes phases de la vie d\'un objet:
<ol>
	<li>un déclencheur "OnCreate" est exécuté quand un objet d\'une classe spécifique est créé.</li>
	<li>un déclencheur "OnEnter" est exécuté avant que l\'objet n\'entre dans un état donné (en venant d\'un autre état du cycle de vie)</li>
	<li>un déclencheur "OnLeave" est exécuté  quand l\'objet quitte un état spécifié</li>
</ol>
</p>
<p>
<i><b>Les actions</b></i> définissent ce qui doit être exécuté. Pour le moment il existe un seul type d\'action: l\'envoi de mail.
Les actions de type mail définissent le modèle du message ainsi que les autres paramètres (destinataires, importance, etc.)</p>
<p>Une page spéciale: <a href="../setup/email.test.php" target="_blank">email.test.php</a> permet de tester votre configuration mail PHP.</p>
<p>Les actions doivent être associées à des déclencheurs pour pouvoir être exécutées.
Lors de l\'association à un déclencheur, on attribue à chaque action un numéro d\'ordre, qui définit la séquence des actions à exécuter.</p>',
	'UI:NotificationsMenu:Triggers' => 'Déclencheurs',
	'UI:NotificationsMenu:AvailableTriggers' => 'Déclencheurs existants',
	'UI:NotificationsMenu:OnCreate' => 'A la création d\'un objet',
	'UI:NotificationsMenu:OnStateEnter' => 'Quand un objet entre dans un état donné',
	'UI:NotificationsMenu:OnStateLeave' => 'Quand un objet quitte un état donné',
	'UI:NotificationsMenu:Actions' => 'Actions',
	'UI:NotificationsMenu:AvailableActions' => 'Actions existantes',

	
	'Menu:RunQueriesMenu' => 'Requêtes OQL',
	'Menu:RunQueriesMenu+' => 'Executer une requête OQL',
	
	'Menu:DataAdministration' => 'Administration des données',
	'Menu:DataAdministration+' => 'Administration des données',
	
	'Menu:UniversalSearchMenu' => 'Recherche Universelle',
	'Menu:UniversalSearchMenu+' => 'Rechercher n\'importe quel objet...',
	
	'Menu:ApplicationLogMenu' => 'Application Log',
	'Menu:ApplicationLogMenu+' => 'Application Log',
	'Menu:ApplicationLogMenu:Title' => 'Application Log',

	'Menu:UserManagementMenu' => 'Gestion des Utilisateurs',
	'Menu:UserManagementMenu+' => 'Gestion des Utilisateurs',

	'Menu:ProfilesMenu' => 'Profils',
	'Menu:ProfilesMenu+' => 'Profils',
	'Menu:ProfilesMenu:Title' => 'Profils',

	'Menu:UserAccountsMenu' => 'Comptes Utilisateur',
	'Menu:UserAccountsMenu+' => 'Comptes Utilisateur',
	'MenuUI:UserAccountsMenu:Title' => 'Comptes Utilisateur',	

	'UI:iTopVersion:Short' => 'iTop version %1$s',
	'UI:iTopVersion:Long' => 'iTop version %1$s-%2$s du %3$s',

));

?>
