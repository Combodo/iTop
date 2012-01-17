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

$oMyMenuGroup = new MenuGroup('ProblemManagement', 42 /* fRank */); // Will create if it does not exist
$iIndex = $oMyMenuGroup->GetIndex();
new TemplateMenuNode('Problem:Overview', dirname(__FILE__).'/overview.html', $iIndex /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewProblem', 'Problem', $iIndex, 1 /* fRank */);
new SearchMenuNode('SearchProblems', 'Problem', $iIndex, 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('Problem:Shortcuts', '', $iIndex, 5 /* fRank */);
new OQLMenuNode('Problem:MyProblems', 'SELECT Problem WHERE agent_id = :current_contact_id AND status NOT IN ("closed", "resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Problem:OpenProblems', 'SELECT Problem WHERE status IN ("new", "assigned", "resolved")', $oShortcutNode->GetIndex(), 2 /* fRank */);

?>
