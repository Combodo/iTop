<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:UserRequest' => 'Demande Utilisateur',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Type de Requête',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Information',
	'Class:UserRequest/Attribute:request_type/Value:information+' => '',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Problème',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => '',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Demande de service',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => '',
	'Class:UserRequest/Attribute:freeze_reason' => 'Raison de la suspension',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Assigner',
	'Class:UserRequest/Stimulus:ev_assign+' => 'Assigner et traiter la demande',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Réassigner',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Marquer comme résolu',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Fermer',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Suspendre',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Menu:RequestManagement' => 'Gestion des demandes',
	'Menu:RequestManagement+' => 'Gestion des demandes utilisateurs',
	'Menu:UserRequest:Overview' => 'Vue d\'ensemble',
	'Menu:UserRequest:Overview+' => 'Vue d\'ensemble des demandes utilisateurs',
	'Menu:NewUserRequest' => 'Nouvelle demande utilisateur',
	'Menu:NewUserRequest+' => 'Créer un nouveau ticket de demande utilisateur',
	'Menu:SearchUserRequests' => 'Rechercher des demandes utilisateur',
	'Menu:SearchUserRequests+' => 'Rechercher parmi les demandes utilisateur',
	'Menu:UserRequest:Shortcuts' => 'Raccourcis',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Mes demandes',
	'Menu:UserRequest:MyRequests+' => 'Demandes utilisateurs qui me sont assignées',
	'Menu:UserRequest:EscalatedRequests' => 'Demandes en escalade',
	'Menu:UserRequest:EscalatedRequests+' => 'Demandes utilisateurs en escalade',
	'Menu:UserRequest:OpenRequests' => 'Demandes en cours',
	'Menu:UserRequest:OpenRequests+' => 'Toutes les demandes utilisateurs en cours',
));
?>
