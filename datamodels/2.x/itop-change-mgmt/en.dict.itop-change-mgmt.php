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

Dict::Add('EN US', 'English', 'English', array(
	'Menu:ChangeManagement' => 'Change management',
	'Menu:Change:Overview' => 'Overview',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'New Change',
	'Menu:NewChange+' => 'Create a new Change ticket',
	'Menu:SearchChanges' => 'Search for Changes',
	'Menu:SearchChanges+' => 'Search for Change tickets',
	'Menu:Change:Shortcuts' => 'Shortcuts',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changes awaiting acceptance',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Changes awaiting approval',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Opened changes',
	'Menu:Changes+' => 'All Opened Changes',
	'Menu:MyChanges' => 'Changes assigned to me',
	'Menu:MyChanges+' => 'Changes assigned to me (as Agent)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes by category for the last 7 days',
	'UI-ChangeManagementOverview-Last-7-days' => 'Number of changes for the last 7 days',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes by domain for the last 7 days',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes by status for the last 7 days',
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
// Class: Change
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Change' => 'Change',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'New',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Assigned',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Planned',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rejected',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Approved',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Closed',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Category',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'application',
	'Class:Change/Attribute:category/Value:application+' => 'application',
	'Class:Change/Attribute:category/Value:hardware' => 'hardware',
	'Class:Change/Attribute:category/Value:hardware+' => 'hardware',
	'Class:Change/Attribute:category/Value:network' => 'network',
	'Class:Change/Attribute:category/Value:network+' => 'network',
	'Class:Change/Attribute:category/Value:other' => 'other',
	'Class:Change/Attribute:category/Value:other+' => 'other',
	'Class:Change/Attribute:category/Value:software' => 'software',
	'Class:Change/Attribute:category/Value:software+' => 'software',
	'Class:Change/Attribute:category/Value:system' => 'system',
	'Class:Change/Attribute:category/Value:system+' => 'system',
	'Class:Change/Attribute:reject_reason' => 'Reject reason',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Change manager',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'Change manager email',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Parent change',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Parent change ref',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Creation date',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Approval date',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Fallback plan',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Related requests',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:related_incident_list' => 'Related incidents',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:child_changes_list' => 'Child changes',
	'Class:Change/Attribute:child_changes_list+' => '',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Parent change friendly name',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Assign',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Plan',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Reject',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Reopen',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Approve',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Close',
	'Class:Change/Stimulus:ev_finish+' => '',
));

?>
