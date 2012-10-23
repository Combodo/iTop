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
	'Class:Incident' => 'Ticket d\'Incident',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Assigner',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Réassigner',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Marquer comme résolu',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Fermer',
	'Class:Incident/Stimulus:ev_close+' => '',
	'Class:lnkTicketToIncident' => 'lien Incident/Ticket',
	'Class:lnkTicketToIncident+' => '',
	'Class:lnkTicketToIncident/Attribute:ticket_id' => 'Ticket',
	'Class:lnkTicketToIncident/Attribute:ticket_id+' => '',
	'Class:lnkTicketToIncident/Attribute:ticket_ref' => 'Réf. Ticket',
	'Class:lnkTicketToIncident/Attribute:ticket_ref+' => '',
	'Class:lnkTicketToIncident/Attribute:incident_id' => 'Incident',
	'Class:lnkTicketToIncident/Attribute:incident_id+' => '',
	'Class:lnkTicketToIncident/Attribute:incident_ref' => 'Réf. Ticket',
	'Class:lnkTicketToIncident/Attribute:incident_ref+' => '',
	'Class:lnkTicketToIncident/Attribute:reason' => 'Raison',
	'Class:lnkTicketToIncident/Attribute:reason+' => '',
	'Menu:IncidentManagement' => 'Gestion des incidents',
	'Menu:IncidentManagement+' => 'Gestion des incidents',
	'Menu:Incident:Overview' => 'Vue d\'ensemble',
	'Menu:Incident:Overview+' => 'Vue d\'ensemble',
	'Menu:NewIncident' => 'Nouvel Incident',
	'Menu:NewIncident+' => 'Créer un nouveau ticket d\'incident',
	'Menu:SearchIncidents' => 'Rechercher des incidents',
	'Menu:SearchIncidents+' => 'Rechercher parmi les tickets d\'incidents',
	'Menu:Incident:Shortcuts' => 'Raccourcis',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => 'Mes tickets',
	'Menu:Incident:MyIncidents+' => 'Tickets d\'incident qui me sont assignés',
	'Menu:Incident:EscalatedIncidents' => 'Ticket en cours d\'escalade',
	'Menu:Incident:EscalatedIncidents+' => 'Ticket d\'incident en cours d\'escalade',
	'Menu:Incident:OpenIncidents' => 'Ticket ouverts',
	'Menu:Incident:OpenIncidents+' => 'Tous les tickets d\'incident ouverts',
));
?>
