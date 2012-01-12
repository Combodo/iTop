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

MetaModel::RegisterRelation("impacts", array("description"=>"Objects impacted by", "verb_down"=>"impacts", "verb_up"=>"depends on"));
MetaModel::RegisterRelation("depends on", array("description"=>"That impacts ", "verb_down"=>"depends on", "verb_up"=>"impacts"));

// Note (RQ) :
// After 1.0.1, the welcome page and menus have been removed from the application
// and put into a separate module "itop-welcome-itil"
// Until we develop a migration utility, and as would like to preserve the
// capability to upgrade iTop without any manual operation, we have decided to
// implement this dirty workaround that makes it...
//////////////require_once(APPROOT.'modules/itop-welcome-itil/model.itop-welcome-itil.php');

// Starting with iTop 1.2 you can restrict the list of organizations displayed in the drop-down list
// by specifying a query as shown below. Note that this is NOT a security settings, since the
// choice 'All Organizations' will always be available in the menu
ApplicationMenu::SetFavoriteSiloQuery('SELECT Organization');

$oAdminMenu = new MenuGroup('DataAdministration', 70 /* fRank */, 'Organization', UR_ACTION_MODIFY, UR_ALLOWED_YES|UR_ALLOWED_DEPENDS);
$iAdminGroup = $oAdminMenu->GetIndex();

new WebPageMenuNode('Audit', utils::GetAbsoluteUrlAppRoot().'pages/audit.php', $iAdminGroup, 33 /* fRank */);

$oTypologyNode = new TemplateMenuNode('Catalogs', '', $iAdminGroup, 50 /* fRank */);
$iTopology = $oTypologyNode->GetIndex();
new OQLMenuNode('Organization', 'SELECT Organization', $iTopology, 10 /* fRank */, true /* bSearch */);
new OQLMenuNode('Application', 'SELECT Application', $iTopology, 20 /* fRank */);
new OQLMenuNode('DBServer', 'SELECT DBServer', $iTopology, 40 /* fRank */);


$oConfigManagementGroup = new MenuGroup('ConfigManagement', 20 /* fRank */);

// Create an entry, based on a custom template, for the Configuration management overview, under the top-level group
new TemplateMenuNode('ConfigManagementOverview', dirname(__FILE__).'/overview.html', $oConfigManagementGroup->GetIndex(), 0 /* fRank */);


$oContactNode = new TemplateMenuNode('Contact', dirname(__FILE__).'/contacts_menu.html', $oConfigManagementGroup->GetIndex(), 1 /* fRank */);
new NewObjectMenuNode('NewContact', 'Contact', $oContactNode->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchContacts', 'Contact', $oContactNode->GetIndex(), 2 /* fRank */);

new OQLMenuNode('Document', 'SELECT Document', $oConfigManagementGroup->GetIndex(), 2 /* fRank */, true /* bSearch */);
new OQLMenuNode('Location', 'SELECT Location', $oConfigManagementGroup->GetIndex(), 3 /* fRank */, true /* bSearch */);
new OQLMenuNode('Group', 'SELECT Group', $oConfigManagementGroup->GetIndex(), 4 /* fRank */, true /* bSearch */);


$oCINode = new TemplateMenuNode('ConfigManagementCI', dirname(__FILE__).'/cis_menu.html', $oConfigManagementGroup->GetIndex(), 5 /* fRank */);
new NewObjectMenuNode('NewCI', 'FunctionalCI', $oCINode->GetIndex(), 0 /* fRank */);
new SearchMenuNode('SearchCIs', 'FunctionalCI', $oCINode->GetIndex(), 1 /* fRank */);

$oShortcutsNode = new TemplateMenuNode('ConfigManagement:Shortcuts', '', $oConfigManagementGroup->GetIndex(), 6 /* fRank */);
new OQLMenuNode('Server', 'SELECT Server', $oShortcutsNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('NetworkDevice', 'SELECT NetworkDevice', $oShortcutsNode->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Printer', 'SELECT Printer', $oShortcutsNode->GetIndex(), 3 /* fRank */);
new OQLMenuNode('PC', 'SELECT PC', $oShortcutsNode->GetIndex(), 4 /* fRank */);
new OQLMenuNode('BusinessProcess', 'SELECT BusinessProcess', $oShortcutsNode->GetIndex(), 5 /* fRank */);
new OQLMenuNode('ApplicationSolution', 'SELECT ApplicationSolution', $oShortcutsNode->GetIndex(), 6 /* fRank */);

?>
