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

$oMyMenuGroup = new MenuGroup('RequestManagement', 30 /* fRank */);

new TemplateMenuNode('UserRequest:Overview', dirname(__FILE__).'/overview.html', $oMyMenuGroup->GetIndex() /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewUserRequest', 'UserRequest', $oMyMenuGroup->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchUserRequests', 'UserRequest', $oMyMenuGroup->GetIndex(), 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('UserRequest:Shortcuts', '', $oMyMenuGroup->GetIndex(), 3 /* fRank */);
$oNode = new OQLMenuNode('UserRequest:MyRequests', 'SELECT UserRequest WHERE agent_id = :current_contact_id AND status NOT IN ("closed","resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('UserRequest:EscalatedRequests', 'SELECT UserRequest WHERE status IN ("escalated_tto", "escalated_ttr")', $oShortcutNode->GetIndex(), 2 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('UserRequest:OpenRequests', 'SELECT UserRequest WHERE status IN ("new", "assigned", "escalated_tto", "escalated_ttr", "frozen", "resolved")', $oShortcutNode->GetIndex(), 3 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));

?>
