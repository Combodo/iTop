<?php
// Copyright (C) 2010-2014 Combodo SARL
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
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', array(
	'Menu:IncidentManagement' => 'Incident Management',
	'Menu:IncidentManagement+' => 'Incident Management',
	'Menu:Incident:Overview' => 'Overview',
	'Menu:Incident:Overview+' => 'Overview',
	'Menu:NewIncident' => 'New incident',
	'Menu:NewIncident+' => 'Create a new incident ticket',
	'Menu:SearchIncidents' => 'Search for incidents',
	'Menu:SearchIncidents+' => 'Search for incident tickets',
	'Menu:Incident:Shortcuts' => 'Shortcuts',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => 'Incidents assigned to me',
	'Menu:Incident:MyIncidents+' => 'Incidents assigned to me (as Agent)',
	'Menu:Incident:EscalatedIncidents' => 'Escalated incidents',
	'Menu:Incident:EscalatedIncidents+' => 'Escalated incidents',
	'Menu:Incident:OpenIncidents' => 'All open incidents',
	'Menu:Incident:OpenIncidents+' => 'All open incidents',
	'Menu:Incident:UnassignedIncidents' => 'Incidents not yet assigned',
	'Menu:Incident:UnassignedIncidents+' => 'Incidents not yet assigned',
	'Menu:Incident:HelpdeskIncidents' => 'Incidents assigned to Level2',
	'Menu:Incident:HelpdeskIncidents+' => 'Incidents assigned to Level2',
	'UI-IncidentManagementOverview-IncidentByPriority-last-14-days' => 'Last 14 days incident per priority',
	'UI-IncidentManagementOverview-Last-14-days' => 'Last 14 days number of incidents',
	'UI-IncidentManagementOverview-OpenIncidentByStatus' => 'Open incidents by status',
	'UI-IncidentManagementOverview-OpenIncidentByAgent' => 'Open incidents by agent',
	'UI-IncidentManagementOverview-OpenIncidentByCustomer' => 'Open incidents by customer',
));




// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//
// Class: Incident
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Incident' => 'Incident',
	'Class:Incident+' => '',
	'Class:Incident/Attribute:status' => 'Status',
	'Class:Incident/Attribute:status+' => '',
	'Class:Incident/Attribute:status/Value:new' => 'New',
	'Class:Incident/Attribute:status/Value:new+' => '',
	'Class:Incident/Attribute:status/Value:escalated_tto' => 'Escalated TTO',
	'Class:Incident/Attribute:status/Value:escalated_tto+' => '',
	'Class:Incident/Attribute:status/Value:assigned' => 'Assigned',
	'Class:Incident/Attribute:status/Value:assigned+' => '',
	'Class:Incident/Attribute:status/Value:escalated_ttr' => 'Escalated TTR',
	'Class:Incident/Attribute:status/Value:escalated_ttr+' => '',
	'Class:Incident/Attribute:status/Value:waiting_for_approval' => 'Waiting for approval',
	'Class:Incident/Attribute:status/Value:waiting_for_approval+' => '',
	'Class:Incident/Attribute:status/Value:pending' => 'Pending',
	'Class:Incident/Attribute:status/Value:pending+' => '',
	'Class:Incident/Attribute:status/Value:resolved' => 'Resolved',
	'Class:Incident/Attribute:status/Value:resolved+' => '',
	'Class:Incident/Attribute:status/Value:closed' => 'Closed',
	'Class:Incident/Attribute:status/Value:closed+' => '',
	'Class:Incident/Attribute:impact' => 'Impact',
	'Class:Incident/Attribute:impact+' => '',
	'Class:Incident/Attribute:impact/Value:1' => 'A department',
	'Class:Incident/Attribute:impact/Value:1+' => '',
	'Class:Incident/Attribute:impact/Value:2' => 'A service',
	'Class:Incident/Attribute:impact/Value:2+' => '',
	'Class:Incident/Attribute:impact/Value:3' => 'A person',
	'Class:Incident/Attribute:impact/Value:3+' => '',
	'Class:Incident/Attribute:priority' => 'Priority',
	'Class:Incident/Attribute:priority+' => '',
	'Class:Incident/Attribute:priority/Value:1' => 'critical',
	'Class:Incident/Attribute:priority/Value:1+' => 'critical',
	'Class:Incident/Attribute:priority/Value:2' => 'high',
	'Class:Incident/Attribute:priority/Value:2+' => 'high',
	'Class:Incident/Attribute:priority/Value:3' => 'medium',
	'Class:Incident/Attribute:priority/Value:3+' => 'medium',
	'Class:Incident/Attribute:priority/Value:4' => 'low',
	'Class:Incident/Attribute:priority/Value:4+' => 'low',
	'Class:Incident/Attribute:urgency' => 'Urgency',
	'Class:Incident/Attribute:urgency+' => '',
	'Class:Incident/Attribute:urgency/Value:1' => 'critical',
	'Class:Incident/Attribute:urgency/Value:1+' => 'critical',
	'Class:Incident/Attribute:urgency/Value:2' => 'high',
	'Class:Incident/Attribute:urgency/Value:2+' => 'high',
	'Class:Incident/Attribute:urgency/Value:3' => 'medium',
	'Class:Incident/Attribute:urgency/Value:3+' => 'medium',
	'Class:Incident/Attribute:urgency/Value:4' => 'low',
	'Class:Incident/Attribute:urgency/Value:4+' => 'low',
	'Class:Incident/Attribute:origin' => 'Origin',
	'Class:Incident/Attribute:origin+' => '',
	'Class:Incident/Attribute:origin/Value:mail' => 'mail',
	'Class:Incident/Attribute:origin/Value:mail+' => 'mail',
	'Class:Incident/Attribute:origin/Value:monitoring' => 'monitoring',
	'Class:Incident/Attribute:origin/Value:monitoring+' => 'monitoring',
	'Class:Incident/Attribute:origin/Value:phone' => 'phone',
	'Class:Incident/Attribute:origin/Value:phone+' => 'phone',
	'Class:Incident/Attribute:origin/Value:portal' => 'portal',
	'Class:Incident/Attribute:origin/Value:portal+' => 'portal',
	'Class:Incident/Attribute:service_id' => 'Service',
	'Class:Incident/Attribute:service_id+' => '',
	'Class:Incident/Attribute:service_name' => 'Service name',
	'Class:Incident/Attribute:service_name+' => '',
	'Class:Incident/Attribute:servicesubcategory_id' => 'Service subcategory',
	'Class:Incident/Attribute:servicesubcategory_id+' => '',
	'Class:Incident/Attribute:servicesubcategory_name' => 'Service subcategory name',
	'Class:Incident/Attribute:servicesubcategory_name+' => '',
	'Class:Incident/Attribute:escalation_flag' => 'Hot Flag',
	'Class:Incident/Attribute:escalation_flag+' => '',
	'Class:Incident/Attribute:escalation_flag/Value:no' => 'No',
	'Class:Incident/Attribute:escalation_flag/Value:no+' => 'No',
	'Class:Incident/Attribute:escalation_flag/Value:yes' => 'Yes',
	'Class:Incident/Attribute:escalation_flag/Value:yes+' => 'Yes',
	'Class:Incident/Attribute:escalation_reason' => 'Hot reason',
	'Class:Incident/Attribute:escalation_reason+' => '',
	'Class:Incident/Attribute:assignment_date' => 'Assignment date',
	'Class:Incident/Attribute:assignment_date+' => '',
	'Class:Incident/Attribute:resolution_date' => 'Resolution date',
	'Class:Incident/Attribute:resolution_date+' => '',
	'Class:Incident/Attribute:last_pending_date' => 'Last pending date',
	'Class:Incident/Attribute:last_pending_date+' => '',
	'Class:Incident/Attribute:cumulatedpending' => 'Cumulated pending',
	'Class:Incident/Attribute:cumulatedpending+' => '',
	'Class:Incident/Attribute:tto' => 'tto',
	'Class:Incident/Attribute:tto+' => '',
	'Class:Incident/Attribute:ttr' => 'ttr',
	'Class:Incident/Attribute:ttr+' => '',
	'Class:Incident/Attribute:tto_escalation_deadline' => 'TTO Deadline',
	'Class:Incident/Attribute:tto_escalation_deadline+' => '',
	'Class:Incident/Attribute:sla_tto_passed' => 'SLA tto passed',
	'Class:Incident/Attribute:sla_tto_passed+' => '',
	'Class:Incident/Attribute:sla_tto_over' => 'SLA tto over',
	'Class:Incident/Attribute:sla_tto_over+' => '',
	'Class:Incident/Attribute:ttr_escalation_deadline' => 'TTR Deadline',
	'Class:Incident/Attribute:ttr_escalation_deadline+' => '',
	'Class:Incident/Attribute:sla_ttr_passed' => 'SLA ttr passed',
	'Class:Incident/Attribute:sla_ttr_passed+' => '',
	'Class:Incident/Attribute:sla_ttr_over' => 'SLA ttr over',
	'Class:Incident/Attribute:sla_ttr_over+' => '',
	'Class:Incident/Attribute:time_spent' => 'Resolution delay',
	'Class:Incident/Attribute:time_spent+' => '',
	'Class:Incident/Attribute:resolution_code' => 'Resolution code',
	'Class:Incident/Attribute:resolution_code+' => '',
	'Class:Incident/Attribute:resolution_code/Value:assistance' => 'assistance',
	'Class:Incident/Attribute:resolution_code/Value:assistance+' => 'assistance',
	'Class:Incident/Attribute:resolution_code/Value:bug fixed' => 'bug fixed',
	'Class:Incident/Attribute:resolution_code/Value:bug fixed+' => 'bug fixed',
	'Class:Incident/Attribute:resolution_code/Value:hardware repair' => 'hardware repair',
	'Class:Incident/Attribute:resolution_code/Value:hardware repair+' => 'hardware repair',
	'Class:Incident/Attribute:resolution_code/Value:other' => 'other',
	'Class:Incident/Attribute:resolution_code/Value:other+' => 'other',
	'Class:Incident/Attribute:resolution_code/Value:software patch' => 'software patch',
	'Class:Incident/Attribute:resolution_code/Value:software patch+' => 'software patch',
	'Class:Incident/Attribute:resolution_code/Value:system update' => 'system update',
	'Class:Incident/Attribute:resolution_code/Value:system update+' => 'system update',
	'Class:Incident/Attribute:resolution_code/Value:training' => 'training',
	'Class:Incident/Attribute:resolution_code/Value:training+' => 'training',
	'Class:Incident/Attribute:solution' => 'Solution',
	'Class:Incident/Attribute:solution+' => '',
	'Class:Incident/Attribute:pending_reason' => 'Pending reason',
	'Class:Incident/Attribute:pending_reason+' => '',
	'Class:Incident/Attribute:parent_incident_id' => 'Parent incident',
	'Class:Incident/Attribute:parent_incident_id+' => '',
	'Class:Incident/Attribute:parent_incident_ref' => 'Parent incident ref',
	'Class:Incident/Attribute:parent_incident_ref+' => '',
	'Class:Incident/Attribute:parent_change_id' => 'Parent change',
	'Class:Incident/Attribute:parent_change_id+' => '',
	'Class:Incident/Attribute:parent_change_ref' => 'Parent change ref',
	'Class:Incident/Attribute:parent_change_ref+' => '',
	'Class:Incident/Attribute:related_request_list' => 'Child requests',
	'Class:Incident/Attribute:related_request_list+' => '',
	'Class:Incident/Attribute:child_incidents_list' => 'Child incidents',
	'Class:Incident/Attribute:child_incidents_list+' => 'All the child incidents related to this incident',
	'Class:Incident/Attribute:public_log' => 'Public log',
	'Class:Incident/Attribute:public_log+' => '',
	'Class:Incident/Attribute:user_satisfaction' => 'User satisfaction',
	'Class:Incident/Attribute:user_satisfaction+' => '',
	'Class:Incident/Attribute:user_satisfaction/Value:1' => 'Very satisfied',
	'Class:Incident/Attribute:user_satisfaction/Value:1+' => 'Very satisfied',
	'Class:Incident/Attribute:user_satisfaction/Value:2' => 'Fairly statisfied',
	'Class:Incident/Attribute:user_satisfaction/Value:2+' => 'Fairly statisfied',
	'Class:Incident/Attribute:user_satisfaction/Value:3' => 'Rather Dissatified',
	'Class:Incident/Attribute:user_satisfaction/Value:3+' => 'Rather Dissatified',
	'Class:Incident/Attribute:user_satisfaction/Value:4' => 'Very Dissatisfied',
	'Class:Incident/Attribute:user_satisfaction/Value:4+' => 'Very Dissatisfied',
	'Class:Incident/Attribute:user_comment' => 'User comment',
	'Class:Incident/Attribute:user_comment+' => '',
	'Class:Incident/Attribute:parent_incident_id_friendlyname' => 'parent_incident_id_friendlyname',
	'Class:Incident/Attribute:parent_incident_id_friendlyname+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Assign',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Re-assign',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_pending' => 'Pending',
	'Class:Incident/Stimulus:ev_pending+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'Timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_autoresolve' => 'Automatic resolve',
	'Class:Incident/Stimulus:ev_autoresolve+' => '',
	'Class:Incident/Stimulus:ev_autoclose' => 'Automatic close',
	'Class:Incident/Stimulus:ev_autoclose+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Mark as resolved',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Close this request',
	'Class:Incident/Stimulus:ev_close+' => '',
	'Class:Incident/Stimulus:ev_reopen' => 'Re-open',
	'Class:Incident/Stimulus:ev_reopen+' => '',
	'Class:Incident/Error:CannotAssignParentIncidentIdToSelf' => 'Cannot assign the Parent incident to the incident itself',

	'Class:Incident/Method:ResolveChildTickets' => 'ResolveChildTickets',
	'Class:Incident/Method:ResolveChildTickets+' => 'Cascade the resolution to child ticket (ev_autoresolve), and align the following characteristics: service, team, agent, resolution info',
));
