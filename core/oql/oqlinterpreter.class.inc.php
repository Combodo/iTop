<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Wrapper to execute the parser, lexical analyzer and normalization of an OQL query
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class OqlNormalizeException extends OQLException
{
	public function __construct($sIssue, $sInput, OqlName $oName, $aExpecting = null)
	{
		parent::__construct($sIssue, $sInput, 0, $oName->GetPos(), $oName->GetValue(), $aExpecting);
	}
}
class UnknownClassOqlException extends OqlNormalizeException
{
	public function __construct($sInput, OqlName $oName, $aExpecting = null)
	{
		parent::__construct('Unknown class', $sInput, $oName, $aExpecting);
	}

	public function GetUserFriendlyDescription()
	{
		$sWrongClass = $this->GetWrongWord();
		$sSuggest = self::FindClosestString($sWrongClass, $this->GetSuggestions());

		if ($sSuggest != '')
		{
			return Dict::Format('UI:OQL:UnknownClassAndFix', $sWrongClass, $sSuggest);
		}
		else
		{
			return Dict::Format('UI:OQL:UnknownClassNoFix', $sWrongClass);
		}
	}
}

class OqlInterpreterException extends OQLException
{
}


class OqlInterpreter
{
	public $m_sQuery;

	public function __construct($sQuery)
	{
		$this->m_sQuery = $sQuery;
	}

	// Note: this function is left public for unit test purposes
	public function Parse()
	{
		$oLexer = new OQLLexer($this->m_sQuery);
		$oParser = new OQLParser($this->m_sQuery);

		while($oLexer->yylex())
		{
			$oParser->doParse($oLexer->token, $oLexer->value, $oLexer->getTokenPos());
		}
		$res = $oParser->doFinish();
		return $res;
	}

	public function ParseObjectQuery()
	{
		$oRes = $this->Parse();
		if (!$oRes instanceof OqlObjectQuery)
		{
			throw new OQLException('Expecting an OQL query', $this->m_sQuery, 0, 0, get_class($oRes));
		}
		return $oRes;
	}

	public function ParseExpression()
	{
		$oRes = $this->Parse();
		if (!$oRes instanceof Expression)
		{
			throw new OQLException('Expecting an OQL expression', $this->m_sQuery, 0, 0, get_class($oRes), array('Expression'));
		}
		return $oRes;
	}
}

?>
