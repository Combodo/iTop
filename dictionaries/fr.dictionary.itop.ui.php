<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:AuditCategory' => 'Catégorie d\'audit',
	'Class:AuditCategory+' => 'Une section de l\'audit',
	'Class:AuditCategory/Attribute:name' => 'Nom',
	'Class:AuditCategory/Attribute:name+' => 'Nom raccourci',
	'Class:AuditCategory/Attribute:description' => 'Description',
	'Class:AuditCategory/Attribute:description+' => 'Description',
	'Class:AuditCategory/Attribute:definition_set' => 'Ensemble de définition',
	'Class:AuditCategory/Attribute:definition_set+' => 'Expression OQL qui défini le périmètre d\'application de l\'audit',
	'Class:AuditCategory/Attribute:rules_list' => 'Règles d\'audit',
	'Class:AuditCategory/Attribute:rules_list+' => 'Règles d\'audit pour cette catégorie',
));

//
// Class: AuditRule
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:AuditRule' => 'Règle d\'audit',
	'Class:AuditRule+' => '',
	'Class:AuditRule/Attribute:name' => 'Nom',
	'Class:AuditRule/Attribute:name+' => '',
	'Class:AuditRule/Attribute:description' => 'Description',
	'Class:AuditRule/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Sous-classe d\'étiquette',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Classe de l\'objet',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Attribut',
	'Class:AuditRule/Attribute:query' => 'Requête',
	'Class:AuditRule/Attribute:query+' => 'Expression OQL de calcul des éléments incorrects',
	'Class:AuditRule/Attribute:valid_flag' => 'Interprétation',
	'Class:AuditRule/Attribute:valid_flag+' => 'La requête définit-elle les éléments valides ?',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Objets valides',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => '',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Objets incorrects',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => '',
	'Class:AuditRule/Attribute:category_id' => 'Catégorie',
	'Class:AuditRule/Attribute:category_id+' => '',
	'Class:AuditRule/Attribute:category_name' => 'Categorie',
	'Class:AuditRule/Attribute:category_name+' => '',
));

//
// Class: QueryOQL
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Query' => 'Requête',
	'Class:Query+' => 'Une requête définit un ensemble d\'information de manière dynamique',
	'Class:Query/Attribute:name' => 'Nom',
	'Class:Query/Attribute:name+' => 'Identification de la requête',
	'Class:Query/Attribute:description' => 'Description',
	'Class:Query/Attribute:description+' => 'Description complète (finalité, utilisations, public)',
	'Class:Query/Attribute:is_template' => 'Base pour champ OQL',
	'Class:Query/Attribute:is_template+' => 'Utilisable comme base pour les destinataires des Notifications',
	'Class:Query/Attribute:is_template/Value:yes' => 'Oui',
	'Class:Query/Attribute:is_template/Value:no' => 'Non',
	'Class:QueryOQL/Attribute:fields' => 'Champs',
	'Class:QueryOQL/Attribute:fields+' => 'Liste CSV des attributs (ou alias.attribut) à exporter',
	'Class:QueryOQL' => 'Requête OQL',
	'Class:QueryOQL+' => 'Une requête écrite dans le langage "Object Query Language"',
	'Class:QueryOQL/Attribute:oql' => 'Expression',
	'Class:QueryOQL/Attribute:oql+' => 'Expression OQL',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:User' => 'Utilisateur',
	'Class:User+' => 'Compte utilisateur',
	'Class:User/Attribute:finalclass' => 'Type de compte',
	'Class:User/Attribute:finalclass+' => 'Nom de la classe instanciable',
	'Class:User/Attribute:contactid' => 'Personne',
	'Class:User/Attribute:contactid+' => '',
	'Class:User/Attribute:org_id' => 'Organisation',
	'Class:User/Attribute:org_id+' => 'Organisation de la personne associée',
	'Class:User/Attribute:last_name' => 'Nom',
	'Class:User/Attribute:last_name+' => '',
	'Class:User/Attribute:first_name' => 'Prénom',
	'Class:User/Attribute:first_name+' => '',
	'Class:User/Attribute:email' => 'Adresse email',
	'Class:User/Attribute:email+' => '',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => '',
	'Class:User/Attribute:language' => 'Langue',
	'Class:User/Attribute:language+' => '',
	'Class:User/Attribute:language/Value:EN US' => 'Anglais',
	'Class:User/Attribute:language/Value:EN US+' => 'Anglais (Etats-unis)',
	'Class:User/Attribute:language/Value:FR FR' => 'Français',
	'Class:User/Attribute:language/Value:FR FR+' => 'Français (France)',
	'Class:User/Attribute:profile_list' => 'Profils',
	'Class:User/Attribute:profile_list+' => 'Rôles, ouvrants les droits d\'accès',
	'Class:User/Attribute:allowed_org_list' => 'Organisations permises',
	'Class:User/Attribute:allowed_org_list+' => 'L\'utilisateur a le droit de voir les données des organisations listées ici. Si aucune organisation n\'est spécifiée, alors aucune restriction ne s\'applique.',
	'Class:User/Attribute:status' => 'Etat',
	'Class:User/Attribute:status+' => 'Est-ce que ce compte utilisateur est actif, ou non?',
	'Class:User/Attribute:status/Value:enabled' => 'Actif',
	'Class:User/Attribute:status/Value:disabled' => 'Désactivé',

	'Class:User/Error:LoginMustBeUnique' => 'Le login doit être unique - "%1s" est déjà utilisé.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'L\'utilisateur doit avoir au moins un profil.',
	'Class:User/Error:ProfileNotAllowed' => 'Le profil "%1$s" ne peux pas être ajouté à son propre utilisateur, il interdit l\'accès à la console',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Impossible de changer l\'état de son propre utilisateur',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Les organisations permises doivent contenir l\'organisation de l\'utilisateur',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'Les profils existants ne permettent pas de modifier les utilisateurs',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'L\'utilisateur doit avoir au moins une organisation.',
	'Class:User/Error:OrganizationNotAllowed' => 'Organisation non autorisée.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'L\'utilisateur n\'appartient pas à vos organisations.',
	'Class:User/Error:PersonIsMandatory' => 'Le Contact est obligatoire.',
	'Class:UserInternal' => 'Utilisateur interne',
	'Class:UserInternal+' => 'Utilisateur défini dans '.ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_Profiles' => 'Profil',
	'Class:URP_Profiles+' => 'Profil utilisateur',
	'Class:URP_Profiles/Attribute:name' => 'Nom',
	'Class:URP_Profiles/Attribute:name+' => '',
	'Class:URP_Profiles/Attribute:description' => 'Description',
	'Class:URP_Profiles/Attribute:description+' => '',
	'Class:URP_Profiles/Attribute:user_list' => 'Utilisateurs',
	'Class:URP_Profiles/Attribute:user_list+' => 'Comptes utilisateur (logins) ayant ce profil',
));

//
// Class: URP_Dimensions
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_Dimensions' => 'Dimension',
	'Class:URP_Dimensions+' => 'Dimension applicative (défini des silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Nom',
	'Class:URP_Dimensions/Attribute:name+' => '',
	'Class:URP_Dimensions/Attribute:description' => 'Description',
	'Class:URP_Dimensions/Attribute:description+' => '',
	'Class:URP_Dimensions/Attribute:type' => 'Type',
	'Class:URP_Dimensions/Attribute:type+' => 'Nom de classe ou type de données (unité de projection)',
));

//
// Class: URP_UserProfile
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_UserProfile' => 'Utilisateur/Profil',
	'Class:URP_UserProfile+' => '',
	'Class:URP_UserProfile/Name' => 'Lien entre %1$s et %2$s',
	'Class:URP_UserProfile/Attribute:userid' => 'Utilisateur',
	'Class:URP_UserProfile/Attribute:userid+' => '',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => '',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => '',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => '',
	'Class:URP_UserProfile/Attribute:reason' => 'Raison',
	'Class:URP_UserProfile/Attribute:reason+' => 'Justifie le rôle affecté à cet utilisateur',
));

//
// Class: URP_UserOrg
//


