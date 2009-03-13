<?

class OqlNormalizeException extends OQLException
{
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

	protected function Parse()
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

	public function ParseQuery()
	{
		$oRes = $this->Parse();
		if (!$oRes instanceof OqlQuery)
		{
			throw new OqlException('Expecting an OQL query', $this->m_sQuery, 0, 0, get_class($oRes), array('OqlQuery'));
		}
		return $oRes;
	}

	public function ParseExpression()
	{
		$oRes = $this->Parse();
		if (!$oRes instanceof Expression)
		{
			throw new OqlException('Expecting an OQL expression', $this->m_sQuery, 0, 0, get_class($oRes), array('Expression'));
		}
		return $oRes;
	}
}

?>
