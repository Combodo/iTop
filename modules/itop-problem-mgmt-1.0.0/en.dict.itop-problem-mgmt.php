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

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+




Dict::Add('EN US', 'English', 'English', array(
        'Menu:ProblemManagement' => 'Problem Management',
        'Menu:ProblemManagement+' => 'Problem Management',
    	'Menu:Problem:Overview' => 'Overview',
    	'Menu:Problem:Overview+' => 'Overview',
    	'Menu:NewProblem' => 'New Problem',
    	'Menu:NewProblem+' => 'New Problem',
    	'Menu:SearchProblems' => 'Search for Problems',
    	'Menu:SearchProblems+' => 'Search for Problems',
    	'Menu:Problem:KnownErrors' => 'All Known Errors',
    	'Menu:Problem:KnownErrors+' => 'All Known Errors',
    	'Menu:Problem:Shortcuts' => 'Shortcuts',
        'Menu:Problem:MyProblems' => 'My Problems',
        'Menu:Problem:MyProblems+' => 'My Problems',
        'Menu:Problem:OpenProblems' => 'All Open problems',
        'Menu:Problem:OpenProblems+' => 'All Open problems',
));
//
// Class: Problem
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Problem' => 'Problem',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'New',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Assigned',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Resolved',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Closed',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => 'Customer',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:org_name' => 'Name',
	'Class:Problem/Attribute:org_name+' => 'Common name',
	'Class:Problem/Attribute:service_id' => 'Service',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Name',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Service Category',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Name',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Product',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impact',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'A Person',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'A Service',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'A Department',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'urgency',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Low',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Low',
	'Class:Problem/Attribute:urgency/Value:2' => 'Medium',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Medium',
	'Class:Problem/Attribute:urgency/Value:3' => 'High',
	'Class:Problem/Attribute:urgency/Value:3+' => 'High',
	'Class:Problem/Attribute:priority' => 'priority',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Low',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Medium',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'High',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'WorkGroup',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:workgroup_name' => 'Name',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_id' => 'Agent',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:agent_name' => 'Agent Name',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'Agent Email',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Related Change',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:close_date' => 'Close Date',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => 'Last Update',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Assignment Date',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Resolution Date',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Known Errors',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Assign',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Reaassign',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Resolve',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Close',
	'Class:Problem/Stimulus:ev_close+' => '',
));

?>
