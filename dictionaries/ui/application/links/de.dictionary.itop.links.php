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
Dict::Add('DE DE', 'German', 'Deutsch', array(

	// Placeholders
 // $%1s : host object class name
 // $%2s : host object friendlyname
 // $%3s : current tab name
 // $%4s : remote object class name
 // $%5s : remote object friendlyname
	'UI:Links:Object:New:Modal:Title' => 'Ein Objekt erstellen',

	// Create
	'UI:Links:Create:Button' => 'Erstellen',
	'UI:Links:Create:Button+' => '%4$s erstellen',
	'UI:Links:Create:Modal:Title' => '%4$s in %2$s erstellen',

	// Add
	'UI:Links:Add:Button' => 'Hinzufügen',
	'UI:Links:Add:Button+' => '%4$s hinzufügen',
	'UI:Links:Add:Modal:Title' => '%4$s zu %2$s hinzufügen',

	// Modify link
	'UI:Links:ModifyLink:Button' => 'Bearbeiten',
	'UI:Links:ModifyLink:Button+' => 'Diese Verknüpfung bearbeiten',
	'UI:Links:ModifyLink:Modal:Title' => 'Verknüpfung zwischen %2$s und %5$s bearbeiten',

	// Modify object
	'UI:Links:ModifyObject:Button' => 'Bearbeiten',
	'UI:Links:ModifyObject:Button+' => 'Dieses Objekt bearbeiten',
	'UI:Links:ModifyObject:Modal:Title' => '%5$s',

	// Remove
	'UI:Links:Remove:Button' => 'entfernen',
	'UI:Links:Remove:Button+' => '%4$s entfernen',
	'UI:Links:Remove:Modal:Title' => '%4$s aus %1$s entfernen',
	'UI:Links:Remove:Modal:Message' => 'Möchten Sie wirklich %5$s aus %2$s entfernen',

	// Delete
	'UI:Links:Delete:Button' => 'Löschen',
	'UI:Links:Delete:Button+' => '%4$s löschen',
	'UI:Links:Delete:Modal:Title' => 'Löschen von %4$s',
	'UI:Links:Delete:Modal:Message' => 'Wollen Sie %5$s wirklich löschen?',

	// Bulk
	'UI:Links:Bulk:LinkWillBeCreatedForAllObjects' => 'Zu allen Objekten hinzufügen',
	'UI:Links:Bulk:LinkWillBeDeletedFromAllObjects' => 'Aus allen Objekten entfernen',
	'UI:Links:Bulk:LinkWillBeCreatedFor1Object' => 'Zu einem Objekt hinzufügen',
	'UI:Links:Bulk:LinkWillBeDeletedFrom1Object' => 'Aus einem Objekt entfernen',
	'UI:Links:Bulk:LinkWillBeCreatedForXObjects' => 'Hinzufügen von {count} Objekten',
	'UI:Links:Bulk:LinkWillBeDeletedFromXObjects' => 'Entfernen von {count} Objekten',
	'UI:Links:Bulk:LinkExistForAllObjects' => 'Alle Objekte sind bereits verknüpft',
	'UI:Links:Bulk:LinkExistForOneObject' => 'Ein Objekt ist verknüpft',
	'UI:Links:Bulk:LinkExistForXObjects' => '{count} Objekte sind verknüpft',

	// New item
	'UI:Links:NewItem' => 'Neues Element',
));