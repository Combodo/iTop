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
 * Wrapper to execute the parser, lexical analyzer and normalization of an OQL query
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


class OqlNormalizeException extends OQLException
{
	public function __construct($sIssue, $sInput, OqlName $oName, $aExpecting = null)
	{
		parent::__construct($sIssue, $sInput, 0, $oName->GetPos(), $oName->GetValue(), $aExpecting);
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
