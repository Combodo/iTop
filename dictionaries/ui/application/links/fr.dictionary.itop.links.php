<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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
Dict::Add('FR FR', 'French', 'Français', array(

	// Placeholders
 // $%1s : host object class name
 // $%2s : host object firendlyname
 // $%3s : current tab name
 // $%4s : remote object class name
 // $%5s : remote object friendlyname
	'UI:Links:Object:New:Modal:Title' => 'Créer un objet',

	// Create
	'UI:Links:Create:Button' => 'Créer',
	'UI:Links:Create:Button+' => 'Créer un(e) %4$s',
	'UI:Links:Create:Modal:Title' => 'Ajouter un(e) %4$s à %2$s',

	// Add
	'UI:Links:Add:Button' => 'Ajouter',
	'UI:Links:Add:Button+' => 'Ajouter un %4$s',
	'UI:Links:Add:Modal:Title' => 'Ajouter un %4$s à %2$s',

	// Modify link
	'UI:Links:ModifyLink:Button' => 'Modifier',
	'UI:Links:ModifyLink:Button+' => 'Modifier cette relation',
	'UI:Links:ModifyLink:Modal:Title' => 'Modifier la relation entre %2$s et %5$s',

	// Modify object
	'UI:Links:ModifyObject:Button' => 'Modifier',
	'UI:Links:ModifyObject:Button+' => 'Modifier cet objet',
	'UI:Links:ModifyObject:Modal:Title' => '%5$s',

	// Remove
	'UI:Links:Remove:Button' => 'Retirer',
	'UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'UI:Links:Remove:Modal:Title' => 'Retirer un %4$s de %1$s',
	'UI:Links:Remove:Modal:Message' => 'Voulez-vous vraiment retirer %5$s de %2$s ?',

	// Delete
	'UI:Links:Delete:Button' => 'Supprimer',
	'UI:Links:Delete:Button+' => 'Supprimer cet(te) %4$s',
	'UI:Links:Delete:Modal:Title' => 'Supprimer un(e) %4$s',
	'UI:Links:Delete:Modal:Message' => 'Voulez-vous vraiment supprimer %5$s ?',

	// Bulk
	'UI:Links:Bulk:LinkWillBeCreatedForAllObjects' => 'Ajouter à tous les objets',
	'UI:Links:Bulk:LinkWillBeDeletedFromAllObjects' => 'Enlever de tous les objets',
	'UI:Links:Bulk:LinkWillBeCreatedFor1Object' => 'Ajouter à un objet',
	'UI:Links:Bulk:LinkWillBeDeletedFrom1Object' => 'Enlever de un objet',
	'UI:Links:Bulk:LinkWillBeCreatedForXObjects' => 'Ajouter à {count} objets',
	'UI:Links:Bulk:LinkWillBeDeletedFromXObjects' => 'Enlever de {count} objets',
	'UI:Links:Bulk:LinkExistForAllObjects' => 'Tous les objets sont déjà liés',
	'UI:Links:Bulk:LinkExistForOneObject' => 'Un objet est lié',
	'UI:Links:Bulk:LinkExistForXObjects' => '{count} objets sont liés',

	// New item
	'UI:Links:NewItem' => 'Nouvel element',
));