Dict::Add('FR FR', 'French', 'Français', array(
	'Class:URP_UserOrg' => 'Utilisateur/Organisation',
	'Class:URP_UserOrg+' => 'Organisations permises pour l\'utilisateur',
	'Class:URP_UserOrg/Name' => 'Lien entre %1$s et %2$s',
	'Class:URP_UserOrg/Attribute:userid' => 'Utilisateur',
	'Class:URP_UserOrg/Attribute:userid+' => '',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organisation',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organisation',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '',
	'Class:URP_UserOrg/Attribute:reason' => 'Raison',
	'Class:URP_UserOrg/Attribute:reason+' => 'Justifie la permission de voir les données de cette organisation',
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
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'oui',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'oui',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'non',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'non',
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
// Class: UserDashboard
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:UserDashboard' => 'Tableau de bord utilisateur',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'Utilisateur',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Menu',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Contenu',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Expression:Unit:Short:DAY' => 'j',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('FR FR', 'French', 'Français', array(
	'BooleanLabel:yes' => 'oui',
	'BooleanLabel:no' => 'non',
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login',
	'Menu:WelcomeMenu' => 'Bienvenue',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT,// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Bienvenue',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT,// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' est un portail opérationnel complet et libre pour gérer votre SI.</p>
<ul>il contient:
<li>Une base de gestion des configurations (CMDB - Configuration management database) pour documenter et gérer votre parc informatique.</li>
<li>Un module de gestion des incidents pour suivre les incidents d\'exploitation et gérer la communication à propos de ces incidents.</li>
<li>Un module de gestion des changements pour planifier et suivre les modifications de votre SI.</li>
<li>Une base des erreurs connues, pour accélérer la résolution des incidents.</li>
<li>Un module de gestion de la maintenance pour documenter les maintenances planifiées et informer les contacts appropriés.</li>
<li>Des tableaux de bord pour avoir rapidement une vue synthétique de votre SI.</li>
</ul>
<p>Tous ces modules peuvent être installés séparément, à votre rythme.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' a été conçu pour les fournisseurs de service, il permet à vos équipes IT de gérer facilement de multiples clients et organisations.
<ul>iTop fournit un riche ensemble de processus métier&nbsp;pour:
<li>Augmenter l\'efficacité de la gestion de votre SI</li> 
<li>Accroitre la performance de vos équipes d\'exploitation</li> 
<li>Améliorer la satisfaction client et fournir aux responsables des vues sur la performance interne du SI.</li>
</ul>
</p>
<p>iTop est complètement ouvert pour s\'intégrer avec votre environnement informatique.</p>
<p>
<ul>Grâce à ce portail opérationnel de nouvelle génération:
<li>Gérez un environnement informatique de plus en plus complexe.</li>
<li>Mettez en place la méthodologie ITIL à votre rythme.</li>
<li>Contrôlez l\'actif le plus important de votre SI&nbsp;: la documentation.</li>
</ul>
</p>',
	'UI:WelcomeMenu:Text'=> '<div>Félicitations, vous avez atterri sur '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.' !</div>

<div>Cette version présente un tout nouveau design moderne et accessible pour la console de support.</div>

<div>Nous avons conservé les fonctions de base d\''.ITOP_APPLICATION.' que vous aviez apprécié et les avons modernisées pour vous les faire adorer.
Nous espérons que vous aimerez cette version autant que nous avons eu du plaisir à l\'imaginer et à la créer.</div>

<div>Configurez vos préférences '.ITOP_APPLICATION.' pour une expérience personnalisée.</div>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Requêtes en cours: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Mes Appels Support',
	'UI:WelcomeMenu:OpenIncidents' => 'Incidents en cours: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Actifs: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Mes Incidents',
	'UI:AllOrganizations' => ' Toutes les Organisations ',
	'UI:YourSearch' => 'Votre recherche',
	'UI:LoggedAsMessage' => 'Connecté(e) comme: %1$s (%2$s)',
	'UI:LoggedAsMessage+Admin' => 'Connecté(e) comme: %1$s (%2$s, Administrateur)',
	'UI:Button:Logoff' => 'Déconnexion',
	'UI:Button:GlobalSearch' => 'Rechercher',
	'UI:Button:Search' => 'Rechercher',
	'UI:Button:Clear' => ' Clear ~~',
	'UI:Button:SearchInHierarchy' => 'Rechercher dans la hiérarchie',
	'UI:Button:Query' => ' Lancer la requête ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Sauver',
	'UI:Button:SaveAnd' => 'Sauver et %1$s',
	'UI:Button:Cancel' => 'Annuler',
	'UI:Button:Close' => 'Fermer',
	'UI:Button:Apply' => 'Appliquer',
	'UI:Button:Send' => 'Envoyer',
	'UI:Button:SendAnd' => 'Envoyer et %1$s',
	'UI:Button:Back' => ' << Retour ',
	'UI:Button:Restart' => ' |<< Recommencer ',
	'UI:Button:Next' => ' Suite >> ',
	'UI:Button:Finish' => ' Terminer ',
	'UI:Button:DoImport' => ' Lancer l\'import ! ',
	'UI:Button:Done' => ' Terminé ',
	'UI:Button:SimulateImport' => ' Simuler l\'import ',
	'UI:Button:Test' => 'Tester !',
	'UI:Button:Evaluate' => ' Exécuter ',
	'UI:Button:Evaluate:Title' => ' Exécuter (Ctrl+Entrée)',
	'UI:Button:AddObject' => ' Ajouter... ',
	'UI:Button:BrowseObjects' => ' Naviguer... ',
	'UI:Button:Add' => ' Ajouter ',
	'UI:Button:AddToList' => ' << Ajouter ',
	'UI:Button:RemoveFromList' => ' Enlever >> ',
	'UI:Button:FilterList' => ' Filtrer... ',
	'UI:Button:Create' => ' Créer ',
	'UI:Button:Delete' => ' Supprimer ! ',
	'UI:Button:Rename' => ' Renommer... ',
	'UI:Button:ChangePassword' => ' Changer ! ',
	'UI:Button:ResetPassword' => ' Ràz du mot de passe ',
	'UI:Button:Insert' => 'Insérer',
	'UI:Button:More' => 'Plus',
	'UI:Button:Less' => 'Moins',
	'UI:Button:Wait' => 'Patientez pendant la mise à jour des champs',
	'UI:Treeview:CollapseAll' => 'Tout replier',
	'UI:Treeview:ExpandAll' => 'Tout déplier',
	'UI:UserPref:DoNotShowAgain' => 'Ne plus afficher ce message',
	'UI:InputFile:NoFileSelected' => 'Aucun fichier sélectionné',
	'UI:InputFile:SelectFile' => 'Sélectionner un fichier',

	'UI:SearchToggle' => 'Recherche',
	'UI:ClickToCreateNew' => 'Créer un(e) %1$s',
	'UI:SearchFor_Class' => 'Rechercher des objets de type %1$s',
	'UI:NoObjectToDisplay' => 'Aucun objet à afficher.',
	'UI:Error:SaveFailed' => 'L\'objet ne peut pas être sauvé : ',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Le paramètre object_id est obligatoire quand link_attr est spécifié. Vérifiez la définition du modèle.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Le paramètre taarget_attr est obligatoire quand link_attr est spécifié. Vérifiez la définition du modèle.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Le paramètre group_by est obligatoire. Vérifiez la définition du modèle.',
	'UI:Error:InvalidGroupByFields' => 'Liste des champs "group by" incorrecte: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Erreur: style de bloc("%1$s") inconnu.',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'la définition du lien est incorrecte: la classe d\'objets à gérer: %1$s n\'est référencée par aucune clef externe de la classe %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'L\'objet: %1$s:%2$d est introuvable.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Erreur: Référence circulaire entre les dépendences entre champs, vérifiez le modèle de données.',
	'UI:Error:UploadedFileTooBig' => 'Le fichier téléchargé est trop gros. (La taille maximale autorisée est %1$s). Pour modifier cette limite contactez votre administrateur '.ITOP_APPLICATION_SHORT.'. (Réglages upload_max_filesize et post_max_size dans la configuration PHP sur le serveur)',
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
	'UI:Error:DeleteNotAllowedOn_Class' => 'Vous n\'êtes pas autorisé à supprimer des objets de type %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'Vous n\'êtes pas autorisé à voir des objets de type %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Vous n\'êtes pas autorisé à faire une modification massive sur les objets de type %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Erreur: l\'objet a déjà été dupliqué !',
	'UI:Error:ObjectAlreadyCreated' => 'Erreur: l\'objet a déjà été créé !',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => '%2$s est actuellement dans l\'état "%3$s", l\'opération "%1$s" que vous avez demandée ne peut être appliquée.',
	'UI:Error:InvalidDashboardFile' => 'Erreur: Le fichier tableau de bord est invalide',
	'UI:Error:InvalidDashboard' => 'Erreur: Le tableau de bord est invalide',
	'UI:Error:MaintenanceMode' => 'L\'application est en maintenance',
	'UI:Error:MaintenanceTitle' => 'Maintenance',
	'UI:Error:InvalidToken' => 'Erreur: l\'opération a déjà été effectuée (CSRF token not found)',

	'UI:Error:SMTP:UnknownVendor' => 'Le provider SMTP OAuth 2.0 %1$s n\'existe pas',

	'UI:GroupBy:Count' => 'Nombre',
	'UI:GroupBy:Count+' => 'Nombre d\'éléments',
	'UI:CountOfObjects' => '%1$d objets correspondants aux critères.',
	'UI_CountOfObjectsShort' => '%1$d objets.',
	'UI:NoObject_Class_ToDisplay' => 'Aucun objet %1$s à afficher',
	'UI:History:LastModified_On_By' => 'Dernière modification par %2$s le %1$s.',
	'UI:HistoryTab' => 'Historique',
	'UI:NotificationsTab' => 'Notifications',
	'UI:History:BulkImports' => 'Historique',
	'UI:History:BulkImports+' => 'Liste des imports CSV (le dernier est en haut de la liste)',
	'UI:History:BulkImportDetails' => 'Changements résultant de l\'import CSV du %1$s (auteur: %2$s)',
	'UI:History:Date' => 'Date',
	'UI:History:Date+' => 'Date de modification',
	'UI:History:User' => 'Utilisateur',
	'UI:History:User+' => 'Utilisateur qui a fait la modification',
	'UI:History:Changes' => 'Changements',
	'UI:History:Changes+' => 'Changements sur cet objet',
	'UI:History:StatsCreations' => 'Créés',
	'UI:History:StatsCreations+' => 'Nombre d\'objets créés',
	'UI:History:StatsModifs' => 'Modifiés',
	'UI:History:StatsModifs+' => 'Nombre d\'objets modifiés',
	'UI:History:StatsDeletes' => 'Effacés',
	'UI:History:StatsDeletes+' => 'Nombre d\'objets effacés',
	'UI:Loading' => 'Chargement...',
	'UI:Menu:Actions' => 'Actions',
	'UI:Menu:OtherActions' => 'Autres Actions',
	'UI:Menu:Transitions' => 'Transitions~~',
	'UI:Menu:OtherTransitions' => 'Other Transitions~~',
	'UI:Menu:New' => 'Créer...',
	'UI:Menu:Add' => 'Ajouter...',
	'UI:Menu:Manage' => 'Gérer...',
	'UI:Menu:EMail' => 'Envoyer par eMail',
	'UI:Menu:CSVExport' => 'Exporter en CSV...',
	'UI:Menu:Modify' => 'Modifier...',
	'UI:Menu:Delete' => 'Supprimer...',
	'UI:Menu:BulkDelete' => 'Supprimer...',
	'UI:UndefinedObject' => 'non défini',
	'UI:Document:OpenInNewWindow:Download' => 'Ouvrir dans un nouvelle fenêtre: %1$s, Télécharger: %2$s',
	'UI:SplitDateTime-Date' => 'date',
	'UI:SplitDateTime-Time' => 'heure',
	'UI:TruncatedResults' => '%1$d objets affichés sur %2$d',
	'UI:DisplayAll' => 'Tout afficher',
	'UI:CollapseList' => 'Refermer',
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
	'UI:SearchValue:NbSelected' => '# sélectionné(e)s',
	'UI:SearchValue:CheckAll' => 'Cocher',
	'UI:SearchValue:UncheckAll' => 'Décocher',
	'UI:SelectOne' => '-- choisir une valeur --',
	'UI:Login:Welcome' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Mot de passe ou identifiant incorrect.',
	'UI:Login:IdentifyYourself' => 'Merci de vous identifier',
	'UI:Login:UserNamePrompt' => 'Identifiant',
	'UI:Login:PasswordPrompt' => 'Mot de passe',
	'UI:Login:ForgotPwd' => 'Mot de passe oublié ?',
	'UI:Login:ForgotPwdForm' => 'Mot de passe oublié',
	'UI:Login:ForgotPwdForm+' => 'Vous pouvez demander à saisir un nouveau mot de passe. Vous allez recevoir un email et vous pourrez suivre les instructions.',
	'UI:Login:ResetPassword' => 'Envoyer le message',
	'UI:Login:ResetPwdFailed' => 'Impossible de vous faire parvenir le message: %1$s',
	'UI:Login:SeparatorOr' => 'Ou',

	'UI:ResetPwd-Error-WrongLogin' => 'le compte \'%1$s\' est inconnu.',
	'UI:ResetPwd-Error-NotPossible' => 'les comptes "externes" ne permettent pas la saisie d\'un mot de passe dans '.ITOP_APPLICATION_SHORT.'.',
	'UI:ResetPwd-Error-FixedPwd' => 'ce mode de saisie du mot de passe n\'est pas autorisé pour ce compte.',
	'UI:ResetPwd-Error-NoContact' => 'le comte n\'est pas associé à une Personne.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'il manque un attribut de type "email" sur la Personne associée à ce compte. Veuillez contacter l\'administrateur de l\'application.',
	'UI:ResetPwd-Error-NoEmail' => 'il manque une adresse email sur la Personne associée à ce compte. Veuillez contacter l\'administrateur de l\'application.',
	'UI:ResetPwd-Error-Send' => 'erreur technique lors de l\'envoi de l\'email. Veuillez contacter l\'administrateur de l\'application.',
	'UI:ResetPwd-EmailSent' => 'Veuillez vérifier votre boîte de réception. Ensuite, suivez les instructions données dans l\'email. Si vous ne recevez pas d\'email, merci de vérifier le login saisi',
	'UI:ResetPwd-EmailSubject' => 'Changer votre mot de passe '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody' => '<body><p>Vous avez demandé à changer votre mot de passe '.ITOP_APPLICATION_SHORT.' sans connaître le mot de passe précédent.</p><p>Veuillez suivre le lien suivant (usage unique) afin de pouvoir <a href="%1$s">saisir un nouveau mot de passe</a></p>.',

	'UI:ResetPwd-Title' => 'Nouveau mot de passe',
	'UI:ResetPwd-Error-InvalidToken' => 'Désolé, le mot de passe a déjà été modifié avec le lien que vous avez suivi, ou bien vous avez reçu plusieurs emails. Dans ce cas, veillez à utiliser le tout dernier lien reçu.',
	'UI:ResetPwd-Error-EnterPassword' => 'Veuillez saisir le nouveau mot de passe pour \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Le mot de passe a bien été changé.',
	'UI:ResetPwd-Login' => 'Cliquez ici pour vous connecter...',

	'UI:Login:About'                               => '~~',
	'UI:Login:ChangeYourPassword'                  => 'Changer de mot de passe',
	'UI:Login:OldPasswordPrompt'                   => 'Ancien mot de passe',
	'UI:Login:NewPasswordPrompt'                   => 'Nouveau mot de passe',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Resaisir le nouveau mot de passe',
	'UI:Login:IncorrectOldPassword'                => 'Erreur: l\'ancien mot de passe est incorrect',
	'UI:LogOffMenu'                                => 'Déconnexion',
	'UI:LogOff:ThankYou'                           => 'Merci d\'avoir utilisé '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Cliquez ici pour vous reconnecter...',
	'UI:ChangePwdMenu'                             => 'Changer de mot de passe...',
	'UI:Login:PasswordChanged'                     => 'Mot de passe mis à jour !',
	'UI:AccessRO-All'                              => ITOP_APPLICATION_SHORT.' est en lecture seule',
	'UI:AccessRO-Users'                            => ITOP_APPLICATION_SHORT.' est en lecture seule pour les utilisateurs finaux',
	'UI:ApplicationEnvironment'                    => 'Environnement applicatif: %1$s',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Les deux saisies du nouveau mot de passe ne sont pas identiques !',
	'UI:Button:Login'                              => 'Entrer dans '.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted'              => 'L\'accès à cette page '.ITOP_APPLICATION_SHORT.' est soumis à autorisation. Merci de contacter votre administrateur '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin'                   => 'Accès restreint aux utilisateurs possédant le profil Administrateur.',
	'UI:Login:Error:WrongOrganizationName'         => 'Organisation inconnue',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Email partagé par plusieurs contacts',
	'UI:Login:Error:NoValidProfiles'               => 'Pas de profil valide',
	'UI:CSVImport:MappingSelectOne'                => '-- choisir une valeur --',
	'UI:CSVImport:MappingNotApplicable'            => '-- ignorer ce champ --',
	'UI:CSVImport:NoData'                          => 'Aucune donnée... merci de fournir des données !',
	'UI:Title:DataPreview'                         => 'Aperçu des données',
	'UI:CSVImport:ErrorOnlyOneColumn'              => 'Erreur: Les données semblent ne contenir qu\'une seule colonne. Avez-vous choisi le bon séparateur ?',
	'UI:CSVImport:FieldName'                       => 'Champ n°%1$d',
	'UI:CSVImport:DataLine1'                       => 'Données Ligne 1',
	'UI:CSVImport:DataLine2'                       => 'Données Ligne  2',
	'UI:CSVImport:idField'                         => 'id (Clef primaire)',
	'UI:Title:BulkImport'                          => ITOP_APPLICATION_SHORT.' - Import massif',
	'UI:Title:BulkImport+'                         => 'Assistant d\'import CSV',
	'UI:Title:BulkSynchro_nbItem_ofClass_class'    => 'Synchronisation de %1$d éléments de type %2$s',
	'UI:CSVImport:ClassesSelectOne'                => '-- choisir une valeur --',
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Erreur interne: "%1$s" n\'est pas une code correct car "%2$s" n\'est pas une clef externe de la classe "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objets(s) resteront inchangés.',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objets(s) seront modifiés.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objets(s) seront créés.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objets(s) seront en erreur.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objets(s) n\'ont pas changé.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objets(s)ont été modifiés.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objets(s) ont été créés.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d ligne(s) contenaient des erreurs.',
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
	'UI:CSVImport:SeparatorCharacter' => 'Séparateur:',
	'UI:CSVImport:TextQualifierCharacter' => 'Délimiteur de texte',
	'UI:CSVImport:CommentsAndHeader' => 'Commentaires et en-tête',
	'UI:CSVImport:SelectClass' => 'Sélectionner le type d\'objets à importer:',
	'UI:CSVImport:AdvancedMode' => 'Mode expert',
	'UI:CSVImport:AdvancedMode+' => 'En mode expert, l\'"id" (clef primaire) des objets peut être utilisé pour renommer des objets.Cependant la colonne "id" (si elle est présente) ne peut être utilisée que comme clef de recherche et ne peut pas être combinée avec une autre clef de recherche.',
	'UI:CSVImport:SelectAClassFirst' => 'Pour configurer la correspondance, choississez d\'abord un type ci-dessus.',
	'UI:CSVImport:HeaderFields' => 'Champs',
	'UI:CSVImport:HeaderMappings' => 'Correspondance',
	'UI:CSVImport:HeaderSearch' => 'Recherche ?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Veuillez choisir la correspondance pour chacun des champs.',
	'UI:CSVImport:AlertMultipleMapping' => 'Veuillez vous assurer que chaque champ cible est sélectionné une seule fois.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Veuillez choisir au moins une clef de recherche.',
	'UI:CSVImport:Encoding' => 'Encodage des caractères',
	'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Recherche Universelle',
	'UI:UniversalSearch:Error' => 'Erreur : %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Sélectionnez le type d\'objets à rechercher : ',

	'UI:CSVReport-Value-Modified' => 'Modifié',
	'UI:CSVReport-Value-SetIssue' => 'Modification impossible - cause : %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Ne peut pas prendre la valeur \'%1$s\' - cause : %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Pas de correspondance',
	'UI:CSVReport-Value-Missing' => 'Absence de valeur obligatoire',
	'UI:CSVReport-Value-Ambiguous' => 'Ambigüité: %1$d objets trouvés',
	'UI:CSVReport-Row-Unchanged' => 'inchangé',
	'UI:CSVReport-Row-Created' => 'créé',
	'UI:CSVReport-Row-Updated' => '%1$d colonnes modifiées',
	'UI:CSVReport-Row-Disappeared' => 'disparu, %1$d colonnes modifiées',
	'UI:CSVReport-Row-Issue' => 'Erreur: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Valeur obligatoire',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objet non trouvé',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Plusieurs objets trouvés (%1$d)',
	'UI:CSVReport-Value-Issue-Readonly' => 'L\'attribut \'%1$s\' est en lecture seule (valeur courante: %2$s, valeur proposée: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Echec de traitement de la valeur: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Valeur incorrecte pour \'%1$s\': pas de correspondance, veuillez vérifier la syntaxe',
	'UI:CSVReport-Value-Issue-Unknown' => 'Valeur incorrecte pour \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Incohérence entre attributs: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Des attributs ont des valeurs incorrectes',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Ne peut pas être créé car il manque des clés externes : %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Format de date incorrect',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Echec de réconciliation',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Réconciliation ambigüe',
	'UI:CSVReport-Row-Issue-Internal' => 'Erreur interne: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Non modifié',
	'UI:CSVReport-Icon-Modified' => 'Modifié',
	'UI:CSVReport-Icon-Missing' => 'A disparu',
	'UI:CSVReport-Object-MissingToUpdate' => 'Objet disparu: sera modifié',
	'UI:CSVReport-Object-MissingUpdated' => 'Objet disparu: modifié',
	'UI:CSVReport-Icon-Created' => 'Créé',
	'UI:CSVReport-Object-ToCreate' => 'L\'objet sera créé',
	'UI:CSVReport-Object-Created' => 'Objet créé',
	'UI:CSVReport-Icon-Error' => 'Erreur',
	'UI:CSVReport-Object-Error' => 'Erreur: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'Ambigüité: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% des lignes chargées sont en erreur et seront ignorées.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% des lignes chargées vont engendrer un nouvel objet.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% des lignes chargées vont modifier un objet.',

	'UI:CSVExport:AdvancedMode' => 'Mode expert',
	'UI:CSVExport:AdvancedMode+' => 'Dans le mode expert, des colonnes supplémentaires apparaissent: l\'identifiant de l\'objet, la valeur des clés externes et leurs attributs de reconciliation.',
	'UI:CSVExport:LostChars' => 'Problème d\'encodage',
	'UI:CSVExport:LostChars+' => 'Le fichier téléchargé sera encodé en %1$s. iTop a détecté des caractères incompatible avec ce format. Ces caractères seront soit remplacés par des caractères de substitution (par exemple: \'é\' transformé en \'e\'), soit perdus. Vous pouvez utiliser le copier/coller depuis votre navigateur web, ou bien contacter votre administrateur pour que l\'encodage corresponde mieux à votre besoin (Cf. paramètre \'csv_file_default_charset\').',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - Audit de la CMDB',
	'UI:Audit:InteractiveAudit' => 'Audit Interactif',
	'UI:Audit:HeaderAuditRule' => 'Règle d\'audit',
	'UI:Audit:HeaderNbObjects' => 'Nb d\'Objets',
	'UI:Audit:HeaderNbErrors' => 'Nb d\'Erreurs',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:OqlError' => 'OQL Error~~',
	'UI:Audit:Error:ValueNA' => 'n/a~~',
	'UI:Audit:ErrorIn_Rule' => 'Error in Rule~~',
	'UI:Audit:ErrorIn_Rule_Reason' => 'Erreur OQL dans la règle %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category' => 'Error in Category~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'Erreur OQL dans la catégorie %1$s: %2$s.',
	'UI:Audit:AuditErrors' => 'Audit Errors~~',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objects audited~~',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objects in errors~~',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objects validated~~',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s errors ouf of %2$s - %3$s%%~~',


	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - Evaluation de requêtes OQL',
	'UI:RunQuery:QueryExamples' => 'Exemples de requêtes',
	'UI:RunQuery:QueryResults' => 'Résultats de la requête',
	'UI:RunQuery:HeaderPurpose' => 'Objectif',
	'UI:RunQuery:HeaderPurpose+' => 'But de la requête',
	'UI:RunQuery:HeaderOQLExpression' => 'Requête OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'La requête en OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Requête à exécuter : ',
	'UI:RunQuery:QueryArguments' => 'Arguments de la requête',
	'UI:RunQuery:MoreInfo' => 'Plus d\'information sur la requête : ',
	'UI:RunQuery:DevelopedQuery' => 'Requête OQL décompilée : ',
	'UI:RunQuery:SerializedFilter' => 'Version sérialisée : ',
	'UI:RunQuery:DevelopedOQL' => 'OQL développé',
	'UI:RunQuery:DevelopedOQLCount' => 'OQL développé pour le compte',
	'UI:RunQuery:ResultSQLCount' => 'SQL généré pour le compte',
	'UI:RunQuery:ResultSQL' => 'SQL généré',
	'UI:RunQuery:Error' => 'Une erreur s\'est produite durant l\'exécution de la requête',
	'UI:Query:UrlForExcel' => 'Lien à copier-coller dans Excel, pour déclarer une source de données à partir du web',
	'UI:Query:UrlV1' => 'La liste des champs à exporter n\'a pas été spécifiée. La page <em>export-V2.php</em> ne peut pas fonctionner sans cette information. Par conséquent, le lien fourni ci-dessous pointe sur l\'ancienne page: <em>export.php</em>. Cette ancienne version de l\'export présente la limitation suivante : la liste des champs exportés varie en fonction du format de l\'export et du modèle de données. <br/>Si vous devez garantir la stabilité du format de l\'export (liste des colonnes) sur le long terme, alors vous devrez renseigner l\'attribut "Champs" et utiliser la page <em>export-V2.php</em>.',
	'UI:Schema:Title' => 'Modèle de données '.ITOP_APPLICATION_SHORT,
	'UI:Schema:TitleForClass' => 'Modèle de données de %1$s~~',
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
	'UI:Schema:LifeCycleTransitions' => 'États et Transitions',
	'UI:Schema:LifeCyleAttributeOptions' => 'Options des attributs',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Caché',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Lecture seule',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obligatoire',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Doit changer',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'L\'utilisateur se verra proposer de changer la valeur',
	'UI:Schema:LifeCycleEmptyList' => 'liste vide',
	'UI:Schema:ClassFilter' => 'Classe :',
	'UI:Schema:DisplayLabel' => 'Affichage :',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label et code',
	'UI:Schema:DisplaySelector/Label' => 'Label',
	'UI:Schema:DisplaySelector/Code' => 'Code',
	'UI:Schema:Attribute/Filter' => 'Filtre',
	'UI:Schema:DefaultNullValue' => 'Valeur null par défaut : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => 'Tapez les 3 premiers caractères...',
	'UI:Edit:SearchQuery' => 'Sélectionner une requête prédéfinie',
	'UI:Edit:TestQuery' => 'Tester la requête',
	'UI:Combo:SelectValue' => '--- choisissez une valeur ---',
	'UI:Label:SelectedObjects' => 'Objets sélectionnés: ',
	'UI:Label:AvailableObjects' => 'Objets disponibles: ',
	'UI:Link_Class_Attributes' => 'Attributs du type %1$s',
	'UI:SelectAllToggle+' => 'Tout sélectionner / Tout déselectionner',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Ajouter des objets de type %1$s liés à %3$s (%2$s)',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Ajouter des objets de type %1$s à lier à cet objet de type %2$s',
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
	'UI:Error:ActionNotAllowed' => 'Vous n\'êtes pas autorisé à effectuer cette action',
	'UI:Error:NotEnoughRightsToDelete' => 'Cet objet ne peut pas être supprimé car l\'utilisateur courant n\'a pas les droits nécessaires.',
	'UI:Error:CannotDeleteBecause' => 'Cet objet ne peut pas être effacé. Raison: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Cet objet ne peut pas être supprimé, des opérations manuelles sont nécessaire avant sa suppression.',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Des opération manuelles sont nécessaires avant de pouvoir effacer cet objet',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s pour %2$s',
	'UI:Delete:Deleted' => 'supprimé',
	'UI:Delete:AutomaticallyDeleted' => 'supprimé automatiquement',
	'UI:Delete:AutomaticResetOf_Fields' => 'mise à jour automatique des champ(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Suppression de toutes les références vers %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Suppression de toutes les références vers les %1$d objets de type %2$s...',
	'UI:Delete:Done+' => 'Ce qui a été effectué...',
	'UI:Delete:_Name_Class_Deleted' => ' %2$s %1$s supprimé.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Suppression de %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Suppression de %1$d objets de type %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Ne peut pas être supprimé: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Devrait être supprimé automatiquement, mais cela n\'est pas possible: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Doit être supprimé manuellement, mais cela n\'est pas possible: %1$s',
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
	'UI:WelcomeToITop' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT,
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %2$s - Détails de %1$s',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Erreur',
	'UI:ObjectDoesNotExist' => 'Désolé cet objet n\'existe pas (ou vous n\'êtes pas autorisé à l\'afficher).',
	'UI:ObjectArchived' => 'Cet objet a été archivé. Veuillez activer le mode Archive, ou contactez votre administrateur.',
	'Tag:Archived' => 'Archivé',
	'Tag:Archived+' => 'Accessible seulement dans le mode Archive',
	'Tag:Obsolete' => 'Obsolète',
	'Tag:Obsolete+' => 'Exclu de l\'analyse d\'impact et des résultats de recherche~~',
	'Tag:Synchronized' => 'Synchronisé',
	'ObjectRef:Archived' => 'Archivé',
	'ObjectRef:Obsolete' => 'Obsolète',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Résultats de la recherche',
	'UI:SearchResultsTitle' => 'Recherche globale',
	'UI:SearchResultsTitle+' => 'Résultat de recherche globale',
	'UI:Search:NoSearch' => 'Rien à rechercher',
	'UI:Search:NeedleTooShort' => 'La clé de recherche "%1$s" est trop courte. Veuillez saisir au moins %2$d caractères.',
	'UI:Search:Ongoing' => 'Recherche de "%1$s"',
	'UI:Search:Enlarge' => 'Elargir la recherche',
	'UI:FullTextSearchTitle_Text' => 'Résultats pour "%1$s" :',
	'UI:Search:Count_ObjectsOf_Class_Found' => 'Trouvé %1$d objet(s) de type %2$s.',
	'UI:Search:NoObjectFound' => 'Aucun objet trouvé.',
	'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %2$s - Modification de %1$s',
	'UI:ModificationTitle_Class_Object' => '%1$s - Modification de <span class="hilite">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %2$s - Duplication de %1$s',
	'UI:CloneTitle_Class_Object' => ' %1$s - Duplication de <span class="hilite">%2$s</span>',
	'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - Création d\'un objet de type %1$s ',
	'UI:CreationTitle_Class' => 'Création d\'un objet de type %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Sélectionnez le type de %1$s à créer :',
	'UI:Class_Object_NotUpdated' => 'Aucun changement détecté, %2$s (type : %2$s) n\'a <strong>pas</strong> été modifié.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) - informations mises à jour.',
	'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Suppression massive',
	'UI:BulkDeleteTitle' => 'Sélectionnez les objets à supprimer:',
	'UI:PageTitle:ObjectCreated' => ITOP_APPLICATION_SHORT.' objet créé.',
	'UI:Title:Object_Of_Class_Created' => '%2$s - %1$s créé(e).',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => '%1$s pour %2$s de l\'état %3$s vers l\'état %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'L\'objet ne peut pas être enregistré: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Erreur Fatale',
	'UI:SystemIntrusion' => 'Accès non autorisé. Vous êtes en train de d\'effectuer une opération qui ne vous est pas permise.',
	'UI:FatalErrorMessage' => 'Erreur fatale, iTop ne peut pas continuer.',
	'UI:Error_Details' => 'Erreur: %1$s.',

	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' gestion des utilisateurs - projections des profils',
	'UI:UserManagement:Class' => 'Type',
	'UI:UserManagement:Class+' => 'Type des objets',
	'UI:UserManagement:ProjectedObject' => 'Objet',
	'UI:UserManagement:ProjectedObject+' => 'L\'objet projeté',
	'UI:UserManagement:AnyObject' => '* indifférent *',
	'UI:UserManagement:User' => 'Utilisateur',
	'UI:UserManagement:User+' => 'L\'utilisateur',
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

	'Menu:AdminTools' => 'Administration',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Outils d\'administration',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Ces outils sont accessibles uniquement aux utilisateurs possédant le profil Administrateur.',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'Système',

	'UI:ChangeManagementMenu' => 'Gestion du Changement',
	'UI:ChangeManagementMenu+' => 'Gestion du Changement',
	'UI:ChangeManagementMenu:Title' => 'Résumé des changements',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changements par type',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changements par état',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changements en attente d\'assignation',

	'UI:ConfigurationManagementMenu' => 'Gestion de Configuration',
	'UI:ConfigurationManagementMenu+' => 'Gestion de Configuration',
	'UI:ConfigurationManagementMenu:Title' => 'Résumé de l\'Infrastructure',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Nombre d\'éléments par type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Nombre d\'éléments par état',

	'UI:ConfigMgmtMenuOverview:Title' => 'Tableau de bord de la Gestion de Configuration',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Actifs par état',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Actifs par type',

	'UI:RequestMgmtMenuOverview:Title' => 'Tableau de bord de la Gestion des Demandes Utilisateurs',
	'UI-RequestManagementOverview-RequestByService' => 'Demandes par service',
	'UI-RequestManagementOverview-RequestByPriority' => 'Demandes par priorité',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Demandes non affectées à un agent',

	'UI:IncidentMgmtMenuOverview:Title' => 'Tableau de bord de la Gestion des Incidents',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidents par service',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidents par priorité',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidents non affectés à un agent',

	'UI:ChangeMgmtMenuOverview:Title' => 'Tableau de bord de la Gestion des Changements',
	'UI-ChangeManagementOverview-ChangeByType' => 'Changes par type',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Changes non affectés à un agent',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interruptions de service liées à un changement',

	'UI:ServiceMgmtMenuOverview:Title' => 'Tableau de bord de la Gestion des Services',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contrats clients à renouveler dans les 30 jours',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Contrats fournisseurs à renouveler dans les 30 jours',

	'UI:ContactsMenu' => 'Contacts',
	'UI:ContactsMenu+' => 'Contacts',
	'UI:ContactsMenu:Title' => 'Résumé des contacts',
	'UI-ContactsMenu-ContactsByLocation' => 'Contacts par emplacement',
	'UI-ContactsMenu-ContactsByType' => 'Contacts par type',
	'UI-ContactsMenu-ContactsByStatus' => 'Contacts par état',

	'Menu:CSVImportMenu' => 'Import CSV',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Import ou mise à jour en masse',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Modèle de Données',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Résumé du Modèle de Données',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Export',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Export des résultats d\'une requête en HTML, CSV ou XML',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notifications',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Configuration des Notifications',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configuration des Notifications',
	'UI:NotificationsMenu:Help' => 'Aide',
	'UI:NotificationsMenu:HelpContent' => '<p>Dans '.ITOP_APPLICATION_SHORT.' les notifications sont totalement configurables. Elles sont basées sur deux types d\'objets: <i>déclencheurs et actions</i>.</p>
<p><i><b>Les déclencheurs</b></i> définissent quand une notification sera exécutée. Il y a différents déclencheurs qui font partie du noyau d\'iTop, mais d\'autres peuvent être apportés par des extensions :
<ol>
	<li>Certains déclencheurs sont exécutés lorsqu\'un objet de la classe spécifiée est <b>créé</b>, <b>mis à jour</b> ou <b>supprimé</b>.</li>
	<li>Certains déclencheurs sont exécutés lorsqu\'un objet d\'une classe donnée <b>entre</b> ou <b>sort</b> d\'un <b>état</b> spécifié.</li>
	<li>Certains déclencheurs sont exécutés lorsqu\'un <b>seuil</b> sur <b>TTO</b> ou <b>TTR</b> a été <b>atteint</b>.</li>
</ol>
</p>
<p>
<i><b>Les actions</b></i> définissent ce qui doit être exécuté. Pour le moment il existe deux types d\'actions :
<ol>
    <li>L\'envoi de mél : Les actions de type mél définissent le modèle du message ainsi que les autres paramètres (destinataires, importance, etc.).<br />
    Une page spéciale : <a href="../setup/email.test.php" target="_blank">email.test.php</a> permet de tester votre configuration mail PHP.</li>
    <li>Les webhooks sortants : Les actions de type webhooks permettent l\'intégration avec une application tierce en transmettant des données structurées à une URL définie.</li>
</ol>
</p>
<p>Les actions doivent être associées à des déclencheurs pour pouvoir être exécutées.
Lors de l\'association à un déclencheur, on attribue à chaque action un numéro d\'ordre, qui définit la séquence des actions à exécuter.</p>',
	'UI:NotificationsMenu:Triggers' => 'Déclencheurs',
	'UI:NotificationsMenu:AvailableTriggers' => 'Déclencheurs existants',
	'UI:NotificationsMenu:OnCreate' => 'A la création d\'un objet',
	'UI:NotificationsMenu:OnStateEnter' => 'Quand un objet entre dans un état donné',
	'UI:NotificationsMenu:OnStateLeave' => 'Quand un objet quitte un état donné',
	'UI:NotificationsMenu:Actions' => 'Actions',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Actions de type mél',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Actions de type webhook (intégrations sortantes)',
	'UI:NotificationsMenu:Actions:Action' => 'Autres types d\'actions',
	'UI:NotificationsMenu:AvailableActions' => 'Actions existantes',

	'Menu:TagAdminMenu' => 'Étiquettes',
	'Menu:TagAdminMenu+' => 'Gestion des étiquettes',
	'UI:TagAdminMenu:Title' => 'Gestion des étiquettes',
	'UI:TagAdminMenu:NoTags' => 'Pas de champ étiquette configuré',
	'UI:TagSetFieldData:Error' => 'Erreur: %1$s',

	'Menu:AuditCategories' => 'Catégories d\'audit',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Catégories d\'audit',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Catégories d\'audit',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Requêtes OQL',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Executer une requête OQL',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Livre des requêtes',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Livre des requêtes',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Administration des données',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Administration des données',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Recherche Universelle',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Rechercher n\'importe quel objet...',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Gestion des Utilisateurs',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Gestion des Utilisateurs',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profils',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profils',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profils',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Comptes Utilisateurs',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Comptes Utilisateurs',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Comptes Utilisateurs',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => '%1$s version %2$s',
	'UI:iTopVersion:Long' => '%1$s version %2$s-%3$s du %4$s',
	'UI:PropertiesTab' => 'Propriétés',

	'UI:OpenDocumentInNewWindow_' => 'Ouvrir',
	'UI:DownloadDocument_' => 'Télécharger',
	'UI:Document:NoPreview' => 'L\'aperçu n\'est pas disponible pour ce type de documents',
	'UI:Download-CSV' => 'Télécharger %1$s',

	'UI:DeadlineMissedBy_duration' => 'Passé de %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dj %2$dh %3$dmin',
	'UI:Help' => 'Aide',
	'UI:PasswordConfirm' => 'Confirmer',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Enregistrez l\'objet courant avant de créer de nouveaux éléments de type %1$s.',
	'UI:DisplayThisMessageAtStartup' => 'Afficher ce message au démarrage',
	'UI:RelationshipGraph' => 'Vue graphique',
	'UI:RelationshipList' => 'Liste',
	'UI:RelationGroups' => 'Groupes',
	'UI:OperationCancelled' => 'Opération Annulée',
	'UI:ElementsDisplayed' => 'Filtrage',
	'UI:RelationGroupNumber_N' => 'Groupe n°%1$d',
	'UI:Relation:ExportAsPDF' => 'Exporter en PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Seuil de groupage',
	'UI:Relation:AdditionalContextInfo' => 'Infos complémentaires de contexte',
	'UI:Relation:NoneSelected' => 'Aucune',
	'UI:Relation:Zoom' => 'Zoom',
	'UI:Relation:ExportAsAttachment' => 'Exporter comme une Pièce Jointe...',
	'UI:Relation:DrillDown' => 'Détails...',
	'UI:Relation:PDFExportOptions' => 'Options de l\'export en PDF',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options pour la Pièce Jointe à %1$s',
	'UI:RelationOption:Untitled' => 'Sans Titre',
	'UI:Relation:Key' => 'Légende',
	'UI:Relation:Comments' => 'Commentaires',
	'UI:RelationOption:Title' => 'Titre',
	'UI:RelationOption:IncludeList' => 'Inclure la liste des objets',
	'UI:RelationOption:Comments' => 'Commentaires',
	'UI:Button:Export' => 'Exporter',
	'UI:Relation:PDFExportPageFormat' => 'Format de page',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter',
	'UI:Relation:PDFExportPageOrientation' => 'Orientation de la page',
	'UI:PageOrientation_Portrait' => 'Portrait',
	'UI:PageOrientation_Landscape' => 'Paysage',
	'UI:RelationTooltip:Redundancy' => 'Redondance',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => 'Nb éléments impactés: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Seuil critique: %1$d / %2$d',
	'Portal:Title' => 'Portail utilisateur '.ITOP_APPLICATION_SHORT,
	'Portal:NoRequestMgmt' => 'Chèr(e) %1$s, vous avez été redirigé(e) vers cette page car votre compte utilisateur est configuré avec le profil \'Utilisateur du Portail\'. Malheureusement, iTop n\'a pas été installé avec le module de \'Gestion des Demandes\'. Merci de contacter votre administrateur '.ITOP_APPLICATION_SHORT.'.',
	'Portal:Refresh' => 'Rafraîchir',
	'Portal:Back' => 'Retour',
	'Portal:WelcomeUserOrg' => 'Bienvenue %1$s (%2$s)',
	'Portal:TitleDetailsFor_Request' => 'Détail de la requête',
	'Portal:ShowOngoing' => 'Requêtes en cours',
	'Portal:ShowClosed' => 'Requêtes fermées',
	'Portal:CreateNewRequest' => 'Créer une nouvelle requête',
	'Portal:CreateNewRequestItil' => 'Créer une nouvelle requête',
	'Portal:CreateNewIncidentItil' => 'Indiquer une panne',
	'Portal:ChangeMyPassword' => 'Changer mon mot de passe',
	'Portal:Disconnect' => 'Déconnexion',
	'Portal:OpenRequests' => 'Mes requêtes en cours',
	'Portal:ClosedRequests' => 'Mes requêtes fermées',
	'Portal:ResolvedRequests' => 'Mes requêtes résolues',
	'Portal:SelectService' => 'Choisissez un service dans le catalogue:',
	'Portal:PleaseSelectOneService' => 'Veuillez choisir un service',
	'Portal:SelectSubcategoryFrom_Service' => 'Choisissez une sous-catégorie du service %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Veuillez choisir une sous-catégorie',
	'Portal:DescriptionOfTheRequest' => 'Entrez la description de votre requête:',
	'Portal:TitleRequestDetailsFor_Request' => 'Détails de votre requête %1$s:',
	'Portal:NoOpenRequest' => 'Aucune requête.',
	'Portal:NoClosedRequest' => 'Aucune requête.',
	'Portal:Button:ReopenTicket' => 'Réouvrir cette requête',
	'Portal:Button:CloseTicket' => 'Clôre cette requête',
	'Portal:Button:UpdateRequest' => 'Mettre à jour la requête',
	'Portal:EnterYourCommentsOnTicket' => 'Vos commentaires à propos du traitement de cette requête:',
	'Portal:ErrorNoContactForThisUser' => 'Erreur: l\'utilisateur courant n\'est pas associé à une Personne/Contact. Contactez votre administrateur.',
	'Portal:Attachments' => 'Pièces jointes',
	'Portal:AddAttachment' => ' Ajouter une pièce jointe ',
	'Portal:RemoveAttachment' => ' Enlever la pièce jointe ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Pièce jointe #%1$d à %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Sélectionnez un modèle de requête pour %1$s',
	'Enum:Undefined' => 'Non défini',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s J %2$s H %3$s min %4$s s',
	'UI:ModifyAllPageTitle' => 'Modification par lots',
	'UI:Modify_N_ObjectsOf_Class' => 'Modification de %1$d objet(s) de type %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modification de %1$d (sur %3$d) objets de type %2$s',
	'UI:Menu:ModifyAll' => 'Modifier...',
	'UI:Button:ModifyAll' => 'Modifier',
	'UI:Button:PreviewModifications' => 'Aperçu des modifications >>',
	'UI:ModifiedObject' => 'Objet Modifié',
	'UI:BulkModifyStatus' => 'Opération',
	'UI:BulkModifyStatus+' => '',
	'UI:BulkModifyErrors' => 'Erreur',
	'UI:BulkModifyErrors+' => '',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Erreur',
	'UI:BulkModifyStatusModified' => 'Modifié',
	'UI:BulkModifyStatusSkipped' => 'Ignoré',
	'UI:BulkModify_Count_DistinctValues' => '%1$d valeurs distinctes:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d fois',
	'UI:BulkModify:N_MoreValues' => '%1$d valeurs supplémentaires...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Tentative de modification du champ en lecture seule: %1$s',
	'UI:FailedToApplyStimuli' => 'L\'action a échoué',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modification de %2$d objet(s) de type %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Nouvelle entrée ici...',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Valeur initiale:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Le champ %1$s ne peut pas être modifié car il est géré par une synchronisation avec une source de données. Valeur ignorée.',
	'UI:ActionNotAllowed' => 'Vous n\'êtes pas autorisé à exécuter cette opération sur ces objets.',
	'UI:BulkAction:NoObjectSelected' => 'Veuillez s\\électionner au moins un objet pour cette opération.',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Le champ %1$s ne peut pas être modifié car il est géré par une synchronisation avec une source de données. Valeur inchangée.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s éléments / %2$s éléments sélectionné(s).',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s éléments.',
	'UI:Pagination:PageSize' => '%1$s éléments par page',
	'UI:Pagination:PagesLabel' => 'Pages:',
	'UI:Pagination:All' => 'Tous',
	'UI:HierarchyOf_Class' => 'Hiérarchie de type %1$s',
	'UI:Preferences' => 'Préférences...',
	'UI:ArchiveModeOn' => 'Activer le mode Archive',
	'UI:ArchiveModeOff' => 'Désactiver le mode Archive',
	'UI:ArchiveMode:Banner' => 'Mode Archive',
	'UI:ArchiveMode:Banner+' => 'Les objets archivés sont visibles, et aucune modification n\'est possible',
	'UI:FavoriteOrganizations' => 'Organisations Favorites',
	'UI:FavoriteOrganizations+' => 'Cochez dans la liste ci-dessous les organisations que vous voulez voir listées dans le menu principal. Ceci n\'est pas un réglage de sécurité. Les objets de toutes les organisations sont toujours visibles en choisissant "Toutes les Organisations" dans le menu.',
	'UI:FavoriteLanguage' => 'Langue préférée',
	'UI:Favorites:SelectYourLanguage' => 'Choisissez votre langue préférée',
	'UI:FavoriteOtherSettings' => 'Autres réglages',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Longueur par défaut :  %1$s éléments par page',
	'UI:Favorites:ShowObsoleteData' => 'Voir les données obsolètes',
	'UI:Favorites:ShowObsoleteData+' => 'Voir les données obsolètes dans les résultats de recherche et dans les listes de choix',
	'UI:NavigateAwayConfirmationMessage' => 'Toute modification sera perdue.',
	'UI:CancelConfirmationMessage' => 'Vous allez perdre vos modifications. Voulez-vous continuer ?',
	'UI:AutoApplyConfirmationMessage' => 'Des modifications n\'ont pas encore été prises en compte. Voulez-vous qu\'elles soient prises en compte automatiquement ?',
	'UI:Create_Class_InState' => 'Créer l\'objet %1$s dans l\'état: ',
	'UI:OrderByHint_Values' => 'Ordre de tri: %1$s',
	'UI:Menu:AddToDashboard' => 'Ajouter au Tableau de Bord...',
	'UI:Button:Refresh' => 'Rafraîchir',
	'UI:Button:GoPrint' => 'Imprimer...',
	'UI:ExplainPrintable' => 'Cliquez sur les icones %1$s pour cacher des éléments lors de l\'impression.<br/>Utilisez la fonction "Aperçu avant impression" de votre navigateur pour prévisualiser avant d\'imprimer.<br/>Note: cet en-tête ainsi que les icones %1$s ne seront pas imprimés.',
	'UI:PrintResolution:FullSize' => 'Pleine largeur',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait',
	'UI:PrintResolution:A4Landscape' => 'A4 Paysage',
	'UI:PrintResolution:LetterPortrait' => 'US Letter Portrait',
	'UI:PrintResolution:LetterLandscape' => 'US Letter Paysage',
	'UI:Toggle:SwitchToStandardDashboard' => 'Basculer sur le tableau de bord standard',
	'UI:Toggle:SwitchToCustomDashboard' => 'Basculer sur le tableau de bord modifié',

	'UI:ConfigureThisList' => 'Configurer Cette Liste...',
	'UI:ListConfigurationTitle' => 'Configuration de la liste',
	'UI:ColumnsAndSortOrder' => 'Colonnes et ordre de tri:',
	'UI:UseDefaultSettings' => 'Utiliser les réglages par défaut',
	'UI:UseSpecificSettings' => 'Utiliser les réglages suivants:',
	'UI:Display_X_ItemsPerPage_prefix' => 'Afficher',
	'UI:Display_X_ItemsPerPage_suffix' => 'éléments par page',
	'UI:UseSavetheSettings' => 'Enregistrer ces réglages',
	'UI:OnlyForThisList' => 'Seulement pour cette liste',
	'UI:ForAllLists' => 'Défaut pour toutes les listes',
	'UI:ExtKey_AsLink' => '%1$s (Lien)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Nom)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Monter',
	'UI:Button:MoveDown' => 'Descendre',

	'UI:OQL:UnknownClassAndFix' => 'La classe "%1$s" est inconnue. Essayez plutôt "%2$s".',
	'UI:OQL:UnknownClassNoFix' => 'La classe "%1$s" est inconnue',

	'UI:Dashboard:EditCustom' => 'Éditer la version modifiée...',
	'UI:Dashboard:CreateCustom' => 'Créer une version modifiée...',
	'UI:Dashboard:DeleteCustom' => 'Supprimer la version modifiée...',
	'UI:Dashboard:RevertConfirm' => 'Toutes modifications apportées à la version d\'origine seront perdues. Veuillez confirmer l\'opération.',
	'UI:ExportDashBoard' => 'Exporter dans un fichier',
	'UI:ImportDashBoard' => 'Importer depuis un fichier...',
	'UI:ImportDashboardTitle' => 'Importation depuis un fichier',
	'UI:ImportDashboardText' => 'Choisissez un fichier de définition de tableau de bord :',
	'UI:Dashboard:Actions' => 'Actions du tableau de bord',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'Ce tableau de bord affiche des informations qui ne reflète pas les modifications en cours.',


	'UI:DashletCreation:Title' => 'Créer un Indicateur',
	'UI:DashletCreation:Dashboard' => 'Tableau de bord',
	'UI:DashletCreation:DashletType' => 'Type d\'Indicateur',
	'UI:DashletCreation:EditNow' => 'Modifier le tableau de bord',

	'UI:DashboardEdit:Title' => 'Éditeur de tableau de bord',
	'UI:DashboardEdit:DashboardTitle' => 'Titre',
	'UI:DashboardEdit:AutoReload' => 'Réactualisation automatique',
	'UI:DashboardEdit:AutoReloadSec' => 'Réactualisation toutes les (secondes)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Le minimum permis est de %1$d secondes',
	'UI:DashboardEdit:Revert' => 'Revenir à la valeur précédente',
	'UI:DashboardEdit:Apply' => 'Appliquer',

	'UI:DashboardEdit:Layout' => 'Mise en page',
	'UI:DashboardEdit:Properties' => 'Propriétés du tableau de bord',
	'UI:DashboardEdit:Dashlets' => 'Indicateurs',
	'UI:DashboardEdit:DashletProperties' => 'Propriétés de l\'Indicateur',

	'UI:Form:Property' => 'Propriété',
	'UI:Form:Value' => 'Valeur',

	'UI:DashletUnknown:Label' => 'Inconnu',
	'UI:DashletUnknown:Description' => 'Element inconnu (est peut-être désinstallé)',
	'UI:DashletUnknown:RenderText:View' => 'Impossible d\'effectuer le rendu de cet élément.',
	'UI:DashletUnknown:RenderText:Edit' => 'Impossible d\'effectuer le rendu de cet élément (classe "%1$s"). Vérifiez avec votre administrateur si il est toujours disponible.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'Impossible d\'effectuer le rendu de cet élément (classe "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuration (XML)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'Impossible d\'effectuer le rendu de cet élément externe (classe "%1$s").',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (XML)',

	'UI:DashletPlainText:Label' => 'Texte',
	'UI:DashletPlainText:Description' => 'Text pur (pas de mise en forme)',
	'UI:DashletPlainText:Prop-Text' => 'Texte',
	'UI:DashletPlainText:Prop-Text:Default' => 'Veuillez saisir votre texte ici...',

	'UI:DashletObjectList:Label' => 'Liste d\'objets',
	'UI:DashletObjectList:Description' => 'Liste d\'objets',
	'UI:DashletObjectList:Prop-Title' => 'Titre',
	'UI:DashletObjectList:Prop-Query' => 'Requête OQL',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Titre',
	'UI:DashletGroupBy:Prop-Query' => 'Requête OQL',
	'UI:DashletGroupBy:Prop-Style' => 'Style',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Grouper par',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Heure de %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Mois de %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Jour de la semaine pour %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Jour du mois pour %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (heure)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (mois)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (jour de la semaine)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (jour du mois)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Veuillez sélectionner le champ sur lequel les objets seront groupés',

	'UI:DashletGroupByPie:Label' => 'Secteurs',
	'UI:DashletGroupByPie:Description' => 'Graphique à secteur',
	'UI:DashletGroupByBars:Label' => 'Barres',
	'UI:DashletGroupByBars:Description' => 'Graphique en Barres',
	'UI:DashletGroupByTable:Label' => 'Table',
	'UI:DashletGroupByTable:Description' => 'Table',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Fonction d\'agrégation',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Attribut',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Type de tri',
	'UI:DashletGroupBy:Prop-OrderField' => 'Trié par',
	'UI:DashletGroupBy:Prop-Limit' => 'Limite',

	'UI:DashletGroupBy:Order:asc' => 'Croissant',
	'UI:DashletGroupBy:Order:desc' => 'Décroissant',

	'UI:GroupBy:count' => 'Nombre',
	'UI:GroupBy:count+' => 'Nombre d\'éléments',
	'UI:GroupBy:sum' => 'Somme',
	'UI:GroupBy:sum+' => 'Somme des %1$s',
	'UI:GroupBy:avg' => 'Moyenne',
	'UI:GroupBy:avg+' => 'Moyenne des %1$s',
	'UI:GroupBy:min' => 'Minimum',
	'UI:GroupBy:min+' => 'Minimum des %1$s',
	'UI:GroupBy:max' => 'Maximum',
	'UI:GroupBy:max+' => 'Maximum des %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'En-tête',
	'UI:DashletHeaderStatic:Description' => 'En-tête présenté comme une barre horizontale',
	'UI:DashletHeaderStatic:Prop-Title' => 'Titre',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacts',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icône',

	'UI:DashletHeaderDynamic:Label' => 'En-tête dynamique',
	'UI:DashletHeaderDynamic:Description' => 'En-tête avec statistiques (regroupements)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Titre',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contacts',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icône',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Sous-titre',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contacts',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Requête OQL',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Grouper par',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Valeurs',

	'UI:DashletBadge:Label' => 'Badge',
	'UI:DashletBadge:Description' => 'Icône représentant une classe d\'objets, ainsi que des liens pour créer/rechercher',
	'UI:DashletBadge:Prop-Class' => 'Classe',

	'DayOfWeek-Sunday' => 'Dimanche',
	'DayOfWeek-Monday' => 'Lundi',
	'DayOfWeek-Tuesday' => 'Mardi',
	'DayOfWeek-Wednesday' => 'Mercredi',
	'DayOfWeek-Thursday' => 'Jeudi',
	'DayOfWeek-Friday' => 'Vendredi',
	'DayOfWeek-Saturday' => 'Samedi',
	'Month-01' => 'Janvier',
	'Month-02' => 'Février',
	'Month-03' => 'Mars',
	'Month-04' => 'Avril',
	'Month-05' => 'Mai',
	'Month-06' => 'Juin',
	'Month-07' => 'Juillet',
	'Month-08' => 'Août',
	'Month-09' => 'Septembre',
	'Month-10' => 'Octobre',
	'Month-11' => 'Novembre',
	'Month-12' => 'Décembre',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Di',
	'DayOfWeek-Monday-Min' => 'Lu',
	'DayOfWeek-Tuesday-Min' => 'Ma',
	'DayOfWeek-Wednesday-Min' => 'Me',
	'DayOfWeek-Thursday-Min' => 'Je',
	'DayOfWeek-Friday-Min' => 'Ve',
	'DayOfWeek-Saturday-Min' => 'Sa',
	'Month-01-Short' => 'Jan',
	'Month-02-Short' => 'Fév',
	'Month-03-Short' => 'Mar',
	'Month-04-Short' => 'Avr',
	'Month-05-Short' => 'Mai',
	'Month-06-Short' => 'Juin',
	'Month-07-Short' => 'Juil',
	'Month-08-Short' => 'Août',
	'Month-09-Short' => 'Sept',
	'Month-10-Short' => 'Oct',
	'Month-11-Short' => 'Nov',
	'Month-12-Short' => 'Déc',
	'Calendar-FirstDayOfWeek' => '1',// 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Créer un Raccourci...',
	'UI:ShortcutRenameDlg:Title' => 'Renommer le raccourci',
	'UI:ShortcutListDlg:Title' => 'Créer un raccourci pour la liste',
	'UI:ShortcutDelete:Confirm' => 'Veuillez confirmer la suppression du ou des raccourci(s)',
	'Menu:MyShortcuts' => 'Mes raccourcis',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Raccourci',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Nom',
	'Class:Shortcut/Attribute:name+' => 'Label utilisé dans le menu et comme titre de la page',
	'Class:ShortcutOQL' => 'Raccourci vers une liste d\'objets',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Requête',
	'Class:ShortcutOQL/Attribute:oql+' => 'Requête de définition de l\'ensemble des objets',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Réactualisation automatique',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Désactivée',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Personnalisée',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Réactualisation toutes les (secondes)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'Le minimum permis est de %1$d secondes',

	'UI:FillAllMandatoryFields' => 'Veuillez remplir tous les champs obligatoires.',
	'UI:ValueMustBeSet' => 'Veuillez spécifier une valeur pour ce champ',
	'UI:ValueMustBeChanged' => 'Veuillez modifier la valeur de ce champ',
	'UI:ValueInvalidFormat' => 'Format invalide',

	'UI:CSVImportConfirmTitle' => 'Veuillez confirmer cette opération',
	'UI:CSVImportConfirmMessage' => 'Etes-vous sûr(e) de vouloir faire cela ?',
	'UI:CSVImportError_items' => 'Erreurs: %1$d',
	'UI:CSVImportCreated_items' => 'Créations: %1$d',
	'UI:CSVImportModified_items' => 'Modifications: %1$d',
	'UI:CSVImportUnchanged_items' => 'Inchangés: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Format de date et heure',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Format par défaut: %1$s (ex. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Format spécial: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Codes de format:<table>
<tr><td>Y</td><td>année (sur 4 chiffres, ex. 2016)</td></tr>
<tr><td>y</td><td>année (sur 2 chiffres, ex. 16 pour 2016)</td></tr>
<tr><td>m</td><td>mois (sur 2 chiffres: 01..12)</td></tr>
<tr><td>n</td><td>month (sur 1 ou 2 chiffres sans le zero au début: 1..12)</td></tr>
<tr><td>d</td><td>jour (sur 2 chiffres: 01..31)</td></tr>
<tr><td>j</td><td>jour (sur 1 ou 2 chiffres sans le zero au début: 1..31)</td></tr>
<tr><td>H</td><td>heure (24 heures sur 2 chiffres: 00..23)</td></tr>
<tr><td>h</td><td>heure (12 heures sur 2 chiffres: 01..12)</td></tr>
<tr><td>G</td><td>heure (24 heures sur 1 ou 2 chiffres: 0..23)</td></tr>
<tr><td>g</td><td>heure (12 heures sur 1 ou 2 chiffres: 1..12)</td></tr>
<tr><td>a</td><td>am ou pm (en minuscules)</td></tr>
<tr><td>A</td><td>AM ou PM (en majuscules)</td></tr>
<tr><td>i</td><td>minutes (sur 2 chiffres: 00..59)</td></tr>
<tr><td>s</td><td>secondes (sur 2 chiffres: 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => 'Enlever',
	'UI:AddAnExisting_Class' => 'Ajouter des objets de type %1$s...',
	'UI:SelectionOf_Class' => 'Sélection d\'objets de type %1$s',

	'UI:AboutBox' => 'A propos d\''.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'A propos d\''.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Modèle de données',
	'UI:About:Support' => 'Informations pour le support',
	'UI:About:Licenses' => 'Licences',
	'UI:About:InstallationOptions' => 'Options d\'installation',
	'UI:About:ManualExtensionSource' => 'Extension',
	'UI:About:Extension_Version' => 'Version: %1$s',
	'UI:About:RemoteExtensionSource' => 'Data',

	'UI:DisconnectedDlgMessage' => 'Vous êtes déconnecté(e). Vous devez vous identifier pour pouvoir continuer à utiliser l\'application.',
	'UI:DisconnectedDlgTitle' => 'Attention !',
	'UI:LoginAgain' => 'S\'identifier',
	'UI:StayOnThePage' => 'Rester sur cette page',

	'ExcelExporter:ExportMenu' => 'Exporter pour Excel...',
	'ExcelExporter:ExportDialogTitle' => 'Export au format Excel',
	'ExcelExporter:ExportButton' => 'Exporter',
	'ExcelExporter:DownloadButton' => 'Télécharger %1$s',
	'ExcelExporter:RetrievingData' => 'Récupération des données...',
	'ExcelExporter:BuildingExcelFile' => 'Construction du fichier Excel...',
	'ExcelExporter:Done' => 'Terminé.',
	'ExcelExport:AutoDownload' => 'Téléchargement automatique dès que le fichier est prêt',
	'ExcelExport:PreparingExport' => 'Préparation de l\'export...',
	'ExcelExport:Statistics' => 'Statistiques',
	'portal:legacy_portal' => 'Portail Utilisateurs',
	'portal:backoffice' => 'Console '.ITOP_APPLICATION_SHORT,

	'UI:CurrentObjectIsLockedBy_User' => 'L\'objet est verrouillé car il est en train d\'être modifié par %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'L\'objet est en train d\'être modifié par %1$s. Vos modifications ne peuvent pas être acceptées car elles risquent d\'être écrasées.',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'L\'objet est en train d\'être modifié par %1$s. Vous pourrez envoyer vos modifications quand il/elle aura fini.',
	'UI:CurrentObjectLockExpired' => 'Le verrouillage interdisant les modifications concurrentes a expiré.',
	'UI:CurrentObjectLockExpired_Explanation' => 'Le verrouillage interdisant les modifications concurrentes a expiré. Vos modifications ne peuvent pas être acceptées car d\'autres utilisateurs peuvent modifier cet objet.',
	'UI:ConcurrentLockKilled' => 'Le verrouillage en édition de l\'objet courant a été supprimé.',
	'UI:Menu:KillConcurrentLock' => 'Supprimer le verrouillage !',

	'UI:Menu:ExportPDF' => 'Exporter en PDF...',
	'UI:Menu:PrintableVersion' => 'Version imprimable',

	'UI:BrowseInlineImages' => 'Parcourir les images...',
	'UI:UploadInlineImageLegend' => 'Ajouter une image',
	'UI:SelectInlineImageToUpload' => 'Sélectionnez l\'image à ajouter',
	'UI:AvailableInlineImagesLegend' => 'Images disponibles',
	'UI:NoInlineImage' => 'Il n\'y a aucune image de disponible sur le serveur. Utilisez le bouton "Parcourir" (ci-dessus) pour sélectionner une image sur votre ordinateur et la télécharger sur le serveur.',

	'UI:ToggleFullScreen' => 'Agrandir / Minimiser',
	'UI:Button:ResetImage' => 'Récupérer l\'image initiale',
	'UI:Button:RemoveImage' => 'Supprimer l\'image',
	'UI:Button:UploadImage' => 'Upload an image from the disk~~',
	'UI:UploadNotSupportedInThisMode' => 'La modification d\'images ou de fichiers n\'est pas supportée dans ce mode.',

	'UI:Button:RemoveDocument' => 'Supprimer le document',

	// Search form
	'UI:Search:Toggle' => 'Réduire / Ouvrir',
	'UI:Search:AutoSubmit:DisabledHint' => 'La soumission automatique a été desactivée pour cette classe',
	'UI:Search:Obsolescence:DisabledHint' => 'Conformément à vos préférences utilisateur, vous ne voyez pas les données obsolètes',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Ajoutez des critères dans le formulaire de recherche ou cliquez sur le bouton rechercher pour voir les objets.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Ajouter un critère',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Récents',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Populaires',
	'UI:Search:AddCriteria:List:Others:Title' => 'Autres',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Aucun.',

	// - Criteria header actions
	'UI:Search:Criteria:Toggle' => 'Réduire / Ouvrir',
	'UI:Search:Criteria:Remove' => 'Retirer',
	'UI:Search:Criteria:Locked' => 'Verrouillé',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s : Indifférent',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s vide',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s non vide',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s égal %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contient %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s commence par %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s fini par %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s correspond à %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s entre [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s : Indifférent',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s depuis %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s jusqu\'à %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s : Indifférent',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s à partir de %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s jusqu\'à %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s : %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s : %2$s et %3$s autres',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s : Indifférent',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s : %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s est renseigné',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s n\'est pas renseigné',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s : %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s : %2$s et %3$s autres',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s : Indifférent',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s est renseigné',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s n\'est pas renseigné',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s : %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s : %2$s et %3$s autres',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s : Indifférent',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Vide',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Non vide',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Egal',
	'UI:Search:Criteria:Operator:Default:Between' => 'Compris entre',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contient',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Commence par',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Fini par',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Exp. rég.',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Egal',// => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Supérieur',// => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Sup. / égal',// > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Inférieur',// => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Inf. / égal',// > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Différent',// => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Contient',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filtrez...',
	'UI:Search:Value:Search:Placeholder' => 'Recherchez...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Commencez à taper pour voir les valeurs possibles.',
	'UI:Search:Value:Autocomplete:Wait' => 'Patientez ...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Aucun résultat.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Cocher tout / aucun',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Cocher tout / aucun visibles',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'De',
	'UI:Search:Criteria:Numeric:Until' => 'à',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Indifférent',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Indifférent',
	'UI:Search:Criteria:DateTime:From' => 'Depuis',
	'UI:Search:Criteria:DateTime:FromTime' => 'Depuis',
	'UI:Search:Criteria:DateTime:Until' => 'jusqu\'à',
	'UI:Search:Criteria:DateTime:UntilTime' => 'jusqu\'à',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Indifférent',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Indifférent',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Indifférent',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Indifférent',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Children of the selected objects will be included.~~',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtré',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtré sur %1$s',

	'UI:StateChanged' => 'Etat modifié',
));

//
// Expression to Natural language
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Expression:Operator:AND' => ' ET ',
	'Expression:Operator:OR' => ' OU ',
	'Expression:Operator:=' => ' : ',

	'Expression:Unit:Short:DAY' => 'j',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',

	'Expression:Unit:Long:DAY' => 'jour(s)',
	'Expression:Unit:Long:HOUR' => 'heure(s)',
	'Expression:Unit:Long:MINUTE' => 'minute(s)',

	'Expression:Verb:NOW' => 'maintenant',
	'Expression:Verb:ISNULL' => ' : non défini',
));

//
// iTop Newsroom menu
//
Dict::Add('FR FR', 'French', 'Français', array(
	'UI:Newsroom:NoNewMessage' => 'Aucun nouveau message',
	'UI:Newsroom:XNewMessage' => '%1$s new message(s)~~',
	'UI:Newsroom:MarkAllAsRead' => 'Marquer tous les messages comme lus',
	'UI:Newsroom:ViewAllMessages' => 'Voir tous les messages',
	'UI:Newsroom:Preferences' => 'Préférences du centre d\'information',
	'UI:Newsroom:ConfigurationLink' => 'Configuration',
	'UI:Newsroom:ResetCache' => 'Ràz du cache',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Afficher les messages de %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Afficher au plus %1$s messages dans le menu %2$s.',
));

Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:DataSources' => 'Synchronisation',
	'Menu:DataSources+' => '',
	'Menu:WelcomeMenu' => 'Bienvenue',
	'Menu:WelcomeMenu+' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage' => 'Bienvenue',
	'Menu:WelcomeMenuPage+' => 'Bienvenue dans '.ITOP_APPLICATION_SHORT,
	'Menu:AdminTools' => 'Administration',
	'Menu:AdminTools+' => 'Outils d\'administration',
	'Menu:AdminTools?' => 'Ces outils sont accessibles uniquement aux utilisateurs possédant le profil Administrateur.',
	'Menu:DataModelMenu' => 'Modèle de Données',
	'Menu:DataModelMenu+' => 'Résumé du Modèle de Données',
	'Menu:ExportMenu' => 'Export',
	'Menu:ExportMenu+' => 'Export des résultats d\'une requête en HTML, CSV ou XML',
	'Menu:NotificationsMenu' => 'Notifications',
	'Menu:NotificationsMenu+' => 'Configuration des Notifications',
	'Menu:AuditCategories' => 'Catégories d\'audit',
	'Menu:AuditCategories+' => 'Catégories d\'audit',
	'Menu:Notifications:Title' => 'Catégories d\'audit',
	'Menu:RunQueriesMenu'         => 'Requêtes OQL',
	'Menu:RunQueriesMenu+'        => 'Executer une requête OQL',
	'Menu:QueryMenu'              => 'Livre des requêtes',
	'Menu:QueryMenu+'             => 'Livre des requêtes',
	'Menu:UniversalSearchMenu'    => 'Recherche Universelle',
	'Menu:UniversalSearchMenu+'   => 'Rechercher n\'importe quel objet...',
	'Menu:UserManagementMenu'     => 'Gestion des Utilisateurs',
	'Menu:UserManagementMenu+'    => 'Gestion des Utilisateurs',
	'Menu:ProfilesMenu'           => 'Profils',
	'Menu:ProfilesMenu+'          => 'Profils',
	'Menu:ProfilesMenu:Title'     => 'Profils',
	'Menu:UserAccountsMenu'       => 'Comptes Utilisateurs',
	'Menu:UserAccountsMenu+'      => 'Comptes Utilisateurs',
	'Menu:UserAccountsMenu:Title' => 'Comptes Utilisateurs',
	'Menu:MyShortcuts'            => 'Mes raccourcis',
	'Menu:UserManagement'         => 'Utilisateurs',
	'Menu:Queries'                => 'Requêtes',
	'Menu:ConfigurationTools'     => 'Configuration',
));
