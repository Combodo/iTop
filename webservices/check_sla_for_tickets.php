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
 * Export data specified by an OQL
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oMyChange = new CMDBChange();
$oMyChange->Set("date", time());
$oMyChange->Set("userinfo", "Automatic updates");
$iChangeId = $oMyChange->DBInsertNoReload();

$oSet = new DBObjectSet(DBObjectSearch::FromOQL('SELECT Incident WHERE escalation_deadline < NOW()'));
while ($oToEscalate = $oSet->Fetch())
{
	$oToEscalate->ApplyStimulus('ev_timeout');
	$oToEscalate->Set('escalation_deadline', null);
	$oToEscalate->DBUpdateTracked($oMyChange);
	echo "<p>ticket ".$oToEscalate->Get('ref')." reached ESCALATION deadline</p>\n";
}

$oSet = new DBObjectSet(DBObjectSearch::FromOQL('SELECT Incident WHERE closure_deadline < NOW()'));
while ($oToEscalate = $oSet->Fetch())
{
	$oToEscalate->ApplyStimulus('ev_close');
	$oToEscalate->Set('closure_deadline', null);
	$oToEscalate->DBUpdateTracked($oMyChange);
	echo "<p>ticket ".$oToEscalate->Get('ref')." reached CLOSURE deadline</p>\n";
}

?>
