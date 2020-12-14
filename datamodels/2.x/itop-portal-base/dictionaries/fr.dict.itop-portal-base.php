<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */
// Portal
Dict::Add('FR FR', 'French', 'Français', array(
	'Page:DefaultTitle' => 'Portail utilisateur %1$s',
	'Page:PleaseWait' => 'Veuillez patienter...',
	'Page:Home' => 'Accueil',
	'Page:GoPortalHome' => 'Revenir à l\'accueil',
	'Page:GoPreviousPage' => 'Page précédente',
	'Page:ReloadPage' => 'Recharger la page',
	'Portal:Button:Submit' => 'Valider',
	'Portal:Button:Apply' => 'Mettre à jour',
	'Portal:Button:Cancel' => 'Annuler',
	'Portal:Button:Close' => 'Fermer',
	'Portal:Button:Add' => 'Ajouter',
	'Portal:Button:Remove' => 'Enlever',
	'Portal:Button:Delete' => 'Supprimer',
	'Portal:EnvironmentBanner:Title' => 'Vous êtes dans le mode <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Retourner au mode PRODUCTION',
	'Error:HTTP:400' => 'Requête incorrecte',
	'Error:HTTP:401' => 'Authentification',
	'Error:HTTP:404' => 'Page non trouvée',
	'Error:HTTP:500' => 'Oups ! Une erreur est survenue.',
	'Error:HTTP:GetHelp' => 'Si le problème persiste, veuillez contacter votre administrateur %1$s.',
	'Error:XHR:Fail' => 'Impossible de charger les données, veuillez contacter votre administrateur %1$s si le problème persiste.',
	'Portal:ErrorUserLoggedOut' => 'Vous êtes déconnecté et devez vous reconnecter pour continuer.',
	'Portal:Datatables:Language:Processing' => 'Veuillez patienter...',
	'Portal:Datatables:Language:Search' => 'Filtrer :',
	'Portal:Datatables:Language:LengthMenu' => 'Afficher _MENU_ éléments par page',
	'Portal:Datatables:Language:ZeroRecords' => 'Aucun résultat',
	'Portal:Datatables:Language:Info' => 'Page _PAGE_ sur _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Pas d\'information disponible',
	'Portal:Datatables:Language:InfoFiltered' => 'filtrées sur un total de _MAX_ éléments',
	'Portal:Datatables:Language:EmptyTable' => 'Aucune donnée élément à afficher',
	'Portal:Datatables:Language:DisplayLength:All' => 'Tout',
	'Portal:Datatables:Language:Paginate:First' => 'Premier',
	'Portal:Datatables:Language:Paginate:Previous' => 'Précédent',
	'Portal:Datatables:Language:Paginate:Next' => 'Suivant',
	'Portal:Datatables:Language:Paginate:Last' => 'Dernier',
	'Portal:Datatables:Language:Sort:Ascending' => 'activer pour trier la colonne par ordre croissant',
	'Portal:Datatables:Language:Sort:Descending' => 'activer pour trier la colonne par ordre décroissant',
	'Portal:Autocomplete:NoResult' => 'Aucun résultat',
	'Portal:Attachments:DropZone:Message' => 'Déposez vos fichiers pour les ajouter en pièces jointes',
	'Portal:File:None' => 'Aucun fichier',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Ouvrir</a> / <a href="%4$s" class="file_download_link">Télécharger</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'fr', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Voulez-vous quitter ce formulaire ? Les données saisies seront perdues',
));

// UserProfile brick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:UserProfile:Name' => 'Profil utilisateur',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Mon profil',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Déconnexion',
	'Brick:Portal:UserProfile:Password:Title' => 'Mot de passe',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Choisissez un mot de passe',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Confirmer le mot de passe',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Veuillez vous adresser à votre administrateur %1$s pour changer votre mot de passe',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Impossible de modifier votre mot de passe, veuillez contacter votre administrateur %1$s',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Informations personnelles',
	'Brick:Portal:UserProfile:Photo:Title' => 'Photo',
));

// AggregatePageBrick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Tableau de bord',
));

// BrowseBrick brick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:Browse:Name' => 'Navigation dans les éléments',
	'Brick:Portal:Browse:Mode:List' => 'Liste',
	'Brick:Portal:Browse:Mode:Tree' => 'Hiérarchie',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Mosaïque',
	'Brick:Portal:Browse:Action:Drilldown' => 'Parcourir',
	'Brick:Portal:Browse:Action:View' => 'Détails',
	'Brick:Portal:Browse:Action:Edit' => 'Modifier',
	'Brick:Portal:Browse:Action:Create' => 'Créer',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Créer %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Tout déplier',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Tout replier',
	'Brick:Portal:Browse:Filter:NoData' => 'Aucun élément',
));

// ManageBrick brick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:Manage:Name' => 'Gestion d\'éléments',
	'Brick:Portal:Manage:Table:NoData' => 'Aucun élément',
	'Brick:Portal:Manage:Table:ItemActions' => 'Actions',
	'Brick:Portal:Manage:DisplayMode:list' => 'Liste',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Secteur',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Histogramme',
	'Brick:Portal:Manage:Others' => 'Autres',
	'Brick:Portal:Manage:All' => 'Total',
	'Brick:Portal:Manage:Group' => 'Groupe',
	'Brick:Portal:Manage:fct:count' => 'Total',
	'Brick:Portal:Manage:fct:sum' => 'Somme',
	'Brick:Portal:Manage:fct:avg' => 'Moyenne',
	'Brick:Portal:Manage:fct:min' => 'Min',
	'Brick:Portal:Manage:fct:max' => 'Max',
));

// ObjectBrick brick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:Object:Name' => 'Objet',
	'Brick:Portal:Object:Form:Create:Title' => 'Création de %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Modification de %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Veuillez compléter les informations suivantes :',
	'Brick:Portal:Object:Form:Message:Saved' => 'Enregistré',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s enregistré(e)',
	'Brick:Portal:Object:Search:Regular:Title' => 'Sélection de %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Sélection de %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Copier l\'url de l\'objet',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Copié'
));

// CreateBrick brick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:Create:Name' => 'Création rapide',
	'Brick:Portal:Create:ChooseType' => 'Veuillez choisir le type',
));

// Filter brick
Dict::Add('FR FR', 'French', 'Français', array(
	'Brick:Portal:Filter:Name' => 'Préfiltre une brique',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'ex : connecter wifi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Rechercher',
));
