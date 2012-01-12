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

$oMyMenuGroup = new MenuGroup('ChangeManagement', 50 /* fRank */);
new TemplateMenuNode('Change:Overview', dirname(__FILE__).'/overview.html', $oMyMenuGroup->GetIndex() /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewChange', 'Change', $oMyMenuGroup->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchChanges', 'Change', $oMyMenuGroup->GetIndex(), 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('Change:Shortcuts', '', $oMyMenuGroup->GetIndex(), 3 /* fRank */);
$oNode = new OQLMenuNode('MyChanges', 'SELECT Change WHERE agent_id = :current_contact_id AND status NOT IN ("closed", "resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('Changes', 'SELECT Change WHERE status != "closed"', $oShortcutNode->GetIndex(), 2 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('WaitingApproval', 'SELECT ApprovedChange WHERE status IN ("plannedscheduled")', $oShortcutNode->GetIndex(), 3 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('WaitingAcceptance', 'SELECT NormalChange WHERE status IN ("new")', $oShortcutNode->GetIndex(), 4 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
?>
