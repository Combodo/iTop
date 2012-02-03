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
 * Associated with the metamodel -> MakeQuery/MakeQuerySingleTable
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class QueryBuilderContext
{
	protected $m_oRootFilter;
	protected $m_aClassAliases;
	protected $m_aTableAliases;

	public $m_oQBExpressions;

	public function __construct($oFilter)
	{
		$this->m_oRootFilter = $oFilter;
		$this->m_oQBExpressions = new QueryBuilderExpressions($oFilter->GetCriteria());

		$this->m_aClassAliases = $oFilter->GetJoinedClasses();
		$this->m_aTableAliases = array();
	}

	public function GetRootFilter()
	{
		return $this->m_oRootFilter;
	}

	public function GenerateTableAlias($sNewName, $sRealName)
	{
		return MetaModel::GenerateUniqueAlias($this->m_aTableAliases, $sNewName, $sRealName);
	}

	public function GenerateClassAlias($sNewName, $sRealName)
	{
		return MetaModel::GenerateUniqueAlias($this->m_aClassAliases, $sNewName, $sRealName);
	}
}

?>