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
 * Class iPlugin
 * Management of application plugin 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

interface iApplicationUIExtension
{
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false);
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false);
	public function OnFormSubmit($oObject, $sFormPrefix = '');
	public function OnFormCancel($sTempId); // temp id is made of session_id and transaction_id, it identifies the object in a unique way

	public function EnumUsedAttributes($oObject); // Not yet implemented
	public function GetIcon($oObject); // Not yet implemented
	public function GetHilightClass($oObject);

	public function EnumAllowedActions(DBObjectSet $oSet);
}

interface iApplicationObjectExtension
{
	public function OnIsModified($oObject);
	public function OnCheckToWrite($oObject);
	public function OnCheckToDelete($oObject);
	public function OnDBUpdate($oObject, $oChange = null);
	public function OnDBInsert($oObject, $oChange = null);
	public function OnDBDelete($oObject, $oChange = null);
}

?>
