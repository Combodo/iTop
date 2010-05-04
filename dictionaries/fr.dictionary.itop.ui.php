<?php

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
	'UI:Button:Cancel' => 'Annuler',
	'UI:Button:Apply' => 'Appliquer',
	'UI:Button:Back' => ' << Retour ',
	'UI:Button:Next' => ' Suite >> ',
	'UI:Button:DoImport' => ' Lancer l\'import ! ',
	'UI:Button:Done' => ' Terminé ',
	'UI:Button:SimulateImport' => ' Simuler l\'import ',
	
	'UI:SearchToggle' => 'Recherche',

	'UI:ClickToCreateNew' => 'Cliquez ici pour créer un nouveau %1$s',
	'UI:NoObjectToDisplay' => 'Aucun objet à afficher.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Le paramètre object_id est obligatoire quand link_attr est spécifié. Vérifiez la définition du modèle.',
	'UI:Error:MandatoryTemplateParameter_link_attr' => 'Le paramètre target_attr est obligatoire quand link_attr est spécifié. Vérifiez la définition du modèle.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Le paramètre group_by est obligatoire. Vérifiez la définition du modèle.',
	'UI:Error:InvalidGroupByFields' => 'Liste des champs "group by" incorrecte: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Erreur: style de bloc("%1$s") inconnu.',
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
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'Export CSV',
	'UI:Menu:Modify' => 'Modifier...',
	'UI:Menu:Delete' => 'Supprimer...',
	'UI:Menu:Manage' => 'Gérer...',
	'UI:Menu:BulkDelete' => 'Supprimer...',
	'UI:UndefinedObject' => 'non défini',
	'UI:Document:OpenInNewWindow:Download' => 'Ouvrir dans un nouvelle fenêtre: %1$s, Télécharger: %2$s',
	'UI:SelectAllToggle+' => 'Tout Sélectionner / Tout Désélectionner',
	'UI:ClickToDisplay+' => 'Cliquer pour afficher',
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
	'UI:Login:Welcome' => 'Bienvenue dans iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Mot de passe ou identifiant incorrect.',
	'UI:Login:IdentifyYourself' => 'Merci de vous identifier',
	'UI:Login:UserNamePrompt' => 'Identifiant',
	'UI:Login:PasswordPrompt' => 'Mot de passe',
	'UI:Button:Login' => 'Entrer dans iTop',
	'UI:Login:Error:AccessRestricted' => 'L\'accès à iTop est soumis à autorisation. Merci de contacter votre administrateur iTop.',
	'UI:CSVImport:MappingSelectOne' => '-- choisir une valeur --',
	'UI:CSVImport:MappingNotApplicable' => '------ n/a ------',
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

));

?>
